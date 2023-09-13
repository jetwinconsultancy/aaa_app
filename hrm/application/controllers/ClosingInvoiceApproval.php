<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClosingInvoiceApproval extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		// $this->load->model(array('extra_model'));
	}

	public function message($to = 'World')
	{
		echo "OLA {$to}!".PHP_EOL;
	}

	public function approval($assignment_id = null,$user_id = null){
		if($assignment_id != null)
		{
			$this->db->update("payroll_assignment", array("invoice_closed" => 1), array("assignment_id" => $assignment_id));

			if ($this->db->affected_rows() == '1') {
				$this->send_approval_result($assignment_id, $user_id, "Approved");
			}
			$this->close_method();
		}
	}

	public function reject($assignment_id = null,$user_id = null){
		if($assignment_id != null)
		{
			$this->db->update("payroll_assignment", array("invoice_closed" => 0), array("assignment_id" => $assignment_id));

			if ($this->db->affected_rows() == '1') {
				$this->send_approval_result($assignment_id, $user_id, "Rejected");
			}
			$this->close_method();
		}
	}

	public function send_approval_result($assignment_id, $user_id, $status){
		$q = $this->db->query("
            SELECT 
            payroll_assignment.assignment_id, 
            payroll_assignment.client_id, 
            payroll_assignment.client_name, 
            firm.name, 
            payroll_assignment_jobs.type_of_job AS job,
            payroll_assignment_jobs.service_id AS job_service_id,
            payroll_assignment.PIC, 
            payroll_assignment.FYE
            FROM payroll_assignment 
            LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
            LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
            LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
            WHERE payroll_assignment.assignment_id = '".$assignment_id."'
        ");

        $result_query = $q->result();

        foreach ($result_query as $key => $value)
        {
            // GET PROPOSAL VALUE
            $value->proposal_value = 0;

            $each_job_service_id = explode(",", $value->job_service_id);

            if(count($each_job_service_id) == 1)
            {
                $q2 = $this->db->query("
                    SELECT * FROM client_billing_info WHERE company_code = '".$value->client_id."' AND service = '".$each_job_service_id[0]."'
                ");

                foreach ($q2->result() as $key2 => $value2)
                {
                    $value->proposal_value = $value2->amount;
                }
            }
            else
            {
                $value->proposal_value = 0;
            }

            $value->proposal_value = number_format(floatval($value->proposal_value),2,'.','');
            // END GET PROPOSAL VALUE

            // GET INVOICE VALUE
            $value->invoice_list = '';
            $value->invoice_value = 0;

            $q3 = $this->db->query("
                SELECT billing.invoice_no,our_service_info.service_name,billing_service.amount FROM payroll_assignment_invoices 
                LEFT JOIN billing_service ON billing_service.id = payroll_assignment_invoices.billing_service_id 
                LEFT JOIN billing ON billing.id = billing_service.billing_id
                LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                WHERE payroll_assignment_invoices.assignment_id = '".$value->assignment_id."'
            ");

            foreach ($q3->result() as $key3 => $value3)
            {
                $value->invoice_value += $value3->amount;

                if(($key3 + 1) == count($q3->result()))
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.')';
                }
                else
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.') , ';
                }
            }

            $value->invoice_value = number_format(floatval($value->invoice_value),2,'.','');
            // END GET INVOICE VALUE

            // REMOVE VALUE WHEN PROPOSAL < INVOICE
            if(floatval($value->proposal_value) < floatval($value->invoice_value))
            {
                array_splice($result_query,$key);
            }
        }

        $assistant_html = '';
        foreach (json_decode($result_query[0]->PIC)->assistant as $key => $assistant) {
            $assistant_html .='
                <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; height: 20px;">Assistant</th>
                <td style="border: 1px solid black; height: 20px;">'.strtoupper($assistant).'</td>
                </tr>
            ';
        }

        $pic = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Partner</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->partner).'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Manager</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->manager).'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Leader</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->leader).'</td>
            </tr>
            '.$assistant_html.'
            </table>
        ';

        $invoice_html = '';
        $each_invoice_list = explode(",", $result_query[0]->invoice_list);
        foreach ($each_invoice_list as $key => $value) {
            if($value == '')
            {
                $invoice_html .= 'N/A';
            }
            else
            {
                $invoice_html .= '
                    <li>'.$value.'</li><br>
                ';
            }
        }

        $invoice = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Total Amount</th>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->invoice_value.'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Invoice Linked</th>
            <td style="border: 1px solid black; height: 20px;">'.$invoice_html.'</td>
            </tr>
            </table>
        ';

        $unbilled_invoice = floatval($result_query[0]->proposal_value) - floatval($result_query[0]->invoice_value);
        $unbilled_invoice = number_format(floatval($unbilled_invoice),2,'.','');

        $close_invoice_detail = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <thead>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">No.</th>
            <th style="border: 1px solid black; height: 20px;">Clients</th>
            <th style="border: 1px solid black; height: 20px;">Firm</th>
            <th style="border: 1px solid black; height: 20px;">Job Type</th>
            <th style="border: 1px solid black; height: 20px;">PIC</th>
            <th style="border: 1px solid black; height: 20px;">FYE</th>
            <th style="border: 1px solid black; height: 20px;">Proposal Value</th>
            <th style="border: 1px solid black; height: 20px;">Invoices Value</th>
            <th style="border: 1px solid black; height: 20px;">Unbilled Invoices Value</th>
            </tr>
            </thead>
            <tbody>
            <tr style="border: 1px solid black;">
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->assignment_id.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->client_name.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->name.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->job.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$pic.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->FYE.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->proposal_value.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$invoice.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$unbilled_invoice.'</td>
            </tr>
            </tbody>
            </table>
        ';

        $q4 = $this->db->query("
            SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name, users.email FROM users WHERE users.id = '".$user_id."'
        ");
        $q4 = $q4->result();

        // SENDINBLUE EMAIL
        $this->load->library('parser');
        $parse_data = array(
            '$close_invoice_detail' => $close_invoice_detail,
            '$user_name'            => $q4[0]->Name,
            '$status'               => $status
        );
        $msg        = file_get_contents('./application/modules/assignment/email_templates/close_invoice_result.html');
        $subject    = 'Close Invoicing Request';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode(array(array("email"=> $q4[0]->email)));
        $cc   		= json_encode(array(array("email"=> "woellywilliam@aaa-global.com")));
        // $to_email   = json_encode(array(array("email"=> "jiawei@aaa-global.com")));
        $message    = $this->parser->parse_string($msg, $parse_data,true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
	}

	public function close_method(){
		echo "<script type='text/javascript'>";
		echo "window.close();";
		echo "</script>";
	}
}