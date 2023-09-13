<?php

class Migration_referral extends CI_Migration {

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
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'company' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'job_title' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'phoneno' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('referral');
    }

    public function down() {
        $this->dbforge->drop_table('referral');
    }

}