<?php

class Migration_experience extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'applicant_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'position' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'company_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'join_month' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'join_year' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'specialization' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'role' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'country' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'industry' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'position_level' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'monthly_salary_currency' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'monthly_salary_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'applicant_id' => array(
                'type' => 'INT',
                'constraint' => 100
            ),
            'experience_description' => array(
                'type' => 'TEXT'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('experience');
    }

    public function down() {
        $this->dbforge->drop_table('experience');
    }

}