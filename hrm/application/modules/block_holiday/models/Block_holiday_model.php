<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Block_holiday_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        // $this->load->model('Employment_json_model');
    }

    public function get_holiday_list(){

        $list = $this->db->query('SELECT * FROM payroll_block_holiday WHERE year(holiday_date)='. date("Y") . ' ORDER BY holiday_date');

        return $list->result();
    }

    public function submit_holiday($data){
        $result = $this->db->insert('payroll_block_holiday', $data);    // insert new customer to database
        // $block_holiday_id = $this->db->insert_id();

        return $result;
    }

    public function delete_holiday($holiday_id){
        $result = $this->db->delete('payroll_block_holiday', array('id' => $holiday_id));

        return $result;
    }
}
?>