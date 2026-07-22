<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;

class DocumentationController extends Controller
{
    public function index()
    {
        // Static, self-contained documentation for the RENTAK super app.
        // Written content — no database dependency.
        $sections = [
            [
                'id' => 'memulai',
                'icon' => 'rocket',
                'title' => 'Memulai',
                'intro' => 'RENTAK (Reformasi dan Integrasi Kinerja) adalah super app internal BPS Kota Batam yang menyatukan seluruh aplikasi dan layanan dalam satu dashboard. Cukup satu kali masuk untuk mengakses semuanya.',
                'steps' => [
                    'Buka halaman RENTAK, lalu klik tombol "Akses RENTAK" atau "Masuk".',
                    'Masukkan email BPS dan kata sandi Anda, kemudian klik "Masuk".',
                    'Anda akan diarahkan ke Dashboard yang menampilkan seluruh aplikasi dalam bentuk kartu.',
                    'Klik kartu aplikasi mana pun untuk membukanya. Aplikasi eksternal akan terbuka di tab baru.',
                ],
                'note' => 'Akun hanya dapat dibuat oleh IT Staff. Jika belum memiliki akun, hubungi tim IT BPS Kota Batam.',
            ],
            [
                'id' => 'dashboard',
                'icon' => 'layout',
                'title' => 'Dashboard & Navigasi',
                'intro' => 'Dashboard adalah pusat kendali Anda. Bagian "Aplikasi Kami" berisi seluruh sistem internal, sedangkan "Tautan Bermanfaat" memuat pintasan ke sumber daya BPS yang sering digunakan.',
                'steps' => [
                    'Gunakan menu di kanan atas (foto profil) untuk membuka Dashboard, Pengaturan, atau Keluar.',
                    'Klik ikon matahari/bulan untuk beralih antara mode terang dan gelap — preferensi tersimpan otomatis.',
                    'Pada perangkat mobile, gunakan tombol menu (garis tiga) untuk membuka navigasi.',
                ],
                'note' => null,
            ],
            [
                'id' => 'omega',
                'icon' => 'award',
                'title' => 'OMEGA — Pegawai Terbaik',
                'intro' => 'OMEGA (Outstanding Member of Great ASN) adalah sistem pemilihan pegawai terbaik triwulanan. Setiap pegawai dapat memberikan suara untuk rekan kerja yang dinilai paling berkontribusi.',
                'steps' => [
                    'Buka aplikasi OMEGA dari Dashboard.',
                    'Baca daftar kandidat pegawai beserta timnya.',
                    'Pilih kandidat, lalu kirim suara Anda. Setiap pengguna memiliki batas suara sesuai ketentuan.',
                    'Rekapitulasi suara hanya dapat dilihat oleh pihak yang diberi akses.',
                ],
                'note' => 'Satu periode penilaian berlangsung setiap triwulan. Pastikan memberikan suara sebelum periode ditutup.',
            ],
            [
                'id' => 'haloip',
                'icon' => 'ticket',
                'title' => 'Halo IP — Layanan Tiket IT',
                'intro' => 'Halo IP adalah layanan tiket untuk melaporkan masalah IT dan permintaan Peta Cetak. Semua pegawai dapat membuat tiket; tim IT akan menindaklanjuti dan memperbarui statusnya.',
                'steps' => [
                    'Buka Halo IP dari Dashboard, lalu klik "Buat Tiket".',
                    'Pilih kategori (masalah IT atau Peta Cetak), isi detail keluhan/permintaan selengkap mungkin.',
                    'Kirim tiket. Anda akan menerima nomor tiket untuk memantau progres.',
                    'Pantau status tiket (Menunggu, Diproses, Selesai) melalui halaman Halo IP.',
                ],
                'note' => 'Khusus IT Staff: gunakan menu "Kelola" untuk menetapkan (assign) petugas dan memperbarui status tiket.',
            ],
            [
                'id' => 'kms',
                'icon' => 'book',
                'title' => 'Knowledge Management',
                'intro' => 'Knowledge Management System (KMS) adalah repositori terpusat pengetahuan organisasi — dokumentasi, materi, dan praktik terbaik yang tersusun per divisi dan kegiatan.',
                'steps' => [
                    'Buka Knowledge Management dari Dashboard.',
                    'Telusuri berdasarkan Divisi, lalu pilih Kegiatan untuk melihat dokumen terkait.',
                    'Buka dokumen untuk membaca atau mengunduh materi.',
                    'Pengguna dengan hak akses dapat menambah divisi, kegiatan, dan dokumen baru.',
                ],
                'note' => null,
            ],
            [
                'id' => 'bahtera',
                'icon' => 'shield',
                'title' => 'BAHTERA — Validasi VHTS',
                'intro' => 'BAHTERA (Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi) membantu memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel (VHTS) agar data yang dihasilkan akurat dan konsisten.',
                'steps' => [
                    'Buka BAHTERA dari Dashboard, lalu pilih versi validasi yang sesuai (v1, v2, atau v3).',
                    'Masukkan atau tempel data survei yang akan divalidasi.',
                    'Jalankan validasi; sistem akan menandai ketidaksesuaian atau anomali data.',
                    'Perbaiki data sesuai catatan validasi sebelum diproses lebih lanjut.',
                ],
                'note' => 'Versi v3 mencakup logika alur tamu asing yang lebih lengkap. Gunakan versi terbaru bila ragu.',
            ],
            [
                'id' => 'laksamana',
                'icon' => 'map',
                'title' => 'Laksamana — Basis Usaha',
                'intro' => 'Laksamana (Lokasi dan Klasifikasi Sensus Manajemen Usaha) adalah basis data usaha untuk pencarian, tagging lokasi/klasifikasi, hingga ekspor data ke Excel.',
                'steps' => [
                    'Buka Laksamana untuk mencari usaha berdasarkan nama, kecamatan, atau kelurahan.',
                    'Buka detail usaha untuk melihat dan memperbarui tagging (bagi pengguna yang berwenang).',
                    'Lihat papan peringkat (leaderboard) untuk memantau progres tagging.',
                    'Gunakan menu "Laksamana Export" di Dashboard untuk mengunduh data dalam format XLSX.',
                ],
                'note' => 'Fitur impor dan penghapusan data hanya tersedia bagi pengguna Laksamana yang terautentikasi.',
            ],
            [
                'id' => 'padamu',
                'icon' => 'file',
                'title' => 'RB Padamu Negeri',
                'intro' => 'RB Padamu Negeri adalah platform dokumentasi Reformasi Birokrasi — menyederhanakan pengelolaan dokumen dan bukti dukung per area perubahan dan tahun anggaran.',
                'steps' => [
                    'Buka RB Padamu Negeri dari Dashboard.',
                    'Pilih tahun periode penilaian yang aktif.',
                    'Telusuri area/komponen Reformasi Birokrasi (pemenuhan dan reform).',
                    'Unggah atau kelola dokumen bukti dukung sesuai kriteria masing-masing komponen.',
                ],
                'note' => null,
            ],
            [
                'id' => 'akun',
                'icon' => 'settings',
                'title' => 'Akun & Keamanan',
                'intro' => 'Kelola profil dan keamanan akun Anda melalui halaman Pengaturan yang dapat diakses dari menu profil.',
                'steps' => [
                    'Klik foto profil di kanan atas, lalu pilih "Pengaturan".',
                    'Perbarui nama pada kartu "Informasi Profil" (email tidak dapat diubah).',
                    'Ganti kata sandi secara berkala melalui kartu "Ubah Kata Sandi".',
                    'Atur tema tampilan (terang/gelap) pada kartu "Tampilan".',
                ],
                'note' => 'Gunakan kata sandi minimal 8 karakter dan jangan bagikan kepada siapa pun.',
            ],
        ];

        return view('new-homepage.documentation', compact('sections'));
    }
}
