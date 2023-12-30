<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kat_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kat_slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'kat_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'kat_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=save as draft,2=publish',
            ],
            'kat_view' => [
                'type' => 'INT',
                'comment' => 'total viewer',
                'default' => 0
            ],
            'kat_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'kat_created_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'kat_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'kat_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'kat_deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'kat_deleted_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);
        $this->forge->addKey('kat_id', true);
        $this->forge->createTable('kategori');
    }

    public function down()
    {
        $this->forge->dropTable('kategori');
    }
}
