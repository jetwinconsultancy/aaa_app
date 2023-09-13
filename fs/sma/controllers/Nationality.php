<?php

//require_once("dbconfig.php");
class Nationality extends CI_Controller {
   
   public static $data;

   function __construct() {
      parent::__construct();
      $this->load->library('session');
      $this->load->helper(array('form', 'url'));
      $this->load->database();
   }
 
 // Fetch all countries list
   public static function getNationality() {

     //try {
      //echo $nationalityId;
       $ci =& get_instance();
       
       $query = "SELECT id, nationality FROM nationality";
       
       $result = $ci->db->query($query);
       $result = $result->result_array();
       //echo json_encode($result);
       if(!$result) {
         throw new exception("Country not found.");
       }
       $res = array();
       foreach($result as $row) {
        $res[$row['id']] = $row['nationality'];
       }
       //$res = json_decode($res);
       $ci =& get_instance();
       $select_nationality = $ci->session->userdata('nationality');

       /*if($nationalityId != "null")
       {
          $select_nationality = $nationalityId;
       }*/
       /*else
       {
          $select_nationality = "null";
       }*/
       //$select_country = $select_country->result_array();
       $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Nationality fetched successfully.", 'result'=>$res, 'selected_nationality'=>$select_nationality);
     /*} catch (Exception $e) {
       $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
     }*/ /*finally {
      echo json_encode($data);
        //return $data;

     }*/
     echo json_encode($data);
   }
}
