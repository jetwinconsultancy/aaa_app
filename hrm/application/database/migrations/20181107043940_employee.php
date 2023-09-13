<?php

class Migration_employee extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'nric_fin_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'address' => array(
                'type' => 'TEXT'
            ),
            'phoneno' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'nationality_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'dob' => array(
                'type' => 'DATE'
            ),
            'date_joined' => array(
                'type' => 'DATE'
            ),
            'date_cessation' => array(
                'type' => 'DATE',
                'null' => true
            ),
            'designation' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'salary' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'workpass' => array(
                'type' => 'VARCHAR',
                'constraint' => 150
            ),
            'pass_expire' => array(
                'type' => 'DATE',
                'null' => true
            ),
            'annual_leave_year' => array(
                'type' => 'int',
                'constraint' => 10
            ),
            'remaining_annual_leave' => array(
                'type' => 'int',
                'constraint' => 10
            ),
            'aws_given' => array(
                'type' => 'BOOLEAN'
            ),
            'cpf_employee' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'cpf_employer' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'cdac' => array(
                'type' => 'decimal',
                'constraint' => '10,2'
            ),
            'remark' => array(
                'type' => 'TEXT'
            ),
            'supervisor' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'department' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'firm_id' => array(
                'type' => 'int',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('employee');
    }

    public function down() {
        $this->dbforge->drop_table('employee');
    }

}