<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_announcement_list(){

        $q = $this->db->query(" SELECT payroll_announcement.* FROM payroll_announcement WHERE YEAR(payroll_announcement.date) = YEAR(CURRENT_DATE) ORDER BY payroll_announcement.date DESC ");
        // $q = $this->db->query(" SELECT payroll_announcement.* FROM payroll_announcement ORDER BY payroll_announcement.date DESC ");

        $list = array();

        foreach($q->result()as $key => $item)
        {
            $list[$key]['id'] = $item->id;
            $list[$key]['date'] = $item->date;
            $list[$key]['department'] = $item->department;

            $depart_list = '';
            $value = explode(',',$item->department);

            for($a=0;$a<count($value);$a++)
            {
                $q1 = $this->db->query(" SELECT * FROM department WHERE id = '".$value[$a]."' ");
                $q1 = $q1->result();

                $depart_list .= $q1[0]->department_name;

                if(count($value) > $a+1 )
                {
                    $depart_list .= ' , ';
                }
            }

            $list[$key]['department_list'] = $depart_list;
            $list[$key]['title'] = $item->title;
            $list[$key]['announcement'] = $item->announcement;
        }
        
        return $list;
        // print_r($q->result_array());
    }

    public function check_announcement_flag ($id){
        $q = $this->db->query(" SELECT * FROM users WHERE id ='".$id."' ");

        $result = $q->result();

        return $result[0]->announcement_flag;
    }

    public function update_announcement_flag($id){

        $this->db->where('id', $id);

        $result = $this->db->update('users', array('announcement_flag' => 0));

        return $result;
    }

    public function get_employeeDepartment()
    {
        $list = $this->db->query("SELECT * FROM department ORDER BY list_order ASC");

        $employee_department_list = array();

        foreach($list->result()as $item){
            if($item->id != 7)
            {
                $employee_department_list[$item->id] = $item->department_name; 
            }
        }

        return $employee_department_list;
    }

    public function new_announcement($data,$id,$this_user_id){

        $new_or_update_flag = '';

        if($id != null)
        {
            $new_or_update_flag = 'updated';

            $this->db->where('id', $id);

            $result = $this->db->update('payroll_announcement', $data);
        }
        else
        {
            $new_or_update_flag = 'made';

            $result = $this->db->insert('payroll_announcement', $data); 
        }

        $value = explode(',',$data['department']);

        for($a=0;$a<count($value);$a++)
        {
            $q = $this->db->query(" SELECT * FROM users WHERE active = 1 AND user_deleted = 0 AND department_id ='".$value[$a]."' ");

            foreach ($q->result() as $eachUser)
            {
                $this->db->where('id', $eachUser->id);
                $this->db->update('users', array('announcement_flag' => 1));

                $this->announcement_model->announcement_email($eachUser->email,$data['title'],$data['date'],$new_or_update_flag,$this_user_id);
            }
        }

        return $result;
    }

    public function announcement_email($email,$title,$date,$new_or_update_flag,$this_user_id)
    {
        $query = $this->db->query(" SELECT * FROM users WHERE users.id = '".$this_user_id."'");
        $query = $query->result();

        $this->load->library('parser');

        $parse_data = array(
            'title'                 => $title,
            'date'                  => date('d F Y', strtotime($date)),
            'new_or_update_flag'    => $new_or_update_flag,
            'on_behalf'             => $query[0]->email
        );

        $msg = file_get_contents('./application/modules/announcement/email_templates/announcement_email.html');
        $message = $this->parser->parse_string($msg, $parse_data,true);

        $subject = 'Announcement ( '.$title.' )';

        $email_detail['from_email'] = array("name" => 'HRM System', "email" => "admin@bizfiles.com.sg");
        $email_detail['email'] = array(array("email"=> $email));

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
          new GuzzleHttp\Client(),
          $config
        );

        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = $subject;
        $sender_email = $email_detail['from_email'];
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = $email_detail['email'];
        // if($email_queue_info[$i]['cc'] != null)
        // {
        //   $sendSmtpEmail['cc'] = json_decode($email_queue_info[$i]['cc'], true);
        // }
        $sendSmtpEmail['htmlContent'] = $message;

        // $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
        // $attachment['name'] = "AA-20200013.pdf";
        //array_push($pdfDocPath, json_decode($email_queue_info[$i]['attachment'], true));
        // $sendSmtpEmail['attachment'] = json_decode($email_queue_info[$i]['attachment'], true);

        try {
          $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
          if ($result) 
          {
              // $email_queue['sended'] = 1;
              $email_queue['sendInBlueResult'] = $result;
              // $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
              echo 'Your Email has successfully been sent.';
          }
        } catch (Exception $e) {
          echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function record_filter($record)
    {
        if($record)
        {
            $q = $this->db->query(" SELECT payroll_announcement.* FROM payroll_announcement WHERE YEAR(payroll_announcement.date) < YEAR(CURRENT_DATE) ORDER BY payroll_announcement.date DESC ");

            $list = array();

            foreach($q->result()as $key => $item)
            {
                $list[$key]['id'] = $item->id;
                $list[$key]['date'] = $item->date;
                $list[$key]['department'] = $item->department;

                $depart_list = '';
                $value = explode(',',$item->department);

                for($a=0;$a<count($value);$a++)
                {
                    $q1 = $this->db->query(" SELECT * FROM department WHERE id = '".$value[$a]."' ");
                    $q1 = $q1->result();

                    $depart_list .= $q1[0]->department_name;

                    if(count($value) > $a+1 )
                    {
                        $depart_list .= ' , ';
                    }
                }

                $list[$key]['department_list'] = $depart_list;
                $list[$key]['title'] = $item->title;
                $list[$key]['announcement'] = $item->announcement;
            }
            
            return $list;
        }
        else
        {
            $q = $this->db->query(" SELECT payroll_announcement.* FROM payroll_announcement WHERE YEAR(payroll_announcement.date) = YEAR(CURRENT_DATE) ORDER BY payroll_announcement.date DESC ");

            $list = array();

            foreach($q->result()as $key => $item)
            {
                $list[$key]['id'] = $item->id;
                $list[$key]['date'] = $item->date;
                $list[$key]['department'] = $item->department;

                $depart_list = '';
                $value = explode(',',$item->department);

                for($a=0;$a<count($value);$a++)
                {
                    $q1 = $this->db->query(" SELECT * FROM department WHERE id = '".$value[$a]."' ");
                    $q1 = $q1->result();

                    $depart_list .= $q1[0]->department_name;

                    if(count($value) > $a+1 )
                    {
                        $depart_list .= ' , ';
                    }
                }

                $list[$key]['department_list'] = $depart_list;
                $list[$key]['title'] = $item->title;
                $list[$key]['announcement'] = $item->announcement;
            }
            
            return $list;
        }

    }
}
?>