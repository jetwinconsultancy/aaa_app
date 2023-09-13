<?php

class Migration_applicant_interview extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'interview_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'applicant_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('applicant_interview');
    }

    public function down() {
        $this->dbforge->drop_table('applicant_interview');
    }

}