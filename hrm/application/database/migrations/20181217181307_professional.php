<?php

class Migration_professional extends CI_Migration {

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
            'professional_body' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'membership_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'membership_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'membership_awarded' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'certificate' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('professional');
    }

    public function down() {
        $this->dbforge->drop_table('professional');
    }

}