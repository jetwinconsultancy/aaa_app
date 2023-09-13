<?php

class Nationality extends CI_Controller
{

  public static $data;

  function __construct()
  {
    parent::__construct();
    $this->load->library('session');
    $this->load->helper(array('form', 'url'));
    $this->load->database();
  }

  // Fetch all countries list
  public static function getNationality()
  {
    $ci = &get_instance();

    $query = "SELECT id, nationality FROM nationality";

    $result = $ci->db->query($query);
    $result = $result->result_array();

    if (!$result) {
      throw new exception("Country not found.");
    }
    $res = array();
    foreach ($result as $row) {
      $res[$row['id']] = $row['nationality'];
    }

    $ci = &get_instance();
    $select_nationality = $ci->session->userdata('nationality');

    $data = array('status' => 'success', 'tp' => 1, 'msg' => "Nationality fetched successfully.", 'result' => $res, 'selected_nationality' => $select_nationality);

    echo json_encode($data);
  }

  public static function getCompanyNationality()
  {
    $ci = &get_instance();

    $query = "SELECT * FROM company_jurisdiction";

    $result = $ci->db->query($query);
    $result = $result->result_array();

    if (!$result) {
      throw new exception("Country not found.");
    }
    $res = array();
    foreach ($result as $row) {
      if ($row['code'] != "") {
        $res[$row['jurisdiction']] = $row['jurisdiction'] . " (" . $row['code'] . ")";
      } else {
        $res[$row['jurisdiction']] = $row['jurisdiction'];
      }
    }

    $ci = &get_instance();
    $select_company_nationality = $ci->session->userdata('company_nationality');

    $data = array('status' => 'success', 'tp' => 1, 'msg' => "Nationality fetched successfully.", 'result' => $res, 'select_company_nationality' => $select_company_nationality);

    echo json_encode($data);
  }
}
