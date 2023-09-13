<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_backup extends CI_Controller {

	function __construct()
    {
		parent::__construct();
		$this->load->library(array('form_validation', 'session', 'encrypt'));//encrypt
	}

    public function message($to = 'World')
    {
    	echo "Hello {$to}!".PHP_EOL;
    }

    public function backup_database()
    {
        ini_set('memory_limit', '-1');
        $this->load->dbutil();

        $prefs = array(
            //'tables'      => array('client'),
            'format' => 'txt',
            'filename' => 'act_db_backup.sql'
        );
        $back = $this->dbutil->backup($prefs);
        $backup =& $back;
        $db_name = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.txt';
        $save = './files/backups/' . $db_name;

        $db_name_sql = 'db-backup-on-' . date("Y-m-d-H-i-s") . '.sql';
        $save_sql_file = './files/backups/' . $db_name_sql;

        $this->load->helper('file');
        //$encrypted_string = $this->encrypt->encode($backup);
        //write_file($save, $encrypted_string);

        write_file($save_sql_file, $backup);
        //$this->session->set_flashdata('messgae', lang('db_saved'));
        //redirect("system_settings/backups");
    }
}