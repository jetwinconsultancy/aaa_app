<?php defined('BASEPATH') OR exit('No direct script access allowed');

require $_SERVER['DOCUMENT_ROOT'] . '/' . explode('/', $_SERVER['REQUEST_URI'])[1] . '/composer_plugin/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// require $_SERVER['DOCUMENT_ROOT'] . '/financial_statement/assets/vendor/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
// require $_SERVER['DOCUMENT_ROOT'] . '/financial_statement/assets/vendor/PhpSpreadsheet/src/PhpSpreadsheet/Exception.php';


class Fs_account_category extends MY_Controller
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
        $this->load->model('fs_account_category_model');
        $this->load->model('fs_replace_content_model');
        $this->load->model('fs_statements_model');
        $this->load->model('fs_notes_model');
        $this->load->model('fs_generate_doc_word_model');
    }

    // public function get_default_main_parent_list()
    // {
    //     $main_account_default_list = $this->fs_account_category_model->get_default_main_sub_account_list('main');

    //     echo json_encode($main_account_default_list);
    // }

    public function get_categorized_data()
    {
        $form_data = $this->input->post();
        $fs_company_info_id        = $form_data['fs_company_info_id'];
        $fs_categorized_account_id = $form_data['fs_categorized_account_id'];

        $fs_categorized_data = $this->fs_account_category_model->get_categorized_fcaro_data($fs_company_info_id, $fs_categorized_account_id);

        echo json_encode($fs_categorized_data);
    }

    // public function index()
    // {
    //     $bc   = array(array('link' => '#', 'page' => lang('Documents')));
    //     $meta = array('page_title' => lang('Documents'), 'bc' => $bc, 'page_name' => 'Documents');

    //     $this->data['fs_report_list'] = $this->fs_model->get_fs_report_list();

    //     // echo json_encode($this->data['fs_report_list']);

    //     $this->page_construct('account_category.php', $meta, $this->data);
    // }

    // public function get_default_account_list()
    // {
    //     $form_data = $this->input->post();
    //     // echo json_encode($form_data);

    //     $default_account_list = $this->fs_account_category_model->get_default_account_list();

    //     // echo json_encode($default_account_list);

    //     foreach ($default_account_list as $key => $value) {
    //         if($form_data['account_code'] == $value['account_code'])
    //         {
    //             echo json_encode($value);
    //         }
    //     }
    //     // echo $this->fs_account_category_model->get_default_account_list();
    // }

    public function layout_tree_structure()
    {
        $this->data['main_account_code_list'] = $this->fs_account_category_model->get_default_main_sub_account_list('default');

        $interface = $this->load->view('/views/financial_statement/template/fs_account_category/layout_tree_structure.php', $this->data);
    }

    public function partial_main_account_list()
    {
        $form_data = $this->input->post();
        $current_categorized_tree = $form_data['current_categorized_tree'];

        // print_r($current_categorized_tree);

        $main_account_list = $this->fs_account_category_model->get_default_main_sub_account_list('main');

        // print_r($main_account_list);

        $main_account_list_defined = [];

        foreach($main_account_list as $key => $value)
        {
            $temp = [];
            $is_used = false;

            foreach($current_categorized_tree as $key => $value_1)
            {
                // print_r($value_1);
                if($value_1['data']['fs_default_acc_category_id'] == $value['id'])
                {
                    $is_used = true;
                }
            }

            array_push($main_account_list_defined, array(
                'fs_default_acc_category_id' => $value['id'],
                'account_code' => $value['account_code'],
                'description'  => $value['description'],
                'is_used'      => $is_used
            ));
        }

        $this->data['main_account_list'] = $main_account_list_defined;

        $interface = $this->load->view('/views/financial_statement/template/fs_account_category/partial_main_account_list.php', $this->data);
    }

    public function partial_sub_account_list()
    {
        $form_data = $this->input->post();
        $current_categorized_tree = $form_data['current_categorized_tree'];

        $sub_account_list = $this->fs_account_category_model->get_default_main_sub_account_list('sub'); 
        $exclude_account_code = $this->fs_account_category_model->get_fs_account_category_json(); 
        $exclude_account_code = $exclude_account_code['partial_sub_account_list'][0]['exclude_account'];    // exclude the account list from sub account list

        $sub_account_list_defined = [];

        foreach($sub_account_list as $key => $value)
        {
            $temp = [];
            $is_used = false;

            if(!in_array($value['account_code'], $exclude_account_code))
            {
                foreach($current_categorized_tree as $key => $value_1)
                {
                    if($value_1['data']['fs_default_acc_category_id'] == $value['id'])
                    {
                        $is_used = true;
                    }
                }

                array_push($sub_account_list_defined, array(
                    'fs_default_acc_category_id' => $value['id'],
                    'account_code' => $value['account_code'],
                    'description'  => $value['description'],
                    'is_used'      => $is_used
                ));
            }
        }

        $this->data['sub_account_list'] = $sub_account_list_defined;

        $interface = $this->load->view('/views/financial_statement/template/fs_account_category/partial_sub_account_list.php', $this->data);
    }

    public function partial_edit_account_code_list()
    {
        $form_data = $this->input->post();
        $current_categorized_tree = $form_data['current_categorized_tree'];
        $selected_acc_code = $form_data['selected_acc_code'];

        $sub_account_list = $this->fs_account_category_model->get_default_main_sub_account_list('sub'); 
        // $exclude_account_code = $this->fs_account_category_model->get_fs_account_category_json(); 
        // $exclude_account_code = $exclude_account_code['partial_sub_account_list'][0]['exclude_account'];    // exclude the account list from sub account list

        $sub_account_list_defined = [];

        foreach($sub_account_list as $key => $value)
        {
            $temp = [];
            $is_used = false;

            // if(!in_array($value['account_code'], $exclude_account_code))
            // {
                foreach($current_categorized_tree as $key => $value_1)
                {
                    // print_r(array($value_1['text'], $value_1['data']['account_code'], $value_1['data']['fs_default_acc_category_id'], $value['id'], $value_1['data']['fs_default_acc_category_id'] == $value['id']));

                    if($value_1['data']['fs_default_acc_category_id'] == $value['id'])
                    {
                        $is_used = true;
                    }
                }

                array_push($sub_account_list_defined, array(
                    'fs_default_acc_category_id' => $value['id'],
                    'account_code' => $value['account_code'],
                    'description'  => $value['description'],
                    'is_used'      => $is_used
                ));
            // }
        }

        $this->data['sub_account_list'] = $sub_account_list_defined;
        $this->data['selected_acc_code'] = $selected_acc_code;

        $interface = $this->load->view('/views/financial_statement/template/fs_account_category/partial_edit_account_code_list.php', $this->data);
    }

    public function categoriedDefaultData($fs_company_info_id) 
    {
        $categorized_acc_list = $this->fs_account_category_model->get_categorizedData_or_default($fs_company_info_id);

        foreach ($categorized_acc_list as $key => $value) 
        {
            $categorized_acc_list[$key]['data']['value']                     = $value['data']['value'];
            $categorized_acc_list[$key]['data']['company_end_prev_ye_value'] = $value['data']['company_end_prev_ye_value'];
        }

        // print_r($categorized_acc_list);

        echo json_encode($categorized_acc_list);
    }

    public function uncategoriedData($fs_company_info_id)
    {
        $data = $this->fs_account_category_model->get_create_uncategorizedData($fs_company_info_id);

        $uncategoriedData_list = [];

        // print_r($data);

        foreach ($data as $key => $value) 
        {
            if(empty($value['company_end_prev_ye_value']))
            {
                $value['company_end_prev_ye_value'] = 0.00;
            }

            array_push($uncategoriedData_list, 
                array(
                    'id'     => $value['id'],
                    'parent' => "#",
                    'text'   => $value['description'],
                    'type'   => "Leaf",
                    'Order'  => 0,
                    'data'   => array(
                                    value => $value['value'],
                                    company_end_prev_ye_value => $value['company_end_prev_ye_value']
                                )
                )
            );
        }
        
        echo json_encode($uncategoriedData_list);
    }

    public function read_extract_excel()
    {
        $fs_company_info_id = $_POST['fs_company_info_id'];
        $current_year_tb    = $_POST['current_year_tb'];

        if($_FILES['excel_file']['name']!="")
        {
            $target_dir = "pdf/document/";

            $file = $_FILES['excel_file']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['excel_file']['tmp_name'];
            $path_filename_ext = $target_dir.$filename.".".$ext;

            // echo json_encode($_FILES['excel_file']);
            move_uploaded_file($temp_name,$path_filename_ext);
        }

        // $inputFileName = 'C:/wamp64/www/financial_statement/composer_plugin/vendor/phpoffice/phpspreadsheet/samples/Reading_workbook_data/sampleData/TrialBalance.xlsx';
        $inputFileName = $_SERVER['DOCUMENT_ROOT'] . '/' . explode('/', $_SERVER['REQUEST_URI'])[1] . '/pdf/document/' . $file;

        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        $worksheet  = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $colIndex   = $worksheet->getHighestDataColumn();

        $highestCol_index = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($colIndex);

        // print_r(array($worksheet->getCellByColumnAndRow(2, 1)->getValue()));
        $row_1_col_2_val = $worksheet->getCellByColumnAndRow(2, 1)->getValue();
        $row_1_col_3_val = $worksheet->getCellByColumnAndRow(3, 1)->getValue();

        // print_r(array(gettype($row_1_col_2_val), gettype($row_1_col_3_val)));

        $starting_row = 1;

        if(gettype($row_1_col_2_val) == 'string' || gettype($row_1_col_2_val) == 'string')
        {
            $starting_row = 2;
        }

        /* check if the extra columns have values, if no values preceed extract */
        $allow_extract = true;

        if($highestCol_index > 2)
        {
            for ($col = 1; $col <= $highestCol_index; ++$col) 
            {
                for ($row = 1; $row <= $highestRow; ++$row) 
                {
                    if(!empty($worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue()))
                    {
                        $allow_extract = false;
                    }
                }
            }
        }
        /* END OF check if the extra columns have values, if no values preceed extract */

        if($highestCol_index == 3 || $allow_extract)
        {
            // extract data from cells
            $rows = [];
            for ($row = $starting_row; $row <= $highestRow; ++$row) 
            {
                // if($row < 3)
                // {
                    $col = 1;
                    $cell = $worksheet->getCellByColumnAndRow($col, $row);

                    // // Skip empty cells
                    // while (in_array($cell->getValue(), [null, ''], true)) 
                    // {
                    //     $col++;
                    //     $cell = $worksheet->getCellByColumnAndRow($col, $row);
                    // }
                    $maxCol = 4;    // set maximum column no

                    for ( ; $col < $maxCol; ++$col) 
                    { 
                        $value = $worksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                        $rows[$row][$col] = $value;

                        // print_r($value);
                    }
                // }
            }

            // pass data to array for database saving later
            $trial_balance = [];
            $total_trial_balance = 0.00;

            $not_numberic_row_col = [];

            $total_row_2 = '';
            $total_row_3 = '';

            foreach($rows as $key=>$row)
            {
                $row_2 = empty($row[2])?0:$row[2];

                if($highestCol_index > 2)
                {
                    $row_3 = empty($row[3])?0:$row[3];
                }
                else
                {
                    $row_3 = 0;
                }

                if(!empty($row[1]))
                {
                    array_push($trial_balance, array(
                        'fs_company_info_id' => $fs_company_info_id,
                        'description'        => $row[1],
                        // 'value'              => $this->evaluate_math_from_string(str_replace("=", "", $row[2])),
                        'value'              => $this->evaluate_math_from_string(round($row_2,2) - round($row_3,2)),
                        'order_by'           => $key
                    ));

                    $total_row_2 += round($row_2,2);
                    $total_row_3 += round($row_3,2);
                }
            }

            $total_trial_balance = round($total_row_2) - round($total_row_3);

            if($total_trial_balance == 0.00 && count($trial_balance) > 0)
            {
                if($current_year_tb)    // trial balance for current year end
                {
                    $insert_result = $this->fs_account_category_model->insert_batch_trial_balance($trial_balance, $fs_company_info_id); 
                }
                else // trial balance for last year end
                {
                    $insert_result = $this->fs_account_category_model->insert_batch_LY_trial_balance($trial_balance, $fs_company_info_id);
                }

                echo json_encode(array('status' => true, 'message' => 'Trial Balance uploaded'));
            }
            elseif(!count($trial_balance) > 0)
            {
                echo json_encode(array('status' => false, 'message' => 'No account record is detected. Please make sure the Excel consists of account list or check uploaded excel format is same as required.'));
            }
            else
            {
                echo json_encode(array('status' => false, 'message' => 'Trial balance account is not balance! Please make sure the sum of values are equal to 0 in Excel.'));
            }
        }
        else
        {
            echo json_encode(array('status' => false, 'message' => 'Uploaded excel only accept maximum with 3 columns. Please remove other extra columns.'));
        }
    }

    /* SAVE DATA */
    public function save_categorized_uncategorized_account()
    {
        $form_data     = $this->input->post();

        $categorized   = $form_data['CategoriedTree'];
        $uncategorized = $form_data['UncategoriedTree'];

        $fs_company_info_id = $form_data['fs_company_info_id'];

        $total_value_ty = 0;
        $total_value_ly = 0;

        foreach ($categorized as $key => $value) 
        {
            if($value['type'] != 'Branch')
            {
                $value_ty = round($this->revert_to_original_value($value['data']['value']), 2);
                $value_ly = round($this->revert_to_original_value($value['data']['company_end_prev_ye_value']), 2);

                $total_value_ty += $value_ty;
                $total_value_ly += $value_ly;
            }
        }

        
            $x = $this->fs_account_category_model->insert_categorized_account($categorized, $fs_company_info_id);
            $y = $this->fs_account_category_model->insert_uncategorized_account($uncategorized, $fs_company_info_id);
            $z = $this->fs_account_category_model->insert_categorized_account_round_off($fs_company_info_id); // duplicate data for round off and save to fs_categorized_account_round_off

            $adjust_round_off = $this->fs_account_category_model->fs_adjust_round_off($fs_company_info_id);  // update on 13/08/2020

            $b = $this->fs_statements_model->insert_update_fs_state_comp_income($fs_company_info_id);
            
            $a = $this->fs_notes_model->update_reset_fs_ntfs($fs_company_info_id);
            // $a = $this->fs_notes_model->update_fs_ntfs_specific_require($fs_company_info_id);
            $c = $this->fs_notes_model->insert_update_fs_note_details($fs_company_info_id);
            $d = $this->fs_notes_model->insert_fs_state_comp_income_fs_note_details($fs_company_info_id);
            $e = $this->fs_account_category_model->update_next_ye_values($fs_company_info_id);

            // $level_1_account_code = $this->fs_account_category_model->get_all_level_account_code($fs_company_info_id);

            // foreach ($categorized as $key => $value) 
            // {
            //     $categorized[$key]['fs_categorized_account_id'] = json_decode($x)->fs_categorized_account_ids[$key];
            // }
            // $fs_state_comp_income = $this->fs_statements_model->insert_update_fs_state_comp_income($fs_company_info_id);
            
        if(number_format($total_value_ty, 2) == 0 && number_format($total_value_ly, 2) == 0)
        {
            if($x['result'] && $y['result'])
            {
                echo json_encode(array('result' => true, 'message' => 'Trial Balance categorized!'));
            }
            else
            {
                echo json_encode(array('result' => false, 'message' => 'Opps! Something went wrong! Please try again later.'));
            }
        }
        else
        {
            if(number_format($total_value_ty, 2) != 0 && number_format($total_value_ly, 2) != 0)
            {
                echo json_encode(array('result' => true, 'message' => 'Data is saved but total of "Current Year Value" and "Last Year Value" are not equal to 0! (' . number_format($total_value_ty, 2) . ', ' . number_format($total_value_ly, 2) . ')'));
            }
            elseif(number_format($total_value_ty, 2) != 0)
            {
                echo json_encode(array('result' => true, 'message' => 'Data is saved but total of "Current Year Value" is not equal to 0! (' . number_format($total_value_ty, 2) . ')'));
            }
            elseif(number_format($total_value_ly, 2) != 0)
            {
                echo json_encode(array('result' => true, 'message' => 'Data is saved but total of "Last Year Value" is not equal to 0! (' . number_format($total_value_ly, 2) . ')'));
            }
        }

        
        // $update_state_comp_income = $this->fs_statements_model->update_state_comp_income($fs_company_info_id);

        // echo json_encode($x);
    }

    // public function create_new_category()
    // {
    //     $form_data = $this->input->post();

    //     $data = array(
    //         'description' => $form_data['new_description'],
    //         'type'        => 'Branch'
    //     );

    //     $result = $this->fs_account_category_model->insert_new_category($data);

    //     echo json_encode($result);
    // }
    /* END OF SAVE DATA */

    // external useful function
    public function evaluate_math_from_string($formula) // to calculate total value for the cell from excel
    {
        $total_in_cell = 0.00;

        $numbers = preg_split('/[-+*=\/\|]=?/', $formula);

        if(preg_match_all('/[-+*=\/\|]=?/', $formula, $operators) !== FALSE){

            $total_in_cell = $numbers[0];

            foreach($operators[0] as $key=>$operator)
            {
                switch($operator){
                    case '+':
                        $total_in_cell = $total_in_cell + (float)$numbers[$key+1];
                        break;
                    case '-':
                        $total_in_cell = $total_in_cell - (float)$numbers[$key+1];
                        break;
                    case '*':
                        $total_in_cell = $total_in_cell * (float)$numbers[$key+1];
                        break;
                    case '/':
                        $total_in_cell = $total_in_cell / (float)$numbers[$key+1];
                        break;
                }
            }
        }
        return $total_in_cell;
    }

    public function revert_to_original_value($number)
    {
        $number = str_replace(',', '', $number);
        $number = str_replace('(', '-', $number);
        $number = str_replace(')', '', $number);

        return (float)$number;
    }

    // public function write_main_excel()
    // {
    //     /* ----------------------------------- Working codes ----------------------------------- */
    //     // // Creating the new document...
    //     // $phpWord = new \PhpOffice\PhpWord\PhpWord();

    //     // //This is the main document in  Template.docx file.
    //     // $file = site_url('excel_word/helloWorld.docx');

    //     // echo $file;

    //     // $phpword = new \PhpOffice\PhpWord\TemplateProcessor($file);

    //     // $phpword->setValue('{name}','Santosh');
    //     // $phpword->setValue('{lastname}','Achari');
    //     // $phpword->setValue('{officeAddress}','Yahoo');

    //     // $phpword->saveAs('./excel_word/edited.docx');
    //     /* ----------------------------------- END OF Working codes ----------------------------------- */

    //     // Creating the new document...
    //     $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();

    //     //This is the main document in a .docx file.
    //     $fileToModify = 'word/document.xml';

    //     // Creating the new document...
    //     $phpWord = new \PhpOffice\PhpWord\PhpWord();

    //     //This is the main document in  Template.docx file.
    //     $filepath = FCPATH . 'excel_word/Sample Report.docx';
    //     $temp_file = FCPATH . 'excel_word/'.date('Ymdhis').'.docx';

    //     copy($filepath, $temp_file);

    //     if ($zip->open($temp_file) === TRUE) 
    //     {
    //         $oldContents = $zip->getFromName($fileToModify);

    //         /* ---------------------------- Modify contents: ---------------------------- */

    //         $sdt_template = $this->fs_replace_content_model->get_part_of_template('<w:sdt', 'w:sdt', $oldContents);

    //         foreach ($sdt_template[0] as $key => $sdt) 
    //         {
    //             // echo json_encode($sdt) . '<br/><br/><br/><br/><br/><br/>';
    //             $alias_tag = $this->fs_replace_content_model->get_tag('<w:alias', '/>', $sdt);

    //             if(count($alias_tag[0]) > 0)    // if got w:alias
    //             {
    //                 $alias_value = $this->fs_replace_content_model->get_attribute_value('w:val', $alias_tag[0][0]);

    //                 // echo json_encode($alias_value[1]) . '\n\n';

    //                 $replace_content = $this->fs_replace_content_model->retrieve_content($alias_value[1]);

    //                 $display_template = $this->fs_replace_content_model->get_part_of_template('<w:t>', 'w:t', $sdt);

    //                 // echo json_encode($display_template[0][0]) . '<br/><br/><br/><br/><br/>';

    //                 // break;

    //                 $sdt_new_content = str_replace($display_template[0][0], '<w:t>' . $replace_content . '</w:t>', $sdt);

    //                 // echo $sdt_new_content;
    //                 // break;

    //                 $oldContents = str_replace($sdt, $sdt_new_content, $oldContents);
    //             }

    //             // echo json_encode($alias_template[0]);

    //             // echo str_replace("<w:alias", "", $alias_template);
    //         }

    //         $newContents = $oldContents;

    //         /* ------------------------- END OF Modify contents: ------------------------- */

    //         //Delete the old...
    //         $zip->deleteName($fileToModify);
    //         //Write the new...
    //         $zip->addFromString($fileToModify, $newContents);
    //         //And write back to the filesystem.
    //         $return =$zip->close();
    //         If ($return==TRUE){
    //             echo json_encode(array('result' => true));
    //         }
    //     } else {
    //         echo json_encode(array('result' => false));
    //     }

        
    // }

    public function test()
    {
        // print_r($this->fs_statements_model->get_fs_financial_position_data(9));
        print_r($this->fs_account_category_model->set_default_tree());
    }

}