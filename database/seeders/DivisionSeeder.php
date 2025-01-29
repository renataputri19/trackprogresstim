<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $divisions = [
            'Humas',
            'Pengolahan',
            'Publikasi',
            'Statistik Distribusi',
            // Add other divisions as needed
        ];

        foreach ($divisions as $division) {
            Division::create([
                'name' => $division,
                'slug' => Str::slug($division),
                'description' => 'Description for ' . $division . ' division'
            ]);
        }
    }
}
