<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';
/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller 
{
    public $user_id;
    public $user_group;
	public $data;
	public $meta;
    public $Settings;
	public $autoload = array();
	
	public function __construct() 
	{  
        $this->load->model('Site');

        $this->Settings = $this->Site->get_setting();
        //echo json_encode($this->Settings->site_name);
        $this->loggedIn = $this->sma->logged_in();

        /* Setup user id and user group */
        if ($this->loggedIn) {
            $this->user_id         = $this->ion_auth->get_user_id();
            // $this->user_group_name = $this->ion_auth->get_users_group($this->user_id)->row()->name;
            // $this->Admin           = $this->ion_auth->in_group('admin') ? TRUE : NULL;
            // $this->data['Admin']   = $this->Admin;

            $this->Owner = $this->sma->in_group('owner') ? TRUE : NULL;
            $this->data['Owner'] = $this->Owner;
            $this->Client = $this->sma->in_group('client') ? TRUE : NULL;
            $this->data['Client'] = $this->Client;
            $this->Supplier = $this->sma->in_group('supplier') ? TRUE : NULL;
            $this->data['Supplier'] = $this->Supplier;
            $this->Admin = $this->sma->in_group('admin') ? TRUE : NULL;
            $this->data['Admin'] = $this->Admin;
            $this->Manager = $this->sma->in_group('manager') ? TRUE : NULL;
            $this->data['Manager'] = $this->Manager;
            $this->Bookkeeper = $this->sma->in_group('bookkeeper') ? TRUE : NULL;
            $this->data['Bookkeeper'] = $this->Bookkeeper;
            $this->Individual = $this->sma->user_type('Individual') ? TRUE : NULL;
            $this->data['Individual'] = $this->Individual;
            $this->Designation = $this->sma->get_designation($this->user_id) ? $this->sma->get_designation($this->user_id) : NULL;
            $this->data['Designation'] = $this->Designation;
        }

		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;	
		
		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);	
		
		/* autoload module items */
		$this->load->_autoloader($this->autoload);

		$this->theme = '/views/';

        date_default_timezone_set('Asia/Singapore');    // set timezone

		// $this->data['test'] = "Testing";
	}
	
	public function __get($class) 
	{
		return CI::$APP->$class;
	}

	function page_construct($pageName, $meta, $data) {

        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');

        $meta['ip_address'] = $this->input->ip_address();
        $meta['Owner'] = $data['Owner'];
        $meta['Admin'] = $data['Admin'];
        //$meta['User'] = $data['User'];
        $meta['Client'] = $data['Client'];
        $meta['Manager'] = $data['Manager'];
        $meta['Bookkeeper'] = $data['Bookkeeper'];
        $meta['Individual'] = $data['Individual'];
        $meta['Designation'] = $data['Designation'];
        if(!$this->Owner) 
        {
            $this->db->select('firm.*, user_firm.default_company, user_firm.in_use')
                    ->from('firm')
                    ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                    ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                    ->where('user_firm.in_use = 1');
                        
        }
        else
        {
            $this->db->select('firm.*')
                    ->from('firm')
                    ->where('id = '.$this->session->userdata('firm_id'));
        }
        $files = $this->db->get();
        $file_info = $files->result_array();
        $meta['firm_name'] = $file_info[0]["name"];
        $meta['logo'] = $file_info[0]["file_name"];

        // $meta['logo'] 		= 'assets/logo/logo.png';
        $meta['page_name']	= isset($meta['page_name'])? $meta['page_name']: '';

        $footer['project_name'] = "HRM SYSTEM";

        $this->load->view('header', $meta);
        $this->load->view($pageName, $data);
        $this->load->view('footer', $footer);
    }
}