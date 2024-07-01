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
            ['name' => 'Eko Aprianto, SST, M.T.I.', 'email' => 'e_aprianto@bps.go.id', 'password' => Hash::make('340016186'), 'is_admin' => 1],
            ['name' => 'Sri Desmiwati, S.ST', 'email' => 'desmi@bps.go.id', 'password' => Hash::make('340016419'), 'is_admin' => 1],
            ['name' => 'Desmaini, S.Si', 'email' => 'desmaini@bps.go.id', 'password' => Hash::make('340016709'), 'is_admin' => 1],
            ['name' => 'Adi Darmanto, S.E.', 'email' => 'adi.darmanto@bps.go.id', 'password' => Hash::make('340054823'), 'is_admin' => 1],
            ['name' => 'Maria Lisbetaria Nababan, SST', 'email' => 'lisbet@bps.go.id', 'password' => Hash::make('340055833'), 'is_admin' => 1],
            ['name' => 'Aditya Sangaji, SST, M.E', 'email' => 'sangaji.aditya@bps.go.id', 'password' => Hash::make('340056197'), 'is_admin' => 1],
            ['name' => 'Retza Bahtiar Anugrah, S.Si.', 'email' => 'retza.anugrah@bps.go.id', 'password' => Hash::make('340059217'), 'is_admin' => 1],
            ['name' => 'Renata Putri Henessa, S.Tr.Stat.', 'email' => 'putri.henessa@bps.go.id', 'password' => Hash::make('340062664'), 'is_admin' => 1],
            ['name' => 'Ema Aprilia Fitriani, SST', 'email' => 'emaaprilia@bps.go.id', 'password' => Hash::make('340055776'), 'is_admin' => 1],
            ['name' => 'Suparyani, SE', 'email' => 'suparyani@bps.go.id', 'password' => Hash::make('340014458'), 'is_admin' => 0],
            ['name' => 'Nina Martini', 'email' => 'ninamartini@bps.go.id', 'password' => Hash::make('340014247'), 'is_admin' => 0],
            ['name' => 'Tunggul Hutabarat, SE', 'email' => 'tunggul@bps.go.id', 'password' => Hash::make('340012079'), 'is_admin' => 0],
            ['name' => 'Catur Ariadi Wahyono', 'email' => 'caturariadi@bps.go.id', 'password' => Hash::make('340012071'), 'is_admin' => 0],
            ['name' => 'Ridha Amalia Hakim, S.Si.', 'email' => 'ridha.hakim@bps.go.id', 'password' => Hash::make('340055474'), 'is_admin' => 0],
            ['name' => 'Dekha Dwi Harianja, SST', 'email' => 'dekha.harianja@bps.go.id', 'password' => Hash::make('340057021'), 'is_admin' => 0],
            ['name' => 'Adlina Khairunnisa, SST', 'email' => 'adlina.khairunnisa@bps.go.id', 'password' => Hash::make('340057261'), 'is_admin' => 0],
            ['name' => 'Debora Sinaga, S.E.', 'email' => 'debora.sinaga@bps.go.id', 'password' => Hash::make('340051074'), 'is_admin' => 0],
            ['name' => 'Metha Arfiandty, A.Md', 'email' => 'metha.arfi@bps.go.id', 'password' => Hash::make('340053611'), 'is_admin' => 0],
            ['name' => 'M. Fadel Pahleva Yacoeb, SST', 'email' => 'fadel.pahleva@bps.go.id', 'password' => Hash::make('340058329'), 'is_admin' => 0],
            ['name' => 'Anditia Pratiwi, S.Tr.Stat', 'email' => 'anditiapr@bps.go.id', 'password' => Hash::make('340058591'), 'is_admin' => 0],
            ['name' => 'Tania Viona Sirait, S.Tr.Stat.', 'email' => 'viona@bps.go.id', 'password' => Hash::make('340059004'), 'is_admin' => 0],
            ['name' => 'Evawane Fahma Kusumawardani, S.Tr.Stat', 'email' => 'evawane.fahma@bps.go.id', 'password' => Hash::make('340059510'), 'is_admin' => 0],
            ['name' => 'Moon Bangun Simamora, A.Md, S.E.', 'email' => 'moon@bps.go.id', 'password' => Hash::make('340054830'), 'is_admin' => 0],
            ['name' => 'Febry Utami, S.Tr.Stat.', 'email' => 'febry.utami@bps.go.id', 'password' => Hash::make('340058734'), 'is_admin' => 0],
            ['name' => 'Ignatius Aprianto A S, S.Tr.Stat', 'email' => 'ignatius.aprianto@bps.go.id', 'password' => Hash::make('340059554'), 'is_admin' => 0],
            ['name' => 'Ivana Yoselin Purba Siboro, S.Tr.Stat.', 'email' => 'yoselin.purba@bps.go.id', 'password' => Hash::make('340060699'), 'is_admin' => 0],
            ['name' => 'Ratih Nurhabibah, S.Tr.Stat.', 'email' => 'ratihnurhabibah@bps.go.id', 'password' => Hash::make('340060871'), 'is_admin' => 0],
            ['name' => 'Arief Tirtana', 'email' => 'arieftirtana@bps.go.id', 'password' => Hash::make('340016845'), 'is_admin' => 0],
            ['name' => 'Moch Yailani', 'email' => 'moch.yailani@bps.go.id', 'password' => Hash::make('340019512'), 'is_admin' => 0],
            ['name' => 'Hardoni', 'email' => 'idon@bps.go.id', 'password' => Hash::make('340018042'), 'is_admin' => 0],
            ['name' => 'Dewi Feronika, A.Md.', 'email' => 'dewifero-pppk@bps.go.id', 'password' => Hash::make('340062810'), 'is_admin' => 0],
            ['name' => 'Cuan Wanti Gultom, A.Md', 'email' => 'cuan.gultom@bps.go.id', 'password' => Hash::make('340061227'), 'is_admin' => 0],
        ];

        DB::table('users')->insert($users);
    }
}
