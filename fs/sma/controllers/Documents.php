<?php defined('BASEPATH') OR exit('No direct script access allowed');

class documents extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->model('document_model');
        $this->load->library(array('session', 'form_validation'));
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $this->data['sales'] = $this->db_model->getLatestSales();
        // $this->data['quotes'] = $this->db_model->getLastestQuotes();
        // $this->data['purchases'] = $this->db_model->getLatestPurchases();
        // $this->data['transfers'] = $this->db_model->getLatestTransfers();
        // $this->data['customers'] = $this->db_model->getLatestCustomers();
        // $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        // $this->data['chatData'] = $this->db_model->getChartData();
        // $this->data['stock'] = $this->db_model->getStockValue();
        // $this->data['bs'] = $this->db_model->getBestSeller();
        // $this->data['users'] = $this->db_model->getUserList();
        // $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        // $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
		// if(isset($_POST['start']))
		// {
		// 	$q = $this->db->query("select A.*,B.client_name from waitingdocument A, client B where A.unique_code = B.unique_code");
		// 	$data = [];
		// 	if ($q->num_rows() > 0) {
		// 		foreach (($q->result()) as $row) {
		// 			$data[] = $row;
		// 		}
		// 		$this->data['hasil'] =$data;
		// 	}
		// }
        $pending_documents_files_id = array();
        $this->session->set_userdata(array(
            'pending_documents_files_id'  =>  $pending_documents_files_id,
        ));

        $bc = array(array('link' => '#', 'page' => lang('Documents')));
        $meta = array('page_title' => lang('Documents'), 'bc' => $bc, 'page_name' => 'Documents');

        if (isset($_POST['search'])) {
            if (isset($_POST['search']) && isset($_POST['type']))
            {

                // if ($_POST['pencarian'] != '')
                // {
                    $this->data['pending_documents'] = $this->document_model->get_all_pending($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['all_documents'] = $this->document_model->get_all_document($_SESSION['group_id'], $_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['document_master'] = $this->document_model->get_all_document_master($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                    $this->data['document_reminder'] = $this->document_model->get_all_document_reminder($_POST['type'],$_POST['search'],$_POST['start'],$_POST['end']);
                // }
            } 
        }
        else
        {
            $this->data['pending_documents'] = $this->document_model->get_all_pending();
            $this->data['all_documents'] = $this->document_model->get_all_document($_SESSION['group_id']);
            $this->data['document_master'] = $this->document_model->get_all_document_master();
            $this->data['document_reminder'] = $this->document_model->get_all_document_reminder();
        }

        
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('document/documents.php', $meta, $this->data);

    }

    public function create_pending_document()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Document')));
        $meta = array('page_title' => lang('Create Document'), 'bc' => $bc, 'page_name' => 'Create Document');
        /*$this->session->unset_userdata('billing_company_code');
        $this->session->unset_userdata('billing_currency');*/

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Create Document', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        // $this->data['page_name'] = 'Clients';
        $this->page_construct('document/create_pending_document.php', $meta, $this->data);
    }

    public function edit_pending_document($id, $type = null)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Document')));
        $meta = array('page_title' => lang('Edit Document'), 'bc' => $bc, 'page_name' => 'Edit Document');

        $this->data['pending_document'] = $this->document_model->get_pending_document($id, $type);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Edit Document - '.(($this->data['pending_document'][0]->client_name != null)?$this->data['pending_document'][0]->client_name:$this->data['pending_document'][0]->company_name).'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/create_pending_document.php', $meta, $this->data);
    }

    public function add_pending_document_file($id, $type = null)
    {
        $bc = array(array('link' => '#', 'page' => lang('Add Document File')));
        $meta = array('page_title' => lang('Add Document File'), 'bc' => $bc, 'page_name' => 'Add Document File');

        $this->data['pending_document_info'] = $this->document_model->get_pending_document($id, $type);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Add Document File - '.(($this->data['pending_document_info'][0]->company_name != null)?$this->data['pending_document_info'][0]->company_name:$this->data['pending_document_info'][0]->client_name).'', base_url());
        //echo json_encode($this->data['pending_document_info'][0]->company_name);

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/add_document_file.php', $meta, $this->data);
    }

    public function edit_pending_document_file($id, $type = null)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Document File')));
        $meta = array('page_title' => lang('Edit Document File'), 'bc' => $bc, 'page_name' => 'Edit Document File');

        $pending_document_id = array();
        $this->session->set_userdata(array(
            'pending_document_id'  =>  $pending_document_id,
        ));

        $pending_documents_files_id = array();
        $this->session->set_userdata(array(
            'pending_documents_files_id'  =>  $pending_documents_files_id,
        ));

        $this->data['pending_document_info'] = $this->document_model->get_pending_document_file($id, $type);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Edit Document File - '.(($this->data['pending_document_info'][0]->company_name != null)?$this->data['pending_document_info'][0]->company_name:$this->data['pending_document_info'][0]->client_name).'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/add_document_file.php', $meta, $this->data);
    }

    public function insert_pending_document_file() 
    {
        if($_POST["doc_type"] == 'trans')
        {
            $data['received_on']=$_POST["document_file_received_on"];
            $data['received_by']=$this->session->userdata('user_id');

            $q = $this->db->get_where("transaction_pending_documents", array("id" => $_POST['pending_document_id']));
            $q = $q->result_array();

            $this->db->update("transaction_pending_documents",$data,array("id" => $_POST['pending_document_id'])); 

            $this->session->set_userdata(array(
                'transaction_pending_document_id'  =>  $_POST['pending_document_id'],
            ));

            $this->session->set_userdata(array(
                'transaction_pending_document_transaction_id'  =>  $q[0]["transaction_id"],
            ));

            if (count($this->session->userdata('transaction_pending_documents_files_id')) != 0)
            {
                $pending_documents_files_id = $this->session->userdata('transaction_pending_documents_files_id');
                for($i = 0; $i < count($pending_documents_files_id); $i++)
                {
                    $files = $this->db->query("select * from transaction_pending_documents_files_id where id='".$pending_documents_files_id[$i]."'");
                    $file_info = $files->result_array();

                    $this->db->where('id', $pending_documents_files_id[$i]);

                    unlink("./uploads/pending_document_file/".$file_info[0]["file_name"]);

                    $this->db->delete('transaction_pending_documents_files_id', array('id' => $pending_documents_files_id[$i]));

                    //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
                }
            }  

            echo json_encode(array("Status" => 1, "doc_type" => "trans")); 
        }
        else
        {
            $data['received_on']=$_POST["document_file_received_on"];
            $data['received_by']=$this->session->userdata('user_id');

            $q = $this->db->get_where("pending_documents", array("id" => $_POST['pending_document_id']));


            $this->db->update("pending_documents",$data,array("id" => $_POST['pending_document_id'])); 

            $this->session->set_userdata(array(
                'pending_document_id'  =>  $_POST['pending_document_id'],
            ));

            if (count($this->session->userdata('pending_documents_files_id')) != 0)
            {
                $pending_documents_files_id = $this->session->userdata('pending_documents_files_id');
                for($i = 0; $i < count($pending_documents_files_id); $i++)
                {
                    $files = $this->db->query("select * from pending_documents_file where id='".$pending_documents_files_id[$i]."'");
                    $file_info = $files->result_array();

                    $this->db->where('id', $pending_documents_files_id[$i]);

                    unlink("./uploads/pending_document_file/".$file_info[0]["file_name"]);

                    $this->db->delete('pending_documents_file', array('id' => $pending_documents_files_id[$i]));

                    //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
                }
            }   

            echo json_encode(array("Status" => 1, "doc_type" => null));
        } 

        
    }

    public function uploadDocumentFile($type = null)
    {
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
            //echo $type;
            if($type == "trans")
            {
                $filesCount = count($_FILES['uploadpendingdocumentfiles']['name']);
                //echo json_encode(count($_FILES['uploadimages']['name']));
                for($i = 0; $i < $filesCount; $i++)
                {   //echo json_encode($_FILES['uploadimages']);
                    $_FILES['uploadpendingdocumentfile']['name'] = $_FILES['uploadpendingdocumentfiles']['name'][$i];
                    $_FILES['uploadpendingdocumentfile']['type'] = $_FILES['uploadpendingdocumentfiles']['type'][$i];
                    $_FILES['uploadpendingdocumentfile']['tmp_name'] = $_FILES['uploadpendingdocumentfiles']['tmp_name'][$i];
                    $_FILES['uploadpendingdocumentfile']['error'] = $_FILES['uploadpendingdocumentfiles']['error'][$i];
                    $_FILES['uploadpendingdocumentfile']['size'] = $_FILES['uploadpendingdocumentfiles']['size'][$i];

                    $uploadPath = './uploads/pending_document_file';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadpendingdocumentfile'))
                    {
                        $fileData = $this->upload->data();
                        //echo json_encode($fileData);
                        $uploadData[$i]['transaction_id'] = $this->session->userdata('transaction_pending_document_transaction_id');
                        $uploadData[$i]['pending_documents_id'] = $this->session->userdata('transaction_pending_document_id');
                        $uploadData[$i]['file_name'] = $fileData['file_name'];
                        /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                        $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/
                    }

                }
                //echo json_encode($uploadData);
                if(!empty($uploadData))
                {
                    $this->db->insert_batch('transaction_pending_documents_file',$uploadData);
                    
                }
            }
            else
            {
                $filesCount = count($_FILES['uploadpendingdocumentfiles']['name']);
                //echo json_encode(count($_FILES['uploadimages']['name']));
                for($i = 0; $i < $filesCount; $i++)
                {   //echo json_encode($_FILES['uploadimages']);
                    $_FILES['uploadpendingdocumentfile']['name'] = $_FILES['uploadpendingdocumentfiles']['name'][$i];
                    $_FILES['uploadpendingdocumentfile']['type'] = $_FILES['uploadpendingdocumentfiles']['type'][$i];
                    $_FILES['uploadpendingdocumentfile']['tmp_name'] = $_FILES['uploadpendingdocumentfiles']['tmp_name'][$i];
                    $_FILES['uploadpendingdocumentfile']['error'] = $_FILES['uploadpendingdocumentfiles']['error'][$i];
                    $_FILES['uploadpendingdocumentfile']['size'] = $_FILES['uploadpendingdocumentfiles']['size'][$i];

                    $uploadPath = './uploads/pending_document_file';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
                    
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);

                    if($this->upload->do_upload('uploadpendingdocumentfile'))
                    {
                        $fileData = $this->upload->data();
                        //echo json_encode($fileData);
                        $uploadData[$i]['pending_documents_id'] = $this->session->userdata('pending_document_id');
                        $uploadData[$i]['file_name'] = $fileData['file_name'];
                        /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                        $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/
                    }

                }
                //echo json_encode($uploadData);
                if(!empty($uploadData))
                {
                    $this->db->insert_batch('pending_documents_file',$uploadData);
                    
                }
            }
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
    }

    public function deleteDocumentFile($id)
    {
        $pending_documents_files_id = $this->session->userdata('pending_documents_files_id');
        array_push($pending_documents_files_id, $id);
        $this->session->set_userdata(array(
            'pending_documents_files_id'  =>  $pending_documents_files_id,
        ));
    }

    public function delete_transaction_document ()
    {
        $id = $_POST["document_id"];

        $transaction_pending_documents = $this->db->query("select * from transaction_pending_documents where id='".$id."'");

        $transaction_pending_documents = $transaction_pending_documents->result_array();

        $this->db->delete("transaction_pending_documents",array('id'=>$id));

        $files = $this->db->query("select * from transaction_pending_documents_file where pending_documents_id='".$id."'");

        $file_info = $files->result_array();

        for($i = 0; $i < count($file_info); $i++)
        {
            $this->db->where('id', $file_info[$i]["id"]);

            unlink("./uploads/pending_document_file/".$file_info[$i]["file_name"]);

            $this->db->delete('transaction_pending_documents_file', array('pending_documents_id' => $id));

            //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
        }
        //$this->db->delete("pending_documents_file",array('pending_documents_id'=>$id));

        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function delete_document ()
    {
        $id = $_POST["document_id"];

        $pending_documents = $this->db->query("select * from pending_documents where id='".$id."'");

        $pending_documents = $pending_documents->result_array();

        if($pending_documents[0]["triggered_by"] != null)
        {
            $history_client = $this->db->query("select * from history_client where id='".$pending_documents[0]["client_id"]."'");

            $history_client = $history_client->result_array();


            

            $history_client_controller = $this->db->query("select * from history_client_controller where id='".$pending_documents[0]["controller_id"]."'");

            $history_client_controller = $history_client_controller->result_array();

            $history_client_charges = $this->db->query("select * from history_client_charges where id='".$pending_documents[0]["charge_id"]."'");

            $history_client_charges = $history_client_charges->result_array();

            if($pending_documents[0]["triggered_by"] == "7") //Change of Company Name
            {
                $data_history['registered_address'] = $history_client[0]["registered_address"];
                $data_history['postal_code'] = $history_client[0]["postal_code"];
                $data_history['street_name'] = $history_client[0]["street_name"];
                $data_history['building_name'] = $history_client[0]["building_name"];
                $data_history['unit_no1'] = $history_client[0]["unit_no1"];
                $data_history['unit_no2'] = $history_client[0]["unit_no2"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "2")
            {
                $data_history['company_name'] = $history_client[0]["company_name"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "3")
            {
                $data_history['company_type'] = $history_client[0]["company_type"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "1")
            {
                $data_history['acquried_by'] = $history_client[0]["acquried_by"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "4")
            {
                $data_history['status'] = $history_client[0]["status"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "5")
            {
                $data_history['activity1'] = $history_client[0]["activity1"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "6")
            {
                $data_history['activity2'] = $history_client[0]["activity2"];

                $this->db->update("client",$data_history,array("id" =>  $pending_documents[0]["client_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "8" || $pending_documents[0]["triggered_by"] == "14" || $pending_documents[0]["triggered_by"] == "16" || $pending_documents[0]["triggered_by"] == "12" || $pending_documents[0]["triggered_by"] == "10" || $pending_documents[0]["triggered_by"] == "18")
            {
                

                $officer_id = json_decode($pending_documents[0]["officer_id"]);

                for($a = 0; $a < count($officer_id); $a++)
                {   
                    $history_client_officers = $this->db->query("select * from history_client_officers where id='".$officer_id[$a]."'");

                    $history_client_officers = $history_client_officers->result_array();

                    $data_history['date_of_appointment'] = $history_client_officers[0]["date_of_appointment"];

                    if(count($history_client_officers) != 0)
                    {
                        $this->db->update("client_officers",$data_history,array("id" =>  $officer_id[$a]));
                        $this->db->delete("history_client_officers",array('id'=>$officer_id[$a]));
                    }
                    else
                    {
                        $this->db->delete("client_officers",array('id'=>$officer_id[$a]));
                    }

                    
                }
                
            }
            else if($pending_documents[0]["triggered_by"] == "9" || $pending_documents[0]["triggered_by"] == "15" || $pending_documents[0]["triggered_by"] == "17" || $pending_documents[0]["triggered_by"] == "13" || $pending_documents[0]["triggered_by"] == "11" || $pending_documents[0]["triggered_by"] == "19")
            {
                /*$data_history['date_of_cessation'] = $history_client_officers[0]["date_of_cessation"];

                $this->db->update("client_officers",$data_history,array("id" =>  $pending_documents[0]["officer_id"]));*/

                $officer_id = json_decode($pending_documents[0]["officer_id"]);

                for($a = 0; $a < count($officer_id); $a++)
                {   
                    $history_client_officers = $this->db->query("select * from history_client_officers where id='".$officer_id[$a]."'");

                    $history_client_officers = $history_client_officers->result_array();

                    $data_history['date_of_cessation'] = $history_client_officers[0]["date_of_cessation"];

                    if(count($history_client_officers) != 0)
                    {
                        $this->db->update("client_officers",$data_history,array("id" =>  $officer_id[$a]));
                        $this->db->delete("history_client_officers",array('id'=>$officer_id[$a]));
                    }
                    else
                    {
                        $this->db->delete("client_officers",array('id'=>$officer_id[$a]));
                    }

                    
                }
            }
            elseif($pending_documents[0]["triggered_by"] == "29")
            {
                $charge_id = json_decode($pending_documents[0]["charge_id"]);

                for($a = 0; $a < count($charge_id); $a++)
                {   
                    $history_client_charges = $this->db->query("select * from history_client_charges where id='".$charge_id[$a]."'");

                    $history_client_charges = $history_client_charges->result_array();

                    $data_history['date_registration'] = $history_client_charges[0]["date_registration"];

                    if(count($history_client_charges) != 0)
                    {
                        $this->db->update("client_charges",$data_history,array("id" =>  $charge_id[$a]));
                        $this->db->delete("history_client_charges",array('id'=>$charge_id[$a]));
                    }
                    else
                    {
                        $this->db->delete("client_charges",array('id'=>$charge_id[$a]));
                    }

                    
                }
            }
            elseif($pending_documents[0]["triggered_by"] == "30")
            {
                $charge_id = json_decode($pending_documents[0]["charge_id"]);

                for($a = 0; $a < count($charge_id); $a++)
                {   
                    $history_client_charges = $this->db->query("select * from history_client_charges where id='".$charge_id[$a]."'");

                    $history_client_charges = $history_client_charges->result_array();

                    $data_history['date_satisfied'] = $history_client_charges[0]["date_satisfied"];

                    if(count($history_client_charges) != 0)
                    {
                        $this->db->update("client_charges",$history_client_charges,array("id" =>  $charge_id[$a]));
                        $this->db->delete("history_client_charges",array('id'=>$charge_id[$a]));
                    }
                    else
                    {
                        $this->db->delete("client_charges",array('id'=>$charge_id[$a]));
                    }

                    
                }
            }
            elseif($pending_documents[0]["triggered_by"] == "23")
            {
                $allotment_id = json_decode($pending_documents[0]["allotment_id"]);

                for($a = 0; $a < count($allotment_id); $a++)
                {   
                    $member_shares = $this->db->query("select * from member_shares where id='".$allotment_id[$a]."'");

                    $member_shares = $member_shares->result_array();

                    //$data_history['date_satisfied'] = $member_shares[0]["date_satisfied"];
                    $this->db->delete("member_shares",array('id'=>$allotment_id[$a]));
                    $this->db->delete("certificate",array('transaction_id'=>$member_shares[0]["transaction_id"]));

                    
                }
            }
            elseif($pending_documents[0]["triggered_by"] == "24")
            {
                $buyback_id = json_decode($pending_documents[0]["buyback_id"]);

                for($a = 0; $a < count($buyback_id); $a++)
                {   
                    $member_shares = $this->db->query("select * from member_shares where id='".$buyback_id[$a]."'");

                    $member_shares = $member_shares->result_array();

                    //$data_history['date_satisfied'] = $member_shares[0]["date_satisfied"];
                    $this->db->delete("member_shares",array('id'=>$buyback_id[$a]));
                    $this->db->delete("certificate",array('transaction_id'=>$member_shares[0]["transaction_id"]));

                    $query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where officer_id = '".$member_shares[0]["officer_id"]."' AND company_code = '".$member_shares[0]["company_code"]."' AND transaction_id = '".$member_shares[0]["transaction_id"]."' AND field_type = '".$member_shares[0]["field_type"]."'");



                    if ($query_certificate_merge->num_rows() > 0) 
                    {
                        $query_certificate_merge = $query_certificate_merge->result_array();

                        for($i = 0; $i < count($query_certificate_merge); $i++)
                        {
                            if($query_certificate_merge[$i]["certificate_no"] != '')
                            {
                                //echo json_encode($query_certificate_merge[$i]["certificate_no"]);
                                $this->db->set("status", 1);
                                $this->db->where(array("certificate_no" => $query_certificate_merge[$i]["certificate_no"], "client_member_share_capital_id" => $query_certificate_merge[$i]["client_member_share_capital_id"]));
                                $this->db->update("certificate");
                                /*echo json_encode($query_certificate_merge[$i]["certificate_no"]);
                                echo json_encode($query_certificate_merge[$i]["client_member_share_capital_id"]);*/
                                //$this->db->update("client_charges",$history_client_charges,array("id" =>  $charge_id[$a]));
                            }
                            $this->db->delete('certificate_merge', array("id" => $query_certificate_merge[$i]["id"]));
                        }

                        
                    }
                }
            }
            elseif($pending_documents[0]["triggered_by"] == "25")
            {
                $transfer_id = json_decode($pending_documents[0]["transfer_id"]);

                for($a = 0; $a < count($transfer_id); $a++)
                {   
                    $member_shares = $this->db->query("select * from member_shares where id='".$transfer_id[$a]."'");

                    $member_shares = $member_shares->result_array();

                    //$data_history['date_satisfied'] = $member_shares[0]["date_satisfied"];
                    $this->db->delete("member_shares",array('id'=>$transfer_id[$a]));
                    $this->db->delete("certificate",array('transaction_id'=>$member_shares[0]["transaction_id"]));

                    $query_certificate_merge = $this->db->query("select id, company_code, merge_date, client_member_share_capital_id, officer_id, field_type, transaction_id, number_of_share, certificate_no, new_certificate_no from certificate_merge where officer_id = '".$member_shares[0]["officer_id"]."' AND company_code = '".$member_shares[0]["company_code"]."' AND transaction_id = '".$member_shares[0]["transaction_id"]."' AND field_type = '".$member_shares[0]["field_type"]."'");

                    if ($query_certificate_merge->num_rows() > 0) 
                    {
                        $query_certificate_merge = $query_certificate_merge->result_array();

                        for($i = 0; $i < count($query_certificate_merge); $i++)
                        {
                            if($query_certificate_merge[$i]["certificate_no"] != '')
                            {
                                //echo json_encode($query_certificate_merge[$i]["certificate_no"]);
                                $this->db->set("status", 1);
                                $this->db->where(array("certificate_no" => $query_certificate_merge[$i]["certificate_no"], "client_member_share_capital_id" => $query_certificate_merge[0]["client_member_share_capital_id"]));
                                $this->db->update("certificate");
                                //$this->db->update("client_charges",$history_client_charges,array("id" =>  $charge_id[$a]));
                            }
                            $this->db->delete('certificate_merge', array("id" => $query_certificate_merge[$i]["id"]));
                        }

                        
                    }
                    
                }
            }
            
            /*else if($pending_documents[0]["triggered_by"] == "15")
            {
                $data_history['date_of_registration'] = $history_client_controller[0]["date_of_registration"];

                $this->db->update("client_controller",$data_history,array("id" =>  $pending_documents[0]["officer_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "16")
            {
                $data_history['date_of_cessation'] = $history_client_controller[0]["date_of_cessation"];

                $this->db->update("client_controller",$data_history,array("id" =>  $pending_documents[0]["officer_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "17")
            {
                $data_history['date_registration'] = $history_client_charges[0]["date_registration"];

                $this->db->update("client_charges",$data_history,array("id" =>  $pending_documents[0]["officer_id"]));
            }
            else if($pending_documents[0]["triggered_by"] == "18")
            {
                $data_history['date_satisfied'] = $history_client_charges[0]["date_satisfied"];

                $this->db->update("client_charges",$data_history,array("id" =>  $pending_documents[0]["officer_id"]));
            }*/
        }

        $this->db->delete("pending_documents",array('id'=>$id));

        $files = $this->db->query("select * from pending_documents_file where pending_documents_id='".$id."'");

        $file_info = $files->result_array();

        for($i = 0; $i < count($file_info); $i++)
        {
            $this->db->where('id', $file_info[$i]["id"]);

            unlink("./uploads/pending_document_file/".$file_info[$i]["file_name"]);

            $this->db->delete('pending_documents_file', array('pending_documents_id' => $id));

            //echo json_encode(unlink("./uploads/images_or_pdf/".$file_info[0]["file_name"]));
        }
        //$this->db->delete("pending_documents_file",array('pending_documents_id'=>$id));

        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function delete_master_document ()
    {
        $id = $_POST["master_id"];
        $this->db->delete("document_master",array('id'=>$id));

        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function delete_reminder_document()
    {
        $id = $_POST["reminder_id"];
        $this->db->delete("document_reminder",array('id'=>$id));

        echo json_encode(array('message' => 'Information Updated', 'title' => 'Updated'));
    }

    public function clientSearch()
    {
        //echo json_decode($_POST);

        $this->db->select('id, company_name');
        $this->db->from('client');
        $this->db->where('firm_id', $this->session->userdata('firm_id'));
        $this->db->where('deleted = 0');
        $clients = $this->db->get(); 

        $phrase = "";
        if(isset($_POST['phrase'])) {
            $phrase = $_POST['phrase'];
        }
        //echo json_encode($clients->result());
        $dataType = "json";
        if(isset($_POST['dataType'])) {
            $dataType = $_POST['dataType'];
        }
        $found_clients = array();
        foreach ($clients->result() as $key => $client) {
            if ($phrase == "" || stristr($client->company_name, $phrase) != false) {
                array_push($found_clients , $client);
            }
        }
        switch($dataType) {
            case "json":
                $json = '[';
                foreach($found_clients as $key => $client) {
                    $json .= '{"name": "' . $client->company_name . '", "id": "'.$client->id.'"}';
                    if ($client !== end($found_clients)) {
                        $json .= ',';   
                    }
                }
                $json .= ']';
                header('Content-Type: application/json');
                echo $json;
            break;
        }

        /*if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            $this->data['charges'] = $data;
        }
        else
        {
            $this->data['charges'] = [];
        }
        return $this->data;*/
    }
    public function reminder()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Reminder')));
        $meta = array('page_title' => lang('Create Reminder'), 'bc' => $bc, 'page_name' => 'Create Reminder');
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Create Reminder', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/reminder_document.php', $meta, $this->data);
    }

    public function edit_reminder($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Reminder')));
        $meta = array('page_title' => lang('Edit Reminder'), 'bc' => $bc, 'page_name' => 'Edit Reminder');

        $this->data['document_reminder'] = $this->document_model->get_reminder_document($id);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Edit Reminder - '.$this->data['document_reminder'][0]->reminder_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/reminder_document.php', $meta, $this->data);
    }

    public function master()
    {
        $bc = array(array('link' => '#', 'page' => lang('Create Master')));
        $meta = array('page_title' => lang('Create Master'), 'bc' => $bc, 'page_name' => 'Create Master');
        
        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Create Master', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/master_document.php', $meta, $this->data);
    }

    public function edit_master($id)
    {
        $bc = array(array('link' => '#', 'page' => lang('Edit Master')));
        $meta = array('page_title' => lang('Edit Master'), 'bc' => $bc, 'page_name' => 'Edit Master');

        $this->data['document_master'] = $this->document_model->get_document_master($id);

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Documents', base_url('documents'));
        $this->mybreadcrumb->add('Edit Master - '.$this->data['document_master'][0]->document_name.'', base_url());

        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('document/master_document.php', $meta, $this->data);
    }

    public function get_billing_info_service()
    {
/*        $service = $_POST['service'];
        $company_code = $_POST['company_code'];*/

        $ci =& get_instance();

        $query = "select * from triggered_by";

        /*$selected_query = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from client_billing_info AS B WHERE company_code = '".$company_code."' AND A.id = B.service)";*/

        $result = $ci->db->query($query);
        //$selected_result = $ci->db->query($selected_query);
        
        //echo json_encode($result->result_array());
        $result = $result->result_array();
        //$selected_result = $selected_result->result_array();
/*
        if (count($selected_result) == 0) {
            $selected_querys = "select A.id from billing_info_service AS A WHERE EXISTS (SELECT service from billing_template AS B WHERE A.id = B.service)";

            $selected_result = $ci->db->query($selected_querys);

            $selected_result = $selected_result->result_array();
        }*/

        if(!$result) {
          throw new exception("Triggered by not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['triggered_by'];
        }

        /*$selected_res = array();
        foreach($selected_result as $key => $row) {
            $selected_res[$key] = $row['id'];
        }*/

        //$ci =& get_instance();
        if($service != "")
        {
            $select_service = $service;
        }
        else
        {
            $select_service = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All triggered by fetched successfully.", 'result'=>$res, 'selected_service'=>$select_service);

        echo json_encode($data);
    }

    public function get_reminder_tag()
    {
        $ci =& get_instance();

        $query = "select * from reminder_tag";

        $result = $ci->db->query($query);

        $result = $result->result_array();

        if(!$result) {
          throw new exception("Reminder Tag not found.");
        }

        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['reminder_tag_name'];
        }


        if($service != "")
        {
            $select_service = $service;
        }
        else
        {
            $select_service = null;
        }
        

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All reminder tag fetched successfully.", 'result'=>$res, 'selected_service'=>$select_service);

        echo json_encode($data);
    }

    public function get_toggle()
    {
        $this->db->select('*');
        $this->db->from('toggles');
        $query = $this->db->get(); 

        if ($query->num_rows() > 0) {
            foreach (($query->result()) as $row) {
                $data[] = $row;
            }
            echo json_encode($data);
        }
    }

    public function add_pending_document()
    {
        $this->form_validation->set_rules('client_name', 'Client Name', 'required');
        $this->form_validation->set_rules('document_name', 'Document Name', 'required');
        if(isset($_POST['document_transaction_date']))
        {
            $this->form_validation->set_rules('document_transaction_date', 'Transaction Date', 'required');
        }
        $this->form_validation->set_rules('pending_document_content', 'Document Content', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Create Document')));
            $meta = array('page_title' => lang('Create Document'), 'bc' => $bc, 'page_name' => 'Create Document');

            $arr = array(
                'client_name' => strip_tags(form_error('client_name')),
                'document_name' => strip_tags(form_error('document_name')),
                'document_transaction_date' => strip_tags(form_error('document_transaction_date')),
                'pending_document_content' => strip_tags(form_error('pending_document_content')),

                //'url' => strip_tags(form_error('url')),
            );
            //$this->page_construct('addpersonprofile.php', $meta, $this->data);


            echo json_encode(array("Status" => 0, 'message' => 'Please complete all required field', 'title' => 'Error', "error" => $arr));
        }
        else
        {   
            // $this->db->select('id, company_name');
            // $this->db->from('client');
            // $this->db->where('firm_id', $this->session->userdata('firm_id'));
            // $this->db->where('company_name', $_POST['client_name']);
            // $clients = $this->db->get(); 

            if(strpos($_POST['pending_document_id'], '/trans') !== false)
            {   
                // $this->db->select('id, company_name');
                // $this->db->from('transaction_client');
                // $this->db->where('firm_id', $this->session->userdata('firm_id'));
                // $this->db->where('company_name', $_POST['client_name']);
                // $transaction_clients = $this->db->get(); 
                $pending_document_id = str_replace('/trans', "", $_POST['pending_document_id']);

                // if ($transaction_clients->num_rows())
                // { 
                    $data['client_id']=$_POST['client_id'];
                    $data['firm_id']=$this->session->userdata('firm_id');
                    $data['officer_id']="";
                    $data['document_name']=$_POST['document_name'];
                    $data['document_date_checkbox']=$_POST['hidden_document_date_checkbox'];
                    if(isset($_POST['document_transaction_date']))
                    {
                        $data['transaction_date']=$_POST['document_transaction_date'];
                    }
                    else
                    {
                        $data['transaction_date']="";
                    }
                    //$data['received_on']="";
                    $data['triggered_by']="";
                    $data['content'] = $_POST["pending_document_content"];
                    $data['created_by']=$this->session->userdata('user_id');

                    $q = $this->db->get_where("transaction_pending_documents", array("id" => $pending_document_id));

                    if (!$q->num_rows())
                    {               
                        $this->db->insert("transaction_pending_documents",$data);
                        /*$insert_client_charge_id = $this->db->insert_id();
                        $this->create_invoice("change_charges", $_POST["company_code"]);*/
                    } 
                    else 
                    {   
                        $this->db->update(" transaction_pending_documents",$data,array("id" => $pending_document_id));     
                    }
                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
                // }
                // else
                // {
                //     echo json_encode(array("Status" => 2, 'message' => 'Please select the correct company name.', 'title' => 'Error'));
                // }
            }
            else
            { 

                $data['client_id']=$_POST['client_id'];
                $data['firm_id']=$this->session->userdata('firm_id');
                $data['officer_id']="";
                $data['document_name']=$_POST['document_name'];
                $data['document_date_checkbox']=$_POST['hidden_document_date_checkbox'];
                if(isset($_POST['document_transaction_date']))
                {
                    $data['transaction_date']=$_POST['document_transaction_date'];
                }
                else
                {
                    $data['transaction_date']="";
                }
                $data['received_on']="";
                $data['triggered_by']="";
                $data['content'] = $_POST["pending_document_content"];
                $data['created_by']=$this->session->userdata('user_id');

                $q = $this->db->get_where("pending_documents", array("id" => $_POST['pending_document_id']));

                if (!$q->num_rows())
                {               
                    $this->db->insert("pending_documents",$data);
                    /*$insert_client_charge_id = $this->db->insert_id();
                    $this->create_invoice("change_charges", $_POST["company_code"]);*/
                } 
                else 
                {   
                    $this->db->update("pending_documents",$data,array("id" => $_POST['pending_document_id']));     
                }
                echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
            }
            
        }
    }

    public function add_document_toggle()
    {
        //echo json_decode($_POST);

        $this->form_validation->set_rules('document_name', 'Document Name', 'required');
        $this->form_validation->set_rules('document_content', 'Document Content', 'required');

        if ($this->form_validation->run() == FALSE || $_POST['service'] == "0")
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Master')));
            $meta = array('page_title' => lang('Master'), 'bc' => $bc, 'page_name' => 'Master');

            if($_POST['service'] == "0")
            {
                //echo (validation_errors());
                $validate_service = "The Triggered By field is required.";
                //form_error('company_type') = $validate_company_type;
                //$this->form_validation->set_message('company_type', $validate_company_type);
            }
            else
            {
                $validate_service = "";
            }

            $arr = array(
                'document_name' => strip_tags(form_error('document_name')),
                'triggered_by' => $validate_service,
                'document_content' => strip_tags(form_error('document_content')),

                //'url' => strip_tags(form_error('url')),
            );
            //$this->page_construct('addpersonprofile.php', $meta, $this->data);


            echo json_encode(array("Status" => 0, "error" => $arr));
        }
        else
        {   
            $data['firm_id']=$this->session->userdata('firm_id');
            $data['document_name']=$_POST['document_name'];
            $data['triggered_by']=$_POST['triggered_by'];
            $data['document_content']=$_POST['document_content'];

            $q = $this->db->get_where("document_master", array("id" => $_POST['document_master_id']));

            if (!$q->num_rows())
            {               
                $this->db->insert("document_master",$data);
                /*$insert_client_charge_id = $this->db->insert_id();
                $this->create_invoice("change_charges", $_POST["company_code"]);*/
            } 
            else 
            {   
                $this->db->update("document_master",$data,array("id" => $_POST['document_master_id']));     
            }
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function add_document_reminder()
    {
        $this->form_validation->set_rules('reminder_name', 'Reminder Name', 'required');
        $this->form_validation->set_rules('before_year_end', 'Before Year End', 'numeric|max_length[4]');
        $this->form_validation->set_rules('before_due_date', 'Before Due Date', 'numeric|max_length[4]');
        //$this->form_validation->set_rules('send_to', 'Send To', 'required');
        //$this->form_validation->set_rules('start_on', 'Start On', 'required');
        $this->form_validation->set_rules('reminder_document_content', 'Document Content', 'required');

        if ($this->form_validation->run() == FALSE || $_POST['reminder_tag'] == 0)
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $bc = array(array('link' => '#', 'page' => lang('Reminder')));
            $meta = array('page_title' => lang('Reminder'), 'bc' => $bc, 'page_name' => 'Reminder');

            if($_POST['reminder_tag'] == 0)
            {
                $validate_reminder_tag = "The Reminder Tag field is required.";
            }
            else
            {
                $validate_reminder_tag = "";
            }

            $arr = array(
                'reminder_tag' => $validate_reminder_tag,
                'reminder_name' => strip_tags(form_error('reminder_name')),
                'before_year_end' => strip_tags(form_error('before_year_end')),
                'before_due_date' => strip_tags(form_error('before_due_date')),
                //'send_to' => strip_tags(form_error('send_to')),
                'start_on' => strip_tags(form_error('start_on')),
                'reminder_document_content' => strip_tags(form_error('reminder_document_content')),
                //'url' => strip_tags(form_error('url')),
            );
            //$this->page_construct('addpersonprofile.php', $meta, $this->data);


            echo json_encode(array("Status" => 0, "error" => $arr));
        }
        else
        {
            $data['firm_id']=$this->session->userdata('firm_id');
            $data['reminder_tag_id']=$_POST['reminder_tag'];
            $data['reminder_name']=$_POST['reminder_name'];
            $data['active']=$_POST['hidden_document_active_checkbox'];
            $data['before_year_end']=$_POST['before_year_end'];
            $data['before_due_date']=$_POST['before_due_date'];
            //$data['send_to']=$_POST['send_to'];
            $data['start_on']=$_POST['start_on'];
            $data['document_content']=$_POST['reminder_document_content'];

            $q = $this->db->get_where("document_reminder", array("id" => $_POST['document_reminder_id']));

            if (!$q->num_rows())
            {               
                $this->db->insert("document_reminder",$data);
                /*$insert_client_charge_id = $this->db->insert_id();
                $this->create_invoice("change_charges", $_POST["company_code"]);*/
            } 
            else 
            {   
                $this->db->update("document_reminder",$data,array("id" => $_POST['document_reminder_id']));     
            }
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

}
