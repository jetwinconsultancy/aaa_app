<?php define( 'APPLICATION_LOADED', true );


class HrmUserAccess extends CI_Controller {

    public function message($to = 'World') {
        echo "Hello {$to}!" . PHP_EOL;
    }

    public function set_deactiveAfterResgin() {

        $today = date("Y-m-d", strtotime("today"));

        $query1 = $this->db->query("SELECT payroll_employee.*, users.active, users.id AS user_id FROM payroll_employee 
                                    LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                                    LEFT JOIN users ON payroll_user_employee.user_id = users.id 
                                    WHERE payroll_employee.date_cessation IS NOT NULL
                                    AND users.active = 1");

        foreach($query1->result_array() as $row)
        {
            $date_cessation = date("Y-m-d", strtotime($row['date_cessation']));

            if($date_cessation < $today)
            {
                $this->db->where('id', $row['user_id']);
                $this->db->update('users', array('active' => 0));
            }
        }
    }

    public function link_service()
    {
        $link_job = "<u>LINK JOB</u><br>";
        $unlink_job = "<u>UNLINK JOB</u><br>";
        $unlink_service = "<u>UNLINK SERVICE</u><br>";
        $linked_service_id = array();

        $list = $this->db->query(" SELECT * FROM payroll_assignment_jobs");

        foreach ($list->result() as $key => $value) 
        {
            if($value->service_id != 0)
            {
                $service_id = explode(",",$value->service_id);

                foreach ($service_id as $key2 => $value2)
                {
                    $list2 = $this->db->query(" SELECT * FROM our_service_info WHERE id = '".$value2."'");
                    $list2 = $list2->result();
                    $link_job .= $list2[0]->service_name."  >>  ".$value->type_of_job.'<br>';

                    array_push($linked_service_id,  $value2);
                }
            }
            else
            {
                $unlink_job .= $value->type_of_job.'<br>';
            }

        }

        $subQ = "(";

        for($a=0; $a<count($linked_service_id); $a++)
        {
            if($a == count($linked_service_id)-1)
            {
                $subQ .= $linked_service_id[$a];
            }
            else
            {
                $subQ .= $linked_service_id[$a].",";
            }
        }

        $subQ .= ")";

        $list3 = $this->db->query(" SELECT * FROM our_service_info WHERE id NOT IN ".$subQ." AND deleted = 0");
        foreach ($list3->result() as $key3 => $value3)
        {
            $unlink_service .= $value3->service_name.'<br>';
        }

        $link_job .= '<br>';
        $unlink_job .= '<br>';
        $unlink_service .= '<br>';

        echo $link_job;
        echo $unlink_job;
        echo $unlink_service;
    }

    public function mask_timesheet_data() {

        $query1 = $this->db->query(" SELECT * FROM timesheet");

        foreach($query1->result_array() as $row)
        {
            $content = json_decode($row['content']);
            $result = array();

            foreach($content as $key => $assignment)
            {
                $client = $assignment[0];

                $query2 = $this->db->query(' SELECT * FROM temp_company_name WHERE ori_name = "'.str_replace('*','',$client).'"');

                if(count($query2->result_array()))
                {
                    $query2_result = $query2->result_array();
                    $content[$key][0] = '*'.$query2_result[0]['replace_by'];
                    array_push($result,$content[$key]);
                }
            }

            $this->db->where('id', $row['id']);
            $this->db->update('timesheet', array('content' => json_encode($result)));
        }
    }
}