<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama'       => 'Muhammad Faisal Bustoni',
            'email'      => 'admin@email.com',
            'password'   => password_hash('admin123', PASSWORD_BCRYPT), // Password mentahnya adalah: admin123
            'role'       => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Eksekusi insert data ke tabel tb_users
        $this->db->table('tb_users')->insert($data);
    }
}