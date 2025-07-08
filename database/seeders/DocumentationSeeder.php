<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documentation = [
            // Category: Memulai
            [
                'category' => 'Memulai',
                'title' => 'Pengenalan RENTAK',
                'description' => 'Gambaran umum tentang super app RENTAK dan fitur intinya.',
                'slug' => 'pengenalan-rentak',
                'icon' => 'rocket',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>RENTAK (Reformasi dan Integrasi Kinerja) adalah super app yang dirancang untuk mengoptimalkan kinerja operasional BPS Batam melalui integrasi sistem data dan proses kerja. Platform ini menyediakan pusat terintegrasi untuk berbagai sistem seperti pelacakan kinerja pegawai, manajemen pengetahuan, dukungan IT, administrasi keuangan, reformasi birokrasi, dan validasi survei.</p>
                    <h3>Fitur Utama</h3>
                    <ul>
                        <li><strong>Employee Performance Integration</strong>: Dashboard untuk melacak tugas dan progres kinerja pegawai.</li>
                        <li><strong>Knowledge Management System</strong>: Repositori terpusat untuk dokumentasi dan pengetahuan organisasi.</li>
                        <li><strong>Halo IPDS System</strong>: Sistem tiket untuk dukungan IT yang efisien.</li>
                        <li><strong>Integration Administrasi Keuangan</strong>: Pengelolaan keuangan dan administrasi proyek besar.</li>
                        <li><strong>Padamu Negeri System</strong>: Dokumentasi terpusat untuk reformasi birokrasi.</li>
                        <li><strong>VHTS Validation System</strong>: Validasi data Survei Tingkat Penghunian Kamar Hotel.</li>
                    </ul>
                    <h3>Cara Memulai</h3>
                    <ol>
                        <li>Login ke akun RENTAK Anda menggunakan kredensial BPS Batam.</li>
                        <li>Akses dashboard utama untuk melihat semua sistem yang tersedia.</li>
                        <li>Pilih sistem yang diperlukan dari menu navigasi.</li>
                        <li>Ikuti panduan konfigurasi awal untuk setiap sistem.</li>
                    </ol>
                    <blockquote>Catatan: Pastikan Anda memiliki izin akses yang sesuai untuk menggunakan sistem tertentu. Hubungi administrator jika diperlukan.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Memulai',
                'title' => 'Persyaratan Sistem',
                'description' => 'Kebutuhan perangkat keras dan lunak untuk performa optimal RENTAK.',
                'slug' => 'persyaratan-sistem',
                'icon' => 'rocket',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Untuk memastikan RENTAK berjalan dengan optimal, perangkat Anda harus memenuhi persyaratan minimum berikut.</p>
                    <h3>Persyaratan Perangkat Keras</h3>
                    <ul>
                        <li>Prosesor: Intel Core i3 atau setara</li>
                        <li>RAM: 4 GB (disarankan 8 GB)</li>
                        <li>Penyimpanan: 500 MB ruang kosong</li>
                        <li>Layar: Resolusi minimum 1366x768</li>
                    </ul>
                    <h3>Persyaratan Perangkat Lunak</h3>
                    <ul>
                        <li>Sistem Operasi: Windows 10, macOS 10.15, atau Ubuntu 20.04</li>
                        <li>Browser: Google Chrome (versi terbaru), Mozilla Firefox, atau Microsoft Edge</li>
                        <li>Koneksi Internet: Stabil dengan kecepatan minimal 5 Mbps</li>
                    </ul>
                    <h3>Instalasi</h3>
                    <ol>
                        <li>Pastikan perangkat Anda memenuhi persyaratan di atas.</li>
                        <li>Buka browser dan akses URL RENTAK yang disediakan oleh BPS Batam.</li>
                        <li>Login dengan kredensial Anda.</li>
                    </ol>
                    <blockquote>Catatan: Untuk performa terbaik, gunakan browser versi terbaru dan hindari penggunaan VPN yang tidak diperlukan.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Memulai',
                'title' => 'Registrasi Pengguna',
                'description' => 'Panduan langkah demi langkah untuk mendaftar dan mengatur akun RENTAK.',
                'slug' => 'registrasi-pengguna',
                'icon' => 'rocket',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>RENTAK menggunakan single sign-on (SSO) dengan kredensial BPS Batam untuk akses yang aman dan terintegrasi. Berikut adalah panduan untuk mendaftar dan mengatur akun Anda.</p>
                    <h3>Langkah Registrasi</h3>
                    <ol>
                        <li>Hubungi administrator IT BPS Batam untuk mendapatkan kredensial SSO.</li>
                        <li>Buka URL RENTAK di browser Anda.</li>
                        <li>Masukkan NIP dan kata sandi Anda di halaman login SSO.</li>
                        <li>Lengkapi profil Anda (nama, jabatan, unit kerja) saat login pertama.</li>
                    </ol>
                    <h3>Pengaturan Akun</h3>
                    <ul>
                        <li><strong>Profil</strong>: Perbarui informasi pribadi di menu Pengaturan.</li>
                        <li><strong>Notifikasi</strong>: Aktifkan notifikasi email untuk pembaruan tugas atau tiket.</li>
                        <li><strong>Keamanan</strong>: Atur ulang kata sandi jika diperlukan melalui portal SSO.</li>
                    </ul>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Simpan kredensial Anda dengan aman dan jangan bagikan.</li>
                        <li>Perbarui profil Anda untuk memastikan data yang akurat.</li>
                        <li>Hubungi administrator jika lupa kata sandi atau terkunci dari akun.</li>
                    </ul>
                    <blockquote>Catatan: Akses ke sistem tertentu mungkin dibatasi berdasarkan peran Anda. Hubungi administrator untuk izin tambahan.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Category: Sistem Inti
            [
                'category' => 'Sistem Inti',
                'title' => 'Employee Performance Integration',
                'description' => 'Panduan lengkap untuk sistem pelacakan kinerja pegawai.',
                'slug' => 'employee-performance-integration',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Employee Performance Integration adalah sistem dalam RENTAK untuk memantau dan mengelola kinerja pegawai BPS Batam. Sistem ini menyediakan dashboard visual untuk melacak tugas, progres, dan metrik kinerja berbasis persentase.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan buka dashboard Employee Performance Integration.</li>
                        <li>Lihat daftar tugas yang ditugaskan kepada Anda atau tim Anda.</li>
                        <li>Perbarui status tugas dan masukkan progres (misalnya, persentase penyelesaian).</li>
                        <li>Gunakan fitur analitik untuk menghasilkan laporan kinerja.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Notifikasi Tugas</td>
                                <td>Mengatur frekuensi notifikasi tugas</td>
                                <td>Harian</td>
                            </tr>
                            <tr>
                                <td>Metrik Kinerja</td>
                                <td>Memilih KPI yang ditampilkan di dashboard</td>
                                <td>Standar</td>
                            </tr>
                            <tr>
                                <td>Laporan Otomatis</td>
                                <td>Mengaktifkan laporan kinerja otomatis</td>
                                <td>Nonaktif</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Perbarui status tugas secara rutin untuk memastikan data real-time.</li>
                        <li>Sesuaikan KPI di dashboard sesuai kebutuhan tim Anda.</li>
                        <li>Ekspor laporan kinerja untuk evaluasi bulanan.</li>
                    </ul>
                    <blockquote>Catatan: Hanya pengguna dengan izin manajerial yang dapat mengatur KPI tim.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Knowledge Management System',
                'description' => 'Cara menggunakan repositori pengetahuan dan fitur dokumentasi.',
                'slug' => 'knowledge-management-system',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Knowledge Management System adalah repositori terpusat dalam RENTAK untuk menyimpan pengetahuan organisasi, praktik terbaik, dan dokumentasi BPS Batam. Sistem ini memfasilitasi berbagi dan pelestarian pengetahuan.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan akses Knowledge Management System.</li>
                        <li>Cari dokumen menggunakan kata kunci atau kategori.</li>
                        <li>Unggah dokumen baru atau perbarui dokumen yang ada.</li>
                        <li>Bagikan dokumen dengan tim melalui tautan atau izin akses.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kategori Dokumen</td>
                                <td>Mengatur kategori untuk pengelompokan dokumen</td>
                                <td>Standar</td>
                            </tr>
                            <tr>
                                <td>Izin Akses</td>
                                <td>Mengatur siapa yang dapat mengakses dokumen</td>
                                <td>Publik (internal)</td>
                            </tr>
                            <tr>
                                <td>Notifikasi</td>
                                <td>Menerima pemberitahuan saat dokumen diperbarui</td>
                                <td>Nonaktif</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Gunakan tag dan kategori yang jelas untuk memudahkan pencarian.</li>
                        <li>Perbarui dokumen secara berkala untuk menjaga relevansi.</li>
                        <li>Batasi akses ke dokumen sensitif hanya untuk pihak berwenang.</li>
                    </ul>
                    <blockquote>Catatan: Dokumen yang diunggah harus sesuai dengan pedoman dokumentasi BPS Batam.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Halo IPDS System',
                'description' => 'Dokumentasi lengkap untuk sistem tiket dukungan IT.',
                'slug' => 'halo-ipds-system',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Halo IPDS System adalah alat layanan pelanggan IT dalam RENTAK yang memungkinkan staf non-IT melaporkan masalah teknis, seperti gangguan Wi-Fi, melalui tiket. Sistem ini memastikan penyelesaian yang cepat dan terorganisir.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan akses Halo IPDS System.</li>
                        <li>Buat tiket baru dengan menjelaskan masalah teknis Anda.</li>
                        <li>Lacak status tiket di dashboard tiket Anda.</li>
                        <li>Terima notifikasi saat tiket diselesaikan oleh tim IT.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Prioritas Tiket</td>
                                <td>Mengatur tingkat prioritas masalah</td>
                                <td>Normal</td>
                            </tr>
                            <tr>
                                <td>Notifikasi</td>
                                <td>Menerima pembaruan status tiket</td>
                                <td>Aktif</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Berikan deskripsi masalah yang jelas saat membuat tiket.</li>
                        <li>Gunakan prioritas tinggi hanya untuk masalah kritis.</li>
                        <li>Periksa knowledge base sebelum membuat tiket untuk solusi cepat.</li>
                    </ul>
                    <blockquote>Catatan: Tim IT akan menghubungi Anda jika diperlukan informasi tambahan untuk menyelesaikan tiket.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Integration Administrasi Keuangan',
                'description' => 'Panduan untuk sistem administrasi dan keuangan proyek.',
                'slug' => 'integration-administrasi-keuangan',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Integration Administrasi Keuangan (IAK) adalah sistem dalam RENTAK untuk mengelola administrasi dan keuangan proyek besar BPS Batam, termasuk anggaran, pelacakan dokumen survei, dan pelaporan keuangan.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan buka Integration Administrasi Keuangan.</li>
                        <li>Buat anggaran proyek baru dan masukkan detail peserta (internal/eksternal).</li>
                        <li>Lacak dokumen survei (misalnya, apakah masih di surveyor atau di BPS).</li>
                        <li>Hasilkan laporan keuangan untuk audit atau evaluasi.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Templat Anggaran</td>
                                <td>Menggunakan templat anggaran standar</td>
                                <td>Aktif</td>
                            </tr>
                            <tr>
                                <td>Pelacakan Dokumen</td>
                                <td>Mengaktifkan pelacakan real-time dokumen</td>
                                <td>Aktif</td>
                            </tr>
                            <tr>
                                <td>Laporan Otomatis</td>
                                <td>Menghasilkan laporan keuangan otomatis</td>
                                <td>Nonaktif</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Perbarui status dokumen survei secara rutin.</li>
                        <li>Gunakan templat anggaran untuk konsistensi.</li>
                        <li>Simpan laporan keuangan sebagai cadangan untuk audit.</li>
                    </ul>
                    <blockquote>Catatan: Hanya pengguna dengan izin keuangan yang dapat mengakses fitur pelaporan.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'Padamu Negeri System',
                'description' => 'Panduan pengelolaan dokumentasi reformasi birokrasi.',
                'slug' => 'padamu-negri-system',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Padamu Negeri System adalah platform dalam RENTAK untuk mengelola dokumentasi reformasi birokrasi, memastikan semua dokumen administrasi terpusat dan mudah diakses.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan akses Padamu Negeri System.</li>
                        <li>Unggah dokumen reformasi birokrasi ke repositori.</li>
                        <li>Atur kategori dan tag untuk memudahkan pencarian.</li>
                        <li>Bagikan dokumen dengan tim untuk kolaborasi.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Kategori Dokumen</td>
                                <td>Mengatur kategori dokumen</td>
                                <td>Standar</td>
                            </tr>
                            <tr>
                                <td>Izin Akses</td>
                                <td>Mengatur siapa yang dapat mengakses dokumen</td>
                                <td>Tim</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Gunakan tag yang deskriptif untuk dokumen.</li>
                        <li>Batasi akses dokumen sensitif hanya untuk pihak berwenang.</li>
                        <li>Perbarui repositori secara berkala untuk keakuratan.</li>
                    </ul>
                    <blockquote>Catatan: Dokumen sensitif harus ditandai dengan izin akses terbatas.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Sistem Inti',
                'title' => 'VHTS Validation System',
                'description' => 'Instruksi detail untuk validasi Survei Tingkat Penghunian Kamar Hotel.',
                'slug' => 'vhts-validation-system',
                'icon' => 'layout-dashboard',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>VHTS Validation System adalah alat dalam RENTAK untuk memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel (VHTS), membantu pegawai BPS Batam memastikan akurasi data survei.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dan buka VHTS Validation System.</li>
                        <li>Impor data survei VHTS dari sistem atau unggah manual.</li>
                        <li>Jalankan proses validasi otomatis untuk memeriksa anomali.</li>
                        <li>Hasilkan laporan validasi untuk analisis lebih lanjut.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Validasi Otomatis</td>
                                <td>Mengaktifkan validasi data otomatis</td>
                                <td>Aktif</td>
                            </tr>
                            <tr>
                                <td>Laporan</td>
                                <td>Format laporan yang dihasilkan</td>
                                <td>PDF</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Periksa anomali data sebelum menyetujui hasil validasi.</li>
                        <li>Simpan laporan validasi untuk dokumentasi.</li>
                        <li>Gunakan fitur analisis untuk mendeteksi tren okupansi hotel.</li>
                    </ul>
                    <blockquote>Catatan: Pastikan data survei diimpor dalam format yang sesuai (misalnya, CSV).</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Category: Administrasi
            [
                'category' => 'Administrasi',
                'title' => 'Manajemen Pengguna',
                'description' => 'Mengatur akun pengguna, izin, dan kontrol akses.',
                'slug' => 'manajemen-pengguna',
                'icon' => 'settings',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Manajemen Pengguna memungkinkan administrator untuk mengelola akun pengguna, menetapkan izin, dan mengatur kontrol akses dalam RENTAK.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dengan akun administrator.</li>
                        <li>Akses menu Manajemen Pengguna di pengaturan sistem.</li>
                        <li>Tambah, edit, atau hapus akun pengguna.</li>
                        <li>Tetapkan peran (misalnya, pegawai, manajer, admin) dan izin sistem.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Peran Pengguna</td>
                                <td>Menetapkan peran untuk pengguna</td>
                                <td>Pegawai</td>
                            </tr>
                            <tr>
                                <td>Izin Sistem</td>
                                <td>Mengatur akses ke sistem tertentu</td>
                                <td>Terbatas</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Tetapkan peran berdasarkan tanggung jawab pengguna.</li>
                        <li>Tinjau izin pengguna secara berkala untuk keamanan.</li>
                        <li>Nonaktifkan akun pengguna yang sudah tidak aktif.</li>
                    </ul>
                    <blockquote>Catatan: Hanya administrator dengan izin penuh yang dapat mengelola pengguna.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Administrasi',
                'title' => 'Konfigurasi Sistem',
                'description' => 'Opsi konfigurasi lanjutan untuk administrator sistem.',
                'slug' => 'konfigurasi-sistem',
                'icon' => 'settings',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Konfigurasi Sistem memungkinkan administrator untuk menyesuaikan pengaturan global RENTAK, seperti notifikasi, integrasi SSO, dan pengaturan database.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dengan akun administrator.</li>
                        <li>Akses menu Konfigurasi Sistem di pengaturan.</li>
                        <li>Sesuaikan pengaturan seperti frekuensi notifikasi atau format laporan.</li>
                        <li>Simpan perubahan dan uji konfigurasi.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Notifikasi Global</td>
                                <td>Mengatur notifikasi untuk semua pengguna</td>
                                <td>Harian</td>
                            </tr>
                            <tr>
                                <td>Format Laporan</td>
                                <td>Memilih format laporan default</td>
                                <td>PDF</td>
                            </tr>
                            <tr>
                                <td>Integrasi SSO</td>
                                <td>Mengaktifkan autentikasi SSO</td>
                                <td>Aktif</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Uji perubahan konfigurasi di lingkungan pengujian terlebih dahulu.</li>
                        <li>Dokumentasikan semua perubahan konfigurasi.</li>
                        <li>Berikan akses konfigurasi hanya kepada administrator terlatih.</li>
                    </ul>
                    <blockquote>Catatan: Perubahan konfigurasi dapat memengaruhi semua pengguna. Pastikan untuk memberi tahu tim sebelum menerapkan perubahan besar.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'Administrasi',
                'title' => 'Cadangan & Pemulihan Data',
                'description' => 'Prosedur untuk mencadangkan dan memulihkan data sistem.',
                'slug' => 'cadangan-pemulihan-data',
                'icon' => 'settings',
                'content' => '
                    <h2>Pengenalan</h2>
                    <p>Cadangan & Pemulihan Data memastikan data RENTAK aman dan dapat dipulihkan jika terjadi kegagalan sistem atau kehilangan data.</p>
                    <h3>Cara Menggunakan</h3>
                    <ol>
                        <li>Login ke RENTAK dengan akun administrator.</li>
                        <li>Akses menu Cadangan & Pemulihan di pengaturan sistem.</li>
                        <li>Jadwalkan cadangan otomatis atau lakukan cadangan manual.</li>
                        <li>Untuk pemulihan, pilih cadangan terbaru dan ikuti panduan pemulihan.</li>
                    </ol>
                    <h3>Pengaturan</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Opsi</th>
                                <th>Deskripsi</th>
                                <th>Default</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jadwal Cadangan</td>
                                <td>Mengatur frekuensi cadangan otomatis</td>
                                <td>Harian</td>
                            </tr>
                            <tr>
                                <td>Lokasi Cadangan</td>
                                <td>Memilih penyimpanan cadangan</td>
                                <td>Server Lokal</td>
                            </tr>
                        </tbody>
                    </table>
                    <h3>Praktik Terbaik</h3>
                    <ul>
                        <li>Lakukan cadangan harian untuk meminimalkan kehilangan data.</li>
                        <li>Simpan cadangan di lokasi eksternal untuk keamanan tambahan.</li>
                        <li>Uji proses pemulihan secara berkala untuk memastikan integritas data.</li>
                    </ul>
                    <blockquote>Catatan: Cadangan data hanya dapat diakses oleh administrator dengan izin penuh.</blockquote>
                ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('documentation')->insert($documentation);
    }
}