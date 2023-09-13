<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require $_SERVER['DOCUMENT_ROOT'] . '/' . explode('/', $_SERVER['REQUEST_URI'])[1] . '/composer_plugin/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// header("Content-type:application/pdf");

// require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');
class Fs_generate_doc_word extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

        $this->load->library(array('encryption'));

		$this->load->helper('form');
		$this->load->model('fs_model');
		$this->load->model('fs_account_category_model');
		$this->load->model('fs_statements_model');
		$this->load->model('fs_replace_content_model');
		$this->load->model('fs_generate_doc_word_model');
	}

	public function index()
	{

	}

	public function fs_report()
	{
		$form_data = $this->input->post();

		$fs_company_info_id    = $form_data['fs_company_info_id'];
        $draft_report          = $form_data['draft_report'];
        $first_generate_report = $form_data['first_generate_report'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

		$retrieve_file_data = $this->fs_generate_doc_word_model->get_fs_doc_template_word($fs_company_info_id);

        $document_template_data = array(
                                        'first_generate_report' => $first_generate_report
                                    );

		if(count($retrieve_file_data) > 0)	// retrieve back the existing filename
		{
            $document_template_data['load_from_default'] = false;
            // print_r($retrieve_file_data);

            $document_template_data['uploaded_data'] = $retrieve_file_data;
		}
		else // create the docx file and save name to db
		{
            $document_template_name = '';
            $document_template_data['load_from_default'] = true;
		}

        if($fs_company_info[0]['accounting_standard_used'] == 4)
        {
            if($fs_company_info[0]['is_audited'])
            {
                if($draft_report)
                {
                    $document_template_name = 'Report (Small FRS) AUDIT - Draft';
                }
                else
                {
                    $document_template_name = 'Report (Small FRS) AUDIT';
                }
            }
            else
            {
                if($draft_report)
                {
                    $document_template_name = 'Report (Small FRS) NON AUDIT - Draft';
                }
                else
                {
                    $document_template_name = 'Report (Small FRS) NON AUDIT';
                }
            }
        }
        else
        {
            if($draft_report)
            {
                $document_template_name = 'Financial Statement - Draft';
            }
            else
            {
                $document_template_name = 'Financial Statement';
            }
        }

        $document_template_data['document_name'] = $document_template_name;

        $result = $this->create_new_docx($fs_company_info_id, $document_template_data);

		// echo json_encode($form_data);
	}

	public function create_new_docx($fs_company_info_id, $document_template_data)
	{
        // print_r(array($document_template_data));
        $additional_info = array(
                        'generate_docs_without_tags' => 0
                    );

		$fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
        $fs_settings     = $this->fs_model->get_fs_settings($fs_company_info_id);

        if(count($fs_settings) > 0)
        {
            if($fs_settings[0]['generate_docs_without_tags'] == null)
            {
                $fs_settings[0]['generate_docs_without_tags'] = 0;
            }

            $additional_info = array(
                            'generate_docs_without_tags' => $fs_settings[0]['generate_docs_without_tags']
                        );
        }

		// Creating the new document...
        $zip = new \PhpOffice\PhpWord\Shared\ZipArchive();

        //This is the main document in a .docx file.
        $fileToModify = 'word/document.xml';

        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        //This is the main document in  Template.docx file.
        if($document_template_data['load_from_default'])
        {
            $filepath = FCPATH . 'Document Templates/FS Master/' . $document_template_data['document_name'] . '.docx';
        }
        else // load from FS Client template uploaded
        {
            $filepath = FCPATH . $document_template_data['uploaded_data'][0]['filepath'];
            // $temp_file = FCPATH . 'Generated Documents/Word/' . $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx';
        }

        $filepath = FCPATH . 'Document Templates/FS Master/' . $document_template_data['document_name'] . '.docx'; // for testing master

        $client_filepath = 'Document Templates/FS Template Client/';
        $temp_file = FCPATH . 'Generated Documents/Word/' . $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx';

        // $filepath = FCPATH . 'Document Sample/1 - Numbering & Hidden text.docx';
        // $temp_file = FCPATH . 'Generated Word File/Numbering & Hidden text.docx';

        // $filepath = FCPATH . 'Document Sample/2 - Table Test 2.docx';
        // $temp_file = FCPATH . 'Generated Word File/2 - Table Test 2 - Generated.docx';

        copy($filepath, $temp_file);

        if ($zip->open($temp_file) === TRUE) 
        {
            for ($i = 0; $i < $zip->numFiles; $i++) 
            {
                $filename = $zip->getNameIndex($i); // get all filenames in zip folder
                
                // print_r($filename . ' ------------------ ');

                // if($filename == 'word/document.xml' || strpos($filename, 'word/header') !== false)
                if($filename == 'word/document.xml' || strpos($filename, 'word/header') !== false)
                {
                    // print_r($filename);
                    $oldContents = $zip->getFromName($filename);

                    // $newContents = $this->fs_generate_doc_word_model->update_using_simplexml($oldContents, $fs_company_info_id); // using simplexml

                    // print_r("expression");
                    $newContents = $this->fs_generate_doc_word_model->update_toggle($oldContents, $fs_company_info_id, $additional_info);
                    $newContents = $this->fs_generate_doc_word_model->update_table($newContents, $fs_company_info_id, $additional_info);
                    $newContents = $this->fs_generate_doc_word_model->convert_special_character($newContents);

                    //Delete the old...
                    $zip->deleteName($filename);
                    //Write the new...
                    $zip->addFromString($filename, $newContents);
                }
            }

            // $oldContents = $zip->getFromName($fileToModify);
            // $oldHeaderContents = $zip->getFromName('word/header1.xml');

            /* ---------------------------- Modify contents: ---------------------------- */

            // $newContents = $this->fs_generate_doc_word_model->update_toggle($oldContents, $fs_company_info_id);
            // $newHeaderContents = $this->fs_generate_doc_word_model->update_toggle($oldHeaderContents, $fs_company_info_id);

            /* ------------------------- END OF Modify contents: ------------------------- */

            //Delete the old...
            // $zip->deleteName($fileToModify);
            // $zip->deleteName('word/header1.xml');

            //Write the new...
            // $zip->addFromString($fileToModify, $newContents);
            // $zip->addFromString('word/header1.xml', $newHeaderContents);
            //And write back to the filesystem.
            $return = $zip->close();

            If ($return==TRUE)
            {
                $array_link = [];

                // output: http://
                // $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
                $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';

                // print_r($protocol . 'Generated Documents/Word/Financial Statement (' . $fs_company_info[0]['company_name'] . ').docx');

                //This is the main document in Template.docx file.
                array_push($array_link, $protocol . $_SERVER['SERVER_NAME'] . '/fs/Generated Documents/Word/' . $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx');

                // if($document_template_data['load_from_default'])
                // {
                //     array_push($array_link, $protocol . $_SERVER['SERVER_NAME'] . '/fs/Generated Documents/Word/' . $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx');
                // }
                // else // load from FS Client template uploaded
                // {
                //     array_push($array_link, $protocol . $_SERVER['SERVER_NAME'] . '/fs/Generated Documents/Word/' . $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx');
                // }

                echo json_encode(array("status" => 1, "link" => $array_link, "first_generate_report" => $document_template_data['first_generate_report']));

                $data = array(
                            'fs_company_info_id' => $fs_company_info_id,
                            'copy_from'          => $temp_file,
                            'client_filepath'    => $client_filepath,
                            'filename'           => $document_template_data['document_name'] . ' (' . $this->encryption->decrypt($fs_company_info[0]['company_name']) . ').docx',
                        );

                if(!($additional_info['generate_docs_without_tags'] == 1))
                {
                    $this->fs_generate_doc_word_model->update_save_report_template($data);
                }
            }
            else
            {
                echo json_encode(array("status" => 0, "link" => $array_link, "first_generate_report" => $document_template_data['first_generate_report']));
            }

        } 
        else 
        {
            return false;
        }
	}

    public function get_trial_balance_template_excel()
    {
        $array_link = [];
        
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link, $protocol . $_SERVER['SERVER_NAME'] . '/fs/Document Templates/Excel/Trial balance - template.xlsx');

        echo json_encode(array("status" => 1, "link" => $array_link));
    }

    public function upload_report_template()
    {
        $fs_company_info_id = $_POST['fs_company_info_id'];

        $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);   
        $year_of_YE = substr($fs_company_info[0]['current_fye_end'], -4);

        $this->fs_generate_doc_word_model->create_year_folder_for_client_report_template($fs_company_info_id, $year_of_YE);  // create year folder if it is empty

        $fs_doc_template_word = $this->fs_generate_doc_word_model->get_fs_doc_template_word($fs_company_info_id);

        // print_r(array('input_file' => $_FILES['rt_input_file']['name']));

        if($_FILES['rt_input_file']['name']!="")
        {
            $target_dir = "Document Templates/FS Template Client/" . $year_of_YE . '/';

            $file = $_FILES['rt_input_file']['name'];
            $path = pathinfo($file);
            $filename = $path['filename'];
            $ext = $path['extension'];
            $temp_name = $_FILES['rt_input_file']['tmp_name'];
            $path_filename_ext = $target_dir.$filename.".".$ext;

            // print_r($_FILES['rt_input_file']);
            

            // save file name
            if(count($fs_doc_template_word) > 0)    // delete previous template and update db data
            {
                $file_pointer = '../fs/' . $fs_doc_template_word[0]['filepath'];

                // print_r(array($file_pointer));

                if (!unlink($file_pointer)) 
                {  
                    // $alert_msg = "$file_pointer cannot be deleted due to an error";  
                    $alert_msg = "The previous file cannot be deleted due to an error. Therefore, the file is failed to upload."; 

                    $result = false;
                }  
                else 
                {  
                    // $alert_msg = "$file_pointer has been deleted";  

                    move_uploaded_file($temp_name, $path_filename_ext);  

                    // update filepath and filename in db
                    $result = $this->fs_notes_model->update_tbl_data('fs_doc_template_word', 
                                                array(
                                                    array(
                                                        'id' => $fs_doc_template_word[0]['id'],
                                                        'info' => array(
                                                                    'filename' => $filename .".". $ext,
                                                                    'filepath' => $path_filename_ext
                                                                )
                                                    )
                                                ));

                    if(!$result)
                    {
                        $error_msg = "Something went wrong. Please try again later."; 
                    }
                    else
                    {
                        $alert_msg = "Upload success. The previous file has been replaced.";  
                    }
                }
            }
            else // add in template to db
            {
                move_uploaded_file($temp_name, $path_filename_ext); 

                $result = $this->fs_notes_model->insert_tbl_data('fs_doc_template_word', 
                                                array(
                                                    array(
                                                    'info' => array(
                                                                'fs_company_info_id' => $fs_company_info_id,
                                                                'filename' => $filename .".". $ext,
                                                                'filepath' => $path_filename_ext
                                                            )
                                                    )
                                                ));

                if(!$result)
                {
                    $error_msg = "Something went wrong. Please try again later."; 
                }
                else
                {
                    $alert_msg = "The report template is uploaded."; 
                }
            }

            // $result = $this->fs_model->save_fs_signing_report($fs_signing_report_id, array('file_name' => $_FILES['rt_input_file']['name'], 'fs_company_info_id' => $fs_company_info_id));
        }
        else
        {
            $result = false;
            $alert_msg = "Input filename cannot be empty.";
        }

        echo json_encode(array('result' => $result, 'alert_msg' => $alert_msg));
    }

    public function remove_report_template()
    {
        $form_data = $this->input->post();
        $rt_id     = $form_data['rt_id'];
        
        $fs_doc_template_word = $this->db->query("SELECT * FROM fs_doc_template_word WHERE id=" . $rt_id);
        $fs_doc_template_word = $fs_doc_template_word->result_array();

        if(count($fs_doc_template_word) > 0)
        {
            $file_pointer = '../fs/' . $fs_doc_template_word[0]['filepath'];

            if (!unlink($file_pointer)) 
            {  
                // $alert_msg = "$file_pointer cannot be deleted due to an error";  
                $alert_msg = "The report template cannot be deleted due to an error.";  

                $result = false;
            }  
            else 
            {  
                // $alert_msg = "$file_pointer has been deleted"; 

                $this->db->where_in('id', $rt_id);
                $result = $this->db->delete('fs_doc_template_word');

                if(!$result)
                {
                    $alert_msg = "Something went wrong. Please try again later."; 
                }
                else
                {
                    $alert_msg = "The report template has been deleted."; 
                }
            }
        }

        echo json_encode(array('result' => $result, 'alert_msg' => $alert_msg));
    }

    public function download_doc_template()
    {
        $form_data = $this->input->post();
        $fs_company_info_id = $form_data['fs_company_info_id'];

        $file = $this->fs_generate_doc_word_model->get_fs_doc_template_word($fs_company_info_id);

        $array_link = [];

        // output: http://
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        if(count($file) > 0)
        {
            array_push($array_link, $protocol . $_SERVER['SERVER_NAME'] . '/fs/' . $file[0]['filepath']);
            echo json_encode(array("status" => 1, "link" => $array_link));
        }
        else
        {
            echo json_encode(array("status" => 0, "msg" => 'Failed to retrieve document template. Please try again later.'));
        }
    }

    public function test()
    {
        $fs_company_info = $this->fs_model->get_fs_company_info(2);

        print_r(date('Y', strtotime($fs_company_info[0]['last_fye_end'])));
    }
}