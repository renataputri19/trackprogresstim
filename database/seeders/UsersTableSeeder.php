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
            ['name' => 'Maulidya Fan Ghul Udzan Utami', 'email' => 'maulidfan.ghul@bps.g.id', 'password' => Hash::make('340063310'), 'is_admin' => 1],
        ];

        DB::table('users')->insert($users);
    }
}
