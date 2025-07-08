<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Division;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $activitiesData = [
            'Humas' => [
                [
                    'name' => 'Kampanye Media Sosial',
                    'description' => 'Mengelola konten dan strategi komunikasi di platform media sosial untuk meningkatkan awareness publik terhadap data statistik.'
                ],
                [
                    'name' => 'Press Release Bulanan',
                    'description' => 'Menyusun dan mendistribusikan siaran pers bulanan mengenai perkembangan data statistik terkini.'
                ],
                [
                    'name' => 'Event Sosialisasi',
                    'description' => 'Mengorganisir acara sosialisasi dan workshop untuk memperkenalkan metodologi dan hasil survei kepada stakeholder.'
                ]
            ],
            'Pengolahan' => [
                [
                    'name' => 'Validasi Data Survei',
                    'description' => 'Melakukan proses validasi dan cleaning data hasil survei lapangan sebelum diolah lebih lanjut.'
                ],
                [
                    'name' => 'Analisis Statistik Deskriptif',
                    'description' => 'Menganalisis data menggunakan metode statistik deskriptif untuk menghasilkan ringkasan dan tren data.'
                ],
                [
                    'name' => 'Pemodelan Data',
                    'description' => 'Mengembangkan model statistik untuk prediksi dan proyeksi berdasarkan data historis.'
                ]
            ],
            'Publikasi' => [
                [
                    'name' => 'Penyusunan Buku Statistik',
                    'description' => 'Menyusun dan mengedit publikasi buku statistik tahunan dengan standar editorial yang tinggi.'
                ],
                [
                    'name' => 'Infografis Data',
                    'description' => 'Membuat visualisasi data dalam bentuk infografis yang mudah dipahami oleh masyarakat umum.'
                ],
                [
                    'name' => 'Newsletter Mingguan',
                    'description' => 'Menerbitkan newsletter mingguan berisi highlight data statistik dan analisis singkat.'
                ]
            ],
            'Statistik Distribusi' => [
                [
                    'name' => 'Survei Harga Konsumen',
                    'description' => 'Melakukan survei rutin harga barang konsumen di berbagai pasar untuk menghitung indeks harga.'
                ],
                [
                    'name' => 'Analisis Pola Distribusi',
                    'description' => 'Menganalisis pola distribusi barang dan jasa di wilayah regional untuk mendukung kebijakan ekonomi.'
                ],
                [
                    'name' => 'Monitoring Inflasi',
                    'description' => 'Memantau dan menganalisis tingkat inflasi berdasarkan data harga dan konsumsi masyarakat.'
                ]
            ],
            'Teknologi Informasi' => [
                [
                    'name' => 'Pengembangan Sistem Database',
                    'description' => 'Merancang dan mengembangkan sistem database untuk penyimpanan dan pengelolaan data statistik.'
                ],
                [
                    'name' => 'Maintenance Server',
                    'description' => 'Melakukan pemeliharaan rutin server dan infrastruktur IT untuk memastikan ketersediaan sistem.'
                ],
                [
                    'name' => 'Digitalisasi Dokumen',
                    'description' => 'Mengkonversi dokumen fisik menjadi format digital dan mengorganisir sistem arsip elektronik.'
                ]
            ]
        ];

        foreach ($activitiesData as $divisionName => $activities) {
            $division = Division::where('name', $divisionName)->first();
            
            if ($division) {
                foreach ($activities as $activityData) {
                    Activity::updateOrCreate(
                        [
                            'division_id' => $division->id,
                            'slug' => Str::slug($activityData['name'])
                        ],
                        [
                            'name' => $activityData['name'],
                            'slug' => Str::slug($activityData['name']),
                            'description' => $activityData['description']
                        ]
                    );
                }
            }
        }
    }
}
