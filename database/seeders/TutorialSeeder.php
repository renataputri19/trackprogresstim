<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TutorialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tutorials = [
            [
                'category' => 'Dasar',
                'title' => 'Memulai dengan RENTAK',
                'description' => 'Pelajari dasar-dasar navigasi dan penggunaan super app RENTAK.',
                'slug' => 'memulai-dengan-rentak',
                'duration' => '5:30',
                'thumbnail' => 'storage/thumbnails/rentak-intro.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan', 'time' => '0:00 - 1:30'],
                    ['title' => 'Login dan Navigasi', 'time' => '1:31 - 3:00'],
                    ['title' => 'Fitur Utama', 'time' => '3:01 - 5:30'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Dashboard Pelacakan Kinerja Pegawai',
                'description' => 'Temukan cara menggunakan fitur pelacakan kinerja untuk memantau tugas dan progres.',
                'slug' => 'dashboard-pelacakan-kinerja-pegawai',
                'duration' => '8:45',
                'thumbnail' => 'storage/thumbnails/performance-tracking.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan Dashboard', 'time' => '0:00 - 2:00'],
                    ['title' => 'Mengelola Tugas', 'time' => '2:01 - 5:00'],
                    ['title' => 'Laporan Kinerja', 'time' => '5:01 - 8:45'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Sistem Manajemen Pengetahuan',
                'description' => 'Pelajari cara mengakses, mencari, dan berkontribusi pada sistem manajemen pengetahuan.',
                'slug' => 'sistem-manajemen-pengetahuan',
                'duration' => '7:15',
                'thumbnail' => 'storage/thumbnails/knowledge-management.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan Repositori', 'time' => '0:00 - 2:15'],
                    ['title' => 'Pencarian Dokumen', 'time' => '2:16 - 4:30'],
                    ['title' => 'Mengunggah Konten', 'time' => '4:31 - 7:15'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Menggunakan Sistem Halo IPDS',
                'description' => 'Panduan langkah demi langkah untuk membuat dan melacak tiket dukungan IT.',
                'slug' => 'sistem-halo-ipds',
                'duration' => '6:20',
                'thumbnail' => 'storage/thumbnails/halo-ipds.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan Halo IPDS', 'time' => '0:00 - 1:45'],
                    ['title' => 'Membuat Tiket', 'time' => '1:46 - 4:00'],
                    ['title' => 'Melacak Status', 'time' => '4:01 - 6:20'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Dokumentasi Padamu Negeri',
                'description' => 'Cara mengelola dokumentasi reformasi birokrasi dengan efisien.',
                'slug' => 'dokumentasi-padamu-negri',
                'duration' => '9:10',
                'thumbnail' => 'storage/thumbnails/padamu-negri.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan Sistem', 'time' => '0:00 - 2:30'],
                    ['title' => 'Mengunggah Dokumen', 'time' => '2:31 - 6:00'],
                    ['title' => 'Berbagi dan Kolaborasi', 'time' => '6:01 - 9:10'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Alur Kerja Validasi VHTS',
                'description' => 'Panduan lengkap untuk memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel.',
                'slug' => 'alur-kerja-validasi-vhts',
                'duration' => '10:30',
                'thumbnail' => 'storage/thumbnails/vhts-validation.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan VHTS', 'time' => '0:00 - 3:00'],
                    ['title' => 'Impor Data Survei', 'time' => '3:01 - 7:00'],
                    ['title' => 'Validasi dan Laporan', 'time' => '7:01 - 10:30'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Manajemen Keuangan IAK',
                'description' => 'Mengelola keuangan dan administrasi proyek melalui sistem IAK.',
                'slug' => 'manajemen-keuangan-iak',
                'duration' => '12:15',
                'thumbnail' => 'storage/thumbnails/iak-financial.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan IAK', 'time' => '0:00 - 3:15'],
                    ['title' => 'Membuat Anggaran', 'time' => '3:16 - 8:00'],
                    ['title' => 'Pelaporan Keuangan', 'time' => '8:01 - 12:15'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Administrasi',
                'title' => 'Manajemen Pengguna dan Izin',
                'description' => 'Panduan untuk mengelola akun pengguna dan izin akses di RENTAK.',
                'slug' => 'manajemen-pengguna-dan-izin',
                'duration' => '6:45',
                'thumbnail' => 'storage/thumbnails/user-management.jpg',
                'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'chapters' => json_encode([
                    ['title' => 'Pengenalan Manajemen Pengguna', 'time' => '0:00 - 2:00'],
                    ['title' => 'Menambah Pengguna', 'time' => '2:01 - 4:30'],
                    ['title' => 'Mengatur Izin', 'time' => '4:31 - 6:45'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tutorials')->insert($tutorials);
    }
}