<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_nama' => 'Administrator',
                'user_username' => 'adm',
                'user_password' => password_hash('adm', PASSWORD_BCRYPT),
                'user_level' => 1,
                'user_status' => 2,
                'user_created_at' => date('Y-m-d H:i:s')

            ],
            [
                'user_nama' => 'Writer',
                'user_username' => 'wrt',
                'user_password' => password_hash('123', PASSWORD_BCRYPT),
                'user_level' => 2,
                'user_status' => 2,
                'user_created_at' => date('Y-m-d H:i:s')
            ]
        ];
        // Using Query Builder
        $this->db->table('user')->insertBatch($data);
    }
}
