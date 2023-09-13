<?php

//require_once("dbconfig.php");
class Companytype extends CI_Controller {
   
    public static $data;

    function __construct() {
      parent::__construct();
      $this->load->library(array('session', 'encryption'));
      $this->load->helper(array('form', 'url'));
      $this->load->database();
    }
 
    // Fetch all Company Type list
    public static function getCompanytype() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, company_type FROM company_type";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Company type not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['company_type'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_company_type = $ci->session->userdata('company_type');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Company Type fetched successfully.", 'result'=>$res, 'selected_company_type'=>$select_company_type);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public static function getAcquriedBy() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, acquried_by FROM acquried_by";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Acquried By not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['acquried_by'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_acquried_by = $ci->session->userdata('acquried_by');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Acquried By fetched successfully.", 'result'=>$res, 'selected_acquried_by'=>$select_acquried_by);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    public static function getStatus() {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        $query = "SELECT id, status FROM status";

        $result = $ci->db->query($query);
        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
          throw new exception("Status not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['status'];
        }
        //$res = json_decode($res);
        $ci =& get_instance();
        $select_status = $ci->session->userdata('status');

        /*if($nationalityId != "null")
        {
        $select_nationality = $nationalityId;
        }*/
        /*else
        {
        $select_nationality = "null";
        }*/
        //$select_country = $select_country->result_array();
        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Status fetched successfully.", 'result'=>$res, 'selected_status'=>$select_status);
        /*} catch (Exception $e) {
        $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
        }*/ /*finally {
        echo json_encode($data);
        //return $data;

        }*/
        echo json_encode($data);
    }

    // Fetch all Chairman list
    public static function getChairman() 
    {

        //try {
        //echo $nationalityId;
        $ci =& get_instance();

        /*$select =   array(
                  'allotment.*', 'officer.identification_no', 'officer.name', 'officer_company.register_no', 'officer_company.company_name',' share_capital.id AS share_capital_id', 'share_capital.class_id', 'share_capital.other_class', 'share_capital.currency_id', 'class.sharetype', 'currencies.currency'
            );

          $this->db->select($select);
          $this->db->from('allotment');
          $this->db->join("officer",'allotment.officer_id = officer.id','allotment.field_type = officer.field_type', 'left');
          $this->db->join("officer_company",'allotment.officer_id = officer_company.id','allotment.field_type = officer_company.field_type','left');
          $this->db->join("client_member_share_capital AS share_capital",'allotment.client_member_share_capital_id = share_capital.id','left');
          $this->db->join("sharetype AS class",'class.id = share_capital.class_id','left');
          $this->db->join("currency AS currencies",'currencies.id = share_capital.currency_id','left');

          $result = $this->db->get();*/
        //echo (DATE("Y-m-d",now()));

        $company_code = $_POST["company_code"];
        $current_date = DATE("Y-m-d",now());

        /*$query = 'SELECT allotment.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id AS share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from allotment left join officer on allotment.officer_id = officer.id and allotment.field_type = officer.field_type left join officer_company on allotment.officer_id = officer_company.id and allotment.field_type = officer_company.field_type left join client_member_share_capital AS share_capital on allotment.client_member_share_capital_id = share_capital.id left join sharetype AS class on class.id = share_capital.class_id left join currency AS currencies on currencies.id = share_capital.currency_id where allotment.amount_paid > 0 AND date_format(allotment.created_at, "%Y-%m-%d") = "'.$current_date.'"';*/

        $query = 'select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on member_shares.officer_id = client.id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) > 0 AND sum(member_shares.amount_paid) > 0 AND date_format(member_shares.created_at, "%Y-%m-%d") = "'.$current_date.'"';

 //AND "'.DATE("m/d/y", allotment.created_at).'" = "'.DATE("m/d/y", now()).'"

        //$query = "SELECT id, company_type FROM company_type";
        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {
            /*foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;*/
            $result = $result->result_array();
           // echo (FROM_UNIXTIME($result[0]['created_at']));

            //echo json_encode($result);
            if(!$result) {
              throw new exception("Chairman not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join client on client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND client.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_query = $corp_rep_query->result_array();
                //echo json_encode(count($corp_rep_query));
                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];
                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, t.company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no left join client as t on t.id = corporate_representative.client_id where r.id = '".$row['client_id']."'"); 
                $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' left join client as p on p.company_code = '".$company_code."' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_client_query = $corp_rep_client_query->result_array();
                //echo json_encode($query);
                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];

                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];

                        
                    }
                }
              }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_all_chairman = $ci->session->userdata('transaction_chairman');

            /*if($nationalityId != "null")
            {
            $select_nationality = $nationalityId;
            }*/
            /*else
            {
            $select_nationality = "null";
            }*/
            //$select_country = $select_country->result_array();
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Chairman fetched successfully.", 'result'=>$res, 'selected_chairman'=>$selected_all_chairman);
            /*} catch (Exception $e) {
            $data = array('status'=>'error', 'tp'=>0, 'msg'=>$e->getMessage());
            }*/ /*finally {
            echo json_encode($data);
            //return $data;

            }*/
            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_chairman'=>'');

            echo json_encode($data);
        }
        
        
    }
    // Fetch all Chairman list
    public static function getAllChairman() {

        $ci =& get_instance();

        /*$query = 'SELECT member_shares.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id AS share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital AS share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype AS class on class.id = share_capital.class_id left join currency AS currencies on currencies.id = share_capital.currency_id group by member_shares.officer_id and member_shares.field_type';*/

        $company_code = $_POST["company_code"];

        $query = 'select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on member_shares.officer_id = client.id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) > 0 AND sum(member_shares.amount_paid) > 0';

        $result = $ci->db->query($query);

        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Chairman not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join client on client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND client.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_query = $corp_rep_query->result_array();
                //echo json_encode(count($corp_rep_query));
                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];
                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, t.company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no left join client as t on t.id = corporate_representative.client_id where r.id = '".$row['client_id']."'"); 
                $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' left join client as p on p.company_code = '".$company_code."' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_client_query = $corp_rep_client_query->result_array();
                //echo json_encode($query);
                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];

                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];

                        
                    }
                }
              }
              
            }

            $ci =& get_instance();

/*                    $this->session->set_userdata('director_signature_1', $row['director_signature_1']);
                    $this->session->set_userdata('director_signature_2', $row['director_signature_2']);*/
            $selected_all_chairman = $ci->session->userdata('chairman');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Chairman fetched successfully.", 'result'=>$res, 'selected_all_chairman'=>$selected_all_chairman);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_chairman'=>'');

            echo json_encode($data);
        }
    }

    public static function getDirectorSignature1() {
        $ci =& get_instance();

        $company_code = $_POST['company_code'];

        $query = 'SELECT client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND client_officers.date_of_cessation = "" AND client_officers.company_code ="'.$company_code.'" GROUP BY client_officers.field_type, client_officers.officer_id';

        $result = $ci->db->query($query);

        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 1 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $ci->encryption->decrypt($row['company_name']);
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director1 = $ci->session->userdata('director_signature_1');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 1 fetched successfully.", 'result'=>$res, 'selected_all_director1'=>$selected_all_director1);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director1'=>'');

            echo json_encode($data);
        }
    }

    public static function getTodayDirectorSignature1() {
        $ci =& get_instance();
        $company_code = $_POST['company_code']; 
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND client_officers.date_of_cessation = "" AND client_officers.company_code ="'.$company_code.'" GROUP BY client_officers.field_type, client_officers.officer_id HAVING date_format(client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

        $result = $ci->db->query($query);
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 1 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $ci->encryption->decrypt($row['company_name']);
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director1 = $ci->session->userdata('director_signature_1');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 1 fetched successfully.", 'result'=>$res, 'selected_all_director1'=>$selected_all_director1);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director1'=>'');

            echo json_encode($data);
        }
    }

    public static function getDirectorSignature2() {
        $ci =& get_instance();
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];

        $query = 'SELECT client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND client_officers.date_of_cessation = "" AND client_officers.id != (select alternate_of from client_officers as A where A.company_code = "'.$company_code.'" AND A.id = "'.$director_signature_1_id.'") AND client_officers.company_code ="'.$company_code.'" GROUP BY client_officers.field_type, client_officers.officer_id HAVING client_officers.id != "'.$director_signature_1_id.'"';

        $result = $ci->db->query($query);
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 2 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $ci->encryption->decrypt($row['company_name']);
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director2 = $ci->session->userdata('director_signature_2');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 2 fetched successfully.", 'result'=>$res, 'selected_all_director2'=>$selected_all_director2);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director2'=>'');

            echo json_encode($data);
        }
    }

    public static function getTodayDirectorSignature2() {

        $ci =& get_instance();
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from client_officers left join officer on client_officers.officer_id = officer.id and client_officers.field_type = officer.field_type left join officer_company on client_officers.officer_id = officer_company.id and client_officers.field_type = officer_company.field_type left join client_officers_position on client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND client_officers.date_of_cessation = "" AND client_officers.company_code ="'.$company_code.'" GROUP BY client_officers.field_type, client_officers.officer_id HAVING client_officers.id != "'.$director_signature_1_id.'" AND date_format(client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

        $result = $ci->db->query($query);
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 2 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $ci->encryption->decrypt($row['company_name']);
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director2 = $ci->session->userdata('director_signature_2');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 2 fetched successfully.", 'result'=>$res, 'selected_all_director2'=>$selected_all_director2);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director2'=>'');

            echo json_encode($data);
        }
    }

    public static function getClientName() 
    {
        $ci =& get_instance();

        $query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Client Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {
                    $res[$row['company_code']] = $row['company_name'];
                }
              
            }
            //$res = json_decode($res);
            $ci =& get_instance();
            $selected_client_name = $ci->session->userdata('billing_company_code');
            $ci->session->unset_userdata('billing_company_code');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Client Name fetched successfully.", 'result'=>$res, 'selected_client_name'=>$selected_client_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_client_name'=>'');

            echo json_encode($data);
        }
    }

    public function getCurrency()
    {
        $currency = $_POST['currency'];

        $result = $this->db->query("select * from currency");

        $result = $result->result_array();
        //echo json_encode($result);
        if(!$result) {
            throw new exception("Currency not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['currency'];
        }

        $ci =& get_instance();
        $selected_currency = $ci->session->userdata('billing_currency');
        $ci->session->unset_userdata('billing_currency');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_currency'=>$selected_currency);

        echo json_encode($data);
    }

    // Fetch all Chairman list
    public static function getTransactionChairman() 
    {
        $ci =& get_instance();

        $company_code = $_POST["company_code"];
        $current_date = DATE("Y-m-d",now());

        $query = 'select transaction_member_shares.*, sum(transaction_member_shares.number_of_share) as number_of_share, sum(transaction_member_shares.amount_share) as amount_share, sum(transaction_member_shares.no_of_share_paid) as no_of_share_paid, sum(transaction_member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = "client" left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" GROUP BY transaction_member_shares.field_type, transaction_member_shares.officer_id HAVING sum(transaction_member_shares.number_of_share) > 0 AND sum(transaction_member_shares.amount_paid) > 0 AND date_format(transaction_member_shares.created_at, "%Y-%m-%d") = "'.$current_date.'"';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Chairman not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join transaction_client on transaction_client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND transaction_client.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_query = $corp_rep_query->result_array();
                //echo json_encode(count($corp_rep_query));
                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];
                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, t.company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no left join client as t on t.id = corporate_representative.client_id where r.id = '".$row['client_id']."'"); 
                $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' left join transaction_client as p on p.company_code = '".$company_code."' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_client_query = $corp_rep_client_query->result_array();
                //echo json_encode($row['client_id']);
                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {

                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];

                        
                    }
                }
              }
              
            }

            $ci =& get_instance();
            $selected_all_chairman = $ci->session->userdata('transaction_chairman');
            $ci->session->unset_userdata('transaction_chairman');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Chairman fetched successfully.", 'result'=>$res, 'selected_chairman'=>$selected_all_chairman);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_chairman'=>'');

            echo json_encode($data);
        }
        
        
    }
    // Fetch all Chairman list
    public static function getAllTransactionChairman() {

        $ci =& get_instance();


        $company_code = $_POST["company_code"];

        $query = 'select transaction_member_shares.*, sum(transaction_member_shares.number_of_share) as number_of_share, sum(transaction_member_shares.amount_share) as amount_share, sum(transaction_member_shares.no_of_share_paid) as no_of_share_paid, sum(transaction_member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = "client" left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" GROUP BY transaction_member_shares.field_type, transaction_member_shares.officer_id HAVING sum(transaction_member_shares.number_of_share) > 0 AND sum(transaction_member_shares.amount_paid) > 0';

        $result = $ci->db->query($query);

        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Chairman not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join transaction_client on transaction_client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND transaction_client.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_query = $corp_rep_query->result_array();
                //echo json_encode(count($corp_rep_query));
                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];
                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, t.company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no left join client as t on t.id = corporate_representative.client_id where r.id = '".$row['client_id']."'"); 
                $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name, p.company_name from client as r left join transaction_client as p on p.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                $corp_rep_client_query = $corp_rep_client_query->result_array();
                //echo json_encode($corp_rep_client_query);
                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        // $res[$row['officer_company_id']."-".$row['field_type']] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['company_name'];

                        $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];

                        
                    }
                }
              }
              
            }

            $ci =& get_instance();

/*                    $this->session->set_userdata('director_signature_1', $row['director_signature_1']);
                    $this->session->set_userdata('director_signature_2', $row['director_signature_2']);*/

            $selected_all_chairman = $ci->session->userdata('transaction_chairman');
            $ci->session->unset_userdata('transaction_chairman');
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Chairman fetched successfully.", 'result'=>$res, 'selected_all_chairman'=>$selected_all_chairman);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_chairman'=>'');

            echo json_encode($data);
        }
    }

    public static function getTransactionDirectorSignature1() {

        $ci =& get_instance();
        //echo json_encode($company_code);
        $company_code = $_POST['company_code'];

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 1 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            
            $selected_all_director1 = $ci->session->userdata('transaction_director_signature_1');
            $ci->session->unset_userdata('transaction_director_signature_1');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 1 fetched successfully.", 'result'=>$res, 'selected_all_director1'=>$selected_all_director1);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director1'=>'');

            echo json_encode($data);
        }
    }

    public static function getTodayTransactionDirectorSignature1() {

        $ci =& get_instance();
        //echo json_encode($company_code);
        $company_code = $_POST['company_code']; 
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING date_format(transaction_client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 1 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            
            $selected_all_director1 = $ci->session->userdata('transaction_director_signature_1');
            $ci->session->unset_userdata('transaction_director_signature_1');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 1 fetched successfully.", 'result'=>$res, 'selected_all_director1'=>$selected_all_director1);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director1'=>'');

            echo json_encode($data);
        }
    }

    public static function getTransactionDirectorSignature2() {

        $ci =& get_instance();
        //echo json_encode($company_code);
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.id != (select alternate_of from transaction_client_officers as A where A.company_code = "'.$company_code.'" AND A.id = "'.$director_signature_1_id.'") AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING transaction_client_officers.id != "'.$director_signature_1_id.'"';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 2 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director2 = $ci->session->userdata('transaction_director_signature_2');
            $ci->session->unset_userdata('transaction_director_signature_2');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 2 fetched successfully.", 'result'=>$res, 'selected_all_director2'=>$selected_all_director2);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director2'=>'');

            echo json_encode($data);
        }
    }

    public static function getTodayTransactionDirectorSignature2() {

        $ci =& get_instance();
        //echo json_encode($company_code);
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING transaction_client_officers.id != "'.$director_signature_1_id.'" AND date_format(transaction_client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

        $result = $ci->db->query($query);
        //echo json_encode($result->result_array());
        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();

            if(!$result) {
              throw new exception("Director Signature 2 not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $res[$row['id']] = $row['company_name'];
              }
              
            }

            $ci =& get_instance();
            $selected_all_director2 = $ci->session->userdata('transaction_director_signature_2');
            $ci->session->unset_userdata('transaction_director_signature_2');
            
            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"All Director Signature 2 fetched successfully.", 'result'=>$res, 'selected_all_director2'=>$selected_all_director2);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_all_director2'=>'');

            echo json_encode($data);
        }
    }


}
