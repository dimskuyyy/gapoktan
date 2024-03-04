<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'post_id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'post_slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'post_kategori_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true
            ],
            'post_judul' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'post_konten' => [
                'type' => 'TEXT',
            ],
            'post_jenis' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=berita,2=product',
            ],
            'post_media_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'post_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=save as draft,2=publish',
            ],
            'post_approve' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'comment' => '1=manual approve,2=auto approve',
            ],
            'post_user_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'post_view' => [
                'type' => 'INT',
                'comment' => 'total viewer',
                'default' => 0,
            ],
            'post_created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'post_created_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'post_published_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'post_published_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'post_updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'post_updated_by' => [
                'type' => 'INT',
                'null' => true
            ],
            'post_deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'post_deleted_by' => [
                'type' => 'INT',
                'null' => true
            ]
        ]);
        $this->forge->addForeignKey('post_kategori_id', 'kategori', 'kat_id', 'CASCADE', 'CASCADE', 'post_kategori_id');
        $this->forge->addForeignKey('post_media_id', 'media', 'media_id', 'CASCADE', 'CASCADE', 'post_media_id');
        $this->forge->addForeignKey('post_user_id', 'user', 'user_id', 'CASCADE', 'CASCADE', 'post_user_id');
        $this->forge->addKey('post_id', true);
        $this->forge->createTable('post');
    }

    public function down()
    {
        $this->forge->dropTable('post');
    }
}
