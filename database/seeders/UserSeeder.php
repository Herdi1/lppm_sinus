<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prodis')->insert([
            [
                'name' => 'D3 - Sistem Informasi Akuntantsi',
                'description' => 'prodi d3 sistem informasi akuntansi'
            ],
            [
                'name' => 'D3 - Sistem Informasi',
                'description' => 'prodi d3 sistem informasi'
            ],
            [
                'name' => 'D3 - Teknologi Informasi',
                'description' => 'prodi d3 teknologi informasi'
            ],
            [
                'name' => 'S1 - Sistem Informasi',
                'description' => 'prodi s1 sistem informasi'
            ],
            [
                'name' => 'S1 - Informatika',
                'description' => 'prodi s1 informatika'
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'id_prodi' => 5,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'rapek',
                'email' => 'rapek@gmail.com',
                'id_prodi' => 4,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'herbi',
                'email' => 'herbi@gmail.com',
                'id_prodi' => 5,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'ammar',
                'email' => 'ammar@gmail.com',
                'id_prodi' => 3,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'hasan',
                'email' => 'hasan@gmail.com',
                'id_prodi' => 2,
                'password' => Hash::make('password123'),
            ],
        ]);
    }
}
