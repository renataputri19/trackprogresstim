<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;

class TutorialController extends Controller
{
    public function index()
    {
        // Static, written step-by-step guides — no database dependency.
        $guides = [
            [
                'title' => 'Masuk ke RENTAK',
                'category' => 'Dasar',
                'icon' => 'login',
                'duration' => '1 menit',
                'desc' => 'Cara masuk ke akun RENTAK Anda untuk pertama kali.',
                'steps' => [
                    'Klik tombol "Akses RENTAK" atau "Masuk" di kanan atas.',
                    'Masukkan email BPS dan kata sandi Anda.',
                    'Klik "Masuk" — Anda langsung diarahkan ke Dashboard.',
                ],
            ],
            [
                'title' => 'Mengenal Dashboard',
                'category' => 'Dasar',
                'icon' => 'layout',
                'duration' => '2 menit',
                'desc' => 'Memahami tata letak dashboard dan cara membuka aplikasi.',
                'steps' => [
                    'Perhatikan bagian "Aplikasi Kami" berisi seluruh sistem internal.',
                    'Bagian "Tautan Bermanfaat" memuat pintasan cepat BPS.',
                    'Klik kartu aplikasi untuk membukanya; aplikasi eksternal terbuka di tab baru.',
                ],
            ],
            [
                'title' => 'Mengganti Tema Terang / Gelap',
                'category' => 'Dasar',
                'icon' => 'moon',
                'duration' => '30 detik',
                'desc' => 'Beralih antara mode terang dan gelap sesuai kenyamanan Anda.',
                'steps' => [
                    'Klik ikon matahari/bulan di kanan atas header.',
                    'Tampilan langsung berubah dan preferensi tersimpan otomatis di perangkat ini.',
                ],
            ],
            [
                'title' => 'Memperbarui Profil & Kata Sandi',
                'category' => 'Dasar',
                'icon' => 'user',
                'duration' => '2 menit',
                'desc' => 'Menjaga data akun tetap mutakhir dan aman.',
                'steps' => [
                    'Klik foto profil di kanan atas, pilih "Pengaturan".',
                    'Perbarui nama pada kartu Informasi Profil, lalu simpan.',
                    'Ganti kata sandi (minimal 8 karakter) pada kartu Ubah Kata Sandi.',
                ],
            ],
            [
                'title' => 'Memberikan Suara di OMEGA',
                'category' => 'Aplikasi',
                'icon' => 'award',
                'duration' => '2 menit',
                'desc' => 'Memilih pegawai terbaik pada periode triwulan berjalan.',
                'steps' => [
                    'Buka aplikasi OMEGA dari Dashboard.',
                    'Baca daftar kandidat beserta timnya.',
                    'Pilih kandidat favorit Anda, lalu kirim suara.',
                    'Pastikan mengirim suara sebelum periode ditutup.',
                ],
            ],
            [
                'title' => 'Membuat Tiket Halo IP',
                'category' => 'Aplikasi',
                'icon' => 'ticket',
                'duration' => '3 menit',
                'desc' => 'Melaporkan masalah IT atau meminta Peta Cetak.',
                'steps' => [
                    'Buka Halo IP dari Dashboard, klik "Buat Tiket".',
                    'Pilih kategori dan isi detail keluhan/permintaan.',
                    'Kirim tiket dan catat nomor tiket Anda.',
                    'Pantau status tiket sampai berstatus "Selesai".',
                ],
            ],
            [
                'title' => 'Validasi Data di BAHTERA',
                'category' => 'Aplikasi',
                'icon' => 'shield',
                'duration' => '4 menit',
                'desc' => 'Memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel (VHTS).',
                'steps' => [
                    'Buka BAHTERA, pilih versi validasi (v1, v2, atau v3).',
                    'Masukkan atau tempel data survei yang akan divalidasi.',
                    'Jalankan validasi dan tinjau catatan/anomali yang ditandai.',
                    'Perbaiki data sesuai catatan sebelum diproses lebih lanjut.',
                ],
            ],
            [
                'title' => 'Mencari & Tagging Usaha di Laksamana',
                'category' => 'Aplikasi',
                'icon' => 'search',
                'duration' => '3 menit',
                'desc' => 'Menemukan usaha dan memperbarui tagging lokasi/klasifikasi.',
                'steps' => [
                    'Buka Laksamana, cari usaha berdasarkan nama/kecamatan/kelurahan.',
                    'Buka detail usaha yang dituju.',
                    'Perbarui tagging (bagi pengguna berwenang), lalu simpan.',
                    'Pantau progres melalui papan peringkat (leaderboard).',
                ],
            ],
            [
                'title' => 'Mengekspor Data Laksamana (XLSX)',
                'category' => 'Aplikasi',
                'icon' => 'download',
                'duration' => '1 menit',
                'desc' => 'Mengunduh data usaha dalam format Excel.',
                'steps' => [
                    'Buka menu "Laksamana Export" dari Dashboard.',
                    'Atur filter data yang diperlukan (bila tersedia).',
                    'Klik unduh untuk menyimpan berkas XLSX.',
                ],
            ],
            [
                'title' => 'Menjelajah Knowledge Management',
                'category' => 'Aplikasi',
                'icon' => 'book',
                'duration' => '2 menit',
                'desc' => 'Menemukan dokumentasi dan materi organisasi.',
                'steps' => [
                    'Buka Knowledge Management dari Dashboard.',
                    'Telusuri berdasarkan Divisi, lalu pilih Kegiatan.',
                    'Buka dokumen untuk membaca atau mengunduh materi.',
                ],
            ],
            [
                'title' => 'Menambah Pengguna Baru',
                'category' => 'Admin & IT',
                'icon' => 'users',
                'duration' => '3 menit',
                'desc' => 'Khusus IT Staff — membuat akun pegawai baru beserta perannya.',
                'steps' => [
                    'Pada Dashboard, buka panel "Manajemen Pengguna", klik "Tambah Pengguna".',
                    'Isi nama, email, kata sandi, dan nomor telepon (opsional).',
                    'Pilih peran bila diperlukan (Administrator / IT Staff) — kosongkan untuk pegawai biasa.',
                    'Klik "Simpan Pengguna".',
                ],
            ],
            [
                'title' => 'Mengelola & Assign Tiket (IT)',
                'category' => 'Admin & IT',
                'icon' => 'clipboard',
                'duration' => '3 menit',
                'desc' => 'Khusus IT Staff — menindaklanjuti tiket Halo IP.',
                'steps' => [
                    'Buka Halo IP, masuk ke menu "Kelola".',
                    'Buka tiket masuk, tetapkan (assign) petugas yang menangani.',
                    'Perbarui status tiket sesuai progres penyelesaian.',
                    'Tandai "Selesai" setelah masalah tuntas.',
                ],
            ],
        ];

        $categories = ['Semua', 'Dasar', 'Aplikasi', 'Admin & IT'];

        return view('new-homepage.tutorials', compact('guides', 'categories'));
    }
}
