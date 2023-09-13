<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_annually_list()
    {
        // $q = $this->db->query(" SELECT * FROM payroll_assignment_recurring WHERE payroll_assignment_recurring.recurring = 'annually' ");
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.recurring = 'annually' AND payroll_assignment.deleted != 1 ORDER BY client_name");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            $annual_list = array();
            $job_list = array();

            $annual_list['client_name'] = $client_name[$b];

            // $q2 = $this->db->query(' SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = "annually" AND payroll_assignment.client_name = "'.$client_name[$b].'" ');
            $q2 = $this->db->query(' SELECT payroll_assignment_recurring.* ,payroll_assignment.* ,payroll_assignment_status.* ,payroll_assignment_jobs.type_of_job as type_of_job_name 
                                        FROM payroll_assignment_recurring 
                                        INNER JOIN payroll_assignment ON payroll_assignment.client_name = payroll_assignment_recurring.client_name 
                                        AND payroll_assignment.FYE = payroll_assignment_recurring.FYE 
                                        AND payroll_assignment.type_of_job = payroll_assignment_recurring.type_of_job
                                        LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job 
                                        WHERE payroll_assignment_recurring.recurring = "annually" 
                                        AND payroll_assignment.deleted != 1
                                        AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'" ');

            $data2 = $q2->result();

            for($c=0;$c<count($data2);$c++)
            {
                $year  = date('Y', strtotime($data2[$c]->FYE));

                if($data2[$c]->signed == 1)
                {
                    $this_year  = date('Y');

                    if(($this_year -1)==$year)
                    {
                        $month  = date('m', strtotime($data2[$c]->FYE));
                        $day  = date('d', strtotime($data2[$c]->FYE));

                        $next_fye = ($year+1)."-".$month."-".$day;

                        array_push($job_list, array($data2[$c]->type_of_job,$next_fye,"NOT ASSIGN","",$data2[$c]->type_of_job_name,""));
                    }
                }

                array_push($job_list, array($data2[$c]->type_of_job,$data2[$c]->FYE,$data2[$c]->assignment_status, json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));

                if($data2[$c]->signed == 0)
                {
                    $signed = 0;

                    while($signed <= 0)
                    {
                        $year -- ;

                        $q3 = $this->db->query(' SELECT * FROM payroll_assignment LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status WHERE payroll_assignment.deleted != 1 AND payroll_assignment.type_of_job = "'.$data2[$c]->type_of_job.'" AND year(payroll_assignment.FYE) = "'.$year.'" AND payroll_assignment.client_name = "'.$client_name[$b].'" ');

                        if ($q3->num_rows() > 0)
                        {
                            $data3 = $q3->result();
                            $signed = $data3[0]->signed;
                            
                            if($signed==0)
                            {
                                array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,$data3[0]->assignment_status,json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));
                            }
                            else
                            {
                                array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,"SIGNED",json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));
                            }
                        }
                        else
                        {
                            $signed = 1;
                        }
                    }
                }
            }

            $annual_list['job_list'] = $job_list;
            array_push($result, $annual_list);
        }

        return $result;
    }

    public function get_quarterly_list()
    {
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = 'quarterly' ORDER BY client_name ");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            $annual_list = array();
            $job_list = array();

            $annual_list['client_name'] = $client_name[$b];

            $query = $this->db->query(' SELECT * FROM payroll_assignment_recurring 
                                        WHERE payroll_assignment_recurring.recurring = "quarterly" 
                                        AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'"');

            $query_result = $query->result();

            for($d=0;$d<count($query_result);$d++)
            {
                $q2 = $this->db->query(' SELECT payroll_assignment_recurring.*, payroll_assignment_jobs.type_of_job as type_of_job_name FROM payroll_assignment_recurring 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment_recurring.type_of_job 
                                        WHERE payroll_assignment_recurring.recurring = "quarterly" 
                                        AND payroll_assignment_recurring.client_name = "'.$query_result[$d]->client_name.'"
                                        AND payroll_assignment_recurring.type_of_job = "'.$query_result[$d]->type_of_job.'"');

                $data2 = $q2->result();

                for($c=0;$c<count($data2);$c++)
                {
                    $fye      = new DateTime(date('Y-m-d', strtotime($data2[$c]->FYE)));
                    $last_fye = new DateTime(date('Y-m-d', strtotime($data2[$c]->FYE)));
                    $last_fye->modify('-1 year');

                    $q_from = new DateTime(date('Y-m-d', strtotime($last_fye->format('Y-m-d'))));
                    $q_from->modify('+1 day');

                    $q_to = new DateTime(date('Y-m-d', strtotime($last_fye->format('Y-m-d'))));
                    $q_to = $this->MonthShifter($q_to,3);

                    $flag = 0 ;

                    while($q_to < $fye)
                    {
                        $q3 = $this->db->query(' SELECT * FROM payroll_assignment LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status WHERE client_name = "'.$client_name[$b].'" AND FYE = "'.$data2[$c]->FYE.'" AND type_of_job = "'.$data2[$c]->type_of_job.'" AND period_from = "'.$q_from->format('Y-m-d').'" AND payroll_assignment.deleted != 1 ');

                        if ($q3->num_rows() > 0)
                        {
                            $data3 = $q3->result();

                            array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,$data3[0]->period_from, $data3[0]->period_to,"ASSIGNED",json_decode($data3[$c]->PIC)->partner, $data2[$c]->type_of_job_name,json_decode($data3[$c]->PIC)->manager));

                            $q_from = new DateTime(date('Y-m-d', strtotime($data3[0]->period_to)));
                            $q_from->modify('+1 day');
                            $q_to = new DateTime(date('Y-m-d', strtotime($data3[0]->period_to)));
                        }
                        else
                        {
                            $temp_q = $q_to->format('Y-m-d');

                            if($flag != 0)
                            {
                                $q_from = new DateTime(date('Y-m-d', strtotime($temp_q)));
                                $q_from->modify('+1 day');

                                $q_to = new DateTime(date('Y-m-d', strtotime($temp_q)));
                                $q_to = $this->MonthShifter($q_to,3);
                            }

                            array_push($job_list, array($data2[$c]->type_of_job,$data2[$c]->FYE,$q_from->format('Y-m-d'), $q_to->format('Y-m-d'),"NOT ASSIGN","", $data2[$c]->type_of_job_name,""));

                            $q_from = new DateTime(date('Y-m-d', strtotime($temp_q)));
                            $q_from->modify('+1 day');

                            if($flag != 0)
                            {
                                $q_from = $this->MonthShifter($q_from,3);
                            }
                        }

                        $flag++; 
                    }
                }
            }

            $annual_list['job_list'] = $job_list;

            array_push($result, $annual_list);
        }

        return $result;
    }

    public function get_monthly_list()
    {
        // $q = $this->db->query(" SELECT * FROM payroll_assignment_recurring WHERE payroll_assignment_recurring.recurring = 'monthly' ");
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.recurring = 'monthly' AND payroll_assignment.deleted != 1 ORDER BY client_name");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            // $q2 = $this->db->query(" SELECT * FROM payroll_assignment_recurring WHERE payroll_assignment_recurring.recurring = 'monthly' AND payroll_assignment_recurring.client_name = '".$client_name[$b]."' ");
            $q2 = $this->db->query(' SELECT * FROM payroll_assignment_recurring WHERE payroll_assignment_recurring.recurring = "monthly" AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'" ');

            $data2 = $q2->result();

            for($d=0;$d<12;$d++)
            {
                $monthly_list = array();
                $job_list     = array();
                $assign_list  = array();
                $partner      = "";
                $manager      = "";

                $set_month = $d + 1;

                for($c=0;$c<count($data2);$c++)
                {
                    // $q3 = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = 'monthly' AND payroll_assignment.client_name = '".$data2[$c]->client_name."' AND payroll_assignment.type_of_job = '".$data2[$c]->type_of_job."' AND month(payroll_assignment.period_from) = '".$set_month."' AND year(payroll_assignment.period_from) = year(CURRENT_DATE) ");
                    $q3 = $this->db->query(' SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = "monthly" AND payroll_assignment.client_name = "'.$data2[$c]->client_name.'" AND payroll_assignment.type_of_job = "'.$data2[$c]->type_of_job.'" AND month(payroll_assignment.period_from) = "'.$set_month.'" AND year(payroll_assignment.period_from) = year(CURRENT_DATE) ');

                    $data3 = $q3->result();

                    array_push($job_list, $data2[$c]->type_of_job);

                    if ($q3->num_rows() > 0)
                    {
                        array_push($assign_list, 1);
                        $partner = isset($data3[$c]->PIC)?json_decode($data3[$c]->PIC)->partner:'';
                        $manager = isset($data3[$c]->manager)?json_decode($data3[$c]->PIC)->manager:'';
                    }
                    else
                    {
                        array_push($assign_list, 0);
                        $partner = "";
                        $manager = "";
                    }
                }

                $monthly_list = array(
                    'client_name' => $client_name[$b],
                    'month'       => $d,
                    'job_list'    => $job_list,
                    'assign_list' => $assign_list,
                    'partner'     => $partner,
                    'manager'     => $manager
                );

                array_push($result, $monthly_list);
            }
        }

        return $result;
    }

    public function get_PartnerReviwer_annually_list($user_id)
    {
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$user_id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        $PIC = "AND payroll_assignment.PIC LIKE '%".$userName."%'";

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.recurring = 'annually' AND payroll_assignment.deleted != 1 AND payroll_assignment.PIC like '%".$userName."%' ORDER BY client_name");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            $annual_list = array();
            $job_list = array();

            $annual_list['client_name'] = $client_name[$b];

            $q2 = $this->db->query(' SELECT payroll_assignment_recurring.* ,payroll_assignment.* ,payroll_assignment_status.* ,payroll_assignment_jobs.type_of_job as type_of_job_name 
                                        FROM payroll_assignment_recurring 
                                        INNER JOIN payroll_assignment ON payroll_assignment.client_name = payroll_assignment_recurring.client_name 
                                        AND payroll_assignment.FYE = payroll_assignment_recurring.FYE 
                                        AND payroll_assignment.type_of_job = payroll_assignment_recurring.type_of_job
                                        LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job 
                                        WHERE payroll_assignment_recurring.recurring = "annually" 
                                        AND payroll_assignment.deleted != 1
                                        AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'"
                                        '.$PIC.'');

            $data2 = $q2->result();

            for($c=0;$c<count($data2);$c++)
            {
                $year  = date('Y', strtotime($data2[$c]->FYE));

                // if($data2[$c]->signed == 1)
                // {
                //     $this_year  = date('Y');

                //     if(($this_year -1)==$year)
                //     {
                //         $month  = date('m', strtotime($data2[$c]->FYE));
                //         $day  = date('d', strtotime($data2[$c]->FYE));

                //         $next_fye = ($year+1)."-".$month."-".$day;

                //         array_push($job_list, array($data2[$c]->type_of_job,$next_fye,"NOT ASSIGN","",$data2[$c]->type_of_job_name));
                //     }
                // }

                array_push($job_list, array($data2[$c]->type_of_job,$data2[$c]->FYE,$data2[$c]->assignment_status, json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));

                if($data2[$c]->signed == 0)
                {
                    $signed = 0;

                    while($signed <= 0)
                    {
                        $year -- ;

                        $q3 = $this->db->query(' SELECT * FROM payroll_assignment LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status WHERE payroll_assignment.deleted != 1 AND payroll_assignment.type_of_job = "'.$data2[$c]->type_of_job.'" AND year(payroll_assignment.FYE) = "'.$year.'" AND payroll_assignment.client_name = "'.$client_name[$b].'"'.$PIC.'');

                        if ($q3->num_rows() > 0)
                        {
                            $data3 = $q3->result();
                            $signed = $data3[0]->signed;
                            
                            if($signed==0)
                            {
                                array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,$data3[0]->assignment_status,json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));
                            }
                            else
                            {
                                array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,"SIGNED",json_decode($data2[$c]->PIC)->partner,$data2[$c]->type_of_job_name,json_decode($data2[$c]->PIC)->manager));
                            }
                        }
                        else
                        {
                            $signed = 1;
                        }
                    }
                }
            }

            $annual_list['job_list'] = $job_list;
            array_push($result, $annual_list);
        }

        return $result;
    }

    public function get_PartnerReviwer_quarterly_list($user_id)
    {
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$user_id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        $PIC = "AND payroll_assignment.PIC LIKE '%".$userName."%'";

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = 'quarterly' AND payroll_assignment.PIC like '%".$userName."%' ORDER BY client_name ");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            $annual_list = array();
            $job_list = array();

            $annual_list['client_name'] = $client_name[$b];

            $query = $this->db->query(' SELECT * FROM payroll_assignment_recurring 
                                        WHERE payroll_assignment_recurring.recurring = "quarterly" 
                                        AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'"');

            $query_result = $query->result();

            for($d=0;$d<count($query_result);$d++)
            {
                $q2 = $this->db->query(' SELECT payroll_assignment_recurring.*, payroll_assignment_jobs.type_of_job as type_of_job_name FROM payroll_assignment_recurring 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment_recurring.type_of_job 
                                        WHERE payroll_assignment_recurring.recurring = "quarterly" 
                                        AND payroll_assignment_recurring.client_name = "'.$query_result[$d]->client_name.'"
                                        AND payroll_assignment_recurring.type_of_job = "'.$query_result[$d]->type_of_job.'"');

                $data2 = $q2->result();

                for($c=0;$c<count($data2);$c++)
                {
                    $fye      = new DateTime(date('Y-m-d', strtotime($data2[$c]->FYE)));
                    $last_fye = new DateTime(date('Y-m-d', strtotime($data2[$c]->FYE)));
                    $last_fye->modify('-1 year');

                    $q_from = new DateTime(date('Y-m-d', strtotime($last_fye->format('Y-m-d'))));
                    $q_from->modify('+1 day');

                    $q_to = new DateTime(date('Y-m-d', strtotime($last_fye->format('Y-m-d'))));
                    $q_to = $this->MonthShifter($q_to,3);

                    $flag = 0 ;

                    while($q_to < $fye)
                    {
                        $q3 = $this->db->query(' SELECT * FROM payroll_assignment LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status WHERE client_name = "'.$client_name[$b].'" AND FYE = "'.$data2[$c]->FYE.'" AND type_of_job = "'.$data2[$c]->type_of_job.'" AND period_from = "'.$q_from->format('Y-m-d').'" AND payroll_assignment.deleted != 1 '.$PIC.'');

                        if ($q3->num_rows() > 0)
                        {
                            $data3 = $q3->result();

                            array_push($job_list, array($data2[$c]->type_of_job,$data3[0]->FYE,$data3[0]->period_from, $data3[0]->period_to,"ASSIGNED",json_decode($data3[$c]->PIC)->partner, $data2[$c]->type_of_job_name,json_decode($data3[$c]->PIC)->manager));

                            $q_from = new DateTime(date('Y-m-d', strtotime($data3[0]->period_to)));
                            $q_from->modify('+1 day');
                            $q_to = new DateTime(date('Y-m-d', strtotime($data3[0]->period_to)));
                        }
                        else
                        {
                            $temp_q = $q_to->format('Y-m-d');

                            if($flag != 0)
                            {
                                $q_from = new DateTime(date('Y-m-d', strtotime($temp_q)));
                                $q_from->modify('+1 day');

                                $q_to = new DateTime(date('Y-m-d', strtotime($temp_q)));
                                $q_to = $this->MonthShifter($q_to,3);
                            }

                            // array_push($job_list, array($data2[$c]->type_of_job,$data2[$c]->FYE,$q_from->format('Y-m-d'), $q_to->format('Y-m-d'),"NOT ASSIGN","", $data2[$c]->type_of_job_name));

                            $q_from = new DateTime(date('Y-m-d', strtotime($temp_q)));
                            $q_from->modify('+1 day');

                            if($flag != 0)
                            {
                                $q_from = $this->MonthShifter($q_from,3);
                            }
                        }

                        $flag++; 
                    }
                }
            }

            $annual_list['job_list'] = $job_list;

            array_push($result, $annual_list);
        }

        return $result;
    }

    public function get_PartnerReviwer_monthly_list($user_id)
    {
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$user_id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        $PIC = "AND payroll_assignment.PIC LIKE '%".$userName."%'";

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.recurring = 'monthly' AND payroll_assignment.deleted != 1 AND payroll_assignment.PIC like '%".$userName."%' ORDER BY client_name");

        $data = $q->result();
        $client_name = array();

        for($a=0;$a<count($data);$a++)
        {
            array_push($client_name, $data[$a]->client_name);
        }

        $client_name = array_map("unserialize", array_unique(array_map("serialize", $client_name)));

        $client_name = array_values($client_name);

        $result = array();

        for($b=0;$b<count($client_name);$b++)
        {
            $q2 = $this->db->query(' SELECT * FROM payroll_assignment_recurring WHERE payroll_assignment_recurring.recurring = "monthly" AND payroll_assignment_recurring.client_name = "'.$client_name[$b].'" ');

            $data2 = $q2->result();

            for($d=0;$d<12;$d++)
            {
                $monthly_list = array();
                $job_list     = array();
                $assign_list  = array();
                $partner      = "";
                $manager      = "";

                $set_month = $d + 1;

                for($c=0;$c<count($data2);$c++)
                {
                    $q3 = $this->db->query(' SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted != 1 AND payroll_assignment.recurring = "monthly" AND payroll_assignment.client_name = "'.$data2[$c]->client_name.'" AND payroll_assignment.type_of_job = "'.$data2[$c]->type_of_job.'" AND month(payroll_assignment.period_from) = "'.$set_month.'" AND year(payroll_assignment.period_from) = year(CURRENT_DATE) '.$PIC.'');

                    $data3 = $q3->result();

                    array_push($job_list, $data2[$c]->type_of_job);

                    if ($q3->num_rows() > 0)
                    {
                        array_push($assign_list, 1);
                        $partner = json_decode($data3[$c]->PIC)->partner;
                        $manager = json_decode($data3[$c]->PIC)->manager;
                    }
                    else
                    {
                        array_push($assign_list, 0);
                        $partner = "";
                        $manager = "";
                    }
                }

                $monthly_list = array(
                    'client_name' => $client_name[$b],
                    'month'       => $d,
                    'job_list'    => $job_list,
                    'assign_list' => $assign_list,
                    'partner'     => $partner,
                    'manager'     => $manager
                );

                array_push($result, $monthly_list);
            }
        }

        return $result;
    }

    public function MonthShifter (DateTime $aDate,$months){
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){ 
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }

    public function get_partner_reviewer_list(){
        $list  = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");
        $list2 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) as Name FROM users WHERE (group_id = '5' OR id = '107' OR id = '65') AND user_deleted = '0' ORDER BY Name ASC");

        $partner_list = array();

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->partner_name)] = strtoupper($item->partner_name);
        }

        foreach($list2->result()as $item2){
            $partner_list[strtoupper($item2->Name)] = strtoupper($item2->Name);
        }

        return $partner_list;
    }

    public function get_partner_list(){
        $list  = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->id)] = strtoupper($item->partner_name);
        }

        return $partner_list;
    }

    public function get_reviewer_list(){
        $list2 = $this->db->query("SELECT users.id,CONCAT(first_name , ' ' , last_name) as Name FROM users WHERE (group_id = '5' OR id = '107' OR id = '65') AND user_deleted = '0' ORDER BY Name ASC");

        $partner_list = array();

        foreach($list2->result()as $item2){
            $partner_list[strtoupper($item2->id)] = strtoupper($item2->Name);
        }

        return $partner_list;
    }

    public function get_partner(){

        $list = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();
        $partner_list[''] = 'REMAIN UNCHANGED';
        $partner_list[0] = 'EMPTY / UNSET';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->id)] = strtoupper($item->partner_name);
        }

        return $partner_list;
        // return $list->result();

    }

    public function get_reviewer(){

        $list2 = $this->db->query("SELECT *,CONCAT(first_name , ' ' , last_name) as Name FROM users 
                                    WHERE (group_id = '5' OR id = '107' OR id = '65') AND user_deleted = '0' ORDER BY Name ASC");

        $partner_list = array();
        $partner_list[''] = 'REMAIN UNCHANGED';
        $partner_list[0] = 'EMPTY / UNSET';

        foreach($list2->result()as $item2){
            $partner_list[strtoupper($item2->id)] = strtoupper($item2->Name);
        }

        return $partner_list;
        // return $list->result();

    }

    public function get_assignment_job_list(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_jobs WHERE payroll_assignment_jobs.id IN (1,2,3,4,5,6,7,8,9,10,11,12,13) AND payroll_assignment_jobs.deleted = '0' ORDER BY FIELD(payroll_assignment_jobs.id,'1','2','3','4','5','6','7','8','10','11','12','13','9')");

        $partner_list = array();
        $partner_list['0'] = 'All';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->id)] = strtoupper($item->type_of_job);
        }

        return $partner_list;
    }

    public function get_client_list()
    {
        $q = $this->db->query("SELECT * FROM payroll_portfolio WHERE deleted = 0 ORDER BY company_name ASC");
        return $q->result();
    }

    // public function set_or_unset_client_list($role,$user_id,$set_value,$job_id){

    //     if($user_id == "NULL")
    //     {
    //         $user_id = NULL;
    //     }

    //     if($role == "partner")
    //     {
    //         $data = array('the_partner'=> $user_id);
    //     }
    //     else
    //     {
    //         $data = array('the_reviewer'=> $user_id);
    //     }

    //     foreach ($set_value as $key => $value) 
    //     {
    //         $value = explode('=',$value);
    //         $company_code = $value[0];
    //         $company_name = $value[1];

    //         $query = $this->db->query('SELECT * FROM payroll_portfolio WHERE payroll_portfolio.company_code = "'.$company_code.'" AND payroll_portfolio.company_name = "'.$company_name.'" AND payroll_portfolio.type_of_job = "'.$job_id.'"');

    //         if ($query->num_rows() > 0)
    //         {
    //             $query = $query->result();

    //             $this->db->where('id', $query[0]->id);
    //             $result = $this->db->update('payroll_portfolio', $data);
    //         }
    //     }

    //     // RETURN
    //     $q = $this->db->query("SELECT * FROM payroll_portfolio ORDER BY company_name ASC");
    //     return $q->result();
    // }
    public function set_or_unset_client_list($partner,$reviewer,$job,$set_value,$partner_filter,$reviewer_filter){

        if($partner == 0)
        {
            $partner = NULL;
        }

        if($reviewer == 0)
        {
            $reviewer = NULL;
        }

        if($partner == '')
        {
            $data = array('the_reviewer'=> $reviewer);
        }
        else if($reviewer == '')
        {
            $data = array('the_partner'=> $partner);
        }
        else
        {
            $data = array('the_partner'=> $partner, 'the_reviewer'=> $reviewer);
        }

        foreach ($set_value as $key => $value) 
        {
            $value = explode('=',$value);
            $company_code = $value[0];
            $company_name = $value[1];

            foreach ($job as $key2 => $value2) 
            {
                $query = $this->db->query('SELECT * FROM payroll_portfolio WHERE payroll_portfolio.company_code = "'.$company_code.'" AND payroll_portfolio.company_name = "'.$company_name.'" AND payroll_portfolio.type_of_job = "'.$value2.'"');

                if ($query->num_rows() > 0)
                {
                    $query = $query->result();

                    $this->db->where('id', $query[0]->id);
                    $result = $this->db->update('payroll_portfolio', $data);
                }
            }
        }

        $result = $this->portfolio_model->filter($partner_filter,$reviewer_filter,$job);
        return $result;
    }

    // UPDATE CLIENT TO PAYROLL_PORTFOLIO
    public function update_client_to_portfolio()
    {
        $q = $this->db->query("SELECT * FROM client WHERE deleted = 0 ORDER BY company_name ASC");

        foreach($q->result() as $client)
        {
            $q3 = $this->db->query("SELECT * FROM payroll_assignment_jobs");

            foreach($q3->result() as $job)
            {
                $q2 = $this->db->query('SELECT * FROM payroll_portfolio WHERE payroll_portfolio.company_code = "'.$client->company_code.'" AND payroll_portfolio.company_name = "'.$this->encryption->decrypt($client->company_name).'" AND payroll_portfolio.type_of_job = "'.$job->id.'"');

                if($q2->num_rows() == 0)
                {
                    $inform = array(
                        'company_code' => $client->company_code,
                        'company_name' => $this->encryption->decrypt($client->company_name),
                        'type_of_job'  => $job->id,
                        'the_partner'  => NULL,
                        'the_reviewer' => NULL,
                    );

                    $this->db->insert('payroll_portfolio', $inform);
                }
                else
                {
                    break;
                }
            }
        }

        $result = $this->portfolio_model->remove_client_to_portfolio();
        return $result;
    }

    public function remove_client_to_portfolio()
    {

        $q = $this->db->query("SELECT * FROM client WHERE deleted = 1 ORDER BY company_name ASC");

        foreach($q->result() as $client)
        {
            $q3 = $this->db->query("SELECT * FROM payroll_assignment_jobs");

            foreach($q3->result() as $job)
            {
                $q2 = $this->db->query('SELECT * FROM payroll_portfolio WHERE payroll_portfolio.company_code = "'.$client->company_code.'" AND payroll_portfolio.company_name = "'.$this->encryption->decrypt($client->company_name).'" AND payroll_portfolio.type_of_job = "'.$job->id.'"');

                if($q2->num_rows() > 0)
                {
                    $q2 = $q2->result();
                       
                    $this->db->where('id', $q2[0]->id);
                    $result = $this->db->update('payroll_portfolio', array('deleted' => 1));
                }
                else
                {
                    break;
                }
            }
        }
        return true;
    }

    public function get_jobs_list(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_jobs");

        $jobs_list = array();
        // $jobs_list['0'] = 'Please Select';
        // $jobs_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $jobs_list[$item->id] = $item->type_of_job;
        }

        return $jobs_list;
    }

    public function get_portfolio_list(){
        $list = $this->db->query("SELECT payroll_portfolio.*, payroll_assignment_jobs.type_of_job AS jobName, payroll_partner.partner_name AS partner, CONCAT(first_name , ' ' , last_name) AS reviewer FROM payroll_portfolio 
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_portfolio.type_of_job
                                    LEFT JOIN payroll_partner ON payroll_partner.id = payroll_portfolio.the_partner
                                    LEFT JOIN users ON users.id = payroll_portfolio.the_reviewer
                                    WHERE payroll_portfolio.deleted = 0");
        return $list->result();
    }

    public function filter($partner,$reviewer,$job){

        if($partner != "")
        {
            $subQ_partner = "payroll_portfolio.the_partner IN (";

            for($a=0; $a<count($partner); $a++)
            {
                if($a == count($partner)-1)
                {
                    $subQ_partner .= $partner[$a];
                }
                else
                {
                    $subQ_partner .= $partner[$a].",";
                }
            }

            $subQ_partner .= ") AND ";
        }
        else
        {
            $subQ_partner = "";
        }

        if($reviewer != "")
        {
            $subQ_reviewer = "payroll_portfolio.the_reviewer IN (";

            for($a=0; $a<count($reviewer); $a++)
            {
                if($a == count($reviewer)-1)
                {
                    $subQ_reviewer .= $reviewer[$a];
                }
                else
                {
                    $subQ_reviewer .= $reviewer[$a].",";
                }
            }

            $subQ_reviewer .= ") AND ";
        }
        else
        {
            $subQ_reviewer = "";
        }

        if($job != "")
        {
            $subQ_job = "payroll_portfolio.type_of_job IN (";

            for($a=0; $a<count($job); $a++)
            {
                if($a == count($job)-1)
                {
                    $subQ_job .= $job[$a];
                }
                else
                {
                    $subQ_job .= $job[$a].",";
                }
            }

            $subQ_job .= ") AND ";
        }
        else
        {
            $subQ_job = "";
        }

        $list = $this->db->query("SELECT payroll_portfolio.*, payroll_assignment_jobs.type_of_job AS jobName, payroll_partner.partner_name AS partner, CONCAT(first_name , ' ' , last_name) AS reviewer FROM payroll_portfolio 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_portfolio.type_of_job
                                LEFT JOIN payroll_partner ON payroll_partner.id = payroll_portfolio.the_partner
                                LEFT JOIN users ON users.id = payroll_portfolio.the_reviewer 
                                WHERE ".$subQ_partner."".$subQ_reviewer."".$subQ_job." payroll_portfolio.deleted = 0");
        return $list->result();
    }

    public function get_PartnerReviwer_portfolio_list($id, $designation)
    {
        if($designation == 'PARTNER')
        {
            $q1 = $this->db->query("SELECT id FROM payroll_partner WHERE user_id = '".$id."'");
            $partner_id = $q1->result();
            $partner_id = json_encode($partner_id[0]->id);

            $subQ = "payroll_portfolio.the_partner IN (".$partner_id.") AND";
        }
        else
        {
            $subQ = "payroll_portfolio.the_reviewer IN (".$id.") AND";
        }

        $list = $this->db->query("SELECT payroll_portfolio.*, payroll_assignment_jobs.type_of_job AS jobName, payroll_partner.partner_name AS partner, CONCAT(first_name , ' ' , last_name) AS reviewer FROM payroll_portfolio 
                                    LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_portfolio.type_of_job
                                    LEFT JOIN payroll_partner ON payroll_partner.id = payroll_portfolio.the_partner
                                    LEFT JOIN users ON users.id = payroll_portfolio.the_reviewer
                                    WHERE ".$subQ." payroll_portfolio.deleted = 0");

        return $list->result();
    }
}
?>