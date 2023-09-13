<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_notes extends MY_Controller
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
        $this->load->library('parser');

        $this->load->model('fs_model');
        $this->load->model('fs_statements_model');
        $this->load->model('fs_account_category_model');
        $this->load->model('fs_notes_model');

        $this->load->model('fs_replace_content_model');
        $this->load->model('fs_generate_doc_word_model');
    }

    public function save_get_currency_details()
    {
        $form_data = $this->input->post();

        $fs_ntfs_master_currency_ids = $form_data['fs_ntfs_master_currency_id'];
        $fs_master_currency_dp       = $form_data['fs_master_currency_dp'];

        $currency_id        = $form_data['currency_id'];
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $data = $this->fs_model->get_currency_info($currency_id);

        $fs_cm_data = [];

        foreach ($fs_ntfs_master_currency_ids as $key => $value) 
        {
            array_push($fs_cm_data, 
                array(
                        'id' => $fs_ntfs_master_currency_ids[$key],
                        'info' => array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'currency_id'        => $fs_master_currency_dp[$key],
                                    'order_by'           => $key + 1
                                )
                )
            );
        }

        $fs_ntfs_master_currency_data = array(
                                            'table_name'  => 'fs_ntfs_master_currency',
                                            'deleted_ids' => [],
                                            'ntfs_data'   => $fs_cm_data
                                        );

        $fs_ntfs_mc_ids = $this->fs_notes_model->insert_update_tbl_data($fs_ntfs_master_currency_data);

        // add new column for related tables
        $info = array(
                    'condition'   => 'Add',
                    'currency_id' => $currency_id
                );

        $this->update_related_currency_table($fs_company_info_id, $info);

        echo json_encode(array('fs_ntfs_mc_ids' => $fs_ntfs_mc_ids, 'data' => $data));
    }

    public function update_fs_ntfs_master_currency()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];
        $currency_ids       = $form_data['currency_ids'];

        $info = array(
                    'condition'   => 'Update',
                    'currency_ids' => $currency_ids
                );

        // update order_by
        $fs_ntfs_master_currency = $this->db->query("SELECT * FROM fs_ntfs_master_currency WHERE fs_company_info_id=" . $fs_company_info_id);
        $fs_ntfs_master_currency = $fs_ntfs_master_currency->result_array();

        $fnmc_currency_ids = array_column($fs_ntfs_master_currency, 'currency_id');

        $updated_fs_ntfs_master_currency = [];

        foreach ($currency_ids as $key => $value) 
        {
            $fnmc_key = array_search($value, $fnmc_currency_ids);
            $fs_ntfs_master_currency[$fnmc_key]['order_by'] = $key + 1;

            array_push($updated_fs_ntfs_master_currency, 
                array(
                    'id'   => $fs_ntfs_master_currency[$fnmc_key]['id'],
                    'info' => $fs_ntfs_master_currency[$fnmc_key]
                )
            );
        }
        $this->fs_notes_model->update_tbl_data('fs_ntfs_master_currency', $updated_fs_ntfs_master_currency);

        $this->update_related_currency_table($fs_company_info_id, $info);
    }

    public function delete_fs_ntfs_master_currency()
    {
        $form_data = $this->input->post();

        $delete_fs_ntfs_master_currency_id = $form_data['fs_ntfs_master_currency_id'];
        $fs_company_info_id                = $form_data['fs_company_info_id'];

        // retrieve the currency id so that can compare for later use.
        $deleted_currency_id = $this->db->query("SELECT * FROM fs_ntfs_master_currency WHERE id =" . $delete_fs_ntfs_master_currency_id);
        $deleted_currency_id = $deleted_currency_id->result_array();

        $deleted_currency_id = $deleted_currency_id[0]['currency_id'];

        $result = $this->db->delete('fs_ntfs_master_currency', array('id' => $delete_fs_ntfs_master_currency_id));

        // remove data from related currency tables
        $info = array(
                    'condition'   => 'Delete',
                    'currency_id' => $deleted_currency_id
                );
        $this->update_related_currency_table($fs_company_info_id, $info);

        echo json_encode(array('result' => $result));
    }

    public function update_related_currency_table($fs_company_info_id, $info)
    {
        $info['fs_company_info_id'] = $fs_company_info_id;

        // table from "Trade and other receivables"
        $info['tbl_name']   = 'fs_trade_and_other_receivables_ntfs_2';
        $this->update_delete_currency_related_tbl($info);

        // table from "Note 18 Cash and short-term deposits"
        $info['tbl_name']   = 'fs_cash_short_term_deposits_ntfs_2';
        $this->update_delete_currency_related_tbl($info);

        // table from "Note 22 Loans and borrowings"
        $info['tbl_name']   = 'fs_loans_and_borrowings_ntfs_3';
        $this->update_delete_currency_related_tbl($info);

        // table from "Note 24 Trade and other payables"
        $info['tbl_name']   = 'fs_trade_and_other_payables_ntfs_2';
        $this->update_delete_currency_related_tbl($info);

        // table 1
        $info['tbl_body_name']   = 'fs_financial_risk_management_s4_t1';
        $info['tbl_header_name'] = 'fs_financial_risk_management_s4_t1_header';
        $this->update_delete_currency_related_tbl_frm($info);

        // table 2
        $info['tbl_body_name']   = 'fs_financial_risk_management_s4_t2';
        $info['tbl_header_name'] = 'fs_financial_risk_management_s4_t2_header';
        $this->update_delete_currency_related_tbl_frm($info);
    }

    public function update_delete_currency_related_tbl($info)
    {
        $fs_company_info_id = $info['fs_company_info_id'];

        // load currency table 
        $tbl_master_currency_data = $this->db->query("SELECT fm.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency fm
                                                        LEFT JOIN currency c ON c.id = fm.currency_id
                                                        WHERE fm.fs_company_info_id=" . $fs_company_info_id . " ORDER BY fm.order_by");
        $tbl_master_currency_data = $tbl_master_currency_data->result_array();

        $last_index_tbl_mc_data = count($tbl_master_currency_data);

        $data = $this->db->query("SELECT * FROM " . $info['tbl_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $data = $data->result_array();

        if($info['condition'] == 'Add')
        {
            $temp_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'currency_id'        => $info['currency_id'],
                            'order_by'           => $last_index_tbl_mc_data
                        );
            $this->fs_notes_model->insert_tbl_data($info['tbl_name'], array(array('info' => $temp_data)));
        }
        elseif($info['condition'] == 'Update')
        {
            $db_currency_ids = array_column($data, 'currency_id');

            foreach ($tbl_master_currency_data as $key => $value) 
            {
                $found_key = array_search($value['currency_id'], $db_currency_ids);
                $temp_data = array(
                                array(
                                    'id'   => $data[$found_key]['id'],
                                    'info' => array(
                                                    'order_by' => $key + 1
                                                )
                                )
                            );

                $this->fs_notes_model->update_tbl_data($info['tbl_name'], $temp_data);
            }
        }
        elseif($info['condition'] == 'Delete')
        {
            $db_currency_ids = array_column($data, 'currency_id');
            $found_key = array_search($info['currency_id'], $db_currency_ids);

            if(!empty($found_key) || (string)$found_key == '0')
            {
                $this->fs_notes_model->delete_tbl_data($info['tbl_name'], $data[$found_key]['id']);

                // reload data and rearrange ordering
                $updated_data = $this->db->query("SELECT * FROM " . $info['tbl_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $updated_data = $updated_data->result_array();

                $updated_db_currency_ids = array_column($updated_data, 'currency_id');

                $rearranged_order_by_data = [];

                foreach ($tbl_master_currency_data as $key => $value) 
                {
                    $found_key = array_search($value['currency_id'], $updated_db_currency_ids);
                    $temp_data = array(
                                    'id'   => $updated_data[$found_key]['id'],
                                    'info' => array(
                                                    'order_by' => $key + 1
                                                )
                                );

                    array_push($rearranged_order_by_data, $temp_data);
                }

                $this->fs_notes_model->update_tbl_data($info['tbl_name'], $rearranged_order_by_data);
            }
        }
    }

    public function update_tbl()
    {
        $fs_company_info_id = 16;

        $tbl_master_currency_data = $this->db->query("SELECT fm.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency fm
                                                        LEFT JOIN currency c ON c.id = fm.currency_id
                                                        WHERE fm.fs_company_info_id=" . $fs_company_info_id . " ORDER BY fm.order_by");
        $tbl_master_currency_data = $tbl_master_currency_data->result_array();

        $data = [];

        foreach ($tbl_master_currency_data as $key => $value) 
        {
            $temp_data = array(
                    'fs_company_info_id' => $fs_company_info_id,
                    'currency_id' => $value['currency_id'],
                    'order_by' => $key + 1
                );

            array_push($data, array('info' => $temp_data));
        }

        $result = $this->fs_notes_model->insert_tbl_data('fs_trade_and_other_payables_ntfs_2', $data);

        if($result)
        {
            echo "Successfully inserted data!";
        }
        else
        {
            echo "Something went wrong. Please try again later.";
        }
    }

    public function update_delete_currency_related_tbl_frm($info)
    {
        $fs_company_info_id = $info['fs_company_info_id'];

        // load currency table 
        $tbl_master_currency_data = $this->db->query("SELECT fm.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency fm
                                                        LEFT JOIN currency c ON c.id = fm.currency_id
                                                        WHERE fm.fs_company_info_id=" . $fs_company_info_id . " ORDER BY fm.order_by");
        $tbl_master_currency_data = $tbl_master_currency_data->result_array();

        if($info['condition'] == 'Add')
        {
            /* -------------- Header part -------------- */
            $header_row = $this->db->query("SELECT * FROM " . $info['tbl_header_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
            $header_row = $header_row->result_array();

            $header_id           = $header_row[0]['id'];
            $header_titles       = explode(",", $header_row[0]['header_titles']);
            $header_currency_ids = explode(",", $header_row[0]['currency_ids']);

            $header_lr_item_titles       = $header_titles[count($header_titles) - 1]; // temporary save last row item "total" to add back later
            $header_lr_item_currency_ids = ''; // temporary save last row item "total" to add back later

            // header titles
            unset($header_titles[count($header_titles) - 1]);
            array_push($header_titles, $tbl_master_currency_data[count($tbl_master_currency_data)-1]['currency']);
            array_push($header_titles, $header_lr_item_titles);

            // header currency ids
            unset($header_currency_ids[count($header_currency_ids) - 1]);
            array_push($header_currency_ids, $tbl_master_currency_data[count($tbl_master_currency_data)-1]['currency_id']);
            array_push($header_currency_ids, $header_lr_item_currency_ids);

            $header_row[0]['header_titles'] = implode(",",$header_titles);
            $header_row[0]['currency_ids']  = implode(",",$header_currency_ids);

            $header_data = array(
                                array(
                                    'id'   => $header_row[0]['id'],
                                    'info' => array(
                                                    'header_titles' => $header_row[0]['header_titles'],
                                                    'currency_ids'  => $header_row[0]['currency_ids']
                                                )
                                )
                            );

            // print_r($header_data);

            $this->fs_notes_model->update_tbl_data($info['tbl_header_name'], $header_data);
            /* -------------- END OF Header part -------------- */

            /* -------------- Body part -------------- */
            $body_row_data = $this->db->query("SELECT * FROM " . $info['tbl_body_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
            $body_row_data = $body_row_data->result_array();

            $temp_frm_s4_row_item = [];

            foreach ($body_row_data as $key => $value) 
            {
                $row_items = explode(",", $value['row_item']); // get row item 

                $lr_item = $row_items[count($row_items) - 1]; // temporary save last row item value to add back later

                unset($row_items[count($row_items) - 1]);
                array_push($row_items, "");
                array_push($row_items, $lr_item);

                $body_row_data[$key]['row_item'] = implode(",", $row_items);

                $body_data = array(
                                array(
                                    'id'   => $value['id'],
                                    'info' => array(
                                                    'row_item' => $body_row_data[$key]['row_item']
                                                )
                                )
                            );
                $this->fs_notes_model->update_tbl_data($info['tbl_body_name'], $body_data);
            }

            /* -------------- END OF Body part -------------- */
        }
        elseif($info['condition'] == 'Update')
        {
            /* Header table */ 
            $header_row = $this->db->query("SELECT * FROM " . $info['tbl_header_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
            $header_row = $header_row->result_array();

            $header_id           = $header_row[0]['id'];
            $header_titles       = explode(",", $header_row[0]['header_titles']);
            $header_currency_ids = explode(",", $header_row[0]['currency_ids']);

            $updated_header_titles       = [];
            $updated_header_currency_ids = [];
            $rearranged_keys = [];

            // get rearranged index
            foreach ($info['currency_ids'] as $key => $value) 
            {
                $found_key = array_search($value, $header_currency_ids);
                array_push($rearranged_keys, $found_key);
            }

            array_push($rearranged_keys, count($header_currency_ids) - 1);

            foreach ($rearranged_keys as $rk_key => $rk_val) 
            {
                array_push($updated_header_titles, $header_titles[$rk_val]);
                array_push($updated_header_currency_ids, $header_currency_ids[$rk_val]);
            }

            $header_row[0]['header_titles'] = implode(",",$updated_header_titles);
            $header_row[0]['currency_ids']  = implode(",",$updated_header_currency_ids);

            // update to database
            $header_data = array(
                                array(
                                    'id'   => $header_row[0]['id'],
                                    'info' => array(
                                                    'header_titles' => $header_row[0]['header_titles'],
                                                    'currency_ids'  => $header_row[0]['currency_ids']
                                                )
                                )
                            );
            $this->fs_notes_model->update_tbl_data($info['tbl_header_name'], $header_data);
            /* END OF Header table */

            /* Body table */ 
            $body_row_data = $this->db->query("SELECT * FROM " . $info['tbl_body_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
            $body_row_data = $body_row_data->result_array();

            $temp_frm_s4_row_item = [];

            foreach ($body_row_data as $key => $value) 
            {
                $update_row_items = [];
                $row_items = explode(",", $value['row_item']); // get row item 

                foreach ($rearranged_keys as $rk_key => $rk_val) 
                {
                    array_push($update_row_items, $row_items[$rk_val]);
                }

                $body_row_data[$key]['row_item'] = implode(",", $update_row_items);
                $body_data = array(
                                array(
                                    'id'   => $value['id'],
                                    'info' => array(
                                                    'row_item' => $body_row_data[$key]['row_item']
                                                )
                                )
                            );
                $this->fs_notes_model->update_tbl_data($info['tbl_body_name'], $body_data);
            }
            /* END OF Body table */
        }
        elseif($info['condition'] == 'Delete')
        {
            // fs_financial_risk_management_s4_t1 (header)
            $header_row = $this->db->query("SELECT * FROM " . $info['tbl_header_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
            $header_row = $header_row->result_array();

            $header_id           = $header_row[0]['id'];
            $header_titles       = explode(",", $header_row[0]['header_titles']);
            $header_currency_ids = explode(",", $header_row[0]['currency_ids']);

            $matched_col = '';

            // check if data have deleted currency id
            foreach ($header_currency_ids as $key => $value) 
            {
                if($value == $info['currency_id'])
                {
                    $matched_col = $key;
                }
            }

            if(!empty($matched_col) || (string)$matched_col == '0')
            {
                // for header table
                unset($header_titles[$matched_col]);
                array_values($header_titles);

                unset($header_currency_ids[$matched_col]);
                array_values($header_currency_ids);

                $header_row[0]['header_titles'] = implode(",",$header_titles);
                $header_row[0]['currency_ids']  = implode(",",$header_currency_ids);

                // remove id for later use 
                unset($header_row[0]['id']);

                $this->db->where('id', $header_id);
                $result = $this->db->update($info['tbl_header_name'], $header_row[0]); // update data with removed currency info for header table

                // for items table
                $frm_s4_t1_data = $this->db->query("SELECT * FROM " . $info['tbl_body_name'] . " WHERE fs_company_info_id=" . $fs_company_info_id);
                $frm_s4_t1_data = $frm_s4_t1_data->result_array();

                $temp_frm_s4_t1_row_item = [];

                foreach ($frm_s4_t1_data as $key => $value) 
                {
                    $temp_frm_s4_t1_row_item = explode(",", $value['row_item']);

                    unset($temp_frm_s4_t1_row_item[$matched_col]);

                    $frm_s4_t1_data[$key]['row_item'] = implode(",", $temp_frm_s4_t1_row_item);

                    $this->db->where('id', $value['id']);
                    $result = $this->db->update($info['tbl_body_name'], $frm_s4_t1_data[$key]);   // update inner data
                }
            }
        }
    }

    public function partial_note_list()
    {
        $form_data = $this->input->post();

        $fs_company_info_id         = $form_data['fs_company_info_id'];
        $fs_statement_doc_type_id   = $form_data['fs_statement_doc_type_id'];
        // $fs_state_comp_income_id    = $form_data['fs_state_comp_income_id'];
        // $selected_note_num         = $form_data['note_num'];
        $this_note_no               = $form_data['note_no'];
        $fs_fcaro_id_list           = $form_data['fs_fcaro_id_list'];
        $fs_state_comp_id_list      = $form_data['fs_state_comp_id_list'];
        // $fs_ntfs_layout_template_list = $form_data['fs_ntfs_layout_template_list'];
        $this_selected_fs_notes_templates_master_id = $form_data['this_selected_fs_notes_templates_master_id'];
        $current_selected_note_list = $form_data['current_selected_note_list'];    // get selected note from layout

        $deleted_fs_note_templates_master_id = $form_data['deleted_fs_note_templates_master_id'];

        $all_selected_note_list_data = [];

        $this->data['fs_statement_doc_type_id'] = $fs_statement_doc_type_id;

        if($fs_statement_doc_type_id == 1 || $fs_statement_doc_type_id == 2)
        {
            // print_r($form_data);

            if($fs_statement_doc_type_id == 1)
            {
                $document_name = "SCI";
            }
            elseif($fs_statement_doc_type_id == 2)
            {
                $document_name = "SFP";
            }

            $selected_note_from_db  = $this->fs_notes_model->get_selected_note_list($fs_company_info_id, $fs_statement_doc_type_id);    // get selected note from db.

            $all_selected_note_list = array_diff($current_selected_note_list, [0]); // remove 0 from this array, all notes are selected from layout

            // add note list (for notes from other statements)
            foreach ($selected_note_from_db as $selected_note_key => $selected_note_value) 
            {
                if(!in_array($selected_note_value['fs_note_templates_master_id'], $all_selected_note_list))
                {
                    array_push($all_selected_note_list, $selected_note_value['fs_note_templates_master_id']);
                }
            }

            /* ------ update / exclude deleted note from layout ------ */
            foreach ($selected_note_from_db as $key => $value) 
            {
                if($value['fs_list_statement_doc_type_id'] != $fs_statement_doc_type_id)
                {
                    array_push($all_selected_note_list_data, $value);
                }
                else
                {
                    if(!empty($value['fs_categorized_account_round_off_id']))   
                    {
                        $fcaro_key = array_search($value['fs_categorized_account_round_off_id'], $fs_fcaro_id_list);

                        if(!empty($fcaro_key) || (string)$fcaro_key == 0)
                        {
                            if(!empty($current_selected_note_list[$fcaro_key]))
                            {
                                $value['fs_note_templates_master_id'] = $current_selected_note_list[$fcaro_key];

                                array_push($all_selected_note_list_data, $value);
                            }
                        }
                    }
                    else // for fs_state_comp_income link such as "Purchases and related costs"
                    {
                        if(!empty($value['fs_state_comp_income_id']))
                        {
                            $sci_key = array_search($value['fs_state_comp_income_id'], $fs_state_comp_id_list);

                            if(!empty($sci_key) || (string)$sci_key == 0)
                            {
                                $value['fs_note_templates_master_id'] = $current_selected_note_list[$sci_key];

                                array_push($all_selected_note_list_data, $value);
                            }
                        }
                    }
                }
            }
            /* ------ END OF update / exclude deleted note from layout ------ */

            /* ------ include selected note from layout ------ */
            foreach ($fs_fcaro_id_list as $key1 => $value1) 
            {
                if(!in_array($value1, array_column($selected_note_from_db, 'fs_categorized_account_round_off_id')) || $value1 == 0)
                {
                    if(empty($value1))  // check if it is fs_state_comp_id_list
                    {
                        if(!empty($fs_state_comp_id_list[$key1]))
                        {
                            if(!in_array($fs_state_comp_id_list[$key1], array_column($all_selected_note_list_data, 'fs_state_comp_income_id')))
                            {
                                if(!empty($current_selected_note_list[$key1]))
                                {
                                    // print_r(array($fs_state_comp_id_list[$key1]));
                                    $sci_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE id=" . $fs_state_comp_id_list[$key1]);
                                    $sci_data = $sci_data->result_array();

                                    array_push(
                                        $all_selected_note_list_data, 
                                        array(
                                            'fs_note_templates_master_id' => $current_selected_note_list[$key1],
                                            'document_name' => $document_name,
                                            'description' => $sci_data[0]['description']
                                        )
                                    );
                                }
                            }
                        }
                    }
                    else
                    {
                        if(!empty($current_selected_note_list[$key1]))
                        {
                            $fcaro_data = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE id=" . $value1);
                            $fcaro_data = $fcaro_data->result_array();

                            array_push(
                                $all_selected_note_list_data, 
                                array(
                                    'fs_note_templates_master_id' => $current_selected_note_list[$key1],
                                    'document_name' => $document_name,
                                    'description' => $fcaro_data[0]['description']
                                )
                            );

                        }
                    }
                }
            }
            /* ------ END OF include selected note from layout ------ */

            $fs_note_list         = $this->fs_notes_model->get_add_note_list($fs_company_info_id);
            $fs_note_details_list = $this->fs_notes_model->get_fs_note_details_for_state_comp_income($fs_company_info_id, $fs_statement_doc_type_id);

            $fs_note_details_ids = [];

            foreach ($fs_note_list as $fs_nl_key => $fs_nl_value) 
            {
                $inserted = false;

                foreach ($fs_note_details_list as $fs_ndl_key => $fs_ndl_value) 
                {
                    if($fs_nl_value['fs_note_templates_master_id'] == $fs_ndl_value['fs_note_templates_master_id'])
                    {
                        array_push($fs_note_details_ids, $fs_ndl_value['id']);
                        $inserted = true;
                    }
                }

                if(!$inserted)
                {
                    array_push($fs_note_details_ids, '0');
                }
            }

            /* ------ Rearrange note no ------ */

            $temp_ntfs_lytd_ids = [];   // to store all selected note include layout

            // print_r($current_selected_note_list);

            // get all note no from db and layout
            foreach ($current_selected_note_list as $fntm_key => $fntm_value) 
            {
                $temp_lytd = '';

                if(empty($fntm_value))
                {
                    $fntm_value = 0;
                }

                $temp_lytd = $this->db->query("SELECT fntd.fs_ntfs_layout_template_default_id 
                                                FROM fs_note_templates_master fntm 
                                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = fntd.fs_ntfs_layout_template_default_id
                                                WHERE fntm.id = " . $fntm_value);
                $temp_lytd = $temp_lytd->result_array();

                array_push($temp_ntfs_lytd_ids, $temp_lytd[0]['fs_ntfs_layout_template_default_id']);
            }

            $fs_ntfs_layout_template_list = $this->fs_notes_model->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

            $note_no = 1;

            foreach ($fs_ntfs_layout_template_list as $lytl_key => $lytl_value) 
            {
                if(!in_array($lytl_value['fs_note_templates_master_id'], $deleted_fs_note_templates_master_id)) // if not deleted
                {
                    if(in_array($lytl_value['fs_ntfs_layout_template_default_id'], $temp_ntfs_lytd_ids) || $lytl_value['is_checked'])
                    {
                        $fs_ntfs_layout_template_list[$lytl_key]['is_checked']  = 1;
                        $fs_ntfs_layout_template_list[$lytl_key]['note_no']     = $note_no;

                        $note_no++;
                    }
                }
                else
                {
                    if($lytl_value['default_checked'])
                    {
                        $fs_ntfs_layout_template_list[$lytl_key]['is_checked']  = 1;
                        $fs_ntfs_layout_template_list[$lytl_key]['note_no']     = $note_no;
                    }
                    else
                    {
                        $fs_ntfs_layout_template_list[$lytl_key]['is_checked']  = 0;
                        $fs_ntfs_layout_template_list[$lytl_key]['note_no']     = '';
                    }
                }
            }

            /* ------ END OF Rearrange note no ------ */

            $this->data['fs_note_list']              = $this->fs_notes_model->get_add_note_list($fs_company_info_id);

            // print_r($this->data['fs_note_list']);
            $this->data['all_selected_note_list']    = $all_selected_note_list; // from db
            // $this->data['all_selected_note_list_data'] = $selected_note_from_db;
            $this->data['all_selected_note_list_data'] = $all_selected_note_list_data; // from db and layout
            $this->data['this_note_no']              = $this_note_no;   // get from layout the number is the index from layout.
            $this->data['fs_note_details_ids']       = $fs_note_details_ids;
            // $this->data['selected_note']             = $this->fs_notes_model->get_selected_note($fs_company_info_id, $fs_categorized_account_id);

            $this->data['fs_categorized_account_id']                  = $fs_categorized_account_id;
            $this->data['this_selected_fs_notes_templates_master_id'] = $this_selected_fs_notes_templates_master_id;
            $this->data['fs_ntfs_layout_template_list']               = $fs_ntfs_layout_template_list;

            $this->data['deleted_fs_note_templates_master_id'] = $deleted_fs_note_templates_master_id;
        }
        else
        {
            $all_selected_note_list = array_diff($current_selected_note_list, [0]); // remove 0 from this array
            $selected_note_from_db  = $this->fs_notes_model->get_selected_note_list($fs_company_info_id, $fs_statement_doc_type_id);    // get selected note from db.

            $fs_note_list                = $this->fs_notes_model->get_used_add_note_list($fs_company_info_id);
            $all_selected_note_list_data = $fs_note_list;  // add in selected note from db to list data

            // print_r($current_selected_note_list);

            // print_r(array_column($fs_note_list, 'fs_note_details_id'));

            // $temp_fs_note_list = $fs_note_list;

            // foreach ($current_selected_note_list as $csnl_key => $csnl_value) // add in selected note from layout
            // {
            //     $fs_note_list_key = array_search($csnl_value, array_column($fs_note_list, 'fs_note_details_id'));

            //     if(!empty($fs_note_list_key) || (string)$fs_note_list_key == '0')
            //     {
            //         $temp_fs_note_list[$fs_note_list_key]['document_name'] = 'SCF';

            //         array_push($all_selected_note_list_data, $temp_fs_note_list[$fs_note_list_key]);
            //     }
            // }

            // print_r($all_selected_note_list_data);

            // $this->data['this_note_no']              = $this_note_no; // get from layout the number is the index from layout.
            $this->data['all_selected_note_list']       = $all_selected_note_list;
            $this->data['all_selected_note_list_data']  = $all_selected_note_list_data;
            $this->data['this_note_no']                 = $this_note_no; // get from layout the number is the index from layout.
            $this->data['fs_note_list']                 = $fs_note_list;
            $this->data['this_selected_fs_notes_templates_master_id'] = $this_selected_fs_notes_templates_master_id;
            $this->data['fs_ntfs_layout_template_list'] = $this->fs_notes_model->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);
        }

        // print_r($this->data['fs_ntfs_layout_template_list']);
        
        $interface = $this->load->view('/views/financial_statement/template/fs_notes/partial_note_list.php', $this->data);
    }

    public function partial_note_layout()
    {
        $form_data = $this->input->post();

        $fs_company_info_id          = $form_data['fs_company_info_id'];
        $fs_note_templates_master_id = $form_data['fs_note_templates_master_id'];
        $fs_categorized_account_id   = $form_data['fs_categorized_account_id'];

        // retrieve fs_note_templates_default_id
        $fs_note_templates_default_id = $this->fs_notes_model->fs_note_templates_master($fs_note_templates_master_id)[0]['fs_note_templates_default_id'];

        // retrieve layout template
        $fs_note_template = $this->fs_notes_model->get_fs_note_layout($fs_company_info_id, $fs_note_templates_default_id);

        // print_r($fs_note_templates_default_id);

        // get note name / account name
        $fs_categorized_data = $this->fs_account_category_model->get_categorized_data($fs_categorized_account_id);
        $fs_note_name = $fs_categorized_data[0]['description'];

        /* get YEAR END */
        // $current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, );

        // $this->data["last_fye_end"]    = $current_last_year_end['last_fye_end'];
        // $this->data["current_fye_end"] = $current_last_year_end['current_fye_end'];

        // Display tr with account name and value. 
        // * Hidding group column is operated by jquery.
        // if($fs_note_templates_default_id == 1)  // Employee Benefits Expense (S0006)
        // {
        //     $account_list = $this->fs_statements_model->get_account_category_item_list($fs_company_info_id, array('S0006'));
        //     $total_cye    = 0.00;
        //     $total_lye    = 0.00;

        //     foreach($account_list as $key => $value)
        //     {
        //         foreach($value[0]['data'] as $key => $data)
        //         {
        //             $temp_layout =  '<tr>' . 
        //                                 '<td>' . $data['description'] . '</td>' . 
        //                                 '<td class="group_col" style="padding:1%">'. 
        //                                     '<input class="form-control" type="text" name="group_cye_input['. $key .']" style="text-align:right;" value="">' .  // cye value (group)
        //                                 '</td>' . 
        //                                 '<td class="group_col lye_value" style="padding:1%">'. 
        //                                     '<input class="form-control" type="text" name="group_lye_input['. $key .']" style="text-align:right;" value="">' .  // lye value (group)
        //                                 '</td>' . 
        //                                 '<td style="width:1%;"></td>' . 
        //                                 '<td style="text-align:right;">' . 
        //                                     $this->fs_replace_content_model->negative_bracket($data['value']) . // cye value (company)
        //                                 '</td>' . 
        //                                 '<td class="lye_value" style="padding:1%">' . 
        //                                     '<input class="form-control" type="text" style="text-align:right;" value="">' .     // lye value (company)
        //                                 '</td>' . 
        //                             '</tr>';

        //             $total_cye += $data['value'];
        //         }
        //     }
        // }

        // Add hidden input for selected template id
        // $selected_master_template_id = "";

        // // input data to do parse for template
        // $parse_data = array(
        //                 'Current Year End' => $current_last_year_end['current_fye_end'],
        //                 'Last Year End'    => $current_last_year_end['last_fye_end'],
        //                 'Account List'     => $temp_layout,
        //                 'Total for company current year' => $this->fs_replace_content_model->negative_bracket($total_cye),
        //                 'Total for company last year'    => $this->fs_replace_content_model->negative_bracket($total_lye)
        //             );

        // $parse_fs_note_template = $this->parser->parse_string($fs_note_template[0]['layout_template'], $parse_data, TRUE);

        // echo json_encode(array('fs_note_templates_default_id' => $fs_note_templates_default_id, 'fs_note_name' => $fs_note_name, 'layout_template' => $parse_fs_note_template));

        echo json_encode(array('fs_note_templates_default_id' => $fs_note_templates_default_id, 'fs_note_name' => $fs_note_name, 'layout_template' => $fs_note_template));
    }

    public function save_ntfs_layout_template()
    {
        $form_data = $this->input->post();

        $fs_company_info_id  = $form_data['fs_company_info_id'];
        $lyt_id_for_checkbox = array_values($form_data['checkbox_lyt_id']);
        $is_checked          = $form_data['is_checked'];

        $lyt_id         = $form_data['lyt_id'];
        $lyt_section_no = $form_data['lyt_section_no'];

        // FOR RENUMBERING UPDATE
        $data = [];

        foreach ($lyt_id as $key => $value) 
        {
            $q = $this->db->query("SELECT lyt.* 
                                    FROM fs_ntfs_layout_template lyt
                                    LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                    WHERE lyt.id=" . $value . " AND lyt.set_parent != lyt.set_section_no AND lytd.is_roman_section = 0");
            $q = $q->result_array();

            if(count($q) > 0)
            {
                if($q[0]['set_section_no'] != $lyt_section_no[$key])
                {
                    array_push($data, array(
                                        'id' => $q[0]['id'],
                                        'set_section_no' => $lyt_section_no[$key])
                                    );
                }
            }
        }
        /* ------------------ DO NOT DELETE THIS ------------------ */
        // $result = $this->fs_notes_model->update_fs_ntfs_layout_template($fs_company_info_id, $data); // rearrange numbering
        /* ------------------ END OF DO NOT DELETE THIS ------------------ */

        $return_result = array('result' => true);

        // FOR IS_CHECKED UPDATE
        foreach ($lyt_id_for_checkbox as $key => $value) 
        {
            array_push($checkbox_update_list, array('is_checked' => explode(',', $is_checked)[$key]));

            $result = $this->fs_notes_model->update_fs_ntfs_layout_template_content($value, array('is_checked' => explode(',', $is_checked)[$key]));

            if(!$result)
            {
                $return_result['result'] = false;
            }

            if($key == count($lyt_id_for_checkbox) - 1)
            {
                echo json_encode($return_result);
            }
        }
    }

    public function retrieve_ntfs_layout()
    {
        $form_data = $this->input->post();

        $fs_ntfs_layout_template_default_id = $form_data['fs_ntfs_layout_template_default_id'];
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info    = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        $q = $this->db->query('SELECT lytd.layout_content, lytd.section_name 
                                FROM fs_ntfs_layout_template_default lytd
                                LEFT JOIN fs_ntfs_layout_template lyt ON lyt.fs_ntfs_layout_template_default_id = lytd.id
                                WHERE lyt.id=' . $fs_ntfs_layout_template_default_id);
        $q = $q->result_array();

        $q[0]['layout_content'] = $this->fs_replace_content_model->ntfs_replace_toggle($q[0]['layout_content'], $q[0]['section_name'], $fs_company_info_id);    // replace the content of ntfs depends on conditions

        $pattern = "/{{[^}}]*}}/";
        $template = $q[0]['layout_content'];
        preg_match_all($pattern, $q[0]['layout_content'], $matches_part);

        $template = $this->fs_replace_content_model->replace_special_sign($matches_part[0], $template); 
        $template = $this->fs_replace_content_model->replace_toggle($matches_part[0], $template, "Note to the financial statements", $fs_company_info_id); 
        $layout_content = $this->fs_replace_content_model->replace_verbs_plural($template, "Note to the financial statements", $fs_company_info_id); 

        // echo $layout_content; 

        file_put_contents('themes/default/views/financial_statement/template/fs_notes/dynamic_content.php', $layout_content);

        if($q[0]['section_name'] == "Revenue")
        {
            $revenue_options = $this->db->query("SELECT * FROM fs_list_sub_revenue_content WHERE set_default = 1");
            $revenue_options = $revenue_options->result_array();

            $revenue_selected = $this->db->query("SELECT * FROM fs_sub_revenue WHERE fs_company_info_id=" . $fs_company_info_id);
            $revenue_selected = $revenue_selected->result_array();

            $this->data['revenue_options']  = $revenue_options;
            $this->data['revenue_selected'] = $revenue_selected;
        }
        elseif($q[0]['section_name'] == "Group accounting - content")
        {
            $fs_subsi_not_consolidated = $this->fs_notes_model->get_fs_subsi_not_consolidated($fs_company_info_id);

            // create dropdown list for subsidiary
            $subsi_list = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries iis 
                                                WHERE iis.fs_company_info_id=" . $fs_company_info_id . ' AND parent_id = 0');
            $subsi_list = $subsi_list->result_array();

            $dp_subsi_list = array();
            // $dp_subsi_list[''] = ' -- Select a subsidiary -- ';

            foreach ($subsi_list as $key => $value) 
            {
                array_push($dp_subsi_list, array('value' => $value['id'], 'text'  => $value['name_of_entity']));
                // $dp_subsi_list[$value['id']] = $value['name_of_entity'];
            }

            // create dropdown list of fs_subsidiary_type
            $subsidiary_type_list = $this->db->query("SELECT * FROM fs_subsidiary_type");
            $subsidiary_type_list = $subsidiary_type_list->result_array();

            $dp_subsidiary_type_list = array();
            $dp_subsidiary_type_list[''] = ' -- Select a subsidiary type -- ';

            foreach ($subsidiary_type_list as $key => $value) 
            {
                $dp_subsidiary_type_list[$value['id']] = $value['type'];
            }

            $this->data['dp_country_list']  = $this->fs_model->get_dp_country_list();
            $this->data['dp_subsi_list'] = $dp_subsi_list;
            $this->data['dp_subsidiary_type_list'] = $dp_subsidiary_type_list;
            $this->data['fs_subsi_not_consolidated'] = $fs_subsi_not_consolidated;
        }
        elseif($q[0]['section_name'] == "Share-based payment transactions")
        {
            $eb = $this->fs_notes_model->get_fs_employee_benefits($fs_company_info_id);

            $this->data['eb'] = $eb;
        }
        elseif(($q[0]['section_name'] == "Intangible assets" && $final_report_type != 1)|| $q[0]['section_name'] == "Other intangible assets")
        {
            $ia = $this->fs_notes_model->get_fs_sub_intangible_assets($fs_company_info_id);

            $this->data['ia'] = $ia;
        }
        elseif($q[0]['section_name'] == "Research and development costs")
        {
            $ia_info = $this->fs_notes_model->get_fs_sub_intangible_assets_info($fs_company_info_id);

            $this->data['ia_info'] = $ia_info;
        }
        elseif($q[0]['section_name'] == "Investment properties")
        {
            $model_dp = array();

            $model_dp["Cost"] = "Cost";
            $model_dp["Fair Value"] = "Fair Value";

            $this->data['model_dp'] = $model_dp;
            $this->data['ip'] = $this->fs_notes_model->get_fs_investment_properties($fs_company_info_id);
        }
        elseif($q[0]['section_name'] == "Property, plant and equipment")
        {
            $sub_ppe = $this->fs_notes_model->get_fs_sub_ppe($fs_company_info_id);
            $sub_ppe_info = $this->fs_notes_model->get_fs_sub_ppe_info($fs_company_info_id);

            $this->data['dp_depreciation_method_list'] = $this->fs_notes_model->get_dp_fs_list_depreciation_method();
            $this->data['sub_ppe'] = $sub_ppe;
            $this->data['sub_ppe_info'] = $sub_ppe_info;
        }
        elseif($q[0]['section_name'] == "Inventories")
        {
            $inventories_info = $this->fs_notes_model->get_fs_sub_inventories_info($fs_company_info_id);

            $this->data['dp_fs_list_net_realizable_value_list'] = $this->fs_notes_model->get_dp_fs_list_net_realizable_value();
            $this->data['inventories_info'] = $inventories_info;
        }
        elseif($q[0]['section_name'] == "Provision")
        {
            $p = $this->fs_notes_model->get_fs_sub_provision($fs_company_info_id);

            $this->data['p'] = $p;
        }
        elseif($q[0]['section_name'] == "EMPLOYEE BENEFITS EXPENSE")
        {
            // for table
            $fs_statements_list = $this->fs_statements_model->get_fs_statement_json();
            $fs_statements_list = json_decode(json_encode($fs_statements_list), true);

            $er_key = array_search("Employee benefits expense", array_column($fs_statements_list['ntfs'][0]['sections'], 'title'));
            $er_account_code = $fs_statements_list['ntfs'][0]['sections'][$er_key]['account_category_code'][0];

            $fca_id   = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($er_account_code));
            $ebe_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id);

            // for checkbox with textarea
            $ebe = $this->fs_notes_model->get_employee_benefits_expense_ntfs($fs_company_info_id);

            $this->data['ebe_list'] = $ebe_list;
            $this->data['ebe_ntfs'] = $ebe;
        }
        elseif($q[0]['section_name'] == "PROFIT BEFORE TAX")
        {
            $this->data['fs_company_info'] = $fs_company_info;

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

            $this->data['profit_b4_tax_ntfs'] = $profit_b4_tax_ntfs;
        }
        elseif($q[0]['section_name'] == "TAX EXPENSE" || $q[0]['section_name'] == "TAXATION")
        {
            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['te']              = $this->fs_notes_model->get_fs_tax_expense_ntfs($fs_company_info_id);
            $this->data['te_info']         = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id, 2);

            if($final_report_type == 1)
            {
                $fs_tax_expense_reconciliation = $this->fs_notes_model->get_fs_tax_expense_reconciliation($fs_company_info_id);

                if(count($fs_tax_expense_reconciliation) > 0)
                {
                    $this->data['ter'] = $fs_tax_expense_reconciliation;
                }
                else // insert default list of tax expense reconciliation
                {
                    $result_insert_list = $this->fs_notes_model->insert_list_default_tax_expense_reconciliation($fs_company_info_id);
                    $this->data['ter']  = $this->fs_notes_model->get_fs_tax_expense_reconciliation($fs_company_info_id);
                }

                // for P/L before tax
                $pl_b4_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3 AND in_use=1");
                $pl_b4_data = $pl_b4_data->result_array();

                $this->data['pl_b4_data'] = $pl_b4_data;     
            }
            else
            {
                $q[0]['section_name'] == "TAXATION";

                $this->data['te_info_1'] = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id, 1);
            }
        }
        elseif($q[0]['section_name'] == "INVESTMENT IN SUBSIDIARIES")
        {
            $this->data['fs_company_info'] = $fs_company_info;
            $fs_investment_in_subsidiaries_ntfs = $this->fs_notes_model->get_tbl_name_leftjoin_tbl_list($fs_company_info_id, "fs_investment_in_subsidiaries_ntfs", "fs_list_investment_in_subsidiaries");
            // $this->data['te_info']         = $this->fs_notes_model->get_fs_tax_expense_ntfs_info($fs_company_info_id);

            // $fs_tax_expense_reconciliation = $this->fs_notes_model->get_fs_tax_expense_reconciliation($fs_company_info_id);

            if(count($fs_investment_in_subsidiaries_ntfs) > 0)
            {
                $this->data['iis'] = $fs_investment_in_subsidiaries_ntfs;
            }
            else // insert default list of tax expense reconciliation
            {
                $result_insert_list = $this->fs_notes_model->insert_list_default($fs_company_info_id, "fs_investment_in_subsidiaries_ntfs", "fs_list_investment_in_subsidiaries");
                $this->data['iis']  = $this->fs_notes_model->get_tbl_name_leftjoin_tbl_list($fs_company_info_id, "fs_investment_in_subsidiaries_ntfs", "fs_list_investment_in_subsidiaries");
            }

            // for table 2
            $iis_t2 = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . ' ORDER BY order_by');
            $iis_t2 = $iis_t2->result_array();

            $this->data['iis_t2'] = $iis_t2;
            $this->data['dp_country_list']  = $this->fs_model->get_dp_country_list();
        }
        elseif($q[0]['section_name'] == "Composition of the Group") // INVESTMENT IN SUBSIDIARIES (i)
        {
            $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

            // for table 2
            $iis_t2 = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . ' ORDER BY order_by');
            $iis_t2 = $iis_t2->result_array();

            // for textarea part
            $iis_p1_info = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p1_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $iis_p1_info = $iis_p1_info->result_array();

            $this->data['iis_p1_info'] = $iis_p1_info;
            $this->data['iis_t2_p1'] = $iis_t2;
            $this->data['dp_country_list']  = $this->fs_model->get_dp_country_list();
        }
        elseif($q[0]['section_name'] == "Summarized financial information about subsidiary with material NCI") // INVESTMENT IN SUBSIDIARIES (ii)
        {
            $iis_p2_t1_titles_data = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p2_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $iis_p2_t1_titles_data = $iis_p2_t1_titles_data->result_array();

            $iis_p2_t1_title_id = 0;

            if(count($iis_p2_t1_titles_data) > 0)
            {
                $iis_p2_t1_title_id = $iis_p2_t1_titles_data[0]['id'];
            }

            if(!empty($iis_p2_t1_titles_data[0]['header_titles']))
            {
                $iis_p2_t1_titles = explode(',', $iis_p2_t1_titles_data[0]['header_titles']);
            }
            else
            {
                $iis_p2_t1_titles = ['Completed investment properties', 'Investment property under construction', 'Total'];
            }

            // row item
            $iis_p2_t1_current_row = [];
            $iis_p2_t1_prior_row   = [];

            $iis_p2_t1_row_data = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p2_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $iis_p2_t1_row_data = $iis_p2_t1_row_data->result_array();

            if(count($iis_p2_t1_row_data) > 0) 
            {
                foreach ($iis_p2_t1_row_data as $key => $value) 
                {
                    if($value['section'] == 'current')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($iis_p2_t1_current_row, $value);
                    }
                    elseif($value['section'] == 'prior')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($iis_p2_t1_prior_row, $value);
                    }
                }
            }
            else // setup default value
            {
                $temp_iis_p2_t1_row_data = $this->build_investment_in_subsidiaries_p2_t1($fs_company_info, $iis_p2_t1_titles); 

                $iis_p2_t1_current_row  = $temp_iis_p2_t1_row_data['current_data'];
                $iis_p2_t1_prior_row    = $temp_iis_p2_t1_row_data['prior_data'];
            }

            $this->data['iis_p2_t1_title_id']    = $iis_p2_t1_title_id;
            $this->data['iis_p2_t1_titles']      = $iis_p2_t1_titles;
            $this->data['iis_p2_t1_current_row'] = $iis_p2_t1_current_row;
            $this->data['iis_p2_t1_prior_row']   = $iis_p2_t1_prior_row;
        }
        elseif($q[0]['section_name'] == "INVESTMENT IN ASSOCIATES")
        {
            $this->data['fs_company_info'] = $fs_company_info;

            if($final_report_type != 1)
            {
                $this->data['iia_info'] = $this->fs_notes_model->get_fs_investment_in_associates_info($fs_company_info_id);
            }
            else
            {
                $this->data['dp_country_list']  = $this->fs_model->get_dp_country_list();

                $iia_t1 = $this->db->query("SELECT * FROM fs_investment_in_associates_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $iia_t1 = $iia_t1->result_array();

                $iia_t2 = $this->db->query("SELECT * FROM fs_investment_in_associates_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $iia_t2 = $iia_t2->result_array();

                $iia_t3 = $this->db->query("SELECT * FROM fs_investment_in_associates_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $iia_t3 = $iia_t3->result_array();

                $this->data['iia_t1'] = $iia_t1;
                $this->data['iia_t2'] = $iia_t2;
                $this->data['iia_t3'] = $iia_t3;
            }
        }
        elseif($q[0]['section_name'] == "INVESTMENT IN JOINT VENTURE")
        {
            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['dp_country_list']  = $this->fs_model->get_dp_country_list();

            if($final_report_type == 1)
            {
                $iijv_t1 = $this->db->query("SELECT * FROM fs_investment_in_joint_venture_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $iijv_t1 = $iijv_t1->result_array();

                $iijv_t2 = $this->db->query("SELECT * FROM fs_investment_in_joint_venture_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $iijv_t2 = $iijv_t2->result_array();

                $this->data['iijv_t1'] = $iijv_t1;
                $this->data['iijv_t2'] = $iijv_t2;
            }
            else
            {
                $this->data['iijv_info'] = $this->fs_notes_model->get_fs_investment_in_joint_venture_info($fs_company_info_id);
            }
        }
        elseif($q[0]['section_name'] == "INTANGIBLE ASSETS")
        {
            if($final_report_type == 1)
            {
                // get default list 
                $ia_options = $this->db->query("SELECT * FROM fs_list_intangible_assets_content WHERE set_default = 1 AND section_name != '' AND fs_list_final_report_type_id=1");
                $ia_options = $ia_options->result_array();

                if(count($ia_options) > 0)
                {
                    foreach ($ia_options as $key => $value) 
                    {
                        $ia_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($ia_options[$key]['content'], 'Intangible assets default content', $fs_company_info_id);
                    }
                }

                // get saved item
                $ia_info_2_selected = $this->db->query("SELECT ia2.* 
                                                    FROM fs_intangible_assets_info_2 ia2 
                                                    LEFT JOIN fs_list_intangible_assets_content pd ON pd.id = ia2.fs_list_intangible_assets_content_id
                                                    WHERE ia2.fs_company_info_id=" . $fs_company_info_id . " AND pd.section_name != '' ORDER BY pd.order_by");
                $ia_info_2_selected = $ia_info_2_selected->result_array();

                $this->data['ia_options'] = $ia_options;
                $this->data['ia_info_2_selected'] = $ia_info_2_selected; 
            }
            else
            {
                $ia_ntfs_info = $this->db->query("SELECT * FROM fs_intangible_assets_info WHERE fs_company_info_id=" . $fs_company_info_id);
                $ia_ntfs_info = $ia_ntfs_info->result_array();
            }

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

            // row item
            $cost_row        = [];
            $accumulated_row = [];
            $carrying_row    = [];

            $cost_last_row        = [];
            $accumulated_last_row = [];
            $carrying_last_row    = [];

            $ia_row_data = $this->db->query("SELECT * FROM fs_intangible_assets_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ia_row_data = $ia_row_data->result_array();

            foreach ($ia_row_data as $key => $value) 
            {
                if($value['section'] == 'cost')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($cost_row, $value);
                }
                elseif($value['section'] == 'accumulated')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($accumulated_row, $value);
                }
                elseif($value['section'] == 'carrying')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($carrying_row, $value);
                }
                elseif($value['section'] == 'last cost')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($cost_last_row, $value);
                }
                elseif($value['section'] == 'last accumulated')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($accumulated_last_row, $value);
                }
                elseif($value['section'] == 'last carrying')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($carrying_last_row, $value);
                }
            }

            // insert default value
            if(count($cost_row) == 0)
            {
                $temp_cost_row_data = $this->build_intangible_assets_t1($fs_company_info, 'cost', $ia_t1_titles);

                $cost_row      = $temp_cost_row_data['row_data'];
                $cost_last_row = $temp_cost_row_data['last_row'];
            }

            // accumulated 
            if(count($accumulated_row) == 0)
            {
                $temp_accumulated_row_data = $this->build_intangible_assets_t1($fs_company_info, 'accumulated', $ia_t1_titles);

                $accumulated_row      = $temp_accumulated_row_data['row_data'];
                $accumulated_last_row = $temp_accumulated_row_data['last_row'];
            }

            if(count($carrying_row) == 0)
            {
                $temp_carrying_row_data = $this->build_intangible_assets_t1($fs_company_info, 'carrying', $ia_t1_titles);

                if(count($carrying_last_row) == 0)
                {
                    $carrying_row      = $temp_carrying_row_data['row_data'];
                    $carrying_last_row = $temp_carrying_row_data['last_row'];
                }
                else
                {
                    $carrying_last_row[0]['section'] = 'carrying';

                    $carrying_row      = $carrying_last_row;
                    $carrying_last_row = $temp_carrying_row_data['last_row'];
                }
            }

            // print_r($carrying_row);

            $this->data['ia_title_id'] = $ia_titles_data[0]['id'];
            $this->data['ia_t1_titles']= $ia_t1_titles;
            $this->data['ia_row_data'] = $ia_row_data;

            $this->data['cost_row']             = $cost_row;
            $this->data['accumulated_row']      = $accumulated_row;
            $this->data['carrying_row']         = $carrying_row;

            $this->data['cost_last_row']        = $cost_last_row;
            $this->data['accumulated_last_row'] = $accumulated_last_row;
            $this->data['carrying_last_row']    = $carrying_last_row;

            $this->data['ia_ntfs_info'] = $ia_ntfs_info; // for textarea
        }   
        elseif($q[0]['section_name'] == "INSURED BENEFITS")
        {
            $this->data['fs_company_info'] = $fs_company_info;

            if($final_report_type != 1)
            {
                $this->data['ib_info'] = $this->fs_notes_model->get_fs_insured_benefits_info($fs_company_info_id);
            }
            else
            {
                $ib_t1 = $this->db->query("SELECT * FROM fs_insured_benefits_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $ib_t1 = $ib_t1->result_array();

                $this->data['ib_t1'] = $ib_t1;
            }
        }
        elseif($q[0]['section_name'] == "INVESTMENT PROPERTIES")
        {
            if($final_report_type == 1)
            {
                /* for table 1 */
                $ip_t1_titles_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
                $ip_t1_titles_data = $ip_t1_titles_data->result_array();

                if(!empty($ip_t1_titles_data[0]['header_titles']))
                {
                    $ip_t1_titles = explode(',', $ip_t1_titles_data[0]['header_titles']);
                }
                else
                {
                    $ip_t1_titles = array('Completed investment properties', 'Investment property under construction', 'Total');
                }

                // row item
                $cost_row        = [];
                $accumulated_row = [];
                $carrying_row    = [];

                $cost_last_row        = [];
                $accumulated_last_row = [];
                $carrying_last_row    = [];

                $ip_t1_row_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $ip_t1_row_data = $ip_t1_row_data->result_array();

                foreach ($ip_t1_row_data as $key => $value) 
                {
                    if($value['section'] == 'cost')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($cost_row, $value);
                    }
                    elseif($value['section'] == 'accumulated')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($accumulated_row, $value);
                    }
                    elseif($value['section'] == 'carrying')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($carrying_row, $value);
                    }
                    elseif($value['section'] == 'last cost')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($cost_last_row, $value);
                    }
                    elseif($value['section'] == 'last accumulated')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($accumulated_last_row, $value);
                    }
                    elseif($value['section'] == 'last carrying')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($carrying_last_row, $value);
                    }
                }

                // insert default value
                if(count($cost_row) == 0)
                {
                    $temp_cost_row_data = $this->build_intangible_assets_t1($fs_company_info, 'cost', $ip_t1_titles);

                    $cost_row      = $temp_cost_row_data['row_data'];
                    $cost_last_row = $temp_cost_row_data['last_row'];
                }

                // accumulated 
                if(count($accumulated_row) == 0)
                {
                    $temp_accumulated_row_data = $this->build_intangible_assets_t1($fs_company_info, 'accumulated', $ip_t1_titles);

                    $accumulated_row      = $temp_accumulated_row_data['row_data'];
                    $accumulated_last_row = $temp_accumulated_row_data['last_row'];
                }

                if(count($carrying_row) == 0)
                {
                    $temp_carrying_row_data = $this->build_intangible_assets_t1($fs_company_info, 'carrying', $ip_t1_titles);

                    $carrying_row      = $temp_carrying_row_data['row_data'];

                    if(count($carrying_last_row) == 0)
                    {
                        $carrying_last_row = $temp_carrying_row_data['last_row'];
                    }
                }

                $this->data['ip_t1_title_id'] = $ip_t1_titles_data[0]['id'];
                $this->data['ip_t1_titles']   = $ip_t1_titles;
                $this->data['ip_t1_row_data'] = $ip_t1_row_data;

                $this->data['cost_row']             = $cost_row;
                $this->data['accumulated_row']      = $accumulated_row;
                $this->data['carrying_row']         = $carrying_row;

                $this->data['cost_last_row']        = $cost_last_row;
                $this->data['accumulated_last_row'] = $accumulated_last_row;
                $this->data['carrying_last_row']    = $carrying_last_row;
                /* END OF for table 1 */

                // for table 2
                $ip_t2_row = $this->fs_notes_model->get_fs_investment_properties_t2($fs_company_info_id);
                $this->data['ip_t2_row'] = $ip_t2_row;

                // for table 4
                $ip_t4_row = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_4 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
                $ip_t4_row = $ip_t4_row->result_array();
                $this->data['ip_t4_row'] = $ip_t4_row;

                /* --- for checkbox --- */
                // for checkbox (default)
                $ip_options = $this->db->query("SELECT * FROM fs_list_investment_properties_content WHERE set_default = 1 AND section_name != '' AND fs_list_final_report_type_id=1");
                $ip_options = $ip_options->result_array();

                if(count($ip_options) > 0)
                {
                    foreach ($ip_options as $key => $value) 
                    {
                        $ip_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($ip_options[$key]['content'], 'Investment properties default content', $fs_company_info_id);
                    }
                }
                $this->data['ip_options'] = $ip_options;

                // for checkbox (saved)
                $ip_info_selected = $this->db->query("SELECT p.* 
                                                        FROM fs_investment_properties_info p 
                                                        LEFT JOIN fs_list_investment_properties_content pd ON pd.id = p.fs_list_investment_properties_content_id
                                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND pd.section_name != '' ORDER BY pd.order_by");
                $ip_info_selected = $ip_info_selected->result_array();
                $this->data['ip_info_selected'] = $ip_info_selected;
                /* --- END OF for checkbox --- */
            }

            // for table 3
            $ip_t3_titles_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ip_t3_titles_data = $ip_t3_titles_data->result_array();

            $ip_t3_title_id = 0;

            if(count($ip_t3_titles_data) > 0)
            {
                $ip_t3_title_id = $ip_t3_titles_data[0]['id'];
            }

            if(!empty($ip_t3_titles_data[0]['header_titles']))
            {
                $ip_t3_titles = explode(',', $ip_t3_titles_data[0]['header_titles']);
            }
            else
            {
                $ip_t3_titles = ['Completed investment properties', 'Investment property under construction', 'Total'];
            }

            // row item
            $ip_t3_row      = [];
            $ip_t3_last_row = [];

            $ip_t3_row_data = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ip_t3_row_data = $ip_t3_row_data->result_array();

            if(count($ip_t3_row_data) > 0) 
            {
                foreach ($ip_t3_row_data as $key => $value) 
                {
                    if($value['section'] == 'normal')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($ip_t3_row, $value);
                    }
                    elseif($value['section'] == 'last')
                    {
                        $value['row_item'] = explode(",", $value['row_item']);
                        array_push($ip_t3_last_row, $value);
                    }
                }
            }
            else // setup default value
            {
                $temp_ip_t3_row_data = $this->build_intangible_assets_t1($fs_company_info, 'normal', $ip_t3_titles);

                $ip_t3_row      = $temp_ip_t3_row_data['row_data'];
                $ip_t3_last_row = $temp_ip_t3_row_data['last_row'];
            }
            
            $this->data['ip_t3_title_id'] = $ip_t3_title_id;
            $this->data['ip_t3_titles']   = $ip_t3_titles;
            $this->data['ip_t3_row']      = $ip_t3_row;
            $this->data['ip_t3_last_row'] = $ip_t3_last_row;

            // for table 5
            $ip_t5_ntfs = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_5 WHERE fs_company_info_id= " . $fs_company_info_id . " ORDER BY order_by");
            $ip_t5_ntfs = $ip_t5_ntfs->result_array();

            $this->data['ip_t5_ntfs'] = $ip_t5_ntfs;
        }
        elseif($q[0]['section_name'] == "PROPERTY, PLANT AND EQUIPMENT") 
        {
            $ppe_t1_titles_data = $this->db->query("SELECT * FROM fs_ppe_ntfs_1_header WHERE fs_company_info_id=" . $fs_company_info_id);
            $ppe_t1_titles_data = $ppe_t1_titles_data->result_array();

            if(!empty($ppe_t1_titles_data[0]['header_titles']))
            {
                $ppe_t1_titles = explode(',', $ppe_t1_titles_data[0]['header_titles']);
            }
            else
            {
                $ppe_t1_titles = array('', '');
            }

            // row item
            $cost_row        = [];
            $accumulated_row = [];
            $carrying_row    = [];

            $cost_last_row        = [];
            $accumulated_last_row = [];
            $carrying_last_row    = [];

            $ppe_t1_row_data = $this->db->query("SELECT * FROM fs_ppe_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $ppe_t1_row_data = $ppe_t1_row_data->result_array();

            foreach ($ppe_t1_row_data as $key => $value) 
            {
                if($value['section'] == 'cost')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($cost_row, $value);
                }
                elseif($value['section'] == 'accumulated')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($accumulated_row, $value);
                }
                elseif($value['section'] == 'carrying')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($carrying_row, $value);
                }
                elseif($value['section'] == 'last cost')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($cost_last_row, $value);
                }
                elseif($value['section'] == 'last accumulated')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($accumulated_last_row, $value);
                }
                elseif($value['section'] == 'last carrying')
                {
                    $value['row_item'] = explode(",", $value['row_item']);
                    array_push($carrying_last_row, $value);
                }
            }

            // insert default value
            if(count($cost_row) == 0)
            {
                $temp_cost_row_data = $this->build_intangible_assets_t1($fs_company_info, 'cost', $ppe_t1_titles);

                $cost_row      = $temp_cost_row_data['row_data'];
                $cost_last_row = $temp_cost_row_data['last_row'];
            }

            // accumulated 
            if(count($accumulated_row) == 0)
            {
                $temp_accumulated_row_data = $this->build_intangible_assets_t1($fs_company_info, 'accumulated', $ppe_t1_titles);

                $accumulated_row      = $temp_accumulated_row_data['row_data'];
                $accumulated_last_row = $temp_accumulated_row_data['last_row'];
            }

            if(count($carrying_row) == 0)
            {
                $temp_carrying_row_data = $this->build_intangible_assets_t1($fs_company_info, 'carrying', $ppe_t1_titles);

                $carrying_row      = $temp_carrying_row_data['row_data'];

                if(count($carrying_last_row) == 0)
                {
                    $carrying_last_row = $temp_carrying_row_data['last_row'];
                }
            }

            $this->data['ppe_t1_title_id'] = $ppe_t1_titles_data[0]['id'];
            $this->data['ppe_t1_titles']   = $ppe_t1_titles;
            $this->data['ppe_t1_row_data'] = $ppe_t1_row_data;

            $this->data['cost_row']             = $cost_row;
            $this->data['accumulated_row']      = $accumulated_row;
            $this->data['carrying_row']         = $carrying_row;

            $this->data['cost_last_row']        = $cost_last_row;
            $this->data['accumulated_last_row'] = $accumulated_last_row;
            $this->data['carrying_last_row']    = $carrying_last_row;

            if($final_report_type == 1)
            {
                $condition = ' AND (fs_list_final_report_type_id=4 OR fs_list_final_report_type_id=1)';   
            }
            elseif($final_report_type == 2 || $final_report_type == 3)
            {
                $condition = ' AND (fs_list_final_report_type_id=4 OR fs_list_final_report_type_id=5)';  
            }

            $ppe_option_nt = $this->db->query("SELECT * FROM fs_list_ppe_content WHERE set_default = 1 AND section_name = ''" . $condition); // nt = no title
            $ppe_option_nt = $ppe_option_nt->result_array();

            if(count($ppe_option_nt) > 0)
            {
                $ppe_option_nt[0]['content'] = $this->fs_replace_content_model->replace_verbs_plural($ppe_option_nt[0]['content'], 'PPE default content', $fs_company_info_id);
            }

            $ppe_options = $this->db->query("SELECT * FROM fs_list_ppe_content WHERE set_default = 1 AND section_name != ''" . $condition);
            $ppe_options = $ppe_options->result_array();

            if(count($ppe_options) > 0)
            {
                foreach ($ppe_options as $key => $value) 
                {
                    $ppe_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($ppe_options[$key]['content'], 'PPE default content', $fs_company_info_id);
                }
            }

            // $ppe_info_nt_content = $this->db->query("SELECT p.* 
            //                                         FROM fs_ppe_info p 
            //                                         LEFT JOIN fs_list_ppe_content pd ON pd.id = p.fs_list_ppe_content_id
            //                                         WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND pd.section_name = '' ORDER BY pd.order_by");
            // $ppe_info_nt_content = $ppe_info_nt_content->result_array();

            $ppe_info_selected = $this->db->query("SELECT p.* 
                                                    FROM fs_ppe_info p 
                                                    LEFT JOIN fs_list_ppe_content pd ON pd.id = p.fs_list_ppe_content_id
                                                    WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND pd.section_name != '' ORDER BY pd.order_by");
            $ppe_info_selected = $ppe_info_selected->result_array();

            $this->data['ppe_option_nt']        = $ppe_option_nt;
            // $this->data['ppe_info_nt_content']  = $ppe_info_nt_content;
            $this->data['ppe_options']          = $ppe_options;
            $this->data['ppe_info_selected']    = $ppe_info_selected;
        }
        elseif($q[0]['section_name'] == "AVAILABLE FOR SALE")
        {   
            $afs_t1_p1 = $this->db->query("SELECT * FROM fs_available_for_sale_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " AND part=1 ORDER BY order_by");
            $afs_t1_p1 = $afs_t1_p1->result_array();

            $afs_t1_p2 = $this->db->query("SELECT * FROM fs_available_for_sale_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " AND part=2 ORDER BY order_by");
            $afs_t1_p2 = $afs_t1_p2->result_array();

            $afs_info_data = $this->db->query("SELECT * FROM fs_available_for_sale_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $afs_info_data = $afs_info_data->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['afs_t1_p1'] = $afs_t1_p1;
            $this->data['afs_t1_p2'] = $afs_t1_p2;
            $this->data['afs_info'] = $afs_info_data;
        }
        elseif($q[0]['section_name'] == "INVENTORIES")
        {
            $inv_t1_data = $this->db->query("SELECT * FROM fs_inventories_ntfs_1 WHERE fs_company_info_id = " . $fs_company_info_id);
            $inv_t1_data = $inv_t1_data->result_array();

            $inv_info = $this->db->query("SELECT * FROM fs_inventories_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $inv_info = $inv_info->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['inv_t1'] = $inv_t1_data;
            $this->data['inv_info'] = $inv_info;
        }
        elseif($q[0]['section_name'] == "CONTRACT ASSETS AND CONTRACT LIABILITIES")
        {
            $cacl_data = $this->db->query("SELECT * FROM fs_contract_assets_and_contract_liabilities_ntfs WHERE fs_company_info_id = " . $fs_company_info_id);
            $cacl_data = $cacl_data->result_array();

            $cacl_info = $this->db->query("SELECT * FROM fs_contract_assets_and_contract_liabilities_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $cacl_info = $cacl_info->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['cacl']            = $cacl_data;
            $this->data['cacl_info']       = $cacl_info;
        }
        elseif($q[0]['section_name'] == "TRADE AND OTHER RECEIVABLES")
        {
            $fs_note_title_json = json_decode(json_encode($this->fs_generate_doc_word_model->get_fs_note_title_json()), true);
            $fnt_ntac_key = array_search('Note 16 - Trade and other receivables', array_column($fs_note_title_json['note_title_account_code'], 'note_title'));
            $tor_account_code = $fs_note_title_json['note_title_account_code'][$fnt_ntac_key]['account_code'];

            $tor_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($tor_account_code));
            $tor_data   = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $tor_fca_id);

            $tor = $this->db->query("SELECT tor2.*, c.name AS `description`
                                        FROM fs_trade_and_other_receivables_ntfs_2 tor2
                                        LEFT JOIN currency c ON c.id = tor2.currency_id
                                        WHERE tor2.fs_company_info_id=" . $fs_company_info_id . " ORDER BY tor2.order_by");
            $tor = $tor->result_array();

            $this->data['tor']    = $tor;
            $this->data['tor_t1'] = $tor_data[0]['child_array'];

            // for full FRS 
            if($final_report_type == 1)
            {
                // for table 3
                $tor_t3_data = $this->db->query("SELECT tor.*, tord.description 
                                                FROM fs_trade_and_other_receivables_ntfs_3 tor
                                                LEFT JOIN fs_list_trade_and_other_receivables_ntfs_3 tord ON tord.id = tor.fs_list_trade_and_other_receivables_ntfs_3_id
                                                WHERE tor.fs_company_info_id = " . $fs_company_info_id . " ORDER BY tor.order_by");
                $tor_t3_data = $tor_t3_data->result_array();

                if(count($tor_t3_data) == 0) // load default
                {
                    $tor_t3_list = $this->db->query("SELECT * FROM fs_list_trade_and_other_receivables_ntfs_3 WHERE in_used=1 ORDER BY order_by");
                    $tor_t3_list = $tor_t3_list->result_array();

                    // setup array pattern
                    foreach ($tor_t3_list as $key => $value) 
                    {
                        array_push($tor_t3_data, 
                            array(
                                'id' =>  '',
                                'fs_company_info_id' => $fs_company_info_id,
                                'fs_list_trade_and_other_receivables_ntfs_3_id' => $value['id'],
                                'description'               => $value['description'],
                                'value'                     => $value['value'],
                                'company_end_prev_ye_value' => $value['company_end_prev_ye_value'],
                                'group_end_this_ye_value'   => $value['group_end_this_ye_value'],
                                'group_end_prev_ye_value'   => $value['group_end_prev_ye_value']
                            )
                        );
                    }
                }

                $this->data['tor_t3'] = $tor_t3_data;

                // for table 4
                $tor_t4_data = $this->db->query("SELECT tor.*, tord.description, tord.part, tord.is_title
                                                FROM fs_trade_and_other_receivables_ntfs_4 tor
                                                LEFT JOIN fs_list_trade_and_other_receivables_ntfs_4 tord ON tord.id = tor.fs_list_trade_and_other_receivables_ntfs_4_id
                                                WHERE tor.fs_company_info_id = " . $fs_company_info_id . " ORDER BY tor.order_by");
                $tor_t4_data = $tor_t4_data->result_array();

                if(count($tor_t4_data) == 0) // load default
                {
                    $tor_t4_list = $this->db->query("SELECT * FROM fs_list_trade_and_other_receivables_ntfs_4 WHERE set_default=1 ORDER BY order_by");
                    $tor_t4_list = $tor_t4_list->result_array();

                    // setup array pattern
                    foreach ($tor_t4_list as $key => $value) 
                    {
                        array_push($tor_t4_data, 
                            array(
                                'id' =>  '',
                                'fs_company_info_id' => $fs_company_info_id,
                                'fs_list_trade_and_other_receivables_ntfs_4_id' => $value['id'],
                                'part'                      => $value['part'],
                                'is_title'                  => $value1['is_title'],
                                'description'               => $value['description'],
                                'value'                     => '',
                                'company_end_prev_ye_value' => '',
                                'group_end_this_ye_value'   => '',
                                'group_end_prev_ye_value'   => ''
                            )
                        );
                    }
                }

                $this->data['tor_t4'] = $tor_t4_data;

                // for checkbox (1, 2)
                $tor_info_selected = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_trade_and_other_receivables_title_id`, lbd.is_fixed
                                                            FROM fs_trade_and_other_receivables_info lb
                                                            LEFT JOIN fs_list_trade_and_other_receivables_content lbd ON lbd.id = lb.fs_list_trade_and_other_receivables_content_id
                                                            LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lbd.fs_list_trade_and_other_receivables_title_id
                                                            WHERE lb.fs_company_info_id=" . $fs_company_info_id . ' ORDER BY lb.order_by');
                $tor_info_selected = $tor_info_selected->result_array();
                $this->data['tor_info_selected'] = $tor_info_selected;



                $tor_1_2_options = $this->db->query("SELECT lb.*, lbt.section_name, lbt.id AS `fs_list_trade_and_other_receivables_title_id`
                                                        FROM fs_list_trade_and_other_receivables_content lb
                                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lb.fs_list_trade_and_other_receivables_title_id
                                                        WHERE lb.id IN (1,2)
                                                        ORDER BY lb.order_by");
                $tor_1_2_options = $tor_1_2_options->result_array();

                $this->data['tor_1_2_options'] = $tor_1_2_options;

                // for checkbox (3)
                $tor_3_options = $this->db->query("SELECT lb.*, lbt.section_name, lbt.id AS `fs_list_trade_and_other_receivables_title_id`
                                                        FROM fs_list_trade_and_other_receivables_content lb
                                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lb.fs_list_trade_and_other_receivables_title_id
                                                        WHERE lb.id = 3
                                                        ORDER BY lb.order_by");
                $tor_3_options = $tor_3_options->result_array();

                $this->data['tor_3_options'] = $tor_3_options;

                // for checkbox (4, 5)
                $tor_4_5_options = $this->db->query("SELECT lb.*, lbt.section_name, lbt.id AS `fs_list_trade_and_other_receivables_title_id`
                                                        FROM fs_list_trade_and_other_receivables_content lb
                                                        LEFT JOIN fs_list_trade_and_other_receivables_title lbt ON lbt.id = lb.fs_list_trade_and_other_receivables_title_id
                                                        WHERE lb.id NOT IN (1,2,3)
                                                        ORDER BY lb.order_by");
                $tor_4_5_options = $tor_4_5_options->result_array();

                $this->data['tor_4_5_options'] = $tor_4_5_options;
            }

            $this->data['fs_company_info'] = $fs_company_info;
        }
        elseif($q[0]['section_name'] == "OTHER CURRENT ASSETS")
        {
            $oca = $this->db->query("SELECT * FROM fs_other_current_assets_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $oca = $oca->result_array(); 

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['oca'] = $oca;
        }
        elseif($q[0]['section_name'] == "CASH AND SHORT-TERM DEPOSITS")
        {
            $template_arr = array(
                                'id'                        => '',
                                'description'               => '',
                                'part'                      => '',
                                'group_end_this_ye_value'   => '',
                                'group_end_prev_ye_value'   => '',
                                'value'                     => '',
                                'company_end_prev_ye_value' => ''
                            ); 

            // for table 1
            $csd_t1 = $this->db->query("SELECT * FROM fs_cash_short_term_deposits_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $csd_t1 = $csd_t1->result_array(); 

            // set default value fot table 1
            if(count($csd_t1) == 0)
            {
                // row 1
                $template_arr['description'] = "Bank and cash balances";
                array_push($csd_t1, $template_arr);

                // row 2
                $template_arr['description'] = "Short term deposits";
                array_push($csd_t1, $template_arr);
            }

            // for table 2
            $csd_t2 = $this->db->query("SELECT csd2.*, c.name AS `description`
                                        FROM fs_cash_short_term_deposits_ntfs_2 csd2
                                        LEFT JOIN currency c ON c.id = csd2.currency_id
                                        WHERE csd2.fs_company_info_id=" . $fs_company_info_id . " ORDER BY csd2.order_by");
            $csd_t2 = $csd_t2->result_array(); 

            // for table 3
            $csd_t3 = $this->db->query("SELECT * FROM fs_cash_short_term_deposits_ntfs_3 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $csd_t3 = $csd_t3->result_array(); 

            // set default value fot table 3
            if(count($csd_t3) == 0)
            {
                // row 1
                $template_arr['part']        = 1;
                $template_arr['description'] = "Bank and cash balances";
                array_push($csd_t3, $template_arr);

                // row 2
                $template_arr['description'] = "Short term deposits";
                array_push($csd_t3, $template_arr);

                // row 3
                $template_arr['part']        = 2;
                $template_arr['description'] = "Less: Short term deposits pledged";
                array_push($csd_t3, $template_arr);
            }

            // for textarea
            $csd_info = $this->db->query("SELECT info.*
                                            FROM fs_cash_short_term_deposits_info info
                                            INNER JOIN 
                                            (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                            FROM fs_cash_short_term_deposits_info
                                            WHERE fs_company_info_id = " . $fs_company_info_id . ") info1
                                            ON info1.fs_company_info_id = info.fs_company_info_id AND info1.max_date = info.created_at
                                            WHERE info.fs_company_info_id = " . $fs_company_info_id);
            $csd_info = $csd_info->result_array();

            $this->data['fs_company_info'] = $fs_company_info;

            $this->data['csd_t1'] = $csd_t1;
            $this->data['csd_t2'] = $csd_t2;
            $this->data['csd_t3'] = $csd_t3;

            $this->data['csd_info'] = $csd_info;
        }
        elseif($q[0]['section_name'] == "SHARE CAPITAL")
        {
            if($final_report_type != 1)
            {
                $this->data['sc_info'] = $this->fs_notes_model->get_fs_share_capital_info($fs_company_info_id);
            }
        }
        elseif($q[0]['section_name'] == "DEFERRED TAX LIABILITIES")
        {
            $dtl = $this->db->query("SELECT * FROM fs_deferred_tax_liabilities_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $dtl = $dtl->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['dtl'] = $dtl;
        }
        elseif($q[0]['section_name'] == "LOANS AND BORROWINGS")
        {
            $lb_info_selected_s = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_loans_and_borrowings_title_id`, lbd.is_fixed
                                                FROM fs_loans_and_borrowings_info lb
                                                LEFT JOIN fs_list_loans_and_borrowings lbd ON lbd.id = lb.fs_list_loans_and_borrowings_id
                                                LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lbd.fs_list_loans_and_borrowings_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . ' AND lbd.fs_list_loans_and_borrowings_title_id = 0 ORDER BY lb.order_by');
            $lb_info_selected_s = $lb_info_selected_s->result_array(); 

            if(count($lb_info_selected_s) == 0)
            {
                $lb_options_s = $this->db->query("SELECT lb.*, lbt.section_name
                                            FROM fs_list_loans_and_borrowings lb
                                            LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lb.fs_list_loans_and_borrowings_title_id 
                                            WHERE lb.fs_list_loans_and_borrowings_title_id = 0
                                            ORDER BY lb.order_by");
                $lb_options_s = $lb_options_s->result_array();

                array_push($lb_info_selected_s, 
                    array(
                        'id'                              => '',
                        'fs_company_info_id'              => $fs_company_info_id,
                        'fs_list_loans_and_borrowings_id' => $lb_options_s[0]['id'],
                        'content'                         => $lb_options_s[0]['content'],
                        'is_checked'                      => 1,
                        'order_by'                        => '',
                        'is_fixed'                        => $lb_options_s[0]['is_fixed']
                    )
                );
            }

            $lb_options = $this->db->query("SELECT lb.*, lbt.section_name, lbt.id AS `fs_list_loans_and_borrowings_title_id`
                                            FROM fs_list_loans_and_borrowings lb
                                            LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lb.fs_list_loans_and_borrowings_title_id 
                                            WHERE lb.fs_list_loans_and_borrowings_title_id <> 0
                                            ORDER BY lb.order_by");
            $lb_options = $lb_options->result_array();

            $lb_info_selected = $this->db->query("SELECT lb.*, lbt.id AS `fs_list_loans_and_borrowings_title_id`, lbd.is_fixed
                                                FROM fs_loans_and_borrowings_info lb
                                                LEFT JOIN fs_list_loans_and_borrowings lbd ON lbd.id = lb.fs_list_loans_and_borrowings_id
                                                LEFT JOIN fs_list_loans_and_borrowings_title lbt ON lbt.id = lbd.fs_list_loans_and_borrowings_title_id
                                                WHERE lb.fs_company_info_id=" . $fs_company_info_id . ' AND lbd.fs_list_loans_and_borrowings_title_id <> 0 ORDER BY lb.order_by');
            $lb_info_selected = $lb_info_selected->result_array();

            $lb_t1 = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 0 ORDER BY order_by");
            $lb_t1 = $lb_t1->result_array();

            $lb_t1_ls = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_1 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 1 ORDER BY order_by");
            $lb_t1_ls = $lb_t1_ls->result_array();

            $lb_t3 = $this->db->query("SELECT lb3.*, c.name AS `description`
                                        FROM fs_loans_and_borrowings_ntfs_3 lb3
                                        LEFT JOIN currency c ON c.id = lb3.currency_id
                                        WHERE lb3.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lb3.order_by");
            $lb_t3 = $lb_t3->result_array();

            $lb_t4 = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_4 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 0 ORDER BY order_by");
            $lb_t4 = $lb_t4->result_array();

            $lb_t4_ls = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_4 WHERE fs_company_info_id=" . $fs_company_info_id . " AND is_last_section = 1 ORDER BY order_by");
            $lb_t4_ls = $lb_t4_ls->result_array();

            if($final_report_type == 1)
            {
                $lb_t2 = $this->fs_notes_model->get_fs_loans_and_borrowings_t2($fs_company_info_id);
                $lb_t5 = $this->fs_notes_model->get_fs_loans_and_borrowings_t5($fs_company_info_id);

                $this->data['lb_t2'] = $lb_t2;
                $this->data['lb_t5'] = $lb_t5;
            }

            $this->data['fs_company_info']    = $fs_company_info;
            $this->data['lb_options']         = $lb_options;
            $this->data['lb_info_selected']   = $lb_info_selected;
            $this->data['lb_info_selected_s'] = $lb_info_selected_s;
            $this->data['lb_t1']              = $lb_t1;
            $this->data['lb_t1_ls']           = $lb_t1_ls;
            $this->data['lb_t3']              = $lb_t3;
            $this->data['lb_t4']              = $lb_t4;
            $this->data['lb_t4_ls']           = $lb_t4_ls;
        }
        elseif($q[0]['section_name'] == "PROVISION")
        {
            $pro_t1 = $this->db->query("SELECT * FROM fs_provision_ntfs WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
            $pro_t1 = $pro_t1->result_array();

            // checkbox part
            if($final_report_type == 1)
            {
                $pro_info_selected = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_provision_info p 
                                        LEFT JOIN fs_list_provision_content pd ON pd.id = p.fs_list_provision_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_provision_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=1) ORDER BY pd.order_by");
                $pro_info_selected = $pro_info_selected->result_array();
            }
            else
            {
                $pro_info_selected = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_provision_info p 
                                        LEFT JOIN fs_list_provision_content pd ON pd.id = p.fs_list_provision_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_provision_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=5) ORDER BY pd.order_by");
                $pro_info_selected = $pro_info_selected->result_array();
            }

            // default list for checkbox
            $pro_options = $this->db->query("SELECT * FROM fs_list_provision_content WHERE set_default = 1 AND section_name != ''" . $condition);
            $pro_options = $pro_options->result_array();

            if(count($pro_options) > 0)
            {
                foreach ($pro_options as $key => $value) 
                {
                    $pro_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($pro_options[$key]['content'], 'Provision default content', $fs_company_info_id);
                }
            }

            $this->data['pro_t1']            = $pro_t1;
            $this->data['pro_info_selected'] = $pro_info_selected;
            $this->data['pro_options']       = $pro_options; 
        }
        elseif($q[0]['section_name'] == "TRADE AND OTHER PAYABLES")
        {
            $fs_note_title_json = $this->fs_generate_doc_word_model->get_fs_note_title_json();
            $fnt_ntac_key = array_search('Note 24 - Trade and other payables', array_column((array)$fs_note_title_json->note_title_account_code, 'note_title'));
            $top_account_code = $fs_note_title_json->note_title_account_code[$fnt_ntac_key]->account_code;

            $top_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, array($top_account_code));
            $top_data   = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $top_fca_id);

            $top = $this->db->query("SELECT top.*, c.name AS `description`
                                        FROM fs_trade_and_other_payables_ntfs_2 top
                                        LEFT JOIN currency c ON c.id = top.currency_id
                                        WHERE top.fs_company_info_id=" . $fs_company_info_id . " ORDER BY top.order_by");
            $top = $top->result_array();

            // checkbox part
            if($final_report_type == 1)
            {
                $top_info_selected = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_trade_and_other_payables_info p 
                                        LEFT JOIN fs_list_trade_and_other_payables_content pd ON pd.id = p.fs_list_trade_and_other_payables_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_trade_and_other_payables_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=1) ORDER BY pd.order_by");
                $top_info_selected = $top_info_selected->result_array();
            }
            else
            {
                $top_info_selected = $this->db->query("SELECT p.*, pd.section_name 
                                        FROM fs_trade_and_other_payables_info p 
                                        LEFT JOIN fs_list_trade_and_other_payables_content pd ON pd.id = p.fs_list_trade_and_other_payables_content_id
                                        WHERE p.fs_company_info_id=" . $fs_company_info_id . " AND p.fs_list_trade_and_other_payables_content_id != 5 AND (pd.fs_list_final_report_type_id=4 OR pd.fs_list_final_report_type_id=5) ORDER BY pd.order_by");
                $top_info_selected = $top_info_selected->result_array();
            }

            // default list for checkbox
            $top_options = $this->db->query("SELECT * FROM fs_list_trade_and_other_payables_content WHERE set_default = 1 AND section_name != ''" . $condition);
            $top_options = $top_options->result_array();

            if(count($top_options) > 0)
            {
                foreach ($top_options as $key => $value) 
                {
                    $top_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($top_options[$key]['content'], 'Trade and other payables default content', $fs_company_info_id);
                }
            }

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['top']    = $top;
            $this->data['top_t1'] = $top_data[0]['child_array'];

            $this->data['top_info_selected'] = $top_info_selected;
            $this->data['top_options']       = $top_options; 
        }
        elseif($q[0]['section_name'] == "OTHER CURRENT LIABILITIES")
        {
            $ocl = $this->db->query("SELECT * FROM fs_other_current_liabilities_ntfs WHERE fs_company_info_id=" . $fs_company_info_id);
            $ocl = $ocl->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['ocl'] = $ocl;
        }
        elseif($q[0]['section_name'] == "Sale and purchase of goods and services")
        {
            $rpt_ntfs_1 = $this->db->query("SELECT * FROM fs_related_party_transactions_ntfs_1 WHERE fs_company_info_id= " . $fs_company_info_id . " ORDER BY order_by");
            $rpt_ntfs_1 = $rpt_ntfs_1->result_array();

            $this->data['fs_company_info'] = $fs_company_info;
            $this->data['rpt_ntfs_1']      = $rpt_ntfs_1;
        }
        elseif($q[0]['section_name'] == "Compensation of key management personnel")
        {
            $rpt_options = $this->db->query("SELECT * FROM fs_list_related_party_transactions_content ORDER BY order_by");
            $rpt_options = $rpt_options->result_array();

            $rpt_info_selected = $this->db->query("SELECT rpt.* 
                                                FROM fs_related_party_transactions_info rpt
                                                LEFT JOIN fs_list_related_party_transactions_content rptd ON rptd.id = rpt.fs_list_related_party_transactions_content_id
                                                WHERE rpt.fs_company_info_id=" . $fs_company_info_id);
            $rpt_info_selected = $rpt_info_selected->result_array();

            $this->data['rpt_options']        = $rpt_options;
            $this->data['rpt_info_selected']  = $rpt_info_selected;

            // print_r($rpt_info_selected);
        }
        elseif($q[0]['section_name'] == "Operating lease commitments – as lessee") // Sub (ii) under "COMMITMENTS"
        {
            // for textbox
            $c2_info = $this->db->query("SELECT * FROM fs_commitment_2_ntfs_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $c2_info = $c2_info->result_array();

            // for table 1
            $c2_t1_data = $this->db->query("SELECT * FROM fs_commitment_2_ntfs_1 WHERE fs_company_info_id = " . $fs_company_info_id);
            $c2_t1_data = $c2_t1_data->result_array();

            // for table 2
            $c2_t2_list = $this->db->query("SELECT * FROM fs_list_commitment_2_ntfs_2 WHERE in_used = 1");
            $c2_t2_list = $c2_t2_list->result_array();

            $c2_t2_data = $this->db->query("SELECT * FROM fs_commitment_2_ntfs_2 WHERE fs_company_info_id = " . $fs_company_info_id);
            $c2_t2_data = $c2_t2_data->result_array();

            $this->data['fs_company_info'] = $fs_company_info;

            $this->data['c2_info']    = $c2_info;

            $this->data['c2_t1']      = $c2_t1_data;
            $this->data['c2_t2_list'] = $c2_t2_list;
            $this->data['c2_t2']      = $c2_t2_data;
        }
        elseif($q[0]['section_name'] == "Operating lease commitments – as lessor") // Sub (ii) under "COMMITMENTS"
        {
            // for textbox
            $c3_info = $this->db->query("SELECT * FROM fs_commitment_3_ntfs_info WHERE fs_company_info_id = " . $fs_company_info_id);
            $c3_info = $c3_info->result_array();

            // for default description
            $c2_t2_list = $this->db->query("SELECT * FROM fs_list_commitment_2_ntfs_2 WHERE in_used = 1");
            $c2_t2_list = $c2_t2_list->result_array();

            // for table 1 (Full FRS only)
            $c3_t1_data = $this->db->query("SELECT * FROM fs_commitment_3_ntfs_1 WHERE fs_company_info_id = " . $fs_company_info_id);
            $c3_t1_data = $c3_t1_data->result_array();

            // for table 2
            $c3_t2_data = $this->db->query("SELECT * FROM fs_commitment_3_ntfs_2 WHERE fs_company_info_id = " . $fs_company_info_id);
            $c3_t2_data = $c3_t2_data->result_array();

            $this->data['fs_company_info'] = $fs_company_info;

            $this->data['c3_info']    = $c3_info;

            $this->data['c3_t1']      = $c3_t1_data; 
            $this->data['c2_t2_list'] = $c2_t2_list;
            $this->data['c3_t2']      = $c3_t2_data;
        }
        elseif($q[0]['section_name'] == "Contingent liabilities") // Sub (ii) under "CONTINGENCIES"
        {
            $cl_options = $this->db->query("SELECT * FROM fs_list_contingencies_content WHERE part = 1 ORDER BY order_by");
            $cl_options = $cl_options->result_array();

            $cl_info_selected = $this->db->query("SELECT ci.*, cd.section_name 
                                                FROM fs_contingencies_info ci
                                                LEFT JOIN fs_list_contingencies_content cd ON cd.id = ci.fs_list_contingencies_content_id
                                                WHERE ci.fs_company_info_id=" . $fs_company_info_id . ' AND cd.part = 1 ORDER BY ci.order_by');
            $cl_info_selected = $cl_info_selected->result_array();

            if(count($cl_options) > 0)
            {
                foreach ($cl_options as $key => $value) 
                {
                    $cl_options[$key]['content'] = $this->fs_replace_content_model->replace_verbs_plural($cl_options[$key]['content'], 'Contingencies default content', $fs_company_info_id);
                }
            }

            $this->data['cl_options'] = $cl_options;
            $this->data['cl_info_selected']  = $cl_info_selected;
        }
        elseif($q[0]['section_name'] == "Contingent assets") // Sub (ii) under "CONTINGENCIES"
        {
            $c_options = $this->db->query("SELECT * FROM fs_list_contingencies_content WHERE part = 2 ORDER BY order_by");
            $c_options = $c_options->result_array();

            $c_info_selected = $this->db->query("SELECT ci.* 
                                                FROM fs_contingencies_info ci
                                                LEFT JOIN fs_list_contingencies_content cd ON cd.id = ci.fs_list_contingencies_content_id
                                                WHERE ci.fs_company_info_id=" . $fs_company_info_id . ' AND cd.part = 2 ORDER BY ci.order_by');
            $c_info_selected = $c_info_selected->result_array();

            $this->data['c_options']        = $c_options;
            $this->data['c_info_selected']  = $c_info_selected;
        }
        /* Note 29 Financial Risk Management */
        elseif($q[0]['section_name'] == "Liquidity risk") // 29.2 Liquidity risk
        {
            $this->data['fs_company_info'] = $fs_company_info;

            // for group table
            $frm_s2_group = $this->fs_notes_model->get_fs_fs_financial_risk_management_ntfs_s2($fs_company_info_id, 'group');
            $this->data['frm_s2_group'] = $frm_s2_group;

            // for company table
            $frm_s2_company = $this->fs_notes_model->get_fs_fs_financial_risk_management_ntfs_s2($fs_company_info_id, 'company');
            $this->data['frm_s2_company'] = $frm_s2_company;
        }
        elseif($q[0]['section_name'] == "Interest rate risk") // 29.3 Interest rate risk
        {
            $this->data['fs_company_info'] = $fs_company_info;

            // for checkbox part
            $frm_s3_info = $this->fs_notes_model->get_fs_financial_risk_management_s3_info($fs_company_info_id);
            $this->data['frm_s3_info'] = $frm_s3_info;

            // for "floating rate instruments" table
            $frm_s3_floating = $this->fs_notes_model->get_fs_financial_risk_management_ntfs_s3($fs_company_info_id, 'floating');
            $this->data['frm_s3_floating'] = $frm_s3_floating;

            // for "fixed rate instruments" table
            $frm_s3_fixed = $this->fs_notes_model->get_fs_financial_risk_management_ntfs_s3($fs_company_info_id, 'fixed');
            $this->data['frm_s3_fixed'] = $frm_s3_fixed;
        }
        elseif($q[0]['section_name'] == "Foreign currency risk") // 29.4 Foreign currency risk
        {
            $this->data['fs_company_info'] = $fs_company_info;

            /* ------------ currency part ------------ */
            $master_currency_item = $this->db->query("SELECT mc.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency mc
                                                        LEFT JOIN currency c ON c.id = mc.currency_id
                                                        WHERE mc.fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by");
            $master_currency_item = $master_currency_item->result_array();

            $display_currency_name = '';

            if(count($master_currency_item) > 0)
            {
                foreach ($master_currency_item as $key => $value) 
                {
                    if($key != 0)
                    {
                        if(count($master_currency_item) - 1 == $key)
                        {
                            $display_currency_name .= ' and ';
                        }
                        else
                        {
                            $display_currency_name .= ', ';
                        }
                    }

                    $display_currency_name .= $value['name'] . ' (' . $value['currency'] . ')';
                }
            }
            else
            {
                $display_currency_name = '___________';
            }

            $this->data['display_currency_name'] = $frm_s4_t1_data['display_currency_name'];
            /* ------------ END OF currency part ------------ */

            // Table 1 
            $frm_s4_t1_data = $this->build_financial_risk_mgmt_s4(1, $fs_company_info_id);
            
            // print_r($frm_s4_t1_data['frm_s4_titles']);

            $this->data['frm_s4_t1_title_id']    = $frm_s4_t1_data['frm_s4_title_id'];
            $this->data['frm_s4_t1_titles']      = $frm_s4_t1_data['frm_s4_titles'];
            // $this->data['frm_s4_t1_titles_data'] = $frm_s4_t1_data['frm_s4_titles_data'];

            $this->data['frm_s4_t1']    = $frm_s4_t1_data['frm_s4'];
            $this->data['frm_s4_t1_fp'] = $frm_s4_t1_data['frm_s4_fp'];
            $this->data['frm_s4_t1_fc'] = $frm_s4_t1_data['frm_s4_fc'];

            // Table 2
            $frm_s4_t2_data = $this->build_financial_risk_mgmt_s4(2, $fs_company_info_id);
            
            $this->data['frm_s4_t2_title_id']    = $frm_s4_t2_data['frm_s4_title_id'];
            $this->data['frm_s4_t2_titles']      = $frm_s4_t2_data['frm_s4_titles'];
            // $this->data['frm_s4_t2_titles_data'] = $frm_s4_t2_data['frm_s4_titles_data'];

            $this->data['frm_s4_t2']    = $frm_s4_t2_data['frm_s4'];
            $this->data['frm_s4_t2_fp'] = $frm_s4_t2_data['frm_s4_fp'];
            $this->data['frm_s4_t2_fc'] = $frm_s4_t2_data['frm_s4_fc'];

            // Table 3
            // retrieve table 1 data
            $frm_s4_t1_group = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t1 WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t1_group = $frm_s4_t1_group->result_array();

            // retrieve table 2 data
            $frm_s4_t2_company = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t2 WHERE fs_company_info_id=" . $fs_company_info_id);
            $frm_s4_t2_company = $frm_s4_t2_company->result_array();

            $frm_s4_t3_company = [];

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

            // print_r(array($row_items_g_prior, $row_items_g_current, $row_items_c_prior, $row_items_c_current));

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

                array_push($frm_s4_t3_company, 
                    array(
                        'currency_id' => $value['currency_id'], 
                        'description' => $value['name'], 
                        'value_ty_g'  => $total_current_g * (10/100), 
                        'value_ly_g'  => $total_prior_g * (10/100), 
                        'value_ty_c'  => $total_current_c * (10/100),
                        'value_ly_c'  => $total_prior_c * (10/100)
                    )
                );
            }

            $this->data['frm_s4_t3_company'] = $frm_s4_t3_company;
        }
        /* END OF Note 29 Financial Risk Management */
        elseif ($q[0]['section_name'] == "FAIR VALUE OF ASSETS") 
        {
            $this->data['fs_company_info'] = $fs_company_info;

            $fva_g_p1 = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='group' AND part=1 ORDER BY order_by");
            $fva_g_p1 = $fva_g_p1->result_array();

            $fva_g_p2 = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='group' AND part=2 ORDER BY order_by");
            $fva_g_p2 = $fva_g_p2->result_array();

            $fva_c_p1 = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='company' AND part=1 ORDER BY order_by");
            $fva_c_p1 = $fva_c_p1->result_array();

            $fva_c_p2 = $this->db->query("SELECT * FROM fs_fair_value_of_assets_ntfs WHERE fs_company_info_id = " . $fs_company_info_id . " AND group_company='company' AND part=2 ORDER BY order_by");
            $fva_c_p2 = $fva_c_p2->result_array();

            // set default value
            $default_temp = array(
                                'id'                        => '',
                                'is_title'                  => false,
                                'description'               => '',
                                'value'                     => '',
                                'company_end_prev_ye_value' => '',
                                'group_end_this_ye_value'   => '',
                                'group_end_prev_ye_value'   => ''
                            );

            $fva_cy_template = [];
            $fva_ly_template = [];

            // for current year template
            $default_temp['description'] = "As at " . $fs_company_info[0]['current_fye_end'];
            $default_temp['is_title']    = true;
            array_push($fva_cy_template, $default_temp);

            $default_temp['is_title']    = false; // reset back to not title
            $default_temp['description'] = "Quoted shares";
            array_push($fva_cy_template, $default_temp);

            $default_temp['description'] = "Unquoted shares";
            array_push($fva_cy_template, $default_temp);


            // for last year template
            $default_temp['description'] = "As at " . $fs_company_info[0]['current_fye_end'];
            $default_temp['is_title']    = true;
            array_push($fva_ly_template, $default_temp);

            $default_temp['is_title']    = false; // reset back to not title
            $default_temp['description'] = "Quoted shares";
            array_push($fva_ly_template, $default_temp);

            $default_temp['description'] = "Unquoted shares";
            array_push($fva_ly_template, $default_temp);


            // for group (current year)
            if(count($fva_g_p1) == 0)
            {
                $fva_g_p1 = $fva_cy_template;
            }
            
            // for group (last year)
            if(count($fva_g_p2) == 0)
            {
                $fva_g_p2 = $fva_ly_template;
            }

            // for group (current year)
            if(count($fva_c_p1) == 0)
            {
                $fva_c_p1 = $fva_cy_template;
            }
            
            // for group (last year)
            if(count($fva_c_p2) == 0)
            {
                $fva_c_p2 = $fva_ly_template;
            }

            $this->data['fva_g_p1'] = $fva_g_p1;
            $this->data['fva_g_p2'] = $fva_g_p2;
            $this->data['fva_c_p1'] = $fva_c_p1;
            $this->data['fva_c_p2'] = $fva_c_p2;
        }
        elseif($q[0]['section_name'] == "EVENTS OCCURRING AFTER THE REPORTING PERIOD")
        {
            if($final_report_type != 1)
            {
                $this->data['eo_info'] = $this->fs_notes_model->get_fs_event_occur_after_rp_info($fs_company_info_id);
            }
        }
        elseif($q[0]['section_name'] == "COMPARATIVE FIGURES") 
        {
            $this->data['comparative_figures'] = $this->fs_notes_model->get_fs_comparative_figures($fs_company_info_id);
        }
        elseif($q[0]['section_name'] == "PRIOR YEARS ADJUSTMENT") 
        {
            $pya_info = $this->db->query("SELECT * FROM fs_prior_years_adjustment_info WHERE fs_company_info_id=" . $fs_company_info_id);
            $pya_info = $pya_info->result_array();

            $this->data['pya_info'] = $pya_info;
        }
        elseif($q[0]['section_name'] == "GOING CONCERN")
        {
            if($final_report_type != 1)
            {
                $this->data['gc_info'] = $this->fs_notes_model->get_fs_going_concern_info($fs_company_info_id); 
            }
        }

        // /* Setting for column width */
        // $col_width = array(
        //                     'account_desc' => 75,
        //                     'note'         => 5,
        //                     'value'        => 20
        //                 );

        // if(!$fs_company_info[0]['first_set'])
        // {
        //     $col_width = array(
        //                     'account_desc' => 65,
        //                     'note'         => 5,
        //                     'value'        => 15
        //                 );

        //     // if($fs_company_info[0]['is_prior_year_amount_restated'])
        //     // {
        //     //     if($fs_company_info[0]['effect_of_restatement_since'] == $fs_company_info[0]['last_fye_begin'])
        //     //     {
        //     //         $col_width = array(
        //     //                 'account_desc' => 50,
        //     //                 'note'         => 5,
        //     //                 'value'        => 15
        //     //             );
        //     //     }
        //     // }
        // }
        // /* END OF Setting for column width */

        if($fs_company_info[0]['first_set'])
        {
            $this->data['colspan_val'] = 1;
        }
        else
        {
            $this->data['colspan_val'] = 2;
        }

        // main data
        $this->data['final_report_type'] = $final_report_type;

        echo $interface = $this->load->view('/views/financial_statement/template/fs_notes/dynamic_content.php', $this->data);
    }

    public function save_note()
    {
        $form_data = $this->input->post();

        $fs_company_info_id             = $form_data['fs_company_info_id'];
        $fs_categorized_account_id      = $form_data['fs_categorized_account_id_to_link_note'];
        $fs_note_templates_master_id    = $form_data['fs_note_templates_master_id'];
        $fs_list_statement_doc_type_id  = $form_data['fs_statement_doc_type_id'];
        // $note_num_displayed             = $this->fs_notes_model->get_note_num_display($fs_company_info_id, $fs_categorized_account_id, $fs_list_statement_doc_type_id);
        $note_num_displayed             = $form_data['note_num_displayed'];

        $fs_note_details = array(
                                'fs_company_info_id'            => $fs_company_info_id,
                                'fs_categorized_account_id'     => $fs_categorized_account_id,
                                'fs_note_templates_master_id'   => $fs_note_templates_master_id,
                                'fs_list_statement_doc_type_id' => $fs_list_statement_doc_type_id,
                                'note_num_displayed'            => $note_num_displayed
                            );

        $result = $this->fs_notes_model->save_fs_note_details($fs_note_details);

        echo json_encode(array('result' => $result));
    }

    public function save_fs_sub_revenue()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_sub_revenue       = $form_data['fs_sub_revenue'];
        $list_sub_revenue_ids = $form_data['list_sub_revenue_ids'];
        $fs_sub_revenue_id    = $form_data['fs_sub_revenue_id'];

        $remove_data_ids = [];
        $add_data        = [];

        $fs_sr_ids = [];

        foreach ($fs_sub_revenue as $key => $value) 
        {
            if(!empty($fs_sub_revenue_id[$key]))     // if is retrieved data from db
            {
                if(!$value)
                {
                    array_push($remove_data_ids, $fs_sub_revenue_id[$key]);
                    array_push($fs_sr_ids, '');
                }
                else
                {
                    array_push($fs_sr_ids, $fs_sub_revenue_id[$key]);
                }
            }
            else    // if not retrieve from fb
            {
                if($value)
                {
                    // array_push($add_data, 
                    //     array(
                    //         'info' => array(
                    //                     'fs_company_info_id' => $fs_company_info_id,
                    //                     'fs_list_sub_revenue_content_id' => $list_sub_revenue_ids[$key]
                    //                 )
                    //     )
                    // );

                    $result = $this->db->insert('fs_sub_revenue', 
                                    array(
                                            'fs_company_info_id' => $fs_company_info_id,
                                            'fs_list_sub_revenue_content_id' => $list_sub_revenue_ids[$key]
                                        ));
                    $fs_sr_id = $this->db->insert_id();

                    array_push($fs_sr_ids, $fs_sr_id);
                }
                else
                {
                    array_push($fs_sr_ids, '');
                }
            }
        }

        // delete data from fs_sub_revenue
        if(count($remove_data_ids) > 0)
        {
            $this->fs_notes_model->delete_tbl_data('fs_sub_revenue', $remove_data_ids);
        }
        
        // // add data to fs_sub_revenue
        // if(count($add_data) > 0)
        // {
        //     $this->fs_notes_model->insert_tbl_data('fs_sub_revenue', $add_data);
        // }

        echo json_encode(array('result' => true, 'fs_sr_ids' => $fs_sr_ids));
    }

    public function save_group_not_consolidated()
    {
        $form_data = $this->input->post();

        $data = array(
            'fs_company_info_id'               => $form_data['fs_company_info_id'],
            'fs_investment_in_subsidiaries_id' => $form_data['snc_fs_investment_in_subsidiaries_id'],
            'fs_subsidiary_type_id'            => $form_data['snc_subsidiary_type'],
            'holding_company_name'             => $form_data['snc_holding_company_name'],
            'country_id'                       => $form_data['snc_country_id'],
            'address_consolidated_fs_obtained' => $form_data['snc_address_consolidated_fs_obtained']
        );

        $this->fs_notes_model->save_group_not_consolidated($data);

        // echo json_encode($form_data);
    }

    public function save_intangible_assets_info()
    {
        $form_data = $this->input->post();

        $ia_info = array(
                    'id'   => $form_data['fs_intangible_assets_info_id'],
                    'info' => array(
                        'fs_company_info_id' => $form_data['fs_company_info_id'],
                        'range_of_year'      => $form_data['range_of_year']
                    )
        );
        $return_ia_info_id = $this->fs_notes_model->save_intangible_assets_info($ia_info);

        echo json_encode($return_ia_info_id);
    }

    public function save_intangible_assets()
    {
        $form_data = $this->input->post();

        $ia_ids      = array_values($form_data['ia_id']);
        $ia_name     = array_values($form_data['ia_name']);
        $ia_code     = array_values($form_data['ia_code']);
        $ia_duration = array_values($form_data['ia_duration']);

        $ia_deleted_ids = preg_split ("/\,/", $form_data['deleted_ids']);

        $ids     = array();
        $ia_info = array();

        foreach ($ia_ids as $key => $value) 
        {
            $generated_code = '';

            array_push($ids, $ia_ids[$key]);

            if($ia_ids[$key] == 0)
            {
                $generated_code = $this->fs_replace_content_model->rand_string(8);
            }
            else
            {
                $generated_code = $ia_code[$key];
            }

            array_push($ia_info, array(
                'fs_company_info_id'     => $form_data['fs_company_info_id'],
                'intangible_assets_code' => $generated_code,
                'name'                   => $ia_name[$key],
                'duration'               => $ia_duration[$key],
                'order_by'               => $key + 1
            ));
        }

        $data = array(
                    'ids' => $ids,
                    'ia_info' => $ia_info,
                    'ia_deleted_ids' => $ia_deleted_ids
                );

        $result = $this->fs_notes_model->save_intangible_assets($data);

        echo json_encode($result);
    }

    public function save_sub_ppe()
    {
        $form_data = $this->input->post();

        // print_r($form_data);

        $sub_ppe_ids      = array_values($form_data['sub_ppe_id']);
        $sub_ppe_name     = array_values($form_data['sub_ppe_name']);
        $sub_ppe_code     = array_values($form_data['sub_ppe_code']);
        $sub_ppe_duration = array_values($form_data['sub_ppe_duration']);

        $sub_ppe_deleted_ids = preg_split ("/\,/", $form_data['deleted_ids']);

        $ids     = array();
        $sub_ppe = array();

        foreach ($sub_ppe_ids as $key => $value) {

            $generated_code = '';

            array_push($ids, $sub_ppe_ids[$key]);

            if($value == 0)
            {
                $generated_code = $this->fs_replace_content_model->rand_string(8);
            }
            else
            {
                $generated_code = $sub_ppe_code[$key];
            }

            array_push($sub_ppe, array(
                'fs_company_info_id' => $form_data['fs_company_info_id'],
                'sub_ppe_code'       => $generated_code,
                'name'               => $sub_ppe_name[$key],
                'duration'           => $sub_ppe_duration[$key],
                'order_by'           => $key + 1
            ));
        }

        $data = array(
                    'ids' => $ids,
                    'sub_ppe_info' => $sub_ppe,
                    'sub_ppe_deleted_ids' => $sub_ppe_deleted_ids
                );

        $dp_sub_ppe_info_data = array(
                    'id'                             => $form_data['fs_sub_ppe_info_id'],
                    'fs_company_info_id'             => $form_data['fs_company_info_id'],
                    'fs_list_depreciation_method_id' => $form_data['sub_ppe_depreciation_method']
                );

        $return_sub_ppe = $this->fs_notes_model->save_sub_ppe($data);
        $return_sub_ppe_info_id = $this->fs_notes_model->save_sub_ppe_info($dp_sub_ppe_info_data);

        echo json_encode(array('result' => true, 'fs_sub_ppe_ids' => $return_sub_ppe['return_ids'], 'sub_ppe_codes' => $return_sub_ppe['return_codes'], 'fs_sub_ppe_info_id' => $return_sub_ppe_info_id));
    }

    public function save_sub_inventories()
    {
        $form_data = $this->input->post();

        $inventories_info_data = array(
                    'id'                              => $form_data['fs_inventories_info_id'],
                    'fs_company_info_id'              => $form_data['fs_company_info_id'],
                    'fs_list_net_realizable_value_id' => $form_data['fs_list_net_realizable_value_id']
                );

        $return_inventories_info_id = $this->fs_notes_model->save_sub_inventories($inventories_info_data);

        echo json_encode(array('result' => true, 'fs_inventories_info_id' => $return_inventories_info_id));
    }

    public function save_ntfs_employee_benefits()
    {
        $form_data = $this->input->post();

        // textarea
        $eb_data = array(
                'fs_company_info_id'              => $form_data['fs_company_info_id'],
                'share_based_payment_transaction' => $form_data['eb_para']
            );

        $result = $this->fs_notes_model->save_ntfs_employee_benefits($eb_data);

        echo json_encode($form_data);
    }

    public function save_sub_investment_properties()
    {
        $form_data = $this->input->post();

        if($form_data['model_type'] == "Cost")
        {
            $data = array(
                        'fs_company_info_id' => $form_data['fs_company_info_id'],
                        'model_type'         => $form_data['model_type'],
                        'model_content'      => $form_data['model_content_cost']
                    );
        }
        else
        {
            $data = array(
                        'fs_company_info_id' => $form_data['fs_company_info_id'],
                        'model_type'         => $form_data['model_type'],
                        'model_content'      => $form_data['model_content_fair_value']
                    );
        }

        $result = $this->fs_notes_model->save_sub_investment_properties($data);

        echo json_encode($form_data);
    }

    public function save_sub_provision()
    {
        $form_data = $this->input->post();

        $p_ids     = array_values($form_data['p_id']);
        $p_code    = array_values($form_data['p_code']);
        $p_header  = array_values($form_data['p_header']);
        $p_content = array_values($form_data['p_content']);

        $p_deleted_ids = preg_split ("/\,/", $form_data['deleted_ids']);
        $p_is_shown    = preg_split ("/\,/", $form_data['is_shown']);

        $ids      = array();
        $p_info = array();

        foreach ($p_ids as $key => $value) 
        {
            $generated_code = '';

            array_push($ids, $p_ids[$key]);

            if($p_ids[$key] == 0)
            {
                $generated_code = $this->fs_replace_content_model->rand_string(8);
            }
            else
            {
                $generated_code = $p_code[$key];
            }

            array_push($p_info, array(
                'fs_company_info_id' => $form_data['fs_company_info_id'],
                'provision_code'     => $generated_code,
                'is_shown'           => $p_is_shown[$key],
                'title'              => $p_header[$key],
                'content'            => $p_content[$key],
                'order_by'           => $key + 1
            ));
        }

        $data = array(
                    'ids' => $ids,
                    'p_info' => $p_info,
                    'p_deleted_ids' => $p_deleted_ids
                );

        $result = $this->fs_notes_model->save_sub_provision($data);

        echo json_encode($data);
    }

    public function save_employee_benefits_expense_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        // print_r($form_data);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        if($final_document_type == 1)
        {
            // table 
            $id        = $form_data['ebe_list_id'];
            $group_cy  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['ebe_list_group_cy']); 
            $group_ly  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['ebe_list_group_ly']); 

            /* for table 1 */
            $ebe_ntfs = [];

            if($fs_company_info[0]['group_type'] != 1)
            {
                foreach ($id as $key => $value) 
                {
                    if(!empty($id[$key])) //    update data
                    {
                        $temp_ebe_ntfs = array(
                                                'id' => $id[$key],
                                                'info' => array(
                                                            'group_end_this_ye_value' => $group_cy[$key],
                                                            'group_end_prev_ye_value' => $group_ly[$key]
                                                        )
                                            );

                        if($fs_company_info[0]['first_set'] == '1')
                        {
                            unset($temp_ebe_ntfs['info']['group_end_prev_ye_value']);
                        }

                        array_push($ebe_ntfs, $temp_ebe_ntfs);
                    }
                }

                $result = $this->fs_notes_model->update_tbl_data('fs_categorized_account_round_off', $ebe_ntfs);
            }
        }

        // textarea
        $ebe_ntfs = array(
                        'id'   => $form_data['fs_employee_benefits_expense_ntfs_id'],
                        'info' => array(
                            'fs_company_info_id'         => $form_data['fs_company_info_id'],
                            'is_shown'                   => $form_data['is_shown'],
                            'share_option_plans_content' => $form_data['ebe_share_option_plans_content']
                        )
                    );
        $return_result = $this->fs_notes_model->save_employee_benefits_expense_ntfs($ebe_ntfs);

        echo json_encode($return_result);
    }

    public function save_profit_b4_tax_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_profit_be4_tax_ids      = array_values($form_data['fs_profit_be4_tax_id']);
        $fs_categorized_account_ids = array_values($form_data['fs_categorized_account_id']);
        $fcaro_ids                  = array_values($form_data['fcaro_id']);
        $income_expense_type        = array_values($form_data['income_expense_type']);

        $input_cye_group            = array_values($form_data['input_cye_group']);
        $input_lye_group            = array_values($form_data['input_lye_group']);

        $pbt_deleted_ids = preg_split ("/\,/", $form_data['deleted_ids']);

        // echo json_encode($pbt_deleted_ids);

        $pbt   = array();
        $fca   = array();
        $fcaro = array();

        foreach ($fs_categorized_account_ids as $key => $fca_id) 
        {
            array_push($pbt, 
                array(
                    'id'   => $fs_profit_be4_tax_ids[$key],
                    'info' => array(
                                'fs_company_info_id'        => $fs_company_info_id,
                                'fs_categorized_account_id' => $fca_id,
                                'income_expense_type'       => $income_expense_type[$key],
                                'order_by'                  => $key + 1
                            )
                )
            );

            array_push($fca, 
                array(
                    'id' => $fca_id,
                    'info' => array(
                        'group_end_this_ye_value' => $input_cye_group[$key],
                        'group_end_prev_ye_value' => $input_lye_group[$key]
                    )
                )
            );

            array_push($fcaro,
                array(
                    'id' => $fcaro_ids[$key],
                    'info' => array(
                        'group_end_this_ye_value' => $input_cye_group[$key],
                        'group_end_prev_ye_value' => $input_lye_group[$key]
                    )
                )
            );
        }

        $fs_profit_be4_tax = array(
            'deleted_ids' => $pbt_deleted_ids,
            'pbt'         => $pbt
        );

        $result_fca     = $this->fs_account_category_model->update_categorzied_account_batch($fca);
        $result_fcaro   = $this->fs_notes_model->update_tbl_data('fs_categorized_account_round_off', $fcaro);
        $return_ids     = $this->fs_notes_model->save_profit_b4_tax_ntfs($fs_profit_be4_tax);

        echo json_encode($return_ids);
    }

    public function save_tax_expense_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        // for tax expense (te)
        $fs_te_id             = array_values($form_data['fs_te_id']);
        $te_description       = array_values($form_data['te_description']);
        $input_te_cye_company = array_values($form_data['te_company_cy']);
        $input_te_lye_company = array_values($form_data['te_company_ly']);
        $input_te_cye_group   = array_values($form_data['te_group_cy']);
        $input_te_lye_group   = array_values($form_data['te_group_ly']);

        $te_ntfs_deleted_ids = preg_split ("/\,/", $form_data['deleted_ids']);  

        $te_ntfs = [];

        foreach ($fs_te_id as $key => $value) 
        {
            $temp_te_ntfs = array(
                'id' => $fs_te_id[$key],
                'info' => array(
                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                            // 'tax_expense_code'           => $generated_code,
                            'description'               => $te_description[$key],
                            'value'                     => $input_te_cye_company[$key],
                            'company_end_prev_ye_value' => $input_te_lye_company[$key],
                            'group_end_this_ye_value'   => $input_te_cye_group[$key],
                            'group_end_prev_ye_value'   => $input_te_lye_group[$key],
                            'order_by'                  => $key + 1
                        )
            );

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_te_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_te_ntfs['info']['group_end_prev_ye_value']);
            }
            array_push($te_ntfs, $temp_te_ntfs);
        }

        $fs_tax_expense = array(
            'deleted_ids' => $te_ntfs_deleted_ids,
            'te_ntfs'     => $te_ntfs
        );

        $te_ids = $this->fs_notes_model->save_fs_tax_expense_ntfs($fs_tax_expense);

        if($final_document_type == 1) // for full FRS only
        {
            // for tax expense reconciliation (ter)
            $fs_ter_id      = array_values($form_data['fs_ter_id']);
            $fs_list_tax_expense_reconciliation_id = array_values($form_data['fs_list_tax_expense_reconciliation_id']);
            $ter_company_cy = array_values($form_data['ter_company_cy']);
            $ter_company_ly = array_values($form_data['ter_company_ly']);
            $ter_group_cy   = array_values($form_data['ter_group_cy']);
            $ter_group_ly   = array_values($form_data['ter_group_ly']);

            $fs_tax_expense_reconciliation = [];

            foreach ($fs_ter_id as $key => $value) 
            {
                array_push($fs_tax_expense_reconciliation, array(
                    'id'   => $fs_ter_id[$key],
                    'info' => array(
                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                'fs_list_tax_expense_reconciliation_id' => $fs_list_tax_expense_reconciliation_id[$key],
                                'value'                     => $ter_company_cy[$key],
                                'company_end_prev_ye_value' => $ter_company_ly[$key],
                                'group_end_this_ye_value'   => $ter_group_cy[$key],
                                'group_end_prev_ye_value'   => $ter_group_ly[$key],
                                'order_by'                  => $key + 1
                            )
                ));
            }
            $result_ter = $this->fs_notes_model->save_fs_tax_expense_reconciliation($fs_tax_expense_reconciliation);
        }
        else // for Small FRS 
        {
            // for tax expense info (te_info)
            /* for semi fixed content template */
            $fs_te_info_1 = array(
                'id'   => $form_data['fs_tax_expense_ntfs_info_id_1'],
                'info' => array(
                    'fs_company_info_id' => $form_data['fs_company_info_id'],
                    'is_shown'           => 1,
                    'part'               => 1,
                    'text_content'       => $form_data['fs_te_info_content_1']
                )
            );

            $te_info_id_1 = $this->fs_notes_model->save_fs_tax_expense_ntfs_info($fs_te_info_1);
            /* END OF for semi fixed content template */
        }

        /* for Only include if company has unabsorbed tax losses */
        $fs_te_info = array(
            'id'   => $form_data['fs_tax_expense_ntfs_info_id'],
            'info' => array(
                'fs_company_info_id' => $form_data['fs_company_info_id'],
                'is_shown'           => $form_data['te_info_is_shown'],
                'part'               => 2,
                'text_content'       => $form_data['fs_te_info_content']
            )
            
        );

        $te_info_id = $this->fs_notes_model->save_fs_tax_expense_ntfs_info($fs_te_info);
        /* END OF for Only include if company has unabsorbed tax losses */

        if($final_document_type == 1) // for full FRS only
        {
            // return ids
            $result_ids = array(
                'result'            => true,
                'final_report_type' => $final_document_type,
                'te_ids'            => $te_ids,
                'te_info_id'        => $te_info_id
            );
        }
        else
        {
            // return ids
            $result_ids = array(
                'result'            => true,
                'final_report_type' => $final_document_type,
                'te_ids'            => $te_ids,
                'te_info_id_1'      => $te_info_id_1,
                'te_info_id'        => $te_info_id
            );
        }

        echo json_encode($result_ids);
    }

    public function save_investment_in_subsidiaries_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $iis_p1_content = $form_data['iis_p1_content'];

        if($final_document_type == 1) // for full FRS only
        {
            if($form_data['iis_part'] == 1)
            {
                $iis_p1_info_id = $form_data['iis_p1_info_id'];
                $iis_p1_content = $form_data['iis_p1_content'];

                $iis_p1 = array(
                                'id' => $iis_p1_info_id,
                                'info' => array(
                                                'fs_company_info_id' => $fs_company_info_id,
                                                'content' => $iis_p1_content
                                            )
                            );

                 $iis_p1_data = array(
                    'table_name'  => 'fs_investment_in_subsidiaries_ntfs_p1_info',
                    'deleted_ids' => [],
                    'ntfs_data'   => array($iis_p1)
                );

                $iis_p1_id = $this->fs_notes_model->insert_update_tbl_data($iis_p1_data);
            }
        }

        // print_r($form_data);

        /* ------------- for table 1 ------------- */
        $fs_iis_id      = array_values($form_data['fs_iis_id']);
        $fs_list_iis_id = array_values($form_data['fs_list_investment_in_subsidiaries_id']);
        $iis_company_cy = array_values($form_data['iis_company_cy']);
        $iis_company_ly = array_values($form_data['iis_company_ly']);

        $iis = array();

        foreach ($fs_iis_id as $key => $iis_id) {
            array_push($iis, array(
                'id'   => $fs_iis_id[$key],
                'info' => array(
                            'fs_company_info_id'        => $fs_company_info_id,
                            'fs_list_investment_in_subsidiaries_id' => $fs_list_iis_id[$key],
                            'value'                     => $iis_company_cy[$key],
                            'company_end_prev_ye_value' => $iis_company_ly[$key],
                            'order_by'                  => $key + 1
                        )
            ));
        }

        $result = $this->fs_notes_model->update_tbl_data("fs_investment_in_subsidiaries_ntfs", $iis);
        /* ------------- END OF for table 1 ------------- */

        /* ------------- for table 2 ------------- */
        $iis_t2_id              = array_values($form_data['iis_t2_id']);
        $iis_t2_noe             = array_values($form_data['iis_t2_noe']);
        $iis_t2_coi             = array_values($form_data['iis_t2_coi']);
        $iis_t2_pa              = array_values($form_data['iis_t2_pa']);
        $iis_t2_interest_val_1  = array_values($form_data['iis_t2_interest_val_1']);
        $iis_t2_interest_val_2  = array_values($form_data['iis_t2_interest_val_2']);

        $iis_t2_rows = array();

        $iis_t2_deleted_row_ids = preg_split ("/\,/", $form_data['iis_t2_deleted_row_ids']);

        foreach ($iis_t2_id as $iis_t2_key => $iis_t2_value) 
        {
            array_push($iis_t2_rows, 
                array(
                    'id'    => $iis_t2_id[$iis_t2_key],
                    'info'  =>  array(
                                    'fs_company_info_id'   => $fs_company_info_id,
                                    'name_of_entity'       => $iis_t2_noe[$iis_t2_key],
                                    'country_id'           => $iis_t2_coi[$iis_t2_key],
                                    'principal_activities' => $iis_t2_pa[$iis_t2_key],
                                    'interest_val_1'       => $iis_t2_interest_val_1[$iis_t2_key],
                                    'interest_val_2'       => $iis_t2_interest_val_2[$iis_t2_key],
                                    'order_by'             => $iis_t2_key + 1
                                )
                )
            );
        }

        $iis_t2_ntfs_data = array(
            'table_name'  => 'fs_investment_in_subsidiaries_ntfs_2',
            'deleted_ids' => $iis_t2_deleted_row_ids,
            'ntfs_data'   => $iis_t2_rows
        );

        $iis_t2_ids = $this->fs_notes_model->insert_update_tbl_data($iis_t2_ntfs_data);
        /* ------------- END OF for table 2 ------------- */

        $return_data = array(
                            'result'    => true,
                            'iis_t2_ids' => $iis_t2_ids,
                            'iis_p1_id' => $iis_p1_id
                        );

       echo json_encode($return_data);
    }

    public function save_investment_in_subsidiaries_ntfs_p2()
    {
        $form_data = $this->input->post();

        $fs_company_info_id  = $form_data['fs_company_info_id'];

         /* ------ save/update/delete for table ------ */
        $header_iis_p2_t1 = $form_data['header_iis_p2_t1'];
        $iis_t1_row       = $form_data['iis_t1_row'];

        $iis_p2_t1_deleted_row_ids = preg_split ("/\,/", $form_data['iis_p2_t1_deleted_row_ids']);

        /* ----------- save header data "fs_investment_in_subsidiaries_ntfs_p2_1_header" ----------- */
        $header_title_id = $header_iis_p2_t1[0];
        $header_row = [];

        for ($x = 1; $x < count($header_iis_p2_t1) - 1; $x++) 
        {
            array_push($header_row, $header_iis_p2_t1[$x]);
        }

        $header_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header_iis_p2_t1,1))
                        );

        if(!empty($header_iis_p2_t1[0]))
        {
            $info = $this->db->query("SELECT * FROM fs_investment_in_subsidiaries_ntfs_p2_1_header WHERE id=" . $header_iis_p2_t1[0]);
            $info = $info->result_array();

            $header_iis_p2_t1_id = $header_iis_p2_t1[0];

            if($info[0]['header_titles'] != $header_data['header_titles'])
            {
                $this->db->where('id', $header_iis_p2_t1[0]);
                $this->db->update('fs_investment_in_subsidiaries_ntfs_p2_1_header', $header_data);
            }
        }
        else
        {
            $header_result = $this->db->insert('fs_investment_in_subsidiaries_ntfs_p2_1_header', $header_data);
            $header_iis_p2_t1_id = $this->db->insert_id();
        }

        // make array to save to db later
        $return_arr = [];

        foreach ($iis_t1_row as $key => $value) 
        {
            $temp_data = array(
                            'id' => $value[0],
                            'info' => array(
                                        'fs_company_info_id' => $fs_company_info_id,
                                        'is_title'           => is_null($value[1])?'':$value[1],
                                        'section'            => $value[2],
                                        'description'        => is_null($value[3])?'':$value[3],
                                        'row_item'           => implode(",",array_slice($value,4)),
                                    )
                        );

            array_push($return_arr, $temp_data);
        }

        // insert order_by
        foreach ($return_arr as $key => $value) 
        {
            $return_arr[$key]['info']['order_by'] = $key + 1;
        }

        $iis_p2_t1_ntfs_data = array(
            'table_name'  => 'fs_investment_in_subsidiaries_ntfs_p2_1',
            'deleted_ids' => $iis_p2_t1_deleted_row_ids,
            'ntfs_data'   => $return_arr
        );

        $fs_iis_p2_t1_ids = $this->fs_notes_model->insert_update_tbl_data($iis_p2_t1_ntfs_data);

        // return ids
        $result_ids = array(
            'result'              => true,
            'header_iis_p2_t1_id' => $header_iis_p2_t1_id,
            'fs_iis_p2_t1_ids'    => $fs_iis_p2_t1_ids
        );

        echo json_encode($result_ids);
        /* ------ END OF save/update/delete for table ------ */
    }

    public function save_investment_associate_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $return_iia_info_id = '';

        if($final_document_type != 1)
        {
            $iia_info_id        = $form_data['iia_info_id'];
            $iia_info_content   = $form_data['iia_info_content'];

            if(!(empty($iia_info_content) && empty($iia_info_id)))
            {
                $fs_iia_info = array(
                    'id'   => $iia_info_id,
                    'info' => array(
                        'fs_company_info_id' => $fs_company_info_id,
                        'content'            => $iia_info_content
                    )
                );

                $return_iia_info_id = $this->fs_notes_model->save_fs_investment_associates_info($fs_iia_info);

                $result_ids = array(
                    'result'            => true,
                    'final_document_type' => $final_document_type,
                    'fs_iia_ntfs_1_ids' => $fs_iia_ntfs_1_ids
                );
            }
        }
        else
        {
            // print_r($form_data);
            /* --- for Table 1 --- */
            $iia_t1_id          = $form_data['iia_t1_id'];
            $iia_t1_description = $form_data['iia_t1_description'];
            $iia_t1_group_cy    = $form_data['iia_t1_group_cy'];
            $iia_t1_group_ly    = $form_data['iia_t1_group_ly'];
            $iia_t1_company_cy  = $form_data['iia_t1_company_cy'];
            $iia_t1_company_ly  = $form_data['iia_t1_company_ly'];

            $iia_ntfs_1_deleted_ids = preg_split ("/\,/", $form_data['iia_ntfs_1_deleted_ids']);

            $iia_ntfs_1 = [];

            foreach ($iia_t1_part as $key => $value) 
            {
                $temp_iia_ntfs_1 = array(
                                        'id' => $iia_t1_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'description'               => $iia_t1_description[$key],
                                                    'value'                     => $iia_t1_group_cy[$key],
                                                    'company_end_prev_ye_value' => $iia_t1_group_ly[$key],
                                                    'group_end_this_ye_value'   => $iia_t1_company_cy[$key],
                                                    'group_end_prev_ye_value'   => $iia_t1_company_ly[$key],
                                                    'order_by'                  => $key + 1
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_iia_ntfs_1['info']['group_end_this_ye_value']);
                    unset($temp_iia_ntfs_1['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_iia_ntfs_1['info']['group_end_prev_ye_value']);
                    unset($temp_iia_ntfs_1['info']['company_end_prev_ye_value']);
                }

                array_push($iia_ntfs_1, $temp_iia_ntfs_1);
            }

            $iia_ntfs_1_data = array(
                'table_name'  => 'fs_investment_in_associates_ntfs',
                'deleted_ids' => $iia_ntfs_1_deleted_ids,
                'ntfs_data'   => $iia_ntfs_1
            );

            $return_iia_ntfs_1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($iia_ntfs_1_data);
            /* --- END OF for Table 1 --- */

            /* --- for Table 2 --- */
            $iia_t2_id              = array_values($form_data['iia_t2_id']);
            $iia_t2_noe             = array_values($form_data['iia_t2_noe']);
            $iia_t2_coi             = array_values($form_data['iia_t2_coi']);
            $iia_t2_pa              = array_values($form_data['iia_t2_pa']);
            $iia_t2_interest_val_1  = array_values($form_data['iia_t2_interest_val_1']);
            $iia_t2_interest_val_2  = array_values($form_data['iia_t2_interest_val_2']);

            $iia_t2 = array();

            $iia_ntfs_2_deleted_ids = preg_split ("/\,/", $form_data['iia_ntfs_2_deleted_ids']);

            foreach ($iia_t2_id as $iia_t2_key => $iia_t2_value) 
            {
                array_push($iia_t2, 
                    array(
                        'id'    => $iia_t2_id[$iia_t2_key],
                        'info'  =>  array(
                                        'fs_company_info_id'   => $fs_company_info_id,
                                        'name_of_entity'       => $iia_t2_noe[$iia_t2_key],
                                        'country_id'           => $iia_t2_coi[$iia_t2_key],
                                        'principal_activities' => $iia_t2_pa[$iia_t2_key],
                                        'interest_val_1'       => $iia_t2_interest_val_1[$iia_t2_key],
                                        'interest_val_2'       => $iia_t2_interest_val_2[$iia_t2_key],
                                        'order_by'             => $iia_t2_key + 1
                                    )
                    )
                );
            }

            $iia_t2_ntfs_data = array(
                'table_name'  => 'fs_investment_in_associates_ntfs_2',
                'deleted_ids' => $iia_ntfs_2_deleted_ids,
                'ntfs_data'   => $iia_t2
            );

            $return_iia_t2_ids = $this->fs_notes_model->insert_update_tbl_data($iia_t2_ntfs_data);
            /* --- END OF for Table 2 --- */

            /* --- for Table 3 --- */
            $fs_iia_t3_id   = $form_data['iia_t3_id']; 
            $description = $form_data['iia_t3_description']; 
            $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iia_t3_group_cy']); 
            $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iia_t3_group_ly']); 
            $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iia_t3_company_cy']); 
            $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iia_t3_company_ly']); 

            $iia_t3_deleted_ids = preg_split ("/\,/", $form_data['iia_ntfs_3_deleted_ids']);

            $iia_t3_ntfs = [];

            foreach ($fs_iia_t3_id as $key => $value) 
            {
                $temp_iia_t3_ntfs = array(
                                    'id' => $fs_iia_t3_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_iia_t3_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_iia_t3_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_iia_t3_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_iia_t3_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($iia_t3_ntfs, $temp_iia_t3_ntfs);
            }

            $iia_t3_ntfs_data = array(
                'table_name'  => 'fs_investment_in_associates_ntfs_3',
                'deleted_ids' => $iia_t3_deleted_ids,
                'ntfs_data'   => $iia_t3_ntfs
            );

            $return_iia_t3_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($iia_t3_ntfs_data);
            /* --- END OF for Table 3 --- */
        }

        // return ids
        $result_ids = array(
            'result'            => true,
            'final_document_type' => $final_document_type,
            'fs_iia_ntfs_1_ids' => $return_iia_ntfs_1_ids,
            'fs_iia_ntfs_2_ids' => $return_iia_t2_ids,
            'fs_iia_ntfs_3_ids' => $return_iia_t3_ids
        );

        echo json_encode($result_ids);
    }

    public function save_investment_joint_venture_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $final_document_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $result_ids = array(
                        'final_doc_type' => $final_document_type
                    );

        if($final_document_type != 1)
        {
            $iijv_info_id       = $form_data['iijv_info_id'];
            $iijv_info_content  = $form_data['iijv_info_content'];

            $return_iijv_info_id = '';

            if(!(empty($iijv_info_content) && empty($iijv_info_id)))
            {
                $fs_iijv_info = array(
                    'id'   => $iijv_info_id,
                    'info' => array(
                        'fs_company_info_id' => $fs_company_info_id,
                        'content'            => $iijv_info_content
                    )
                );
                
                $return_iijv_info_id = $this->fs_notes_model->save_fs_investment_joint_venture_info($fs_iijv_info);
            }

            // return ids
            $result_ids = array(
                'result'        => true,
                'final_document_type' => $final_document_type,
                'iijv_info_id'    => $return_iijv_info_id
            );
        }
        else
        {
            // print_r($form_data);
            /* ------------- for table 1 ------------- */
            $iijv_t1_id              = array_values($form_data['iijv_t1_id']);
            $iijv_t1_noe             = array_values($form_data['iijv_t1_noe']);
            $iijv_t1_coi             = array_values($form_data['iijv_t1_coi']);
            $iijv_t1_pa              = array_values($form_data['iijv_t1_pa']);
            $iijv_t1_interest_val_1  = array_values($form_data['iijv_t1_interest_val_1']);
            $iijv_t1_interest_val_2  = array_values($form_data['iijv_t1_interest_val_2']);

            $iijv_t1 = array();

            $iijv_t1_deleted_row_ids = preg_split ("/\,/", $form_data['iijv_t1_deleted_row_ids']);

            foreach ($iijv_t1_id as $iijv_t1_key => $iijv_t1_value) 
            {
                array_push($iijv_t1, 
                    array(
                        'id'    => $iijv_t1_id[$iijv_t1_key],
                        'info'  =>  array(
                                        'fs_company_info_id'   => $fs_company_info_id,
                                        'name_of_entity'       => $iijv_t1_noe[$iijv_t1_key],
                                        'country_id'           => $iijv_t1_coi[$iijv_t1_key],
                                        'principal_activities' => $iijv_t1_pa[$iijv_t1_key],
                                        'interest_val_1'       => $iijv_t1_interest_val_1[$iijv_t1_key],
                                        'interest_val_2'       => $iijv_t1_interest_val_2[$iijv_t1_key],
                                        'order_by'             => $iijv_t1_key + 1
                                    )
                    )
                );
            }

            $iijv_t1_ntfs_data = array(
                'table_name'  => 'fs_investment_in_joint_venture_ntfs',
                'deleted_ids' => $iijv_t1_deleted_row_ids,
                'ntfs_data'   => $iijv_t1
            );

            $return_iijv_t1_ids = $this->fs_notes_model->insert_update_tbl_data($iijv_t1_ntfs_data);
            /* ------------- END OF for table 1 ------------- */

            /* ------------- for table 2 ------------- */
            $fs_iijv_t2_id   = $form_data['iijv_t2_id']; 
            $description = $form_data['iijv_t2_description']; 
            $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iijv_t2_group_cy']); 
            $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iijv_t2_group_ly']); 
            $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iijv_t2_company_cy']); 
            $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['iijv_t2_company_ly']); 

            $iijv_t2_deleted_ids = preg_split ("/\,/", $form_data['iijv_t2_deleted_row_ids']);

            $iijv_t2_ntfs = [];

            foreach ($fs_iijv_t2_id as $key => $value) 
            {
                $temp_iijv_t2_ntfs = array(
                                    'id' => $fs_iijv_t2_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_iijv_t2_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_iijv_t2_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_iijv_t2_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_iijv_t2_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($iijv_t2_ntfs, $temp_iijv_t2_ntfs);
            }

            $iijv_t2_ntfs_data = array(
                'table_name'  => 'fs_investment_in_joint_venture_ntfs_2',
                'deleted_ids' => $iijv_t2_deleted_ids,
                'ntfs_data'   => $iijv_t2_ntfs
            );

            $fs_iijv_t2_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($iijv_t2_ntfs_data);
            /* ------------- END OF for table 2 ------------- */

            // return ids
            // $result_ids = array(
            //     'result'        => true,
            //     'fs_iijv_t2_ids'    => $fs_iijv_t2_ids
            // );

            $result_ids = array(
                'result'        => true,
                'final_document_type' => $final_document_type,
                'iijv_t1_ids'    => $return_iijv_t1_ids,
                'iijv_t2_ids' => $fs_iijv_t2_ids
            );
        }
        

        echo json_encode($result_ids);
    }

    public function save_insured_benefits_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        if($final_report_type == 1)
        {
            // print_r($form_data);

             /* --- for Table 1 --- */
            $ib_t1_id          = $form_data['ib_t1_id'];
            $ib_t1_description = $form_data['ib_t1_description'];
            $ib_t1_group_cy    = $form_data['ib_t1_group_cy'];
            $ib_t1_group_ly    = $form_data['ib_t1_group_ly'];
            $ib_t1_company_cy  = $form_data['ib_t1_company_cy'];
            $ib_t1_company_ly  = $form_data['ib_t1_company_ly'];

            $ib_ntfs_1_deleted_ids = preg_split ("/\,/", $form_data['ib_ntfs_1_deleted_ids']);

            $ib_ntfs_1 = [];

            foreach ($ib_t1_id as $key => $value) 
            {
                $temp_ib_ntfs_1 = array(
                                        'id' => $ib_t1_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'description'               => $ib_t1_description[$key],
                                                    'value'                     => $ib_t1_group_cy[$key],
                                                    'company_end_prev_ye_value' => $ib_t1_group_ly[$key],
                                                    'group_end_this_ye_value'   => $ib_t1_company_cy[$key],
                                                    'group_end_prev_ye_value'   => $ib_t1_company_ly[$key],
                                                    'order_by'                  => $key + 1
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_ib_ntfs_1['info']['group_end_this_ye_value']);
                    unset($temp_ib_ntfs_1['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_ib_ntfs_1['info']['group_end_prev_ye_value']);
                    unset($temp_ib_ntfs_1['info']['company_end_prev_ye_value']);
                }

                array_push($ib_ntfs_1, $temp_ib_ntfs_1);
            }

            $ib_ntfs_1_data = array(
                'table_name'  => 'fs_insured_benefits_ntfs',
                'deleted_ids' => $ib_ntfs_1_deleted_ids,
                'ntfs_data'   => $ib_ntfs_1
            );

            $return_ib_ntfs_1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($ib_ntfs_1_data);
            /* --- END OF for Table 1 --- */

            // return ids
            $result_ids = array(
                'result'               => true,
                'final_report_type' => $final_report_type,
                'return_ib_ntfs_1_ids' => $return_ib_ntfs_1_ids
            );
        }
        else
        {
            $ib_info_id        = $form_data['ib_info_id'];
            $ib_info_content   = $form_data['ib_info_content'];

            $return_ib_info_id = '';

            if(!(empty($ib_info_content) && empty($ib_info_id)))
            {
                $fs_ib_info = array(
                    'id'   => $ib_info_id,
                    'info' => array(
                        'fs_company_info_id' => $fs_company_info_id,
                        'content'            => $ib_info_content
                    )
                );

                $return_ib_info_id = $this->fs_notes_model->save_fs_insured_benefits_info($fs_ib_info);
            }

            // return ids
            $result_ids = array(
                'result'        => true,
                'final_report_type' => $final_report_type,
                'ib_info_id'    => $return_ib_info_id
            );
        }

        echo json_encode($result_ids);
    }

    public function save_ntfs_intangible_assets() 
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id']; 
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        $ia_ntfs_info_id      = $form_data['ia_ntfs_info_id'];
        $ia_ntfs_info_content = $form_data['ia_ntfs_info_content'];

        $header          = $form_data['header'];
        $cost_row        = $form_data['cost_row'];
        $accumulated_row = $form_data['accumulated_row'];
        $carrying_row    = $form_data['carrying_row'];

        $cost_last        = $form_data['cost'];
        $accumulated_last = $form_data['accumulated'];
        $carrying_last    = $form_data['carrying'];

        $ia_deleted_row_ids = preg_split ("/\,/", $form_data['ia_deleted_row_ids']);   

        /* ----------- save header data "fs_intangible_assets_ntfs_1_header" ----------- */
        $header_title_id = $header[0];
        $header_row = [];

        for ($x = 1; $x < count($header) - 1; $x++) 
        {
            array_push($header_row, $header[$x]);
        }

        $header_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header,1))
                        );

        if(!empty($header[0]))
        {
            $info = $this->db->query("SELECT * FROM fs_intangible_assets_ntfs_1_header WHERE id=" . $header[0]);
            $info = $info->result_array();

            $header_id = $header[0];

            if($info[0]['header_titles'] != $header_data['header_titles'])
            {
                $this->db->where('id', $header_id);
                $this->db->update('fs_intangible_assets_ntfs_1_header', $header_data);
            }
        }
        else
        {
            $header_result = $this->db->insert('fs_intangible_assets_ntfs_1_header', $header_data);
            $header_id     = $this->db->insert_id();
        }
        /* ----------- END OF save header data "fs_intangible_assets_ntfs_1_header" ----------- */

        $cost_row_array        = $this->remake_array_dynamic_column_tbl($cost_row, 'cost', $fs_company_info_id); 
        $accumulated_row_array = $this->remake_array_dynamic_column_tbl($accumulated_row, 'accumulated', $fs_company_info_id);
        $carrying_row_array    = $this->remake_array_dynamic_column_tbl($carrying_row, 'carrying', $fs_company_info_id);

        $cost_last_row_array        = $this->remake_array_dynamic_column_tbl($cost_last, 'last cost', $fs_company_info_id);
        $accumulated_last_row_array = $this->remake_array_dynamic_column_tbl($accumulated_last, 'last accumulated', $fs_company_info_id);
        $carrying_last_row_array    = $this->remake_array_dynamic_column_tbl($carrying_last, 'last carrying', $fs_company_info_id);

        $ia_rows = array_merge($cost_row_array, $cost_last_row_array, $accumulated_row_array, $accumulated_last_row_array, $carrying_row_array, $carrying_last_row_array);

        // insert order_by
        foreach ($ia_rows as $key => $value) 
        {
            $ia_rows[$key]['info']['order_by'] = $key + 1;
        }

        $ia_ntfs_data = array(
            'table_name'  => 'fs_intangible_assets_ntfs_1',
            'deleted_ids' => $ia_deleted_row_ids,
            'ntfs_data'   => $ia_rows
        );

        $fs_ia_t1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table_1($ia_ntfs_data);

        if($final_report_type == 1)
        {
            $fs_ia_info_2_id      = array_values($form_data['fs_ia_info_2_id']);
            $fs_ia_info_2_on_off  = array_values($form_data['fs_ia_info_2_on_off']);
            $list_ia_info_2_id    = array_values($form_data['list_ia_info_2_id']);
            $fs_ia_info_2_content = array_values($form_data['fs_ia_info_2_content']);

            $return_fs_ia_info_2_ids = [];

            foreach ($fs_ia_info_2_on_off as $key => $value) 
            {
                if($value == 1)
                {
                    if(!empty($fs_ia_info_2_id[$key])) //    update data
                    {
                        $result = $this->fs_notes_model->update_tbl_data('fs_intangible_assets_info_2', 
                                    array(
                                        array(
                                            'id' => $fs_ia_info_2_id[$key],
                                            'info' => array(
                                                        'content' => $fs_ia_info_2_content[$key],
                                                        'is_checked' => 1
                                                    )
                                        )
                                    )
                                );

                        array_push($return_fs_ia_info_2_ids, $fs_ia_info_2_id[$key]);
                    }
                    else // create new
                    {
                        $fs_list_ppe_content_id = $list_ia_info_2_id[$key];

                        $result = $this->db->insert('fs_intangible_assets_info_2', 
                                        array(
                                            'fs_company_info_id'     => $fs_company_info_id,
                                            'fs_list_intangible_assets_content_id' => $list_ia_info_2_id[$key],
                                            'content'                => $fs_ia_info_2_content[$key],
                                            'is_checked'             => 1
                                        )
                                    );
                        array_push($return_fs_ia_info_2_ids, $this->db->insert_id());
                    }
                }
                else    // if checkbox is off, change is_checked status to 0
                {
                    if(!empty($fs_ia_info_2_id[$key]))
                    {
                        // set not checked
                        $result = $this->fs_notes_model->update_tbl_data('fs_intangible_assets_info_2', 
                                    array(
                                        array(
                                            'id' => $fs_ia_info_2_id[$key],
                                            'info' => array(
                                                        'is_checked' => 0, 
                                                        'content' => $fs_ia_info_2_content[$key]
                                                    )
                                        )
                                    )
                                );
                    }
                    array_push($return_fs_ia_info_2_ids, $fs_ia_info_2_id[$key]);
                }
            }

            $return_data = array(
                            'result'            => true,
                            'final_report_type' => $final_report_type,
                            'header_id'         => $header_id,
                            'fs_ia_t1_ids'      => $fs_ia_t1_ids,
                            'return_fs_ia_info_2_ids' => $return_fs_ia_info_2_ids 
                        );
        }
        else
        {
            /* for textarea part */
            $ia_ntfs_info = array(
                                'table_name' => 'fs_intangible_assets_info',
                                'deleted_ids' => [],
                                'ntfs_data' => array(
                                                    array(
                                                        'id'   => $ia_ntfs_info_id, 
                                                        'info' => array(
                                                                    'fs_company_info_id' => $fs_company_info_id,
                                                                    'content' => $ia_ntfs_info_content
                                                                )
                                                    )
                                                )
                            );

            $return_ia_ntfs_info_id = $this->fs_notes_model->insert_update_tbl_data($ia_ntfs_info);
            /* END OF for textarea part */

            $return_data = array(
                            'result'            => true,
                            'final_report_type' => $final_report_type,
                            'header_id'         => $header_id,
                            'fs_ia_t1_ids'      => $fs_ia_t1_ids,
                            'ia_ntfs_info_id'   => $return_ia_ntfs_info_id[0]
                        );
        }

       echo json_encode($return_data);
        
    }

    public function remake_array_dynamic_column_tbl($data_rows, $section, $fs_company_info_id)
    {
        $return_arr = [];

        foreach ($data_rows as $key => $value) 
        {
            $temp_data = array(
                            'id' => $value[0],
                            'info' => array(
                                        'fs_company_info_id' => $fs_company_info_id,
                                        'section'            => $section,
                                        'is_checked'         => is_null($value[1])?'':$value[1],
                                        'description'        => is_null($value[2])?'':$value[2],
                                        'row_item'           => implode(",",array_slice($value,3)),
                                    )
                        );

            array_push($return_arr, $temp_data);
        }
        return $return_arr;
    }

    public function save_ntfs_investment_properties()
    {
        $form_data = $this->input->post();

        $fs_company_info_id  = $form_data['fs_company_info_id'];
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        if($final_report_type == 1)
        {
            /* ------ save/update/delete for table 1 ------ */
            $header          = $form_data['header'];
            $cost_row        = $form_data['cost_row'];
            $accumulated_row = $form_data['accumulated_row'];
            $carrying_row    = $form_data['carrying_row'];

            $cost_last        = $form_data['cost'];
            $accumulated_last = $form_data['accumulated'];
            $carrying_last    = $form_data['carrying'];

            $ip_t1_deleted_row_ids = preg_split ("/\,/", $form_data['ip_t1_deleted_row_ids']);

            /* ----------- save header data "fs_investment_properties_ntfs_1_header" ----------- */
            $header_title_id = $header[0];
            $header_row = [];

            for ($x = 1; $x < count($header) - 1; $x++) 
            {
                array_push($header_row, $header[$x]);
            }

            $header_data = array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'header_titles'      => implode(",",array_slice($header,1))
                            );

            if(!empty($header[0]))
            {
                $info = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_1_header WHERE id=" . $header[0]);
                $info = $info->result_array();

                $header_ip_t1_id = $header[0];

                if($info[0]['header_titles'] != $header_data['header_titles'])
                {
                    $this->db->where('id', $header[0]);
                    $this->db->update('fs_investment_properties_ntfs_1_header', $header_data);
                }
            }
            else
            {
                $header_result = $this->db->insert('fs_investment_properties_ntfs_1_header', $header_data);
                $header_ip_t1_id = $this->db->insert_id();
            }
            /* ----------- END OF save header data "fs_investment_properties_ntfs_1_header" ----------- */

            $cost_row_array        = $this->remake_array_dynamic_column_tbl($cost_row, 'cost', $fs_company_info_id);
            $accumulated_row_array = $this->remake_array_dynamic_column_tbl($accumulated_row, 'accumulated', $fs_company_info_id);
            $carrying_row_array    = $this->remake_array_dynamic_column_tbl($carrying_row, 'carrying', $fs_company_info_id);

            $cost_last_row_array        = $this->remake_array_dynamic_column_tbl($cost_last, 'last cost', $fs_company_info_id);
            $accumulated_last_row_array = $this->remake_array_dynamic_column_tbl($accumulated_last, 'last accumulated', $fs_company_info_id);
            $carrying_last_row_array    = $this->remake_array_dynamic_column_tbl($carrying_last, 'last carrying', $fs_company_info_id);

            $ip_t1_rows = array_merge($cost_row_array, $cost_last_row_array, $accumulated_row_array, $accumulated_last_row_array, $carrying_row_array, $carrying_last_row_array);

            // insert order_by
            foreach ($ip_t1_rows as $key => $value) 
            {
                $ip_t1_rows[$key]['info']['order_by'] = $key + 1;
            }

            $ip_t1_ntfs_data = array(
                'table_name'  => 'fs_investment_properties_ntfs_1',
                'deleted_ids' => $ip_t1_deleted_row_ids,
                'ntfs_data'   => $ip_t1_rows
            );

            $fs_ip_t1_ids = $this->fs_notes_model->insert_update_tbl_data($ip_t1_ntfs_data);
            /* ------ END OF save/update/delete for table 1 ------ */

            /* for Table 2 */
            $fs_ip_t2_id = $form_data['fs_ip_t2_id'];
            $description = $form_data['fs_ip_t2_description'];
            $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['fs_ip_t2_group_cy']); 
            $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['fs_ip_t2_group_ly']); 
            $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['fs_ip_t2_company_cy']); 
            $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['fs_ip_t2_company_ly']); 

            $ip_t2_ntfs_deleted_ids = preg_split ("/\,/", $form_data['ip_t2_deleted_row_ids']);

            $ip_t2_ntfs = [];

            foreach ($fs_ip_t2_id as $t2_key => $t2_value) 
            {
                $temp_ip_t2_ntfs = array(
                                        'id' => $fs_ip_t2_id[$t2_key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'description'               => $description[$t2_key],
                                                    'value'                     => $company_cy[$t2_key],
                                                    'company_end_prev_ye_value' => $company_ly[$t2_key],
                                                    'group_end_this_ye_value'   => $group_cy[$t2_key],
                                                    'group_end_prev_ye_value'   => $group_ly[$t2_key],
                                                    'order_by'                  => $t2_key + 1
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_ip_t2_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_ip_t2_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_ip_t2_ntfs['info']['group_end_prev_ye_value']);
                    unset($temp_ip_t2_ntfs['info']['company_end_prev_ye_value']);
                }

                array_push($ip_t2_ntfs, $temp_ip_t2_ntfs);
            }

            $ip_t2_ntfs_data = array(
                'table_name'  => 'fs_investment_properties_ntfs_2',
                'deleted_ids' => $ip_t2_ntfs_deleted_ids,
                'ntfs_data'   => $ip_t2_ntfs
            );

            $fs_ip_t2_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($ip_t2_ntfs_data);
            /* END OF for Table 2 */

            /* for Table 4 */
            $fs_ip_t4_id              = $form_data['fs_ip_t4_id'];
            $description_and_location = $form_data['ip_t4_description_and_location']; 
            $existing_use             = $form_data['ip_t4_existing_use']; 
            $tenure                   = $form_data['ip_t4_tenure']; 
            $unexpired_lease_term     = $form_data['ip_t4_unexpired_lease_term']; 

            $ip_t4_ntfs_deleted_ids = preg_split ("/\,/", $form_data['ip_t4_deleted_row_ids']);

            $ip_t4_ntfs = [];

            // print_r($form_data);

            foreach ($fs_ip_t4_id as $t4_key => $t4_value) 
            {
                array_push($ip_t4_ntfs, 
                    array(
                        'id' => $fs_ip_t4_id[$t4_key],
                        'info' => array(
                                    'fs_company_info_id'        => $fs_company_info_id,
                                    'description_and_location'  => $description_and_location[$t4_key],
                                    'existing_use'              => $existing_use[$t4_key],
                                    'tenure'                    => $tenure[$t4_key],
                                    'unexpired_lease_term'      => $unexpired_lease_term[$t4_key],
                                    'order_by'                  => $t4_key + 1
                                )
                    )
                );
            }

            $ip_t4_ntfs_data = array(
                'table_name'  => 'fs_investment_properties_ntfs_4',
                'deleted_ids' => $ip_t4_ntfs_deleted_ids,
                'ntfs_data'   => $ip_t4_ntfs
            );

            $fs_ip_t4_ids = $this->fs_notes_model->insert_update_tbl_data($ip_t4_ntfs_data);
            /* END OF for Table 4 */


            /* for checkbox part */
            // for checkbox part
            $fs_ip_info_id      = array_values($form_data['fs_ip_info_id']);
            $fs_ip_info_on_off  = array_values($form_data['fs_ip_info_on_off']);
            $list_ip_info_id    = array_values($form_data['list_ip_info_id']);
            $fs_ip_info_content = array_values($form_data['fs_ip_info_content']);

            $fs_ip_info_ids = [];

            foreach ($fs_ip_info_on_off as $key => $value) 
            {
                if($value == 1)
                {
                    if(!empty($fs_ip_info_id[$key])) //    update data
                    {
                        $result = $this->fs_notes_model->update_tbl_data('fs_investment_properties_info', 
                                    array(
                                        array(
                                            'id' => $fs_ip_info_id[$key],
                                            'info' => array(
                                                        'content' => $fs_ip_info_content[$key],
                                                        'is_checked' => 1
                                                    )
                                        )
                                    )
                                );

                        array_push($fs_ip_info_ids, $fs_ip_info_id[$key]);
                    }
                    else // create new
                    {
                        $fs_list_ip_content_id = $list_ip_info_id[$key];

                        $result = $this->db->insert('fs_investment_properties_info', 
                                                array(
                                                    'fs_company_info_id'     => $fs_company_info_id,
                                                    'fs_list_investment_properties_content_id'  => $list_ip_info_id[$key],
                                                    'content'                => $fs_ip_info_content[$key],
                                                    'is_checked'             => 1
                                                )
                                            );
                        array_push($fs_ip_info_ids, $this->db->insert_id());
                    }
                }
                else    // if checkbox is off, change is_checked status to 0
                {
                    if(!empty($fs_ip_info_id[$key]))
                    {
                        // set not checked
                        $result = $this->fs_notes_model->update_tbl_data('fs_investment_properties_info', 
                                    array(
                                        array(
                                            'id' => $fs_ip_info_id[$key],
                                            'info' => array(
                                                        'is_checked' => 0, 
                                                        'content' => $fs_ip_info_content[$key]
                                                    )
                                        )
                                    )
                                );
                    }
                    array_push($fs_ip_info_ids, $fs_ip_info_id[$key]);
                }
            }
            /* END OF for checkbox part */
        }

        /* ------ save/update/delete for table 3 ------ */
        $header_ip_t3   = $form_data['header_ip_t3'];
        $ip_t3_row      = $form_data['ip_t3_row'];
        $ip_t3_last_row = $form_data['ip_t3_last_row'];

        $ip_t3_deleted_row_ids = preg_split ("/\,/", $form_data['ip_t3_deleted_row_ids']);

        /* ----------- save header data "fs_investment_properties_ntfs_3_header" ----------- */
        $header_title_id = $header_ip_t3[0];
        $header_row = [];

        for ($x = 1; $x < count($header_ip_t3) - 1; $x++) 
        {
            array_push($header_row, $header_ip_t3[$x]);
        }

        $header_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header_ip_t3,1))
                        );

        if(!empty($header_ip_t3[0]))
        {
            $info = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_3_header WHERE id=" . $header_ip_t3[0]);
            $info = $info->result_array();

            $header_ip_t3_id = $header_ip_t3[0];

            if($info[0]['header_titles'] != $header_data['header_titles'])
            {
                $this->db->where('id', $header_ip_t3[0]);
                $this->db->update('fs_investment_properties_ntfs_3_header', $header_data);
            }
        }
        else
        {
            $header_result = $this->db->insert('fs_investment_properties_ntfs_3_header', $header_data);
            $header_ip_t3_id     = $this->db->insert_id();
        }

        $ip_t3_row_array      = $this->remake_array_dynamic_column_tbl($ip_t3_row, 'normal', $fs_company_info_id);
        $ip_t3_last_row_array = $this->remake_array_dynamic_column_tbl($ip_t3_last_row, 'last', $fs_company_info_id);

        $ip_t3_rows = array_merge($ip_t3_row_array, $ip_t3_last_row_array);

        // insert order_by
        foreach ($ip_t3_rows as $key => $value) 
        {
            $ip_t3_rows[$key]['info']['order_by'] = $key + 1;
        }

        $ip_t3_ntfs_data = array(
            'table_name'  => 'fs_investment_properties_ntfs_3',
            'deleted_ids' => $ip_t3_deleted_row_ids,
            'ntfs_data'   => $ip_t3_rows
        );

        $fs_ip_t3_ids = $this->fs_notes_model->insert_update_tbl_data($ip_t3_ntfs_data);
        /* ------ END OF save/update/delete for table 3 ------ */

        /* ------ save/update/delete for table 5 ------ */
        $fs_ip_t5_id       = $form_data['fs_ip_t5_id'];
        $ip_t5_has_parent  = $form_data['ip_t5_has_parent'];
        $ip_t5_title_item  = $form_data['ip_t5_title_item'];
        $ip_t5_description = $form_data['ip_t5_description'];
        $ip_t5_group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ip_t5_group_cy']);
        $ip_t5_group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ip_t5_group_ly']);
        $ip_t5_company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ip_t5_company_cy']);
        $ip_t5_company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ip_t5_company_ly']);

        // print_r($ip_t5_has_parent);

        $ip_t5_ntfs_deleted_ids = preg_split ("/\,/", $form_data['ip_t5_ntfs_deleted_ids']);

        $ip_t5_ntfs = [];

        foreach ($ip_t5_title_item as $t5_key => $t5_value) 
        {
            $temp_ip_t5_ntfs = array(
                                    'id' => $fs_ip_t5_id[$t5_key],
                                    'info' => array(
                                                'fs_company_info_id'        => $fs_company_info_id,
                                                'title_item'                => $t5_value,
                                                'description'               => $ip_t5_description[$t5_key],
                                                'value'                     => $ip_t5_company_cy[$t5_key],
                                                'company_end_prev_ye_value' => $ip_t5_company_ly[$t5_key],
                                                'group_end_this_ye_value'   => $ip_t5_group_cy[$t5_key],
                                                'group_end_prev_ye_value'   => $ip_t5_group_ly[$t5_key],
                                                'has_parent'                => $ip_t5_has_parent[$t5_key],
                                                'order_by'                  => $t5_key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_ip_t5_ntfs['info']['group_end_this_ye_value']);
                unset($temp_ip_t5_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_ip_t5_ntfs['info']['group_end_prev_ye_value']);
                unset($temp_ip_t5_ntfs['info']['company_end_prev_ye_value']);
            }

            array_push($ip_t5_ntfs, $temp_ip_t5_ntfs);
        }

        $ip_t5_ntfs_data = array(
            'table_name'  => 'fs_investment_properties_ntfs_5',
            'deleted_ids' => $ip_t5_ntfs_deleted_ids,
            'ntfs_data'   => $ip_t5_ntfs
        );

        $fs_ip_t5_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($ip_t5_ntfs_data);

        if($final_report_type == 1)
        {
            echo json_encode(
                array(
                    'result'            => true, 
                    'final_report_type' => $final_report_type,
                    'header_ip_t1_id'   => $header_ip_t1_id,
                    'fs_ip_t1_ids'      => $fs_ip_t1_ids,
                    'fs_ip_t2_ids'      => $fs_ip_t2_ids,
                    'header_ip_t3_id'   => $header_ip_t3_id,
                    'fs_ip_t3_ids'      => $fs_ip_t3_ids,
                    'fs_ip_t4_ids'      => $fs_ip_t4_ids,
                    'fs_ip_t5_ids'      => $fs_ip_t5_ids,
                    'fs_ip_info_ids'    => $fs_ip_info_ids
                )
            );
        }
        else
        {
            echo json_encode(
                array(
                    'result' => true, 
                    'final_report_type' => $final_report_type,
                    'header_ip_t3_id' => $header_ip_t3_id,
                    'fs_ip_t3_ids' => $fs_ip_t3_ids,
                    'fs_ip_t5_ids' => $fs_ip_t5_ids
                )
            );
        }
        /* ------ END OF save/update/delete for table 5 ------ */
    }

    public function save_ntfs_fs_ppe()
    {
        $form_data = $this->input->post();
        $fs_company_info_id  = $form_data['fs_company_info_id'];

        $header          = $form_data['header'];
        $cost_row        = $form_data['cost_row'];
        $accumulated_row = $form_data['accumulated_row'];
        $carrying_row    = $form_data['carrying_row'];

        $cost_last        = $form_data['cost'];
        $accumulated_last = $form_data['accumulated'];
        $carrying_last    = $form_data['carrying'];

        $ppe_t1_deleted_row_ids = preg_split ("/\,/", $form_data['ppe_t1_deleted_row_ids']);

        /* ----------- save header data "fs_ppe_ntfs_1_header" ----------- */
        $header_title_id = $header[0];
        $header_row = [];

        for ($x = 1; $x < count($header) - 1; $x++) 
        {
            array_push($header_row, $header[$x]);
        }

        $header_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header,1))
                        );

        if(!empty($header[0]))
        {
            $info = $this->db->query("SELECT * FROM fs_ppe_ntfs_1_header WHERE id=" . $header[0]);
            $info = $info->result_array();

            $header_id = $header[0];

            if($info[0]['header_titles'] != $header_data['header_titles'])
            {
                $this->db->where('id', $header[0]);
                $this->db->update('fs_ppe_ntfs_1_header', $header_data);
            }
        }
        else
        {
            $header_result = $this->db->insert('fs_ppe_ntfs_1_header', $header_data);
            $header_id     = $this->db->insert_id();
        }
        /* ----------- END OF save header data "fs_ppe_ntfs_1_header" ----------- */

        $cost_row_array        = $this->remake_array_dynamic_column_tbl($cost_row, 'cost', $fs_company_info_id);
        $accumulated_row_array = $this->remake_array_dynamic_column_tbl($accumulated_row, 'accumulated', $fs_company_info_id);
        $carrying_row_array    = $this->remake_array_dynamic_column_tbl($carrying_row, 'carrying', $fs_company_info_id);

        $cost_last_row_array        = $this->remake_array_dynamic_column_tbl($cost_last, 'last cost', $fs_company_info_id);
        $accumulated_last_row_array = $this->remake_array_dynamic_column_tbl($accumulated_last, 'last accumulated', $fs_company_info_id);
        $carrying_last_row_array    = $this->remake_array_dynamic_column_tbl($carrying_last, 'last carrying', $fs_company_info_id);

        $ppe_t1_rows = array_merge($cost_row_array, $cost_last_row_array, $accumulated_row_array, $accumulated_last_row_array, $carrying_row_array, $carrying_last_row_array);

        // insert order_by
        foreach ($ppe_t1_rows as $key => $value) 
        {
            $ppe_t1_rows[$key]['info']['order_by'] = $key + 1;
        }

        $ppe_t1_ntfs_data = array(
            'table_name'  => 'fs_ppe_ntfs_1',
            'deleted_ids' => $ppe_t1_deleted_row_ids,
            'ntfs_data'   => $ppe_t1_rows
        );

        $fs_ppe_t1_ids = $this->fs_notes_model->insert_update_tbl_data($ppe_t1_ntfs_data);

        // // insert order_by
        // foreach ($ppe_t1_rows as $key => $value) 
        // {
        //     $ppe_t1_rows[$key]['info']['order_by'] = $key + 1;
        // }

        // for checkbox part
        $fs_ppe_info_id      = array_values($form_data['fs_ppe_info_id']);
        $fs_ppe_info_on_off  = array_values($form_data['fs_ppe_info_on_off']);
        $list_ppe_info_id    = array_values($form_data['list_ppe_info_id']);
        $fs_ppe_info_content = array_values($form_data['fs_ppe_info_content']);

        $return_fs_ppe_info_ids = [];

        foreach ($fs_ppe_info_on_off as $key => $value) 
        {
            if($value == 1)
            {
                if(!empty($fs_ppe_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_ppe_info', 
                                array(
                                    array(
                                        'id' => $fs_ppe_info_id[$key],
                                        'info' => array(
                                                    'content' => $fs_ppe_info_content[$key],
                                                    'is_checked' => 1
                                                )
                                    )
                                )
                            );

                    array_push($return_fs_ppe_info_ids, $fs_ppe_info_id[$key]);
                }
                else // create new
                {
                    $fs_list_ppe_content_id = $list_ppe_info_id[$key];

                    $result = $this->db->insert('fs_ppe_info', 
                                    array(
                                        'fs_company_info_id'     => $fs_company_info_id,
                                        'fs_list_ppe_content_id' => $list_ppe_info_id[$key],
                                        'content'                => $fs_ppe_info_content[$key],
                                        'is_checked'             => 1
                                    )
                                );
                    array_push($return_fs_ppe_info_ids, $this->db->insert_id());
                }
            }
            else    // if checkbox is off, change is_checked status to 0
            {
                if(!empty($fs_ppe_info_id[$key]))
                {
                    // set not checked
                    $result = $this->fs_notes_model->update_tbl_data('fs_ppe_info', 
                                array(
                                    array(
                                        'id' => $fs_ppe_info_id[$key],
                                        'info' => array(
                                                    'is_checked' => 0, 
                                                    'content' => $fs_ppe_info_content[$key]
                                                )
                                    )
                                )
                            );
                }
                array_push($return_fs_ppe_info_ids, $fs_ppe_info_id[$key]);
            }
        }

        echo json_encode(
            array(
                'result' => true, 
                'header_id' => $header_id,
                'fs_ppe_t1_ids' => $fs_ppe_t1_ids,
                'return_fs_ppe_info_ids' => $return_fs_ppe_info_ids
            )
        );
    }

    public function save_ntfs_available_for_sale()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        // print_r($form_data);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_afs_id   = $form_data['afs_t1_id'];
        $description = $form_data['afs_t1_description'];
        $part        = $form_data['afs_t1_part'];
        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['afs_t1_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['afs_t1_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['afs_t1_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['afs_t1_company_ly']); 

        $afs_t1_deleted_ids = preg_split ("/\,/", $form_data['afs_t1_deleted_ids']);

        $afs_ntfs = [];

        foreach ($fs_afs_id as $key => $value) 
        {
            $temp_afs_ntfs = array(
                                'id' => $fs_afs_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            'description'               => $description[$key],
                                            'part'                      => $part[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_afs_ntfs['info']['group_end_this_ye_value']);
                unset($temp_afs_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_afs_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_afs_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($afs_ntfs, $temp_afs_ntfs);
        }

        $afs_ntfs_data = array(
            'table_name'  => 'fs_available_for_sale_ntfs',
            'deleted_ids' => $afs_t1_deleted_ids,
            'ntfs_data'   => $afs_ntfs
        );

        $fs_afs_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($afs_ntfs_data);

        /* textarea part */
        $afs_info_id        = $form_data['afs_info_id'];
        $afs_info_content   = $form_data['afs_info_content'];

        $return_afs_info_id = '';

        if(!(empty($afs_info_content) && empty($afs_info_id)))
        {
            $fs_afs_info = array(
                            'deleted_ids' => [],
                            'table_name'  => 'fs_available_for_sale_info',
                            'ntfs_data'   => array(
                                                array(
                                                    'id'   => $afs_info_id,
                                                    'info' => array(
                                                        'fs_company_info_id' => $fs_company_info_id,
                                                        'content'            => $afs_info_content
                                                    )
                                                )
                                            )
                            
                        );

            $return_afs_info_id = $this->fs_notes_model->insert_update_tbl_data($fs_afs_info);
        }
        /* END OF textarea part */

        // return ids
        echo json_encode(
            array(
                'result'        => true,
                'afs_info_id'   => $return_afs_info_id,
                'fs_afs_ids'    => $fs_afs_ids
            )
        );
    }

    public function save_ntfs_fs_inventories()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $inv_t1_id             = $form_data['inv_t1_id'];
        $inv_t1_description    = $form_data['inv_t1_description'];
        $inv_t1_group_ty_val   = $form_data['inv_t1_group_ty_val'];
        $inv_t1_group_ly_val   = $form_data['inv_t1_group_ly_val'];
        $inv_t1_company_ty_val = $form_data['inv_t1_company_ty_val'];
        $inv_t1_company_ly_val = $form_data['inv_t1_company_ly_val'];

        $inv_t1_data = array(
                            'id' => $inv_t1_id,
                            'info' => array(
                                        'fs_company_info_id'        => $fs_company_info_id,
                                        'description'               => $inv_t1_description,
                                        'value'                     => $inv_t1_company_ty_val,
                                        'company_end_prev_ye_value' => $inv_t1_company_ly_val,
                                        'group_end_this_ye_value'   => $inv_t1_group_ty_val,
                                        'group_end_prev_ye_value'   => $inv_t1_group_ly_val,
                                    )
                        );

        if($fs_company_info[0]['first_set'] == '1')
        {
            unset($inv_t1_data['info']['company_end_prev_ye_value']);
            unset($inv_t1_data['info']['group_end_prev_ye_value']);
        }

        if(!empty($inv_t1_id))
        {
            $result = $this->fs_notes_model->update_tbl_data('fs_inventories_ntfs_1', array($inv_t1_data));

            $fs_inv_t1_id = $inv_t1_data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_inventories_ntfs_1', $inv_t1_data['info']);
            $fs_inv_t1_id = $this->db->insert_id(); 
        }

        /* textarea part */
        $fs_inv_info_id        = $form_data['fs_inv_info_id'];
        $fs_inv_info_content   = $form_data['fs_inv_info_content'];

        $fs_inv_info_id = '';

        if(!(empty($fs_inv_info_id) && empty($fs_inv_info_content)))
        {
            $fs_inv_info_1 = array(
                                'deleted_ids' => [],
                                'table_name'  => 'fs_inventories_info',
                                'ntfs_data'   => array(
                                                    array(
                                                        'id'   => $form_data['fs_inv_info_id'],
                                                        'info' => array(
                                                            'fs_company_info_id' => $fs_company_info_id,
                                                            'is_shown'           => $form_data['inv_is_shown'],
                                                            'content'            => $form_data['fs_inv_info_content']
                                                        )
                                                    )
                                                )
                                
                            );

            // print_r($fs_inv_info_1);

            $fs_inv_info_id = $this->fs_notes_model->insert_update_tbl_data($fs_inv_info_1);
        }
        /* END OF textarea part */

        // /* for info part */
        // $fs_inv_info_1 = array(
        //     'id'   => $form_data['fs_inv_info_id'],
        //     'info' => array(
        //         'fs_company_info_id' => $fs_company_info_id,
        //         'is_shown'           => $form_data['inv_is_shown'],
        //         'text_content'       => $form_data['fs_inv_info_content']
        //     )
            
        // );

        // $fs_inv_info_id = $this->fs_notes_model->save_fs_tax_expense_ntfs_info($fs_inv_info_1);
        // /* END OF for info part */

        echo json_encode(
            array(
                'result' => true,
                'fs_inv_t1_id' => $fs_inv_t1_id,
                'fs_inv_info_id' => $fs_inv_info_id
            )
        );
    }

    public function save_contract_assets_and_contract_liabilities_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        // for table part
        $cacl_id     = $form_data['cacl_id'];
        $description = $form_data['cacl_description'];
        $group_cy    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['cacl_group_cy']); 
        $group_ly    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['cacl_group_ly']); 
        $company_cy  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['cacl_company_cy']); 
        $company_ly  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['cacl_company_ly']); 

        $cacl_deleted_ids = preg_split ("/\,/", $form_data['cacl_deleted_ids']);

        $cacl_ntfs = [];

        foreach ($cacl_id as $key => $value) 
        {
            $temp_cacl_ntfs = array(
                                    'id' => $cacl_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $fs_company_info_id,
                                                'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_cacl_ntfs['info']['group_end_this_ye_value']);
                unset($temp_cacl_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_cacl_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_cacl_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($cacl_ntfs, $temp_cacl_ntfs);
        }

        $cacl_ntfs_data = array(
            'table_name'  => 'fs_contract_assets_and_contract_liabilities_ntfs',
            'deleted_ids' => $cacl_deleted_ids,
            'ntfs_data'   => $cacl_ntfs
        );

        $fs_cacl_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($cacl_ntfs_data);
        // END OF for table part

        /* textarea part */
        $cacl_info_id        = $form_data['cacl_info_id'];
        $cacl_info_content   = $form_data['cacl_info_content'];

        $return_cacl_info_id = '';

        if(!(empty($cacl_info_content) && empty($cacl_info_id)))
        {
            $fs_cacl_info = array(
                            'deleted_ids' => [],
                            'table_name'  => 'fs_contract_assets_and_contract_liabilities_info',
                            'ntfs_data'   => array(
                                                array(
                                                    'id'   => $cacl_info_id,
                                                    'info' => array(
                                                        'fs_company_info_id' => $fs_company_info_id,
                                                        'content'            => $cacl_info_content
                                                    )
                                                )
                                            )
                            
                        );

            $return_cacl_info_id = $this->fs_notes_model->insert_update_tbl_data($fs_cacl_info);
        }
        /* END OF textarea part */

        // return ids
        $result_ids = array(
            'result'            => true,
            'final_report_type' => $final_report_type,
            'fs_cacl_ids'       => $fs_cacl_ids,
            'fs_cacl_info_id'   => $return_cacl_info_id
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_trade_and_other_receivables()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        // print_r($form_data);

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        // for table 1
        $tor_t1_1_id   = $form_data['tor_t1_1_id'];
        $tor_t1_1_group_cy    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_t1_1_group_cy']); 
        $tor_t1_1_group_ly    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_t1_1_group_ly']); 

        /* for table 1 */
        $tor_ntfs_1 = [];

        if($fs_company_info[0]['group_type'] != 1)
        {
            foreach ($tor_t1_1_id as $key_t1 => $value_t1) 
            {
                if(!empty($tor_t1_1_id[$key_t1])) //    update data
                {
                    $temp_tor_ntfs_1 = array(
                                            'id' => $tor_t1_1_id[$key_t1],
                                            'info' => array(
                                                        'group_end_this_ye_value' => $tor_t1_1_group_cy[$key_t1],
                                                        'group_end_prev_ye_value' => $tor_t1_1_group_ly[$key_t1]
                                                    )
                                        );

                    if($fs_company_info[0]['first_set'] == '1')
                    {
                        unset($temp_tor_ntfs_1['info']['group_end_prev_ye_value']);
                    }

                    array_push($tor_ntfs_1, $temp_tor_ntfs_1);
                }
            }

            $result = $this->fs_notes_model->update_tbl_data('fs_categorized_account_round_off', $tor_ntfs_1);

            // array_push($return_fs_lb_info_ids, $fs_lb_info_id[$key]);
        }
        /* END OF for table 1 */

        // for table 2
        $fs_tor_id   = $form_data['fs_tor_id'];
        // $description = $form_data['tor_description'];
        $group_cy    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_group_cy']); 
        $group_ly    = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_group_ly']); 
        $company_cy  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_company_cy']); 
        $company_ly  = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['tor_company_ly']); 

        /* ----------- for table 2 ----------- */
        $tor_t2_deleted_ids = preg_split ("/\,/", $form_data['tor_t2_deleted_ids']);

        $tor_ntfs_2 = [];

        foreach ($fs_tor_id as $key => $value) 
        {
            $temp_tor_ntfs_2 = array(
                                    'id' => $fs_tor_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $fs_company_info_id,
                                                // 'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_tor_ntfs_2['info']['group_end_this_ye_value']);
                unset($temp_tor_ntfs_2['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_tor_ntfs_2['info']['company_end_prev_ye_value']);
                unset($temp_tor_ntfs_2['info']['group_end_prev_ye_value']);
            }

            array_push($tor_ntfs_2, $temp_tor_ntfs_2);
        }

        $tor_ntfs_2_data = array(
            'table_name'  => 'fs_trade_and_other_receivables_ntfs_2',
            'deleted_ids' => $tor_t2_deleted_ids,
            'ntfs_data'   => $tor_ntfs_2
        );

        $fs_tor_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($tor_ntfs_2_data);
        /* ----------- END OF for table 2 ----------- */

        /* ----------- For full set ----------- */
        if($final_report_type == 1)
        {
            /* ----------- for table 3 ----------- */
            $fs_tor_t3_id       = $form_data['fs_tor_t3_id'];
            $fs_list_content_id = $form_data['tor_t3_fs_list_trade_and_other_receivables_ntfs_3_id'];
            $group_cy           = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t3_group_cy']);
            $group_ly           = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t3_group_ly']); 
            $company_cy         = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t3_company_cy']); 
            $company_ly         = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t3_company_ly']); 

            $tor_t3_ntfs = [];

            foreach ($fs_tor_t3_id as $key => $value) 
            {
                $temp_tor_t3_ntfs = array(
                                    'id' => $fs_tor_t3_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'fs_list_trade_and_other_receivables_ntfs_3_id' => $fs_list_content_id[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_tor_t3_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_tor_t3_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_tor_t3_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_tor_t3_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($tor_t3_ntfs, $temp_tor_t3_ntfs);
            }

            $tor_t3_ntfs_data = array(
                'table_name'  => 'fs_trade_and_other_receivables_ntfs_3',
                'deleted_ids' => [],
                'ntfs_data'   => $tor_t3_ntfs
            );

            $fs_tor_t3_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($tor_t3_ntfs_data);

            /* ----------- END OF for table 3 ----------- */

            /* ----------- for table 4 ----------- */
            $fs_tor_t4_id       = $form_data['fs_tor_t4_id'];
            $fs_list_content_id = $form_data['tor_t4_fs_list_trade_and_other_receivables_ntfs_4_id'];
            $group_cy           = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t4_group_cy']);
            $group_ly           = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t4_group_ly']); 
            $company_cy         = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t4_company_cy']); 
            $company_ly         = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['tor_t4_company_ly']); 

            $tor_t4_ntfs = [];

            foreach ($fs_tor_t4_id as $key => $value) 
            {
                $temp_tor_t4_ntfs = array(
                                    'id' => $fs_tor_t4_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'fs_list_trade_and_other_receivables_ntfs_4_id' => $fs_list_content_id[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_tor_t4_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_tor_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_tor_t4_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_tor_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($tor_t4_ntfs, $temp_tor_t4_ntfs);
            }

            $tor_t4_ntfs_data = array(
                'table_name'  => 'fs_trade_and_other_receivables_ntfs_4',
                'deleted_ids' => [],
                'ntfs_data'   => $tor_t4_ntfs
            );

            $fs_tor_t4_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($tor_t4_ntfs_data);
            /* ----------- END OF for table 4 ----------- */

            /* for checkbox part */
            $fs_tor_info_id             = $form_data['fs_tor_info_id'];
            $fs_tor_info_on_off         = $form_data['fs_tor_info_on_off'];
            // $fs_tor_info_checkbox       = $form_data['fs_tor_info_checkbox'];
            $fs_list_tor_content_id     = $form_data['fs_list_tor_content_id'];
            $tor_info_checkbox_content  = $form_data['tor_info_checkbox_content'];

            $tor_info = [];

            foreach ($fs_tor_info_on_off as $cbx_key => $cbx_value) 
            {
                $temp_tor_info = array(
                                    'id'   => $fs_tor_info_id[$cbx_key],
                                    'info' => array(
                                                    'fs_company_info_id' => $fs_company_info_id,
                                                    'fs_list_trade_and_other_receivables_content_id' => $fs_list_tor_content_id[$cbx_key],
                                                    'content'            => $tor_info_checkbox_content[$cbx_key],
                                                    'is_checked'         => $fs_tor_info_on_off[$cbx_key],
                                                    'order_by'           => $cbx_key + 1
                                                )
                                );

                array_push($tor_info, $temp_tor_info);
            }

            $tor_info_data = array(
                'table_name'  => 'fs_trade_and_other_receivables_info',
                'deleted_ids' => [],
                'ntfs_data'   => $tor_info
            );

            $fs_tor_info_ids = $this->fs_notes_model->insert_update_tbl_data($tor_info_data);

            /* END OF for checkbox part */

            // return ids
            $result_ids = array(
                'result'            => true,
                'final_report_type' => $final_report_type,
                'fs_tor_ids'        => $fs_tor_ids,
                'fs_tor_t3_ids'     => $fs_tor_t3_ids,
                'fs_tor_t4_ids'     => $fs_tor_t4_ids,
                'fs_tor_info_ids'   => $fs_tor_info_ids
            );
        } /* ----------- END OF For full set ----------- */
        else
        {
            // return ids
            $result_ids = array(
                'result'            => true,
                'final_report_type' => $final_report_type,
                'fs_tor_ids'        => $fs_tor_ids
            );
        }

        echo json_encode($result_ids);
    }

    public function save_ntfs_other_current_assets()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_oca_id   = $form_data['fs_oca_id'];
        $description = $form_data['oca_description'];
        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['oca_group_cy']);
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['oca_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['oca_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['oca_company_ly']); 

        $oca_deleted_ids = preg_split ("/\,/", $form_data['oca_deleted_ids']);

        $oca_ntfs = [];

        foreach ($fs_oca_id as $key => $value) 
        {
            $temp_oca_ntfs = array(
                                'id' => $fs_oca_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            'description'               => $description[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_oca_ntfs['info']['group_end_this_ye_value']);
                unset($temp_oca_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_oca_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_oca_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($oca_ntfs, $temp_oca_ntfs);
        }

        $oca_ntfs_data = array(
            'table_name'  => 'fs_other_current_assets_ntfs',
            'deleted_ids' => $oca_deleted_ids,
            'ntfs_data'   => $oca_ntfs
        );

        $fs_oca_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($oca_ntfs_data);

        // return ids
        $result_ids = array(
            'result'        => true,
            'fs_oca_ids'    => $fs_oca_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_fs_cash_short_term_deposits()
    {
        $form_data = $this->input->post();

        // print_r($form_data);

        $fs_company_info_id = $form_data['fs_company_info_id'];
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        /* ------- for Table 1 ------- */
        $fs_csd_t1_id   = $form_data['fs_csd_t1_id'];
        $description = $form_data['csd_t1_description'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t1_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t1_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t1_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t1_company_ly']); 

        $csd_t1_deleted_ids = preg_split ("/\,/", $form_data['csd_t1_deleted_ids']);

        $csd_t1_ntfs = [];

        foreach ($fs_csd_t1_id as $key => $value) 
        {
            $temp_csd_t1_ntfs = array(
                                    'id' => $fs_csd_t1_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_csd_t1_ntfs['info']['group_end_this_ye_value']);
                unset($temp_csd_t1_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_csd_t1_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_csd_t1_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($csd_t1_ntfs, $temp_csd_t1_ntfs);
        }

        $csd_t1_ntfs_data = array(
            'table_name'  => 'fs_cash_short_term_deposits_ntfs_1',
            'deleted_ids' => $csd_t1_deleted_ids,
            'ntfs_data'   => $csd_t1_ntfs
        );

        $fs_csd_t1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($csd_t1_ntfs_data);
        /* ------- END OF for Table 1 ------- */

        /* ------- for Table 2 ------- */
        $fs_csd_t2_id   = $form_data['fs_csd_t2_id'];
        // $description = $form_data['csd_t2_description'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t2_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t2_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t2_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t2_company_ly']); 

        $csd_t2_deleted_ids = preg_split ("/\,/", $form_data['csd_t2_deleted_ids']);

        $csd_t2_ntfs = [];

        foreach ($fs_csd_t2_id as $key => $value) 
        {
            $temp_csd_t2_ntfs = array(
                                'id' => $fs_csd_t2_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            // 'description'               => $description[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_csd_t2_ntfs['info']['group_end_this_ye_value']);
                unset($temp_csd_t2_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_csd_t2_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_csd_t2_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($csd_t2_ntfs, $temp_csd_t2_ntfs);
        }

        $csd_t2_ntfs_data = array(
            'table_name'  => 'fs_cash_short_term_deposits_ntfs_2',
            'deleted_ids' => $csd_t2_deleted_ids,
            'ntfs_data'   => $csd_t2_ntfs
        );

        $fs_csd_t2_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($csd_t2_ntfs_data);
        /* ------- END OF for Table 2 ------- */

        /* ------- for Table 3 ------- */
        $fs_csd_t3_id   = $form_data['fs_csd_t3_id'];
        $description = $form_data['csd_t3_description'];
        $part        = $form_data['csd_t3_part'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t3_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t3_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t3_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['csd_t3_company_ly']); 

        $csd_t3_ntfs = [];

        foreach ($fs_csd_t3_id as $key => $value) 
        {
            $temp_csd_t3_ntfs = array(
                                'id' => $fs_csd_t3_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            'description'               => $description[$key],
                                            'part'                      => $part[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_csd_t3_ntfs['info']['group_end_this_ye_value']);
                unset($temp_csd_t3_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_csd_t3_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_csd_t3_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($csd_t3_ntfs, $temp_csd_t3_ntfs);
        }

        $csd_t3_ntfs_data = array(
            'table_name'  => 'fs_cash_short_term_deposits_ntfs_3',
            'deleted_ids' => [],
            'ntfs_data'   => $csd_t3_ntfs
        );

        $fs_csd_t3_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($csd_t3_ntfs_data);
        /* ------- END OF for Table 3 ------- */

        /* ------- for textarea ------- */
        $csd_info_id        = $form_data['csd_info_id'];
        $csd_info_content   = $form_data['csd_info_content'];

        $fs_csd_info_id = '';

        if(!(empty($csd_info_content) && empty($csd_info_id)))
        {
            $csd_info_ntfs = array(
                'id'   => $csd_info_id,
                'info' => array(
                    'fs_company_info_id' => $fs_company_info_id,
                    'content'            => $csd_info_content
                )
            );

            // print_r($csd_info_ntfs);

            $csd_info_ntfs_data = array(
                'table_name'  => 'fs_cash_short_term_deposits_info',
                'deleted_ids' => [],
                'ntfs_data'   => array($csd_info_ntfs)
            );

            $fs_csd_info_id = $this->fs_notes_model->insert_update_tbl_data($csd_info_ntfs_data);
        }

        /* ------- END OF for textarea ------- */

        // return ids
        $result_ids = array(
            'result'         => true,
            'fs_csd_t1_ids'  => $fs_csd_t1_ids,
            'fs_csd_t2_ids'  => $fs_csd_t2_ids,
            'fs_csd_t3_ids'  => $fs_csd_t3_ids,
            'fs_csd_info_id' => $fs_csd_info_id 
        );

        echo json_encode($result_ids);
    }

    public function save_share_capital_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $sc_info_id        = $form_data['sc_info_id'];
        $sc_info_content   = $form_data['sc_info_content'];

        $return_sc_info_id = '';

        if(!(empty($sc_info_content) && empty($sc_info_id)))
        {
            $fs_sc_info = array(
                'id'   => $sc_info_id,
                'info' => array(
                    'fs_company_info_id' => $fs_company_info_id,
                    'content'            => $sc_info_content
                )
            );

            $return_sc_info_id = $this->fs_notes_model->save_fs_share_capital_info($fs_sc_info);
        }

        // return ids
        $result_ids = array(
            'result'        => true,
            'sc_info_id'    => $return_sc_info_id
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_deferred_tax_liabilities()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_dtl_id   = $form_data['fs_dtl_id'];
        $description = $form_data['dtl_description'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['dtl_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['dtl_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['dtl_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['dtl_company_ly']); 

        $dtl_deleted_ids = preg_split ("/\,/", $form_data['dtl_deleted_ids']);

        $dtl_ntfs = [];

        foreach ($fs_dtl_id as $key => $value) 
        {
            $temp_dtl_ntfs = array(
                                'id' => $fs_dtl_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            'description'               => $description[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_dtl_ntfs['info']['group_end_this_ye_value']);
                unset($temp_dtl_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_dtl_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_dtl_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($dtl_ntfs, $temp_dtl_ntfs);
        }

        $dtl_ntfs_data = array(
            'table_name'  => 'fs_deferred_tax_liabilities_ntfs',
            'deleted_ids' => $dtl_deleted_ids,
            'ntfs_data'   => $dtl_ntfs
        );

        $fs_dtl_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($dtl_ntfs_data);

        // return ids
        $result_ids = array(
            'result'        => true,
            'fs_dtl_ids'    => $fs_dtl_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_loans_and_borrowings()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        // print_r($form_data);

        $fs_lb_info_id        = array_values($form_data['fs_lb_info_id']);
        $fs_lb_info_on_off    = array_values($form_data['fs_lb_info_on_off']);
        $fs_list_lb_content_id = array_values($form_data['fs_list_lb_content_id']);
        $lb_info_checkbox_content = array_values($form_data['lb_info_checkbox_content']);

        $fs_lb_t3_id = $form_data['fs_lb_t3_id'];
        // $description = $form_data['lb_t3_description'];
        $lb_t3_group_cy    = $form_data['lb_t3_group_cy'];
        $lb_t3_group_ly    = $form_data['lb_t3_group_ly'];
        $lb_t3_company_cy  = $form_data['lb_t3_company_cy'];
        $lb_t3_company_ly  = $form_data['lb_t3_company_ly'];

        /* ------------- for the checkbox ------------- */
        $return_fs_lb_info_ids = [];

        foreach ($fs_lb_info_on_off as $key => $value) 
        {
            // if($value == 1)
            // {
                if(!empty($fs_lb_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_loans_and_borrowings_info', 
                                array(
                                    array(
                                        'id' => $fs_lb_info_id[$key],
                                        'info' => array(
                                                    'content' => $lb_info_checkbox_content[$key],
                                                    'order_by' => $key + 1,
                                                    'is_checked' => $fs_lb_info_on_off[$key]
                                                )
                                    )
                                )
                            );

                    array_push($return_fs_lb_info_ids, $fs_lb_info_id[$key]);
                }
                else // create new
                {
                    $fs_list_loans_and_borrowings_id = $fs_list_lb_content_id[$key];

                    $result = $this->db->insert('fs_loans_and_borrowings_info', 
                                    array(
                                        'fs_company_info_id'               => $fs_company_info_id,
                                        'fs_list_loans_and_borrowings_id'  => $fs_list_lb_content_id[$key],
                                        'content'                          => $lb_info_checkbox_content[$key],
                                        'order_by'                         => $key + 1,
                                        'is_checked'                       => $fs_lb_info_on_off[$key]
                                    )
                                );
                    array_push($return_fs_lb_info_ids, $this->db->insert_id());

                }
            // }
            // else    // if checkbox is off, change is_checked status to 0
            // {
            //     if(!empty($fs_lb_info_id[$key]))
            //     {
            //         // set not checked
            //         $result = $this->fs_notes_model->update_tbl_data('fs_loans_and_borrowings_info', 
            //                     array(
            //                         array(
            //                             'id' => $fs_lb_info_id[$key],
            //                             'info' => array(
            //                                         'order_by'   => $key,
            //                                         'is_checked' => 0, 
            //                                     )
            //                         )
            //                     )
            //                 );
            //     }

            //     array_push($return_fs_lb_info_ids, $fs_lb_info_id[$key]);
            // }
        }
        /* ------------- END OF for the checkbox ------------- */

        if($final_report_type == 1)
        {
            // save table 5 & table 2
            /* for table 2 */
            $fs_lb_t2_id     = $form_data['fs_lb_t2_id'];
            $is_subtotal     = $form_data['fs_lb_t2_is_subtotal'];
            $is_last_section = $form_data['fs_lb_t2_is_last_section'];
            $is_title        = $form_data['fs_lb_t2_is_title'];
            $prior_current   = $form_data['fs_lb_t2_prior_current'];
            $description     = $form_data['fs_lb_t2_description'];
            $value_1         = $form_data['fs_lb_t2_value_1'];
            $value_2         = $form_data['fs_lb_t2_value_2'];
            $value_3         = $form_data['fs_lb_t2_value_3'];
            $value_4         = $form_data['fs_lb_t2_value_4'];

            $lb_t2_ntfs = [];

            foreach ($fs_lb_t2_id as $key => $value) 
            {
                $temp_lb_t2_ntfs = array(
                                        'id' => $fs_lb_t2_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id' => $fs_company_info_id,
                                                    'is_subtotal'        => $is_subtotal[$key],
                                                    'is_last_section'    => $is_last_section[$key],
                                                    'is_title'           => $is_title[$key],
                                                    'prior_current'      => $prior_current[$key],
                                                    'description'        => $description[$key],
                                                    'value_1'            => $value_1[$key],
                                                    'value_2'            => $value_2[$key],
                                                    'value_3'            => $value_3[$key],
                                                    'value_4'            => $value_4[$key],
                                                    'order_by'           => $key + 1
                                                )
                                    );

                array_push($lb_t2_ntfs, $temp_lb_t2_ntfs);
            }

            $lb_t2_ntfs_data = array(
                'table_name'  => 'fs_loans_and_borrowings_ntfs_2',
                'deleted_ids' => [],
                'ntfs_data'   => $lb_t2_ntfs
            );

            $fs_lb_t2_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($lb_t2_ntfs_data);
            /* END OF for table 2 */

            /* for table 5 */
            $fs_lb_t5_id    = $form_data['fs_lb_t5_id'];
            $is_main_title  = $form_data['fs_lb_t5_is_main_title'];
            $main_title     = $form_data['fs_lb_t5_main_title'];
            $is_subtitle    = $form_data['fs_lb_t5_is_subtitle'];
            $subtitle       = $form_data['fs_lb_t5_subtitle'];
            $description    = $form_data['fs_lb_t5_description'];
            $group_cy       = $form_data['fs_lb_t5_group_cy'];
            $group_ly       = $form_data['fs_lb_t5_group_ly'];
            $company_cy     = $form_data['fs_lb_t5_company_cy'];
            $company_ly     = $form_data['fs_lb_t5_company_ly'];

            $lb_t5_deleted_ids = preg_split ("/\,/", $form_data['lb_t5_deleted_ids']);

            $lb_t5_ntfs = [];

            foreach ($fs_lb_t5_id as $key => $value) 
            {
                $temp_lb_t5_ntfs = array(
                                        'id' => $fs_lb_t5_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'is_main_title'             => $is_main_title[$key],
                                                    'main_title'                => $main_title[$key],
                                                    'is_subtitle'               => $is_subtitle[$key],
                                                    'subtitle'                  => $subtitle[$key],
                                                    'description'               => $description[$key],
                                                    'value'                     => $company_cy[$key],
                                                    'company_end_prev_ye_value' => $company_ly[$key],
                                                    'group_end_this_ye_value'   => $group_cy[$key],
                                                    'group_end_prev_ye_value'   => $group_ly[$key],
                                                    'order_by'                  => $key + 1
                                                )
                                    );

                // print_r($temp_lb_t5_ntfs);

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_lb_t5_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_lb_t5_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_lb_t5_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_lb_t5_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($lb_t5_ntfs, $temp_lb_t5_ntfs);
            }

            $lb_t5_ntfs_data = array(
                'table_name'  => 'fs_loans_and_borrowings_ntfs_5',
                'deleted_ids' => $lb_t5_deleted_ids,
                'ntfs_data'   => $lb_t5_ntfs
            );

            $fs_lb_t5_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($lb_t5_ntfs_data);
            /* END OF for table 5 */
        }
        else
        {
            /* ------------- for table 1 ------------- */
            $fs_lb_t1_id       = $form_data['fs_lb_t1_id'];
            $fs_lb_t1_is_last_section = $form_data['fs_lb_t1_is_last_section'];
            $lb_t1_description = $form_data['lb_t1_description'];
            $lb_t1_group_cy    = $form_data['lb_t1_group_cy'];
            $lb_t1_group_ly    = $form_data['lb_t1_group_ly'];
            $lb_t1_company_cy  = $form_data['lb_t1_company_cy'];
            $lb_t1_company_ly  = $form_data['lb_t1_company_ly'];

            $lb_t1_deleted_ids = preg_split ("/\,/", $form_data['lb_t1_deleted_ids']);

            $lb_t1_ntfs = [];

            foreach ($fs_lb_t1_id as $key => $value) 
            {
                $temp_lb_t1_ntfs = array(
                                        'id' => $fs_lb_t1_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'is_last_section'           => $fs_lb_t1_is_last_section[$key],
                                                    'description'               => $lb_t1_description[$key],
                                                    'value'                     => $lb_t1_company_cy[$key],
                                                    'company_end_prev_ye_value' => $lb_t1_company_ly[$key],
                                                    'group_end_this_ye_value'   => $lb_t1_group_cy[$key],
                                                    'group_end_prev_ye_value'   => $lb_t1_group_ly[$key],
                                                    'order_by'                  => $key + 1
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_lb_t1_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_lb_t1_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_lb_t1_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_lb_t1_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($lb_t1_ntfs, $temp_lb_t1_ntfs);
            }

            $lb_t1_ntfs_data = array(
                'table_name'  => 'fs_loans_and_borrowings_ntfs_1',
                'deleted_ids' => $lb_t1_deleted_ids,
                'ntfs_data'   => $lb_t1_ntfs
            );

            $fs_lb_t1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($lb_t1_ntfs_data);
            /* ------------- END OF for table 1 ------------- */

            /* ------------- for table 4 ------------- */
            $fs_lb_t4_id       = $form_data['fs_lb_t4_id'];
            $lb_t4_is_title    = $form_data['lb_t4_is_title'];
            $lb_t4_description = $form_data['lb_t4_description'];
            $lb_t4_group_cy    = $form_data['lb_t4_group_cy'];
            $lb_t4_group_ly    = $form_data['lb_t4_group_ly'];
            $lb_t4_company_cy  = $form_data['lb_t4_company_cy'];
            $lb_t4_company_ly  = $form_data['lb_t4_company_ly'];

            $lb_t4_deleted_ids = preg_split ("/\,/", $form_data['lb_t4_deleted_ids']);
            $lb_t4_ntfs = [];
            $lb_t4_index = 1;

            foreach ($lb_t4_description as $key => $value) 
            {
                $temp_lb_t4_ntfs = array(
                                        'id' => $fs_lb_t4_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                    'is_title'                  => $lb_t4_is_title[$key],
                                                    'is_last_section'           => 0,
                                                    'description'               => $lb_t4_description[$key],
                                                    'value'                     => $lb_t4_company_cy[$key],
                                                    'company_end_prev_ye_value' => $lb_t4_company_ly[$key],
                                                    'group_end_this_ye_value'   => $lb_t4_group_cy[$key],
                                                    'group_end_prev_ye_value'   => $lb_t4_group_ly[$key],
                                                    'order_by'                  => $lb_t4_index
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_lb_t4_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_lb_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_lb_t4_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_lb_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($lb_t4_ntfs, $temp_lb_t4_ntfs);

                $lb_t4_index++;
            }

            // last section
            $fs_lb_t4_ls_id       = $form_data['fs_lb_t4_ls_id'];
            $lb_t4_ls_is_title    = $form_data['lb_t4_ls_is_title'];
            $lb_t4_ls_description = $form_data['lb_t4_ls_description'];
            $lb_t4_ls_group_cy    = $form_data['lb_t4_ls_group_cy'];
            $lb_t4_ls_group_ly    = $form_data['lb_t4_ls_group_ly'];
            $lb_t4_ls_company_cy  = $form_data['lb_t4_ls_company_cy'];
            $lb_t4_ls_company_ly  = $form_data['lb_t4_ls_company_ly'];

            foreach ($fs_lb_t4_ls_id as $key => $value) 
            {
                $temp_lb_t4_ntfs = array(
                                        'id' => $fs_lb_t4_ls_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                    'is_title'                  => $lb_t4_ls_is_title[$key],
                                                    'is_last_section'           => 1,
                                                    'description'               => $lb_t4_ls_description[$key],
                                                    'value'                     => $lb_t4_ls_company_cy[$key],
                                                    'company_end_prev_ye_value' => $lb_t4_ls_company_ly[$key],
                                                    'group_end_this_ye_value'   => $lb_t4_ls_group_cy[$key],
                                                    'group_end_prev_ye_value'   => $lb_t4_ls_group_ly[$key],
                                                    'order_by'                  => $lb_t4_index
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_lb_t4_ntfs['info']['group_end_this_ye_value']);
                    unset($temp_lb_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_lb_t4_ntfs['info']['company_end_prev_ye_value']);
                    unset($temp_lb_t4_ntfs['info']['group_end_prev_ye_value']);
                }

                array_push($lb_t4_ntfs, $temp_lb_t4_ntfs);

                $lb_t4_index++;
            }

            $lb_t4_ntfs_data = array(
                'table_name'  => 'fs_loans_and_borrowings_ntfs_4',
                'deleted_ids' => $lb_t4_deleted_ids,
                'ntfs_data'   => $lb_t4_ntfs
            );

            $fs_lb_t4_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($lb_t4_ntfs_data);
            /* ------------- END OF for table 4 ------------- */
        }
        

        /* ------------- for table 3 ------------- */
        $lb_t3_deleted_ids = preg_split ("/\,/", $form_data['lb_t3_deleted_ids']);

        $lb_t3_ntfs = [];

        foreach ($fs_lb_t3_id as $key => $value) 
        { 
            $temp_lb_t3_ntfs = array(
                                    'id' => $fs_lb_t3_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $fs_company_info_id,
                                                // 'description'               => $description[$key],
                                                'value'                     => $lb_t3_group_cy[$key],
                                                'company_end_prev_ye_value' => $lb_t3_group_ly[$key],
                                                'group_end_this_ye_value'   => $lb_t3_company_cy[$key],
                                                'group_end_prev_ye_value'   => $lb_t3_company_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_lb_t3_ntfs['info']['group_end_this_ye_value']);
                unset($temp_lb_t3_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_lb_t3_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_lb_t3_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($lb_t3_ntfs, $temp_lb_t3_ntfs);
        }

        $lb_t3_ntfs_data = array(
            'table_name'  => 'fs_loans_and_borrowings_ntfs_3',
            'deleted_ids' => $lb_t3_deleted_ids,
            'ntfs_data'   => $lb_t3_ntfs
        );

        $fs_lb_t3_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($lb_t3_ntfs_data);
        /* ------------- END OF for table 3 ------------- */

        if($final_report_type == 1)
        {
            // return ids
            $result_ids = array(
                'result'          => true,
                'final_report_type' => $final_report_type,
                'fs_lb_info_ids'  => $return_fs_lb_info_ids,
                'fs_lb_t2_ids'    => $fs_lb_t2_ids,
                'fs_lb_t3_ids'    => $fs_lb_t3_ids,
                'fs_lb_t5_ids'    => $fs_lb_t5_ids
            );
        }
        else
        {
            // return ids
            $result_ids = array(
                'result'          => true,
                'final_report_type' => $final_report_type,
                'fs_lb_info_ids'  => $return_fs_lb_info_ids,
                'fs_lb_t1_ids'    => $fs_lb_t1_ids,
                'fs_lb_t3_ids'    => $fs_lb_t3_ids,
                'fs_lb_t4_ids'    => $fs_lb_t4_ids
            );
        }

        echo json_encode($result_ids);
    }

    public function save_ntfs_provision()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id); 
        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        $fs_pro_id   = $form_data['pro_t1_id'];
        $description = $form_data['pro_t1_description'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['pro_t1_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['pro_t1_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['pro_t1_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['pro_t1_company_ly']); 

        $pro_deleted_ids = preg_split ("/\,/", $form_data['pro_ntfs_1_deleted_ids']);

        $pro_ntfs = [];

        foreach ($fs_pro_id as $key => $value) 
        {
            $temp_pro_ntfs = array(
                                    'id' => $fs_pro_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_pro_ntfs['info']['group_end_this_ye_value']);
                unset($temp_pro_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_pro_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_pro_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($pro_ntfs, $temp_pro_ntfs);
        }

        $pro_ntfs_data = array(
            'table_name'        => 'fs_provision_ntfs',
            'deleted_ids'       => $pro_deleted_ids,
            'ntfs_data'         => $pro_ntfs
        );

        $fs_pro_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($pro_ntfs_data);

         // for checkbox part
        $fs_pro_info_id      = array_values($form_data['fs_pro_info_id']);
        $fs_pro_info_on_off  = array_values($form_data['fs_pro_info_on_off']);
        $list_pro_info_id    = array_values($form_data['list_pro_info_id']);
        $fs_pro_info_content = array_values($form_data['fs_pro_info_content']);

        $return_fs_pro_info_ids = [];

        foreach ($fs_pro_info_on_off as $key => $value) 
        {
            if($value == 1)
            {
                if(!empty($fs_pro_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_provision_info', 
                                array(
                                    array(
                                        'id' => $fs_pro_info_id[$key],
                                        'info' => array(
                                                    'content' => $fs_pro_info_content[$key],
                                                    'is_checked' => 1
                                                )
                                    )
                                )
                            );

                    array_push($return_fs_pro_info_ids, $fs_pro_info_id[$key]);
                }
                else // create new
                {
                    $fs_list_pro_content_id = $list_pro_info_id[$key];

                    $result = $this->db->insert('fs_provision_info', 
                                    array(
                                        'fs_company_info_id'     => $fs_company_info_id,
                                        'fs_list_provision_content_id' => $list_pro_info_id[$key],
                                        'content'                => $fs_pro_info_content[$key],
                                        'is_checked'             => 1
                                    )
                                );
                    array_push($return_fs_pro_info_ids, $this->db->insert_id());
                }
            }
            else    // if checkbox is off, change is_checked status to 0
            {
                if(!empty($fs_pro_info_id[$key]))
                {
                    // set not checked
                    $result = $this->fs_notes_model->update_tbl_data('fs_provision_info', 
                                array(
                                    array(
                                        'id' => $fs_pro_info_id[$key],
                                        'info' => array(
                                                    'is_checked' => 0, 
                                                    'content' => $fs_pro_info_content[$key]
                                                )
                                    )
                                )
                            );
                }
                array_push($return_fs_pro_info_ids, $fs_pro_info_id[$key]);
            }
        }

        // return ids
        $result_ids = array(
            'result'            => true,
            'final_report_type' => $final_report_type,
            'fs_pro_ids'        => $fs_pro_ids,
            'fs_pro_info_ids'   => $return_fs_pro_info_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_trade_and_other_payables()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // for table 1
        $top_t1_id       = $form_data['top_t1_id'];
        $top_t1_group_cy = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['top_t1_group_cy']); 
        $top_t1_group_ly = array_map(function($v){ return (empty($v)) ? 0 : $v; }, $form_data['top_t1_group_ly']); 

        /* for table 1 */
        $top_ntfs_1 = [];

        if($fs_company_info[0]['group_type'] != 1)
        {
            foreach ($top_t1_id as $key_t1 => $value_t1) 
            {
                if(!empty($top_t1_id[$key_t1])) //    update data
                {
                    $temp_top_ntfs_1 = array(
                                            'id' => $top_t1_id[$key_t1],
                                            'info' => array(
                                                        'group_end_this_ye_value' => $top_t1_group_cy[$key_t1],
                                                        'group_end_prev_ye_value' => $top_t1_group_ly[$key_t1]
                                                    )
                                        );

                    if($fs_company_info[0]['first_set'] == '1')
                    {
                        unset($temp_top_ntfs_1['info']['group_end_prev_ye_value']);
                    }

                    array_push($top_ntfs_1, $temp_top_ntfs_1);
                }
            }

            $result = $this->fs_notes_model->update_tbl_data('fs_categorized_account_round_off', $top_ntfs_1);
        }
        /* END OF for table 1 */

        $fs_top_id   = $form_data['fs_top_id'];
        // $description = $form_data['top_description'];

        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['top_group_cy']);
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['top_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['top_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['top_company_ly']); 

        $top_deleted_ids = preg_split ("/\,/", $form_data['top_deleted_ids']);

        $top_ntfs = [];

        foreach ($fs_top_id as $key => $value) 
        {
            $temp_top_ntfs = array(
                                    'id' => $fs_top_id[$key],
                                    'info' => array(
                                                'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                // 'description'               => $description[$key],
                                                'value'                     => $company_cy[$key],
                                                'company_end_prev_ye_value' => $company_ly[$key],
                                                'group_end_this_ye_value'   => $group_cy[$key],
                                                'group_end_prev_ye_value'   => $group_ly[$key],
                                                'order_by'                  => $key + 1
                                            )
                                );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_top_ntfs['info']['group_end_this_ye_value']);
                unset($temp_top_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_top_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_top_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($top_ntfs, $temp_top_ntfs);
        }

        $top_ntfs_data = array(
            'table_name'  => 'fs_trade_and_other_payables_ntfs_2',
            'deleted_ids' => $top_deleted_ids,
            'ntfs_data'   => $top_ntfs
        );

        $fs_top_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($top_ntfs_data);

        // for checkbox part
        $fs_top_info_id      = array_values($form_data['fs_top_info_id']);
        $fs_top_info_on_off  = array_values($form_data['fs_top_info_on_off']);
        $list_top_info_id    = array_values($form_data['list_top_info_id']);
        $fs_top_info_content = array_values($form_data['fs_top_info_content']);

        $return_fs_top_info_ids = [];

        foreach ($fs_top_info_on_off as $key => $value) 
        {
            if($value == 1)
            {
                if(!empty($fs_top_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_trade_and_other_payables_info', 
                                array(
                                    array(
                                        'id' => $fs_top_info_id[$key],
                                        'info' => array(
                                                    'content' => $fs_top_info_content[$key],
                                                    'is_checked' => 1
                                                )
                                    )
                                )
                            );

                    array_push($return_fs_top_info_ids, $fs_top_info_id[$key]);
                }
                else // create new
                {
                    $fs_list_top_content_id = $list_top_info_id[$key];

                    $result = $this->db->insert('fs_trade_and_other_payables_info', 
                                    array(
                                        'fs_company_info_id'                          => $fs_company_info_id,
                                        'fs_list_trade_and_other_payables_content_id' => $list_top_info_id[$key],
                                        'content'                                     => $fs_top_info_content[$key],
                                        'is_checked'                                  => 1
                                    )
                                );
                    array_push($return_fs_top_info_ids, $this->db->insert_id());
                }
            }
            else    // if checkbox is off, change is_checked status to 0
            {
                if(!empty($fs_top_info_id[$key]))
                {
                    // set not checked
                    $result = $this->fs_notes_model->update_tbl_data('fs_trade_and_other_payables_info', 
                                array(
                                    array(
                                        'id' => $fs_top_info_id[$key],
                                        'info' => array(
                                                    'is_checked' => 0, 
                                                    'content' => $fs_top_info_content[$key]
                                                )
                                    )
                                )
                            );
                }
                array_push($return_fs_top_info_ids, $fs_top_info_id[$key]);
            }
        }

        // return ids
        $result_ids = array(
            'result'        => true,
            'fs_top_ids'    => $fs_top_ids,
            'fs_top_info_ids' => $return_fs_top_info_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_other_current_liabilities()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_ocl_id   = $form_data['fs_ocl_id'];
        $description = $form_data['ocl_description'];
        $group_cy    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ocl_group_cy']); 
        $group_ly    = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ocl_group_ly']); 
        $company_cy  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ocl_company_cy']); 
        $company_ly  = array_map(function($v){ return (is_null($v)) ? 0 : $v; }, $form_data['ocl_company_ly']); 

        // print_r($form_data);

        $ocl_deleted_ids = preg_split ("/\,/", $form_data['ocl_deleted_ids']);

        $ocl_ntfs = [];

        foreach ($fs_ocl_id as $key => $value) 
        {
            $temp_ocl_ntfs = array(
                                'id' => $fs_ocl_id[$key],
                                'info' => array(
                                            'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                            'description'               => $description[$key],
                                            'value'                     => $company_cy[$key],
                                            'company_end_prev_ye_value' => $company_ly[$key],
                                            'group_end_this_ye_value'   => $group_cy[$key],
                                            'group_end_prev_ye_value'   => $group_ly[$key],
                                            'order_by'                  => $key + 1
                                        )
                            );

            if($fs_company_info[0]['group_type'] == 1)
            {
                unset($temp_ocl_ntfs['info']['group_end_this_ye_value']);
                unset($temp_ocl_ntfs['info']['group_end_prev_ye_value']);
            }

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_ocl_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_ocl_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($ocl_ntfs, $temp_ocl_ntfs);
        }
        
        $ocl_ntfs_data = array(
            'table_name'  => 'fs_other_current_liabilities_ntfs',
            'deleted_ids' => $ocl_deleted_ids,
            'ntfs_data'   => $ocl_ntfs
        );

        $fs_ocl_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($ocl_ntfs_data);

        // return ids
        $result_ids = array(
            'result'        => true,
            'fs_ocl_ids'    => $fs_ocl_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_related_party_transactions()
    {
        $form_data           = $this->input->post();
        $fs_company_info_id  = $form_data['fs_company_info_id'];
        $rpt_section         = $form_data['rpt_section'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // print_r($form_data);

        if($rpt_section == 1)
        {
            $fs_rpt_1_id       = $form_data['fs_rpt_1_id'];
            $rpt_1_title_item  = $form_data['rpt_1_title_item'];
            $rpt_1_description = $form_data['rpt_1_description'];
            $rpt_1_company_cy  = $form_data['rpt_1_company_cy'];
            $rpt_1_company_ly  = $form_data['rpt_1_company_ly'];
            $rpt_1_group_cy    = $form_data['rpt_1_group_cy'];
            $rpt_1_group_ly    = $form_data['rpt_1_group_ly'];

            $rpt_ntfs_1_deleted_ids = preg_split ("/\,/", $form_data['rpt_ntfs_1_deleted_ids']);

            $rpt_ntfs_1 = [];

            foreach ($rpt_1_title_item as $key => $value) 
            {
                $temp_rpt_ntfs_1 = array(
                                        'id' => $fs_rpt_1_id[$key],
                                        'info' => array(
                                                    'fs_company_info_id'        => $fs_company_info_id,
                                                    'title_item'                => $value,
                                                    'description'               => $rpt_1_description[$key],
                                                    'value'                     => $rpt_1_company_cy[$key],
                                                    'company_end_prev_ye_value' => $rpt_1_company_ly[$key],
                                                    'group_end_this_ye_value'   => $rpt_1_group_cy[$key],
                                                    'group_end_prev_ye_value'   => $rpt_1_group_ly[$key],
                                                    'order_by'                  => $key + 1
                                                )
                                    );

                if($fs_company_info[0]['group_type'] == 1)
                {
                    unset($temp_rpt_ntfs_1['info']['group_end_this_ye_value']);
                    unset($temp_rpt_ntfs_1['info']['group_end_prev_ye_value']);
                }

                if($fs_company_info[0]['first_set'] == '1')
                {
                    unset($temp_rpt_ntfs_1['info']['group_end_prev_ye_value']);
                    unset($temp_rpt_ntfs_1['info']['company_end_prev_ye_value']);
                }

                array_push($rpt_ntfs_1, $temp_rpt_ntfs_1);
            }

            $rpt_ntfs_1_data = array(
                'table_name'  => 'fs_related_party_transactions_ntfs_1',
                'deleted_ids' => $rpt_ntfs_1_deleted_ids,
                'ntfs_data'   => $rpt_ntfs_1
            );

            $fs_rpt_ntfs_1_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($rpt_ntfs_1_data);

            echo json_encode(array('result' => true, 'fs_rpt_ntfs_1_ids' => $fs_rpt_ntfs_1_ids));
        }
        else if($rpt_section == 2)
        {
            $fs_rpt_info_id        = array_values($form_data['fs_rpt_info_id']);
            $fs_rpt_info_on_off    = array_values($form_data['fs_rpt_info_on_off']);
            $fs_list_rpt_content_id = array_values($form_data['fs_list_rpt_content_id']);
            $fs_rpt_info_content   = array_values($form_data['fs_rpt_info_content']);

            /* ---------- for checkbox ---------- */
            $return_fs_rpt_info_ids = [];

            foreach ($fs_rpt_info_on_off as $key => $value) 
            {
                if($value == 1)
                {
                    if(!empty($fs_rpt_info_id[$key])) //    update data
                    {
                        $result = $this->fs_notes_model->update_tbl_data('fs_related_party_transactions_info', 
                                    array(
                                        array(
                                            'id' => $fs_rpt_info_id[$key],
                                            'info' => array(
                                                        'content' => $fs_rpt_info_content[$key],
                                                        'is_checked' => 1
                                                    )
                                        )
                                    )
                                );

                        array_push($return_fs_rpt_info_ids, $fs_rpt_info_id[$key]);
                    }
                    else // create new
                    {
                        $fs_list_related_party_transactions_content_id = $fs_list_rpt_content_id[$key];

                        $result = $this->db->insert('fs_related_party_transactions_info', 
                                        array(
                                            'fs_company_info_id'               => $fs_company_info_id,
                                            'fs_list_related_party_transactions_content_id' => $fs_list_related_party_transactions_content_id[$key],
                                            'content'                          => $fs_rpt_info_content[$key],
                                            'is_checked'                       => 1
                                        )
                                    );
                        array_push($return_fs_rpt_info_ids, $this->db->insert_id());
                    }
                }
                else    // if checkbox is off, change is_checked status to 0
                {
                    if(!empty($fs_rpt_info_id[$key]))
                    {
                        // set not checked
                        $result = $this->fs_notes_model->update_tbl_data('fs_related_party_transactions_info', 
                                    array(
                                        array(
                                            'id' => $fs_rpt_info_id[$key],
                                            'info' => array(
                                                        'is_checked' => 0, 
                                                        'content'    => $fs_rpt_info_content[$key]
                                                    )
                                        )
                                    )
                                );
                    }
                    array_push($return_fs_rpt_info_ids, $fs_rpt_info_id[$key]);
                }
            }
            /* ---------- END OF for checkbox ---------- */

            echo json_encode(array('result' => true, 'return_fs_rpt_info_ids' => $return_fs_rpt_info_ids));
        }
    }

    public function save_ntfs_fs_commitment_p2() 
    {
        $form_data = $this->input->post();

        // print_r($form_data);

        $fs_company_info_id = $form_data['fs_company_info_id'];

        /* --------- for table 1 --------- */
        $c2_t1_id             = $form_data['c2_t1_id'];
        $c2_t1_description    = $form_data['c2_t1_description'];
        $c2_t1_group_ty_val   = $form_data['c2_t1_group_ty_val'];
        $c2_t1_group_ly_val   = $form_data['c2_t1_group_ly_val'];
        $c2_t1_company_ty_val = $form_data['c2_t1_company_ty_val'];
        $c2_t1_company_ty_val = $form_data['c2_t1_company_ly_val'];

        /* for textbox part */
        $c2_info = array(
                    'deleted_ids' => [],
                    'table_name'  => 'fs_commitment_2_ntfs_info',
                    'ntfs_data'   => array(
                                        array(
                                            'id'   => $form_data['c2_info_id'],
                                            'info' => array(
                                                        'fs_company_info_id' => $fs_company_info_id,
                                                        'content'            => $form_data['c2_info_content']
                                                    )
                                        )
                                    )
                    
                );

        $return_c2_info_id = $this->fs_notes_model->insert_update_tbl_data($c2_info);
        /* END OF for textbox part */

        $c2_t1_data = array(
                            'id' => $c2_t1_id,
                            'info' => array(
                                        'fs_company_info_id'        => $fs_company_info_id,
                                        'description'               => $c2_t1_description,
                                        'value'                     => $c2_t1_group_ty_val,
                                        'company_end_prev_ye_value' => $c2_t1_group_ly_val,
                                        'group_end_this_ye_value'   => $c2_t1_company_ty_val,
                                        'group_end_prev_ye_value'   => $c2_t1_company_ty_val
                                    )
                        );

        if(!empty($c2_t1_id))
        {
            $result = $this->fs_notes_model->update_tbl_data('fs_commitment_2_ntfs_1', array($c2_t1_data));

            $fs_c2_t1_id = $c2_t1_data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_commitment_2_ntfs_1', $c2_t1_data['info']);
            $fs_c2_t1_id = $this->db->insert_id();
        }
        /* --------- END OF for table 1 --------- */

        /* --------- for table 2 --------- */
        $c2_t2_id             = $form_data['c2_t2_id'];
        $c2_t2_is_checked     = $form_data['c2_t2_is_checked'];
        $c2_t2_list_id        = $form_data['c2_t2_list_id'];
        $c2_t2_group_ty_val   = $form_data['c2_t2_group_ty_val'];
        $c2_t2_group_ly_val   = $form_data['c2_t2_group_ly_val'];
        $c2_t2_company_ty_val = $form_data['c2_t2_company_ty_val'];
        $c2_t2_company_ly_val = $form_data['c2_t2_company_ly_val'];

        $c2_t2_rows = array();

        foreach ($c2_t2_id as $key => $value) 
        {
            array_push($c2_t2_rows, 
                        array(
                            'id' => $c2_t2_id[$key],
                            'info' => array(
                                        'fs_company_info_id'             => $fs_company_info_id,
                                        'is_checked'                     => $c2_t2_is_checked[$key],
                                        'fs_list_commitment_2_ntfs_2_id' => $c2_t2_list_id[$key],
                                        'value'                          => $c2_t2_group_ty_val[$key],
                                        'company_end_prev_ye_value'      => $c2_t2_group_ly_val[$key],
                                        'group_end_this_ye_value'        => $c2_t2_company_ty_val[$key],
                                        'group_end_prev_ye_value'        => $c2_t2_company_ly_val[$key]
                                    )
                        ));
        }

        $c2_t2_ntfs_data = array(
            'table_name'  => 'fs_commitment_2_ntfs_2',
            'deleted_ids' => [],
            'ntfs_data'   => $c2_t2_rows
        );

        $fs_c2_t2_ids = $this->fs_notes_model->insert_update_tbl_data($c2_t2_ntfs_data);
        /* --------- END OF for table 2 --------- */

        echo json_encode(
            array(
                'result'       => true,
                'fs_c2_t1_id'  => $fs_c2_t1_id,
                'fs_c2_t2_ids' => $fs_c2_t2_ids,
                'fs_c2_info_id' => $return_c2_info_id
            )
        );
    }

    public function save_ntfs_fs_commitment_p3()
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];

        $final_report_type  = $this->fs_model->get_final_document_type($fs_company_info_id);

        if($final_report_type == 1)
        {
            /* --------- for table 1 --------- */
            $c3_t1_id             = $form_data['c3_t1_id'];
            $c3_t1_description    = $form_data['c3_t1_description'];
            $c3_t1_group_ty_val   = $form_data['c3_t1_group_ty_val'];
            $c3_t1_group_ly_val   = $form_data['c3_t1_group_ly_val'];
            $c3_t1_company_ty_val = $form_data['c3_t1_company_ty_val'];
            $c3_t1_company_ly_val = $form_data['c3_t1_company_ly_val'];

            $c3_t1_data = array(
                                'id' => $c3_t1_id,
                                'info' => array(
                                            'fs_company_info_id'        => $fs_company_info_id,
                                            'description'               => $c3_t1_description,
                                            'value'                     => $c3_t1_group_ty_val,
                                            'company_end_prev_ye_value' => $c3_t1_group_ly_val,
                                            'group_end_this_ye_value'   => $c3_t1_company_ty_val,
                                            'group_end_prev_ye_value'   => $c3_t1_company_ly_val
                                        )
                            );

            if(!empty($c3_t1_id))
            {
                $result = $this->fs_notes_model->update_tbl_data('fs_commitment_3_ntfs_1', array($c3_t1_data));

                $fs_c3_t1_id = $c3_t1_data['id'];
            }
            else
            {
                $result = $this->db->insert('fs_commitment_3_ntfs_1', $c3_t1_data['info']);
                $fs_c3_t1_id = $this->db->insert_id();
            }
            /* --------- END OF for table 1 --------- */
        }

        /* for textbox part */
        $c3_info = array(
                    'deleted_ids' => [],
                    'table_name'  => 'fs_commitment_3_ntfs_info',
                    'ntfs_data'   => array(
                                        array(
                                            'id'   => $form_data['c3_info_id'],
                                            'info' => array(
                                                        'fs_company_info_id' => $fs_company_info_id,
                                                        'content'            => $form_data['c3_info_content']
                                                    )
                                        )
                                    )
                    
                );

        $return_c3_info_id = $this->fs_notes_model->insert_update_tbl_data($c3_info);
        /* END OF for textbox part */

        $c3_t2_id             = $form_data['c3_t2_id'];
        $c3_t2_is_checked     = $form_data['c3_t2_is_checked'];
        $c3_t2_list_id        = $form_data['c3_t2_list_id'];
        $c3_t2_group_ty_val   = $form_data['c3_t2_group_ty_val'];
        $c3_t2_group_ly_val   = $form_data['c3_t2_group_ly_val'];
        $c3_t2_company_ty_val = $form_data['c3_t2_company_ty_val'];
        $c3_t2_company_ly_val = $form_data['c3_t2_company_ly_val'];

        $c3_t2_rows = array();

        foreach ($c3_t2_id as $key => $value) 
        {
            array_push($c3_t2_rows, 
                        array(
                            'id' => $c3_t2_id[$key],
                            'info' => array(
                                        'fs_company_info_id'             => $fs_company_info_id,
                                        'is_checked'                     => $c3_t2_is_checked[$key],
                                        'fs_list_commitment_2_ntfs_2_id' => $c3_t2_list_id[$key],
                                        'value'                          => $c3_t2_company_ty_val[$key],
                                        'company_end_prev_ye_value'      => $c3_t2_company_ly_val[$key],
                                        'group_end_this_ye_value'        => $c3_t2_group_ty_val[$key],
                                        'group_end_prev_ye_value'        => $c3_t2_group_ly_val[$key]
                                    )
                        ));
        }

        $c3_t2_ntfs_data = array(
            'table_name'  => 'fs_commitment_3_ntfs_2',
            'deleted_ids' => [],
            'ntfs_data'   => $c3_t2_rows
        );

        $fs_c3_t2_ids = $this->fs_notes_model->insert_update_tbl_data($c3_t2_ntfs_data);

        echo json_encode(
            array(
                'result'            => true,
                'final_report_type' => $final_report_type,
                'fs_c3_t1_id'       => $fs_c3_t1_id,
                'fs_c3_t2_ids'      => $fs_c3_t2_ids,
                'fs_c3_info_id'     => $return_c3_info_id
            )
        );
    }

    public function save_ntfs_financial_risk_management_s2()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_frm_s2_group_ids = [];
        $fs_frm_s2_company_ids = [];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        if($fs_company_info[0]['group_type'] != '1')
        {
            /* for group table */
            $frm_s2_group_id            = $form_data['frm_s2_group_id'];
            $frm_s2_group_prior_current = $form_data['frm_s2_group_prior_current'];
            $frm_s2_group_is_title      = $form_data['frm_s2_group_is_title'];
            $frm_s2_group_section       = $form_data['frm_s2_group_section'];
            $frm_s2_group_description   = $form_data['frm_s2_group_description'];
            
            $frm_s2_group_value_1 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_group_value_1']);
            $frm_s2_group_value_2 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_group_value_2']); 
            $frm_s2_group_value_3 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_group_value_3']); 
            $frm_s2_group_value_4 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_group_value_4']); 

            $frm_s2_group_deleted_row_ids = preg_split ("/\,/", $form_data['frm_s2_group_deleted_row_ids']);

            $frm_s2_group_ntfs = [];

            foreach ($frm_s2_group_id as $g_key => $g_value)  
            { 
                if($frm_s2_group_is_title[$g_key] == 'true' || $frm_s2_group_is_title[$g_key] == '1')
                {
                    $frm_s2_group_is_title[$g_key] = 1;
                }
                else
                {
                    $frm_s2_group_is_title[$g_key] = 0;
                }

                array_push($frm_s2_group_ntfs, 
                    array(
                        'id' => $frm_s2_group_id[$g_key],
                        'info' => array(
                                    'fs_company_info_id'  => $fs_company_info_id,
                                    'prior_current'       => $frm_s2_group_prior_current[$g_key],
                                    'is_title'            => $frm_s2_group_is_title[$g_key],
                                    'section'             => $frm_s2_group_section[$g_key],
                                    'description'         => $frm_s2_group_description[$g_key],
                                    'within_12_months'    => $frm_s2_group_value_1[$g_key],
                                    'within_2_to_5_years' => $frm_s2_group_value_2[$g_key],
                                    'more_than_5_years'   => $frm_s2_group_value_3[$g_key],
                                    'total'               => $frm_s2_group_value_4[$g_key],
                                    'order_by'            => $g_key + 1
                                )
                    )
                );
            }

            $frm_s2_group_ntfs_data = array(
                'table_name'  => 'fs_financial_risk_management_ntfs_s2_group',
                'deleted_ids' => $frm_s2_group_deleted_row_ids,
                'ntfs_data'   => $frm_s2_group_ntfs 
            );

            $fs_frm_s2_group_ids = $this->fs_notes_model->insert_update_tbl_data($frm_s2_group_ntfs_data);
            /* END OF for group table */
        }

        /* for company table */
        $frm_s2_company_id            = $form_data['frm_s2_company_id'];
        $frm_s2_company_prior_current = $form_data['frm_s2_company_prior_current'];
        $frm_s2_company_is_title      = $form_data['frm_s2_company_is_title'];
        $frm_s2_company_section       = $form_data['frm_s2_company_section'];
        $frm_s2_company_description   = $form_data['frm_s2_company_description'];
        
        $frm_s2_company_value_1 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_company_value_1']);
        $frm_s2_company_value_2 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_company_value_2']); 
        $frm_s2_company_value_3 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_company_value_3']); 
        $frm_s2_company_value_4 = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s2_company_value_4']); 

        $frm_s2_company_deleted_row_ids = preg_split ("/\,/", $form_data['frm_s2_company_deleted_row_ids']);

        $frm_s2_company_ntfs = [];

        foreach ($frm_s2_company_id as $c_key => $c_value)  
        { 
            if($frm_s2_company_is_title[$c_key] == 'true' || $frm_s2_company_is_title[$c_key] == '1')
            {
                $frm_s2_company_is_title[$c_key] = 1;
            }
            else
            {
                $frm_s2_company_is_title[$c_key] = 0;
            }

            array_push($frm_s2_company_ntfs, 
                array(
                    'id' => $frm_s2_company_id[$c_key],
                    'info' => array(
                                'fs_company_info_id'   => $fs_company_info_id,
                                'prior_current'        => $frm_s2_company_prior_current[$c_key],
                                'is_title'             => $frm_s2_company_is_title[$c_key],
                                'section'              => $frm_s2_company_section[$c_key],
                                'description'          => $frm_s2_company_description[$c_key],
                                'less_than_a_year'     => $frm_s2_company_value_1[$c_key],
                                'between_1_to_5_years' => $frm_s2_company_value_2[$c_key],
                                'more_than_5_years'    => $frm_s2_company_value_3[$c_key],
                                'total'                => $frm_s2_company_value_4[$c_key],
                                'order_by'             => $c_key + 1
                            )
                )
            );
        }

        $frm_s2_company_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_ntfs_s2_company',
            'deleted_ids' => $frm_s2_company_deleted_row_ids,
            'ntfs_data'   => $frm_s2_company_ntfs
        );

        $fs_frm_s2_company_ids = $this->fs_notes_model->insert_update_tbl_data($frm_s2_company_ntfs_data);
        /* END OF for company table */

        // return ids
        $result_ids = array(
            'result'                => true,
            'fs_frm_s2_group_ids'   => $fs_frm_s2_group_ids,
            'fs_frm_s2_company_ids' => $fs_frm_s2_company_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_financial_risk_management_s3()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // print_r($form_data);

        /* for checkbox */
        $cbx_id           = $form_data['fs_frm_s3_info_id'];
        $cbx_is_checked   = $form_data['fs_frm_s3_info_is_checked'];
        $cbx_content      = $form_data['fs_frm_s3_info_content'];
        $cbx_is_textarea  = $form_data['fs_frm_s3_info_is_textarea'];
        $cbx_main_section = $form_data['fs_frm_s3_info_main_section'];
        $cbx_sub_section  = $form_data['fs_frm_s3_info_sub_section'];

        $frm_s3_info = [];

        foreach ($cbx_id as $key => $value) 
        {
            array_push($frm_s3_info, 
                array(
                    'id' => $cbx_id[$key],
                    'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'is_checked'         => $cbx_is_checked[$key],
                                'content'            => $cbx_content[$key],
                                'is_textarea'        => $cbx_is_textarea[$key],
                                'main_section'       => $cbx_main_section[$key],
                                'sub_section'        => $cbx_sub_section[$key],
                                'order_by'           => $key + 1
                            )
                )
            );
        }

        $frm_s3_info_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_s3_info',
            'deleted_ids' => [],
            'ntfs_data'   => $frm_s3_info
        );

        $fs_frm_s3_info_ids = $this->fs_notes_model->insert_update_tbl_data($frm_s3_info_ntfs_data);
        /* END OF for checkbox */


        /* for "floating" table */
        $id          = $form_data['frm_s3_floating_id'];
        $description = $form_data['frm_s3_floating_description'];
        $group_ty    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_floating_group_ty']);
        $group_ly    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_floating_group_ly']); 
        $company_ty  = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_floating_company_ty']); 
        $company_ly  = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_floating_company_ly']); 

        $frm_s3_floating_deleted_row_ids = preg_split ("/\,/", $form_data['frm_s3_floating_deleted_row_ids']);

        $frm_s3_ntfs_floating = [];

        foreach ($id as $key => $value) 
        {
            $temp_frm_s3_floating_ntfs = array(
                                            'id' => $id[$key],
                                            'info' => array(
                                                        'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                        'description'               => $description[$key],
                                                        'value'                     => $company_ty[$key],
                                                        'company_end_prev_ye_value' => $company_ly[$key],
                                                        'group_end_this_ye_value'   => $group_ty[$key],
                                                        'group_end_prev_ye_value'   => $group_ly[$key],
                                                        'order_by'                  => $key + 1
                                                    )
                                        );

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_frm_s3_floating_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_frm_s3_floating_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($frm_s3_ntfs_floating, $temp_frm_s3_floating_ntfs);
        }

        $frm_s3_floating_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_ntfs_s3_floating',
            'deleted_ids' => $frm_s3_floating_deleted_row_ids,
            'ntfs_data'   => $frm_s3_ntfs_floating
        );

        $fs_frm_s3_floating_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($frm_s3_floating_ntfs_data);
        /* END OF for "floating" table */

        /* ------------ for "fixed" table ------------ */
        $id          = $form_data['frm_s3_fixed_id'];
        $description = $form_data['frm_s3_fixed_description'];
        $group_ty    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_fixed_group_ty']);
        $group_ly    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_fixed_group_ly']); 
        $company_ty  = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_fixed_company_ty']); 
        $company_ly  = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['frm_s3_fixed_company_ly']); 

        $frm_s3_fixed_deleted_row_ids = preg_split ("/\,/", $form_data['frm_s3_fixed_deleted_row_ids']);

        $frm_s3_ntfs_fixed = [];

        foreach ($id as $key => $value) 
        {
            $temp_frm_s3_fixed_ntfs = array(
                                            'id' => $id[$key],
                                            'info' => array(
                                                        'fs_company_info_id'        => $form_data['fs_company_info_id'],
                                                        'description'               => $description[$key],
                                                        'value'                     => $company_ty[$key],
                                                        'company_end_prev_ye_value' => $company_ly[$key],
                                                        'group_end_this_ye_value'   => $group_ty[$key],
                                                        'group_end_prev_ye_value'   => $group_ly[$key],
                                                        'order_by'                  => $key + 1
                                                    )
                                        );

            if($fs_company_info[0]['first_set'] == '1')
            {
                unset($temp_frm_s3_fixed_ntfs['info']['company_end_prev_ye_value']);
                unset($temp_frm_s3_fixed_ntfs['info']['group_end_prev_ye_value']);
            }

            array_push($frm_s3_ntfs_fixed, $temp_frm_s3_fixed_ntfs);
        }

        $frm_s3_fixed_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_ntfs_s3_fixed',
            'deleted_ids' => $frm_s3_fixed_deleted_row_ids,
            'ntfs_data'   => $frm_s3_ntfs_fixed
        );

        $fs_frm_s3_fixed_ids = $this->fs_notes_model->save_dynamic_row_ntfs_table($frm_s3_fixed_ntfs_data);
        /* ------------ END OF for "fixed" table ------------ */

        // return ids
        $result_ids = array(
            'result'                 => true,
            'fs_frm_s3_info_ids'     => $fs_frm_s3_info_ids,
            'fs_frm_s3_floating_ids' => $fs_frm_s3_floating_ids,
            'fs_frm_s3_fixed_ids'    => $fs_frm_s3_fixed_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_financial_risk_management_s4()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // print_r($form_data);

        /* -------- Table 1 -------- */
        // for Header table
        $frm_s4_t1_header_id          = $form_data['frm_s4_t1_header_id'];
        $frm_s4_t1_header_currency_id = $form_data['frm_s4_t1_header_currency_id'];
        $frm_s4_t1_header_row         = $form_data['frm_s4_t1_header'];

        $header_data         = [];
        $header_row          = [];
        $header_currency_ids = [];

        for ($x = 0; $x < count($frm_s4_t1_header_row); $x++) 
        {
            array_push($header_currency_ids, $frm_s4_t1_header_currency_id[$x]);
            array_push($header_row, $frm_s4_t1_header_row[$x]);
        }

        array_push($header_data, 
            array(
                'id' => $frm_s4_t1_header_id[0],
                'info' => array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header_row, 0)),
                            'currency_ids'       => implode(",",array_slice($header_currency_ids, 0))
                        )
            )
        );

        $frm_s4_t1_header_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_s4_t1_header',
            'deleted_ids' => [],
            'ntfs_data'   => $header_data
        );

        $frm_s4_t1_header_id = $this->fs_notes_model->insert_update_tbl_data($frm_s4_t1_header_ntfs_data);

        // for Row table (current year)
        $frm_s4_t1_current_id             = array_values($form_data['frm_s4_t1_current_id']);
        $frm_s4_t1_current_prior_current  = array_values($form_data['frm_s4_t1_current_prior_current']);
        $frm_s4_t1_current_is_fixed       = array_values($form_data['frm_s4_t1_current_is_fixed']);
        $frm_s4_t1_current_is_checked     = array_values($form_data['frm_s4_t1_current_is_checked']);
        $frm_s4_t1_current_description    = array_values($form_data['frm_s4_t1_current_description']);
        $frm_s4_t1_current_row_item       = array_values($form_data['frm_s4_t1_current_row_item']);

        $temp_prior_current = '';
        $frm_s4_t1_data = [];

        $frm_s4_t1_count = 0;

        $frm_s4_t1_deleted_ids = preg_split ("/\,/", $form_data['frm_s4_t1_deleted_ids']);

        foreach ($frm_s4_t1_current_id as $key => $value) 
        {
            $frm_s4_t1_count++;

            // array push item
            array_push($frm_s4_t1_data, 
                array(
                    'id' => $frm_s4_t1_current_id[$key],
                    'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'prior_current'      => $frm_s4_t1_current_prior_current[$key],
                                'is_fixed'           => $frm_s4_t1_current_is_fixed[$key],
                                'is_checked'         => $frm_s4_t1_current_is_checked[$key],
                                'description'        => $frm_s4_t1_current_description[$key],
                                'row_item'           => implode(",",array_slice($frm_s4_t1_current_row_item[$key], 0)),
                                'order_by'           => $frm_s4_t1_count
                            )
                )
            );
        }

        // for Row table (prior year)
        $frm_s4_t1_prior_id             = array_values($form_data['frm_s4_t1_prior_id']);
        $frm_s4_t1_prior_prior_current  = array_values($form_data['frm_s4_t1_prior_prior_current']);
        $frm_s4_t1_prior_is_fixed       = array_values($form_data['frm_s4_t1_prior_is_fixed']);
        $frm_s4_t1_prior_is_checked     = array_values($form_data['frm_s4_t1_prior_is_checked']);
        $frm_s4_t1_prior_description    = array_values($form_data['frm_s4_t1_prior_description']);
        $frm_s4_t1_prior_row_item       = array_values($form_data['frm_s4_t1_prior_row_item']);

        foreach ($frm_s4_t1_prior_id as $key => $value) 
        {
            $frm_s4_t1_count++;

            // array push item
            array_push($frm_s4_t1_data, 
                array(
                    'id' => $frm_s4_t1_prior_id[$key],
                    'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'prior_current'      => $frm_s4_t1_prior_prior_current[$key],
                                'is_fixed'           => $frm_s4_t1_prior_is_fixed[$key],
                                'is_checked'         => $frm_s4_t1_prior_is_checked[$key],
                                'description'        => $frm_s4_t1_prior_description[$key],
                                'row_item'           => implode(",",array_slice($frm_s4_t1_prior_row_item[$key], 0)),
                                'order_by'           => $frm_s4_t1_count
                            )
                )
            );
        }
        
        $frm_s4_t1_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_s4_t1',
            'deleted_ids' => $frm_s4_t1_deleted_ids,
            'ntfs_data'   => $frm_s4_t1_data
        );

        $frm_s4_t1_ids = $this->fs_notes_model->insert_update_tbl_data($frm_s4_t1_ntfs_data);
        /* --------- END OF for table 1 --------- */

        /* -------- Table 2 -------- */
        // for Header table
        $frm_s4_t2_header_id          = $form_data['frm_s4_t2_header_id'];
        $frm_s4_t2_header_currency_id = $form_data['frm_s4_t2_header_currency_id'];
        $frm_s4_t2_header_row         = $form_data['frm_s4_t2_header'];

        $header_data         = [];
        $header_row          = [];
        $header_currency_ids = [];

        for ($x = 0; $x < count($frm_s4_t2_header_row); $x++) 
        {
            array_push($header_currency_ids, $frm_s4_t2_header_currency_id[$x]);
            array_push($header_row, $frm_s4_t2_header_row[$x]);
        }

        array_push($header_data, 
            array(
                'id' => $frm_s4_t2_header_id[0],
                'info' => array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'header_titles'      => implode(",",array_slice($header_row, 0)),
                            'currency_ids'       => implode(",",array_slice($header_currency_ids, 0))
                        )
            )
        );

        $frm_s4_t2_header_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_s4_t2_header',
            'deleted_ids' => [],
            'ntfs_data'   => $header_data
        );

        $frm_s4_t2_header_id = $this->fs_notes_model->insert_update_tbl_data($frm_s4_t2_header_ntfs_data);

        // for Row table (current year)
        $frm_s4_t2_current_id             = array_values($form_data['frm_s4_t2_current_id']);
        $frm_s4_t2_current_prior_current  = array_values($form_data['frm_s4_t2_current_prior_current']);
        $frm_s4_t2_current_is_fixed       = array_values($form_data['frm_s4_t2_current_is_fixed']);
        $frm_s4_t2_current_is_checked     = array_values($form_data['frm_s4_t2_current_is_checked']);
        $frm_s4_t2_current_description    = array_values($form_data['frm_s4_t2_current_description']);
        $frm_s4_t2_current_row_item       = array_values($form_data['frm_s4_t2_current_row_item']);

        $temp_prior_current = '';
        $frm_s4_t2_data = [];

        $frm_s4_t2_count = 0;

        $frm_s4_t2_deleted_ids = preg_split ("/\,/", $form_data['frm_s4_t2_deleted_ids']);

        // print_r($frm_s4_t2_deleted_ids);

        foreach ($frm_s4_t2_current_id as $key => $value) 
        {
            $frm_s4_t2_count++;

            // array push item
            array_push($frm_s4_t2_data, 
                array(
                    'id' => $frm_s4_t2_current_id[$key],
                    'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'prior_current'      => $frm_s4_t2_current_prior_current[$key],
                                'is_fixed'           => $frm_s4_t2_current_is_fixed[$key],
                                'is_checked'         => $frm_s4_t2_current_is_checked[$key],
                                'description'        => $frm_s4_t2_current_description[$key],
                                'row_item'           => implode(",",array_slice($frm_s4_t2_current_row_item[$key], 0)),
                                'order_by'           => $frm_s4_t2_count
                            )
                )
            );
        }

        // for Row table (prior year)
        $frm_s4_t2_prior_id             = array_values($form_data['frm_s4_t2_prior_id']);
        $frm_s4_t2_prior_prior_current  = array_values($form_data['frm_s4_t2_prior_prior_current']);
        $frm_s4_t2_prior_is_fixed       = array_values($form_data['frm_s4_t2_prior_is_fixed']);
        $frm_s4_t2_prior_is_checked     = array_values($form_data['frm_s4_t2_prior_is_checked']);
        $frm_s4_t2_prior_description    = array_values($form_data['frm_s4_t2_prior_description']);
        $frm_s4_t2_prior_row_item       = array_values($form_data['frm_s4_t2_prior_row_item']);

        foreach ($frm_s4_t2_prior_id as $key => $value) 
        {
            $frm_s4_t2_count++;

            // array push item
            array_push($frm_s4_t2_data, 
                array(
                    'id' => $frm_s4_t2_prior_id[$key],
                    'info' => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'prior_current'      => $frm_s4_t2_prior_prior_current[$key],
                                'is_fixed'           => $frm_s4_t2_prior_is_fixed[$key],
                                'is_checked'         => $frm_s4_t2_prior_is_checked[$key],
                                'description'        => $frm_s4_t2_prior_description[$key],
                                'row_item'           => implode(",",array_slice($frm_s4_t2_prior_row_item[$key], 0)),
                                'order_by'           => $frm_s4_t2_count
                            )
                )
            );
        }
        
        $frm_s4_t2_ntfs_data = array(
            'table_name'  => 'fs_financial_risk_management_s4_t2',
            'deleted_ids' => $frm_s4_t2_deleted_ids,
            'ntfs_data'   => $frm_s4_t2_data
        );

        $frm_s4_t2_ids = $this->fs_notes_model->insert_update_tbl_data($frm_s4_t2_ntfs_data);
        /* --------- END OF for table 2 --------- */

        echo json_encode(
            array(
                'result'              => true,
                'frm_s4_t1_header_id' => $frm_s4_t1_header_id,
                'frm_s4_t1_ids'       => $frm_s4_t1_ids,
                'frm_s4_t2_header_id' => $frm_s4_t2_header_id,
                'frm_s4_t2_ids'       => $frm_s4_t2_ids
            )
        );
    }

    public function save_ntfs_fair_value_of_assets()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $fs_fva_id      = $form_data['fva_id'];
        $description    = $form_data['fva_description'];
        $group_company  = $form_data['fva_group_company'];
        $part           = $form_data['fva_part'];
        $is_title       = $form_data['fva_is_title'];
        $fva_value_1    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['fva_value_1']);
        $fva_value_2    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['fva_value_2']); 
        $fva_value_3    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['fva_value_3']); 
        $fva_value_4    = array_map(function($v){ return (is_null($v) || empty($v)) ? 0 : $v; }, $form_data['fva_value_4']); 

        $fva_ntfs = [];

        foreach ($fs_fva_id as $key => $value) 
        {
            $temp_fva_ntfs = array(
                                'id' => $fs_fva_id[$key],
                                'info' => array(
                                            'fs_company_info_id' => $form_data['fs_company_info_id'],
                                            'group_company'      => $group_company[$key],
                                            'part'               => $part[$key],
                                            'is_title'           => $is_title[$key],
                                            'description'        => $description[$key],
                                            'value_1'            => $fva_value_1[$key],
                                            'value_2'            => $fva_value_2[$key],
                                            'value_3'            => $fva_value_3[$key],
                                            'value_4'            => $fva_value_4[$key],
                                            'order_by'           => $key + 1
                                        )
                            );

            array_push($fva_ntfs, $temp_fva_ntfs);
        }

        $fva_ntfs_data = array(
            'table_name'  => 'fs_fair_value_of_assets_ntfs',
            'deleted_ids' => [],
            'ntfs_data'   => $fva_ntfs
        );

        $fs_fva_ids = $this->fs_notes_model->insert_update_tbl_data($fva_ntfs_data);

        // return ids
        $result_ids = array(
            'result'        => true,
            'fs_fva_ids'    => $fs_fva_ids
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_fs_contingencies()
    {
        $form_data = $this->input->post();

        // print_r($form_data);

        $fs_c_info_id        = array_values($form_data['fs_c_info_id']);
        $fs_c_info_on_off    = array_values($form_data['fs_c_info_on_off']);
        $fs_list_c_content_id = array_values($form_data['fs_list_c_content_id']);
        $fs_c_info_content   = array_values($form_data['fs_c_info_content']);

        $fs_company_info_id  = $form_data['fs_company_info_id'];

        $return_fs_c_info_ids = [];

        foreach ($fs_c_info_on_off as $key => $value) 
        {
            if($value == 1)
            {
                if(!empty($fs_c_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_contingencies_info', 
                                array(
                                    array(
                                        'id' => $fs_c_info_id[$key],
                                        'info' => array(
                                                    'content' => $fs_c_info_content[$key],
                                                    'order_by' => $key + 1,
                                                    'is_checked' => 1
                                                )
                                    )
                                )
                            );

                    array_push($return_fs_c_info_ids, $fs_c_info_id[$key]);
                }
                else // create new
                {
                    $fs_list_contingencies_content_id = $fs_list_c_content_id[$key];

                    $result = $this->db->insert('fs_contingencies_info', 
                                    array(
                                        'fs_company_info_id'               => $fs_company_info_id,
                                        'fs_list_contingencies_content_id' => $fs_list_c_content_id[$key],
                                        'content'                          => $fs_c_info_content[$key],
                                        'order_by'                         => $key + 1,
                                        'is_checked'                       => 1
                                    )
                                );

                    $return_id = $this->db->insert_id();
                    array_push($return_fs_c_info_ids, $return_id);
                }
            }
            else    // if checkbox is off, change is_checked status to 0
            {
                if(!empty($fs_c_info_id[$key]))
                {
                    // set not checked
                    $result = $this->fs_notes_model->update_tbl_data('fs_contingencies_info', 
                                array(
                                    array(
                                        'id' => $fs_c_info_id[$key],
                                        'info' => array(
                                                    'order_by'   => $key,
                                                    'is_checked' => 0, 
                                                    'content'    => $fs_c_info_content[$key]
                                                )
                                    )
                                )
                            );
                }

                array_push($return_fs_c_info_ids, $fs_c_info_id[$key]);
            }
        }

        echo json_encode(array('result' => true, 'return_fs_c_info_ids' => $return_fs_c_info_ids));
    }

    public function save_event_occur_after_rp_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $eo_info_id        = $form_data['eo_info_id'];
        $eo_info_content   = $form_data['eo_info_content'];

        $return_eo_info_id = '';

        if(!(empty($eo_info_content) && empty($eo_info_id)))
        {
            $fs_eo_info = array(
                'id'   => $eo_info_id,
                'info' => array(
                    'fs_company_info_id' => $fs_company_info_id,
                    'content'            => $eo_info_content
                )
            );

            $return_eo_info_id = $this->fs_notes_model->save_fs_event_occur_after_rp_info($fs_eo_info);
        }

        // return ids
        $result_ids = array(
            'result'        => true,
            'eo_info_id'    => $return_eo_info_id
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_prior_years_adjustment()
    {
        $form_data = $this->input->post();

        $fs_pya_info_id        = array_values($form_data['fs_pya_info_id']);
        $fs_pya_info_on_off    = array_values($form_data['fs_pya_info_on_off']);
        $fs_pya_info_content   = array_values($form_data['fs_pya_info_content']);

        $fs_company_info_id  = $form_data['fs_company_info_id'];

        $return_fs_pya_info_ids = [];

        foreach ($fs_pya_info_on_off as $key => $value) 
        {
            if($value == 1)
            {
                if(!empty($fs_pya_info_id[$key])) //    update data
                {
                    $result = $this->fs_notes_model->update_tbl_data('fs_prior_years_adjustment_info', 
                                array(
                                    array(
                                        'id' => $fs_pya_info_id[$key],
                                        'info' => array(
                                                    'content' => $fs_pya_info_content[$key],
                                                    'is_checked' => 1
                                                )
                                    )
                                )
                            );
                    array_push($return_fs_pya_info_ids, $fs_pya_info_id[$key]);
                }
                else // create new
                {
                    $result = $this->db->insert('fs_prior_years_adjustment_info', 
                                    array(
                                        'fs_company_info_id'               => $fs_company_info_id,
                                        'content'                          => $fs_pya_info_content[$key],
                                        'is_checked'                       => 1
                                    )
                                );
                    array_push($return_fs_pya_info_ids, $this->db->insert_id());
                }
            }
            else    // if checkbox is off, change is_checked status to 0
            {
                if(!empty($fs_pya_info_id[$key]))
                {
                    // set not checked
                    $result = $this->fs_notes_model->update_tbl_data('fs_prior_years_adjustment_info', 
                                array(
                                    array(
                                        'id' => $fs_pya_info_id[$key],
                                        'info' => array(
                                                    'is_checked' => 0, 
                                                    'content'    => $fs_pya_info_content[$key]
                                                )
                                    )
                                )
                            );
                }
                array_push($return_fs_pya_info_ids, $fs_pya_info_id[$key]);
            }
        }
        echo json_encode(array('result' => true, 'return_fs_pya_info_ids' => $return_fs_pya_info_ids));
    }

    public function save_going_concern_ntfs()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];
        $gc_info_id        = $form_data['gc_info_id'];
        $gc_info_content   = $form_data['gc_info_content'];

        $return_gc_info_id = '';

        if(!(empty($gc_info_content) && empty($gc_info_id)))
        {
            $fs_gc_info = array(
                'id'   => $gc_info_id,
                'info' => array(
                    'fs_company_info_id' => $fs_company_info_id,
                    'content'            => $gc_info_content
                )
            );

            $return_gc_info_id = $this->fs_notes_model->save_fs_going_concern_info($fs_gc_info);
        }

        // return ids
        $result_ids = array(
            'result'        => true,
            'gc_info_id'    => $return_gc_info_id
        );

        echo json_encode($result_ids);
    }

    public function save_ntfs_comparative_figures()
    {
        $form_data = $this->input->post();
        $fs_company_info_id         = $form_data['fs_company_info_id'];
        $fs_comparative_figures_id  = $form_data['fs_comparative_figures_id'];
        $content                    = $form_data['fs_cf_content'];
        $fs_default_template_comparative_figures_id = $form_data['fs_comp_fig_type'];

        print_r($form_data);

        // $data = array(
        //             'id' => $fs_comparative_figures_id,
        //             'info' => array(
        //                         'fs_company_info_id' => $fs_company_info_id,
        //                         'content' => $content,
        //                         'fs_default_template_comparative_figures_id' => $fs_default_template_comparative_figures_id
        //                     )
        //         );

        // // echo json_encode($data);

        // if(empty($fs_comparative_figures_id))
        // {
        //     $result = $this->fs_notes_model->insert_tbl_data('fs_comparative_figures', array($data));
        // } 
        // else
        // {
        //     $result = $this->fs_notes_model->update_tbl_data('fs_comparative_figures', array($data));
        // }

        // $fs_comparative_figures = $this->fs_notes_model->get_fs_comparative_figures($fs_company_info_id);

        // echo json_encode(array('result' => $result, 'data' => $fs_comparative_figures));
    }

    public function get_starting_note_no()  // get add note starting number
    {
        $form_data = $this->input->post();

        $fs_company_info_id         = $form_data['fs_company_info_id'];
        $fs_statement_doc_type_id   = $form_data['fs_statement_doc_type_id'];

        $starting_no = '';

        if($fs_statement_doc_type_id == 2)
        {
            $starting_no = $this->fs_notes_model->get_starting_note_no($fs_company_info_id, 1);
        }

        echo json_encode($starting_no);
    }

    public function get_input_note_num()  // get add note starting number
    {
        $form_data = $this->input->post();

        $fs_company_info_id = $form_data['fs_company_info_id'];
        $fs_note_details_id = $form_data['fs_note_details_id'];

        $input_note_num = '';

    
        $input_note_num = $this->fs_notes_model->get_input_note_num($fs_company_info_id, $fs_note_details_id);
        

        echo json_encode($input_note_num);
    }

    public function build_investment_in_subsidiaries_p2_t1($fs_company_info, $titles)
    {
        // set default value
        $temp_empty_row_item = [];

        if(count($titles) > 0)
        {
            foreach ($titles as $key => $value) 
            {
                array_push($temp_empty_row_item, '');
            }
        }
        else
        {
            $temp_empty_row_item = ['',''];
        }

        $row_temp = array(
                        'id'                 => '',
                        'fs_company_info_id' => $fs_company_info[0]['id'],
                        'is_title'           => 0,
                        'section'            => 'current',
                        'description'        => '',
                        'row_item'           => $temp_empty_row_item,
                        'order_by'           => 1
                    );

        /* ----- current year ----- */
        $row_data_current = [];

        // row 1
        $row_temp['is_title']    = 1;
        $row_temp['description'] = $fs_company_info[0]['current_fye_end'];
        array_push($row_data_current, $row_temp);

        // row 2
        $row_temp['is_title']    = 0;
        $row_temp['description'] = 'Non-current assets';
        array_push($row_data_current, $row_temp);

        // row 3
        $row_temp['description'] = 'Current assets';
        array_push($row_data_current, $row_temp);

        // row 4
        $row_temp['description'] = 'Non-current liabilities';
        array_push($row_data_current, $row_temp);

        // row 5
        $row_temp['description'] = 'Current liabilities';
        array_push($row_data_current, $row_temp);

        // row 6
        $row_temp['description'] = 'Revenue';
        array_push($row_data_current, $row_temp);

        // row 7
        $row_temp['description'] = 'Profit before tax';
        array_push($row_data_current, $row_temp);

        // row 8
        $row_temp['description'] = 'Tax expense';
        array_push($row_data_current, $row_temp);

        // row 9
        $row_temp['description'] = 'Other comprehensive income';
        array_push($row_data_current, $row_temp);   
        /* ----- END OF current year ----- */

        if(!$fs_company_info[0]['first_set'])
        {
            /* ----- prior year ----- */
            $row_data_prior = $row_data_current;
            $row_data_prior[0]['description'] = $fs_company_info[0]['last_fye_end'];
            /* ----- END OF prior year ----- */

            foreach ($row_data_prior as $key => $value) 
            {
                $row_data_prior[$key]['section'] = 'prior';
            }
        }

        return array('current_data' => $row_data_current, 'prior_data' => $row_data_prior);
    }

    public function build_intangible_assets_t1($fs_company_info, $section, $ia_titles)
    {
        /* ------------- general use ------------- */
        $addition_desc_temp            = 'Additions';
        $disposal_desc_temp            = 'Disposal';
        $charge_for_the_year_desc_temp = 'Charge for the year';

        // for intangible assets Table 3
        $net_fair_value_gain_desc_temp = 'Net fair value gain recognized in profit or loss';
        $net_fair_value_loss_desc_temp = 'Net fair value loss recognized in profit or loss';
        $transfer_to_completed_properties_desc_temp = 'Transfers to completed properties';

        // if report is first set eg. "At 1.1.2017"
        if($fs_company_info[0]['first_set'])
        {
            $tye_beg_temp = 'At ' . date("d.m.Y", strtotime($fs_company_info[0]['current_fye_begin']));
            $tye_end_temp = 'At ' . date("d.m.Y", strtotime($fs_company_info[0]['current_fye_end']));
        }
        else
        {
            $lye_beg_temp = 'At ' . date("d.m.Y", strtotime($fs_company_info[0]['last_fye_begin']));
            $lye_end_temp = 'At ' . date("d.m.Y", strtotime($fs_company_info[0]['last_fye_end']));
            $tye_end_temp = 'At ' . date("d.m.Y", strtotime($fs_company_info[0]['current_fye_end']));
        }

        // set default value
        $temp_empty_row_item = [];

        if(count($ia_titles) > 0)
        {
            foreach ($ia_titles as $key => $value) 
            {
                array_push($temp_empty_row_item, '');
            }
        }
        else
        {
            $temp_empty_row_item = ['',''];
        }

        $row_temp = array(
                        'id'                 => '',
                        'fs_company_info_id' => $fs_company_info[0]['id'],
                        'section'            => '',
                        'is_checked'         => 0,
                        'description'        => '',
                        'row_item'           => $temp_empty_row_item,
                        'order_by'           => 1
                    );
        /* ------------- END OF general use ------------- */

        if($fs_company_info[0]['first_set'])
        {
            $row_data = [];
            $last_row = [];

            if($section == 'cost' || $section == 'accumulated' || $section == 'normal') // 'cost' & 'accumulated' for PPE / Intangible assets | 'normal' for intangible assets table 3
            {
                // row 1
                $row_1 = $row_temp;

                $row_1['section']     = $section;
                $row_1['description'] = $tye_beg_temp;

                // row 2
                $row_2 = $row_temp;

                $row_2['section']     = $section;
                $row_2['description'] = $addition_desc_temp;

                array_push($row_data, $row_1, $row_2);

                if($section == 'normal')
                {
                    // row for Net fair value gain recognized in profit or loss
                    $row_net_fair_value_gain = $row_temp;

                    $row_net_fair_value_gain['section']     = $section;
                    $row_net_fair_value_gain['description'] = $net_fair_value_gain_desc_temp;

                    array_push($row_data, $row_net_fair_value_gain);
                }

                // row 3
                $row_3 = $row_temp;

                $row_3['section']     = $section;
                $row_3['description'] = $disposal_desc_temp;

                // last row
                $row_last = $row_temp;

                $row_last['section']     = $section;
                $row_last['description'] = $tye_end_temp;

                array_push($row_data, $row_3);
                array_push($last_row, $row_last);
            }
            elseif($section == 'carrying') // for PPE / Intangible assets
            {
                $row_last = $row_temp;

                $row_last['section']     = $section;
                $row_last['description'] = $tye_end_temp;

                array_push($last_row, $row_last);
            }
        }
        else
        {
            $row_data = [];
            $last_row = [];

            if($section == 'cost' || $section == 'accumulated' || $section == 'normal') // 'cost' & 'accumulated' for PPE / Intangible assets | 'normal' for intangible assets table 3
            {
                // row 1
                $row_1 = $row_temp;

                $row_1['section']     = $section;
                $row_1['description'] = $lye_beg_temp;

                // row 2
                $row_2 = $row_temp;

                $row_2['section']     = $section;
                if($section == 'cost' || $section == 'normal')
                {
                    $row_2['description'] = $addition_desc_temp;
                }
                elseif($section == "accumulated")
                {
                    $row_2['description'] = $charge_for_the_year_desc_temp;
                }

                array_push($row_data, $row_1, $row_2);

                // row for Net fair value gain recognized in profit or loss
                if($section == 'normal')
                {
                    $row_net_fair_value_gain = $row_temp;

                    $row_net_fair_value_gain['section']     = $section;
                    $row_net_fair_value_gain['description'] = $net_fair_value_gain_desc_temp;

                    array_push($row_data, $row_net_fair_value_gain);
                }

                // row 3
                $row_3 = $row_temp;

                $row_3['section']     = $section;
                $row_3['description'] = $disposal_desc_temp;

                // row 4
                $row_4 = $row_temp;

                $row_4['section']     = $section;
                $row_4['is_checked']  = 1;
                $row_4['description'] = $lye_end_temp;

                // row 5
                $row_5 = $row_temp;

                $row_5['section']     = $section;

                if($section == 'cost' || $section == 'normal')
                {
                    $row_5['description'] = $addition_desc_temp;
                }
                elseif($section == "accumulated")
                {
                    $row_5['description'] = $charge_for_the_year_desc_temp;
                }

                array_push($row_data, $row_3, $row_4, $row_5);

                // row for 'xfer to completed properties' & 'Net fair value loss recognized in profit or loss'
                if($section == 'normal')
                {
                    $row_xfer_completed_properties = $row_temp;

                    $row_xfer_completed_properties['section']     = $section;
                    $row_xfer_completed_properties['description'] = $transfer_to_completed_properties_desc_temp;

                    array_push($row_data, $row_xfer_completed_properties);

                    $row_net_fair_value_loss = $row_temp;

                    $row_net_fair_value_loss['section']     = $section;
                    $row_net_fair_value_loss['description'] = $net_fair_value_loss_desc_temp;

                    array_push($row_data, $row_net_fair_value_loss);
                }

                // row 6
                $row_6 = $row_temp;

                $row_6['section']     = $section;
                $row_6['description'] = $disposal_desc_temp;

                // last row
                $row_last = $row_temp;

                $row_last['section']     = $section;
                $row_last['description'] = $tye_end_temp;

                array_push($row_data, $row_6);
                array_push($last_row, $row_last);
            }
            elseif($section == 'carrying')  // for PPE / Intangible assets
            {
                // row 1
                $last_row_1 = $row_temp;

                $last_row_1['section']     = $section;
                $last_row_1['description'] = $tye_end_temp;

                // last row
                $row_last = $row_temp;

                $row_last['section']     = $section;
                $row_last['description'] = $lye_end_temp;

                array_push($row_data, $last_row_1);
                array_push($last_row, $row_last);
            }

            foreach ($row_data as $key => $value) 
            {
                $row_data[$key]['order_by'] = $key + 1;
            }
        }

        return array('row_data' => $row_data, 'last_row' => $last_row);
    }

    public function build_financial_risk_mgmt_s4($table_num, $fs_company_info_id)
    {
        /* ---------- 29.4 part - currency name ---------- */ 
        $master_currency_item = $this->db->query("SELECT mc.*, c.currency, c.name, c.id AS `currency_id`
                                                        FROM fs_ntfs_master_currency mc
                                                        LEFT JOIN currency c ON c.id = mc.currency_id
                                                        WHERE mc.fs_company_info_id = " . $fs_company_info_id);
        $master_currency_item = $master_currency_item->result_array();

        // for header
        $frm_s4_titles_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t" . $table_num . "_header WHERE fs_company_info_id=" . $fs_company_info_id);
        $frm_s4_titles_data = $frm_s4_titles_data->result_array();

        $frm_s4_titles = []; 

        if(count($frm_s4_titles_data[0]['header_titles']) > 0)
        {
            $temp_frm_s4_titles       = explode(',', $frm_s4_titles_data[0]['header_titles']);
            $temp_frm_s4_currency_ids = explode(',', $frm_s4_titles_data[0]['currency_ids']);

            foreach ($temp_frm_s4_titles as $key => $value) 
            {
                array_push($frm_s4_titles, 
                    array(
                        'currency_id' => $temp_frm_s4_currency_ids[$key],
                        'currency'    => $value
                    )
                );
            }
        }
        else
        {
            foreach ($master_currency_item as $key => $value) 
            {
                array_push($frm_s4_titles, 
                    array(
                        'currency_id' => $value['currency_id'],
                        'currency'    => $value['currency']
                    )
                );
            }

            array_push($frm_s4_titles, 
                array(
                    'id'       => '',
                    'currency' => 'Total'
                )
            );
        }

        // for returning data later
        $frm_s4_title_id    = $frm_s4_titles_data[0]['id'];
        $frm_s4_titles      = $frm_s4_titles;
        $frm_s4_titles_data = $frm_s4_titles_data;

        // for data row
        $frm_s4_row_data = $this->db->query("SELECT * FROM fs_financial_risk_management_s4_t" . $table_num . " WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $frm_s4_row_data = $frm_s4_row_data->result_array();

        $frm_s4 = [];
        $frm_s4_fp = [];
        $frm_s4_fc = [];

        // set default values

        if(count($frm_s4_row_data) == 0) // prior year (fixed)
        {
            if(!$fs_company[0]['first_set'])
            {
                array_push($frm_s4_fp, 
                    array(
                        'id'                 => '',
                        'fs_company_info_id' => $fs_company_info_id,
                        'prior_current'      => 'prior',
                        'is_fixed'           => 1,
                        'is_checked'         => 0,
                        'description'        => 'Less: Financial assets denominated in functional currency',
                        'row_item'           => []
                    )
                );

                foreach ($frm_s4_titles as $key => $value) 
                {
                    array_push($frm_s4_fp[0]['row_item'], '');
                }
            }

            // current year (fixed)
            array_push($frm_s4_fc, 
                array(
                    'id'                 => '',
                    'fs_company_info_id' => $fs_company_info_id,
                    'prior_current'      => 'current',
                    'is_fixed'           => 1,
                    'is_checked'         => 0,
                    'description'        => 'Less: Financial assets denominated in functional currency',
                    'row_item'           => []
                )
            );

            foreach ($frm_s4_titles as $key => $value) 
            {
                array_push($frm_s4_fc[0]['row_item'], '');
            }
        }
        else // retrieve data
        {
            foreach ($frm_s4_row_data as $key => $value) 
            {
                $value['row_item'] = explode(",", $value['row_item']);

                if(!$value['is_fixed'])
                {
                    array_push($frm_s4, $value);
                }
                else
                {
                    if($value['prior_current'] == 'prior')
                    {
                        array_push($frm_s4_fp, $value);
                    }
                    elseif($value['prior_current'] == 'current')
                    {
                        array_push($frm_s4_fc, $value);
                    }
                }
            }
        }

        // return data
        return 
            array(
                'frm_s4_title_id'       => $frm_s4_title_id,
                'frm_s4_titles'         => $frm_s4_titles,
                'frm_s4_titles_data'    => $frm_s4_titles_data,
                'frm_s4'                => $frm_s4,
                'frm_s4_fp'             => $frm_s4_fp,
                'frm_s4_fc'             => $frm_s4_fc
            );
    }
    // public function negative_bracket($number)
    // {
    //     if($number == 0)
    //     {
    //         return "-";
    //     }
    //     elseif($number < 0)
    //     {
    //         return "(" . number_format(abs($number), 2) . ")";
    //     }
    //     else
    //     {
    //         return number_format($number, 2);
    //     }
    // }
}