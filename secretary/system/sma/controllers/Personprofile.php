<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personprofile extends MY_Controller
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
        $this->load->model('master_model');
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Person')));
        $meta = array('page_title' => lang('Person'), 'bc' => $bc, 'page_name' => 'Person');
		$term = ''; $type = '';
		if (isset($_POST['tipepencarian'])) $type = $_POST['tipepencarian'];
		if (isset($_POST['pencarian'])) $term = $_POST['pencarian'];
		$this->data['person'] = $this->master_model->get_all_person($term,$type);
		// $this->sma->print_arrays($_POST);
        $this->page_construct('personprofile.php', $meta, $this->data);

    }

    public function add()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Add Person Profile')));
        $meta = array('page_title' => lang('Add Person Profile'), 'bc' => $bc, 'page_name' => 'Add Person Profile');
        $this->page_construct('addpersonprofile.php', $meta, $this->data);

    }

    public function edit($gid)
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Edit Person Profile')));
        $meta = array('page_title' => lang('Edit Person Profile'), 'bc' => $bc, 'page_name' => 'Edit Person Profile');
        
		$this->data['citizen'] = $this->master_model->get_all_citizen();
		$q = $this->db->query("select gid,nama,address,alternate_address,nationality,citizen, date_of_birth, phone,email,tipe from officer where gid='".$gid."'");
		if ($q->num_rows() > 0) {            
			$this->data['person'] = $q->result()[0];
        }
        $this->page_construct('addpersonprofile.php', $meta, $this->data);

    }
	
	public function update()
	{
		// $this->sma->print_arrays($_POST);
		$officer = [];
		$officer['gid'] = $_POST['gid'];
		$officer['nama'] = $_POST['nama'];
		$officer['date_of_birth'] = $_POST['date_of_birth'];
		$officer['addresstype'] = $_POST['addresstype'];
		$officer['address'] = '<ZC>'.$_POST['zipcode'].'</ZC><ST>'.$_POST['street'].'</ST><B>'.$_POST['buildingname'].'</B><UN1>'.$_POST['unit_no1'].'</UN1><UN2>'.$_POST['unit_no2'].'</UN2><AA>'.$_POST['alternate_address']."</AA>";
		$officer['addresstype'] = $_POST['addresstype'];
		$officer['citizen'] = $_POST['citizen'];
		$officer['local_fix_line'] = $_POST['local_fix_line'];
		$officer['phone'] = $_POST['phone'];
		$officer['email'] = $_POST['email'];
		// print_r($officer);
		$q = $this->db->query("select gid from officer where gid='".$_POST['oldgid']."'");
		if ($q->num_rows() > 0) {          
			echo "A";
			$this->db->where('gid', $_POST['oldgid']);
			$this->db->update('officer', $officer);
        } else {
			$this->db->insert('officer', $officer);
		}

        redirect("personprofile");
	}
}
