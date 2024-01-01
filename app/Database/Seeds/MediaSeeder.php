<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'media_nama' => 'Test',
            'media_path' => 'default.png',
            'media_slug' => 'test',
            'media_created_at' => date('Y-m-d H:i:s'),
            'media_created_by' => 1

        ];
        // Using Query Builder
        $this->db->table('media')->insert($data);
    }
}
