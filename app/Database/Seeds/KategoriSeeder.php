<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            $data = [
                'kat_slug' => url_title(Time::now()->format('Y-m-d H:i:s') . '-' . 'Ini Kategori Ke ', '-', true) . '-' . $i,
                'kat_nama' => 'Ini Kategori Ke ' . $i,
                'kat_status' => 2,
                'kat_created_at' => date('Y-m-d H:i:s'),
                'kat_created_by' => 1,
            ];
            $dummyData[] = $data;
        }
        // Using Query Builder
        $this->db->table('kategori')->insertBatch($dummyData);
    }
}
