<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fs_statements_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));

        $this->load->model('fs_notes_model');
        $this->load->model('fs_account_category_model');
    }

    public function get_fs_statement_json()
    {
        $url         = 'assets/json/fs_statements_insert_account.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        return $data_decode[0];
    }

    public function get_fs_statement()
    {
        return $this->get_fs_statement_json();
    }

    public function get_state_detailed_pro_loss_info($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_state_detailed_profit_loss WHERE fs_company_info_id=" . $fs_company_info_id);

        return $q->result_array();
    }

    // public function get_fs_state_comp_income($fs_company_info_id, $static_description)  // for tax expense ntfs use
    // {
        // $q = $this->db->query("SELECT sci.* 
        //                         FROM fs_state_comp_income sci
        //                         INNER JOIN (
        //                             SELECT fs_company_info_id, max(created_at) as `MaxDate` 
        //                             FROM fs_state_comp_income
        //                             WHERE fs_company_info_id = " . $fs_company_info_id . " AND static_description='" . $static_description . 
        //                                 "' ORDER BY created_at LIMIT 1
        //                         ) sci1 ON sci.fs_company_info_id = sci1.fs_company_info_id AND sci.created_at = sci1.MaxDate
        //                         WHERE sci.fs_company_info_id = " . $fs_company_info_id . " AND sci.static_description='" . $static_description . "'");

        // // $q = $this->db->query("SELECT * FROM fs_state_comp_income 
        // //                         WHERE fs_company_info_id=" . $fs_company_info_id . " AND static_description='" . $static_description);

        // return $q->result_array();

    //     return '';
    // }

    public function get_fs_state_comp_income($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id = " . $fs_company_info_id . " AND in_use = 1 ORDER BY order_by");

        return $q->result_array();
    }

    public function is_saved_fs_categorized_account_round_off($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_categorized_account_round_off WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        if(count($q) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // public function get_account_category_item_list($fs_company_info_id, $account_code_list)
    // {
    //     $data = [];

    //     foreach ($account_code_list as $key => $account_code) 
    //     {
    //         $temp_data = [];
            
    //         $q = $this->db->query("SELECT fca2.id AS `fs_categorized_parent_account_id`, fca.id AS `fs_categorized_account_id`, fca2.account_code AS `account_code`, fca.description, fca.value, fca2.description AS `category_name`, fca.company_end_prev_ye_value, fca.company_beg_prev_ye_value, fca.group_end_this_ye_value, fca.group_end_prev_ye_value, fca.group_beg_prev_ye_value
    //                 FROM audit_fs_categorized_account fca
    //                 LEFT JOIN fs_default_acc_category ON fs_default_acc_category.account_code = fca.parent
    //                 LEFT JOIN audit_fs_categorized_account fca2 ON fca2.account_code = fca.parent AND fca2.fs_company_info_id = fca.fs_company_info_id
    //                 WHERE fca.parent = '" . $account_code . "' AND fca.fs_company_info_id=" . $fs_company_info_id);

    //         if(count($q->result_array()) > 0)
    //         {
    //             // get fs_note_detail for parent
    //             // $fs_note_details_data_parent = $this->fs_notes_model->get_fs_note_details($q->result_array()[0]['fs_categorized_parent_account_id']);

    //             // if(empty($fs_note_details_data_parent))
    //             // {
    //             //     $fs_note_details_id_parent = '';
    //             //     $fs_note_templates_master_id_parent = '';
    //             // }
    //             // else
    //             // {
    //             //     $fs_note_details_id_parent = $fs_note_details_data_parent[0]['id'];
    //             //     $fs_note_templates_master_id_parent = $fs_note_details_data_parent[0]['fs_note_templates_master_id'];
    //             // }


    //             foreach($q->result_array() as $value_1)
    //             {
    //                 // get fs_note_details_id
    //                 // $fs_note_details = $this->fs_notes_model->get_fs_note_details($value_1['fs_categorized_account_id']);

    //                 if(empty($fs_note_details))
    //                 {
    //                     $fs_note_details_id = '';
    //                     $fs_note_templates_master_id = '';
    //                 }
    //                 else
    //                 {
    //                     $fs_note_details_id = $fs_note_details[0]['id'];
    //                     $fs_note_templates_master_id = $fs_note_details[0]['fs_note_templates_master_id'];
    //                 }

    //                 // create array for sub
    //                 array_push($temp_data, 
    //                     array(
    //                         'parent_id'     => $value_1['fs_categorized_parent_account_id'],
    //                         'id'            => $value_1['fs_categorized_account_id'],
    //                         'account_code'  => $value_1['account_code'],
    //                         'description'   => $value_1['description'],
    //                         'value'         => $value_1['value'],
    //                         'category_name' => $value_1['category_name'],
    //                         'fs_note_details_id' => $fs_note_details_id,
    //                         'fs_note_templates_master_id' => $fs_note_templates_master_id,
    //                         'company_end_prev_ye_value' => $value_1['company_end_prev_ye_value'],
    //                         'company_beg_prev_ye_value' => $value_1['company_beg_prev_ye_value'],
    //                         'group_end_this_ye_value'   => $value_1['group_end_this_ye_value'],
    //                         'group_end_prev_ye_value'   => $value_1['group_end_prev_ye_value'],
    //                         'group_beg_prev_ye_value'   => $value_1['group_beg_prev_ye_value']
    //                     )
    //                 );
    //             }

    //             // rearrange array
    //             array_push($data, array(
    //                                 array(
    //                                     'fs_categorized_account_id'          => $q->result_array()[0]['fs_categorized_parent_account_id'], 
    //                                     'category_name'                      => $q->result_array()[0]['category_name'], 
    //                                     'account_code'                       => $q->result_array()[0]['account_code'], 
    //                                     'value'                              => $q->result_array()[0]['value'],
    //                                     'company_end_prev_ye_value'          => $q->result_array()[0]['company_end_prev_ye_value'],
    //                                     'company_beg_prev_ye_value'          => $q->result_array()[0]['company_beg_prev_ye_value'],
    //                                     'group_end_this_ye_value'            => $q->result_array()[0]['group_end_this_ye_value'],
    //                                     'group_end_prev_ye_value'            => $q->result_array()[0]['group_end_prev_ye_value'],
    //                                     'group_beg_prev_ye_value'            => $q->result_array()[0]['group_beg_prev_ye_value'],
    //                                     // 'fs_note_details_id_parent'          =>  $fs_note_details_id_parent, 
    //                                     'fs_note_templates_master_id_parent' => $fs_note_templates_master_id_parent, 
    //                                     'data'                               => $temp_data
    //                                 )));
    //         }
    //         elseif(count($q->result_array()) == 0)  // for no parent
    //         {
    //             $q2 = $this->db->query("SELECT *
    //                                     FROM audit_fs_categorized_account 
    //                                     WHERE account_code = '" . $account_code . "' AND fs_company_info_id=" . $fs_company_info_id);

    //             // // get fs_note_templates_master_id
    //             // $fs_note_details = $this->fs_notes_model->get_fs_note_details($q2->result_array()[0]['fs_categorized_account_id']);

    //             // if(empty($fs_note_details))
    //             // {
    //             //     $fs_note_details_id = '';
    //             //     $fs_note_templates_master_id = '';
    //             // }
    //             // else
    //             // {
    //             //     $fs_note_details_id = $fs_note_details[0]['id'];
    //             //     $fs_note_templates_master_id = $fs_note_details[0]['fs_note_templates_master_id'];
    //             // }


    //             if(count($q2->result_array()) > 0)
    //             {
    //                 array_push($data, array(
    //                                     array(
    //                                         'fs_categorized_account_id'   => $q2->result_array()[0]['id'], 
    //                                         'category_name'               => $q2->result_array()[0]['description'], 
    //                                         'account_code'                => $q2->result_array()[0]['account_code'], 
    //                                         // 'fs_note_details_id'          => $fs_note_details_id, 
    //                                         'fs_note_templates_master_id' => $fs_note_templates_master_id,
    //                                         'data'                        => []
    //                                     )));
    //             }
    //             // }
                
    //         }
    //     }

    //     return $data;
    // }

    // public function get_account_category_item_round_off_list($fs_company_info_id, $account_code_list)
    // {
    //     $data = [];

    //     foreach ($account_code_list as $key => $account_code) 
    //     {
    //         $temp_data = [];
            
    //         $q = $this->db->query("SELECT fca2.id AS `fs_categorized_parent_account_id`, fca.id AS `fs_categorized_account_id`, fca2.account_code AS `account_code`, fca.description, fca.value, fca2.description AS `category_name`, fca.company_end_prev_ye_value, fca.company_beg_prev_ye_value, fca.group_end_this_ye_value, fca.group_end_prev_ye_value, fca.group_beg_prev_ye_value
    //                 FROM fs_categorized_account_round_off fca
    //                 LEFT JOIN fs_default_acc_category ON fs_default_acc_category.account_code = fca.parent
    //                 LEFT JOIN fs_categorized_account_round_off fca2 ON fca2.account_code = fca.parent AND fca2.fs_company_info_id = fca.fs_company_info_id
    //                 WHERE fca.parent = '" . $account_code . "' AND fca.fs_company_info_id=" . $fs_company_info_id);

    //         if(count($q->result_array()) > 0)
    //         {
    //             foreach($q->result_array() as $value_1)
    //             {
    //                 // if(empty($fs_note_details))
    //                 // {
    //                 //     $fs_note_details_id = '';
    //                 //     $fs_note_templates_master_id = '';
    //                 // }
    //                 // else
    //                 // {
    //                 //     $fs_note_details_id = $fs_note_details[0]['id'];
    //                 //     $fs_note_templates_master_id = $fs_note_details[0]['fs_note_templates_master_id'];
    //                 // }

    //                 // create array for sub
    //                 array_push($temp_data, 
    //                     array(
    //                         'parent_id'     => $value_1['fs_categorized_parent_account_id'],
    //                         'id'            => $value_1['fs_categorized_account_id'],
    //                         'account_code'  => $value_1['account_code'],
    //                         'description'   => $value_1['description'],
    //                         'value'         => $value_1['value'],
    //                         'category_name' => $value_1['category_name'],
    //                         'fs_note_details_id' => $fs_note_details_id,
    //                         'fs_note_templates_master_id' => $fs_note_templates_master_id,
    //                         'company_end_prev_ye_value' => $value_1['company_end_prev_ye_value'],
    //                         'company_beg_prev_ye_value' => $value_1['company_beg_prev_ye_value'],
    //                         'group_end_this_ye_value'   => $value_1['group_end_this_ye_value'],
    //                         'group_end_prev_ye_value'   => $value_1['group_end_prev_ye_value'],
    //                         'group_beg_prev_ye_value'   => $value_1['group_beg_prev_ye_value']
    //                     )
    //                 );
    //             }

    //             // rearrange array
    //             array_push($data, array(
    //                                 array(
    //                                     'fs_categorized_account_id'          => $q->result_array()[0]['fs_categorized_parent_account_id'], 
    //                                     'category_name'                      => $q->result_array()[0]['category_name'], 
    //                                     'account_code'                       => $q->result_array()[0]['account_code'], 
    //                                     'value'                              => $q->result_array()[0]['value'],
    //                                     'company_end_prev_ye_value'          => $q->result_array()[0]['company_end_prev_ye_value'],
    //                                     'company_beg_prev_ye_value'          => $q->result_array()[0]['company_beg_prev_ye_value'],
    //                                     'group_end_this_ye_value'            => $q->result_array()[0]['group_end_this_ye_value'],
    //                                     'group_end_prev_ye_value'            => $q->result_array()[0]['group_end_prev_ye_value'],
    //                                     'group_beg_prev_ye_value'            => $q->result_array()[0]['group_beg_prev_ye_value'],
    //                                     'fs_note_details_id_parent'          =>  $fs_note_details_id_parent, 
    //                                     'fs_note_templates_master_id_parent' => $fs_note_templates_master_id_parent, 
    //                                     'data'                               => $temp_data
    //                                 )));
    //         }
    //         elseif(count($q->result_array()) == 0)  // for no parent
    //         {
    //             $q2 = $this->db->query("SELECT *
    //                                     FROM fs_categorized_account_round_off 
    //                                     WHERE account_code = '" . $account_code . "' AND fs_company_info_id=" . $fs_company_info_id);

    //             // // get fs_note_templates_master_id
    //             // $fs_note_details = $this->fs_notes_model->get_fs_note_details($q2->result_array()[0]['fs_categorized_account_id']);

    //             // if(empty($fs_note_details))
    //             // {
    //             //     $fs_note_details_id = '';
    //             //     $fs_note_templates_master_id = '';
    //             // }
    //             // else
    //             // {
    //             //     $fs_note_details_id = $fs_note_details[0]['id'];
    //             //     $fs_note_templates_master_id = $fs_note_details[0]['fs_note_templates_master_id'];
    //             // }


    //             if(count($q2->result_array()) > 0)
    //             {
    //                 array_push($data, array(
    //                                     array(
    //                                         'fs_categorized_account_id'   => $q2->result_array()[0]['id'], 
    //                                         'category_name'               => $q2->result_array()[0]['description'], 
    //                                         'account_code'                => $q2->result_array()[0]['account_code'], 
    //                                         'fs_note_details_id'          => $fs_note_details_id, 
    //                                         'fs_note_templates_master_id' => $fs_note_templates_master_id,
    //                                         'data'                        => []
    //                                     )));
    //             }
    //         }
    //     }

    //     return $data;
    // }

    public function get_account_category_item_round_off_list($fs_company_info_id, $reference_id_list)   // updated on 14/2/2020
    {
        $data = [];

        foreach ($reference_id_list as $key => $reference_id) 
        {
            $temp_data = [];
            
            // $q = $this->db->query("SELECT fca2.id AS `fs_categorized_parent_account_id`, fca.id AS `fs_categorized_account_id`, fca2.account_code AS `account_code`, fca.description, fca.value, fca2.description AS `category_name`, fca.company_end_prev_ye_value, fca.company_beg_prev_ye_value, fca.group_end_this_ye_value, fca.group_end_prev_ye_value, fca.group_beg_prev_ye_value
            //         FROM fs_categorized_account_round_off fca
            //         LEFT JOIN fs_default_acc_category ON fs_default_acc_category.account_code = fca.parent
            //         LEFT JOIN fs_categorized_account_round_off fca2 ON fca2.account_code = fca.parent AND fca2.fs_company_info_id = fca.fs_company_info_id
            //         WHERE fca.parent = '" . $reference_id . "' AND fca.fs_company_info_id=" . $fs_company_info_id);

            $q = $this->db->query("SELECT fcaro.*, fca.parent, fca.type, fca.fs_default_acc_category_id, fca.id AS `fca_id`, fdac.account_code
                                    FROM fs_categorized_account_round_off fcaro 
                                    LEFT JOIN audit_fs_categorized_account fca ON fca.id = fcaro.fs_categorized_account_id 
                                    LEFT JOIN fs_default_acc_category fdac ON fdac.id = fca.fs_default_acc_category_id
                                    WHERE fcaro.fs_company_info_id =" . $fs_company_info_id);
            $q = $q->result_array();

            if(count($q->result_array()) > 0)
            {
                foreach($q->result_array() as $value_1)
                {
                    // create array for sub
                    array_push($temp_data, 
                        array(
                            'parent_id'     => $value_1['fs_categorized_parent_account_id'],
                            'id'            => $value_1['fs_categorized_account_id'],
                            'account_code'  => $value_1['account_code'],
                            'description'   => $value_1['description'],
                            'value'         => $value_1['value'],
                            'category_name' => $value_1['category_name'],
                            'fs_note_details_id' => $fs_note_details_id,
                            'fs_note_templates_master_id' => $fs_note_templates_master_id,
                            'company_end_prev_ye_value' => $value_1['company_end_prev_ye_value'],
                            'company_beg_prev_ye_value' => $value_1['company_beg_prev_ye_value'],
                            'group_end_this_ye_value'   => $value_1['group_end_this_ye_value'],
                            'group_end_prev_ye_value'   => $value_1['group_end_prev_ye_value'],
                            'group_beg_prev_ye_value'   => $value_1['group_beg_prev_ye_value']
                        )
                    );
                }

                // rearrange array
                array_push($data, array(
                                    array(
                                        'fs_categorized_account_id'          => $q->result_array()[0]['fs_categorized_parent_account_id'], 
                                        'category_name'                      => $q->result_array()[0]['category_name'], 
                                        'account_code'                       => $q->result_array()[0]['account_code'], 
                                        'value'                              => $q->result_array()[0]['value'],
                                        'company_end_prev_ye_value'          => $q->result_array()[0]['company_end_prev_ye_value'],
                                        'company_beg_prev_ye_value'          => $q->result_array()[0]['company_beg_prev_ye_value'],
                                        'group_end_this_ye_value'            => $q->result_array()[0]['group_end_this_ye_value'],
                                        'group_end_prev_ye_value'            => $q->result_array()[0]['group_end_prev_ye_value'],
                                        'group_beg_prev_ye_value'            => $q->result_array()[0]['group_beg_prev_ye_value'],
                                        'fs_note_details_id_parent'          =>  $fs_note_details_id_parent, 
                                        'fs_note_templates_master_id_parent' => $fs_note_templates_master_id_parent, 
                                        'data'                               => $temp_data
                                    )));
            }
            elseif(count($q->result_array()) == 0)  // for no parent
            {
                $q2 = $this->db->query("SELECT *
                                        FROM fs_categorized_account_round_off 
                                        WHERE account_code = '" . $reference_id . "' AND fs_company_info_id=" . $fs_company_info_id);

                if(count($q2->result_array()) > 0)
                {
                    array_push($data, array(
                                        array(
                                            'fs_categorized_account_id'   => $q2->result_array()[0]['id'], 
                                            'category_name'               => $q2->result_array()[0]['description'], 
                                            'account_code'                => $q2->result_array()[0]['account_code'], 
                                            'fs_note_details_id'          => $fs_note_details_id, 
                                            'fs_note_templates_master_id' => $fs_note_templates_master_id,
                                            'data'                        => []
                                        )));
                }
            }
        }

        return $data;
    }

    // public function get_total_operating_expenses($fs_company_info_id)
    // {
    //     $q = $this->db->query("SELECT * FROM fs_schedule_operating_expenses WHERE fs_company_info_id=" . $fs_company_info_id);

    //     return $q->result_array();
    // }


    public function insert_fs_total_by_account_category($data)
    {
        foreach($data as $key => $value)
        {
            $q = $this->db->query("SELECT * FROM fs_total_by_account_category WHERE fs_categorized_account_id='" . $value['fs_categorized_account_id'] . "'");

            if(count($q->result_array()) > 0)
            {
                $this->db->where('fs_categorized_account_id', $value['fs_categorized_account_id']);
                $result = $this->db->update('fs_total_by_account_category', $value);
            }
            else
            {
                $result = $this->db->insert('fs_total_by_account_category', $value);
            }

            if(!$result)
            {
                return false;
            }
        }

        return true;
    }

    public function insert_fs_schedule_operating_expenses($data)
    {
        $q = $this->db->query("SELECT * FROM fs_schedule_operating_expenses WHERE fs_company_info_id='" . $data['fs_company_info_id'] . "'");

        if(count($q->result_array()) > 0)
        {
            $this->db->where('fs_company_info_id', $data['fs_company_info_id']);
            $result = $this->db->update('fs_schedule_operating_expenses', $data);

            return $result;
        }
        else
        {
            return $result = $this->db->insert('fs_schedule_operating_expenses', $data);
        }
    }

    public function insert_fs_state_detailed_profit_loss($data)
    {
        $q = $this->db->query("SELECT * FROM fs_state_detailed_profit_loss WHERE fs_company_info_id='" . $data['fs_company_info_id'] . "'");

        if(count($q->result_array()) > 0)
        {
            $this->db->where('fs_company_info_id', $data['fs_company_info_id']);
            $result = $this->db->update('fs_state_detailed_profit_loss', $data);

            return $result;
        }
        else
        {
            return $result = $this->db->insert('fs_state_detailed_profit_loss', $data);
        }

        // return $data;
    }

    // public function insert_fs_state_comp_income($data)
    // {
    //     $return_id = '';
    //     $temp = [];

    //     if(!empty($data['id']) && !($data['id'] == 0))
    //     {
    //         $info = $this->db->query("SELECT * FROM fs_state_comp_income WHERE id=" . $data['id']);
    //         $info = $info->result_array();

    //         if($info[0]['static_description']         != $data['info']['static_description']        || 
    //             $info[0]['description']               != $data['info']['description']               ||
    //             $info[0]['value']                     != $data['info']['value']                     ||
    //             $info[0]['company_end_prev_ye_value'] != $data['info']['company_end_prev_ye_value'] ||
    //             $info[0]['group_end_this_ye_value']   != $data['info']['group_end_this_ye_value']   ||
    //             $info[0]['group_end_prev_ye_value']   != $data['info']['group_end_prev_ye_value']
    //         )
    //         {
    //             $result = $this->db->insert('fs_state_comp_income', $data['info']);
    //             $return_id = $this->db->insert_id();
    //         }
    //         else
    //         {
    //             $return_id = $data['id'];
    //         }
    //     }
    //     else
    //     {
    //         $result = $this->db->insert('fs_state_comp_income', $data['info']);
    //         $return_id = $this->db->insert_id();
    //     }

    //     return $return_id;
    // }

    public function insert_fs_state_comp_income($data)
    {
        $return_ids = [];

        foreach ($data as $key => $value) 
        {
            if(!empty($value['id']) && $value['id'] != 0)
            {
                $this->db->where('id', $value['id']);
                $result = $this->db->update('fs_state_comp_income', $value['info']);

                array_push($return_ids, $value['id']);
            }
            else
            {
                $result = $this->db->insert('fs_state_comp_income', $value['info']);

                array_push($return_ids, $this->db->insert_id());
            }

            if(!$result)
            {
                return ["status" => 0, "return message" => "Error on inserting/updating data", "return_ids" => array()];
            }
        }

        return ["status" => 1, "return message" => "Successfully saved data!", "return_ids" => $return_ids];
    }

    public function update_state_comp_income($fs_company_info_id)  // when tree update, update fs_state_comp_income
    {
        $q = $this->db->query("SELECT * FROM fs_state_comp_income sci WHERE sci.fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        $fca = $this->db->query("SELECT * FROM audit_categorized_account fca WHERE fca.fs_company_info_id = " . $fs_company_info_id);
        $fca_ids = array_column($fca->result_array(), 'id');

        $delete_fs_note_details = [];
        $delete_fs_state_comp_income = [];

        foreach ($q as $key => $value) 
        {
            if(in_array($value['fs_categorized_account_id'], $fca_ids))
            {   
                $update_fs_state_comp_income = [];

                $fcaro = $this->db->query("SELECT * FROM fs_categorized_account_round_off fcaro 
                                            WHERE fcaro.fs_categorized_account_id = " . $value['fs_categorized_account_id'] . 
                                            " AND fcaro.fs_company_info_id =" . $fs_company_info_id . 
                                            " AND fcaro.is_deleted = 0 ORDER BY order_by");
                $fcaro = $fcaro->result_array();

                // print_r($this->fs_account_category_model->calculate_total($fs_company_info_id, $fcaro[0]['account_code']));

                // $update_fs_state_comp_income = array(
                //                                     'description'           => $fcaro[0]['description'],
                //                                     'value_company_ye'      => $fcaro[0]['value'],
                //                                     'value_company_lye_end' => $fcaro[0]['company_end_prev_ye_value']
                //                                 );
            }
            elseif($value['fs_categorized_account_id'] == 0)
            {
                
            }
            else
            {
                // change status in_use to 0 for table fs_note_details (delete)
                array_push($delete_fs_state_comp_income, 
                    array(
                        'id' => $value['id'], 
                        'in_use' => 0
                    )
                ); 

                // change status in_use to 0 for table fs_note_details (delete)
                array_push($delete_fs_note_details, 
                    array(
                        'fs_state_comp_income_id' => $value['id'], 
                        'in_use' => 0
                    )
                );  
            }
        }

        if(count($delete_fs_state_comp_income) > 0)
        {
            $this->db->update_batch('fs_state_comp_income',$delete_fs_state_comp_income,'id');
        }

        if(count($delete_fs_note_details) > 0)
        {
            $this->db->update_batch('fs_note_details',$delete_fs_note_details,'fs_state_comp_income_id');
        }
    }

    public function insert_update_fs_state_comp_income($fs_company_info_id) // when tree is update, run this function to insert or update data. 
    {
        $fs_company_info   = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $fs_statement_list = $this->get_fs_statement();    // get list of code from json

        $array_template = array(
                                'value_company_ye'      => 0,
                                'value_company_lye_end' => 0,
                                'value_group_ye'        => 0,
                                'value_group_lye_end'   => 0
                            );

        $income_list_data                   = $array_template;
        $other_income_list_data             = $array_template;
        $changes_in_inventories_data        = $array_template;
        $purchases_and_related_costs_data   = $array_template;
        $expenses_data                      = $array_template;
        $pl_be4_tax_data                    = $array_template;
        $pl_after_tax_data                  = $array_template;
        $additional_data                    = $array_template;
        $soa_pl_data                        = $array_template;

        foreach ($fs_statement_list->statement_comprehensive_income[0]->sections as $sci_json_key => $sci_json_value) 
        {
            $data = [];

            if($sci_json_value->list_name == "income_list") // income list 
            {
                $income_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));

                foreach ($income_list as $ikey => $ivalue) 
                {
                    $income_list_data['value_company_ye']       += !empty($ivalue['parent_array'][0]['total_c'])     ? $ivalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $income_list_data['value_company_lye_end']  += !empty($ivalue['parent_array'][0]['total_c_lye']) ? $ivalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $income_list_data['value_group_ye']         += !empty($ivalue['parent_array'][0]['group_end_this_ye_value']) ? $ivalue['parent_array'][0]['group_end_this_ye_value'] * (-1)  : 0;
                    $income_list_data['value_group_lye_end']    += !empty($ivalue['parent_array'][0]['group_end_prev_ye_value']) ? $ivalue['parent_array'][0]['group_end_prev_ye_value'] * (-1)  : 0;
                    // $income_list_data['value_group_ye']         += !empty($ivalue['parent_array'][0]['total_g'])     ? $ivalue['parent_array'][0]['total_g']      : 0;
                    // $income_list_data['value_group_lye_end']    += !empty($ivalue['parent_array'][0]['total_g_lye']) ? $ivalue['parent_array'][0]['total_g_lye']  : 0;
                }
            }
            elseif($sci_json_value->list_name == "other_income_list") // other list 
            {
                $other_income_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));

                foreach ($other_income_list as $ikey => $ivalue) 
                {
                    $other_income_list_data['value_company_ye']       += !empty($ivalue['parent_array'][0]['total_c'])     ? $ivalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $other_income_list_data['value_company_lye_end']  += !empty($ivalue['parent_array'][0]['total_c_lye']) ? $ivalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $other_income_list_data['value_group_ye']         += !empty($ivalue['parent_array'][0]['group_end_this_ye_value']) ? $ivalue['parent_array'][0]['group_end_this_ye_value'] * (-1)  : 0;
                    $other_income_list_data['value_group_lye_end']    += !empty($ivalue['parent_array'][0]['group_end_prev_ye_value']) ? $ivalue['parent_array'][0]['group_end_prev_ye_value'] * (-1)  : 0;
                }

                // print_r($other_income_list_data);
                // print_r($this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));
            }
            elseif($sci_json_value->list_name == "changes_in_inventories")  // Changes in inventories
            {
                $opening_inventories_g_ye  = 0;
                $opening_inventories_g_lye = 0;
                $closing_inventories_g_ye  = 0;

                $opening_inventories_c_lye = 0;
                $closing_inventories_c_lye = 0;

                /* GET AND SET OPENING, CLOSING AND PURCAHSES */
                if(!empty($this->fs_notes_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[0]))))
                {
                    $opening_inventories = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_notes_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[0])))[0];
                }
                elseif(!empty($this->fs_notes_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[1]))))
                {
                    $closing_inventories = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_notes_model->get_fca_id($fs_company_info_id, array($sci_json_value->sub_account_code[1])))[0];
                }
                // print_r($opening_inventories);

                // if(count($opening_inventories) > 0)
                // {
                //     $opening_inventories_c_lye = $opening_inventories['parent_array'][0]['total_c_lye'];
                // }

                // if(count($closing_inventories_c_lye) > 0)
                // {
                //     $closing_inventories_c_lye = $closing_inventories['parent_array'][0]['total_c_lye'];
                // }


                if(isset($opening_inventories) && isset($closing_inventories))
                {
                    if(count($opening_inventories) >= 0 || count($closing_inventories) >= 0)
                    {
                        $data = array(
                                    'fs_company_info_id'    => $fs_company_info_id,
                                    'description'           => 'Changes in inventories',
                                    'value_group_ye'        => $opening_inventories['parent_array'][0]['group_end_this_ye_value'] + $closing_inventories['parent_array'][0]['group_end_this_ye_value'],
                                    'value_group_lye_end'   => $opening_inventories['parent_array'][0]['group_end_prev_ye_value'] + $closing_inventories['parent_array'][0]['group_end_prev_ye_value'],
                                    'fs_list_state_comp_income_section_id' => 1,
                                    // 'value'                     => $opening_inventories['parent_array'][0]['total_c'] + 
                                    //                                $purchases['parent_array'][0]['total_c'] - 
                                    //                                $closing_inventories['parent_array'][0]['total_c'], 
                                    'value_company_ye'      => $opening_inventories['parent_array'][0]['total_c'] + $closing_inventories['parent_array'][0]['total_c'],
                                    // 'company_end_prev_ye_value' => $opening_inventories_c_lye + $purchases_c_lye - $closing_inventories_c_lye
                                    'value_company_lye_end' => $opening_inventories['parent_array'][0]['total_c_lye'] + $closing_inventories['parent_array'][0]['total_c_lye'],
                                    'in_use' => 1
                                );

                        $changes_in_inventories_data['value_company_ye']       = $data['value_company_ye'] *(-1);
                        $changes_in_inventories_data['value_company_lye_end']  = $data['value_company_lye_end'] *(-1);
                        $changes_in_inventories_data['value_group_ye']         = $data['value_group_ye'] *(-1);
                        $changes_in_inventories_data['value_group_lye_end']    = $data['value_group_lye_end'] *(-1);
                    }
                }

                $cii_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=1"); // get changes in inventories
                $cii_data = $cii_data->result_array();

                if(count($cii_data) > 0)
                {
                    $changes_in_inventories_data['value_group_ye']         = $cii_data['value_group_ye'];
                    $changes_in_inventories_data['value_group_lye_end']    = $cii_data['value_group_lye_end'];
                }
            }
            elseif($sci_json_value->list_name == "expense_list") // Purchases and related costs
            {
                // get sub account codes list
                $expense_sub_list = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, $sci_json_value->account_category_code[0]);
                $expense_list     = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $expense_sub_list); 

                // $expenses_data
                foreach ($expense_list as $ekey => $evalue) 
                {
                    $expenses_data['value_company_ye']      += !empty($evalue['parent_array'][0]['total_c'])     ? $evalue['parent_array'][0]['total_c'] * (-1)     : 0;
                    $expenses_data['value_company_lye_end'] += !empty($evalue['parent_array'][0]['total_c_lye']) ? $evalue['parent_array'][0]['total_c_lye'] * (-1) : 0;
                    $expenses_data['value_group_ye']        += !empty($evalue['parent_array'][0]['total_g'])     ? $evalue['parent_array'][0]['total_g'] * (-1)     : 0;
                    $expenses_data['value_group_lye_end']   += !empty($evalue['parent_array'][0]['total_g_lye']) ? $evalue['parent_array'][0]['total_g_lye'] * (-1) : 0;
                }
            }
            elseif($sci_json_value->list_name == "purchases_and_related_costs") // Purchases and related costs
            {
                $purchases_and_related_costs = $this->fs_account_category_model->get_account_with_exclude_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code, $sci_json_value->exclude_sub_account_code);

                foreach ($purchases_and_related_costs as $prckey => $prcvalue) 
                {
                    $purchases_and_related_costs_data['value_company_ye']       += !empty($prcvalue['parent_array'][0]['total_c'])     ? $prcvalue['parent_array'][0]['total_c'] * (-1)     : 0;
                    $purchases_and_related_costs_data['value_company_lye_end']  += !empty($prcvalue['parent_array'][0]['total_c_lye']) ? $prcvalue['parent_array'][0]['total_c_lye'] * (-1) : 0;
                    $purchases_and_related_costs_data['value_group_ye']         += !empty($prcvalue['parent_array'][0]['total_g'])     ? $prcvalue['parent_array'][0]['total_g'] * (-1)     : 0;
                    $purchases_and_related_costs_data['value_group_lye_end']    += !empty($prcvalue['parent_array'][0]['total_g_lye']) ? $prcvalue['parent_array'][0]['total_g_lye'] * (-1) : 0;
                }

                $data = array(
                        'fs_company_info_id'    => $fs_company_info_id,
                        'description'           => 'Purchases and related costs',
                        'value_company_ye'      => ($purchases_and_related_costs_data['value_company_ye'] != 0)      ? $purchases_and_related_costs_data['value_company_ye']       : '',
                        'value_company_lye_end' => ($purchases_and_related_costs_data['value_company_lye_end'] != 0) ? $purchases_and_related_costs_data['value_company_lye_end']  : '',
                        'fs_list_state_comp_income_section_id' => 2,
                        'value_group_ye'        => ($purchases_and_related_costs_data['value_group_ye'] != 0)        ? $purchases_and_related_costs_data['value_group_ye']       : '',
                        'value_group_lye_end'   => ($purchases_and_related_costs_data['value_group_lye_end'] != 0)   ? $purchases_and_related_costs_data['value_group_lye_end']  : '',
                        'in_use' => 1
                    );

                /* ------------- if 'fs_state_comp_income' has record, we follow it ------------- */
                $prc_data = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=2"); // get purchases and related costs
                $prc_data = $prc_data->result_array();

                if(count($prc_data) > 0)
                {
                    $purchases_and_related_costs_data['value_group_ye']      = $prc_data['value_group_ye'];
                    $purchases_and_related_costs_data['value_group_lye_end'] = $prc_data['value_group_lye_end'];
                }
                /* ------------- END OF if 'fs_state_comp_income' has record, we follow it ------------- */
            }
            elseif ($sci_json_value->list_name == "additional_list") 
            {
                $additional_list = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code));    // TAXATION

                foreach ($additional_list as $akey => $avalue) 
                {
                    $additional_data['value_company_ye']      += !empty($avalue['parent_array'][0]['total_c'])     ? $avalue['parent_array'][0]['total_c'] * (-1)      : 0;
                    $additional_data['value_company_lye_end'] += !empty($avalue['parent_array'][0]['total_c_lye']) ? $avalue['parent_array'][0]['total_c_lye'] * (-1)  : 0;
                    $additional_data['value_group_ye']        += !empty($avalue['parent_array'][0]['total_g'])     ? $avalue['parent_array'][0]['total_g'] * (-1)      : 0;
                    $additional_data['value_group_lye_end']   += !empty($avalue['parent_array'][0]['total_g_lye']) ? $avalue['parent_array'][0]['total_g_lye'] * (-1)  : 0;
                }
            }
            elseif($sci_json_value->list_name == "soa_pl_list") // Share of associates profit or loss
            {
                // $soa_pl_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);    // TAXATION
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

                $soa_pl_list = $data;

                foreach ($soa_pl_list as $akey => $avalue) 
                {
                    $soa_pl_data['value_company_ye']      += !empty($avalue[0]['parent_array'][0]['total_c'])     ? $avalue[0]['parent_array'][0]['total_c'] * (-1)     : 0;
                    $soa_pl_data['value_company_lye_end'] += !empty($avalue[0]['parent_array'][0]['total_c_lye']) ? $avalue[0]['parent_array'][0]['total_c_lye'] * (-1) : 0;
                    $soa_pl_data['value_group_ye']        += !empty($avalue[0]['parent_array'][0]['total_g'])     ? $avalue[0]['parent_array'][0]['total_g'] * (-1)     : 0;
                    $soa_pl_data['value_group_lye_end']   += !empty($avalue[0]['parent_array'][0]['total_g_lye']) ? $avalue[0]['parent_array'][0]['total_g_lye'] * (-1) : 0;
                }
            }
            elseif($sci_json_value->list_name == "pl_be4_tax" || $sci_json_value->list_name == "pl_after_tax")  // profit/loss before tax  AND  profit/loss after tax
            {
                $profit_loss_description = '';

                $income_bigger_group_ty   = '';
                $income_bigger_group_ly   = '';
                $income_bigger_company_ty = '';
                $income_bigger_company_ly = '';

                // if(count($income_list_data) > 0 && count($purchases_and_related_costs_data) > 0)
                // {
                    $income_bigger_group_ty   = (($income_list_data['value_group_ye'] + $other_income_list_data['value_group_ye'] +
                                                 ($changes_in_inventories_data['value_group_ye'] + $purchases_and_related_costs_data['value_group_ye'] + $expenses_data['value_group_ye']) ) >= 0) ? true : false;

                    $income_bigger_group_ly   = (($income_list_data['value_group_lye_end'] + $other_income_list_data['value_group_lye_end'] +
                                                 ($changes_in_inventories_data['value_group_lye_end'] + $purchases_and_related_costs_data['value_group_lye_end'] + $expenses_data['value_group_lye_end'])) >= 0) ? true : false;

                    $income_bigger_company_ty = (($income_list_data['value_company_ye'] + $other_income_list_data['value_company_ye'] +
                                                 ($changes_in_inventories_data['value_company_ye'] + $purchases_and_related_costs_data['value_company_ye'] + $expenses_data['value_company_ye'])) >= 0) ? true : false;

                    $income_bigger_company_ly = (($income_list_data['value_company_lye_end'] + $other_income_list_data['value_company_lye_end'] +
                                                 ($changes_in_inventories_data['value_company_lye_end'] + $purchases_and_related_costs_data['value_company_lye_end'] + $expenses_data['value_company_lye_end'])) >= 0) ? true : false;
                // }

                    // print_r(array($income_bigger_group_ty, $income_bigger_group_ly, $income_bigger_company_ty, $income_bigger_company_ly));

                    // print_r(array($income_list_data['value_company_ye'], $changes_in_inventories_data['value_company_ye'], $purchases_and_related_costs_data['value_company_ye'], $expenses_data['value_company_ye']));
                // elseif(count($income_list_data) > 0)
                // {
                //     $income_bigger_group_ty   = true;
                //     $income_bigger_group_ly   = true;
                //     $income_bigger_company_ty = true;
                //     $income_bigger_company_ly = true;
                // }
                // elseif(count($purchases_and_related_costs_data) > 0)
                // {
                //     $income_bigger_group_ty   = false;
                //     $income_bigger_group_ly   = false;
                //     $income_bigger_company_ty = false;
                //     $income_bigger_company_ly = false;
                // }

                // for display description ("Profit, Loss, Profit/Loss, Loss/Profit")
                if($fs_company_info[0]['group_type'] != 1)    // for group
                {
                    // if($income_bigger_group_ty && $income_bigger_group_ly && $income_bigger_company_ty && $income_bigger_company_ly && $fs_company_info[0]['group_type'])   // if all are positive
                    if($income_bigger_group_ty && $income_bigger_group_ly && $income_bigger_company_ty && $income_bigger_company_ly) // if all are positive
                    {
                        $profit_loss_description = 'Profit';
                    }
                    elseif(!$income_bigger_group_ty && !$income_bigger_group_ly && !$income_bigger_company_ty && !$income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Loss';
                    }
                    else
                    {
                        if($income_bigger_group_ty) // check the first value if it is bigger value, set Profit/Loss, else set Loss/Profit
                        {
                            $profit_loss_description = 'Profit/Loss';   
                        }
                        else
                        {
                            $profit_loss_description = 'Loss/Profit';
                        }
                    }
                }
                elseif($fs_company_info[0]['group_type'] == 1)    // no group (company only)
                {
                    if($income_bigger_company_ty && $income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Profit';
                    }
                    elseif(!$income_bigger_company_ty && !$income_bigger_company_ly)
                    {
                        $profit_loss_description = 'Loss';
                    }
                    else
                    {
                        if($income_bigger_company_ty)
                        {
                            $profit_loss_description = 'Profit/Loss';
                        }
                        else
                        {
                            $profit_loss_description = 'Loss/Profit';
                        }
                    }   
                }

                if($sci_json_value->list_name == "pl_be4_tax")
                {
                    /* --- profit/loss before tax = income - (changes in inventories + purchases and related costs + expenses) --- */
                    $pl_be4_tax_data['value_company_ye']        = $income_list_data['value_company_ye']               + 
                                                                $other_income_list_data['value_company_ye']           +
                                                                ($changes_in_inventories_data['value_company_ye']     + 
                                                                $purchases_and_related_costs_data['value_company_ye'] + 
                                                                $expenses_data['value_company_ye']);

                    $pl_be4_tax_data['value_company_lye_end']   = $income_list_data['value_company_lye_end']               + 
                                                                $other_income_list_data['value_company_lye_end']           +
                                                                ($changes_in_inventories_data['value_company_lye_end']     + 
                                                                $purchases_and_related_costs_data['value_company_lye_end'] + 
                                                                $expenses_data['value_company_lye_end']);

                    $pl_be4_tax_data['value_group_ye']          = $income_list_data['value_group_ye']               + 
                                                                $other_income_list_data['value_group_ye']           +
                                                                ($changes_in_inventories_data['value_group_ye']     + 
                                                                $purchases_and_related_costs_data['value_group_ye'] + 
                                                                $expenses_data['value_group_ye']);

                    $pl_be4_tax_data['value_group_lye_end']     = $income_list_data['value_group_lye_end']               + 
                                                                $other_income_list_data['value_group_lye_end']           +
                                                                ($changes_in_inventories_data['value_group_lye_end']     + 
                                                                $purchases_and_related_costs_data['value_group_lye_end'] + 
                                                                $expenses_data['value_group_lye_end']);

                    $data = array(
                            'fs_company_info_id'    => $fs_company_info_id,
                            'description'           => $profit_loss_description . " before tax",
                            'value_group_ye'        => ($pl_be4_tax_data['value_group_ye'] != 0)        ? $pl_be4_tax_data['value_group_ye']        : '',
                            'value_group_lye_end'   => ($pl_be4_tax_data['value_group_lye_end'] != 0)   ? $pl_be4_tax_data['value_group_lye_end']   : '',
                            'fs_list_state_comp_income_section_id' => 3,
                            'value_company_ye'      => ($pl_be4_tax_data['value_company_ye'] != 0)      ? $pl_be4_tax_data['value_company_ye']      : '',
                            'value_company_lye_end' => ($pl_be4_tax_data['value_company_lye_end'] != 0) ? $pl_be4_tax_data['value_company_lye_end'] : '',
                            'in_use' => 1
                        );
                }
                elseif ($sci_json_value->list_name == "pl_after_tax") 
                {
                    /* --- Method 1: profit/loss after tax = profit/loss before tax - additional --- */
                    /* --- Method 2: profit/loss after tax = (income - (changes in inventories + purchases and related costs + expenses)) - additional --- */
                    // print_r($soa_pl_data);

                    $pl_after_tax_data['value_group_ye']        = $pl_be4_tax_data['value_group_ye']        - $additional_data['value_group_ye']        - $soa_pl_data['value_group_ye'];
                    $pl_after_tax_data['value_group_lye_end']   = $pl_be4_tax_data['value_group_lye_end']   - $additional_data['value_group_lye_end']   - $soa_pl_data['value_group_lye_end'];
                    $pl_after_tax_data['value_company_ye']      = $pl_be4_tax_data['value_company_ye']      - $additional_data['value_company_ye']      - $soa_pl_data['value_company_ye'];
                    $pl_after_tax_data['value_company_lye_end'] = $pl_be4_tax_data['value_company_lye_end'] - $additional_data['value_company_lye_end'] - $soa_pl_data['value_company_lye_end'];

                    $data = array(
                            'fs_company_info_id'    => $fs_company_info_id,
                            'description'           => $profit_loss_description . " after tax",
                            'value_group_ye'        => ($pl_after_tax_data['value_group_ye'] != 0)        ? $pl_after_tax_data['value_group_ye']        : '',
                            'value_group_lye_end'   => ($pl_after_tax_data['value_group_lye_end'] != 0)   ? $pl_after_tax_data['value_group_lye_end']   : '',
                            'fs_list_state_comp_income_section_id' => 4,
                            'value_company_ye'      => ($pl_after_tax_data['value_company_ye'] != 0)      ? $pl_after_tax_data['value_company_ye']      : '',
                            'value_company_lye_end' => ($pl_after_tax_data['value_company_lye_end'] != 0) ? $pl_after_tax_data['value_company_lye_end'] : '',
                            'in_use' => 1
                        );
                }
            }

            // print_r($sci_json_value->fs_list_state_comp_income_section_id);

            // Save data to fs_state_comp_income
            // print_r($sci_json_value->fs_list_state_comp_income_section_id);

            if(isset($sci_json_value->fs_list_state_comp_income_section_id) && $sci_json_value->fs_list_state_comp_income_section_id!= 5)
            {
                $retrieve_sci_data = $this->db->query("SELECT * FROM fs_state_comp_income sci WHERE sci.fs_company_info_id=" . $fs_company_info_id . " AND sci.fs_list_state_comp_income_section_id=" . $sci_json_value->fs_list_state_comp_income_section_id);
                $retrieve_sci_data = $retrieve_sci_data->result_array();

                if(count($retrieve_sci_data) > 0)
                {
                    // update data
                    if(!empty($data))
                    {   
                        $result = $this->db->update('fs_state_comp_income', $data, array('id' => $retrieve_sci_data[0]['id']));
                    }
                    else
                    {
                        $result = $this->db->delete('fs_state_comp_income', array('id' => $retrieve_sci_data[0]['id']));
                    }
                }
                else
                {
                    // insert data
                    if(!empty($data))
                    {
                        $result = $this->db->insert('fs_state_comp_income', $data);
                    }
                }
            }
        }
        
        return $result;
    }

    public function insert_fs_state_cash_flows($data)
    {
        $return_ids = [];

        if(!empty($data['id']) && $data['id'] != 0)
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('fs_state_cash_flows', $data);

            array_push($return_ids, $data['id']);
        }
        else
        {
            $result = $this->db->insert('fs_state_cash_flows', $data);

            array_push($return_ids, $this->db->insert_id());
        }

        if(!$result)
        {
            return ["status" => 0, "return message" => "Error on inserting/updating data", "return_ids" => array()];
        }
        

        return ["status" => 1, "return message" => "Successfully saved data!", "return_ids" => $return_ids];
    }

    public function delete_state_cash_flows($arr_delete_row)
    {
        if(count($arr_delete_row) > 0)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->delete('fs_state_cash_flows');
        }
    }

    public function delete_state_cash_flows_category($category_id, $fs_company_info_id)
    {
        $this->db->where('category_id', $category_id);
        $this->db->where('fs_company_info_id', $fs_company_info_id);
        $this->db->delete('fs_state_cash_flows');
    }

    public function delete_state_cash_flows_fixed_category($category_id, $fs_company_info_id){
  
        $this->db->where('category_id', $category_id);
        $this->db->where('fs_company_info_id', $fs_company_info_id);
        $this->db->delete('fs_state_cash_flows_fixed');

    }

    public function delete_state_changes_in_equity($arr_delete_row)
    {
        if(count($arr_delete_row) > 0)
        {
            $arr_delete_row = explode(',',$arr_delete_row);
            $this->db->where_in('id', $arr_delete_row);
            $this->db->delete('fs_state_changes_in_equity');
        }
    }

    public function delete_state_changes_in_equity_with_prior($fs_company_info_id, $group_type)
    {   
        $query_for_prior = " AND current_prior='prior'";

        if($group_type == '1')
        {
            $query_for_prior = " AND (current_prior='prior'";
            $query_for_group = " OR group_company = 'group')"; 
        }

        // delete from table "fs_state_changes_in_equity"
        $this->db->query("DELETE FROM fs_state_changes_in_equity WHERE fs_company_info_id = " . $fs_company_info_id . $query_for_prior . $query_for_group);

        // delete from table "fs_state_changes_in_equity_footer"
        $this->db->query("DELETE FROM fs_state_changes_in_equity_footer WHERE fs_company_info_id = " . $fs_company_info_id . $query_for_prior . $query_for_group);
    }

    public function create_state_cash_flows_w_ly_val($fs_company_info_id)
    {
        $ly_fs_company_info_id = $this->fs_model->get_fs_company_info_last_year($fs_company_info_id);

        $q = $this->db->query("SELECT cf.* , fnd.note_num_displayed, fnd.fs_note_templates_master_id, setup_cf.parent_id, setup_cf.category_id, setup_cf.description, setup_cf.main_value AS `value_company_ye`, setup_cf.is_checked
                                FROM fs_state_cash_flows cf
                                LEFT JOIN fs_setup_state_cash_flows setup_cf ON setup_cf.id = cf.fs_setup_state_cash_flows_id
                                LEFT JOIN fs_note_details fnd ON cf.fs_note_details_id = fnd.id
                                WHERE cf.fs_company_info_id = " . $ly_fs_company_info_id . " ORDER BY order_by");
        $q = $q->result_array();
        $q = $this->fs_notes_model->get_update_note_num_displayed($q, $ly_fs_company_info_id);

        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_note_details_id']))
            {
                $q[$key]['note_num_displayed'] = NULL;
            }
        }

        // create data for fs_setup_state_cash_flows
        $temp_fs_setup_cash_flows = [];

        foreach ($q as $key => $value)
        { 
            array_push($temp_fs_setup_cash_flows,
                array(
                    'id'    => '',
                    'info'  => array(
                                'fs_company_info_id' => $fs_company_info_id,
                                'is_checked'         => $value['is_checked'],
                                'parent_id'          => $value['parent_id'],
                                'category_id'        => $value['category_id'],
                                'description'        => $value['description'],
                                'order_by'           => $key+1
                            )
                )
            );
        }
        $return_ids = $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows', $temp_fs_setup_cash_flows);

        // create data for fs_state_cash_flows with last year value
        $temp_fs_state_cash_flows = [];

        foreach ($q as $key => $value) 
        {
            array_push($temp_fs_state_cash_flows,
                array(
                    'id'    => '',
                    'info'  => array(
                                    'fs_company_info_id'            => $fs_company_info_id,
                                    'fs_setup_state_cash_flows_id'  => $return_ids[$key],
                                    'value_company_lye_end'         => $value['value_company_ye'],
                                    'order_by' => $key + 1
                                )
                )
            );
        }
        $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows', $temp_fs_state_cash_flows);

        // for hide/show section part
        $ly_fs_state_cash_flows_hide_section = $this->db->query("SELECT * FROM fs_state_cash_flows_hide_section WHERE fs_company_info_id=" . $ly_fs_company_info_id);
        $ly_fs_state_cash_flows_hide_section = $ly_fs_state_cash_flows_hide_section->result_array();

        $fs_cfs_hide_section = [];

        foreach ($ly_fs_state_cash_flows_hide_section as $key => $value) 
        {
            array_push($fs_cfs_hide_section,
                array(
                    'id'    => '',
                    'info'  => array(
                                    'fs_company_info_id' => $fs_company_info_id,
                                    'section_id'         => $value['section_id'],
                                    'status'             => $value['status']
                                ) 
                )
            );
        }
        $this->fs_notes_model->insert_update_tbl_data_2('fs_state_cash_flows_hide_section', $fs_cfs_hide_section);
    }

    public function get_fs_state_cash_flows($fs_company_info_id)
    { 
        $q = $this->db->query("SELECT cf.* , fnd.note_num_displayed, fnd.fs_note_templates_master_id, setup_cf.parent_id, setup_cf.category_id, setup_cf.description, setup_cf.main_value AS `value_company_ye`, setup_cf.is_checked
                                FROM fs_state_cash_flows cf
                                LEFT JOIN fs_setup_state_cash_flows setup_cf ON setup_cf.id = cf.fs_setup_state_cash_flows_id
                                LEFT JOIN fs_note_details fnd ON cf.fs_note_details_id = fnd.id
                                WHERE cf.fs_company_info_id = " . $fs_company_info_id . " ORDER BY order_by DESC");
        $q = $q->result_array();
        $q = $this->fs_notes_model->get_update_note_num_displayed($q, $fs_company_info_id);

        foreach ($q as $key => $value) 
        {
            if(empty($value['fs_note_details_id']))
            {
                $q[$key]['note_num_displayed'] = NULL;
            }
        }

        return $q;
    }

    public function get_fs_state_cash_flows_fixed($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_state_cash_flows_fixed WHERE fs_company_info_id = " . $fs_company_info_id );

        return $q->result_array();
    }
    

    public function get_fs_state_cash_flows_section($fs_company_info_id, $section_id)
    {
        $q = $this->db->query("SELECT * FROM fs_state_cash_flows_hide_section WHERE fs_company_info_id = " . $fs_company_info_id." AND section_id = ". $section_id);
        $q = $q->result_array();

        if(count($q) > 0)
        {
            return $q;
        }
        else
        {
            if($section_id == 1)
            {
                // operating activities
                array_push($q, 
                    array(
                        'id'                 => '',
                        'fs_company_info_id' => $fs_company_info_id,
                        'section_id'         => $section_id,
                        'status'             => 1
                    )
                );
            }
            elseif($section_id == 2 || $section_id == 3)
            {
                // investing activities
                array_push($q, 
                    array(
                        'id'                 => '',
                        'fs_company_info_id' => $fs_company_info_id,
                        'section_id'         => $section_id,
                        'status'             => 0
                    )
                );
            }

            return $q;
        }
    }

    // get data for setup financial of cash flows
    public function get_fs_setup_state_cash_flows_header($fs_company_info_id)
    {
        $q = $this->db->query("SELECT * FROM fs_setup_state_cash_flows_header WHERE fs_company_info_id = " . $fs_company_info_id);
        $q = $q->result_array();

        $data = array(
                        'header_id' => '',
                        'items'     => [],
                        'desc'      => []
                    );
        $temp_data = [];

        // get data from statement of financial position
        $fp_data = $this->get_fps_data_for_cfs($fs_company_info_id);

        foreach ($fp_data as $key => $value) 
        {
            $value = $value[0];

            array_push($temp_data, 
                array(
                    'fcaro_id'      => $value['id'],
                    'description'   => $value['description'],
                    'total_c'       => $value['total_c'],
                    'total_c_lye'   => $value['total_c_lye'],
                    'total_g'       => $value['total_g'],
                    'total_g_lye'   => $value['total_g_lye'],
                    'order_by'      => $key
                )
            );
            array_push($data['desc'], $value['description']);
        }

        if(count($q) > 0)
        {   
            $db_header_items = explode(',',$q[0]['header_titles']);
            $update_needed = false;

            if(count($db_header_items) == count($data['desc'])) // check if data from db length = new updated data length
            {
                foreach (array_column($temp_data, 'fcaro_id') as $key => $value) 
                {
                    if($value != $db_header_items[$key])
                    {
                        $update_needed = true;
                    }
                }
            }
            else
            {
                $update_needed = true;
            }

            if($update_needed) // TO UPDATE DATA FOR SETUP CASH FLOWS (ADD, DELETE, UPDATE)
            {
                $deleted_fcaro_ids  = [];
                $added_fcaro_ids    = [];

                $db_body_items = $this->db->query("SELECT * FROM fs_setup_state_cash_flows WHERE fs_company_info_id=" . $fs_company_info_id);
                $db_body_items = $db_body_items->result_array();

                foreach ($db_body_items as $key => $value) 
                {
                    $row_items = $value['row_item'];
                    $row_items = explode(',',$row_items);
                    $db_body_items[$key]['row_item'] = $row_items;
                }

                $deleted_fcaro_ids  = array_diff($db_header_items, array_column($temp_data, 'fcaro_id')); // capture extra id in $db_header_items (deleted id)
                $added_fcaro_ids    = array_diff(array_column($temp_data, 'fcaro_id'), $db_header_items); // capture added id in $temp_data (add new id)

                $body_data = '';

                // delete data
                foreach ($deleted_fcaro_ids as $key => $value) 
                {
                    $matched_key_del = array_search($value, $db_header_items); // get index from db data

                    if($matched_key_del || (string)$matched_key_del == '0')
                    {
                        unset($db_header_items[$matched_key_del]);  // remove item from header array

                        // remove item from body array
                        foreach ($db_body_items as $key2 => $value2) 
                        {
                            unset($value2['row_item'][$matched_key_del]);
                            $db_body_items[$key2]['row_item'] = array_values($value2['row_item']);
                        }
                    }
                }

                // add data
                foreach ($added_fcaro_ids as $key => $value) 
                {
                    $matched_key_add = array_search($value, array_column($temp_data, 'fcaro_id')); // get index from new array

                    array_splice($db_header_items, $matched_key_add, 0, array(array_column($temp_data, 'fcaro_id')[$matched_key_add]));

                    if($matched_key_add || (string)$matched_key_add == '0')
                    {
                        // remove item from body array
                        foreach ($db_body_items as $key2 => $value2) 
                        {
                            array_splice($value2['row_item'], $matched_key_add, 0, array(''));
                            $db_body_items[$key2]['row_item'] = array_values($value2['row_item']);
                        }
                    }
                }

                // update data (rearrange)
                $ori_db_body_items = $db_body_items; // Avoid array get replaced and unable to retrieve previously saved data.

                foreach (array_column($temp_data, 'fcaro_id') as $key => $value) 
                {
                    if($value != $db_header_items[$key])
                    {
                        $matched_key_update = array_search($value, $db_header_items);

                        if($matched_key_update || (string)$matched_key_update == '0')
                        {
                            foreach ($db_body_items as $key2 => $value2) 
                            {
                                $value2['row_item'][$key] = $ori_db_body_items[$key2]['row_item'][$matched_key_update]; // replace values with previously saved data under this column
                                $db_body_items[$key2]['row_item'] = $value2['row_item'];
                            }
                        }
                    }
                }

                $body_data_2_db = [];

                // update db
                $header_data_2_db = array(
                                    'id' => $q[0]['id'],
                                    'info' => array(
                                                'header_titles' => implode(",",array_column($temp_data, 'fcaro_id'))
                                            )
                                );
                $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows_header', array($header_data_2_db));

                // update db, convert row_item column from array to string
                foreach ($db_body_items as $key => $value) 
                {
                    $db_body_items[$key]['row_item'] = implode(",",$value['row_item']);

                    array_push($body_data_2_db,
                        array(
                            'id' => $value['id'],
                            'info' => array(
                                        'row_item' => implode(",",$value['row_item'])
                                    )
                        )
                    );
                }
                $this->fs_notes_model->insert_update_tbl_data_2('fs_setup_state_cash_flows', $body_data_2_db);
            }

            $data['header_id'] = $q[0]['id'];
            $data['items']     = $temp_data;
        }
        else    // get data from financial position (default)
        {
            $data['items'] = $temp_data;
        }

        return $data;
    }

    public function get_fs_setup_state_cash_flows($fs_company_info_id)
    {
        $cfs_body_data = array(
                            'pl_be4_tax' => [],
                            'adjustment' => [],
                            'changes'    => [],
                            'net_cash'   => [],
                            'investing'  => [],
                            'financing'  => []
                        );

        // get header data
        $cfs_header = $this->get_fs_setup_state_cash_flows_header($fs_company_info_id);

        $template_row_item = '';

        if(isset($cfs_header['desc']) && count($cfs_header['desc']) > 0)
        {
            $header_titles = $cfs_header['desc'];

            $template_row_item = str_repeat(',', count($header_titles)-1);
            $template_row_item = explode(',',$template_row_item);
        }

        // get body data
        $cfs_body = $this->db->query("SELECT * FROM fs_setup_state_cash_flows WHERE fs_company_info_id=" . $fs_company_info_id . ' ORDER BY order_by');
        $cfs_body = $cfs_body->result_array();

        if(count($cfs_body) == 0)
        {
            // check if has last year record, create new rows if yes
            $this->fs_statements_model->create_state_cash_flows_w_ly_val($fs_company_info_id);

            // reload data
            $cfs_body = $this->db->query("SELECT * FROM fs_setup_state_cash_flows WHERE fs_company_info_id=" . $fs_company_info_id . ' ORDER BY order_by');
            $cfs_body = $cfs_body->result_array();
        }

        foreach ($cfs_body as $key => $value)
        {
            $value['is_adjustment_values'] = explode(',',$value['is_adjustment_values']);

            if(!empty($value['row_item']))
            {
                $value['row_item'] = explode(',',$value['row_item']);
            }
            else
            {
                $value['row_item'] = $template_row_item;
            }

            if($value['parent_id'] == '#pl_be4_tax') // profit before tax
            {
                array_push($cfs_body_data['pl_be4_tax'], $value);
            }
            elseif($value['parent_id'] == '#adjustment') // Adjustment
            {
                array_push($cfs_body_data['adjustment'], $value);
            }
            elseif($value['parent_id'] == '#changes') // Changes
            {
                array_push($cfs_body_data['changes'], $value);
            }
            elseif($value['parent_id'] == '#net_cash') // Net Cash
            {
                array_push($cfs_body_data['net_cash'], $value);
            }
            elseif($value['parent_id'] == '#investing') // Investing
            {
                array_push($cfs_body_data['investing'], $value);
            }
            elseif($value['parent_id'] == '#financing') // Financing
            {
                array_push($cfs_body_data['financing'], $value);
            }
        }

        // set default values / update values
        // Profit before tax
        if(count($cfs_body_data['pl_be4_tax']) > 0) // if has record, we check if 'profit before tax' has any changes. Update if yes.
        {
            $temp_pl_be4_tax = [];

            // for profit before tax
            $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
            $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

            if(count($pl_be4_tax_values_from_sci) > 0)
            {
                $cfs_body_data['pl_be4_tax'][0]['description'] = $pl_be4_tax_values_from_sci[0]['description'];
                $cfs_body_data['pl_be4_tax'][0]['main_value'] = $pl_be4_tax_values_from_sci[0]['value_company_ye'];

                // update #pl_be4_tax section
                $temp_db_data = array(
                                    'id' => $cfs_body_data['pl_be4_tax'][0]['id'],
                                    'info' => array(
                                                    'description' => $cfs_body_data['pl_be4_tax'][0]['description'],
                                                    'main_value'  => $cfs_body_data['pl_be4_tax'][0]['main_value']
                                                )
                                );

                $this->fs_notes_model->update_tbl_data('fs_setup_state_cash_flows', array($temp_db_data));
            }
        }
        else
        {
            $temp_pl_be4_tax = [];

            // for profit before tax
            $pl_be4_tax_values_from_sci = $this->db->query("SELECT * FROM fs_state_comp_income WHERE fs_company_info_id=" . $fs_company_info_id . " AND fs_list_state_comp_income_section_id=3");
            $pl_be4_tax_values_from_sci = $pl_be4_tax_values_from_sci->result_array(); 

            if(count($pl_be4_tax_values_from_sci) > 0) // if has profit before tax record
            {
                array_push($temp_pl_be4_tax, 
                    array(
                        'id'          => '',
                        'is_checked'  => 1,
                        'parent_id'   => '#pl_be4_tax',
                        'category_id' => 0,
                        'description' => $pl_be4_tax_values_from_sci[0]['description'],
                        'main_value'  => $pl_be4_tax_values_from_sci[0]['value_company_ye'],
                        'row_item'    => $template_row_item
                    )
                );
            }
            else // no record
            {
                array_push($temp_pl_be4_tax, 
                    array(
                        'id'          => '',
                        'is_checked'  => 1,
                        'parent_id'   => '#pl_be4_tax',
                        'category_id' => 0,
                        'description' => 'N/A',
                        'main_value'  => '',
                        'row_item'    => $template_row_item
                    )
                );
            }

            $cfs_body_data['pl_be4_tax'] = $temp_pl_be4_tax;
        }

        // changes in working capital part
        if(count($cfs_body_data['changes']) == 0)
        {
            $temp_changes = [];

            // row 1
            array_push($temp_changes, 
                array(
                    'id'          => '',
                    'is_checked'  => 1,
                    'parent_id'   => '#changes',
                    'category_id' => 1,
                    'description' => 'Changes in inventories',
                    'main_value'  => '',
                    'row_item'    => $template_row_item
                )
            );

            // row 2
            array_push($temp_changes, 
                array(
                    'id'          => '',
                    'is_checked'  => 1,
                    'parent_id'   => '#changes',
                    'category_id' => 1,
                    'description' => 'Changes in receivables',
                    'main_value'  => '',
                    'row_item'    => $template_row_item
                )
            );

            // row 3
            array_push($temp_changes, 
                array(
                    'id'          => '',
                    'is_checked'  => 1,
                    'parent_id'   => '#changes',
                    'category_id' => 1,
                    'description' => 'Changes in payables',
                    'main_value'  => '',
                    'row_item'    => $template_row_item
                )
            );

            $cfs_body_data['changes'] = $temp_changes;
        }
        
        // Net cash from operations part
        if(count($cfs_body_data['net_cash']) == 0)
        {
            $temp_net_cash = [];

            // row 1
            array_push($temp_net_cash, 
                array(
                    'id'          => '',
                    'is_checked'  => 1,
                    'parent_id'   => '#net_cash',
                    'category_id' => 1,
                    'description' => 'Tax paid',
                    'main_value'  => '',
                    'row_item'    => $template_row_item
                )
            );

            // row 2
            array_push($temp_net_cash, 
                array(
                    'id'          => '',
                    'is_checked'  => 1,
                    'parent_id'   => '#net_cash',
                    'category_id' => 1,
                    'description' => 'Tax refund',
                    'main_value'  => '',
                    'row_item'    => $template_row_item
                )
            );

            $cfs_body_data['net_cash'] = $temp_net_cash;
        }

        return $cfs_body_data;
    }

    public function get_fps_data_for_cfs($fs_company_info_id) // get data from "Statement of financial position" for "Statement of Cash flows" uses.
    {
        $fs_ntfs_list = $this->fs_notes_model->get_fs_ntfs_json();

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
        }

        $data = $this->fs_account_category_model->operate_account_value($data, array('Q103'), array(array('operator' => '+', 'insert_values_arr' => $total_for_revenue_reserve)));
        /* ------------------ END OF Sum up value to get "revenue reserve (Q103)" ------------------ */

        /* rearrange data, take needed data only */
        $cfs_data = [];

        foreach ($data as $level_1_key => $level_1) 
        {
            $hide_main_title = false;
            $level_1_description = "";

            $fs_ntfs_list_key = array_search($level_1['parent_array'][0]['account_code'], array_column($description_reference_id_list, "account_code")); // get key

            if(!empty($fs_ntfs_list_key) || (string)$fs_ntfs_list_key == 0)
            {
                $level_1_description = $description_reference_id_list[$fs_ntfs_list_key]['description']; // get description from fs_ntfs_list json from document name "Statement of financial position"
            }

            // move equity's level 1 to level 2 template
            if($level_1_description == "Equity")
            {
                $level_1['child_array'] = array($level_1);
            }

            if(!empty($level_1['parent_array']))
            {
                foreach ($level_1['child_array'] as $level_2_key => $level_2) 
                {
                    /* GET 1 LINE ONLY IF NO CHILD UNDER LEVEL 2 */
                    if(count($level_2['child_array']) > 0)
                    {
                        if(!empty($level_2['parent_array']))
                        {
                            foreach ($level_2['child_array'] as $level_3_key => $level_3)
                            {
                                /* GET LEVEL 3 THAT HAS SUBCATEGORY */
                                if(!empty($level_3['parent_array']))
                                {
                                    array_push($cfs_data, $level_3['parent_array']);
                                }

                                /* GET LEVEL 3 WITHOUT SUBCATEGORY UNDER IT */
                                elseif($level_1_description == "Liabilities" || $level_1_description == "Assets")
                                {
                                    array_push($cfs_data, $level_3['child_array']);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $cfs_data;
    }

    public function get_all_adjusted_state_comp_income($fs_company_info_id)
    {
        $income_list            = [];
        $other_income_list      = [];
        $changes_in_inventories = [];
        $purchases              = [];
        $expense_list           = [];
        $pl_be4_tax             = [];
        $additional_list        = [];
        $pl_after_tax           = [];
        $other_list             = [];

        // if fs_state_comp_income has the list, load the list else setup the values.
        $fs_state_comp_list = $this->fs_statements_model->get_fs_state_comp_income($fs_company_info_id);

        /* setup values for statement of comprehensive income */ 
        $fs_statement_list = $this->fs_statements_model->get_fs_statement();    // get list of code from json 

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
                    $temp = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($income_fca_id_value));

                    // $temp[0]['parent_array'][0]['group_end_this_ye_value']  = $temp[0]['parent_array'][0]['group_end_this_ye_value'] * (-1);
                    // $temp[0]['parent_array'][0]['group_end_prev_ye_value']  = $temp[0]['parent_array'][0]['group_end_prev_ye_value'] * (-1);

                    $temp[0]['parent_array'][0]['total_c']      = $temp[0]['parent_array'][0]['total_c']     * (-1);
                    $temp[0]['parent_array'][0]['total_c_lye']  = $temp[0]['parent_array'][0]['total_c_lye'] * (-1);

                    array_push($income_data, $temp);
                }

                $income_list = array($income_data);
                // print_r($income_list);
            }
            elseif($sci_json_value->list_name == "other_income_list")
            {
                $other_income_fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);
                $other_income_list = [];

                foreach ($other_income_fca_id as $other_income_fca_id_key => $other_income_fca_id_value) 
                {
                    $temp = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($other_income_fca_id_value));

                    // $temp[0]['parent_array'][0]['group_end_this_ye_value']  = $temp[0]['parent_array'][0]['group_end_this_ye_value'] * (-1);
                    // $temp[0]['parent_array'][0]['group_end_prev_ye_value']  = $temp[0]['parent_array'][0]['group_end_prev_ye_value'] * (-1);

                    $temp[0]['parent_array'][0]['total_c']      = $temp[0]['parent_array'][0]['total_c']     * (-1);
                    $temp[0]['parent_array'][0]['total_c_lye']  = $temp[0]['parent_array'][0]['total_c_lye'] * (-1);

                    array_push($other_income_list, $temp);
                }
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
                    $temp = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value));

                    // $temp[0]['parent_array'][0]['group_end_this_ye_value']  = $temp[0]['parent_array'][0]['group_end_this_ye_value'] * (-1);
                    // $temp[0]['parent_array'][0]['group_end_prev_ye_value']  = $temp[0]['parent_array'][0]['group_end_prev_ye_value'] * (-1);

                    $temp[0]['parent_array'][0]['total_c']      = $temp[0]['parent_array'][0]['total_c']     * (-1);
                    $temp[0]['parent_array'][0]['total_c_lye']  = $temp[0]['parent_array'][0]['total_c_lye'] * (-1);

                    array_push($data, $temp);
                }

                $expense_list = $data;

                // print_r($expense_list);
            }
            elseif($sci_json_value->list_name == "additional_list")
            {
                // $additional_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);    // TAXATION
                $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                foreach ($fca_id as $fca_id_key => $fca_id_value) 
                {
                    array_push($data, $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value)));
                }

                $additional_list = array($this->fs_account_category_model->change_sign_in_account($data[0]));
            }
            elseif($sci_json_value->list_name == "soa_pl_list") // Share of associates profit or loss
            {
                // $soa_pl_list = $this->fs_account_category_model->get_account_with_sub_round_off($fs_company_info_id, $sci_json_value->account_category_code);    // TAXATION
                $temp_data = [];

                $fca_id = $this->fs_notes_model->get_fca_id($fs_company_info_id, $sci_json_value->account_category_code);

                foreach ($fca_id as $fca_id_key => $fca_id_value) 
                {
                    $temp_data = $this->fs_account_category_model->get_account_with_sub_round_off_ids($fs_company_info_id, array($fca_id_value));

                    if(count($temp_data[0]['child_array']) > 0)
                    {
                        // $temp_data[0]['parent_array'][0]['group_end_this_ye_value']  = $temp_data[0]['parent_array'][0]['group_end_this_ye_value'] * (-1);
                        // $temp_data[0]['parent_array'][0]['group_end_prev_ye_value']  = $temp_data[0]['parent_array'][0]['group_end_prev_ye_value'] * (-1);

                        // $temp_data[0]['parent_array'][0]['total_c']      = $temp_data[0]['parent_array'][0]['total_c']     * (-1);
                        // $temp_data[0]['parent_array'][0]['total_c_lye']  = $temp_data[0]['parent_array'][0]['total_c_lye'] * (-1);

                        $temp_data[0]['parent_array'][0]['group_end_this_ye_value']  = $temp_data[0]['parent_array'][0]['group_end_this_ye_value'];
                        $temp_data[0]['parent_array'][0]['group_end_prev_ye_value']  = $temp_data[0]['parent_array'][0]['group_end_prev_ye_value'];

                        $temp_data[0]['parent_array'][0]['total_c']      = $temp_data[0]['parent_array'][0]['total_c'];
                        $temp_data[0]['parent_array'][0]['total_c_lye']  = $temp_data[0]['parent_array'][0]['total_c_lye'];

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

                foreach ($fs_list_state_comp_income_section_id_list as $key => $value) 
                {
                    array_push($id_list, $value);
                }
                /*----- END OF rearrange array -----*/

                $keys = array_keys($id_list, 5);    // get all keys matched with 5 in fs_list_state_comp_income_section_id

                foreach ($keys as $key => $value) 
                {
                    array_push($other_list, $fs_state_comp_list[$value]);
                }
            } 
        }

        if(count($additional_list[0][0]['child_array']) == 0 && count($soa_pl_list[0][0]['child_array']) == 0)   // if no tax, change the title
        {
            if(count($pl_be4_tax) > 0)
            {
                $pl_be4_tax['description'] = "Profit for the year";
                $pl_after_tax = [];

                if(count($other_list) == 0) // the user input section eg. Other comprehensive income; Net of tax
                {
                    $pl_be4_tax['description'] .= ", Total comprehensive income for the year";
                }
            }
        }
        else
        {
            if(count($other_list) == 0) // the user input section eg. Other comprehensive income; Net of tax
            {
                $pl_after_tax['description'] .= ", Total comprehensive income for the year";
            }
        }


        // print_r(array($pl_be4_tax, $additional_list, $pl_after_tax, $additional_list[0][0]));

        $data = array(
                    'income_list'                 => $income_list,
                    'other_income_list'           => $other_income_list,
                    'changes_in_inventories'      => $changes_in_inventories,
                    'purchases_and_related_costs' => $purchases_and_related_costs,
                    'expense_list'                => $expense_list,
                    'pl_be4_tax'                  => $pl_be4_tax,
                    'additional_list'             => $additional_list,
                    'pl_after_tax'                => $pl_after_tax,
                    'soa_pl_list'                 => $soa_pl_list,
                    'other_list'                  => $other_list
                );

        return $data;
    }

    public function save_fs_state_cash_flows_fixed($data)
    {
        $q = $this->db->query("SELECT * FROM fs_state_cash_flows_fixed WHERE fs_company_info_id = " . $data['fs_company_info_id']." AND fixed_tag = '".$data['fixed_tag']."'");
        $q = $q->result_array();

        $result = false;

        if(count($q) > 0)
        {
            $result = $this->db->update("fs_state_cash_flows_fixed", $data, array("id" => $q[0]['id']));
        }
        else
        {
            $result =  $this->db->insert("fs_state_cash_flows_fixed",$data);
        }

        return $result;
    }

    public function save_fs_state_cash_flows_hide_section($data)
    {
        $q = $this->db->query("SELECT * FROM fs_state_cash_flows_hide_section WHERE fs_company_info_id = " . $data['fs_company_info_id']." AND section_id = ".$data['section_id']);
        $q = $q->result_array();

        $result = false;

        if(count($q) > 0)
        {
            $result = $this->db->update("fs_state_cash_flows_hide_section", $data, array("id" => $q[0]['id']));
        }
        else
        {
            $result =  $this->db->insert("fs_state_cash_flows_hide_section",$data);
        }

        return $result;
    }

    public function insert_fs_state_changes_in_equity($data)
    {
        $return_ids = [];

        if(!empty($data['id']) && $data['id'] != 0)
        {
            $this->db->where('id', $data['id']);
            $result = $this->db->update('fs_state_changes_in_equity', $data);

            array_push($return_ids, $data['id']);
        }
        else
        {
            $result = $this->db->insert('fs_state_changes_in_equity', $data);

            array_push($return_ids, $this->db->insert_id());
        }

        if(!$result)
        {
            return ["status" => 0, "return message" => "Error on inserting/updating data", "return_ids" => array()];
        }
        

        return ["status" => 1, "return message" => "Successfully saved data!", "return_ids" => $return_ids];
    }

    public function insert_changes_in_equity_footer($data)
    {
        $q = $this->db->query("SELECT * FROM fs_state_changes_in_equity_footer WHERE fs_company_info_id = " . $data['fs_company_info_id']." AND group_company = '".$data['group_company']."' AND current_prior = '".$data['current_prior']."'");
        $q = $q->result_array();

        $result = false;

        if(count($q) > 0)
        {
            $result = $this->db->update("fs_state_changes_in_equity_footer", $data, array("id" => $q[0]['id']));
        }
        else
        {
            $result =  $this->db->insert("fs_state_changes_in_equity_footer",$data);
        }

        return $result;
    }

    public function insert_changes_in_equity_header_titles($data){
        $q = $this->db->query("SELECT * FROM fs_state_changes_in_equity_header WHERE fs_company_info_id = " . $data['fs_company_info_id']." AND group_company = '".$data['group_company']."'");
        $q = $q->result_array();

        $result = false;

        if(count($q) > 0)
        {
            $result = $this->db->update("fs_state_changes_in_equity_header", $data, array("id" => $q[0]['id']));
        }
        else
        {
            $result =  $this->db->insert("fs_state_changes_in_equity_header",$data);
        }

        return $result;
    }

    public function get_fs_state_changes_in_equity($fs_company_info_id, $current_prior, $group_company)
    {
        $q = $this->db->query("SELECT * 
                                FROM fs_state_changes_in_equity
                                WHERE fs_company_info_id = " . $fs_company_info_id. " AND current_prior ='" .$current_prior. "' AND group_company ='" .$group_company."' ORDER BY row_order" );

        return $q->result_array();
    }

    public function get_fs_state_changes_in_equity_header($fs_company_info_id, $group_company)
    {
        $q = $this->db->query("SELECT * 
                                FROM fs_state_changes_in_equity_header
                                WHERE fs_company_info_id = " . $fs_company_info_id. " AND group_company ='" .$group_company. "'" );

        return $q->result_array();
    }

    public function get_fs_state_changes_in_equity_footer($fs_company_info_id, $group_company)
    {
        $q = $this->db->query("SELECT * 
                                FROM fs_state_changes_in_equity_footer
                                WHERE fs_company_info_id = " . $fs_company_info_id. " AND group_company ='" .$group_company. "'" );

        return $q->result_array();
    }
    // public function get_fs_state_cash_flows_fixed($fs_company_info_id)
    // {
    //     $q = $this->db->query("SELECT * FROM fs_state_cash_flows_fixed WHERE fs_company_info_id = " . $fs_company_info_id );

    //     return $q->result_array();
    // }

    public function delete_row_from_table($table, $ids)
    {
        $result = false;
        
        if(!empty($ids) || count($ids) > 0)
        {
            $this->db->where_in('id', $ids);
            $result = $this->db->delete($table);
        }

        return $result;
    }

}
