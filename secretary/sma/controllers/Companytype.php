<?php
class Companytype extends CI_Controller {
   
    public static $data;

    function __construct() {
      parent::__construct();
      $this->load->library(array('encryption', 'session'));
      $this->load->helper(array('form', 'url'));
      $this->load->database();
    }
 
    // Fetch all Company Type list
    public static function getCompanytype() {
        $ci =& get_instance();

        $query = "SELECT id, company_type FROM company_type";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Company type not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['company_type'];
        }

        $ci =& get_instance();
        $select_company_type = $ci->session->userdata('company_type');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Company Type fetched successfully.", 'result'=>$res, 'selected_company_type'=>$select_company_type);

        echo json_encode($data);
    }

    public static function getAcquriedBy() {

        $ci =& get_instance();

        $query = "SELECT id, acquried_by FROM acquried_by";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Acquried By not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['acquried_by'];
        }

        $ci =& get_instance();
        $select_acquried_by = $ci->session->userdata('acquried_by');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Acquried By fetched successfully.", 'result'=>$res, 'selected_acquried_by'=>$select_acquried_by);

        echo json_encode($data);
    }

    public static function getStatus() {

        $ci =& get_instance();

        $query = "SELECT id, status FROM status";

        $result = $ci->db->query($query);
        $result = $result->result_array();

        if(!$result) {
          throw new exception("Status not found.");
        }

        $res = array();
        foreach($result as $row) {
          $res[$row['id']] = $row['status'];
        }

        $ci =& get_instance();
        $select_status = $ci->session->userdata('status');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Status fetched successfully.", 'result'=>$res, 'selected_status'=>$select_status);

        echo json_encode($data);
    }

    // Fetch all Chairman list
    public static function getChairman() 
    {
        $ci =& get_instance();

        $company_code = $_POST["company_code"];
        $current_date = DATE("Y-m-d",now());

        $client_query = $ci->db->query('select * from client where company_code = "'.$company_code.'"');
        $client_query = $client_query->result_array();
        if(count($client_query) > 0)
        {
            $company_name = $ci->encryption->decrypt($client_query[0]["company_name"]);
        }
        else
        {
            $company_name = null;
        }

        $query = 'select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on member_shares.officer_id = client.id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) > 0 AND sum(member_shares.amount_paid) > 0 AND date_format(member_shares.created_at, "%Y-%m-%d") = "'.$current_date.'"';

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
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                // $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join client on client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND client.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_query = $corp_rep_query->result_array();

                // if(count($corp_rep_query) != 0)
                // {
                //     foreach($corp_rep_query as $corp_rep_row) {
                //         $corp_rep_row['officer_company_name'] = $this->encryption->decrypt($corp_rep_row['officer_company_name']);
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                //     }
                // }
                $select_corp_rep_query = $ci->db->query("select officer_company.company_name as officer_company_name, officer_company.register_no from officer_company where officer_company.id = '".$row['officer_company_id']."'");

                $select_corp_rep_query = $select_corp_rep_query->result_array();

                $corp_rep_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$ci->encryption->decrypt($select_corp_rep_query[0]["register_no"]).'"');
                $corp_rep_query = $corp_rep_query->result_array();

                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_query[0]['officer_company_name'] = $ci->encryption->decrypt($select_corp_rep_query[0]['officer_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_query[0]['officer_company_name'];
                        }
                    }
                }
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' left join client as p on p.company_code = '".$company_code."' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_client_query = $corp_rep_client_query->result_array();

                // if(count($corp_rep_client_query) != 0)
                // {
                //     foreach($corp_rep_client_query as $corp_rep_row) {
                //         $corp_rep_row['name_of_corp_rep'] = $this->encryption->decrypt($corp_rep_row['name_of_corp_rep']);
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];
                //     }
                // }
                $select_corp_rep_client_query = $ci->db->query("select r.company_name as client_company_name, r.registration_no from client as r where r.id = '".$row['client_id']."'"); 
                $select_corp_rep_client_query = $select_corp_rep_client_query->result_array();
                
                $corp_rep_client_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$ci->encryption->decrypt($select_corp_rep_client_query[0]["registration_no"]).'"');
                $corp_rep_client_query = $corp_rep_client_query->result_array();

                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_client_query[0]['client_company_name'] = $ci->encryption->decrypt($select_corp_rep_client_query[0]['client_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_client_query[0]['client_company_name'];
                        }
                    }
                }
              }
              
            }

            $ci =& get_instance();
            $selected_all_chairman = $ci->session->userdata('transaction_chairman');

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
    public static function getAllChairman() {

        $ci =& get_instance();

        $company_code = $_POST["company_code"];

        $client_query = $ci->db->query('select * from client where company_code = "'.$company_code.'"');
        $client_query = $client_query->result_array();
        if(count($client_query) > 0)
        {
            $company_name = $ci->encryption->decrypt($client_query[0]["company_name"]);
        }
        else
        {
            $company_name = null;
        }
        
        $query = 'select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client on member_shares.officer_id = client.id and member_shares.field_type = "client" left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id HAVING sum(member_shares.number_of_share) > 0 AND sum(member_shares.amount_paid) > 0';

        $result = $ci->db->query($query);

        if ($result->num_rows() > 0) 
        {
            $result = $result->result_array();
            //echo json_encode($result);
            if(!$result) {
              throw new exception("Chairman not found.");
            }

            $res = array();
            foreach($result as $row) {
              if($row['name'] != null)
              {
                $row['name'] = $ci->encryption->decrypt($row['name']);
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $select_corp_rep_query = $ci->db->query("select officer_company.company_name as officer_company_name, officer_company.register_no from officer_company where officer_company.id = '".$row['officer_company_id']."'");

                $select_corp_rep_query = $select_corp_rep_query->result_array();

                $corp_rep_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$ci->encryption->decrypt($select_corp_rep_query[0]["register_no"]).'"');
                $corp_rep_query = $corp_rep_query->result_array();

                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_query[0]['officer_company_name'] = $ci->encryption->decrypt($select_corp_rep_query[0]['officer_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_query[0]['officer_company_name'];
                        }
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                $select_corp_rep_client_query = $ci->db->query("select r.company_name as client_company_name, r.registration_no from client as r where r.id = '".$row['client_id']."'"); 
                $select_corp_rep_client_query = $select_corp_rep_client_query->result_array();
                
                $corp_rep_client_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$ci->encryption->decrypt($select_corp_rep_client_query[0]["registration_no"]).'"');
                $corp_rep_client_query = $corp_rep_client_query->result_array();

                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_client_query[0]['client_company_name'] = $ci->encryption->decrypt($select_corp_rep_client_query[0]['client_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_client_query[0]['client_company_name'];
                        }
                    }
                }
              }
            }

            $ci =& get_instance();

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

    public static function getUserName()
    {
        $ci =& get_instance();

        $query = 'SELECT users.id, users.last_name, users.first_name FROM users left join user_firm as a on a.user_id = "'.$ci->session->userdata("user_id").'" left join user_firm as b on b.firm_id = a.firm_id where b.user_id = users.id AND users.id != 1 AND users.user_deleted = 0 AND users.active = 1 GROUP BY users.id';

        $result = $ci->db->query($query);
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Users not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['first_name'] != null)
                {
                    $res[$row['id']] = $row['last_name']." ".$row['first_name'];
                }
            }
            if($_SESSION['group_id'] != 2 && $_SESSION['group_id'] != 5 && $_SESSION['group_id'] != 6)
            {
                $ci =& get_instance();
                $selected_user_name = $ci->session->userdata('claim_user_id');
                if($ci->session->userdata("user_id") != $selected_user_name && $selected_user_name != null)
                {
                    $selected_user_name = $selected_user_name;
                }
                else
                {
                    $selected_user_name = $ci->session->userdata("user_id");
                }
            }
            else
            {
                $ci =& get_instance();
                $selected_user_name = $ci->session->userdata('claim_user_id');
                $ci->session->unset_userdata('claim_user_id');
            }

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"User fetched successfully.", 'result'=>$res, 'selected_user_name'=>$selected_user_name, 'group_id'=>$_SESSION['group_id']);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_vendor_name'=>'', 'group_id' => '');

            echo json_encode($data);
        }
    }

    public static function getVendorName()
    {
        $ci =& get_instance();
        $query = 'SELECT vendor_info.id, vendor_info.supplier_code, vendor_info.company_name FROM vendor_info left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where user_firm.firm_id = vendor_info.firm_id AND deleted != 1';

        $result = $ci->db->query($query);
        if ($result->num_rows() > 0) 
        {

            $result = $result->result_array();

            if(!$result) {
              throw new exception("Vendor Name not found.");
            }

            $res = array();
            foreach($result as $row) {
                if($row['company_name'] != null)
                {
                    $res[$row['supplier_code']] = $row['company_name'];
                }
              
            }
            $ci =& get_instance();
            $selected_vendor_name = $ci->session->userdata('payment_voucher_supplier_code');
            $ci->session->unset_userdata('payment_voucher_supplier_code');

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Vendor Name fetched successfully.", 'result'=>$res, 'selected_vendor_name'=>$selected_vendor_name);

            echo json_encode($data);
        }
        else
        { 
            $res = array();

            $data = array('status'=>'success', 'tp'=>1, 'msg'=>"No data can be selected.", 'result'=>$res, 'selected_vendor_name'=>'');

            echo json_encode($data);
        }
    }

    public static function getClientName() 
    {
        $ci =& get_instance();

        $query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        $result = $ci->db->query($query);
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
                    $res[$row['company_code']] = $ci->encryption->decrypt($row['company_name']);
                }
              
            }
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

    public function get_payment_receipt_type()
    {
        $ci =& get_instance();
        $result = $ci->db->query("select payment_receipt_type.* from payment_receipt_type");

        $result = $result->result_array();
        if(!$result) {
            throw new exception("Type not found.");
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['type_name'];
        }

        $client_query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        $client_result = $ci->db->query($client_query);

        if ($client_result->num_rows() > 0) 
        {

            $client_result = $client_result->result_array();

            if(!$client_result) {
              throw new exception("Client Name not found.");
            }

            $client_res = array();
            foreach($client_result as $client_row) {
                if($client_row['company_name'] != null)
                {
                    $client_res[$client_row['company_code']] = $client_row['company_name'];
                }
              
            }
        }
        else
        {
            $client_res = null;
        }

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Type fetched successfully.", 'result'=>$res, "client_result"=>$client_res);

        echo json_encode($data);
    }

    public function get_payment_voucher_type()
    {
        $ci =& get_instance();
        $result = $ci->db->query("select payment_voucher_type.* from payment_voucher_type where deleted = 0");

        $result = $result->result_array();
        // if(!$result) {
        //     throw new exception("Type not found.");
        // }
        // $res = array();
        // foreach($result as $row) {
        //     $res[$row['id']] = $row['type_name'];
        // }

        $client_query = 'SELECT client.id, client.company_code, client.company_name FROM client left join user_firm on user_id = "'.$ci->session->userdata("user_id").'" where user_firm.firm_id = client.firm_id AND deleted != 1';

        $client_result = $ci->db->query($client_query);
        if ($client_result->num_rows() > 0) 
        {

            $client_result = $client_result->result_array();

            if(!$client_result) {
              throw new exception("Client Name not found.");
            }

            $client_res = array();
            foreach($client_result as $client_row) {
                if($client_row['company_name'] != null)
                {
                    $client_res[$client_row['company_code']] = $ci->encryption->decrypt($client_row['company_name']);
                }
            }
        }
        else
        {
            $client_res = null;
        }

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Type fetched successfully.", 'result'=>$result, "client_result"=>$client_res);

        echo json_encode($data);
    }

    public function getBankAcc()
    {
        $result = $this->db->query("select bank_info.*, currency.currency as currency_name from bank_info left join currency on currency.id = bank_info.currency where firm_id = '".$this->session->userdata('firm_id')."'");

        $result = $result->result_array();
        if(!$result) {
            //throw new exception("BankAcc not found.");
            echo false;
        }
        $res = array();
        foreach($result as $row) {
            $res[$row['id']] = $row['banker']." (".$row['currency_name'].")";
        }

        $ci =& get_instance();
        $selected_bank_acc = $ci->session->userdata('payment_voucher_bank_acc_id');
        $ci->session->unset_userdata('payment_voucher_bank_acc_id');

        $data = array('status'=>'success', 'tp'=>1, 'msg'=>"Currency fetched successfully.", 'result'=>$res, 'selected_bank_acc'=>$selected_bank_acc);

        echo json_encode($data);
    }

    public function getCurrency()
    {
        $currency = isset($_POST['currency'])?$_POST['currency']:'';

        $result = $this->db->query("select * from currency");

        $result = $result->result_array();
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

        $client_query = $ci->db->query('select * from transaction_client where company_code = "'.$company_code.'"');
        $client_query = $client_query->result_array();
        if(count($client_query) > 0)
        {
            $company_name = $this->encryption->decrypt($client_query[0]["company_name"]);
        }
        else
        {
            $company_name = null;
        }
        

        $query = 'select transaction_member_shares.*, sum(transaction_member_shares.number_of_share) as number_of_share, sum(transaction_member_shares.amount_share) as amount_share, sum(transaction_member_shares.no_of_share_paid) as no_of_share_paid, sum(transaction_member_shares.amount_paid) as amount_paid, officer.id as officer_id, officer.identification_no, officer.name, officer_company.id as officer_company_id, officer_company.register_no, officer_company.company_name, client.id as client_id, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = "client" left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" GROUP BY transaction_member_shares.field_type, transaction_member_shares.officer_id HAVING sum(transaction_member_shares.number_of_share) > 0 AND sum(transaction_member_shares.amount_paid) > 0 AND date_format(transaction_member_shares.created_at, "%Y-%m-%d") = "'.$current_date.'"';

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
                $row['name'] = $this->encryption->decrypt( $row['name']);
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                // $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join transaction_client on transaction_client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND transaction_client.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_query = $corp_rep_query->result_array();

                // if(count($corp_rep_query) != 0)
                // {
                //     foreach($corp_rep_query as $corp_rep_row) {
                //         $corp_rep_row['officer_company_name'] = $this->encryption->decrypt($corp_rep_row['officer_company_name']);
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                //     }
                // }
                $select_corp_rep_query = $ci->db->query("select officer_company.company_name as officer_company_name, officer_company.register_no from officer_company where officer_company.id = '".$row['officer_company_id']."'");

                $select_corp_rep_query = $select_corp_rep_query->result_array();

                $corp_rep_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$this->encryption->decrypt($select_corp_rep_query[0]["register_no"]).'"');
                $corp_rep_query = $corp_rep_query->result_array();

                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_query[0]['officer_company_name'] = $this->encryption->decrypt($select_corp_rep_query[0]['officer_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_query[0]['officer_company_name'];
                        }
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name from client as r left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' left join transaction_client as p on p.company_code = '".$company_code."' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_client_query = $corp_rep_client_query->result_array();

                // if(count($corp_rep_client_query) != 0)
                // {
                //     foreach($corp_rep_client_query as $corp_rep_row) 
                //     {
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];
                //     }
                // }
                $select_corp_rep_client_query = $ci->db->query("select r.company_name as client_company_name, r.registration_no from client as r where r.id = '".$row['client_id']."'"); 
                $select_corp_rep_client_query = $select_corp_rep_client_query->result_array();
                
                $corp_rep_client_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$this->encryption->decrypt($select_corp_rep_client_query[0]["registration_no"]).'"');
                $corp_rep_client_query = $corp_rep_client_query->result_array();

                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_client_query[0]['client_company_name'] = $this->encryption->decrypt($select_corp_rep_client_query[0]['client_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_client_query[0]['client_company_name'];
                        }
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
        $client_query = $ci->db->query('select * from transaction_client where company_code = "'.$company_code.'"');
        $client_query = $client_query->result_array();
        if(count($client_query) > 0)
        {
            $company_name = $this->encryption->decrypt($client_query[0]["company_name"]);
        }
        else
        {
            $company_name = null;
        }

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
                $row['name'] = $this->encryption->decrypt( $row['name']);
                $res[$row['officer_id']."-".$row['field_type']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                // $corp_rep_query = $ci->db->query("select corporate_representative.*, officer_company.company_name as officer_company_name from officer_company left join transaction_client on transaction_client.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = officer_company.register_no AND corporate_representative.cessation_date = '' where officer_company.id = '".$row['officer_company_id']."' AND transaction_client.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_query = $corp_rep_query->result_array();
                // //echo json_encode(count($corp_rep_query));
                // if(count($corp_rep_query) != 0)
                // {
                //     foreach($corp_rep_query as $corp_rep_row) {
                //         $corp_rep_row['officer_company_name'] = $this->encryption->decrypt($corp_rep_row['officer_company_name']);
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['officer_company_name'];
                //     }
                // }
                $select_corp_rep_query = $ci->db->query("select officer_company.company_name as officer_company_name, officer_company.register_no from officer_company where officer_company.id = '".$row['officer_company_id']."'");

                $select_corp_rep_query = $select_corp_rep_query->result_array();

                $corp_rep_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$this->encryption->decrypt($select_corp_rep_query[0]["register_no"]).'"');
                $corp_rep_query = $corp_rep_query->result_array();

                if(count($corp_rep_query) != 0)
                {
                    foreach($corp_rep_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_query[0]['officer_company_name'] = $this->encryption->decrypt($select_corp_rep_query[0]['officer_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_query[0]['officer_company_name'];
                        }
                    }
                }
                
              }
              else if($row['client_company_name'] != null)
              {
                // $corp_rep_client_query = $ci->db->query("select corporate_representative.*, r.company_name as client_company_name, p.company_name from client as r left join transaction_client as p on p.company_code = '".$company_code."' left join corporate_representative on corporate_representative.registration_no = r.registration_no AND corporate_representative.cessation_date = '' where r.id = '".$row['client_id']."' AND p.company_name = corporate_representative.subsidiary_name"); 

                // $corp_rep_client_query = $corp_rep_client_query->result_array();
                // if(count($corp_rep_client_query) != 0)
                // {
                //     foreach($corp_rep_client_query as $corp_rep_row) {
                //         $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$corp_rep_row['client_company_name'];
                //     }
                // }
                $select_corp_rep_client_query = $ci->db->query("select r.company_name as client_company_name, r.registration_no from client as r where r.id = '".$row['client_id']."'"); 
                $select_corp_rep_client_query = $select_corp_rep_client_query->result_array();
                
                $corp_rep_client_query = $ci->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" AND registration_no = "'.$this->encryption->decrypt($select_corp_rep_client_query[0]["registration_no"]).'"');
                $corp_rep_client_query = $corp_rep_client_query->result_array();

                if(count($corp_rep_client_query) != 0)
                {
                    foreach($corp_rep_client_query as $corp_rep_row) {
                        if($corp_rep_row['subsidiary_name'] == $company_name)
                        {
                            $select_corp_rep_client_query[0]['client_company_name'] = $this->encryption->decrypt($select_corp_rep_client_query[0]['client_company_name']);
                            $res[$corp_rep_row["id"]."-corp_rep"] = $corp_rep_row["name_of_corp_rep"]." - ".$select_corp_rep_client_query[0]['client_company_name'];
                        }
                    }
                }
              }
              
            }

            $ci =& get_instance();

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
        $company_code = $_POST['company_code'];

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id';

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
                $row['name'] = $this->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $this->encryption->decrypt($row['company_name']);
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
        $company_code = $_POST['company_code']; 
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING date_format(transaction_client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

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
                $row['name'] = $this->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $this->encryption->decrypt($row['company_name']);
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
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.id != (select alternate_of from transaction_client_officers as A where A.company_code = "'.$company_code.'" AND A.id = "'.$director_signature_1_id.'") AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING transaction_client_officers.id != "'.$director_signature_1_id.'"';

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
                $row['name'] = $this->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $this->encryption->decrypt($row['company_name']);
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
        $company_code = $_POST['company_code'];
        $director_signature_1_id = $_POST['director_signature_1_id'];
        $current_date = DATE("Y-m-d",now());

        $query = 'SELECT transaction_client_officers.*, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client_officers_position.position from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id and transaction_client_officers.field_type = officer.field_type left join officer_company on transaction_client_officers.officer_id = officer_company.id and transaction_client_officers.field_type = officer_company.field_type left join client_officers_position on transaction_client_officers.position = client_officers_position.id where (client_officers_position.position = "Director" OR client_officers_position.position = "Nominee Director" OR client_officers_position.position = "Alternate Director" OR client_officers_position.position = "Managing Director") AND transaction_client_officers.date_of_cessation = "" AND transaction_client_officers.company_code ="'.$company_code.'" GROUP BY transaction_client_officers.field_type, transaction_client_officers.officer_id HAVING transaction_client_officers.id != "'.$director_signature_1_id.'" AND date_format(transaction_client_officers.created_at, "%Y-%m-%d") = "'.$current_date.'"';

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
                $row['name'] = $this->encryption->decrypt($row['name']);
                $res[$row['id']] = $row['name'];
              }
              else if($row['company_name'] != null)
              {
                $row['company_name'] = $this->encryption->decrypt($row['company_name']);
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
