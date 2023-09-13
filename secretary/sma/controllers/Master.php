<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('master_model');
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        $bc = array(array('link' => '#', 'page' => lang('Master')));
        $meta = array('page_title' => lang('Master'), 'bc' => $bc, 'page_name' => 'Master');
		$this->data['sharetype'] = $this->master_model->get_all_share_type();
		$this->data['currency'] = $this->master_model->get_all_currency();
		$this->data['kolom'] = $this->master_model->get_all_kolom();
		$this->data['citizen'] = $this->master_model->get_all_citizen();
		$this->data['typeofdoc'] = $this->master_model->get_all_typeofdoc();
		$this->data['doccategory'] = $this->master_model->get_all_doccategory();
        $this->page_construct('master.php', $meta, $this->data);

    }
	
	public function save_share(){
		$data['sharetype'] = $this->input->post('sharetype');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_share($data,$id);
		} else {
			$this->master_model->save_share($data);
		}
		redirect('master');
	}
	public function remove_sharetype($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_share($id);
		} 
		redirect('master');
	}
	public function save_currency(){
		$data['currency'] = $this->input->post('currency');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_currency($data,$id);
		} else {
			$this->master_model->save_currency($data);
		}
		redirect('master/#w2-currency');
	}
	public function remove_currency($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_currency($id);
		} 
		redirect('master/#w2-currency');
	}
	public function save_citizen(){
		$data['citizen'] = $this->input->post('citizen');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_citizen($data,$id);
		} else {
			$this->master_model->save_citizen($data);
		}
		redirect('master/#w2-currency');
	}
	public function remove_citizen($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_citizen($id);
		} 
		redirect('master/#w2-citizen');
	}
	public function save_typeofdoc(){
		$data['typeofdoc'] = $this->input->post('typeofdoc');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_typeofdoc($data,$id);
		} else {
			$this->master_model->save_typeofdoc($data);
		}
		redirect('master/#w2-typeofdoc');
	}
	public function remove_typeofdoc($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_typeofdoc($id);
		} 
		redirect('master/#w2-typeofdoc');
	}
	
	public function save_doccategory(){
		$data['doccategory'] = $this->input->post('doccategory');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_doccategory($data,$id);
		} else {
			$this->master_model->save_doccategory($data);
		}
		redirect('master/#w2-doccategory');
	}
	public function remove_doccategory($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_doccategory($id);
		} 
		redirect('master/#w2-typeofdoc');
	}
	
	public function save_service(){
		// $this->sma->print_arrays($_POST);
		$data['service_type'] = $this->input->post('service_type');
		$data['service_name'] = $this->input->post('service_name');
		$data['service_price'] = $this->input->post('service_price');
		$data['field_service'] = implode("|",$this->input->post('field_service'));
		// $data['file0'] = implode("|",$this->input->post('field_service'));
		// foreach
				$config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'txt|doc|docx';
				$config['overwrite'] = TRUE;
				$config['max_filename'] = 25;
				$config['encrypt_name'] = TRUE;
		$this->load->library('upload',$config);
		if ($_FILES['files1']['size'] > 0)
		{
			if (!$this->upload->do_upload('files1')) {
				echo "error";
			}
			$data['field1'] = $this->upload_path.$this->upload->file_name;
			$data['field1_n'] = $_FILES['files1']['name'];
		}
		if ($_FILES['files2']['size'] > 0)
		{
			if (!$this->upload->do_upload('files2')) {
				echo "error";
			}
			$data['field2'] = $this->upload->file_name;
			$data['field2_n'] = $_FILES['files1']['name'];
		}
		if ($_FILES['files3']['size'] > 0)
		{
			if (!$this->upload->do_upload('files3')) { echo "error"; }
			$data['field3'] = $this->upload->file_name;
			$data['field3_n'] = $_FILES['files1']['name'];
		}
		if ($_FILES['files4']['size'] > 0)
		{
			if (!$this->upload->do_upload('files4')) { echo "error"; }
			$data['field4'] = $this->upload->file_name;
			$data['field4_n'] = $_FILES['files1']['name'];
		}
		if ($_FILES['files5']['size'] > 0)
		{
			if (!$this->upload->do_upload('files5')) { echo "error"; }
			$data['field5'] = $this->upload->file_name;
			$data['field5_n'] = $_FILES['files1']['name'];
		}
		if ($_FILES['files6']['size'] > 0)
		{
			if (!$this->upload->do_upload('files6')) { echo "error"; }
			$data['field6'] = $this->upload->file_name;
			$data['field6_n'] = $_FILES['files1']['name'];
		}
		
		// $this->sma->print_arrays($data);
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_service($data,$id);
		} else {
			$this->master_model->save_service($data);
		}
		// redirect('master/#w2-service');
	}
	public function remove_service($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_service($id);
		} 
		redirect('master/#w2-service');
	}
	public function download_sample($id){
		$id = str_replace("%C2%AB","«",$id);
		$id = str_replace("%C2%BB","»",$id);
		print_r($id);
		$this->load->helper('PHPWord');
		// require_once '../../PHPWord.php';
		$PHPWord = new PHPWord();
		// $idtemplate = 0;
		// if (isset($_GET['tmplt'])) $idtemplate = $_GET['tmplt'];
		$extra = "";
		$document ="";
		$extra = "_yes";
		$document = $PHPWord->loadTemplate('template_srtAWA_penawaran_yes.docx');
		
		$document->setValue('Nomor1', $nomor);
		
		$document->save('srt/Surat '.$company_name.'-'.$tipe.'_'.$now[0].'.docx');
		header('location: srt/Surat%20'.$company_name.'-'.$tipe.'_'.$now[0].'.docx');
		// $id =  $this->input->post('id');
		// if(isset($id) && $id != '')
		// {
			// $this->master_model->remove_doccategory($id);
		// } 
		// redirect('master/#w2-typeofdoc');
	}
	
	public function save_kolom(){
		$data['title'] = $this->input->post('title_kolom');
		$data['kode'] = $this->input->post('kode_kolom');
		$id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->edit_kolom($data,$id);
		} else {
			$this->master_model->save_kolom($data);
		}
		redirect('master/#w2-kolom');
	}
	public function remove_kolom($id){
		// $id =  $this->input->post('id');
		if(isset($id) && $id != '')
		{
			$this->master_model->remove_kolom($id);
		} 
		redirect('master/#w2-kolom');
	}
	
}
