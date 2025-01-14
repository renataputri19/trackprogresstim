<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {

$users = [
            ['name' => 'Nimta Nababan, S.Mat.', 'email' => 'nimta@bps.go.id', 'password' => Hash::make('340054831'), 'is_admin' => 1],
            ['name' => 'Sri Fahrina, A.Md.Stat', 'email' => 'sri.fahrina@bps.go.id', 'password' => Hash::make('340060430'), 'is_admin' => 1],
            ['name' => 'Florentz Magdalena', 'email' => 'fmagdalena@bps.go.id', 'password' => Hash::make('340056837'), 'is_admin' => 1],
        ];

        DB::table('users')->insert($users);
    }
}
