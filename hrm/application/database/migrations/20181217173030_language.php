<?php

class Migration_language extends CI_Migration {

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
                'constraint' => 100
            ),
            'spoken' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'written' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('language');
    }

    public function down() {
        $this->dbforge->drop_table('language');
    }

}