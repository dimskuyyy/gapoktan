<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_username' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'user_password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'user_level' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=admin,2=writer'
            ],
            'user_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=tidak aktif,2=aktif'
            ],
            'user_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'user_created_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'user_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'user_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'user_deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'user_deleted_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->createTable('user');
    }

    public function down()
    {
        $this->forge->dropTable('user');
    }
}
