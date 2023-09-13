<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_setting extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('db_model');
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => 'Settings'));
        $meta = array('page_title' => 'Settings', 'bc' => $bc, 'page_name' => 'Settings');
		
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id = '.$this->session->userdata("user_id"));
        //$this->db->where('group_id = 2');
        $this->db->order_by('id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            $this->data['user_info'] = $data;
        }

        $this->data['jurisdiction_info'] = $this->db_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));
        $this->data['category_info'] = $this->db_model->get_category_info($this->session->userdata("user_admin_code_id"));
        $this->data['payment_voucher_type'] = $this->db_model->get_payment_voucher_type($this->session->userdata("user_admin_code_id"));

        $this->page_construct('admin_setting.php', $meta, $this->data);
    }

    public function add_jurisdiction_info()
    {
        $id = $_POST['jurisdiction_info_id'];
        $data['user_admin_code_id'] = $this->session->userdata("user_admin_code_id");
        $data['code'] = $_POST['code'];
        $data['jurisdiction'] = $_POST['jurisdiction'];

        $q = $this->db->get_where("gst_jurisdiction", array("id" => $id));

        if (!$q->num_rows())
        {
            $check_code = $this->db->get_where("gst_jurisdiction", array("code" => $_POST['code'], "user_admin_code_id" => $data['user_admin_code_id']));

            if (!$check_code->num_rows())
            {
                $check_jurisdiction = $this->db->get_where("gst_jurisdiction", array("jurisdiction" => $_POST['jurisdiction'], "user_admin_code_id" => $data['user_admin_code_id']));

                if (!$check_jurisdiction->num_rows())
                {
                    $this->db->insert("gst_jurisdiction", $data);
                    $insert_jurisdiction_info_id = $this->db->insert_id();

                    $this->save_audit_trail("Settings", "Jurisdiction List", "Jurisdiction is added.");

                    echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_jurisdiction_info_id" => $insert_jurisdiction_info_id));
                }
                else
                {
                    echo json_encode(array("Status" => 2, 'message' => 'Cannot have same Jurisdiction.', 'title' => 'Error'));
                }
            }
            else
            {
                echo json_encode(array("Status" => 2, 'message' => 'Cannot have same Code.', 'title' => 'Error'));
            }

        }
        else
        {
            $this->db->update("gst_jurisdiction", $data, array("id" => $id));

            $this->save_audit_trail("Settings", "Jurisdiction List", "Jurisdiction is edited.");

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function delete_jurisdiction_info ()
    {
        $id = $_POST["jurisdiction_info_id"];
        $data['deleted'] = 1;

        $this->db->update("gst_jurisdiction", $data, array("id" => $id));

        $this->save_audit_trail("Settings", "Jurisdiction List", "Jurisdiction is deleted.");

        echo json_encode(array("Status" => 1));         
    }

    public function get_dropdown_jurisdiction_info ()
    {
        $this->data['dropdown_jurisdiction_info'] = $this->db_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));

        echo json_encode(array("Status" => 1, $this->data));
    }

    public function save_category_list()
    {
        //echo json_encode(json_decode($_POST["delete_category_info"]));
        $category_id = $_POST["category_id"];
        $category_data['user_admin_code_id'] = $this->session->userdata("user_admin_code_id");
        $category_data['category'] = $_POST["category"];
        $gst_category_info_id = $_POST["gst_category_info_id"];
        $jurisdiction = $_POST["jurisdiction"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $rate = $_POST["rate"];
        $deleted_category_info = json_decode($_POST["delete_category_info"]);

        $q = $this->db->get_where("gst_category", array("id" => $category_id));

        if (!$q->num_rows())
        {   
            $this->db->insert("gst_category", $category_data);
            $insert_gst_category_id = $this->db->insert_id();

            $this->save_audit_trail("Settings", "Category List", "Category is added.");
        }
        else
        {
            $this->db->update("gst_category", $category_data, array("id" => $category_id));
            $insert_gst_category_id = $category_id;

            $this->save_audit_trail("Settings", "Category List", "Category is edited.");
        }

        $category_info_length = count($jurisdiction);

        for($t = 0; $t < $category_info_length; $t++)
        {
            $category_info_data['gst_category_id'] = $insert_gst_category_id;
            $category_info_data['jurisdiction_id'] = $jurisdiction[$t];

            $startDateArr = explode("/", $start_date[$t]);
            $newStartDate = $startDateArr[2] . '-' . $startDateArr[1] . '-' . $startDateArr[0];
            $category_info_data['start_date'] = $newStartDate;

            if($end_date[$t] != "")
            {
                $endDateArr = explode("/", $end_date[$t]);
                $newEndDate = $endDateArr[2] . '-' . $endDateArr[1] . '-' . $endDateArr[0];
                $category_info_data['end_date'] = $newEndDate;
            }
            else
            {
                $category_info_data['end_date'] = NULL;
            }

            $category_info_data['rate'] = $rate[$t];

            $gst_category_info_query = $this->db->get_where("gst_category_info", array("id" => $gst_category_info_id[$t]));

            if (!$gst_category_info_query->num_rows())
            {   
                $this->db->insert("gst_category_info", $category_info_data);
            }
            else
            {
                $this->db->update("gst_category_info", $category_info_data, array("id" => $gst_category_info_id[$t]));
            }
        }

        if(count($deleted_category_info) > 0)
        {
            for($y = 0; $y < count($deleted_category_info); $y++)
            {
                $deleted['deleted'] = 1;
                $this->db->update("gst_category_info", $deleted, array("id" => $deleted_category_info[$y]));
            }
        }

        $this->data['category_info'] = $this->db_model->get_category_info($this->session->userdata("user_admin_code_id"));

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', $this->data));
    }

    public function get_edit_category()
    {
        $gst_category_id = $_POST["gst_category_id"];

        $this->data['edit_category_info'] = $this->db_model->get_edit_category_info($gst_category_id);

        echo json_encode(array("Status" => 1, $this->data));

    }

    public function get_gst_category()
    {
        $this->data['dropdown_jurisdiction_info'] = $this->db_model->get_jurisdiction_info($this->session->userdata("user_admin_code_id"));
        $this->data['category_info'] = $this->db_model->get_category_info($this->session->userdata("user_admin_code_id"));

        echo json_encode(array("Status" => 1, $this->data));
    }

    public function sav_company()
    {
		$this->sma->prints_arrays($_POST,$_FILES);
        // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $bc = array(array('link' => '#', 'page' => lang('Setting')));
        // $meta = array('page_title' => lang('Setting'), 'bc' => $bc, 'page_name' => 'Setting');
        // $this->page_construct('setting.php', $meta, $this->data);

    }
	public function save()
	{
		$company['company_name'] = $_POST['company_name'];
		$company['company_uen'] = $_POST['company_uen'];
		$company['company_phone'] = $_POST['company_phone'];
		$company['company_email'] = $_POST['company_email'];
		$company['company_fax'] = $_POST['company_fax'];
		$company['company_postcode'] = $_POST['company_postcode'];
		$company['company_street'] = $_POST['company_street'];
		$company['company_building'] = $_POST['company_building'];
		$company['company_unit'] = $_POST['company_unit'];
        $this->db->where('id', 1);

		$this->db->update('company',$company);
		$this->sma->print_arrays($_POST);
	}

    public function save_payment_voucher_type()
    {
        $payment_voucher_type_id = $_POST["payment_voucher_type_id"];
        $payment_voucher_type_data['type_name'] = $_POST["payment_voucher_type"];

        $q = $this->db->get_where("payment_voucher_type", array("id" => $payment_voucher_type_id));

        if (!$q->num_rows())
        {   
            $this->db->insert("payment_voucher_type", $payment_voucher_type_data);
            $this->save_audit_trail("Settings", "Payment Type", "Payment voucher type is added.");
        }
        else
        {
            $this->db->update("payment_voucher_type", $payment_voucher_type_data, array("id" => $payment_voucher_type_id));
            $this->save_audit_trail("Settings", "Payment Type", "Payment voucher type is edited.");
        }

        $this->data['payment_voucher_type'] = $this->db_model->get_payment_voucher_type($this->session->userdata("user_admin_code_id"));

        echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', $this->data));
    }

    public function get_edit_payment_voucher_type()
    {
        $payment_voucher_type_id = $_POST["payment_voucher_type_id"];

        $this->data['payment_voucher_type_info'] = $this->db_model->get_edit_payment_voucher_type($payment_voucher_type_id);

        echo json_encode(array("Status" => 1, $this->data));
    }

    public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }
}