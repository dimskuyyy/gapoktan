<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostKomenTable extends Migration
{
    protected $cache;
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'komen_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'komen_nama' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'komen_post_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'komen_reply_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'komen_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'komen_komentar' => [
                'type' => 'TEXT',
            ],
            'komen_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1= Not Approve,2=Approve',
            ],
            'komen_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'komen_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'komen_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'komen_deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'komen_deleted_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);
        $this->forge->addForeignKey('komen_post_id', 'post', 'post_id', 'CASCADE', 'CASCADE', 'komen_post_id');
        $this->forge->addKey('komen_id', true);
        $this->forge->createTable('post_komen');
    }

    public function down()
    {
        $this->cache = \Config\Services::cache();
        $this->cache->clean();
        $this->forge->dropTable('post_komen');
    }
}
