<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_notes_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));

        $this->load->model('fs_model');
        $this->load->model('fs_account_category_model');
    }

    public function get_fs_ntfs_json()
    {
        $url         = 'assets/json/fs_ntfs.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return json_decode(json_encode($data_decode[0]), true);
    }

    public function get_ntfs_layout_template_parents($fs_company_info_id)
    {
        // $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lytd.default_checked, lyt.order_by, fntd.fs_ntfs_layout_template_default_id, fntm.id AS `fs_note_templates_master_id`
        //                         FROM fs_ntfs_layout_template lyt
        //                         LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
        //                         LEFT JOIN fs_note_template_default fntd ON fntd.fs_ntfs_layout_template_default_id = lytd.id
        //                         LEFT JOIN fs_note_templates_master fntm ON fntm.fs_note_templates_default_id = fntd.id AND fntm.fs_company_info_id=" . $fs_company_info_id . "
        //                         WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " AND lyt.set_parent = 0 ORDER BY lyt.order_by ASC");

        $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lytd.default_checked, lyt.order_by, fntd.fs_ntfs_layout_template_default_id, fntm.id AS `fs_note_templates_master_id`
                                FROM fs_ntfs_layout_template lyt
                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                LEFT JOIN fs_note_template_default fntd ON fntd.fs_ntfs_layout_template_default_id = lytd.id
                                LEFT JOIN fs_note_templates_master fntm ON fntm.fs_company_info_id=" . $fs_company_info_id . "
                                WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " AND lyt.set_parent = 0 AND fntm.fs_note_templates_default_id = fntd.id ORDER BY lyt.order_by ASC");

        return $q->result_array();
    }

    public function get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id)
    {
        $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lytd.default_checked, lyt.order_by, fntd.fs_ntfs_layout_template_default_id, fntm.id AS `fs_note_templates_master_id`
                                FROM fs_ntfs_layout_template lyt
                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                LEFT JOIN fs_note_template_default fntd ON fntd.fs_ntfs_layout_template_default_id = lytd.id
                                LEFT JOIN fs_note_templates_master fntm ON fntm.fs_note_templates_default_id = fntd.id AND fntm.fs_company_info_id=" . $fs_company_info_id . "
                                WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " AND lyt.set_parent = 0 ORDER BY lyt.order_by ASC");
        $q = $q->result_array();

        $note_no = 1;

        foreach ($q as $key => $value) 
        {
            if($value['is_checked'])
            {   
                if(!(empty($value['fs_note_templates_master_id']) && $key > 1))
                {
                    $q[$key]['note_no'] = $note_no;

                    $note_no++;
                }
            }
            else
            {
                $q[$key]['note_no'] = ''; 
            }

            // remove row to prevent duplicate data especially tax expense.
            if($key > 1)
            {
                if(empty($value['fs_note_templates_master_id']))
                {
                    unset($q[$key]);
                }
            }
        }

        return array_values($q);
    }

    public function get_update_note_num_displayed($q, $fs_company_info_id)
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        foreach ($q as $key => $value) // update note number displayed (follow the arranged note)
        {
            if(in_array($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id')))
            {
                $anl_key = array_search($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id'));

                $q[$key]['note_num_displayed'] = $arranged_note_list[$anl_key]['note_no'];
            }
        }

        return $q;
    }

    public function get_fca_id($fs_company_info_id, $account_code)   // account code as known as reference id
    {
        $fca_ids = [];

        foreach ($account_code as $ac_key => $ac_value) 
        {
            $q = $this->db->query("SELECT * FROM audit_categorized_account WHERE account_code = '" . $ac_value . "' AND fs_company_info_id=" . $fs_company_info_id);
            $q = $q->result_array();

            foreach ($q as $key => $value) 
            {
                array_push($fca_ids, $value['id']);
            }
        }
        
        return $fca_ids;
    }

    public function get_insert_update_ntfs_layout_template($fs_company_info_id, $final_document_status)
    {
        $fs_default_acc_category_fs_ntfs_layout_template = $this->db->query("SELECT * FROM fs_default_acc_category_fs_ntfs_layout_template");   // get linked note with categorized account
        $fs_default_acc_category_fs_ntfs_layout_template = $fs_default_acc_category_fs_ntfs_layout_template->result_array();

        $fs_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id =" . $fs_company_info_id);
        $fs_categorized_account = $fs_categorized_account->result_array();

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        if($final_document_status['changed_final_document_type'])    // changed accounting_standard, 
        {
            // tempo hold previously saved fs_ntfs_layout_template list for later use.
            $previous_fs_ntfs_lyt = $this->db->query("SELECT * FROM fs_ntfs_layout_template WHERE fs_company_info_id=" . $fs_company_info_id);
            $previous_fs_ntfs_lyt = $previous_fs_ntfs_lyt->result_array();

            $previous_fs_ntfs_lytd_ids = array_column($previous_fs_ntfs_lyt, 'fs_ntfs_layout_template_default_id');

            // delete previous fs_ntfs_layout_template
            // $this->db->query("DELETE FROM fs_ntfs_layout_template WHERE fs_company_info_id=" . $fs_company_info_id);

            if(count($previous_fs_ntfs_lyt) > 0)
            {
                /* ----------------- delete previous fs_ntfs_layout_template ----------------- */
                $delete_ntfs_layout_ids = [];

                foreach ($previous_fs_ntfs_lyt as $p_key => $p_value) 
                {
                    if(!in_array($p_value['fs_ntfs_layout_template_default_id'], array_column($this->get_ntfs_layout_default($fs_company_info_id), 'id')))
                    {
                        array_push($delete_ntfs_layout_ids, $p_value['id']);
                    }
                }

                if(count($delete_ntfs_layout_ids) > 0)
                {
                    $this->db->where_in('id', $delete_ntfs_layout_ids);
                    $this->db->delete('fs_ntfs_layout_template');
                }
                /* ----------------- END OF delete previous fs_ntfs_layout_template ----------------- */

                $result = true;

                $update_data = [];
                $insert_data = [];

                $index = 1;

                /* create and save the list */ 
                foreach($this->get_ntfs_layout_default($fs_company_info_id) as $key => $value) 
                {   
                    $temp_data = [];

                    $temp_data = array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'fs_default_acc_category_id' => 0,
                                    'fs_ntfs_layout_template_default_id' => $value['id'],
                                    'set_parent'     => $value['parent'],
                                    'set_section_no' => $value['section_no'],
                                    'is_shown'       => 1,
                                    'is_checked'     => $value['default_checked'],
                                    'order_by'       => $index
                                );

                    if(array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))) // check if need to depend on checked account
                    {
                        $dac_ntfslt_key = array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"));
                        $linked_dac_ids = json_decode($fs_default_acc_category_fs_ntfs_layout_template[$dac_ntfslt_key]['fs_default_acc_category_ids']);    // get fs_default_acc_category_ids

                        foreach ($linked_dac_ids as $linked_dac_ids_key => $linked_dac_ids_value) 
                        {
                            if(array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id')))
                            {
                                $matched_key = array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                                $temp_data['is_checked'] = true;
                                $temp_data['fs_default_acc_category_id'] = json_decode($fs_categorized_account[$matched_key]['fs_default_acc_category_id']);
                            }
                        }
                    }

                    if(in_array($value['id'], $previous_fs_ntfs_lytd_ids))
                    {
                        $selected_key = array_search($value['id'], $previous_fs_ntfs_lytd_ids);

                        array_push($update_data, 
                            array(
                                'id' => $previous_fs_ntfs_lyt[$selected_key]['id'],
                                'info' => $temp_data
                            )
                        );
                    }
                    else
                    {
                        array_push($insert_data, $temp_data);
                    }
                    

                    $index++;
                }

                // uodate note
                if(count($update_data) > 0)
                {
                    $result = $this->update_tbl_data('fs_ntfs_layout_template', $update_data);
                }

                // create note
                if(count($insert_data) > 0)
                {
                    $result = $this->insert_fs_ntfs_layout_template($insert_data); // add in new note if newly add in
                }
               
                $this->rearrange_ntfs_template_layout_section_no($fs_company_info_id);
                $this->update_reset_fs_ntfs($fs_company_info_id);
            }
            else    // create new list
            {
                $data = [];
                $index = 1;

                /* create and save the list */ 
                foreach($this->get_ntfs_layout_default($fs_company_info_id) as $key => $value) 
                {   
                    $temp_data = [];

                    $temp_data = array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'fs_default_acc_category_id' => 0,
                                    'fs_ntfs_layout_template_default_id' => $value['id'],
                                    'set_parent'     => $value['parent'],
                                    'set_section_no' => $value['section_no'],
                                    'is_shown'       => 1,
                                    'is_checked'     => $value['default_checked'],
                                    'order_by'       => $index
                                );

                    if(array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))) // check if need to depend on checked account
                    {
                        $dac_ntfslt_key = array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"));
                        $linked_dac_ids = json_decode($fs_default_acc_category_fs_ntfs_layout_template[$dac_ntfslt_key]['fs_default_acc_category_ids']);    // get fs_default_acc_category_ids

                        foreach ($linked_dac_ids as $linked_dac_ids_key => $linked_dac_ids_value) 
                        {
                            if(array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id')))
                            {
                                $matched_key = array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                                $temp_data['is_checked'] = true;
                                $temp_data['fs_default_acc_category_id'] = json_decode($fs_categorized_account[$matched_key]['fs_default_acc_category_id']);
                            }
                        }
                    }

                    array_push($data, $temp_data);

                    $index++;
                }

                if(count($data) > 0)
                {
                    $result = $this->insert_fs_ntfs_layout_template($data);
                }

                $this->rearrange_ntfs_template_layout_section_no($fs_company_info_id);
                $this->update_reset_fs_ntfs($fs_company_info_id);
            }
            
            if($result)
            {
                $q = $this->db->query("SELECT lytd.id, lyt.fs_ntfs_layout_template_default_id, fca.description, lytd.section_name, dac.account_code, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lyt.order_by  
                                        FROM fs_ntfs_layout_template lyt
                                        LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                        LEFT JOIN fs_default_acc_category dac ON dac.id = lyt.fs_default_acc_category_id
                                        LEFT JOIN audit_categorized_account fca ON fca.fs_default_acc_category_id = dac.id AND fca.fs_company_info_id = " . $fs_company_info_id . "
                                        WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lyt.order_by ASC");
                $data = $q->result_array();

                // update Profit before tax (title depend on displayed in 'Statement of Comprehensive Income')
                foreach ($data as $key => $value) 
                {
                    if($value['fs_ntfs_layout_template_default_id'] == 56)
                    {
                        $fs_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_list_state_comp_income_section_id = 3 AND fs_company_info_id = " . $fs_company_info_id);
                        $fs_sci = $fs_sci->result_array();

                        if(count($fs_sci) > 0)
                        {
                            $data[$key]['section_name'] = $fs_sci[0]['description'];
                        }

                        break;
                    }
                }
            }
            else
            {
                $data = [];
            }

            return $data;
        }
        else
        {
            $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, 
                                    lyt.is_shown, lyt.is_checked, lyt.order_by 
                                FROM fs_ntfs_layout_template lyt
                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lyt.order_by ASC");
            $q = $q->result_array();

            if(count($q) == 0)
            {
                $data = [];
                $index = 1;

                foreach($this->get_ntfs_layout_default($fs_company_info_id) as $key => $value)
                {   
                    $temp_data = [];
                    // // hide 2.5 group accounting sections
                    // if($value['section_name'] == "Group accounting" 
                    //     || $value['section_name'] == "Business Combination" 
                    //     || $value['section_name'] == "Subsidiaries" 
                    //     || $value['section_name'] == "Acquisitions from entities under common control" 
                    //     || $value['section_name'] == "Loss of control" 
                    //     || $value['section_name'] == "Subsidiaries in the separate financial statements" 
                    //     || $value['section_name'] == "Transactions eliminated on consolidation" 
                    //     || $value['section_name'] == "Group accounting - content")
                    // {
                    //     if($fs_company_info[0]['group_type'] != '1')
                    //     {
                    //         array_push($data, array(
                    //             'fs_company_info_id' => $fs_company_info_id,
                    //             'fs_ntfs_layout_template_default_id' => $value['id'],
                    //             'set_parent' => $value['parent'],
                    //             'set_section_no' => $value['section_no'],
                    //             'is_shown'  => 1,
                    //             'is_checked' => 1,
                    //             'order_by'  => $index
                    //         ));
                    //     }
                    //     else
                    //     {
                    //         array_push($data, array(
                    //             'fs_company_info_id' => $fs_company_info_id,
                    //             'fs_ntfs_layout_template_default_id' => $value['id'],
                    //             'set_parent' => $value['parent'],
                    //             'set_section_no' => $value['section_no'],
                    //             'is_shown'  => 1,
                    //             'is_checked' => 0,
                    //             'order_by'  => $index
                    //         ));
                    //     }
                    // }
                    // else
                    // {
                        // array_push($data, array(
                        //         'fs_company_info_id' => $fs_company_info_id,
                        //         'fs_ntfs_layout_template_default_id' => $value['id'],
                        //         'set_parent' => $value['parent'],
                        //         'set_section_no' => $value['section_no'],
                        //         'is_shown'  => 1,
                        //         'is_checked' => $value['default_checked'],
                        //         'order_by'  => $index
                        //     ));
                    // }

                        // print_r($value['id']);
                    
                    // print_r(array(array_search((int)$value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))));

                    $temp_data = array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'fs_default_acc_category_id' => 0,
                                    'fs_ntfs_layout_template_default_id' => $value['id'],
                                    'set_parent'     => $value['parent'],
                                    'set_section_no' => $value['section_no'],
                                    'is_shown'       => 1,
                                    'is_checked'     => $value['default_checked'],
                                    'order_by'       => $index
                                );

                    if(array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))) // check if need to depend on checked account
                    {
                        $dac_ntfslt_key = array_search($value['id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"));
                        $linked_dac_ids = json_decode($fs_default_acc_category_fs_ntfs_layout_template[$dac_ntfslt_key]['fs_default_acc_category_ids']);    // get fs_default_acc_category_ids

                        foreach ($linked_dac_ids as $linked_dac_ids_key => $linked_dac_ids_value) 
                        {
                            if(array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id')))
                            {
                                $matched_key = array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                                $temp_data['is_checked'] = true;
                                $temp_data['fs_default_acc_category_id'] = json_decode($fs_categorized_account[$matched_key]['fs_default_acc_category_id']);
                            }
                        }
                    }

                    array_push($data, $temp_data);

                    $index++;
                }

                $result = $this->insert_fs_ntfs_layout_template($data);
                $this->rearrange_ntfs_template_layout_section_no($fs_company_info_id);
                $this->update_reset_fs_ntfs($fs_company_info_id);

                if($result)
                {
                    $q = $this->db->query("SELECT lytd.id, lyt.fs_ntfs_layout_template_default_id, fca.description, lytd.section_name, dac.account_code, lytd.parent, lytd.section_no, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lyt.order_by  
                                            FROM fs_ntfs_layout_template lyt
                                            LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                            LEFT JOIN fs_default_acc_category dac ON dac.id = lyt.fs_default_acc_category_id
                                            LEFT JOIN audit_categorized_account fca ON fca.fs_default_acc_category_id = dac.id AND fca.fs_company_info_id = " . $fs_company_info_id . "
                                            WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lyt.order_by ASC");
                    $data = $q->result_array();

                    // update Profit before tax (title depend on displayed in 'Statement of Comprehensive Income')
                    foreach ($data as $key => $value) 
                    {
                        if($value['fs_ntfs_layout_template_default_id'] == 56)
                        {
                            $fs_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_list_state_comp_income_section_id = 3 AND fs_company_info_id = " . $fs_company_info_id);
                            $fs_sci = $fs_sci->result_array();

                            if(count($fs_sci) > 0)
                            {
                                $data[$key]['section_name'] = $fs_sci[0]['description'];
                            }

                            break;
                        }
                    }
                }
                else
                {
                    $data = [];
                }

                return $data;
            }
            else  // get fs_ntfs_layout_template list only
            {
                // foreach ($q as $key => $value) 
                // {
                    // if($value['section_name'] == "Group accounting" 
                    //     || $value['section_name'] == "Business Combination" 
                    //     || $value['section_name'] == "Subsidiaries" 
                    //     || $value['section_name'] == "Acquisitions from entities under common control" 
                    //     || $value['section_name'] == "Loss of control" 
                    //     || $value['section_name'] == "Subsidiaries in the separate financial statements" 
                    //     || $value['section_name'] == "Transactions eliminated on consolidation" 
                    //     || $value['section_name'] == "Group accounting - content")
                    // {
                    //     if($fs_company_info[0]['group_type'] == '1')
                    //     {
                    //         $data = array('is_checked' => false);
                    //     }
                    //     else
                    //     {
                    //         $data = array('is_checked' => true);
                    //     }

                    //     $this->db->where('id', $value['id']);
                    //     $result = $this->db->update('fs_ntfs_layout_template', $data);
                        
                    //     // $this->db->update('fs_ntfs_layout_template', $data, array('id' => $value['id']));
                    //     // return $data;
                    // }
                // }

                // $this->update_reset_fs_ntfs($fs_company_info_id); 
                
                $data = [];

                $q = $this->db->query("SELECT lyt.id, fca.description, lytd.section_name, dac.account_code, lyt.set_parent AS `parent`, lyt.set_section_no AS `section_no`, lytd.is_roman_section, lyt.is_shown, lyt.is_checked, lyt.order_by, lytd.id AS `fs_ntfs_layout_template_default_id` 
                                        FROM fs_ntfs_layout_template lyt
                                        LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                        LEFT JOIN fs_default_acc_category dac ON dac.id = lyt.fs_default_acc_category_id
                                        LEFT JOIN audit_categorized_account fca ON fca.fs_default_acc_category_id = dac.id AND fca.fs_company_info_id = " . $fs_company_info_id . "
                                        WHERE lyt.fs_company_info_id=" . $fs_company_info_id . " ORDER BY lyt.order_by ASC");
                $data = $q->result_array();

                foreach ($data as $key => $value) 
                {
                    if($value['fs_ntfs_layout_template_default_id'] == 56)
                    {
                        $fs_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_list_state_comp_income_section_id = 3 AND fs_company_info_id = " . $fs_company_info_id);
                        $fs_sci = $fs_sci->result_array();

                        if(count($fs_sci) > 0)
                        {
                            $data[$key]['section_name'] = $fs_sci[0]['description'];
                        }

                        break;
                    }
                }

                return $data;
            }
        }
    }

    public function get_ntfs_layout_default($fs_company_info_id)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $fs_list_final_report_type_id = 0;

        if($fs_company_info[0]['accounting_standard_used'] != 4)
        {
            $fs_list_final_report_id = 1;
        }
        else
        {
            if($fs_company_info[0]['is_audited'])
            {
                $fs_list_final_report_id = 2;
            }
            else
            {
                $fs_list_final_report_id = 3;
            }
        }

        // $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lytd.default_checked 
        //                         FROM fs_ntfs_layout_template_default lytd 
        //                         WHERE in_used = 1 
        //                         ORDER BY order_by");
        // $q = $this->db->query("SELECT lyt.id, lyt.section_name, lyt.parent, lyt.section_no FROM fs_ntfs_layout_template lyt WHERE parent = 0 ORDER BY order_by");
        $q = $this->db->query("SELECT lytd.id, lytd.section_name, lytd.parent, lytd.section_no, lytd.is_roman_section, lytd.default_checked 
                                FROM fs_ntfs_layout_template_default lytd 
                                LEFT JOIN fs_ntfs_layout_template_default_fs_list_final_report lytd_fr ON lytd_fr.fs_ntfs_layout_template_default_id = lytd.id 
                                WHERE lytd.in_used = 1 AND lytd_fr.fs_list_final_report_id = " . $fs_list_final_report_id . "
                                ORDER BY lytd_fr.order_by");
        $q = $q->result_array();

        return $q;
    }

    public function get_ntfs_layout_content($id)
    {
        $q = $this->db->query("SELECT lytd.section_content 
                                FROM fs_ntfs_layout_template_default lytd 
                                WHERE lytd.id=" . $id . " ORDER BY order_by");
        $q = $q->result_array();

        return $q;
    }

    public function get_note_list_default_depend_final_report($fs_company_info_id)
    {
        $final_doc_type = $this->fs_model->get_final_document_type($fs_company_info_id);

        $fs_note_template_default = $this->db->query("SELECT ntd.id AS `fs_note_template_default_id`, ntd.default_name, ntd.fs_ntfs_layout_template_default_id, ntd.in_used, ntd_fr.order_by
                                                            FROM fs_list_note_template_default_fs_final_report ntd_fr
                                                            JOIN fs_note_template_default ntd ON ntd.id = ntd_fr.fs_note_template_default_id
                                                            WHERE ntd_fr.fs_list_final_report_type_id = " . $final_doc_type . " ORDER BY ntd_fr.order_by");
        $fs_note_template_default = $fs_note_template_default->result_array();

        return $fs_note_template_default;
    }

    public function get_add_note_list($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_note_templates_master m WHERE m.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        // create note list if if is empty.
        if(count($q) == 0)
        {
            $temp_array = [];

            // $fs_note_template_default = $this->db->query("SELECT ntd_fr.* 
            //                                                 FROM fs_list_note_template_default_fs_final_report ntd_fr
            //                                                 JOIN fs_note_template_default ntd ON ntd.id = ntd_fr.fs_note_template_default_id
            //                                                 WHERE ntd_fr.fs_list_final_report_type_id = " . $final_doc_type . " ORDER BY ntd_fr.order_by");
            // $fs_categorized_account = $fs_note_template_default->result_array();
            $fs_categorized_account = $this->get_note_list_default_depend_final_report($fs_company_info_id); 

            foreach ($fs_categorized_account as $key => $value) 
            {
                array_push($temp_array, 
                            array(
                                'fs_company_info_id'            => $fs_company_info_id,
                                'fs_note_templates_default_id'  => $value['fs_note_template_default_id'],
                                'order_by'                      => $key,
                                'link_allowed'                  => 1
                            ));
            }

            $result = $this->db->insert_batch('fs_note_templates_master', $temp_array);
        }

        // get and retrieve data from database
        // $q2 = $this->db->query("SELECT m.id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, t.layout_content, d.pdf_template, d.fs_ntfs_layout_template_default_id
        //                         FROM fs_note_templates_master m
        //                         LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id
        //                         LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
        //                         WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1");
        $fs_list_final_report_type_id = $this->fs_model->get_final_document_type($fs_company_info_id);

        $q2 = $this->db->query("SELECT m.id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, d.pdf_template, d.fs_ntfs_layout_template_default_id
                                FROM fs_note_templates_master m
                                LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id
                                LEFT JOIN fs_ntfs_layout_template_default_fs_list_final_report nltd_lfr ON nltd_lfr.fs_ntfs_layout_template_default_id = d.fs_ntfs_layout_template_default_id AND nltd_lfr.fs_list_final_report_id = " . $fs_list_final_report_type_id . "
                                LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
                                WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1 ORDER by nltd_lfr.order_by");

        return $q2->result_array();
    }

    public function get_used_add_note_list($fs_company_info_id) // get all used note list and remove duplicate note
    {
        // $q2 = $this->db->query("SELECT note.fs_note_templates_master_id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, t.layout_content, d.pdf_template, flsdt.name AS `document_name`, fcaro.description, note.id AS `fs_note_details_id`, note.note_num_displayed
        //                         FROM fs_note_details note 
        //                         LEFT JOIN fs_list_statement_doc_type flsdt ON flsdt.id = note.fs_list_statement_doc_type_id 
        //                         LEFT JOIN fs_categorized_account_round_off fcaro ON fcaro.id = note.fs_categorized_account_round_off_id 
        //                         LEFT JOIN fs_note_templates_master m ON note.fs_note_templates_master_id = m.id 
        //                         LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id 
        //                         LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
        //                         WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1 AND note.in_use = 1 GROUP BY note.fs_note_templates_master_id ORDER BY note.note_num_displayed");

        $q2 = $this->db->query("SELECT note.fs_note_templates_master_id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, flsdt.short_name AS `document_name`, fca.account_code, fcaro.description, note.id AS `fs_note_details_id`, note.note_num_displayed AS `note_no`, t.id AS `fs_ntfs_layout_template_default_id`
                                FROM fs_note_details note 
                                LEFT JOIN fs_list_statement_doc_type flsdt ON flsdt.id = note.fs_list_statement_doc_type_id 
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fcaro.id = note.fs_categorized_account_round_off_id 
                                LEFT JOIN audit_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id
                                LEFT JOIN fs_note_templates_master m ON note.fs_note_templates_master_id = m.id 
                                LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id 
                                LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
                                WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1 AND note.in_use = 1 GROUP BY note.fs_note_templates_master_id ORDER BY t.order_by");
                                // WHERE m.fs_company_info_id=" . $fs_company_info_id . " AND m.link_allowed = 1 AND note.in_use = 1 GROUP BY note.fs_note_templates_master_id ORDER BY note.fs_list_statement_doc_type_id");

        return $q2->result_array();
    }

    public function get_fs_note_details($fs_company_info_id, $fs_list_statement_doc_type_id)
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // print_r($arranged_note_list);

        $q = $this->db->query("SELECT fnd.*, fntd.fs_ntfs_layout_template_default_id
                                FROM fs_note_details fnd 
                                INNER JOIN 
                                    (SELECT fnd_1.fs_categorized_account_round_off_id, max(fnd_1.created_at) as `MaxDate` 
                                     FROM audit_categorized_account fca 
                                     JOIN fs_note_details fnd_1 
                                     WHERE fca.fs_company_info_id = " . $fs_company_info_id . "
                                     GROUP BY fnd_1.fs_categorized_account_round_off_id) fnd_max_date 
                                ON fnd.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id AND fnd.created_at = fnd_max_date.MaxDate
                                LEFT JOIN fs_note_templates_master fntm ON fnd.fs_note_templates_master_id = fntm.id
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $fs_list_statement_doc_type_id . ' AND fnd.in_use = 1 ORDER BY fnd.note_num_displayed');
        $q = $q->result_array();

        // update note number displayed (follow the arranged note)
        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_ntfs_layout_template_default_id']))
            {
                unset($q[$key]);
            }
            elseif(in_array($value['fs_ntfs_layout_template_default_id'], array_column($arranged_note_list, 'fs_ntfs_layout_template_default_id')))
            {
                $anl_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($arranged_note_list, 'fs_ntfs_layout_template_default_id'));

                $q[$key]['note_num_displayed'] = $arranged_note_list[$anl_key]['note_no'];
            }
        }
        return array_values($q);
    }

    public function get_fs_note_details_for_state_comp_income($fs_company_info_id, $fs_list_statement_doc_type_id)
    {
        // if(!empty($fs_categorized_account_id))
        // {
        //     $q = $this->db->query("SELECT fnd.*, fntm.order_by
        //                         FROM fs_note_details fnd
        //                         LEFT JOIN fs_note_templates_master fntm ON fntm.id = fnd.fs_note_templates_master_id AND fntm.link_allowed = 1
        //                         INNER JOIN (
        //                             SELECT fs_categorized_account_id, max(created_at) as `MaxDate` 
        //                             FROM fs_note_details
        //                             WHERE fs_categorized_account_id = " . $fs_categorized_account_id . " ORDER BY created_at LIMIT 1
        //                         ) fnd1 
        //                         ON fnd.fs_categorized_account_id = fnd1.fs_categorized_account_id AND fnd.created_at = fnd1.MaxDate
        //                         WHERE fnd.fs_categorized_account_id=" .  $fs_categorized_account_id . " LIMIT 1");

        //     return $q->result_array();
        // }
        // else
        // {
        //     return [];
        // }

        /* DO NOT DELETE THIS (for manual note no) */
        // $q = $this->db->query("SELECT fnd.*, sci_fnd.fs_state_comp_income_id FROM fs_note_details fnd 
        //                         LEFT JOIN fs_state_comp_income_fs_note_details sci_fnd ON sci_fnd.fs_note_details_id = fnd.id
        //                         WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $fs_list_statement_doc_type_id . ' AND fnd.in_use = 1' );
        /* END OF DO NOT DELETE THIS (for manual note no) */

        $q = $this->db->query("SELECT fnd.*, sci_fnd.fs_state_comp_income_id, fntd.fs_ntfs_layout_template_default_id
                                FROM fs_note_details fnd 
                                LEFT JOIN fs_state_comp_income_fs_note_details sci_fnd ON sci_fnd.fs_note_details_id = fnd.id
                                LEFT JOIN fs_note_templates_master fntm ON fnd.fs_note_templates_master_id = fntm.id
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $fs_list_statement_doc_type_id . ' AND fnd.in_use = 1');
        $q = $q->result_array();

        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);
        $fs_ntfs_lytd_arranged_list = array_column($arranged_note_list, 'fs_note_templates_master_id');

        foreach ($q as $key => $value) 
        {
            if(in_array($value['fs_note_templates_master_id'], $fs_ntfs_lytd_arranged_list))
            {
                $arranged_key = array_search($value['fs_note_templates_master_id'], $fs_ntfs_lytd_arranged_list);

                $q[$key]['note_no'] = $arranged_note_list[$arranged_key]['note_no'];
            }
            elseif(is_null($value['fs_ntfs_layout_template_default_id']))
            {
                unset($q[$key]);
            }
        }

        return array_values($q);
    }

    public function fs_note_templates_master($fs_note_templates_master_id)
    {
        $q = $this->db->query("SELECT *
                                FROM fs_note_templates_master
                                WHERE id=" . $fs_note_templates_master_id . " AND link_allowed = 1");

        // $q = $this->db->query("SELECT m.id AS `fs_note_templates_master_id`, m.fs_note_templates_default_id, t.section_name AS `default_name`, t.layout_content, d.pdf_template
        //                         FROM fs_note_templates_master m
        //                         LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id
        //                         LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
        //                         WHERE m.id = " . $fs_note_templates_master_id . " AND link_allowed = 1");

        return $q->result_array();
    }

    public function get_fs_note_layout($fs_company_info_id, $fs_note_templates_default_id)
    {
        $q = $this->db->query("SELECT t.layout_content AS 'layout_template'
                                FROM fs_note_templates_master m
                                LEFT JOIN fs_note_template_default d ON d.id = m.fs_note_templates_default_id
                                LEFT JOIN fs_ntfs_layout_template_default t ON d.fs_ntfs_layout_template_default_id = t.id
                                WHERE m.fs_note_templates_default_id =" . $fs_note_templates_default_id . ' AND m.fs_company_info_id=' . $fs_company_info_id);

        return $q->result_array();
    }

    public function get_fs_subsi_not_consolidated($fs_company_info_id)
    {
        $q = $this->db->query("SELECT snc.* 
                                FROM fs_subsi_not_consolidated snc
                                -- LEFT JOIN fs_investment_in_subsidiaries ins ON ins.id = snc.fs_investment_in_subsidiaries_id
                                INNER JOIN (
                                    SELECT fs_company_info_id, max(created_at) as `MaxDate` 
                                    FROM fs_subsi_not_consolidated
                                    WHERE fs_company_info_id = " . $fs_company_info_id . " ORDER BY created_at LIMIT 1
                                ) snc1 ON snc.fs_company_info_id = snc1.fs_company_info_id AND snc.created_at = snc1.MaxDate
                                WHERE snc.fs_company_info_id = " . $fs_company_info_id);

        return $q->result_array();
    }

    public function get_fs_sub_intangible_assets_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ia_info.*
                                FROM fs_sub_intangible_assets_info ia_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_sub_intangible_assets_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") ia_info1
                                ON ia_info1.fs_company_info_id = ia_info.fs_company_info_id AND ia_info1.max_date = ia_info.created_at");
        $q = $q->result_array();

        return $q;
    }

    // inner of intangible assets
    public function get_fs_sub_intangible_assets($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ia.*
                                FROM fs_sub_intangible_assets ia
                                INNER JOIN 
                                (SELECT intangible_assets_code, MAX(created_at) AS max_date
                                FROM fs_sub_intangible_assets
                                WHERE fs_company_info_id = " . $fs_company_info_id . "
                                GROUP BY intangible_assets_code) ia1
                                ON ia1.intangible_assets_code = ia.intangible_assets_code AND ia1.max_date = created_at AND is_removed = 0");
        $q = $q->result_array();

        return $q;
    }

    // inner of property, plant and equipment
    public function get_fs_sub_ppe_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT sub_ppe_info.*, dm.method_name
                                FROM fs_sub_ppe_info sub_ppe_info
                                LEFT JOIN fs_list_depreciation_method dm ON dm.id = sub_ppe_info.fs_list_depreciation_method_id
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_sub_ppe_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") sub_ppe_info1
                                ON sub_ppe_info1.fs_company_info_id = sub_ppe_info.fs_company_info_id AND sub_ppe_info1.max_date = sub_ppe_info.created_at");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_sub_ppe($fs_company_info_id)
    {
        $q = $this->db->query("SELECT sub_ppe.*
                                FROM fs_sub_ppe sub_ppe
                                INNER JOIN 
                                (SELECT sub_ppe_code, MAX(created_at) AS max_date
                                FROM fs_sub_ppe
                                WHERE fs_company_info_id = " . $fs_company_info_id . "
                                GROUP BY sub_ppe_code) sub_ppe1
                                ON sub_ppe1.sub_ppe_code = sub_ppe.sub_ppe_code AND sub_ppe1.max_date = sub_ppe.created_at AND sub_ppe.is_removed = 0");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_sub_inventories_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT i_info.*, nrv.name
                                FROM fs_sub_inventories_info i_info
                                LEFT JOIN fs_list_net_realizable_value nrv ON nrv.id = i_info.fs_list_net_realizable_value_id
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_sub_inventories_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") i_info_1
                                ON i_info_1.fs_company_info_id = i_info.fs_company_info_id AND i_info_1.max_date = i_info.created_at");
        $q = $q->result_array();

        return $q;
    }

     // inner of property, plant and equipment
    public function get_fs_employee_benefits($fs_company_info_id)
    {
        $q = $this->db->query("SELECT eb.*
                                FROM fs_employee_benefits eb
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_employee_benefits
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") eb1
                                ON eb1.fs_company_info_id = eb.fs_company_info_id AND eb1.max_date = eb.created_at");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_investment_properties($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ip.*
                                FROM fs_investment_properties ip
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_investment_properties
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") ip1
                                ON ip1.fs_company_info_id = ip.fs_company_info_id AND ip1.max_date = ip.created_at");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_sub_provision($fs_company_info_id)
    {
        $q = $this->db->query("SELECT p.*
                                FROM fs_sub_provision p
                                INNER JOIN 
                                (SELECT provision_code, MAX(created_at) AS max_date
                                FROM fs_sub_provision
                                WHERE fs_company_info_id = " . $fs_company_info_id . "
                                GROUP BY provision_code) p1
                                ON p1.provision_code = p.provision_code AND p1.max_date = p.created_at AND p.is_removed = 0 
                                GROUP BY p.provision_code
                                ORDER BY order_by 
                                ");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_provision_content_list($fs_company_info_id)
    {
        $q = $this->db->query("SELECT p.*
                                FROM fs_provision p
                                INNER JOIN 
                                (SELECT provision_code, MAX(created_at) AS max_date
                                FROM fs_provision
                                WHERE fs_company_info_id = " . $fs_company_info_id . "
                                GROUP BY provision_code) p1
                                ON p1.provision_code = p.provision_code AND p1.max_date = p.created_at AND p.is_removed = 0 AND is_shown = '1'
                                GROUP BY p.provision_code
                                ORDER BY order_by 
                                ");
        $q = $q->result_array();

        return $q;
    }

    public function get_employee_benefits_expense_ntfs($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ebe_ntfs.*
                                FROM fs_employee_benefits_expense_ntfs ebe_ntfs
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_employee_benefits_expense_ntfs
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") ebe_ntfs1
                                ON ebe_ntfs1.fs_company_info_id = ebe_ntfs.fs_company_info_id AND ebe_ntfs1.max_date = ebe_ntfs.created_at");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_profit_be4_tax($fs_company_info_id)
    {
        $q = $this->db->query("SELECT pbt.*, fca.description, fca.type, fca.value, fca.company_end_prev_ye_value, fca.company_beg_prev_ye_value,
                                fca.group_end_this_ye_value, fca.group_end_prev_ye_value, fca.group_beg_prev_ye_value, fcaro.id AS `fcaro_id`
                                FROM fs_profit_be4_tax pbt
                                LEFT JOIN audit_categorized_account fca ON fca.id = pbt.fs_categorized_account_id
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fca.id = fcaro.fs_categorized_account_id
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_profit_be4_tax
                                WHERE fs_company_info_id = " . $fs_company_info_id . " 
                                GROUP BY fs_categorized_account_id) pbt1
                                ON pbt1.fs_company_info_id = pbt.fs_company_info_id AND pbt1.max_date = pbt.created_at AND pbt.is_removed = 0 
                                GROUP BY pbt.fs_categorized_account_id ORDER BY order_by");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_tax_expense_ntfs($fs_company_info_id)
    {
        $q = $this->db->query("SELECT te.*
                                FROM fs_tax_expense_ntfs te
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_tax_expense_ntfs
                                WHERE fs_company_info_id = " . $fs_company_info_id . " 
                                GROUP BY tax_expense_code) te1
                                ON te1.fs_company_info_id = te.fs_company_info_id AND te1.max_date = te.created_at AND te.is_removed = 0
                                GROUP BY te.tax_expense_code ORDER BY order_by");

        $q = $q->result_array();

        return $q;
    }

    public function get_fs_tax_expense_ntfs_info($fs_company_info_id, $part)
    {
        $q = $this->db->query("SELECT te_info.*
                                FROM fs_tax_expense_ntfs_info te_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_tax_expense_ntfs_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . " AND part='" . $part . "') te_info1
                                ON te_info1.fs_company_info_id = te_info.fs_company_info_id AND te_info1.max_date = te_info.created_at
                                WHERE te_info.fs_company_info_id = " . $fs_company_info_id . " AND te_info.part='" . $part . "'");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_tax_expense_reconciliation($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ter.*, lter.description
                                FROM fs_tax_expense_reconciliation ter
                                LEFT JOIN fs_list_tax_expense_reconciliation lter ON lter.id = ter.fs_list_tax_expense_reconciliation_id
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_tax_expense_reconciliation
                                WHERE fs_company_info_id = " . $fs_company_info_id . " 
                                GROUP BY fs_list_tax_expense_reconciliation_id) ter1
                                ON ter1.fs_company_info_id = ter.fs_company_info_id AND ter1.max_date = ter.created_at 
                                GROUP BY ter.fs_list_tax_expense_reconciliation_id ORDER BY order_by");
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_investment_in_associates_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT iia_info.*
                                FROM fs_investment_in_associates_info iia_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_investment_in_associates_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") iia_info1
                                ON iia_info1.fs_company_info_id = iia_info.fs_company_info_id AND iia_info1.max_date = iia_info.created_at
                                WHERE iia_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_investment_in_joint_venture_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT iijv_info.*
                                FROM fs_investment_in_joint_venture_info iijv_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_investment_in_joint_venture_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") iijv_info1
                                ON iijv_info1.fs_company_info_id = iijv_info.fs_company_info_id AND iijv_info1.max_date = iijv_info.created_at
                                WHERE iijv_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_insured_benefits_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT ib_info.*
                                FROM fs_insured_benefits_info ib_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_insured_benefits_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") ib_info1
                                ON ib_info1.fs_company_info_id = ib_info.fs_company_info_id AND ib_info1.max_date = ib_info.created_at
                                WHERE ib_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_investment_properties_t2($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_investment_properties_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id);
        $q = $q->result_array();

        if(count($q) == 0)
        {
            $default_template = array(
                                'description'               => '',
                                'value'                     => '',
                                'company_end_prev_ye_value' => '',
                                'group_end_this_ye_value'   => '',
                                'group_end_prev_ye_value'   => ''
                            );

            // row 1
            $default_template['description'] = "Completed investment properties";
            array_push($q, $default_template);

            // row 2
            $default_template['description'] = "Investment property under construction";
            array_push($q, $default_template);
        }

        return $q;
    }

    public function get_fs_loans_and_borrowings_t2($fs_company_info_id)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $q = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_2 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $q = $q->result_array();

        if(count($q) == 0) // set default value
        {
            $default_template = array(
                                    'is_subtotal'               => false,
                                    'is_last_section'           => false,
                                    'is_title'                  => false,
                                    'prior_current'             => '',
                                    'description'               => '',
                                    'value'                     => '',
                                    'company_end_prev_ye_value' => '',
                                    'group_end_this_ye_value'   => '',
                                    'group_end_prev_ye_value'   => ''
                                );

            $temp_p = [];

            $default_template['description'] = "Not later than one year";
            array_push($temp_p, $default_template);

            $default_template['description'] = "Later than one year but not later than five years";
            array_push($temp_p, $default_template);

            $default_template['description'] = "Later than five years";
            array_push($temp_p, $default_template);

            $default_template['description'] = "Total minimum lease payment";
            $default_template['is_subtotal'] = true;
            array_push($temp_p, $default_template);

            $default_template['description'] = "Less: future finance charges";
            $default_template['is_subtotal'] = false; // change back to false
            array_push($temp_p, $default_template);

            $default_template['description']     = "Present value of minimum lease payments";
            $default_template['is_last_section'] = true;
            array_push($temp_p, $default_template);

        
            $default_template['is_last_section'] = false; // change back to false

            // set prior year
            if(!$fs_company_info[0]['first_set'])
            {
                // set current year
                $default_template['is_title'] = true;
                $default_template['prior_current'] = "prior";
                $default_template['description'] = $fs_company_info[0]['current_fye_end'];

                array_push($q, $default_template);

                // change prior_current in items
                foreach ($temp_p as $key => $value) 
                {
                    $temp_p[$key]['prior_current'] = "prior";
                }

                $q = array_merge($q, $temp_p);
            }

            // set current year
            $default_template['is_title'] = true;
            $default_template['prior_current'] = "current";
            $default_template['description'] = $fs_company_info[0]['last_fye_end'];

            array_push($q, $default_template);

            foreach ($temp_p as $key => $value) 
            {
                $temp_p[$key]['prior_current'] = "current";
            }

            $q = array_merge($q, $temp_p);
        }

        return $q;
    }

    public function get_fs_loans_and_borrowings_t5($fs_company_info_id)
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $q = $this->db->query("SELECT * FROM fs_loans_and_borrowings_ntfs_5 WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $q = $q->result_array();

        if(count($q) == 0) // set default value
        {
            $default_template = array(
                                    'is_main_title'             => '',
                                    'main_title'                => '',
                                    'is_subtitle'               => '',
                                    'subtitle'                  => '',
                                    'description'               => '',
                                    'value'                     => '',
                                    'company_end_prev_ye_value' => '',
                                    'group_end_this_ye_value'   => '',
                                    'group_end_prev_ye_value'   => ''
                                );

        
            // for current title and its items
            // row 1 (main title)
            $default_template['is_main_title']  = true;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = false;
            $default_template['subtitle']       = '';
            $default_template['description']    = 'Current';

            array_push($q, $default_template);

            // row 2 (subtitle)
            $default_template['is_main_title']  = false;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = true;
            $default_template['subtitle']       = 'Not later than a year';
            $default_template['description']    = 'Not later than a year';

            array_push($q, $default_template);

            // row 3 
            $default_template['is_main_title']  = false;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = false;
            $default_template['subtitle']       = 'Not later than a year';
            $default_template['description']    = 'Obligation under finance lease';

            array_push($q, $default_template);

            // row 4 
            $default_template['is_main_title']  = false;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = false;
            $default_template['subtitle']       = 'Not later than a year';
            $default_template['description']    = 'Bank loans';

            array_push($q, $default_template);

            // row 5 
            $default_template['is_main_title']  = false;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = false;
            $default_template['subtitle']       = 'Not later than a year';
            $default_template['description']    = 'Bank overdraft';

            array_push($q, $default_template);

            // row 6 
            $default_template['is_main_title']  = false;
            $default_template['main_title']     = 'Current';
            $default_template['is_subtitle']    = false;
            $default_template['subtitle']       = 'Not later than a year';
            $default_template['description']    = 'Redeemable preference shares';

            array_push($q, $default_template);

            if(!$fs_company_info[0]['first_set'])
            {
                // for current title and its items
                // row 1 (main title)
                $default_template['is_main_title']  = true;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = false;
                $default_template['subtitle']       = '';
                $default_template['description']    = 'Non-current';

                array_push($q, $default_template);

                // row 2 (subtitle)
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = true;
                $default_template['subtitle']       = 'Later than a year but less than 5 years';
                $default_template['description']    = 'Later than a year but less than 5 years';

                array_push($q, $default_template);

                // row 3 
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = false;
                $default_template['subtitle']       = 'Later than a year but less than 5 years';
                $default_template['description']    = 'Obligation under finance lease';

                array_push($q, $default_template);

                // row 4 
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = false;
                $default_template['subtitle']       = 'Later than a year but less than 5 years';
                $default_template['description']    = 'Bank loans';

                array_push($q, $default_template);


                // row 1 (subtitle)
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = true;
                $default_template['subtitle']       = 'Later than 5 years';
                $default_template['description']    = 'Later than 5 years';

                array_push($q, $default_template);

                // row 2
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = false;
                $default_template['subtitle']       = 'Later than 5 years';
                $default_template['description']    = 'Obligation under finance lease';

                array_push($q, $default_template);

                // row 3
                $default_template['is_main_title']  = false;
                $default_template['main_title']     = 'Non-current';
                $default_template['is_subtitle']    = false;
                $default_template['subtitle']       = 'Later than 5 years';
                $default_template['description']    = 'Bank loans';

                array_push($q, $default_template);
            }
        }

        return $q;
    }

    public function get_fs_share_capital_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT sc_info.*
                                FROM fs_share_capital_info sc_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_share_capital_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") sc_info1
                                ON sc_info1.fs_company_info_id = sc_info.fs_company_info_id AND sc_info1.max_date = sc_info.created_at
                                WHERE sc_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_fs_financial_risk_management_ntfs_s2($fs_company_info_id, $group_company)
    {
        $q = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s2_" . $group_company . " WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $q = $q->result_array();

        if(count($q) == 0)
        {
            if($group_company == 'group')
            {
                $arr_template = array(
                                'id'                  => '',
                                'prior_current'       => '',
                                'is_title'            => false,
                                'section'             => '',
                                'description'         => '',
                                'within_12_months'    => '',
                                'within_2_to_5_years' => '',
                                'more_than_5_years'   => '',
                                'total'               => '',
                                'description'         => '',
                            );
            }
            else
            {
                $arr_template = array(
                                'id'                  => '',
                                'prior_current'       => '',
                                'is_title'            => false,
                                'section'             => '',
                                'description'         => '',
                                'less_than_a_year'    => '',
                                'between_1_to_5_years' => '',
                                'more_than_5_years'   => '',
                                'total'               => '',
                                'description'         => '',
                            );
            }

            /* Current year */
            $arr_template['prior_current'] = 'current';

            // section "financial assets"
            // row 1
            $arr_template['is_title']    = true;
            $arr_template['section']     = 'financial assets';
            $arr_template['description'] = 'Financial assets:';

            array_push($q, $arr_template);

            // row 2
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Trade and other receivables';

            array_push($q, $arr_template);

            // row 3
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Cash and short-term deposits';

            array_push($q, $arr_template);

            // section "financial liabilities"
            // row 1
            $arr_template['is_title']    = true;
            $arr_template['section']     = 'financial liabilities';
            $arr_template['description'] = 'Financial liabilities:';

            array_push($q, $arr_template);

            // row 2
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Trade and other payables';

            array_push($q, $arr_template);

            // row 3
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Loans and borrowings';

            array_push($q, $arr_template);
            /* END OF Current year */


            /* Prior year */
            $arr_template['prior_current'] = 'prior';

            // section "financial assets"
            // row 1
            $arr_template['is_title']    = true;
            $arr_template['section']     = 'financial assets';
            $arr_template['description'] = 'Financial assets:';

            array_push($q, $arr_template);

            // row 2
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Trade and other receivables';

            array_push($q, $arr_template);

            // row 3
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Cash and short-term deposits';

            array_push($q, $arr_template);

            // section "financial liabilities"
            // row 1
            $arr_template['is_title']    = true;
            $arr_template['section']     = 'financial liabilities';
            $arr_template['description'] = 'Financial liabilities:';

            array_push($q, $arr_template);

            // row 2
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Trade and other payables';

            array_push($q, $arr_template);

            // row 3
            $arr_template['is_title']    = false;
            $arr_template['description'] = 'Loans and borrowings';

            array_push($q, $arr_template);
            /* END OF Prior year */
        }

        return $q;
    }

    public function get_fs_financial_risk_management_s3_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_financial_risk_management_s3_info WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        if(count($q) == 0)
        {
            $q = $this->db->query("SELECT * FROM fs_list_financial_risk_management_s3_info WHERE in_used=1 ORDER BY order_by");
            $q = $q->result_array();

            foreach ($q as $key => $value) 
            {
                $q[$key]['id'] = '';
                $q[$key]['is_checked'] = true;
            }
        }

        return $q;
    }

    public function get_fs_financial_risk_management_ntfs_s3($fs_company_info_id, $section)
    {
        $q = $this->db->query("SELECT * FROM fs_financial_risk_management_ntfs_s3_" . $section . " WHERE fs_company_info_id=" . $fs_company_info_id . " ORDER BY order_by");
        $q = $q->result_array();

        if(count($q) == 0)
        {
            if($section == "floating")
            {
                $template_arr = array(
                                'description'               => '',
                                'value'                     => '',
                                'company_end_prev_ye_value' => '',
                                'group_end_this_ye_value'   => '',
                                'group_end_prev_ye_value'   => ''
                            );

                // row 1
                $template_arr['description'] = 'Bank overdraft';
                array_push($q, $template_arr);

                // row 2
                $template_arr['description'] = "Bankers' acceptance";
                array_push($q, $template_arr);

                // row 3
                $template_arr['description'] = 'Term loans';
                array_push($q, $template_arr);

                // row 4
                $template_arr['description'] = 'Trust receipts';
                array_push($q, $template_arr);

                // row 5
                $template_arr['description'] = 'Revolving credits';
                array_push($q, $template_arr);
            }
            elseif($section == "fixed")
            {
                $template_arr = array(
                                'description'               => '',
                                'value'                     => '',
                                'company_end_prev_ye_value' => ''
                            );

                // row 1
                $template_arr['description'] = 'Fixed deposits with maturity less than 90 days';
                array_push($q, $template_arr);

                // row 2
                $template_arr['description'] = 'Obligation under finance lease';
                array_push($q, $template_arr);
            }
        }

        return $q;
    }

    public function get_fs_event_occur_after_rp_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT eo_info.*
                                FROM fs_event_occur_after_rp_info eo_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_event_occur_after_rp_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") eo_info1
                                ON eo_info1.fs_company_info_id = eo_info.fs_company_info_id AND eo_info1.max_date = eo_info.created_at
                                WHERE eo_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_going_concern_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT gc_info.*
                                FROM fs_going_concern_info gc_info
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM fs_going_concern_info
                                WHERE fs_company_info_id = " . $fs_company_info_id . ") gc_info1
                                ON gc_info1.fs_company_info_id = gc_info.fs_company_info_id AND gc_info1.max_date = gc_info.created_at
                                WHERE gc_info.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_fs_comparative_figures($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_comparative_figures WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_tbl_name_leftjoin_tbl_list($fs_company_info_id, $tbl_name, $leftjoin_tbl_list_name)
    {
        $q = $this->db->query('SELECT tbl.*, tbl_list.description
                                FROM ' . $tbl_name . ' tbl
                                LEFT JOIN ' . $leftjoin_tbl_list_name . ' tbl_list ON tbl_list.id = tbl.' . $leftjoin_tbl_list_name . '_id
                                INNER JOIN 
                                (SELECT fs_company_info_id, MAX(created_at) AS max_date
                                FROM ' . $tbl_name . '
                                WHERE fs_company_info_id = ' . $fs_company_info_id . ' 
                                GROUP BY ' . $leftjoin_tbl_list_name . '_id) tbl1
                                ON tbl1.fs_company_info_id = tbl.fs_company_info_id AND tbl1.max_date = tbl.created_at 
                                GROUP BY tbl.' . $leftjoin_tbl_list_name . '_id ORDER BY tbl.order_by');
        $q = $q->result_array();

        return $q;
    }

    public function get_selected_note_list($fs_company_info_id, $fs_statement_doc_type_id)
    {
        if($fs_statement_doc_type_id != 3)
        {
            $q = $this->db->query("SELECT fnd.id AS `fs_note_details_id`, fnd.fs_categorized_account_round_off_id, sci_fnd.fs_state_comp_income_id, fnd.fs_list_statement_doc_type_id, fnd.fs_note_templates_master_id, flsdt.short_name AS `document_name`, fnd.note_num_displayed, fcaro.description
                                FROM fs_note_details fnd 
                                LEFT JOIN fs_list_statement_doc_type flsdt ON flsdt.id = fnd.fs_list_statement_doc_type_id
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fcaro.id = fnd.fs_categorized_account_round_off_id
                                LEFT JOIN fs_state_comp_income_fs_note_details sci_fnd ON sci_fnd.fs_note_details_id = fnd.id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.in_use = 1");
            $q = $q->result_array();

            foreach ($q as $key => $value) 
            {
                if(!empty($value['fs_state_comp_income_id']))
                {
                    $q_1 = $this->db->query("SELECT * FROM fs_state_comp_income WHERE id = " . $value['fs_state_comp_income_id']);
                    $q_1 = $q_1->result_array();

                    if(count($q_1) > 0)
                    {
                        $q[$key]['description'] = $q_1[0]['description'];
                    }
                }
            }
        }
        else
        {
            $q = $this->db->query("SELECT fnd.id AS `fs_note_details_id`, fnd.fs_categorized_account_round_off_id, sci_fnd.fs_state_comp_income_id, fnd.fs_list_statement_doc_type_id, fnd.fs_note_templates_master_id, flsdt.short_name AS `document_name`, fnd.note_num_displayed, fcaro.description
                                FROM fs_note_details fnd 
                                LEFT JOIN fs_list_statement_doc_type flsdt ON flsdt.id = fnd.fs_list_statement_doc_type_id
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fcaro.id = fnd.fs_categorized_account_round_off_id
                                LEFT JOIN fs_state_comp_income_fs_note_details sci_fnd ON sci_fnd.fs_note_details_id = fnd.id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $fs_statement_doc_type_id . " AND fnd.in_use = 1");
            $q = $q->result_array();
        }
        

        return $q;
    }

    public function get_selected_note($fs_company_info_id, $fs_categorized_account_round_off_id)
    {
        if(!empty($fs_categorized_account_round_off_id))
        {
            $q = $this->db->query("SELECT fnd.id AS `fs_note_details_id`, fnd.fs_categorized_account_round_off_id, fnd.fs_note_templates_master_id, fnd.note_num_displayed, fntd.fs_ntfs_layout_template_default_id
                            FROM fs_note_details fnd 
                            INNER JOIN 
                                (SELECT fnd_1.fs_categorized_account_round_off_id, max(fnd_1.created_at) as `MaxDate` 
                                 FROM audit_categorized_account fca 
                                 JOIN fs_note_details fnd_1 
                                 WHERE fca.fs_company_info_id = " . $fs_company_info_id . "
                                 GROUP BY fnd_1.fs_categorized_account_round_off_id) fnd_max_date 
                            ON fnd.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id AND fnd.created_at = fnd_max_date.MaxDate
                            LEFT JOIN fs_note_templates_master fntm ON fnd.fs_note_templates_master_id = fntm.id
                            LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                            WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_categorized_account_round_off_id = " . $fs_categorized_account_round_off_id . " AND fnd.in_use = 1");
            $q = $q->result_array();

            // // update note number displayed (follow the arranged note)
            foreach ($q as $key => $value) 
            {
                if(empty($value['fs_ntfs_layout_template_default_id']))
                {
                    unset($q[$key]);
                }
            }

            $q = $this->fs_notes_model->get_update_note_num_displayed($q, $fs_company_info_id);

            return $q; 
        }
        else
        {
            return [];
        }
    }

    public function get_selected_note_for_fs_state_comp_income($fs_company_info_id, $fs_state_comp_income_id)
    {
        $q = $this->db->query("SELECT fnd.*, sci_fnd.fs_state_comp_income_id FROM fs_note_details fnd 
                                LEFT JOIN fs_state_comp_income_fs_note_details sci_fnd ON sci_fnd.fs_note_details_id = fnd.id
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND sci_fnd.fs_state_comp_income_id = " . $fs_state_comp_income_id . ' AND fnd.in_use = 1');

        return $q->result_array();
    }

    public function get_starting_note_no($fs_company_info_id, $target_fs_statement_doc_type_id)
    {
        $q = $this->db->query("SELECT MAX(fnd.note_num_displayed) AS `biggest_note_no` FROM fs_note_details fnd
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.fs_list_statement_doc_type_id = " . $target_fs_statement_doc_type_id);
        $q = $q->result_array();

        return $q;
    }

    public function get_input_note_num($fs_company_info_id, $fs_note_details_id)
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // print_r($arranged_note_list);

        $q = $this->db->query("SELECT fnd.note_num_displayed, fnd.fs_note_templates_master_id FROM fs_note_details fnd
                                WHERE fnd.fs_company_info_id = " . $fs_company_info_id . " AND fnd.id = " . $fs_note_details_id);
        $q = $q->result_array();

        // print_r($q);

        foreach ($q as $key => $value) // update note number displayed (follow the arranged note)
        {
            if(in_array($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id')))
            {
                $anl_key = array_search($value['fs_note_templates_master_id'], array_column($arranged_note_list, 'fs_note_templates_master_id'));

                $q[$key]['note_num_displayed'] = $arranged_note_list[$anl_key]['note_no'];

                // print_r($arranged_note_list[$anl_key]['note_no']);
            }
        }

        return $q;
    }

    public function get_fs_default_template_comparative_figures()
    {
        $q = $this->db->query("SELECT * FROM fs_default_template_comparative_figures");
        $q = $q->result_array();

        return $q;
    }
    // public function get_note_num_display($fs_company_info_id, $fs_categorized_account_id, $fs_list_statement_doc_type_id)
    // {
    //     // if($fs_list_statement_doc_type_id)
    //     return 1;
    // }
    /* ------------------------------------------- get and set for dropdown ------------------------------------------- */
    public function get_dp_fs_list_depreciation_method()
    {
        $fs_list_depreciation_method_list = $this->db->query("SELECT * FROM fs_list_depreciation_method");
        $fs_list_depreciation_method_list = $fs_list_depreciation_method_list->result_array();

        $dp_fs_list_depreciation_method = array();
        // $dp_fs_list_depreciation_method[''] = ' -- Select a subsidiary type -- ';

        foreach ($fs_list_depreciation_method_list as $key => $value) 
        {
            $dp_fs_list_depreciation_method[$value['id']] = $value['method_name'];
        }

        return $dp_fs_list_depreciation_method;
    }

    public function get_dp_fs_list_net_realizable_value()
    {
        $fs_list_net_realizable_value_list = $this->db->query("SELECT * FROM fs_list_net_realizable_value");
        $fs_list_net_realizable_value_list = $fs_list_net_realizable_value_list->result_array();

        $dp_fs_list_net_realizable_value = array();
        // $dp_fs_list_net_realizable_value[''] = ' -- Select a subsidiary type -- ';

        foreach ($fs_list_net_realizable_value_list as $key => $value) 
        {
            $dp_fs_list_net_realizable_value[$value['id']] = $value['name'];
        }

        return $dp_fs_list_net_realizable_value;
    }
     /* ------------------------------------------- END OF get and set for dropdown ------------------------------------------- */

    public function insert_fs_ntfs_layout_template($data) 
    {
        $result = $this->db->insert_batch('fs_ntfs_layout_template', $data); 

        return $result;
    }

    public function save_fs_note_details($data)
    {
        return $result = $this->db->insert('fs_note_details', $data);
    }

    public function insert_update_fs_note_details($fs_company_info_id)
    {
        $this->fs_notes_model->get_add_note_list($fs_company_info_id); 
        
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $fs_ntfs_with_arranged_note = $this->fs_notes_model->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // print_r($fs_ntfs_with_arranged_note);

        $q = $this->db->query(
                                "SELECT fca.fs_default_acc_category_id, fca.account_code, fcaro.id AS `fcaro_id` 
                                FROM audit_categorized_account fca 
                                LEFT JOIN fs_categorized_account_round_off fcaro ON fca.id = fcaro.fs_categorized_account_id
                                WHERE fca.fs_company_info_id =" . $fs_company_info_id
                            );
        $q = $q->result_array();

        $fs_ntfs = $this->get_fs_ntfs_json(); 

        // get connection info default between 2 tables
        $fs_default_acc_category_fs_ntfs_layout_template = $this->db->query("SELECT * FROM fs_default_acc_category_fs_ntfs_layout_template");
        $fs_default_acc_category_fs_ntfs_layout_template = $fs_default_acc_category_fs_ntfs_layout_template->result_array();

        // print_r($fs_default_acc_category_fs_ntfs_layout_template);

        $fs_note_templates_master = $this->db->query(
                                                        "SELECT fntm.id AS `fs_note_templates_master_id`, fntd.fs_ntfs_layout_template_default_id
                                                        FROM fs_note_templates_master fntm 
                                                        LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id
                                                        WHERE fntm.fs_company_info_id = " . $fs_company_info_id
                                                    );
        $fs_note_templates_master = $fs_note_templates_master->result_array();

        // print_r($fs_note_templates_master);

        // if(count($fs_note_templates_master) == 0)
        // {
        //     // create fs_note_template_master
            
        // }

        foreach (array_column($fs_ntfs['statements'], "link_note") as $key => $value)   // list of account code to link to note for statement by statement (from json file)
        {
            foreach ($value as $ac_key => $ac_value) // loop by account codes
            {
                // if(in_array($ac_value, array_column($q, 'account_code')))
                // {
                    // $q_key = array_key_exists($ac_value, array_column($q, 'account_code'));

                    foreach ($q as $q_key => $q_value) 
                    {
                        if($q_value['account_code'] == $ac_value)
                        {
                            foreach ($fs_default_acc_category_fs_ntfs_layout_template as $key_2 => $value_2) // find back the linked
                            {
                                $note_num_displayed = '';

                                if(in_array($q_value['fs_default_acc_category_id'], json_decode($value_2['fs_default_acc_category_ids'])))
                                {
                                    $fs_ntfs_layout_template_default_id = $value_2['fs_ntfs_layout_template_default_id'];

                                    $fs_note_templates_master_key = array_search($fs_ntfs_layout_template_default_id, array_column($fs_note_templates_master, 'fs_ntfs_layout_template_default_id'));

                                    // get note number displayed
                                    $fs_ntfs_with_arranged_note_key = array_search($fs_ntfs_layout_template_default_id, array_column($fs_ntfs_with_arranged_note, 'fs_ntfs_layout_template_default_id'));

                                    if($fs_ntfs_with_arranged_note_key || (string)$fs_ntfs_with_arranged_note_key == '0')
                                    {
                                        $note_num_displayed = $fs_ntfs_with_arranged_note[$fs_ntfs_with_arranged_note_key]['note_no'];
                                    }

                                    // update / save data for fs_note_details
                                    if($fs_note_templates_master_key || (string)$fs_note_templates_master_key == '0')
                                    {
                                        if( !empty($q_value['fcaro_id']) && 
                                            !empty($fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id']) && 
                                            !empty($fs_ntfs['statements'][$key]['fs_list_statement_doc_type']))
                                        {
                                            $data = array(
                                                        "info" => array(
                                                                    'fs_categorized_account_round_off_id' => $q_value['fcaro_id'],
                                                                    'fs_company_info_id'                  => $fs_company_info_id,
                                                                    'fs_note_templates_master_id'         => $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id'],
                                                                    'fs_list_statement_doc_type_id'       => $fs_ntfs['statements'][$key]['fs_list_statement_doc_type'],
                                                                    'note_num_displayed'                  => $note_num_displayed,
                                                                    'in_use'                              => 1
                                                                )
                                                    );

                                            $fs_note_details_data = $this->db->query("SELECT * 
                                                                                        FROM fs_note_details 
                                                                                        WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_note_templates_master_id=" . $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id'] . " AND fs_categorized_account_round_off_id=" . $q_value['fcaro_id']);
                                            $fs_note_details_data = $fs_note_details_data->result_array();

                                            if(count($fs_note_details_data) > 0)
                                            {   
                                                $data['id'] = $fs_note_details_data['id'];
                                                $result = $this->update_tbl_data('fs_note_details', array($data));
                                            }
                                            else
                                            {
                                                $result = $this->insert_tbl_data('fs_note_details', array($data));
                                            }
                                        }

                                        break;
                                    }
                                }
                            }
                        }
                    }

                // }
            }

            $this->update_checked_fs_ntfs_layout_template($fs_company_info_id);

            // foreach ($q as $key_1 => $value_1) // loop fs_categorized_account 
            // {
            //     if(!empty($value_1['account_code']) && in_array($value_1['account_code'], $value))
            //     {
            //         foreach ($fs_default_acc_category_fs_ntfs_layout_template as $key_2 => $value_2) 
            //         {
            //             if(in_array($value_1['fs_default_acc_category_id'], json_decode($value_2['fs_default_acc_category_ids'])))
            //             {
            //                 $fs_ntfs_layout_template_default_id = $value_2['fs_ntfs_layout_template_default_id'];

            //                 $fs_note_templates_master_key = array_search($fs_ntfs_layout_template_default_id, array_column($fs_note_templates_master, 'fs_ntfs_layout_template_default_id'));

            //                 if($fs_note_templates_master_key || (string)$fs_note_templates_master_key == '0')
            //                 {
            //                     if( !empty($value_1['fcaro_id']) && 
            //                         !empty($fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id']) && 
            //                         !empty($fs_ntfs['statements'][$key]['fs_list_statement_doc_type']))
            //                     {
            //                         $data = array(
            //                                     "info" => array(
            //                                                 'fs_categorized_account_round_off_id' => $value_1['fcaro_id'],
            //                                                 'fs_company_info_id'                  => $fs_company_info_id,
            //                                                 'fs_note_templates_master_id'         => $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id'],
            //                                                 'fs_list_statement_doc_type_id'       => $fs_ntfs['statements'][$key]['fs_list_statement_doc_type'],
            //                                                 'note_num_displayed'                  => $note_num_displayed,
            //                                                 'in_use'                              => 1
            //                                             )
            //                                 );

            //                         $fs_note_details_data = $this->db->query("SELECT * 
            //                                                                     FROM fs_note_details 
            //                                                                     WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_note_templates_master_id=" . $fs_note_templates_master[$fs_note_templates_master_key]['fs_note_templates_master_id']);
            //                         $fs_note_details_data = $fs_note_details_data->result_array();

            //                         if(count($fs_note_details_data) > 0)
            //                         {   
            //                             $data['id'] = $fs_note_details_data['id'];
            //                             $result = $this->update_tbl_data('fs_note_details', array($data));
            //                         }
            //                         else
            //                         {
            //                             $result = $this->insert_tbl_data('fs_note_details', array($data));
            //                         }
            //                     }

            //                     $note_num_displayed++;

            //                     break;
            //                 }
            //             }
            //         }
            //     }
            // }
        }

        // rearrange note number in fs_note_details

    }

    public function update_checked_fs_ntfs_layout_template($fs_company_info_id) // checked or unchecked when statement updates notes.
    {
        $arranged_note_list = $this->get_ntfs_layout_template_with_arranged_note_no($fs_company_info_id);

        // get fs_note_details by latest date
        $q = $this->db->query("SELECT fnd_main.*, fntd.fs_ntfs_layout_template_default_id
                                FROM fs_note_details fnd_main 
                                INNER JOIN 
                                    (SELECT fnd.fs_categorized_account_round_off_id, max(fnd.created_at) as `MaxDate` 
                                     FROM audit_categorized_account fca 
                                     JOIN fs_note_details fnd 
                                     WHERE fca.fs_company_info_id = " . $fs_company_info_id . "
                                     GROUP BY fnd.fs_categorized_account_round_off_id) fnd_max_date 
                                ON fnd_main.fs_categorized_account_round_off_id = fnd_max_date.fs_categorized_account_round_off_id 
                                AND fnd_main.created_at = fnd_max_date.MaxDate 
                                AND fnd_main.fs_company_info_id = " . $fs_company_info_id . "
                                LEFT JOIN fs_note_templates_master fntm ON fnd_main.fs_note_templates_master_id = fntm.id 
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id 
                                GROUP BY fnd_main.fs_categorized_account_round_off_id
                                ORDER BY fnd_main.id");
        $q = $q->result_array(); 

        // update note number displayed (follow the arranged note)
        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_ntfs_layout_template_default_id']))
            {
                unset($q[$key]);    // remove unlinked note (due to changed document type)
            }
        }

        $q = array_values($q);

        // print_r($q);

        $linked_note_fnltd_list = array_column($q, "fs_ntfs_layout_template_default_id");

        foreach ($q as $key1 => $value1) 
        {
            // print_r($value1);

            $matched_keys = array_keys($linked_note_fnltd_list, $value1['fs_ntfs_layout_template_default_id']);

            $fnlt = $this->db->query("SELECT * FROM fs_ntfs_layout_template WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_ntfs_layout_template_default_id = " . $value1['fs_ntfs_layout_template_default_id']);
            $fnlt = $fnlt->result_array();

            if(count($matched_keys) > 1)    // if note is used more than 1 time (maybe in different statement appears)
            {
                $in_use = 0;

                foreach ($matched_keys as $m_key => $m_value) 
                {
                    if($q[$m_value]['in_use'])
                    {
                        $in_use = 1;
                    }
                }

                if(count($fnlt) > 0)
                {
                    $data = array(
                                    'id'   => $fnlt[0]['id'],
                                    'info' => array('is_checked' => $in_use)
                                );

                    // if note is in use, checked the linked note in NTA list, else unchecked the linked note in NTA list
                    // if($value1['in_use'])
                    // {
                    //     $data['info']['is_checked'] = $in_use;
                    // }

                    if(!$in_use)  // remove the linked note in Statement of Cash Flow
                    {
                        $fs_state_cash_flows = $this->db->query("SELECT * FROM fs_state_cash_flows WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_note_details_id = " . $value1['id']);
                        $fs_state_cash_flows = $fs_state_cash_flows->result_array();

                        if(count($fs_state_cash_flows) > 0)
                        {
                            // empty fs_note_details_id
                            $fs_scf_data = array(
                                                'id'    => $fs_state_cash_flows[0]['id'],
                                                'info'  => array('fs_note_details_id' => '')
                                            );

                            $this->update_tbl_data('fs_state_cash_flows', array($fs_scf_data)); // update fs_state_cash_flows
                        }
                    }

                    $this->update_tbl_data('fs_ntfs_layout_template', array($data));
                }
            }
            else // for only 1 note tagged in statement
            {
                if(count($fnlt) > 0)
                {
                    $data = array(
                                    'id'   => $fnlt[0]['id'],
                                    'info' => array('is_checked' => 0)
                                );

                    // if note is in use, checked the linked note in NTA list, else unchecked the linked note in NTA list
                    if($value1['in_use'])
                    {
                        $data['info']['is_checked'] = 1;
                    }
                    else   // remove the linked note in Statement of Cash Flow
                    {
                        $fs_state_cash_flows = $this->db->query("SELECT * FROM fs_state_cash_flows WHERE fs_company_info_id = " . $fs_company_info_id . " AND fs_note_details_id = " . $value1['id']);
                        $fs_state_cash_flows = $fs_state_cash_flows->result_array();

                        if(count($fs_state_cash_flows) > 0)
                        {
                            // empty fs_note_details_id

                            $fs_scf_data = array(
                                                'id'    => $fs_state_cash_flows[0]['id'],
                                                'info'  => array('fs_note_details_id' => '')
                                            );

                            $this->update_tbl_data('fs_state_cash_flows', array($fs_scf_data)); // update fs_state_cash_flows
                        }
                    }

                    $this->update_tbl_data('fs_ntfs_layout_template', array($data));
                }
            }
        }
    }

    public function update_reset_fs_ntfs($fs_company_info_id)   // check the section if the condition is met
    {
        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        $q = $this->db->query("SELECT * FROM fs_ntfs_layout_template lyt WHERE lyt.fs_company_info_id =" . $fs_company_info_id);
        $q = $q->result_array();

        $fs_default_acc_category_fs_ntfs_layout_template = $this->db->query("SELECT * FROM fs_default_acc_category_fs_ntfs_layout_template");
        $fs_default_acc_category_fs_ntfs_layout_template = $fs_default_acc_category_fs_ntfs_layout_template->result_array();

        $fs_categorized_account = $this->db->query("SELECT * FROM audit_categorized_account WHERE fs_company_info_id =" . $fs_company_info_id);
        $fs_categorized_account = $fs_categorized_account->result_array();

        $fs_default_acc_category = $this->fs_account_category_model->get_default_account_list();
        $lyt_default_list = $this->get_ntfs_layout_default($fs_company_info_id);

        $data = $q;

        foreach ($q as $key => $value) 
        {
            // reset to default setttings
            $lyt_default_list_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($lyt_default_list, "id"));

            if($lyt_default_list_key || (string)$lyt_default_list_key == '0')
            {
                $lyt_default_list_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($lyt_default_list, "id"));
                $data[$key]['is_checked'] = $lyt_default_list[$lyt_default_list_key]['default_checked'];
                $data[$key]['fs_default_acc_category_id'] = 0;
            }

            // check from fs_default_acc_category_fs_ntfs_layout_template
            if(array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"))) // check if need to depend on checked account
            {
                $dac_ntfslt_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_default_acc_category_fs_ntfs_layout_template, "fs_ntfs_layout_template_default_id"));
                $linked_dac_ids = json_decode($fs_default_acc_category_fs_ntfs_layout_template[$dac_ntfslt_key]['fs_default_acc_category_ids']);    // get fs_default_acc_category_ids

                foreach ($linked_dac_ids as $linked_dac_ids_key => $linked_dac_ids_value) 
                {
                    $matched_key = array_search($linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                    if($matched_key || (string)$matched_key == '0')
                    {
                        $matched_key = array_search((int)$linked_dac_ids_value, array_column($fs_categorized_account, 'fs_default_acc_category_id'));

                        $data[$key]['is_checked'] = true;
                        $data[$key]['fs_default_acc_category_id'] = json_decode($fs_categorized_account[$matched_key]['fs_default_acc_category_id']);   // link with note
                    }
                }
            }
        }

        $result = $this->update_fs_ntfs_specific_require($fs_company_info_id); // specific requirement (certain note depends on condition to decide whether want to checked or not)

        // update data
        $data_to_save = [];

        foreach ($data as $key => $value) 
        {
            array_push($data_to_save, 
                array(
                    'id' => $value['id'],
                    'info' => array(
                                'fs_default_acc_category_id' => $value['fs_default_acc_category_id'],
                                'is_checked' => $value['is_checked']
                            )
                )
            );
        }

        if(count($data_to_save) > 0)
        {
            $result = $this->update_tbl_data('fs_ntfs_layout_template', $data_to_save);
        }
    }

    public function update_fs_ntfs_specific_require($fs_company_info_id)
    {   
        $fs_ntfs = $this->get_fs_ntfs_json(); 

        $data = $this->db->query("SELECT * FROM fs_ntfs_layout_template lyt WHERE lyt.fs_company_info_id =" . $fs_company_info_id);
        $data = $data->result_array();

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

        // specific requirement (certain note depends on condition to decide whether want to checked or not)
        foreach ($data as $key => $value) 
        {
            $matched_fs_ntfs_key = '';
            $matched_fs_ntfs_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_ntfs['ntfs'], 'fs_ntfs_layout_template_default_id'));

            $temp_data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'content'   => '',
                            'fs_default_template_comparative_figures_id' => 0
                        );

            if($matched_fs_ntfs_key || (string)$matched_fs_ntfs_key == '0')
            {
                if($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['name'] == "COMPARATIVE FIGURES")   // for comparative figures
                {
                    $fs_comp_fig_type = 0;

                    $fs_comparative_figures = $this->fs_notes_model->get_fs_comparative_figures($fs_company_info_id);
                    $get_fs_default_template_comparative_figures = $this->get_fs_default_template_comparative_figures();

                    if($fs_company_info[0]['first_set'] == 1)
                    {
                        $data[$key]['is_checked'] = true;
                        $data[$key]['fs_default_acc_category_id'] = $value['fs_ntfs_layout_template_default_id'];

                        $temp_data['content'] = $get_fs_default_template_comparative_figures[1]['content'];
                        $temp_data['fs_default_template_comparative_figures_id'] = 2;
                    }
                    else // check dates
                    {
                        $lye_beg = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['last_fye_begin']), "", -4);
                        $lye_end = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['last_fye_end']), "", -4);

                        $current_beg = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['current_fye_begin']), "", -4);
                        $current_end = substr_replace(preg_replace('/\s+/', '', $fs_company_info[0]['current_fye_end']), "", -4);

                        $interval_beg = $this->fs_model->compare_date_period($fs_company_info[0]['last_fye_begin'], $fs_company_info[0]['current_fye_begin'], '0 day');
                        $interval_end = $this->fs_model->compare_date_period($fs_company_info[0]['last_fye_end'], $fs_company_info[0]['current_fye_end'], '0 day');

                        if($lye_beg == $current_beg && $lye_end == $current_end && $interval_beg->y == 1 && $interval_end->y == 1)
                        {
                            $data[$key]['is_checked'] = false;
                            $data[$key]['fs_default_acc_category_id'] = 0;

                            if(isset($fs_comparative_figures[0]) && count($fs_comparative_figures[0]['fs_default_template_comparative_figures_id']) > 0)
                            {
                                $temp_data['content'] = $fs_comparative_figures[0]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 1;
                                $data[$key]['is_checked'] = true;
                            }
                            else
                            {
                                $temp_data['content'] = '';
                                $temp_data['fs_default_template_comparative_figures_id'] = 1;
                            }
                        }
                        else
                        {
                            $data[$key]['is_checked'] = true;
                            $data[$key]['fs_default_acc_category_id'] = $value['fs_ntfs_layout_template_default_id'];

                            if($lye_beg == $current_beg && $lye_end != $current_end)
                            {
                                $temp_data['content'] = $get_fs_default_template_comparative_figures[3]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 4;
                            }
                            else
                            {
                                $temp_data['content'] = $get_fs_default_template_comparative_figures[2]['content'];
                                $temp_data['fs_default_template_comparative_figures_id'] = 3;
                            }
                        }
                    }

                    if(count($fs_comparative_figures) > 0)  // save fs_comparative_figures
                    {
                        $result = $this->fs_notes_model->update_tbl_data('fs_comparative_figures', 
                                        array(
                                            array(
                                                'id'    => $fs_comparative_figures[0]['id'],
                                                'info'  => $temp_data
                                            )
                                    ));
                    }
                    else
                    {
                        $result = $this->fs_notes_model->insert_tbl_data('fs_comparative_figures', 
                                        array(
                                            array(
                                                'info' => $temp_data
                                            )
                                        ));
                    }
                }
                elseif($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['name'] == "GOING CONCERN") // for going concern
                {
                    foreach ($fs_ntfs['ntfs'][$matched_fs_ntfs_key]['compare_ref_id'] as $cri_key => $compare_ref_id) // full formula "A001 < L100"
                    {
                        $split_formula = preg_split('/\s+/', $compare_ref_id);

                        $formula_data_list = [];
                        $operator = '';

                        // insert data in array first
                        foreach ($split_formula as $key1 => $formula_data) 
                        {
                            if($formula_data == "<")
                            {
                                $operator = $formula_data;
                            }
                            else
                            {
                                // get fca data from database.
                                // get id
                                $fca_id = $this->get_fca_id($fs_company_info_id, array($formula_data));

                                // print_r($fca_id);

                                array_push($formula_data_list, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $fca_id));
                            }
                        }

                        // check condition (formula)
                        // print_r($formula_data_list[0][0]['parent_array'][0]['total_c']      < $formula_data_list[1][0]['parent_array'][0]['total_c']);
                        // print_r($formula_data_list);
                        if(!empty($formula_data_list[0]) && !empty($formula_data_list[1]))
                        {
                            if( 
                                $formula_data_list[0][0]['parent_array'][0]['total_c']      < $formula_data_list[1][0]['parent_array'][0]['total_c']     || 
                                $formula_data_list[0][0]['parent_array'][0]['total_c_lye']  < $formula_data_list[1][0]['parent_array'][0]['total_c_lye'] || 
                                $formula_data_list[0][0]['parent_array'][0]['total_g']      < $formula_data_list[1][0]['parent_array'][0]['total_g']     || 
                                $formula_data_list[0][0]['parent_array'][0]['total_g_lye']  < $formula_data_list[1][0]['parent_array'][0]['total_g_lye']
                            )
                            {
                                $data[$key]['is_checked'] = true;

                                break;
                            }
                            else
                            {
                                $data[$key]['is_checked'] = false;
                            }
                        }
                        else
                        {
                            $data[$key]['is_checked'] = false;
                        }

                        
                    }
                }
            }
        }

        // update data
        $data_to_save = [];

        foreach ($data as $key => $value) 
        {
            array_push($data_to_save, 
                array(
                    'id' => $value['id'],
                    'info' => array(
                                'fs_default_acc_category_id' => $value['fs_default_acc_category_id'],
                                'is_checked' => $value['is_checked']
                            )
                )
            );
        }

        if(count($data_to_save) > 0)
        {
            $result = $this->update_tbl_data('fs_ntfs_layout_template', $data_to_save);
        }

        return $result;
    }

    public function update_fs_ntfs_layout_template($fs_company_info_id, $data)  // modify numbering eg. 2.1, 2.2 ...
    {
        $q = $this->db->query("SELECT lyt.*, lytd.is_roman_section FROM fs_ntfs_layout_template lyt
                                LEFT JOIN fs_ntfs_layout_template_default lytd ON lytd.id = lyt.fs_ntfs_layout_template_default_id
                                WHERE fs_company_info_id=" . $fs_company_info_id); // retrieve the info
        $q = $q->result_array();

        $old_section_no = [];

        // get old section no before replace
        foreach ($data as $key => $ntfs_id_section_no) 
        {
            foreach ($q as $key => $list) 
            {
                if($ntfs_id_section_no['id'] == $list['id'])
                {
                    array_push($old_section_no, $list['set_section_no']);
                }
            }
        }

        // find back and replace with new section no. (Compare parent and section no)
        foreach ($q as $key => $value) // full list
        {
            foreach ($old_section_no as $key => $section_no)    // old section no only
            {
                $temp = [];

                if($section_no == $value['set_parent'])
                {
                    if($value['is_roman_section'] == '1')
                    {
                        $temp = array('set_parent' => $data[$key]['set_section_no']);   // if it is roman, then we update parent index
                    }
                    elseif($value['set_section_no'] == $section_no && $value['set_parent'] == $section_no)
                    {
                        $temp = array(
                            'set_parent' => $data[$key]['set_section_no'],
                            'set_section_no' => $data[$key]['set_section_no']
                        );
                    }
                    elseif($value['set_parent'] == $section_no)
                    {
                        $temp = array(
                            'set_parent' => (int)$data[$key]['set_section_no']
                        );
                    }
                }

                if(!empty($temp))
                {
                    $this->db->update('fs_ntfs_layout_template', $temp, array('id' => $value['id']));   // get id and replace with new parent section no
                }
            }
        }

        // replace section no
        foreach ($data as $key => $list_data) 
        {
            $temp_data = array('set_section_no' => $list_data['set_section_no']);

            $this->db->update('fs_ntfs_layout_template', $temp_data, array('id' => $list_data['id']));   // get id and replace with new parent section no
        }

        $this->rearrange_ntfs_template_layout($fs_company_info_id);
    }

    public function rearrange_ntfs_template_layout($fs_company_info_id) // rearrange order_by
    {
        // get data where set_parent is 0.
        $q = $this->db->query("SELECT lyt.* 
                                FROM fs_ntfs_layout_template lyt  
                                WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                " AND lyt.set_parent=0 
                                ORDER BY cast(lyt.set_section_no as unsigned)");
        $q = $q->result_array();

        $order_by = 0;
        $order_key = [];
        
        // get data where set_parent like 1, 2 , 3 ...
        foreach ($q as $key_1 => $level_1) 
        {
            $q_1 = $this->db->query("SELECT lyt.* 
                                        FROM fs_ntfs_layout_template lyt 
                                        WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                    " AND lyt.set_parent = " . $level_1['set_section_no'] . 
                                    " ORDER BY lyt.set_section_no");
            $q_1 = $q_1->result_array();

            // print_r(array((int)$level_1['set_section_no']));
            // print_r($q_1);

            // update order_by
            $order_by ++;
            $this->update_fs_ntfs_layout_template_content($level_1['id'], array('order_by' => $order_by));

            if(count($q_1) > 0)
            {
                for($i = 0; $i <count($q_1); $i++)
                {
                    $val = explode(".", $q_1[$i]['set_section_no']);
                    $val = $val[1];

                    $val_array = $q_1[$i];

                    $j = $i-1;

                    $val_1 = explode(".", $q_1[$j]['set_section_no']);
                    $val_1 = $val_1[1];

                    while($j >= 0 && (int)$val_1 > (int)$val)
                    {
                        $q_1[$j+1] = $q_1[$j];
                        $j--;

                        $val_1 = explode(".", $q_1[$j]['set_section_no']);
                        $val_1 = $val_1[1];
                    }

                    $q_1[$j+1] = $val_array;
                }

                // get data where set_parent like 2.1, 2.2, 2.3 ...
                foreach ($q_1 as $key_2 => $level_2) 
                {
                    // update order_by
                    $order_by ++;
                    $this->update_fs_ntfs_layout_template_content($level_2['id'], array('order_by' => $order_by));

                    if(strpos($level_2['set_parent'], '.0') == false)   // to avoid main category wrongly take roman content as sub category such as 6.0 Investment in subsidiaries
                    {
                        $q_2 = $this->db->query("SELECT lyt.* 
                                                    FROM fs_ntfs_layout_template lyt 
                                                    WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                                " AND lyt.set_parent = " . $level_2['set_section_no'] . 
                                                " ORDER BY order_by");
                        $q_2 = $q_2->result_array();

                        // level 3
                        if(count($q_2) > 0)
                        {
                            foreach ($q_2 as $key_3 => $level_3) 
                            {
                                // update order_by
                                $order_by ++;
                                $this->update_fs_ntfs_layout_template_content($level_3['id'], array('order_by' => $order_by));
                            }
                        }
                    }
                    
                }
            }
        }

        return $order_key;
    }

    public function rearrange_ntfs_template_layout_section_no($fs_company_info_id) // rearrange section no eg. 2.0, 2.1 ...
    {
        // get data where set_parent is 0.
        $q = $this->db->query("SELECT lyt.* 
                                FROM fs_ntfs_layout_template lyt  
                                WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                " AND lyt.set_parent=0 ORDER BY order_by");
        $q = $q->result_array();

        $order_by = 0;
        $order_key = [];

        $main_count = 1;
        
        // get data where set_parent like 1, 2 , 3 ...
        foreach ($q as $key_1 => $level_1) 
        {
            $q_1 = $this->db->query("SELECT lyt.* 
                                        FROM fs_ntfs_layout_template lyt 
                                        WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                    " AND lyt.set_parent = " . $level_1['set_section_no'] . " ORDER BY order_by");
            $q_1 = $q_1->result_array();

            // update order_by
            $order_by ++;
            $this->update_fs_ntfs_layout_template_content($level_1['id'], array('set_section_no' => $main_count . '.0'));

            if(count($q_1) > 0)
            {
                $sub_count = 1;

                // get data where set_parent like 2.1, 2.2, 2.3 ...
                foreach ($q_1 as $key_2 => $level_2) 
                {
                    // update order_by
                    $order_by ++;
                    $this->update_fs_ntfs_layout_template_content($level_2['id'], array('set_parent' => $main_count . '.0', 'set_section_no' => $main_count . '.' . $sub_count));

                    if(strpos($level_2['set_parent'], '.0') == false)   // to avoid main category wrongly take roman content as sub category such as 6.0 Investment in subsidiaries
                    {
                        $q_2 = $this->db->query("SELECT lyt.* 
                                                    FROM fs_ntfs_layout_template lyt 
                                                    WHERE lyt.fs_company_info_id =" . $fs_company_info_id . 
                                                " AND lyt.set_parent = " . $level_2['set_section_no'] . 
                                                " ORDER BY order_by");
                        $q_2 = $q_2->result_array();

                        // level 3
                        if(count($q_2) > 0)
                        {
                            foreach ($q_2 as $key_3 => $level_3) 
                            {
                                // update order_by
                                $order_by ++;

                                if($level_3['set_section_no'] == $level_3['set_parent'])
                                {
                                    $this->update_fs_ntfs_layout_template_content($level_3['id'], 
                                        array(
                                            'set_parent'     => $main_count . '.' . $sub_count, 
                                            'set_section_no' => $main_count . '.' . $sub_count)
                                        );
                                }
                                else
                                {
                                    $this->update_fs_ntfs_layout_template_content($level_3['id'], array('set_parent' => $main_count . '.' . $sub_count));
                                }
                            }
                        }
                    }

                    $sub_count++;
                }
            }

            $main_count++;
        }

        return $order_key;
    }

    public function update_fs_ntfs_layout_template_content($id, $data)
    {
        $result = $this->db->update('fs_ntfs_layout_template', $data, array('id' => $id));

        return $result;
    }

    public function update_related_notes($fs_company_info_id, $final_document_status)
    {
        if($final_document_status['changed_final_document_type'])
        {
            $fs_note_list = $this->get_note_list_default_depend_final_report($fs_company_info_id);  // get default list
            $fs_note_template_default_id_list = array_column($fs_note_list, 'fs_note_template_default_id');

            $fs_note_templates_master = $this->db->query("SELECT * FROM fs_note_templates_master WHERE fs_company_info_id=" . $fs_company_info_id);
            $fs_note_templates_master = $fs_note_templates_master->result_array();

            $fntm_fntd_id_list = array_column($fs_note_templates_master, 'fs_note_templates_default_id');

            $diff = array_diff($fntm_fntd_id_list, $fs_note_template_default_id_list);   // get fntd_id which is not in the created fs_note_templates_master

             /* ------ delete fs_note_templates_master ------ */
            $delete_fntm_ids = [];

            // print_r($diff);

            foreach ($diff as $key => $value) 
            {
                array_push($delete_fntm_ids, $fs_note_templates_master[$key]['id']);
            }

            if(count($delete_fntm_ids) > 0)
            {
                $this->db->where_in('id', $delete_fntm_ids);
                $this->db->delete('fs_note_templates_master');
            }
            /* ------ END OF delete fs_note_templates_master ------ */

            /* ------ delete fs_note_details where fs_note_templates_master_id in $delete_fntm_ids ------ */
            $fs_note_details_list = $this->db->query("SELECT * FROM fs_note_details WHERE fs_company_info_id = " . $fs_company_info_id);
            $fs_note_details_list = $fs_note_details_list->result_array();

            $delete_fnd_ids = [];

            foreach ($fs_note_details_list as $key1 => $value1) 
            {
                if(in_array($value1['fs_note_templates_master_id'], $delete_fntm_ids))
                {
                    array_push($delete_fnd_ids, $value['id']);
                }
            }

            if(count($delete_fnd_ids) > 0)
            {
                $this->db->where_in('id', $delete_fnd_ids);
                $this->db->delete('fs_note_details');
            }
            /* ------ END OF delete fs_note_details where fs_note_templates_master_id in $delete_fntm_ids ------ */

            /* ------ delete fs_state_comp_income_fs_note_details where fs_note_templates_master_id in $delete_fnd_ids ------ */
            foreach ($delete_fnd_ids as $key2 => $value2) 
            {
                $fs_sci_fnd_data = $this->db->query("SELECT * FROM fs_state_comp_income_fs_note_details WHERE fs_note_details_id = " . $value2);
                $fs_sci_fnd_data = $fs_sci_fnd_data->result_array();

                if(count($fs_sci_fnd_data) > 0)
                {
                    $this->db->where('id', $delete_sci_fnd_ids);
                    $this->db->delete('fs_state_comp_income_fs_note_details');
                }
            }
            /* ------ END OF delete fs_state_comp_income_fs_note_details where fs_note_templates_master_id in $delete_fnd_ids ------ */

            /* ------ Add in new data for unlisted in fs_note_templates_master ------ */
            $fs_note_templates_master_updated = $this->db->query("SELECT * FROM fs_note_templates_master WHERE fs_company_info_id=" . $fs_company_info_id);
            $fs_note_templates_master_updated = $fs_note_templates_master_updated->result_array();

            $updated_lytd = array_column($fs_note_templates_master_updated, 'fs_note_templates_default_id');

            // print_r($fs_note_templates_master_updated);

            foreach ($fs_note_template_default_id_list as $key3 => $value3) 
            {
                if(in_array($value3, $updated_lytd))    // update order_by
                {
                    $selected_key = array_search($value3, $updated_lytd);

                    // print_r($fs_note_templates_master_updated[$selected_key]);

                    $this->db->where('id', $fs_note_templates_master_updated[$selected_key]['id']);
                    $this->db->update('fs_note_templates_master', array('order_by' => $fs_note_list[$key3]['order_by']));
                }
                else // create new row
                {
                    $this->db->insert('fs_note_templates_master', 
                                        array(
                                            'fs_company_info_id' => $fs_company_info_id,
                                            'fs_note_templates_default_id' => $value3,
                                            'order_by'  => $fs_note_list[$key3]['order_by'],
                                            'link_allowed' => 1
                                        ));
                }
            }
            /* ------ END OF Add in new data for unlisted in fs_note_templates_master ------ */
        }
    }

    /* insert list of default for tax expense reconcilation */
    public function insert_list_default_tax_expense_reconciliation($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_list_tax_expense_reconciliation ter WHERE in_used = 1 ORDER BY order_by");
        $q = $q->result_array();

        $data = [];

        foreach ($q as $key => $value) 
        {
            array_push($data, 
                array(
                    'fs_company_info_id'                    => $fs_company_info_id,
                    'fs_list_tax_expense_reconciliation_id' => $value['id'],
                    'order_by'                              => $key + 1
                )
            );
        }

        $result = $this->db->insert_batch('fs_tax_expense_reconciliation', $data);

        return $result;
    }

    /* insert list of default for tax expense reconcilation */
    public function insert_list_default($fs_company_info_id, $insert_tbl, $from_tbl)
    {
        $q = $this->db->query('SELECT * FROM ' . $from_tbl . ' ter WHERE in_used = 1 ORDER BY order_by');
        $q = $q->result_array();

        $tbl_id = $from_tbl . '_id';

        $data = [];

        foreach ($q as $key => $value) 
        {
            array_push($data, 
                array(
                    'fs_company_info_id' => $fs_company_info_id,
                    $tbl_id              => $value['id'],
                    'order_by'           => $key + 1
                )
            );
        }

        $result = $this->db->insert_batch($insert_tbl, $data);

        return $result;
    }

    public function save_group_not_consolidated($data)
    {
        return $result = $this->db->insert('fs_subsi_not_consolidated', $data);
    }

    public function save_dynamic_row_ntfs_table($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $delete_key => $delete_value) 
            {
                if(!empty($delete_value))
                {
                    $this->db->delete($data['table_name'], array('id' => $delete_value));
                }
            }
        }

        foreach ($data['ntfs_data'] as $key => $value)
        {
            if(!empty($value['id']))    // update
            {
                $info = $this->db->query("SELECT * FROM " . $data['table_name'] . " WHERE id=" . $value['id']);
                $info = $info->result_array();

                if( $info[0]['description']               != $value['info']['description']               ||
                    $info[0]['value']                     != $value['info']['value']                     ||
                    $info[0]['company_end_prev_ye_value'] != $value['info']['company_end_prev_ye_value'] ||
                    $info[0]['group_end_this_ye_value']   != $value['info']['group_end_this_ye_value']   ||
                    $info[0]['group_end_prev_ye_value']   != $value['info']['group_end_prev_ye_value']   ||
                    $info[0]['order_by']                  != $value['info']['order_by']
                )
                {
                    $result = $this->update_tbl_data($data['table_name'], array($value));
                    array_push($return_ids, $value['id']);
                }
                else    // create new
                {
                    $id = $value['id'];
                    array_push($return_ids, $id);
                }
            }
            else
            {
                $result = $this->db->insert($data['table_name'], $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function save_dynamic_row_ntfs_table_1($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $delete_key => $delete_value) 
            {
                if(!empty($delete_value))
                {
                    $this->db->delete($data['table_name'], array('id' => $delete_value));
                }
            }
        }

        foreach ($data['ntfs_data'] as $key => $value)
        {
            if(!empty($value['id']))    // update
            {
                $info = $this->db->query("SELECT * FROM " . $data['table_name'] . " WHERE id=" . $value['id']);
                $info = $info->result_array();

                if( $info[0]['section']     != $value['info']['section']     ||
                    $info[0]['is_checked']  != $value['info']['is_checked']  ||
                    $info[0]['description'] != $value['info']['description'] ||
                    $info[0]['row_item']    != $value['info']['row_item']    ||
                    $info[0]['order_by']    != $value['info']['order_by']
                )
                {
                    $result = $this->update_tbl_data($data['table_name'], array($value));

                    array_push($return_ids, $value['id']);
                }
                else    // create new
                {
                    $id = $value['id'];
                    array_push($return_ids, $id);
                }
            }
            else
            {
                $result = $this->db->insert($data['table_name'], $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function save_intangible_assets($data)    // insert new record if got changes or id is 0
    {
        if(count($data['ia_deleted_ids']) > 0)
        {   
            foreach ($data['ia_deleted_ids'] as $key => $value) 
            {
                $this->db->where('id', $value);
                $result = $this->db->update('fs_sub_intangible_assets', array('is_removed' => 1));
            }
        }

        // return $data['ids'];
        foreach ($data['ids'] as $key => $id) 
        {
            if($id == 0)
            {
                // return $data['ia_info'][$key];
                $result = $this->db->insert('fs_sub_intangible_assets', $data['ia_info'][$key]);
                // return $data['ia_info'][$key];
            }
            else
            {
                $info = $this->db->query("SELECT * FROM fs_sub_intangible_assets WHERE id=" . $id);
                $info = $info->result_array();

                if($info[0]['name'] != $data['ia_info'][$key]['name'] ||
                    $info[0]['duration'] != $data['ia_info'][$key]['duration'] || 
                    $info[0]['order_by'] != $data['ia_info'][$key]['order_by'])
                {
                    $result = $this->db->insert('fs_sub_intangible_assets', $data['ia_info'][$key]);
                }

                // return $info[0]['name'];
            }
        }

        return true;
    }

    public function save_intangible_assets_info($data)
    {
        $return_ia_info_id = '';
        $temp = [];

        if(!empty($data['id'])){
            $info = $this->db->query("SELECT * FROM fs_sub_intangible_assets_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if( $info[0]['range_of_year'] != $data['info']['range_of_year'])
            {
                $result = $this->db->insert('fs_sub_intangible_assets_info', $data['info']);
                $return_ia_info_id = $this->db->insert_id();
            }
            else
            {
                $return_ia_info_id = $data['id'];
            }
        }
        else
        {
            $result = $this->db->insert('fs_sub_intangible_assets_info', $data['info']);
            $return_ia_info_id = $this->db->insert_id();
        }

        return $return_ia_info_id;
    }

    public function save_sub_ppe($data)    // insert new record if got changes or id is 0
    {
        $return_ids     = [];
        $return_codes   = [];

        if(count($data['sub_ppe_deleted_ids']) > 0)
        {   
            foreach ($data['sub_ppe_deleted_ids'] as $key => $value) 
            {
                $this->db->where('id', $value);
                $result = $this->db->update('fs_sub_ppe', array('is_removed' => 1));
            }
        }

        foreach ($data['ids'] as $key => $id) 
        {
            if($id == 0)
            {
                $result = $this->db->insert('fs_sub_ppe', $data['sub_ppe_info'][$key]);
                $return_id = $this->db->insert_id();

                array_push($return_ids, $return_id);
                array_push($return_codes, $data['sub_ppe_info'][$key]['sub_ppe_code']);
            }
            else
            {
                $info = $this->db->query("SELECT * FROM fs_sub_ppe WHERE id=" . $id);
                $info = $info->result_array();

                if($info[0]['name'] != $data['sub_ppe_info'][$key]['name'] ||
                    $info[0]['duration'] != $data['sub_ppe_info'][$key]['duration'] || 
                    $info[0]['order_by'] != $data['sub_ppe_info'][$key]['order_by'])
                {
                    $temp_data = array(
                                    'id' => $id,
                                    'info' => $data['sub_ppe_info'][$key]
                                );
                    $result = $this->update_tbl_data('fs_sub_ppe', array($temp_data));
                }

                array_push($return_ids, $id);
                array_push($return_codes, $info[0]['sub_ppe_code']);
            }
        }

        return array('return_ids' => $return_ids, 'return_codes' => $return_codes);
    }

    public function save_sub_ppe_info($data)
    {
        $return_sub_ppe_info_id = '';
        $temp = [];

        if(!empty($data['id'])){
            $info = $this->db->query("SELECT * FROM fs_sub_ppe_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if( $info[0]['fs_list_depreciation_method_id'] != $data['fs_list_depreciation_method_id'])
            {
                $temp_data = array(
                                'id'    => $data['id'],
                                'info'  => array(
                                                'fs_company_info_id'             => $data['fs_company_info_id'],
                                                'fs_list_depreciation_method_id' => $data['fs_list_depreciation_method_id']
                                            )
                                );

                // $result = $this->db->update('fs_sub_ppe_info', $temp_data);
                $result = $this->update_tbl_data('fs_sub_ppe_info', array($temp_data));
            }

            $return_sub_ppe_info_id = $data['id'];
        }
        else
        {
            $temp_data = array(
                                'fs_company_info_id'             => $data['fs_company_info_id'],
                                'fs_list_depreciation_method_id' => $data['fs_list_depreciation_method_id']);

            $result = $this->db->insert('fs_sub_ppe_info', $temp_data);
            $return_sub_ppe_info_id = $this->db->insert_id();
        }

        return $return_sub_ppe_info_id;
    }

    public function save_sub_inventories($data)
    {
        $return_inventories_info_id = '';
        $temp = [];

        if(!empty($data['id'])){
            $info = $this->db->query("SELECT * FROM fs_sub_inventories_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if( $info[0]['fs_list_net_realizable_value_id'] != $data['fs_list_net_realizable_value_id'])
            {
                $temp_data = array(
                                'fs_company_info_id'             => $data['fs_company_info_id'],
                                'fs_list_net_realizable_value_id' => $data['fs_list_net_realizable_value_id']);

                $result = $this->db->insert('fs_sub_inventories_info', $temp_data);
                $return_inventories_info_id = $this->db->insert_id();
            }
            else
            {
                $return_inventories_info_id = $data['id'];
            }
        }
        else
        {
            $temp_data = array(
                                'fs_company_info_id'             => $data['fs_company_info_id'],
                                'fs_list_net_realizable_value_id' => $data['fs_list_net_realizable_value_id']);

            $result = $this->db->insert('fs_sub_inventories_info', $temp_data);
            $return_inventories_info_id = $this->db->insert_id();
        }

        return $return_inventories_info_id;
    }

    public function save_sub_provision($data)    // insert new record if got changes or id is 0
    {
        if(count($data['p_deleted_ids']) > 0)
        {   
            foreach ($data['p_deleted_ids'] as $key => $value) 
            {
                $this->db->where('id', $value);
                $result = $this->db->update('fs_sub_provision', array('is_removed' => 1));
            }
        }

        // return $data['ids'];
        foreach ($data['ids'] as $key => $id) 
        {
            if($id == 0)
            {
                $result = $this->db->insert('fs_sub_provision', $data['p_info'][$key]);
            }
            else
            {
                $info = $this->db->query("SELECT * FROM fs_sub_provision WHERE id=" . $id);
                $info = $info->result_array();

                if( $info[0]['is_shown'] != $data['p_info'][$key]['is_shown'] ||
                    $info[0]['title']    != $data['p_info'][$key]['title'] ||
                    $info[0]['content']  != $data['p_info'][$key]['content'] || 
                    $info[0]['order_by'] != $data['p_info'][$key]['order_by'])
                {
                    $result = $this->db->insert('fs_sub_provision', $data['p_info'][$key]);
                }

                // return $info[0]['name'];
            }
        }

        return true;
    }

    public function save_ntfs_employee_benefits($data)
    {
        $result = $this->db->insert('fs_employee_benefits', $data);

        return $result;
    }

    public function save_sub_investment_properties($data)
    {
        $result = $this->db->insert('fs_investment_properties', $data);

        return $result;
    }

    public function save_employee_benefits_expense_ntfs($data)
    {
        $result = true;
        
        $return_ebe_ntfs_id = '';
        $temp = [];

        if(!empty($data['id'])){
            $info = $this->db->query("SELECT * FROM fs_employee_benefits_expense_ntfs WHERE id=" . $data['id']);
            $info = $info->result_array();

            if( $info[0]['is_shown'] != $data['info']['is_shown'] || $info[0]['share_option_plans_content'] != $data['info']['share_option_plans_content'])
            {
                $result = $this->db->insert('fs_employee_benefits_expense_ntfs', $data['info']);
                $return_ebe_ntfs_id = $this->db->insert_id();
            }
            else
            {
                $return_ebe_ntfs_id = $data['id'];
            }
        }
        else
        {
            $result = $this->db->insert('fs_employee_benefits_expense_ntfs', $data['info']);
            $return_ebe_ntfs_id = $this->db->insert_id();
        }

        return array('result' => $result, 'return_id' => $return_ebe_ntfs_id);
    }

    public function save_profit_b4_tax_ntfs($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $pbt_delete_key => $pbt_delete_value) 
            {
                $this->db->where('id', $pbt_delete_value);
                $result = $this->db->update('fs_profit_be4_tax', array('is_removed' => 1));
            }
        }

        foreach ($data['pbt'] as $key => $value)
        {
            if(!empty($value['id']) || $value['id'] == 0)
            {
                $info = $this->db->query("SELECT * FROM fs_profit_be4_tax WHERE id=" . $value['id']);
                $info = $info->result_array();

                if( $info[0]['fs_categorized_account_id'] != $value['info']['fs_categorized_account_id'] || 
                    $info[0]['income_expense_type']       != $value['info']['income_expense_type']      ||
                    $info[0]['order_by']                  != $value['info']['order_by']
                )
                {
                    $result = $this->db->insert('fs_profit_be4_tax', $value['info']);
                    $id = $this->db->insert_id();

                    array_push($return_ids, $id);
                }
                else
                {
                    $id = $value['id'];
                    array_push($return_ids, $id);
                }
            }
            else
            {
                $result = $this->db->insert('fs_profit_be4_tax', $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return array('result' => $result, 'return_ids' => $return_ids);
    }

    public function save_fs_tax_expense_ntfs($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $te_delete_key => $te_delete_value) 
            {
                $this->db->where('id', $te_delete_value);
                $result = $this->db->update('fs_tax_expense_ntfs', array('is_removed' => 1));
            }
        }

        foreach ($data['te_ntfs'] as $key => $value)
        {
            if(!empty($value['id']))    // update
            {
                $info = $this->db->query("SELECT * FROM fs_tax_expense_ntfs WHERE id=" . $value['id']);
                $info = $info->result_array();

                if( $info[0]['description']               != $value['info']['description']               || 
                    $info[0]['value']                     != $value['info']['value']                     ||
                    $info[0]['company_end_prev_ye_value'] != $value['info']['company_end_prev_ye_value'] ||
                    $info[0]['group_end_this_ye_value']   != $value['info']['group_end_this_ye_value']   ||
                    $info[0]['group_end_prev_ye_value']   != $value['info']['group_end_prev_ye_value']   ||
                    $info[0]['order_by']                  != $value['info']['order_by']
                )
                {
                    // $result = $this->db->insert('fs_tax_expense_ntfs', $value['info']);
                    // $id = $this->db->insert_id();
                    $result = $this->update_tbl_data('fs_tax_expense_ntfs', array($value));

                    array_push($return_ids, $value['id']);
                }
                else    // create new
                {
                    $id = $value['id'];
                    array_push($return_ids, $id);
                }
            }
            else
            {
                $generated_code = $this->fs_replace_content_model->rand_string(8);

                $value['info']['tax_expense_code'] = $generated_code;

                $result = $this->db->insert('fs_tax_expense_ntfs', $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function save_fs_tax_expense_reconciliation($data)   // update data only
    {
        foreach ($data as $key => $value) 
        {
            $this->db->where('id', $value['id']);
            $result = $this->db->update('fs_tax_expense_reconciliation', $value['info']);
        }

        return $result;
    }

    public function save_fs_tax_expense_ntfs_info($data)
    {
        $return_te_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_tax_expense_ntfs_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['is_shown'] != $data['info']['is_shown'] || $info[0]['text_content'] != $data['info']['text_content'])
            {
                // $result = $this->db->insert('fs_tax_expense_ntfs_info', $data['info']);
                // $return_te_info_id = $this->db->insert_id();
                $result = $this->update_tbl_data('fs_tax_expense_ntfs_info', array($data));
            }
            $return_te_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_tax_expense_ntfs_info', $data['info']);
            $return_te_info_id = $this->db->insert_id();
        }

        return $return_te_info_id;
    }

    

    public function save_fs_investment_associates_info($data)
    {
        $return_iia_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_investment_in_associates_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_investment_in_associates_info', array($data));
            }
            $return_iia_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_investment_in_associates_info', $data['info']);
            $return_iia_info_id = $this->db->insert_id();
        }

        return $return_iia_info_id;
    }

    public function save_fs_investment_joint_venture_info($data)
    {
        $return_iijv_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_investment_in_joint_venture_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_investment_in_joint_venture_info', array($data));
            }
            $return_iijv_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_investment_in_joint_venture_info', $data['info']);
            $return_iijv_info_id = $this->db->insert_id();
        }

        return $return_iijv_info_id;
    }

    public function save_fs_insured_benefits_info($data)
    {
        $return_ib_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_insured_benefits_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_insured_benefits_info', array($data));
            }
            $return_ib_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_insured_benefits_info', $data['info']);
            $return_ib_info_id = $this->db->insert_id();
        }

        return $return_ib_info_id;
    }

    public function save_fs_share_capital_info($data)
    {
        $return_sc_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_share_capital_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_share_capital_info', array($data));
            }
            $return_sc_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_share_capital_info', $data['info']);
            $return_sc_info_id = $this->db->insert_id();
        }

        return $return_sc_info_id;
    }

    public function save_fs_event_occur_after_rp_info($data)
    {
        $return_eo_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_event_occur_after_rp_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_event_occur_after_rp_info', array($data));
            }
            $return_eo_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_event_occur_after_rp_info', $data['info']);
            $return_eo_info_id = $this->db->insert_id();
        }

        return $return_eo_info_id;
    }

    public function save_fs_going_concern_info($data)
    {
        $return_gc_info_id = '';
        $temp = [];

        if(!empty($data['id']))
        {
            $info = $this->db->query("SELECT * FROM fs_going_concern_info WHERE id=" . $data['id']);
            $info = $info->result_array();

            if($info[0]['content'] != $data['info']['content'])
            {
                $result = $this->update_tbl_data('fs_going_concern_info', array($data));
            }
            $return_gc_info_id = $data['id'];
        }
        else
        {
            $result = $this->db->insert('fs_going_concern_info', $data['info']);
            $return_gc_info_id = $this->db->insert_id();
        }

        return $return_gc_info_id;
    }

    public function insert_note_details($data, $fs_statement_doc_type_id)
    {
        foreach ($data as $key => $value) 
        {   
            $fs_note_details_id = '';
            $result = true;
            
            if($value['id'] != '')
            {
                if($value['id'] != 0)   // update_fs_note_details
                {
                    if($value['info']['fs_note_templates_master_id'] == 0)
                    {
                        $this->db->where('id', $value['id']);
                        $result = $this->db->update('fs_note_details', array('in_use' => 0));
                    }
                    else
                    {
                        $this->db->where('id', $value['id']);
                        $result = $this->db->update('fs_note_details', $value['info']);
                    }

                    $fs_note_details_id = $value['id'];
                }
                else // create fs_note_details if id is 0
                {
                    if(!empty($value['info']['fs_note_templates_master_id']) && $value['info']['fs_note_templates_master_id'] != 0)
                    {
                        $result = $this->db->insert('fs_note_details', $value['info']);

                        $fs_note_details_id = $this->db->insert_id();
                    }
                }

                if($fs_statement_doc_type_id == 1)
                {
                    // insert to fs_state_comp_income_fs_note_details
                    if($value['info']['fs_categorized_account_round_off_id'] == 0 && !empty($fs_note_details_id))
                    {
                        if(!empty($value['fs_state_comp_income_id']))
                        {
                            $sci_fnd_arr = array(
                                            'fs_state_comp_income_id'   => $value['fs_state_comp_income_id'],
                                            'fs_note_details_id'        => $fs_note_details_id
                                        );

                            $q_sci_fnd = $this->db->query("SELECT * FROM fs_state_comp_income_fs_note_details sci_fnd 
                                                            WHERE sci_fnd.fs_state_comp_income_id=" . $value['fs_state_comp_income_id'] . ' AND sci_fnd.fs_note_details_id=' . $fs_note_details_id);
                            $q_sci_fnd = $q_sci_fnd->result_array();

                            if(count($q_sci_fnd) > 0)
                            {
                                $this->db->where('fs_state_comp_income_id', $value['fs_state_comp_income_id']);
                                $result = $this->db->update('fs_state_comp_income_fs_note_details', $sci_fnd_arr);
                            }
                            else
                            {
                                $result = $this->db->insert('fs_state_comp_income_fs_note_details', $sci_fnd_arr);
                            }
                        }
                    }
                }

                if(!$result)
                {
                    return array("status" => false, "return message" => "Error on inserting/updating data");
                }
            }
        }

        $result = 1;
        
        return $result;
    }

    public function insert_fs_state_comp_income_fs_note_details($fs_company_info_id) 
    {
        $sci_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id);
        $sci_data = $sci_data->result_array();

        $fs_ntfs = $this->get_fs_ntfs_json();

        $statement_key = array_search('Statement of comprehensive income', array_column($fs_ntfs['statements'], 'document_name'));

        $link_note_not_in_tree_data = $fs_ntfs['statements'][$statement_key]['link_note_not_in_tree'];

        $data = [];
        $fs_state_comp_income_ids = [];

        // build fs_note_details data
        foreach ($sci_data as $key => $value) 
        {
            if(in_array($value['fs_list_state_comp_income_section_id'], array_column($link_note_not_in_tree_data, 'fs_list_state_comp_income_section_id')))
            {
                $link_note_not_in_tree_data_key = array_search($value['fs_list_state_comp_income_section_id'], array_column($link_note_not_in_tree_data, 'fs_list_state_comp_income_section_id'));

                $fs_note_templates_master_id = $this->db->query("SELECT fntm.id AS `fntm_id`, fntd.*
                                                                FROM fs_note_template_default fntd
                                                                LEFT JOIN fs_note_templates_master fntm ON fntm.fs_note_templates_default_id = fntd.id AND fntm.fs_company_info_id = " . $fs_company_info_id . "
                                                                WHERE fntd.fs_ntfs_layout_template_default_id = " . $link_note_not_in_tree_data[$link_note_not_in_tree_data_key]['fs_ntfs_layout_template_default_id']);
                $fs_note_templates_master_id = $fs_note_templates_master_id->result_array();

                $temp_data =  array(
                                'fs_categorized_account_round_off_id' => 0,
                                'fs_company_info_id'                  => $fs_company_info_id,
                                'fs_note_templates_master_id'         => $fs_note_templates_master_id[0]['fntm_id'],
                                'fs_list_statement_doc_type_id'       => $link_note_not_in_tree_data[$link_note_not_in_tree_data_key]['fs_list_statement_doc_type_id'],
                                'in_use'                              => 1
                            );

                $arranged_noted_detail = $this->get_update_note_num_displayed(array($temp_data), $fs_company_info_id);

                $temp_data['note_num_displayed'] = $arranged_noted_detail[0]['note_num_displayed'];

                array_push($data, $temp_data);
                array_push($fs_state_comp_income_ids, $value['id']);
            }
        }

        // retrieve fs_state_comp_income_fs_note_details to do add or update
        $fs_state_comp_income_fs_note_details = $this->db->query("SELECT fsci_fnd.*
                                                                FROM `fs_state_comp_income_fs_note_details` fsci_fnd
                                                                LEFT JOIN fs_state_comp_income fsci ON fsci.id = fsci_fnd.fs_state_comp_income_id
                                                                WHERE fsci.fs_company_info_id = " . $fs_company_info_id . " AND fsci.in_use = 1");
        $fs_state_comp_income_fs_note_details = $fs_state_comp_income_fs_note_details->result_array();
        
        if(count($fs_state_comp_income_fs_note_details) > 0)    // update / add notes
        {
            foreach ($data as $key => $value) 
            {
                if(in_array($fs_state_comp_income_ids[$key], array_column($fs_state_comp_income_fs_note_details, 'fs_state_comp_income_id')))
                {
                    $sci_fnd_key = array_search($fs_state_comp_income_ids[$key], array_column($fs_state_comp_income_fs_note_details, 'fs_state_comp_income_id'));
                    
                    // update data
                    // print_r($fs_state_comp_income_fs_note_details[$sci_fnd_key]);
                    $this->db->where('id', $fs_state_comp_income_fs_note_details[$sci_fnd_key]['fs_note_details_id']);
                    $result = $this->db->update('fs_note_details', $value);
                }
                else
                {
                    // insert new data
                    $result_a = $this->db->insert('fs_note_details', $value);
                    $fs_note_details_id = $this->db->insert_id();

                    // insert to fs_state_comp_income_fs_note_details
                    $result_b = $this->db->insert('fs_state_comp_income_fs_note_details', 
                                                    array(
                                                        'fs_state_comp_income_id' => $fs_state_comp_income_ids[$key],
                                                        'fs_note_details_id'      => $fs_note_details_id
                                                    )
                                                );
                }
            }
        }
        else // add notes
        {
            foreach ($data as $key => $value) 
            {
                // insert to fs_note_details
                $result_a = $this->db->insert('fs_note_details', $value);
                $fs_note_details_id = $this->db->insert_id();

                // insert to fs_state_comp_income_fs_note_details
                $result_b = $this->db->insert('fs_state_comp_income_fs_note_details', 
                                                array(
                                                    'fs_state_comp_income_id' => $fs_state_comp_income_ids[$key],
                                                    'fs_note_details_id'      => $fs_note_details_id
                                                )
                                            );

            }
        }
    }

    public function insert_update_tbl_data($data)
    {
        $return_ids = array();

        if(count($data['deleted_ids']) > 0)
        {   
            foreach ($data['deleted_ids'] as $delete_key => $delete_value) 
            {
                if(!empty($delete_value))
                {
                    $this->db->delete($data['table_name'], array('id' => $delete_value));
                }
            }
        }

        foreach ($data['ntfs_data'] as $key => $value)
        {
            if(!empty($value['id']))    // update
            {
                $info = $this->db->query("SELECT * FROM " . $data['table_name'] . " WHERE id=" . $value['id']);
                $info = $info->result_array();

                $result = $this->update_tbl_data($data['table_name'], array($value));

                array_push($return_ids, $value['id']);
            }
            else
            {
                $result = $this->db->insert($data['table_name'], $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function insert_update_tbl_data_2($tbl_name, $data) // if empty id, create new row, else update data.
    {
        $return_ids = [];

        foreach ($data as $key => $value) 
        {
            if(!empty($value['id']))
            {   
                $this->db->where('id', $value['id']);
                $result = $this->db->update($tbl_name, $value['info']);

                array_push($return_ids, $value['id']);
            }
            else
            {
                $result = $this->db->insert($tbl_name, $value['info']);
                $id = $this->db->insert_id();

                array_push($return_ids, $id);
            }
        }

        return $return_ids;
    }

    public function insert_tbl_data($tbl_name, $data)    // insert new data only
    {
        foreach ($data as $key => $value) 
        {
            // print_r(array($data));
            $result = $this->db->insert($tbl_name, $value['info']);
        }

        return $result;
    }

    public function update_tbl_data($tbl_name, $data)   // update data only
    {
        foreach ($data as $key => $value) 
        {
            $this->db->where('id', $value['id']);
            $result = $this->db->update($tbl_name, $value['info']);
        }

        return $result;
    }

    public function delete_tbl_data($tbl_name, $id)
    {
        if(!empty($id))
        {
            $this->db->where_in('id', $id);
            $result = $this->db->delete($tbl_name);
        }

        return $result;
    }

    public function update_fs_note_details_note_num_displayed($fs_company_info_id, $fs_ntfs_layout_template_list)
    {
        $q = $this->db->query("SELECT fnd.*, fntd.fs_ntfs_layout_template_default_id 
                                FROM fs_note_details fnd 
                                LEFT JOIN fs_note_templates_master fntm ON fnd.fs_note_templates_master_id = fntm.id 
                                LEFT JOIN fs_note_template_default fntd ON fntd.id = fntm.fs_note_templates_default_id 
                                WHERE fnd.fs_company_info_id = 2 AND fnd.in_use = 1");
        $q = $q->result_array();

        $result = true;

        foreach ($q as $key => $value) 
        {
            $data = [];

            $fnlt_key = array_search($value['fs_ntfs_layout_template_default_id'], array_column($fs_ntfs_layout_template_list, 'id'));

            if(!empty($fnlt_key) && !empty($fs_ntfs_layout_template_list[$fnlt_key]->note_no))
            {
                $data = array(
                            'id' => $value['id'],
                            'info' => array('note_num_displayed' => $fs_ntfs_layout_template_list[$fnlt_key]->note_no)
                        );

                $result = $this->update_tbl_data('fs_note_details', array($data));

                if(!$result)
                {
                    return $result;
                }
            }
        }
        return $result;
    }
}
