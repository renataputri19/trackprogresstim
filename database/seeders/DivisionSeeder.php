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
            [
                'name' => 'Humas',
                'description' => 'Divisi Hubungan Masyarakat bertanggung jawab atas komunikasi eksternal, publikasi, dan hubungan dengan media serta masyarakat umum.'
            ],
            [
                'name' => 'Pengolahan',
                'description' => 'Divisi Pengolahan Data menangani proses pengumpulan, validasi, dan pengolahan data statistik dari berbagai sumber.'
            ],
            [
                'name' => 'Publikasi',
                'description' => 'Divisi Publikasi bertugas menyusun, mengedit, dan menerbitkan laporan statistik serta dokumen resmi lainnya.'
            ],
            [
                'name' => 'Statistik Distribusi',
                'description' => 'Divisi Statistik Distribusi fokus pada analisis dan penyajian data distribusi perdagangan dan ekonomi regional.'
            ],
            [
                'name' => 'Teknologi Informasi',
                'description' => 'Divisi Teknologi Informasi mengelola infrastruktur IT, pengembangan sistem, dan dukungan teknis untuk seluruh organisasi.'
            ]
        ];

        foreach ($divisions as $divisionData) {
            Division::updateOrCreate(
                ['slug' => Str::slug($divisionData['name'])],
                [
                    'name' => $divisionData['name'],
                    'slug' => Str::slug($divisionData['name']),
                    'description' => $divisionData['description']
                ]
            );
        }
    }
}
