<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('tims')->insert([
            ['name' => 'SUBBAGIAN UMUM'],
            ['name' => 'SOSIAL'],
            ['name' => 'PRODUKSI'],
            ['name' => 'DISTRIBUSI'],
            ['name' => 'NERWILIS'],
            ['name' => 'PENGOLAHAN DAN IT'],
        ]);
        
    }
}
