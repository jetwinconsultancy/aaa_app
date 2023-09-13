<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');
use NcJoes\OfficeConverter\OfficeConverter;

class Encrypt_data extends CI_Controller {

    public function encrypt($to = 'World')
    {
        //echo "Hello {$to}!".PHP_EOL;
        $this->load->library(array('encryption', 'Excel', 'encrypt'));
        $this->load->model('transaction_word_model');
        // $this->encrypt_officer();
        // $this->encrypt_officer_company();
        // $this->encrypt_officer_mobile_no();
        // $this->encrypt_officer_company_phone_number();
        // $this->encrypt_officer_fixed_line_no();
        // $this->encrypt_officer_company_email();
        // $this->encrypt_officer_email();
        //$this->encrypt_client();
        // $this->encrypt_transaction_client();
        // $this->encrypt_transaction_master();
        //$this->encrypt_individual_client();
        //$this->test_take_latest_client_code();
        //$this->test_save_share_transfer();
        //$this->retrieve_person_company_email();
        //$this->retrieve_person_individual_email();
        //$this->retrieve_client_contact_person_email();
        //$this->check_share_member_can_become_controller();
        //$this->check_share_member_can_become_controller_in_client_module();
        //$this->replace_activity_value();
        //$this->restore_backup_file_test();
        //$this->detect_payment_voucher_no();
        //$this->decrypt_individual_client();
        //$this->test_fye_date();
        //$this->check_firm_gst_date();
        //$this->check_person_data();
        //$this->convertPDF();
        //$this->get_member_director_list();
        //$this->insert_officer_detail_to_db();
        //$this->add_roc_service();
        //$this->update_recurring_billing_gst();
        //$this->update_transaction_master_completed();
        //$this->test_http();
        //$this->generate_money_leading_client();
        //$this->update_our_services_description();
        //$this->decrypt_database();
        //$this->generate_services_with_invoice();
        //$this->generate_services_with_recurring_invoice();
        //$this->generate_client_qb_list();
        //$this->generate_vendor_list();
        $this->generate_client_addredd_excel();
        //$this->test_get_gst_rate();
    }

    public function decrypt_database()
    {
        ini_set('memory_limit', '-1');
        $sql_contents = file_get_contents('./files/backups/test.txt');
        $sql_contents = explode(";\n", $this->encrypt->decode($sql_contents));

        foreach($sql_contents as $query)
        {
            //echo json_encode(trim($query)."<br>");
            // $pos = strpos(trim($query),'ci_sessions');
            // var_dump($pos);
            $pos = trim($query);
            //var_dump($pos);
            if($pos)
            {
                $result = $this->db->query($query);
            }
        }
    }

    public function encrypt_officer()
    {
    	$q = $this->db->query("select * from officer");

        $q = $q->result_array();

        foreach($q as $key => $data)
        {
        	$officer['identification_no'] = $this->encryption->encrypt($data['identification_no']);
        	$officer['name'] = $this->encryption->encrypt($data['name']);

        	$this->db->where('id', $data['id']);    
            $this->db->update('officer', $officer);
        }
    }

    public function encrypt_officer_company()
    {
    	$query_officer_company = $this->db->query("select * from officer_company");

        $query_officer_company = $query_officer_company->result_array();

        foreach($query_officer_company as $key => $data_officer_company)
        {
        	$officer_company['register_no'] = $this->encryption->encrypt($data_officer_company['register_no']);
        	$officer_company['company_name'] = $this->encryption->encrypt($data_officer_company['company_name']);

        	$this->db->where('id', $data_officer_company['id']);    
            $this->db->update('officer_company', $officer_company);
        }
    }

    public function encrypt_officer_mobile_no()
    {
        $query_officer_mobile_no = $this->db->query("select * from officer_mobile_no");

        $query_officer_mobile_no = $query_officer_mobile_no->result_array();

        foreach($query_officer_mobile_no as $key => $data_officer_mobile_no)
        {
            $officer_mobile_no['mobile_no'] = $this->encryption->encrypt($data_officer_mobile_no['mobile_no']);

            $this->db->where('id', $data_officer_mobile_no['id']);    
            $this->db->update('officer_mobile_no', $officer_mobile_no);
        }
    }

    public function encrypt_officer_company_phone_number()
    {
        $query_officer_company_phone_number = $this->db->query("select * from officer_company_phone_number");

        $query_officer_company_phone_number = $query_officer_company_phone_number->result_array();

        foreach($query_officer_company_phone_number as $key => $data_officer_company_phone_number)
        {
            $officer_company_phone_number['phone_number'] = $this->encryption->encrypt($data_officer_company_phone_number['phone_number']);

            $this->db->where('id', $data_officer_company_phone_number['id']);    
            $this->db->update('officer_company_phone_number', $officer_company_phone_number);
        }
    }

    public function encrypt_officer_fixed_line_no()
    {
        $query_officer_fixed_line_no = $this->db->query("select * from officer_fixed_line_no");

        $query_officer_fixed_line_no = $query_officer_fixed_line_no->result_array();

        foreach($query_officer_fixed_line_no as $key => $data_officer_fixed_line_no)
        {
            $officer_fixed_line_no['fixed_line_no'] = $this->encryption->encrypt($data_officer_fixed_line_no['fixed_line_no']);

            $this->db->where('id', $data_officer_fixed_line_no['id']);    
            $this->db->update('officer_fixed_line_no', $officer_fixed_line_no);
        }
    }

    public function encrypt_officer_company_email()
    {
        $query_officer_company_email = $this->db->query("select * from officer_company_email");

        $query_officer_company_email = $query_officer_company_email->result_array();

        foreach($query_officer_company_email as $key => $data_officer_company_email)
        {
            $officer_company_email['email'] = $this->encryption->encrypt($data_officer_company_email['email']);

            $this->db->where('id', $data_officer_company_email['id']);    
            $this->db->update('officer_company_email', $officer_company_email);
        }
    }

    public function encrypt_officer_email()
    {
        $query_officer_email = $this->db->query("select * from officer_email");

        $query_officer_email = $query_officer_email->result_array();

        foreach($query_officer_email as $key => $data_officer_email)
        {
            $officer_email['email'] = $this->encryption->encrypt($data_officer_email['email']);

            $this->db->where('id', $data_officer_email['id']);    
            $this->db->update('officer_email', $officer_email);
        }
    }

    public function encrypt_client()
    {
        $q = $this->db->query("select * from client");

        $q = $q->result_array();

        foreach($q as $key => $data)
        {
            $officer['registration_no'] = $this->encryption->encrypt($data['registration_no']);
            $officer['company_name'] = $this->encryption->encrypt($data['company_name']);

            $this->db->where('id', $data['id']);    
            $this->db->update('client', $officer);
        }
    }

    public function encrypt_transaction_client()
    {
        $q = $this->db->query("select * from transaction_client");

        $q = $q->result_array();

        foreach($q as $key => $data)
        {
            //$officer['registration_no'] = $this->encryption->encrypt($data['registration_no']);
            $officer['company_name'] = $this->encryption->encrypt($data['company_name']);

            $this->db->where('id', $data['id']);    
            $this->db->update('transaction_client', $officer);
        }
    }

    public function encrypt_transaction_master()
    {
        $q = $this->db->query("select * from transaction_master");

        $q = $q->result_array();

        foreach($q as $key => $data)
        {
            //$officer['registration_no'] = $this->encryption->encrypt($data['registration_no']);
            if($data['client_name'] != null)
            {
                $officer['client_name'] = $this->encryption->encrypt($data['client_name']);
            }
            else
            {
                $officer['client_name'] = $data['client_name'];
            }

            if($data['registration_no'] != null)
            {
                $officer['registration_no'] = $this->encryption->encrypt($data['registration_no']);
            }
            else
            {
                $officer['registration_no'] = $data['registration_no'];
            }   

            $this->db->where('id', $data['id']);    
            $this->db->update('transaction_master', $officer);
        }
    }

    public function encrypt_individual_client()
    {
        print_r($this->encryption->encrypt(trim(strtoupper("NORDIC SEMICONDUCTOR ASA"))));
    }

    public function decrypt_individual_client()
    {
        print_r($this->encryption->decrypt("69f840d8c8b682034325cc4df8e1c706b366fbb5376dd72a933b0d2b8d738d8e3eabe592fbd20bc467c63ad85ac46f1bf668a4d8557dd1bae8ceab8ad0db65c9r1BpPxbbeS6TWXKkdyN1DZxU9RFm2STaaoHY56R2pgzC++iZqm/TYZM23JEIjdYK+rXaiqE2CrCD75vv1hU3JQ=="));
    }

    public function test_take_latest_client_code()
    {
        $company_name = "PPPPPP";

        $firstCharacter = substr($company_name, 0, 1);

        $q = $this->db->query("SELECT MAX(CAST(SUBSTRING(client_code, -5) AS UNSIGNED)) as latest_client_code FROM client WHERE client_code LIKE '".$firstCharacter."%' AND deleted = 0 ORDER BY latest_client_code DESC LIMIT 1");

        $q = $q->result_array();

        $num_padded = sprintf("%05d", $q[0]["latest_client_code"] + 1);

        //print_r($q[0]["latest_client_code"]);
        print_r($firstCharacter.$num_padded);
        
        echo json_encode($firstCharacter);
    }

    public function retrieve_person_company_email()
    {
        $q = $this->db->query("SELECT officer_company.company_name, officer_company_email.email FROM officer_company LEFT JOIN officer_company_email on officer_company_email.officer_company_id = officer_company.id");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/company_list_with_email.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            $new_sheet->setCellValue('A'.$column_num, $this->encryption->decrypt($q[$num]['company_name']));

            if($q[$num]['email'] != null)
            {
                $new_sheet->setCellValue('B'.$column_num, $this->encryption->decrypt($q[$num]['email']));
            }
            else
            {
                $new_sheet->setCellValue('B'.$column_num, "");
            }

            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/company_list_with_email.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function generate_client_addredd_excel()
    {
        $q = $this->db->query("select client.*, client_contact_info_email.email from client left join client_contact_info on client_contact_info.company_code = client.company_code left join client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id and client_contact_info_email.primary_email = 1 WHERE client.foreign_add_3 = '59000 KUALA LUMPUR' AND client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1'"); //postal_code = 068914 AND unit_no1 = 26 AND unit_no2 = 10 

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/client_address.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;

        foreach($q as $key => $data)
        {
            $registration_no = $this->encryption->decrypt($data['registration_no']);
            $company_name = $this->encryption->decrypt($data['company_name']);

            $new_sheet->setCellValue('A'.$column_num, $registration_no);
            $new_sheet->setCellValue('B'.$column_num, $company_name);
            $new_sheet->setCellValue('C'.$column_num, $data['foreign_add_1']);
            $new_sheet->setCellValue('D'.$column_num, $data['foreign_add_2']);
            $new_sheet->setCellValue('E'.$column_num, $data['foreign_add_3']);
            $new_sheet->setCellValue('F'.$column_num, $data['unit_no1']);
            $new_sheet->setCellValue('G'.$column_num, $data['unit_no2']);
            $new_sheet->setCellValue('H'.$column_num, $data['email']);

            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/client_sbf_address.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function retrieve_person_individual_email()
    {
        $q = $this->db->query("SELECT officer.name, officer_email.email FROM officer LEFT JOIN officer_email on officer_email.officer_id = officer.id WHERE date_of_birth = '1970-01-01'");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/individual_list_with_email.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            $new_sheet->setCellValue('A'.$column_num, $this->encryption->decrypt($q[$num]['name']));

            if($q[$num]['email'] != null)
            {
                $new_sheet->setCellValue('B'.$column_num, $this->encryption->decrypt($q[$num]['email']));
            }
            else
            {
                $new_sheet->setCellValue('B'.$column_num, "");
            }

            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/individual_list_with_email.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function retrieve_client_contact_person_email()
    {
        $q = $this->db->query("SELECT client.company_code, client.registration_no, client.company_name, client_contact_info.name, client_contact_info_email.email FROM client LEFT JOIN client_contact_info on client_contact_info.company_code = client.company_code LEFT JOIN client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id AND client_contact_info_email.primary_email = 1 LEFT JOIN client_billing_info on client_billing_info.company_code = client.company_code WHERE client.firm_id = 18 AND client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND client_billing_info.service = 3 AND client_billing_info.deactive = 0 AND client_billing_info.deleted = 0");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/client_contact_list_with_email.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            $new_sheet->setCellValue('A'.$column_num, $this->encryption->decrypt($q[$num]['company_name']));

            if($q[$num]['name'] != null)
            {
                $new_sheet->setCellValue('B'.$column_num, $q[$num]['name']);
            }
            else
            {
                $new_sheet->setCellValue('B'.$column_num, "");
            }

            if($q[$num]['email'] != null)
            {
                $new_sheet->setCellValue('C'.$column_num, $q[$num]['email']);
            }
            else
            {
                $new_sheet->setCellValue('C'.$column_num, "");
            }

            $new_sheet->setCellValue('D'.$column_num, $this->encryption->decrypt($q[$num]['registration_no']));
            $new_sheet->setCellValue('E'.$column_num, $q[$num]['company_code']);
            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/client_contact_list_with_email.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function test_save_share_transfer()
    {
        // $q = $this->db->query('select * from transaction_share_transfer_record where transaction_page_id = '.$transaction_master_id);
        //$transaction_id = "TR-".mt_rand(100000000, 999999999);
        $lodgement_date = "28/03/2020";
        $company_code = "company_1545967387";
        $q = $this->db->query('select * from transaction_share_transfer_record where transaction_page_id = 1178');

        if ($q->num_rows() > 0) 
        {
            $q = $q->result_array();

            $id = $q[0]["id"];
            $transaction_page_id = $q[0]["transaction_page_id"];
            $transferor_array = json_decode($q[0]["transferor_array"]);
            $transferee_array = json_decode($q[0]["transferee_array"]);
            $index = 0;
            $total_no_of_share = 0;
            $total_amount_share = 0;
            $per_share = 0;

            $member_query = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid from member_shares left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
            $member_query = $member_query->result_array();

            for($p = 0; $p < count($member_query); $p++)
            {
                $total_no_of_share += (int)str_replace(',', '',$member_query[$p]['number_of_share']);
                $total_amount_share += (int)str_replace(',', '',$member_query[$p]['amount_share']);
            }

            $per_share = $total_amount_share/$total_no_of_share;

            foreach (($transferor_array) as $row)
            {
                if($row->number_of_shares_to_transfer != "" && $row->number_of_shares_to_transfer != "0")
                {
                    if($row->new_number_of_share == "0")
                    {
                        $transaction_id = "TR-".mt_rand(100000000, 999999999);
                        $previous_cert_query = $this->db->query('select * from certificate where id = '.$row->certificate_id);
                        $previous_cert_query = $previous_cert_query->result_array();

                        $previous_cert_status['status'] = 2;
                        $this->db->update("certificate",$previous_cert_status,array("id" => $row->certificate_id));

                        //member_shares
                        $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
                        $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                        $member_share_info["officer_id"] = $row->officer_id;
                        $member_share_info["field_type"] = $row->field_type;
                        $member_share_info["transaction_id"] = $transaction_id;
                        $member_share_info["number_of_share"] = -(str_replace(',', '',$row->number_of_shares_to_transfer));
                        $member_share_info["amount_share"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
                        $member_share_info["no_of_share_paid"] = -(str_replace(',', '',$row->number_of_shares_to_transfer));
                        $member_share_info["amount_paid"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
                        $member_share_info["transaction_date"] = $lodgement_date;
                        $member_share_info["transaction_type"] = "Transfer";
                        $member_share_info["consideration"] = 0;
                        $member_share_info["cert_status"] = 1;
                        $this->db->insert("member_shares",$member_share_info);

                        //certificate
                        $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
                        $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                        $cert_info["officer_id"] = $row->officer_id;
                        $cert_info["field_type"] = $row->field_type;
                        $cert_info["transaction_id"] = $transaction_id;
                        $cert_info["number_of_share"] = 0;
                        $cert_info["amount_share"] = 0;
                        $cert_info["no_of_share_paid"] = 0;
                        $cert_info["amount_paid"] = 0;
                        $cert_info["certificate_no"] = $row->certificate;
                        $cert_info["new_certificate_no"] = $row->certificate;
                        $cert_info["status"] = 1;
                        $this->db->insert("certificate",$cert_info);
                    }
                    else
                    {
                        $transaction_id = "TR-".mt_rand(100000000, 999999999);
                        $previous_cert_query = $this->db->query('select * from certificate where id = '.$row->certificate_id);
                        $previous_cert_query = $previous_cert_query->result_array();

                        $previous_cert_status['status'] = 2;
                        $this->db->update("certificate",$previous_cert_status,array("id" => $row->certificate_id));

                        //member_shares
                        $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
                        $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                        $member_share_info["officer_id"] = $row->officer_id;
                        $member_share_info["field_type"] = $row->field_type;
                        $member_share_info["transaction_id"] = $transaction_id;
                        $member_share_info["number_of_share"] = -(str_replace(',', '', $row->number_of_shares_to_transfer));
                        $member_share_info["amount_share"] = -((float)str_replace(',', '',$row->number_of_shares_to_transfer) * $per_share);
                        $member_share_info["no_of_share_paid"] = -(str_replace(',', '', $row->number_of_shares_to_transfer));
                        $member_share_info["amount_paid"] = -((float)str_replace(',', '', $row->number_of_shares_to_transfer) * $per_share);
                        $member_share_info["transaction_date"] = $lodgement_date;
                        $member_share_info["transaction_type"] = "Transfer";
                        $member_share_info["consideration"] = 0;
                        $member_share_info["cert_status"] = 1;
                        $this->db->insert("member_shares",$member_share_info);

                        //certificate
                        $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
                        $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                        $cert_info["officer_id"] = $row->officer_id;
                        $cert_info["field_type"] = $row->field_type;
                        $cert_info["transaction_id"] = $transaction_id;
                        $cert_info["number_of_share"] = str_replace(',', '', $row->new_number_of_share);
                        $cert_info["amount_share"] = (float)str_replace(',', '', $row->new_number_of_share) * $per_share;
                        $cert_info["no_of_share_paid"] = str_replace(',', '', $row->new_number_of_share);
                        $cert_info["amount_paid"] = (float)str_replace(',', '', $row->new_number_of_share) * $per_share;
                        $cert_info["certificate_no"] = $row->certificate;
                        $cert_info["new_certificate_no"] = $row->certificate;
                        $cert_info["status"] = 1;
                        $this->db->insert("certificate",$cert_info);
                    }
                } 
            }

            foreach (($transferee_array) as $row)
            {
                $transaction_id = "TR-".mt_rand(100000000, 999999999);
                $previous_cert_query = $this->db->query('select * from transaction_certificate where id = '.$row->certificate_id);
                $previous_cert_query = $previous_cert_query->result_array();

                //member_shares
                $member_share_info["company_code"] = $previous_cert_query[0]["company_code"];
                $member_share_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                $member_share_info["officer_id"] = $row->officer_id;
                $member_share_info["field_type"] = $row->field_type;
                $member_share_info["transaction_id"] = $transaction_id;
                $member_share_info["number_of_share"] = $row->new_number_of_share;
                $member_share_info["amount_share"] = ((float)$row->new_number_of_share) * $per_share;
                $member_share_info["no_of_share_paid"] = $row->new_number_of_share;
                $member_share_info["amount_paid"] = ((float)$row->new_number_of_share) * $per_share;
                $member_share_info["transaction_date"] = $lodgement_date;
                $member_share_info["transaction_type"] = "Transfer";
                $member_share_info["consideration"] = 0;
                $member_share_info["cert_status"] = 1;
                $this->db->insert("member_shares",$member_share_info);

                //certificate
                $cert_info["company_code"] = $previous_cert_query[0]["company_code"];
                $cert_info["client_member_share_capital_id"] = $previous_cert_query[0]["client_member_share_capital_id"];
                $cert_info["officer_id"] = $row->officer_id;
                $cert_info["field_type"] = $row->field_type;
                $cert_info["transaction_id"] = $transaction_id;
                $cert_info["number_of_share"] = $row->new_number_of_share;
                $cert_info["amount_share"] = ((float)$row->new_number_of_share) * $per_share;
                $cert_info["no_of_share_paid"] = $row->new_number_of_share;
                $cert_info["amount_paid"] = ((float)$row->new_number_of_share) * $per_share;
                $cert_info["certificate_no"] = $row->certificate;
                $cert_info["new_certificate_no"] = $row->certificate;
                $cert_info["status"] = 1;
                $this->db->insert("certificate",$cert_info);
            }

            //$share_transfer_info_query =  $this->db->query("select transaction_transfer_member_id.id as transaction_transfer_member_id, client.company_code, client.company_name as client_company_name, transaction_transfer_member_id.transaction_id as transaction_id, from_officer.id as from_officer_id, from_officer.field_type as from_officer_field_type, from_officer_company.id as from_officer_company_id, from_officer_company.field_type as from_officer_company_field_type, from_client.id as from_client_company_id, 'client' as from_client_company_field_type, from_share_capital.id as share_capital_id, from_share_capital.class_id, from_share_capital.other_class, from_share_capital.currency_id, from_class.sharetype, from_currencies.currency, from_transfer_member.id as from_transfer_member_id, to_officer.id as to_officer_id, to_officer.field_type as to_officer_field_type, to_officer_company.id as to_officer_company_id, to_officer_company.field_type as to_officer_company_field_type, to_client.id as to_client_company_id, 'client' as to_client_company_field_type, to_transfer_member.id as to_transfer_member_id, to_transfer_member.number_of_share as to_number_of_share from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_transfer_member.field_type = 'client' left join transaction_master on transaction_master.id = transaction_transfer_member_id.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_transfer_member_id.transaction_id = '1178'");//".$id."

            $share_transfer_info_query = $this->db->query("select transaction_transfer_member_id.id as transaction_transfer_member_id, client.company_code, client.company_name as client_company_name, transaction_transfer_member_id.transaction_id as transaction_id, from_officer.id as from_officer_id, from_officer.field_type as from_officer_field_type, from_officer.identification_no as from_officer_identification_no, from_officer.name as from_officer_name, from_officer_company.id as from_officer_company_id, from_officer_company.register_no as from_officer_company_register_no, from_officer_company.field_type as from_officer_company_field_type, from_officer_company.company_name as from_officer_company_name, from_client.id as from_client_company_id, from_client.registration_no as from_client_regis_no, 'client' as from_client_company_field_type, from_client.company_name as from_client_company_name, from_share_capital.id as share_capital_id, from_share_capital.class_id, from_share_capital.other_class, from_share_capital.currency_id, from_class.sharetype, from_currencies.currency, from_transaction_certificate.id as from_cert_id, from_transaction_certificate.certificate_no as from_certificate_no, from_transaction_certificate.new_certificate_no as from_new_certificate_no, from_transfer_member.id as from_transfer_member_id, from_transfer_member.number_of_share as from_number_of_share, from_transfer_member.consideration as from_consideration, to_officer.id as to_officer_id, to_officer.field_type as to_officer_field_type, to_officer.identification_no as to_officer_identification_no, to_officer.name as to_officer_name, to_officer_company.id as to_officer_company_id, to_officer_company.register_no as to_officer_company_register_no, to_officer_company.field_type as to_officer_company_field_type, to_officer_company.company_name as to_officer_company_name, to_client.id as to_client_company_id, to_client.registration_no as to_client_regis_no, 'client' as to_client_company_field_type, to_client.company_name as to_client_company_name, to_transaction_certificate.id as to_cert_id, to_transaction_certificate.certificate_no as to_certificate_no, to_transaction_certificate.new_certificate_no as to_new_certificate_no, to_transfer_member.id as to_transfer_member_id, to_transfer_member.number_of_share as to_number_of_share from transaction_transfer_member_id left join transaction_member_shares as from_transfer_member on from_transfer_member.id = transaction_transfer_member_id.transfer_from_id left join officer as from_officer on from_officer.id = from_transfer_member.officer_id and from_officer.field_type = from_transfer_member.field_type left join officer_company as from_officer_company on from_officer_company.id = from_transfer_member.officer_id and from_officer_company.field_type = from_transfer_member.field_type left join client as from_client on from_client.id = from_transfer_member.officer_id and from_client.deleted = 0 and from_transfer_member.field_type = 'client' left join client_member_share_capital as from_share_capital on from_transfer_member.client_member_share_capital_id = from_share_capital.id left join sharetype as from_class on from_class.id = from_share_capital.class_id left join currency as from_currencies on from_currencies.id = from_share_capital.currency_id left join transaction_certificate as from_transaction_certificate on from_transaction_certificate.officer_id = from_transfer_member.officer_id and from_transaction_certificate.company_code = from_transfer_member.company_code and from_transaction_certificate.field_type = from_transfer_member.field_type and from_transaction_certificate.transaction_id = from_transfer_member.transaction_id left join transaction_member_shares as to_transfer_member on to_transfer_member.id = transaction_transfer_member_id.transfer_to_id left join officer as to_officer on to_officer.id = to_transfer_member.officer_id and to_officer.field_type = to_transfer_member.field_type left join officer_company as to_officer_company on to_officer_company.id = to_transfer_member.officer_id and to_officer_company.field_type = to_transfer_member.field_type left join client as to_client on to_client.id = to_transfer_member.officer_id and to_client.deleted = 0 and to_transfer_member.field_type = 'client' left join transaction_certificate as to_transaction_certificate on to_transaction_certificate.officer_id = to_transfer_member.officer_id and to_transaction_certificate.company_code = to_transfer_member.company_code and to_transaction_certificate.field_type = to_transfer_member.field_type and to_transaction_certificate.transaction_id = to_transfer_member.transaction_id left join transaction_master on transaction_master.id = transaction_transfer_member_id.transaction_id left join client on client.company_code = transaction_master.company_code where transaction_transfer_member_id.transaction_id = '1178'");
            
            if ($share_transfer_info_query->num_rows() > 0) 
            {
                $share_transfer_info_query = $share_transfer_info_query->result_array();
                //echo json_encode($share_transfer_info_query);
                foreach (($share_transfer_info_query) as $row) 
                {   
                    $register_of_transfers["company_code"] = $row["company_code"];
                    $register_of_transfers["date"] = $lodgement_date;

                    if($row["from_officer_field_type"] == "individual")
                    {
                        $register_of_transfers["transferor_office_id"] = $row["from_officer_id"];
                        $register_of_transfers["transferor_field_type"] = $row["from_officer_field_type"];
                    }
                    else if($row["from_officer_company_field_type"] == "company")
                    {
                        $register_of_transfers["transferor_office_id"] = $row["from_officer_company_id"];
                        $register_of_transfers["transferor_field_type"] = $row["from_officer_company_field_type"];
                    }
                    else if($row["from_client_company_field_type"] == "client")
                    {
                        $register_of_transfers["transferor_office_id"] = $row["from_client_company_id"];
                        $register_of_transfers["transferor_field_type"] = $row["from_client_company_field_type"];
                    }

                    if($row["to_officer_field_type"] == "individual")
                    {
                        $register_of_transfers["transferee_office_id"] = $row["to_officer_id"];
                        $register_of_transfers["transferee_field_type"] = $row["to_officer_field_type"];
                    }
                    else if($row["to_officer_company_field_type"] == "company")
                    {
                        $register_of_transfers["transferee_office_id"] = $row["to_officer_company_id"];
                        $register_of_transfers["transferee_field_type"] = $row["to_officer_company_field_type"];
                    }
                    else if($row["to_client_company_field_type"] == "client")
                    {
                        $register_of_transfers["transferee_office_id"] = $row["to_client_company_id"];
                        $register_of_transfers["transferee_field_type"] = $row["to_client_company_field_type"];
                    }
                    $register_of_transfers["new_number_share"] = $row["to_number_of_share"];
                    $register_of_transfers["new_amount_share"] = (float)$row["to_number_of_share"] * $per_share;
                    $register_of_transfers["sharetype"] = $row["sharetype"];
                    $register_of_transfers["other_class"] = $row["other_class"];
                    $register_of_transfers["currency"] = $row["currency"];
                    $this->db->insert("register_of_transfers",$register_of_transfers);
                    $register_of_transfers_id = $this->db->insert_id();

                    foreach (($transferee_array) as $transferee_row) 
                    {
                        if($register_of_transfers["transferee_office_id"] == $transferee_row->officer_id && $register_of_transfers["transferee_field_type"] == $transferee_row->field_type)
                        {
                            $new_cert_no["new_cert"] = $transferee_row->certificate;
                            $this->db->update("register_of_transfers",$new_cert_no,array("id" => $register_of_transfers_id));
                        }
                    }

                    foreach (($transferor_array) as $transferor_row) 
                    {
                        $cancel_cert_query = $this->db->query('select * from certificate where id = '.$transferor_row->certificate_id);
                        $cancel_cert_query = $cancel_cert_query->result_array();

                        if($register_of_transfers["transferor_office_id"] == $transferor_row->officer_id && $register_of_transfers["transferee_field_type"] == $transferor_row->field_type && $transferor_row->number_of_shares_to_transfer != "")
                        {
                            // foreach (($transferee_array) as $transferee_row) 
                            // {
                                // if($register_of_transfers["transferee_office_id"] == $transferee_row->officer_id && $register_of_transfers["transferee_field_type"] == $transferee_row->field_type)
                                // {
                                    $register_of_transfers_info["register_of_transfers_id"] = $register_of_transfers_id;
                                    $register_of_transfers_info["old_cert_id"] = $cancel_cert_query[0]["id"];
                                    $register_of_transfers_info["old_number_share"] = $cancel_cert_query[0]["number_of_share"];
                                    $register_of_transfers_info["old_amount_share"] = (float)$cancel_cert_query[0]["number_of_share"] * $per_share;
                                    $register_of_transfers_info["old_cert"] = $cancel_cert_query[0]["certificate_no"];
                                    $register_of_transfers_info["balance_number_share"] = str_replace(',', '', $transferor_row->new_number_of_share);
                                    $register_of_transfers_info["balance_amount_share"] = (float)str_replace(',', '', $transferor_row->new_number_of_share) * $per_share;
                                    $register_of_transfers_info["balance_cert"] = $transferor_row->certificate;
                                    // $register_of_transfers_info["new_number_share"] = $transferee_row->new_number_of_share;
                                    // $register_of_transfers_info["new_cert"] = $transferee_row->certificate;
                                    

                                    $this->db->insert("register_of_transfers_info",$register_of_transfers_info);
                                //}
                            //}
                        }
                    }




                    //register_of_transfers
                    //echo json_encode($share_transfer_info_query);
                }
            }
            
        }
    }

    public function check_share_member_can_become_controller()
    {
        $company_code = "company_1553833429";
        $transaction_master_id = "1181";

        $get_member_info = $this->db->query("select transaction_member_shares.*, z.company_name as tr_client_company_name, officer.name, officer.identification_no, officer_company.company_name, officer_company.register_no, client.company_name as client_company_name, client.registration_no, transaction_certificate.new_certificate_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join client as z on z.company_code = transaction_member_shares.company_code right join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");// AND transaction_member_shares.number_of_share > 0

        $get_member_info = $get_member_info->result_array();

        if(count($get_member_info) > 0)
        {
            for($t = 0 ; $t < count($get_member_info) ; $t++)
            {
                // if($get_member_info[$t]["number_of_share"] > 0)
                // {
                    $get_member_info[$t]['tr_client_company_name'] = $this->encryption->decrypt($get_member_info[$t]['tr_client_company_name']);
                    if($get_member_info[$t]['field_type'] == "individual")
                    {
                        $get_member_info[$t]['identification_no'] = $this->encryption->decrypt($get_member_info[$t]['identification_no']);
                        $get_member_info[$t]['name'] = $this->encryption->decrypt($get_member_info[$t]['name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['identification_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    elseif($get_member_info[$t]['field_type'] == "company")
                    {
                        $get_member_info[$t]['register_no'] = $this->encryption->decrypt($get_member_info[$t]['register_no']);
                        $get_member_info[$t]['company_name'] = $this->encryption->decrypt($get_member_info[$t]['company_name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['register_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    elseif($get_member_info[$t]['field_type'] == "client")
                    {
                        $get_member_info[$t]['registration_no'] = $this->encryption->decrypt($get_member_info[$t]['registration_no']);
                        $get_member_info[$t]['client_company_name'] = $this->encryption->decrypt($get_member_info[$t]['client_company_name']);

                        $transaction_member_info[$t]['identification_no_or_uen'] = $get_member_info[$t]['registration_no'];
                        $transaction_member_info[$t]['officer_id'] = $get_member_info[$t]['officer_id'];
                        $transaction_member_info[$t]['field_type'] = $get_member_info[$t]['field_type'];
                    }
                    $transaction_member_info[$t]['share_number'] = $get_member_info[$t]['number_of_share'];
                    $datas[] = $transaction_member_info[$t];
                //}
            }
        }
        print_r($datas);

        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key=>$row) {
                if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);

                    $member_info[$key]['identification_no_or_uen'] = $row->identification_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);

                    $member_info[$key]['identification_no_or_uen'] = $row->register_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);

                    $member_info[$key]['identification_no_or_uen'] = $row->registration_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                $member_info[$key]['share_number'] = $row->number_of_share;
                $datas[] = $member_info[$key];
            }
        }
        
        print_r($datas);

        // begin the iteration for grouping data name and calculate the amount
        $member_shares_detail = array();
        foreach($datas as $data) {
            $index = $this->data_exists($data['officer_id'], $data['field_type'], $member_shares_detail);
            if ($index < 0) {
                $member_shares_detail[] = $data;
            }
            else {
                $member_shares_detail[$index]['share_number'] +=  (int)$data['share_number'];
            }
        }

        print_r($member_shares_detail); //display 

        $total_number_of_share = 0;
        for($e = 0; $e < count($member_shares_detail); $e++ )
        {
            $number_of_share_info = (int)str_replace(',', '', $member_shares_detail[$e]["share_number"]);
            $total_number_of_share = $total_number_of_share + $number_of_share_info;
        }
        print_r($total_number_of_share.'/n');

        for($h = 0; $h < count($member_shares_detail); $h++ )
        {
            $check_controller_query = $this->db->query("SELECT client_controller.* FROM client_controller WHERE company_code = '".$company_code."' AND officer_id = '".$member_shares_detail[$h]["officer_id"]."' AND field_type = '".$member_shares_detail[$h]["field_type"]."' AND date_of_cessation = ''");

            if ($check_controller_query->num_rows() > 0) 
            {
                //print_r($check_controller_query->result_array());
                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
                if($percentage_of_controller >= 25)
                {
                    print_r("bigger");
                    print_r($transaction_member_info);
                    for($d = 0; $d < count($transaction_member_info); $d++)
                    {
                        if($transaction_member_info[$d]["officer_id"] == $member_shares_detail[$h]["officer_id"] && $transaction_member_info[$d]["field_type"] == $member_shares_detail[$h]["field_type"])
                        {
                            //need to sign notice of controller, because more than 25%
                            //$controller_detail[] = $member_shares_detail[$h];
                        }
                    }
                }
                else
                {
                    print_r("smaller");
                }
                
            }
            else
            {
                print_r($member_shares_detail[$h]);
                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
                print_r($percentage_of_controller);
                if($percentage_of_controller >= 25)
                {
                    $controller_detail[] = $member_shares_detail[$h];

                    $this->db->select('
                        transaction_member_shares.*, 
                        z.company_name as tr_client_company_name, 
                        officer.identification_no, 
                        officer.name, 
                        officer.date_of_birth, 
                        officer.address_type as officer_address_type, 
                        officer.postal_code1 as officer_postal_code, 
                        officer.street_name1 as officer_street_name,
                        officer.building_name1 as officer_builing_name,
                        officer.unit_no1 as officer_unit_no1,
                        officer.unit_no2 as officer_unit_no2,
                        officer.foreign_address1 as officer_foreign_address1,
                        officer.foreign_address2 as officer_foreign_address2,
                        officer.foreign_address3 as officer_foreign_address3,
                        nationality.nationality, 
                        officer_company.register_no, 
                        officer_company.company_name, 
                        officer_company.country_of_incorporation,
                        officer_company.address_type as officer_company_address_type,
                        officer_company.company_postal_code,
                        officer_company.company_street_name,
                        officer_company.company_building_name,
                        officer_company.company_unit_no1,
                        officer_company.company_unit_no2,
                        officer_company.company_foreign_address1,
                        officer_company.company_foreign_address2,
                        officer_company.company_foreign_address3, 
                        client.company_name as client_company_name, 
                        client.registration_no, 
                        company_type.company_type as company_type_name,
                        client.postal_code as client_postal_code, 
                        client.street_name as client_street_name,
                        client.building_name as client_builing_name,
                        client.unit_no1 as client_unit_no1,
                        client.unit_no2 as client_unit_no2,
                        ');
                    $this->db->from('transaction_member_shares');
                    $this->db->join('officer', 'officer.id = transaction_member_shares.officer_id AND officer.field_type = transaction_member_shares.field_type', 'left');
                    $this->db->join('officer_company', 'officer_company.id = transaction_member_shares.officer_id AND officer_company.field_type = transaction_member_shares.field_type', 'left');
                    $this->db->join('client', 'client.id = transaction_member_shares.officer_id AND transaction_member_shares.field_type = "client"', 'left');
                    $this->db->join('company_type', 'company_type.id = client.company_type', 'left');
                    $this->db->join('client as z', 'z.company_code = transaction_member_shares.company_code', 'left');
                    $this->db->join('nationality', 'nationality.id = officer.nationality', 'left');
                    $this->db->where('transaction_member_shares.transaction_page_id', $transaction_master_id);
                    $this->db->where('transaction_member_shares.company_code', $company_code);
                    $this->db->where('transaction_member_shares.officer_id', $member_shares_detail[$h]["officer_id"]);
                    $this->db->where('transaction_member_shares.field_type', $member_shares_detail[$h]["field_type"]);
                    $this->db->order_by("id", "asc");

                    $controller_query = $this->db->get();
                    $controller_content = $controller_query->result_array();
                    if($controller_content[0]["name"] != null)
                    {
                        $controller_content[0]["nationality_name"] = $controller_content[0]["nationality"];
                        $address = array(
                            'type'          => $controller_content[0]["officer_address_type"],
                            'street_name1'  => $controller_content[0]["officer_street_name"],
                            'unit_no1'      => $controller_content[0]["officer_unit_no1"],
                            'unit_no2'      => $controller_content[0]["officer_unit_no2"],
                            'building_name1'=> $controller_content[0]["officer_builing_name"],
                            'postal_code1'  => $controller_content[0]["officer_postal_code"],
                            'foreign_address1' => $controller_content[0]["officer_foreign_address1"],
                            'foreign_address2' => $controller_content[0]["officer_foreign_address2"],
                            'foreign_address3' => $controller_content[0]["officer_foreign_address3"]
                        );
                    }
                    else if($controller_content[0]["company_name"] != null)
                    {
                        $controller_content[0]["nationality_name"] = $controller_content[0]["country_of_incorporation"];
                        $address = array(
                            'type'          => $controller_content[0]["officer_company_address_type"],
                            'street_name1'  => $controller_content[0]["company_street_name"],
                            'unit_no1'      => $controller_content[0]["company_unit_no1"],
                            'unit_no2'      => $controller_content[0]["company_unit_no2"],
                            'building_name1'=> $controller_content[0]["company_building_name"],
                            'postal_code1'  => $controller_content[0]["company_postal_code"],
                            'foreign_address1' => $controller_content[0]["company_foreign_address1"],
                            'foreign_address2' => $controller_content[0]["company_foreign_address2"],
                            'foreign_address3' => $controller_content[0]["company_foreign_address3"]
                        );
                    }
                    else
                    {
                        $controller_content[0]["nationality_name"] = "";
                        $address = array(
                            'type'          => "Local",
                            'street_name1'  => $controller_content[0]["client_street_name"],
                            'unit_no1'      => $controller_content[0]["client_unit_no1"],
                            'unit_no2'      => $controller_content[0]["client_unit_no2"],
                            'building_name1'=> $controller_content[0]["client_builing_name"],
                            'postal_code1'  => $controller_content[0]["client_postal_code"]
                        );
                    }
                    $controller_content[0]["address"] = $this->write_address_local_foreign($address, "comma", "big_cap");
                    print_r($controller_content);

                    if($string2 == "Member to Controller")
                    {
                        $content = $controller_content;
                    }
                    else
                    {
                        $controller_query = $controller_content;

                        if($controller_query[0]["company_name"] != null)
                        {
                            $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['register_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

                            $get_corp_rep_info = $get_corp_rep_info->result_array();

                            if($string2 == "Corp Rep Or Person Controller Name")
                            {
                                $content = $get_corp_rep_info[0]["name_of_corp_rep"];
                            }
                            else if($string2 == "Corp Rep Or Person Controller identification no")
                            {
                                $content = $get_corp_rep_info[0]["identity_number"];
                            }
                        }
                        elseif($controller_query[0]['client_company_name'] != null)
                        {
                            $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['registration_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

                            $get_corp_rep_info = $get_corp_rep_info->result_array();

                            if($string2 == "Corp Rep Or Person Controller Name")
                            {
                                $content = $get_corp_rep_info[0]["name_of_corp_rep"];
                            }
                            else if($string2 == "Corp Rep Or Person Controller identification no")
                            {
                                $content = $get_corp_rep_info[0]["identity_number"];
                            }
                        }
                    }
                }
                else
                {
                    print_r("smaller");
                }

            }
        }
        print_r($content);

        //print_r($res);
       //get total share allotment
        // for($e = 0; $e < count($_POST['number_of_share']); $e++ )
        // {
        //     $number_of_share_info = (int)str_replace(',', '', $_POST['number_of_share'][$e]);
        //     $total_number_of_share = $total_number_of_share + $number_of_share_info;
        // }

        // $previous_total_share = $this->db->query("select client_member_share_capital.*, member_shares.company_code, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.amount_paid) as paid_up from client_member_share_capital left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code where client_member_share_capital.company_code = '".$_POST['company_code']."' group by client_member_share_capital.id");
        // if ($previous_total_share->num_rows() > 0) {
        //     $previous_total_share->result_array();
        //     $total_number_of_share = $total_number_of_share + $previous_total_share[0]["number_of_share"];
        // }
        //end get total share allotment
    }

    public function check_share_member_can_become_controller_in_client_module()
    {
        $company_code = "company_1553833429";

        $q = $this->db->query('select member_shares.*, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, client.registration_no, client.company_name as client_company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client on client.id = member_shares.officer_id and member_shares.field_type = "client" where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key=>$row) {
                if($row->field_type == "individual")
                {
                    $row->identification_no = $this->encryption->decrypt($row->identification_no);
                    $row->name = $this->encryption->decrypt($row->name);

                    $member_info[$key]['identification_no_or_uen'] = $row->identification_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                elseif($row->field_type == "company")
                {
                    $row->register_no = $this->encryption->decrypt($row->register_no);
                    $row->company_name = $this->encryption->decrypt($row->company_name);

                    $member_info[$key]['identification_no_or_uen'] = $row->register_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                else
                {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    $row->client_company_name = $this->encryption->decrypt($row->client_company_name);

                    $member_info[$key]['identification_no_or_uen'] = $row->registration_no;
                    $member_info[$key]['officer_id'] = $row->officer_id;
                    $member_info[$key]['field_type'] = $row->field_type;
                }
                $member_info[$key]['share_number'] = $row->number_of_share;
                $datas[] = $member_info[$key];
            }
        }
        
        //print_r($datas);

        // begin the iteration for grouping data name and calculate the amount
        $member_shares_detail = array();
        foreach($datas as $data) {
            $index = $this->transaction_word_model->data_exists($data['officer_id'], $data['field_type'], $member_shares_detail);
            if ($index < 0) {
                $member_shares_detail[] = $data;
            }
            else {
                $member_shares_detail[$index]['share_number'] +=  (int)$data['share_number'];
            }
        }

       // print_r($member_shares_detail); //display 

        $total_number_of_share = 0;
        for($e = 0; $e < count($member_shares_detail); $e++ )
        {
            $number_of_share_info = (int)str_replace(',', '', $member_shares_detail[$e]["share_number"]);
            $total_number_of_share = $total_number_of_share + $number_of_share_info;
        }
        //print_r($total_number_of_share.'/n');

        for($h = 0; $h < count($member_shares_detail); $h++ )
        {
            $check_controller_query = $this->db->query("SELECT client_controller.* FROM client_controller WHERE company_code = '".$company_code."' AND officer_id = '".$member_shares_detail[$h]["officer_id"]."' AND field_type = '".$member_shares_detail[$h]["field_type"]."' AND date_of_cessation = ''");

            if ($check_controller_query->num_rows() > 0) 
            {
                //print_r($check_controller_query->result_array());
                $check_controller_query = $check_controller_query->result_array();
                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
                print_r($percentage_of_controller."/n/n");
                if($percentage_of_controller >= 25)
                {
                    //print_r("bigger");
                    //print_r($transaction_member_info);
                    //for($d = 0; $d < count($transaction_member_info); $d++)
                    //{
                        //if($transaction_member_info[$d]["officer_id"] == $member_shares_detail[$h]["officer_id"] && $transaction_member_info[$d]["field_type"] == $member_shares_detail[$h]["field_type"])
                        //{
                            //need to sign notice of controller, because more than 25%
                            //$controller_detail[] = $member_shares_detail[$h];
                        //}
                    //}
                }
                else
                {
                    //print_r("smaller");
                    if($date != NULL)
                    {
                        $client_controller['date_of_cessation'] = $date;
                        $this->db->update("client_controller",$client_controller,array("id" => $check_controller_query[0]["id"]));
                    }
                }
                
            }
            else
            {
                //print_r($member_shares_detail[$h]);
                $percentage_of_controller = ((int)$member_shares_detail[$h]["share_number"]/(int)$total_number_of_share) * 100;
                
                if($percentage_of_controller >= 25)
                {
                    //$controller_detail[] = $member_shares_detail[$h];
                    $this->db->select('
                        member_shares.*, 
                        z.company_name as tr_client_company_name, 
                        officer.identification_no, 
                        officer.name, 
                        officer.date_of_birth, 
                        officer.address_type as officer_address_type, 
                        officer.postal_code1 as officer_postal_code, 
                        officer.street_name1 as officer_street_name,
                        officer.building_name1 as officer_builing_name,
                        officer.unit_no1 as officer_unit_no1,
                        officer.unit_no2 as officer_unit_no2,
                        officer.foreign_address1 as officer_foreign_address1,
                        officer.foreign_address2 as officer_foreign_address2,
                        officer.foreign_address3 as officer_foreign_address3,
                        nationality.nationality, 
                        officer_company.register_no, 
                        officer_company.company_name, 
                        officer_company.date_of_incorporation,
                        officer_company.country_of_incorporation,
                        officer_company.address_type as officer_company_address_type,
                        officer_company.company_postal_code,
                        officer_company.company_street_name,
                        officer_company.company_building_name,
                        officer_company.company_unit_no1,
                        officer_company.company_unit_no2,
                        officer_company.company_foreign_address1,
                        officer_company.company_foreign_address2,
                        officer_company.company_foreign_address3, 
                        client.company_name as client_company_name, 
                        client.registration_no, 
                        client.incorporation_date,
                        company_type.company_type as company_type_name,
                        client.postal_code as client_postal_code, 
                        client.street_name as client_street_name,
                        client.building_name as client_builing_name,
                        client.unit_no1 as client_unit_no1,
                        client.unit_no2 as client_unit_no2,
                        ');
                    $this->db->from('member_shares');
                    $this->db->join('officer', 'officer.id = member_shares.officer_id AND officer.field_type = member_shares.field_type', 'left');
                    $this->db->join('officer_company', 'officer_company.id = member_shares.officer_id AND officer_company.field_type = member_shares.field_type', 'left');
                    $this->db->join('client', 'client.id = member_shares.officer_id AND member_shares.field_type = "client"', 'left');
                    $this->db->join('company_type', 'company_type.id = client.company_type', 'left');
                    $this->db->join('client as z', 'z.company_code = member_shares.company_code', 'left');
                    $this->db->join('nationality', 'nationality.id = officer.nationality', 'left');
                    //$this->db->where('transaction_member_shares.transaction_page_id', $transaction_master_id);
                    $this->db->where('member_shares.company_code', $company_code);
                    $this->db->where('member_shares.officer_id', $member_shares_detail[$h]["officer_id"]);
                    $this->db->where('member_shares.field_type', $member_shares_detail[$h]["field_type"]);
                    $this->db->order_by("id", "asc");

                    $controller_query = $this->db->get();
                    $controller_content = $controller_query->result_array();
                    if($controller_content[0]["name"] != null)
                    {
                        $controller_content[0]["nationality_name"] = $controller_content[0]["nationality"];
                        $address = array(
                            'type'          => $controller_content[0]["officer_address_type"],
                            'street_name1'  => $controller_content[0]["officer_street_name"],
                            'unit_no1'      => $controller_content[0]["officer_unit_no1"],
                            'unit_no2'      => $controller_content[0]["officer_unit_no2"],
                            'building_name1'=> $controller_content[0]["officer_builing_name"],
                            'postal_code1'  => $controller_content[0]["officer_postal_code"],
                            'foreign_address1' => $controller_content[0]["officer_foreign_address1"],
                            'foreign_address2' => $controller_content[0]["officer_foreign_address2"],
                            'foreign_address3' => $controller_content[0]["officer_foreign_address3"]
                        );
                    }
                    else if($controller_content[0]["company_name"] != null)
                    {
                        $controller_content[0]["nationality_name"] = $controller_content[0]["country_of_incorporation"];
                        $address = array(
                            'type'          => $controller_content[0]["officer_company_address_type"],
                            'street_name1'  => $controller_content[0]["company_street_name"],
                            'unit_no1'      => $controller_content[0]["company_unit_no1"],
                            'unit_no2'      => $controller_content[0]["company_unit_no2"],
                            'building_name1'=> $controller_content[0]["company_building_name"],
                            'postal_code1'  => $controller_content[0]["company_postal_code"],
                            'foreign_address1' => $controller_content[0]["company_foreign_address1"],
                            'foreign_address2' => $controller_content[0]["company_foreign_address2"],
                            'foreign_address3' => $controller_content[0]["company_foreign_address3"]
                        );
                    }
                    else
                    {
                        $controller_content[0]["nationality_name"] = "";
                        $address = array(
                            'type'          => "Local",
                            'street_name1'  => $controller_content[0]["client_street_name"],
                            'unit_no1'      => $controller_content[0]["client_unit_no1"],
                            'unit_no2'      => $controller_content[0]["client_unit_no2"],
                            'building_name1'=> $controller_content[0]["client_builing_name"],
                            'postal_code1'  => $controller_content[0]["client_postal_code"]
                        );
                    }
                    $controller_content[0]["address"] = $this->transaction_word_model->write_address_local_foreign($address, "comma", "big_cap");
                    //print_r($controller_content);

                    // if($string2 == "Member to Controller")
                    // {
                        $content[] = $controller_content[0];
                        if($date != NULL)
                        {
                            $insert_client_controller["company_code"] = $company_code;
                            $insert_client_controller["officer_id"] = $member_shares_detail[$h]["officer_id"];
                            $insert_client_controller["field_type"] = $member_shares_detail[$h]["field_type"];
                            $insert_client_controller["date_of_birth"] = (($controller_content[0]["date_of_birth"] != null)? date("d/m/Y", strtotime($controller_content[0]["date_of_birth"])):(($controller_content[0]["date_of_incorporation"] != null)?$controller_content[0]["date_of_incorporation"]:(($controller_content[0]["incorporation_date"] != null)?$controller_content[0]["incorporation_date"]:"")));
                            $insert_client_controller["nationality_name"] = $controller_content[0]["nationality_name"];
                            $insert_client_controller["address"] = $controller_content[0]["address"];
                            $insert_client_controller["date_of_registration"] = $date;
                            $insert_client_controller["date_of_notice"] = $date;
                            $insert_client_controller["confirmation_received_date"] = $date;
                            $insert_client_controller["date_of_entry"] = $date;

                            $this->db->insert("client_controller",$insert_client_controller);
                        }
                    // }
                    // else
                    // {
                    //     $controller_query = $controller_content;

                    //     if($controller_query[0]["company_name"] != null)
                    //     {
                    //         $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['register_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

                    //         $get_corp_rep_info = $get_corp_rep_info->result_array();

                    //         if($string2 == "Corp Rep Or Person Controller Name for Share Member")
                    //         {
                    //             $content = $get_corp_rep_info[0]["name_of_corp_rep"];
                    //         }
                    //         else if($string2 == "Corp Rep Or Person Controller identification no for Share Member")
                    //         {
                    //             $content = $get_corp_rep_info[0]["identity_number"];
                    //         }
                    //     }
                    //     elseif($controller_query[0]['client_company_name'] != null)
                    //     {
                    //         $get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$this->encryption->decrypt($controller_query[0]['registration_no']).'" and corporate_representative.subsidiary_name = "'.$this->encryption->decrypt($controller_query[0]['tr_client_company_name']).'"');

                    //         $get_corp_rep_info = $get_corp_rep_info->result_array();

                    //         if($string2 == "Corp Rep Or Person Controller Name for Share Member")
                    //         {
                    //             $content = $get_corp_rep_info[0]["name_of_corp_rep"];
                    //         }
                    //         else if($string2 == "Corp Rep Or Person Controller identification no for Share Member")
                    //         {
                    //             $content = $get_corp_rep_info[0]["identity_number"];
                    //         }
                    //     }
                    // }
                }
                else
                {
                    //print_r("smaller");
                }

            }
        }
    }

    public function replace_activity_value()
    {
        $q = $this->db->query("select * from client");

        $q = $q->result_array();

        $business_activity_query = $this->db->query("select * from business_activity_list");

        $business_activity_query = $business_activity_query->result_array();
        $count = 0;
        $continue_with_2018 = false; 
        foreach($q as $key => $data)
        {
            // $str = $data["activity1"];
            // preg_match_all('!\d+!', $str, $matches);
            // echo($data["activity1"] . ' - ' . $matches[0][0] . "<br>");//436
            foreach($business_activity_query as $key => $BAdata) {
                if (strpos($BAdata['business_activity_name'], '2018') == false) {
                    if($data["activity1"] == 'RETAIL SALE OF COMPUTER GAMES (INCLUDING ELECTRONIC GAMES AND VIDEO GAME CONSOLES) (47413)') // activity1
                    {
                        $percentage = 86;
                    }
                    elseif($data["activity1"] == 'RETAIL SALE OF MOTOR VEHICLES EXCEPT MOTORCYCLES AND SCOOTERS (47311)' || $data["activity1"] == 'RETAIL SALE OF JEWELLERY MADE FROM PRECIOUS METALS AND STONES (47731)')
                    {
                        $percentage = 83;
                    }
                    else
                    {
                        $percentage = 80;
                    }
                    similar_text(strtoupper($data["activity1"]), strtoupper($BAdata['business_activity_name']), $similarity_pst);
                    // echo ($data["activity1"] . "<br>");
                    // echo ($BAdata['business_activity_name'] . "<br>");
                    // echo ($similarity_pst . "<br>");
                    if (number_format($similarity_pst, 0) > $percentage){
                        $too_similar = $BAdata['business_activity_name'];

                        echo($data["activity1"] . ' - ' . $too_similar . "<br>");
                        
                        //$updateData['activity1'] = $too_similar;
                        //$this->db->update("client",$updateData,array("company_code" =>  $data['company_code']));

                        $count = $count + 1;
                        $continue_with_2018 = false;
                        break;
                    }
                    else
                    {
                        $continue_with_2018 = true;
                    }
                }
            }

            if($continue_with_2018)
            {
                foreach($business_activity_query as $key => $BAdata2) {
                    if (strpos($BAdata2['business_activity_name'], '2018') !== false) {
                        if($data["activity1"] == 'RETAIL SALE OF COMPUTER GAMES (INCLUDING ELECTRONIC GAMES AND VIDEO GAME CONSOLES) (47413)')
                        {
                            $percentage = 86;
                        }
                        elseif($data["activity1"] == 'RETAIL SALE OF MOTOR VEHICLES EXCEPT MOTORCYCLES AND SCOOTERS (47311)' || $data["activity1"] == 'RETAIL SALE OF JEWELLERY MADE FROM PRECIOUS METALS AND STONES (47731)')//47311//47731
                        {
                            $percentage = 83;
                        }
                        else
                        {
                            $percentage = 70;
                        }
                        similar_text(strtoupper($data["activity1"]), strtoupper($BAdata2['business_activity_name']), $similarity_pst2);
                        if (number_format($similarity_pst2, 0) > $percentage){
                            $too_similar = $BAdata2['business_activity_name'];
                            echo($data["activity1"] . ' - ' . $too_similar . "<br>");

                            //$updateData['activity1'] = $too_similar;
                            //$this->db->update("client",$updateData,array("company_code" =>  $data['company_code']));

                            $count = $count + 1;
                            $continue_with_2018 = false;
                            break;
                        }
                    }
                }
            }
            
            // $this->db->where('id', $data['id']);    
            // $this->db->update('client', $officer);
        }
        echo $count;
    }

    public function restore_backup_file_test()
    {
        $dbfile = "db-backup-on-2020-05-08-16-25-01";
        //$file = file_get_contents('./files/backups/' . $dbfile . '.txt');
        //echo json_encode(htmlentities($file));
        $sql_contents = file_get_contents('./files/backups/' . $dbfile . '.txt');
        $sql_contents = explode(";\n", $this->encrypt->decode($sql_contents));

        foreach($sql_contents as $query)
        {
            //echo json_encode(trim($query)."<br>");
            // $pos = strpos(trim($query),'ci_sessions');
            // var_dump($pos);
            $pos = trim($query);
            //var_dump($pos);
            if($pos)
            {
                $result = $this->db->query($query);
            }
        }
        //$this->db->conn_id->multi_query(htmlentities($file));//$this->encrypt->decode(
        //$this->db->conn_id->close();
    }

    public function test_fye_date()
    {
        //$original_fye_date = "28 February 2019";
        //$original_fye_date = "29 February 2020";
        $original_fye_date = "28 February 2021";

        $dm = date('d F', strtotime($original_fye_date));

        if($dm == "28 February")
        {
            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

            $dt = new DateTime($fye_date);

            $dt->modify( 'first day of next month' );
            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');
        }
        else if($dm == "29 February")
        {
            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

            $dt = new DateTime($fye_date);

            //$dt->modify( 'first day of next month' );
            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');
        }
        
        echo $dt->format('Y-m-d'), "\n";

        //echo json_encode($fye_date);
        
    }

    // for search if a data has been added into $amount, returns the key (index)
    public function data_exists($officer_id, $field_type, $array) {
        $result = -1;
        for($i=0; $i<sizeof($array); $i++) {
            if ($array[$i]['officer_id'] == $officer_id && $array[$i]['field_type'] == $field_type) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    // latest version include foreign address. 
    public function write_address_local_foreign($address, $type, $style)
    {
        $unit = '';
        $unit_building_name = '';

        if($type == "normal")
        {
            $br1 = '';
            $br2 = '';
        }
        elseif($type == "letter")
        {
            $br1 = ', <br/>';
            $br2 = ', <br/>';
        }
        elseif($type == "comma")
        {
            $br1 = ', ';
            $br2 = ', ';
        }

        if($address['type'] == "Local")
        {
            // Add unit
            if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
            {
                $unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
            }
            else
            {
                if($type != "letter")
                {
                    $br2 = '';
                }
            }

            // Add building
            if(!empty($address['building_name1']) && !empty($unit))
            {
                $unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
            }
            elseif(!empty($unit))
            {
                $unit_building_name = $unit . $br2;
            }
            elseif(!empty($address['building_name1']))
            {
                $unit_building_name = $address['building_name1'] . $br2;
            }

            if($style == "big_cap")
            {
                $sg_word = 'SINGAPORE ';
            }
            else
            {
                $sg_word = 'Singapore ';
            }

            return $address['street_name1'] . $br1 . $unit_building_name . $sg_word . $address['postal_code1'];
        }
        else if($address['type'] == "Foreign")
        {
            $foreign_address1 = !empty($address["foreign_address1"])? $address["foreign_address1"]: '';

            if(!empty($address["foreign_address1"]))
            {
                if(substr($address["foreign_address1"], -1) == ",")
                {
                    $foreign_address1 = rtrim($address["foreign_address1"],',');    // remove , if there is any at last character
                }
                else
                {
                    $foreign_address1 = $address["foreign_address1"];
                }
            }

            if(!empty($address["foreign_address2"]))
            {
                if(substr($address["foreign_address2"], -1) == ",")
                {
                    $foreign_address2 = $br1 . rtrim($address["foreign_address2"],',');     // remove , if there is any at last character
                }
                else
                {
                    $foreign_address2 = $br1 . $address["foreign_address2"];
                }
            }
            else
            {
                $foreign_address2 = '';
            }

            $foreign_address3 = !empty($address["foreign_address3"])? $br2 . $address["foreign_address3"]: '';

            return $foreign_address1.$foreign_address2.$foreign_address3;
        }
    }

    public function detect_payment_voucher_no()
    {
        //$where = "firm_id = '".$this->session->userdata('firm_id')."'";
        $current_year = date("Y");

        $query_min_payment_voucher_no = $this->db->query("select MIN(CAST(SUBSTRING(payment_voucher_no, 1, 5) AS UNSIGNED)) as latest_payment_voucher_no, SUBSTRING(payment_voucher_no, -4) as latest_year from transaction_dividend_list where SUBSTRING(payment_voucher_no, -4) = '".$current_year."'");

        if ($query_min_payment_voucher_no->num_rows() > 0) 
        {
            $query_min_payment_voucher_no = $query_min_payment_voucher_no->result_array();

            if($query_min_payment_voucher_no[0]["latest_payment_voucher_no"] == 1)
            {
                $query_max_payment_voucher_no = $this->db->query("select MAX(CAST(SUBSTRING(payment_voucher_no, 1, 5) AS UNSIGNED)) as latest_payment_voucher_no, SUBSTRING(payment_voucher_no, -4) as latest_year from transaction_dividend_list where SUBSTRING(payment_voucher_no, -4) = '".$current_year."'");

                if ($query_max_payment_voucher_no->num_rows() > 0) 
                {
                    $query_max_payment_voucher_no = $query_max_payment_voucher_no->result_array();

                    $last_section_max_payment_voucher_no = $query_max_payment_voucher_no[0]["latest_payment_voucher_no"];

                    $payment_voucher_number = ($last_section_max_payment_voucher_no + 1) . " / " . date("Y");
                }
            }
            else
            {
                $payment_voucher_number = "00001 / ".date("Y");
            }
        }
        else
        {
            $payment_voucher_number = "00001 / ".date("Y");
        }

        echo json_encode($payment_voucher_number);
        
    }

    public function check_firm_gst_date()
    {
        // $check_gst_status_query = $this->db->query("SELECT gst_firm.* FROM firm LEFT JOIN gst_firm ON gst_firm.firm_id = firm.id AND CURDATE() between gst_firm.register_date and gst_firm.deregister_date WHERE id = '".$this->session->userdata("firm_id")."'");
        $company_code = "company_1553766584";
        $currency = "1";

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$this->session->userdata("firm_id")."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $check_gst_status_array = $check_gst_status_query->result_array();

            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description, gst_category_info.start_date as gst_start_date, gst_category_info.end_date as gst_end_date, gst_category_info.rate 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category on billing_info_service_category.id = our_service_info.service_type 
                LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL) 
                where client_billing_info.company_code = '".$company_code."' and client_billing_info.currency = '".$currency."' and client_billing_info.deleted = 0");

            $services = $p->result_array();
        }
        else
        {
            $p = $this->db->query("select client_billing_info.*, our_service_info.service_type, our_service_info.service_name, billing_info_service_category.category_description 
                FROM client_billing_info 
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                LEFT JOIN billing_info_service_category ON billing_info_service_category.id = our_service_info.service_type 
                WHERE client_billing_info.company_code = '".$company_code."' and client_billing_info.currency = '".$currency."' and client_billing_info.deleted = 0");

            if ($p->num_rows() > 0) 
            {
                foreach (($p->result_array()) as $row) 
                {
                    $row["rate"] = NULL;
                    $data[] = $row;
                }
            }

            $services = $data;
        }

        echo json_encode($services);
    }

    public function check_person_data()
    {
        $check_person_data = $this->db->query("SELECT * FROM officer WHERE DATE_ADD(officer.identification_expiry_date, INTERVAL 1 MONTH) = CURRENT_DATE()");

        echo json_encode($check_person_data->result_array());
    }

    public function convertPDF()
    {
        // $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // \PhpOffice\PhpWord\Settings::setPdfRendererPath('vendor/dompdf/dompdf');
        // \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

        // //Load temp file
        // $phpWord = \PhpOffice\PhpWord\IOFactory::load($_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/DRIW-Dividends - BENZ RECOVERY PTE. LTD..docx'); 

        // //Save it
        // $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
        // $xmlWriter->save($_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/result.pdf');



        $converter = new OfficeConverter($_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/DRIW-Dividends - BENZ RECOVERY PTE. LTD..docx');
        $converter->convertTo($_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/result.pdf'); //generates pdf file in same directory as test-file.docx
    }

    public function get_member_director_list()
    {
        $director_member_result = $this->transaction_word_model->getWordValue($transaction_master_id, "get_director_with_member_result", "company_1545964448", "18", null, "Financial Support Letter");

        echo json_encode($director_member_result);
    }

    public function insert_officer_detail_to_db()
    {
        $total_column = 157;
        $q = $this->db->query("SELECT * FROM officer");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/person_detail.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $new_sheet->getStyle("F2:F156")->getNumberFormat()->setFormatCode("YYYY-MM-DD");
        $column_num = 2;
        $insert = true;

        // Get the value from cell A1
        //$cellValue = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
        for($t = 2; $t < $total_column; $t++)
        {
            $excel_identification_no = strtoupper($new_sheet->getCell('D'.$t)->getValue());
            $excel_name = strtoupper($new_sheet->getCell('B'.$t)->getValue());
            $excel_identification_type = $new_sheet->getCell('C'.$t)->getValue();
            $excel_nationality = $new_sheet->getCell('E'.$t)->getValue(); //nationality 
            $excel_date_of_birth = $new_sheet->getCell('F'.$t)->getValue();
            $date_of_birth = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($excel_date_of_birth)); 
            $excel_address_type = $new_sheet->getCell('H'.$t)->getValue();
            $excel_postal_code1 = $new_sheet->getCell('I'.$t)->getValue();
            $excel_street_name1 = $new_sheet->getCell('J'.$t)->getValue();
            $excel_building_name1 = $new_sheet->getCell('K'.$t)->getValue();
            $excel_unit_no1 = $new_sheet->getCell('L'.$t)->getValue();
            $excel_unit_no2 = $new_sheet->getCell('M'.$t)->getValue();
            $excel_foreign_address1 = $new_sheet->getCell('N'.$t)->getValue();
            $excel_foreign_address2 = $new_sheet->getCell('O'.$t)->getValue();
            $excel_foreign_address3 = $new_sheet->getCell('P'.$t)->getValue();

            $query_nationality = $this->db->query("SELECT * FROM nationality WHERE code = '".$excel_nationality."'");
            $arr_query_nationality = $query_nationality->result_array();

            //echo($excel_identification_no." Name: ".$excel_name. " excel_date_of_birth: ".$test_date."<br>");
            for($num = 0; $num < count($q); $num++)
            {
                $identification_no = $this->encryption->decrypt($q[$num]['identification_no']);
                $company_name = $this->encryption->decrypt($q[$num]['company_name']);

                if($identification_no == $excel_identification_no)
                {
                    $insert = false;
                    //break;
                }

                $column_num++;
            }

            if($insert)
            {
                echo($excel_identification_no." Name: ".$excel_name. " excel_date_of_birth: ".$date_of_birth."<br>");
                $data["user_admin_code_id"] = 1;
                $data["field_type"] = "individual";
                $data["identification_no"] = $this->encryption->encrypt($excel_identification_no);
                $data["name"] = $this->encryption->encrypt($excel_name);
                $data["identification_type"] = $excel_identification_type;
                $data["nationality"] = $arr_query_nationality[0]["id"];
                $data["date_of_birth"] = ($date_of_birth != "2020-08-04")?$date_of_birth:"1970-01-01";
                $data["address_type"] = ($excel_address_type != NULL)?$excel_address_type:"";
                $data["postal_code1"] = ($excel_postal_code1 != NULL)?$excel_postal_code1:"";
                $data["street_name1"] = ($excel_street_name1 != NULL)?$excel_street_name1:"";
                $data["building_name1"] = ($excel_building_name1 != NULL)?$excel_building_name1:"";
                $data["unit_no1"] = ($excel_unit_no1 != NULL)?$excel_unit_no1:"";
                $data["unit_no2"] = ($excel_unit_no2 != NULL)?$excel_unit_no2:"";
                $data["alternate_address"] = "0";
                $data["foreign_address1"] = ($excel_foreign_address1 != NULL)?$excel_foreign_address1:"";
                $data["foreign_address2"] = ($excel_foreign_address2 != NULL)?$excel_foreign_address2:"";
                $data["foreign_address3"] = ($excel_foreign_address3 != NULL)?$excel_foreign_address3:"";
                $data["created_by"] = 82;

                $this->db->insert("officer",$data);
            }
            $insert = true;
        }

        // $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        // $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/client_contact_list_with_email.xls';

        // $writer->save($filename);
        
        // echo json_encode("success");
    }

    public function add_roc_service()
    {
        $client_q = $this->db->query("SELECT * FROM client WHERE client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND firm_id = 26");

        $client_q = $client_q->result_array();

        for($num = 0; $num < count($client_q); $num++)
        {
            $this->db->select('MAX(client_billing_info_id) as max_client_billing_id');
            $this->db->from('client_billing_info');
            $this->db->where('company_code', $client_q[$num]['company_code']);
            $row = $this->db->get();
            $row_max_id = $row->result_array();

            if (!$row->num_rows())
            {   
                $max_id = 0;
            } 
            else 
            {
                $max_id = (int)$row_max_id[0]['max_client_billing_id'];
            }

            $client_billing_info['company_code'] = $client_q[$num]['company_code'];
            $client_billing_info['client_billing_info_id'] = $max_id + 1;
            $client_billing_info['service'] = 178;
            $client_billing_info['invoice_description'] = "Being professional fees for updating and lodge Registrable Controllers information with ACRA.";
            //(int)str_replace(',', '', $amount[$p]);
            $client_billing_info['amount'] = 100.00;
            $client_billing_info['currency'] = 1;
            $client_billing_info['unit_pricing'] = 7;
            $client_billing_info['servicing_firm'] = 26;
            $client_billing_info['deactive'] = 0;

            $this->db->insert("client_billing_info",$client_billing_info);
        }
        
    }

    public function update_recurring_billing_gst()
    {
        // $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax,
        //                             currency.currency as currency_name from firm 
        //                             LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
        //                             LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
        //                             LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
        //                             LEFT JOIN currency ON currency.id = firm.firm_currency
        //                             where firm.id = '".$firm_id."'");

        // $query = $query->result_array();
        // $gst_checkbox = $query[0]['gst_checkbox'];
        $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where recurring_billing.firm_id = '26' AND recurring_billing.amount != '0.00'"); //send on = 17/1 // AND recurring_billing.recu_invoice_issue_date = '01/02/2020' // AND recurring_billing.firm_id = '18' // AND recurring_billing.firm_id = 21//  recurring_billing.recu_invoice_issue_date = '22/07/2020' AND // LIMIT 10 // recurring_billing.firm_id = 26
            //client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.firm_id = '18' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00'
        $q = $q->result_array();

        //echo json_encode($q);

        for($t= 0; $t < count($q); $t++)
        {
            $got_gst = false;
            $sub_total = 0; $gst = 0;

            $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '26'");
            //26
            if ($check_gst_status_query->num_rows() > 0) 
            {
                $got_gst = true;
                $check_gst_status_array = $check_gst_status_query->result_array();

                $gst_attribute = ", gst_category_info.rate as gst_category_info_rate, gst_category_info.gst_category_id";
                $gst_where = "LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                    LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL)";
            }
            else
            {
                $got_gst = false;
                $gst_attribute = "";
                $gst_where = "";
            }

            $p = $this->db->query("select recurring_billing.id, recurring_billing.firm_id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, client_billing_info.service as client_billing_info_service, recurring_billing_service.gst_new_way".$gst_attribute."
                FROM recurring_billing 
                LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id 
                LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service
                ".$gst_where." 
                where recurring_billing.id =".$q[$t]["id"]." ORDER BY recurring_billing_service.id");

            $p = $p->result_array();
            //print_r($p);
            for($z = 0; $z < count($p); $z++)
            {
                //update recurring billing service date

                $sub_total += (float)$p[$z]['amount'];

                $gst += round((($p[$z]['gst_category_info_rate'] / 100) * (float)$p[$z]['amount']), 2);

                $recurring_billing_service_data = array(
                    'gst_category_id' => $p[$z]["gst_category_id"],
                    'gst_rate' => $p[$z]["gst_category_info_rate"],
                    'gst_new_way' => 1
                );

                $this->db->where('id', $p[$z]["recurring_billing_service_id"]);
                $this->db->update('recurring_billing_service', $recurring_billing_service_data);
            }
            $total = $sub_total + $gst;

            $recurring_billing_data = array(
                'amount' => $total,
                'outstanding' => $total
            );
            $this->db->where('id', $q[$t]["id"]);
            $this->db->update('recurring_billing', $recurring_billing_data);
        }
    }

    public function update_transaction_master_completed()
    {
        $transaction_master_completed = array(
            'completed' => 1
        );

        $this->db->where('status != 1');
        $this->db->update('transaction_master', $transaction_master_completed);
    }

    public function test_http()
    {
        //$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        //$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
        echo json_encode($protocol);
    }

    public function generate_money_leading_client()
    {
        $q = $this->db->query("SELECT client.company_name, client.activity1, client.activity2 FROM client where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1'");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/company_list_with_business_activity.xls");

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;

        for($num = 0; $num < count($q); $num++)
        {
            if(preg_match("/MONEY-LENDING/i", $q[$num]["activity1"]) || preg_match("/MONEY-LENDING/i", $q[$num]["activity2"]))
            {
                $new_sheet->setCellValue('A'.$column_num, $this->encryption->decrypt($q[$num]['company_name']));
                if(preg_match("/MONEY-LENDING/i", $q[$num]["activity1"]))
                {
                    $new_sheet->setCellValue('B'.$column_num, $q[$num]['activity1']);
                }
                else if(preg_match("/MONEY-LENDING/i", $q[$num]["activity2"]))
                {
                    $new_sheet->setCellValue('B'.$column_num, $q[$num]['activity2']);
                }

                $column_num++;
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/company_list_with_business_activity.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function update_our_services_description()
    {
         $p = $this->db->query("SELECT transaction_service_proposal_service_info.*, our_service_info.service_proposal_description as our_service_proposal_description FROM transaction_service_proposal_service_info LEFT JOIN our_service_info ON our_service_info.id = transaction_service_proposal_service_info.our_service_id");

         $p = $p->result_array();
        //print_r($p);
        for($z = 0; $z < count($p); $z++)
        {
            $sp["service_proposal_description"] = $p[$z]['our_service_proposal_description'];

            $this->db->where('id', $p[$z]["id"]);
            $this->db->update('transaction_service_proposal_service_info', $sp);
        }
    }

    public function generate_services_with_invoice()
    {
        $date_filter = 'AND STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("01/01/2021","%d/%m/%Y") and STR_TO_DATE("31/05/2021","%d/%m/%Y")';

        // $q = $this->db->query("SELECT billing.invoice_date, billing.company_name, billing.invoice_no, our_service_info.service_name, billing_service.invoice_description, billing_service.amount FROM billing 
        //     LEFT JOIN billing_service ON billing_service.billing_id = billing.id
        //     LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
        //     LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
        //     WHERE billing.status != 1 AND billing.firm_id = 18 ". $date_filter." ORDER BY billing.id");
        $column_num = 2;
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/invoice_with_services.xls"); 

        $new_sheet = $spreadsheet->getActiveSheet();

        $billing_data = $this->db->query("SELECT billing.* FROM billing WHERE billing.firm_id = 26 AND billing.status = 0 ".$date_filter."");

        if ($billing_data->num_rows())
        {
            $billing_data_array = $billing_data->result_array();

            for($p = 0; $p < count($billing_data_array); $p++)
            {
                $check_is_come_from_services_list = $this->db->query('select transaction_master_with_billing.*, transaction_master.transaction_task_id from transaction_master_with_billing left join transaction_master on transaction_master.id =  transaction_master_with_billing.transaction_master_id where billing_id = "'.$billing_data_array[$p]['id'].'"');

                if ($check_is_come_from_services_list->num_rows() > 0) 
                {
                    $check_is_come_from_services = $check_is_come_from_services_list->result_array();

                    if($check_is_come_from_services[0]["transaction_task_id"] != "1")
                    {
                        $qb_customer_id = ", client_qb_id.qb_customer_id";
                        $left_join_client = " LEFT JOIN client ON client.company_code = billing.company_code LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency ";

                        if($check_is_come_from_services[0]["transaction_task_id"] == "4" || $check_is_come_from_services[0]["transaction_task_id"] == "33" || $check_is_come_from_services[0]["transaction_task_id"] == "34")
                        {
                            $left_join_client = $left_join_client." LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                                LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service ";
                        }
                        else
                        {
                            $left_join_client = $left_join_client." LEFT JOIN our_service_info ON our_service_info.id = billing_service.service ";
                        }
                    }
                    else
                    {
                        $qb_customer_id = ", transaction_client_qb_id.qb_customer_id";
                        $left_join_client = " LEFT JOIN transaction_client ON transaction_client.company_code = billing.company_code 
                        LEFT JOIN transaction_client_billing_info ON transaction_client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = transaction_client_billing_info.service
                        LEFT JOIN transaction_client_qb_id ON transaction_client_qb_id.company_code = billing.company_code AND transaction_client_qb_id.currency_name = currency.currency ";
                    }

                    $billing_service_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, our_service_info.service_name, gst_category.category as gst_category_name, currency.currency as currency_name".$qb_customer_id."
                        FROM billing 
                        LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                        LEFT JOIN currency ON currency.id = billing.currency_id
                        ".$left_join_client."
                        LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                        WHERE billing.id = '".$billing_data_array[$p]['id']."' ORDER BY billing_service.id");
                }
                else
                {
                    $billing_service_info = $this->db->query("SELECT billing.*, billing_service.*, billing_service.id as billing_service_id, billing_service.amount as billing_service_amount, client_qb_id.qb_customer_id, our_service_info.service_name, currency.currency as currency_name, gst_category.category as gst_category_name FROM billing 
                        LEFT JOIN billing_service ON billing_service.billing_id = billing.id 
                        LEFT JOIN client ON client.company_code = billing.company_code 
                        LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service 
                        LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service 
                        LEFT JOIN currency ON currency.id = billing.currency_id 
                        LEFT JOIN gst_category ON gst_category.id = billing_service.gst_category_id 
                        LEFT JOIN client_qb_id ON client_qb_id.company_code = billing.company_code AND client_qb_id.currency_name = currency.currency 
                        WHERE billing.id = '".$billing_data_array[$p]['id']."' ORDER BY billing_service.id");
                }

                $q = $billing_service_info->result_array();
                
                for($num = 0; $num < count($q); $num++)
                {
                    $new_sheet->setCellValue('A'.$column_num, $q[$num]['invoice_date']);
                    $new_sheet->setCellValue('B'.$column_num, $q[$num]['company_name']);
                    $new_sheet->setCellValue('C'.$column_num, $q[$num]['invoice_no']);
                    $new_sheet->setCellValue('D'.$column_num, $q[$num]['service_name']);
                    $new_sheet->setCellValue('E'.$column_num, $q[$num]['invoice_description']);
                    $new_sheet->setCellValue('F'.$column_num, $q[$num]['amount']);

                    $column_num++;
                }
            }
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/invoice_with_services.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function generate_services_with_recurring_invoice()
    {
        $q = $this->db->query("SELECT recurring_billing.invoice_date, client.company_name, recurring_billing.invoice_no, our_service_info.service_name, recurring_billing_service.invoice_description, recurring_billing_service.amount FROM recurring_billing 
            LEFT JOIN client ON client.company_code = recurring_billing.company_code AND client.deleted = 0
            LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id
            LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service
            LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
            WHERE recurring_billing.status != 1 AND recurring_billing.firm_id = 26 ORDER BY recurring_billing.id");

        $q = $q->result_array();
        print_r($q);

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/invoice_with_services.xls"); 

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            $new_sheet->setCellValue('A'.$column_num, $q[$num]['invoice_date']);
            $new_sheet->setCellValue('B'.$column_num, $this->encryption->decrypt($q[$num]['company_name']));
            $new_sheet->setCellValue('C'.$column_num, $q[$num]['invoice_no']);
            $new_sheet->setCellValue('D'.$column_num, $q[$num]['service_name']);
            $new_sheet->setCellValue('E'.$column_num, $q[$num]['invoice_description']);
            $new_sheet->setCellValue('F'.$column_num, $q[$num]['amount']);

            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/invoice_with_services.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function generate_client_qb_list()
    {
        $q = $this->db->query("SELECT client.company_name, transaction_client.company_name as transaction_company_name, client_qb_id.currency_name FROM client_qb_id 
            LEFT JOIN client ON client.company_code = client_qb_id.company_code
            LEFT JOIN transaction_client ON transaction_client.company_code = client_qb_id.company_code
            ORDER BY client_qb_id.company_code");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/client_quickbook_list.xls"); 

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            if($q[$num]["transaction_company_name"] != null)
            {
                $new_sheet->setCellValue('A'.$column_num, trim($this->encryption->decrypt($q[$num]["transaction_company_name"])." (".$q[$num]["currency_name"].")"));
            }
            else
            {
                $new_sheet->setCellValue('A'.$column_num, trim($this->encryption->decrypt($q[$num]["company_name"])." (".$q[$num]["currency_name"].")"));
            }

            print_r(trim($this->encryption->decrypt($q[$num]["company_name"])." (".$q[$num]["currency_name"].")"));
            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/client_quickbook_list.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function generate_vendor_list()
    {
        $q = $this->db->query("SELECT * FROM `vendor_info` LEFT JOIN vendor_contact_info ON vendor_contact_info.supplier_code = vendor_info.supplier_code LEFT JOIN vendor_contact_info_phone ON vendor_contact_info_phone.vendor_contact_info_id = vendor_contact_info.id LEFT JOIN vendor_contact_info_email ON vendor_contact_info_email.vendor_contact_info_id = vendor_contact_info.id WHERE `deleted` = 0");

        $q = $q->result_array();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/vendor_list.xls"); 

        $new_sheet = $spreadsheet->getActiveSheet();
        $column_num = 2;
        for($num = 0; $num < count($q); $num++)
        {
            if($q[$num]["company_name"] != null)
            {
                $new_sheet->setCellValue('A'.$column_num, trim($q[$num]["company_name"]));
            }
            if($q[$num]["name"] != null)
            {
                $new_sheet->setCellValue('C'.$column_num, trim($q[$num]["name"]));
            }
            if($q[$num]["phone"] != null)
            {
                $new_sheet->setCellValue('D'.$column_num, trim($q[$num]["phone"]));
            }
            if($q[$num]["email"] != null)
            {
                $new_sheet->setCellValue('E'.$column_num, trim($q[$num]["email"]));
            }

            if(!empty($q[$num]['unit_no1']) && !empty($q[$num]['unit_no2']))
            {
                $unit = '#' . $q[$num]['unit_no1'] . '-' . $q[$num]['unit_no2'];
            }

            // Add building
            if(!empty($q[$num]['building_name']) && !empty($unit))
            {
                $unit_building_name = $unit . ' ' . $q[$num]['building_name'] . ",";
            }
            elseif(!empty($unit))
            {
                $unit_building_name = $unit . ",";
            }
            elseif(!empty($address['building_name']))
            {
                $unit_building_name = $address['building_name'] . ",";
            }

            $sg_word = 'Singapore ';

            $new_sheet->setCellValue('B'.$column_num, $q[$num]['street_name'] . $br1 . $unit_building_name . $sg_word . $q[$num]['postal_code']);
            
            //return $address['street_name1'] . $br1 . $unit_building_name . $sg_word . $address['postal_code1'];

            $column_num++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/pdf/document/vendor_list.xls';

        $writer->save($filename);
        
        echo json_encode("success");
    }

    public function test_get_gst_rate()
    {
        $got_gst = false;

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = 28");

        print_r($check_gst_status_query->result_array());

        if ($check_gst_status_query->num_rows() > 0) 
        {
            $got_gst = true;
            $check_gst_status_array = $check_gst_status_query->result_array();

            $gst_attribute = ", gst_category_info.rate as gst_category_info_rate, gst_category_info.gst_category_id";
            $gst_where = "LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL)";
        }
        else
        {
            $got_gst = false;
            $gst_attribute = "";
            $gst_where = "";
        }

        $p = $this->db->query("select recurring_billing.id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, client_billing_info.service as client_billing_info_service, recurring_billing_service.gst_new_way".$gst_attribute."
            FROM recurring_billing 
            LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id 
            LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service
            ".$gst_where." 
            where recurring_billing.id =678 ORDER BY recurring_billing_service.id");

        $p = $p->result_array();

        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax,
                                    currency.currency as currency_name from firm 
                                    LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                    LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                    LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                    LEFT JOIN currency ON currency.id = firm.firm_currency
                                    where firm.id = 28");

        $query = $query->result_array();

        print_r($p);
    }
    // public function update_table_db()
    // {
    //     ALTER TABLE `transaction_tasks` ADD `test` INT NOT NULL AFTER `deleted`;
    // }

    // public function encrypt($to = 'World')
    // {
    //     //echo "Hello {$to}!".PHP_EOL;
    //     $this->load->library('encrypt');

    //     $this->encrypt_officer();
    //     $this->encrypt_officer_company();
    // }

    // public function encrypt_officer()
    // {
    //     $q = $this->db->query("select * from officer");

    //     $q = $q->result_array();

    //     foreach($q as $key => $data)
    //     {
    //         $officer['identification_no'] = $this->encrypt->encode($data['identification_no']);
    //         $officer['name'] = $this->encrypt->encode($data['name']);

    //         $this->db->where('id', $data['id']);    
    //         $this->db->update('officer', $officer);
    //     }
    // }

    // public function encrypt_officer_company()
    // {
    //     $query_officer_company = $this->db->query("select * from officer_company");

    //     $query_officer_company = $query_officer_company->result_array();

    //     foreach($query_officer_company as $key => $data_officer_company)
    //     {
    //         $officer_company['register_no'] = $this->encrypt->encode($data_officer_company['register_no']);
    //         $officer_company['company_name'] = $this->encrypt->encode($data_officer_company['company_name']);

    //         $this->db->where('id', $data_officer_company['id']);    
    //         $this->db->update('officer_company', $officer_company);
    //     }
    // }
}