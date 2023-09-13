<?php

class Migration_education extends CI_Migration {

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
            'uni_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'graduate_month' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'graduate_year' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'qualification' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'uni_country' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'uni_fieldOfStudy' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'major' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'grade' => array(
                'type' => 'VARCHAR',
                'constraint' => 300
            ),
            'score' => array(
                'type' => 'decimal',
                'constraint' => '65,0'
            ),
            'total_score' => array(
                'type' => 'decimal',
                'constraint' => '65,0'
            ),
            'additional_info' => array(
                'type' => 'VARCHAR',
                'constraint' => 1000
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('education');
    }

    public function down() {
        $this->dbforge->drop_table('education');
    }

}