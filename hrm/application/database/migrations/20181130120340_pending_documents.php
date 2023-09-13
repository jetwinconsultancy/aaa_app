<?php

class Migration_pending_documents extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'template' => array(
                'type' => 'LONGTEXT'
            ),
            'document_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('pending_documents');
    }

    public function down() {
        $this->dbforge->drop_table('pending_documents');
    }

}