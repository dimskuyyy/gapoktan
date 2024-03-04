<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'set_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'set_role' => [
                'type'  => 'VARCHAR',
                'constraint' => '255',
            ],
            'set_value' => [
                'type' => 'TEXT',
            ],
            'set_additional' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'set_optional' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'set_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=active,2=inactive',
            ],
            'set_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'set_created_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'set_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'set_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
        ]);
        $this->forge->addKey('set_id', true);
        $this->forge->createTable('setting');
    }

    public function down()
    {
        $this->forge->dropTable('setting');
    }
}
