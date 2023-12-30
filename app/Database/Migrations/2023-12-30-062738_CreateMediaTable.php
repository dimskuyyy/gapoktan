<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMediaTable extends Migration
{
    protected $helpers = ['file'];

    public function up()
    {
        $this->forge->addField([
            'media_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'media_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'media_slug' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'media_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'media_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'media_created_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'media_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'media_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'media_deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'media_deleted_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);

        $this->forge->addKey('media_id', true);
        $this->forge->createTable('media');
    }

    public function down()
    {
        $this->forge->dropTable('media');
        delete_files(WRITEPATH . 'uploads/media/');
    }
}
