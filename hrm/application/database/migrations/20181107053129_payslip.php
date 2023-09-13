<?php

class Migration_payslip extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'employee_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'payslip_for' => array(
                'type' => 'DATE'
            ),
            'date' => array(
                'type' => 'DATE'
            ),
            'department' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'pv_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'basic_salary' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'aws' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'bonus' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'commission' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'cdac' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'salary_advancement' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'unpaid_leave' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'health_incentive' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'other_incentive' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'cpf_employer' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'cpf_employee' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'sd_levy' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'generate_by' => array(
                'type' => 'DATE'
            ),
            'remaining_al' => array(
                'type' => 'INT',
                'constraint' => 10
            ),
            'payment_mode' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'shown' => array(
                'type' => 'BOOLEAN'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payslip');
    }

    public function down() {
        $this->dbforge->drop_table('payslip');
    }

}