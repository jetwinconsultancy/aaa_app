<?php

class Migration_applicant extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'position' => array(
                'type' => 'VARCHAR',
                'constraint' => 150
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'phoneno' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'ic_passport_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'nationality_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'is_pr_singaporean' => array(
                'type' => 'BOOLEAN'
            ),
            'address' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            // 'postal_code' => array(
            //     'type' => 'VARCHAR',
            //     'constraint' => 100
            // ),
            // 'street_name' => array(
            //     'type' => 'VARCHAR',
            //     'constraint' => 200
            // ),
            // 'building_name' => array(
            //     'type' => 'VARCHAR',
            //     'constraint' => 200
            // ),
            // 'unit_no_floor' => array(
            //     'type' => 'VARCHAR',
            //     'constraint' => 100
            // ),
            // 'unit_no' => array(
            //     'type' => 'VARCHAR',
            //     'constraint' => 100
            // ),
            'dob' => array(
                'type' => 'DATETIME'
            ),
            'gender' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'skills' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'expected_salary' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'last_drawn_salary' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'uploaded_resume' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'about' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'pic' => array(
                'type' => 'LONGTEXT'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('applicant');
    }

    public function down() {
        $this->dbforge->drop_table('applicant');
    }

}