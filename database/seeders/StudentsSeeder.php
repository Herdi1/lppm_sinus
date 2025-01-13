<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('students')->insert([
            [
                'name' => 'student1',
                'nim' => 2110001,
                'email' => 'student1@gmail.com',
                'prodi' => 'prodi1',
            ],
            [
                'name' => 'student2',
                'nim' => 2110002,
                'email' => 'student2@gmail.com',
                'prodi' => 'prodi2',
            ],
            [
                'name' => 'student3',
                'nim' => 2110003,
                'email' => 'student3@gmail.com',
                'prodi' => 'prodi3',
            ],
            [
                'name' => 'student4',
                'nim' => 2110004,
                'email' => 'student4@gmail.com',
                'prodi' => 'prodi4',
            ],
        ]);
    }
}
