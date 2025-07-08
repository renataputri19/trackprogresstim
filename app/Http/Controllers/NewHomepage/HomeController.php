<?php

namespace App\Http\Controllers\NewHomepage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the new homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $features = [
            [
                'id' => 'performance',
                'title' => 'Employee Performance Integration',
                'description' => 'Dashboard dinamis untuk melacak tugas pegawai, memantau progres kerja, dan memvisualisasikan metrik kinerja.',
                'icon' => 'bar-chart-3',
                'image' => 'images/features/performance-dashboard.jpg',
                'benefits' => [
                    'Pelacakan tugas dan progres secara real-time',
                    'Visualisasi kinerja berbasis persentase',
                    'Dashboard KPI yang dapat disesuaikan untuk manajemen',
                    'Laporan dan analitik kinerja otomatis'
                ]
            ],
            [
                'id' => 'knowledge',
                'title' => 'Knowledge Management System',
                'description' => 'Repositori terpusat untuk pengetahuan organisasi, praktik terbaik, dan dokumentasi untuk memfasilitasi berbagi dan pelestarian pengetahuan.',
                'icon' => 'book-open',
                'image' => 'images/features/knowledge-management.jpg',
                'benefits' => [
                    'Repositori pengetahuan terpusat',
                    'Dokumentasi dan sumber daya yang dapat dicari',
                    'Berbagi pengetahuan kolaboratif',
                    'Pelestarian pengetahuan institusional'
                ]
            ],
            [
                'id' => 'ticketing',
                'title' => 'Halo IPDS System',
                'description' => 'Sistem terintegrasi untuk melaporkan, melacak, dan menyelesaikan masalah IT, memastikan dukungan teknis yang cepat dan efisien.',
                'icon' => 'ticket',
                'image' => 'images/features/ticketing-system.jpg',
                'benefits' => [
                    'Penugasan dan prioritas tiket otomatis',
                    'Pembaruan status dan notifikasi real-time',
                    'Pelacakan dan penyelesaian masalah yang komprehensif',
                    'Analitik kinerja untuk tim dukungan IT'
                ]
            ],
            [
                'id' => 'finance',
                'title' => 'Integration Administrasi Keuangan',
                'description' => 'Sistem untuk mengelola administrasi dan keuangan proyek besar, melacak progres survei, dan memastikan transparansi keuangan.',
                'icon' => 'dollar-sign',
                'image' => 'images/features/finance-administration.jpg',
                'benefits' => [
                    'Pengelolaan anggaran dan kontrak proyek',
                    'Pelacakan dokumen survei secara real-time',
                    'Laporan keuangan untuk audit',
                    'Dukungan untuk tim internal dan eksternal'
                ]
            ],
            [
                'id' => 'padamu-negri',
                'title' => 'Padamu Negeri System',
                'description' => 'Platform terpusat untuk dokumentasi reformasi birokrasi, menyederhanakan pengelolaan dokumen dan proses administrasi.',
                'icon' => 'file-text',
                'image' => 'images/features/padamu-negri.jpg',
                'benefits' => [
                    'Dokumentasi reformasi birokrasi terpusat',
                    'Akses mudah ke dokumen administrasi',
                    'Otomatisasi proses birokrasi',
                    'Peningkatan transparansi dan akuntabilitas'
                ]
            ],
            [
                'id' => 'bahtera',
                'title' => 'Sistem Validasi BAHTERA',
                'description' => 'Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi - Alat untuk memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel (VHTS), membantu pegawai BPS Batam memastikan akurasi data.',
                'icon' => 'check-circle',
                'image' => 'images/features/vhts-validation.jpg',
                'benefits' => [
                    'Validasi data survei yang cepat dan akurat',
                    'Otomatisasi proses verifikasi',
                    'Laporan hasil survei yang terintegrasi',
                    'Dukungan untuk analisis data hotel'
                ]
            ]
        ];

        $benefits = [
            [
                'title' => 'Alur Kerja yang Efisien',
                'description' => 'Hilangkan proses berulang dan otomatisasi tugas rutin untuk meningkatkan efisiensi operasional.'
            ],
            [
                'title' => 'Kolaborasi yang Lebih Baik',
                'description' => 'Dorong kerja tim dengan alat komunikasi terintegrasi dan ruang kerja bersama.'
            ],
            [
                'title' => 'Keputusan Berbasis Data',
                'description' => 'Akses analitik real-time dan laporan komprehensif untuk pengambilan keputusan strategis.'
            ],
            [
                'title' => 'Akuntabilitas yang Ditingkatkan',
                'description' => 'Lacak progres, tetapkan tanggung jawab, dan pantau kinerja dengan sistem transparan.'
            ],
            [
                'title' => 'Optimalisasi Sumber Daya',
                'description' => 'Alokasikan sumber daya manusia dan material secara efisien berdasarkan data beban kerja.'
            ],
            [
                'title' => 'Pelestarian Pengetahuan',
                'description' => 'Simpan dan kelola pengetahuan institusional untuk kontinuitas dan pelatihan.'
            ]
        ];

        $stats = [
            [
                'value' => '100%',
                'label' => 'Integrasi'
            ],
            [
                'value' => '6+',
                'label' => 'Sistem Inti'
            ],
            [
                'value' => '80%',
                'label' => 'Peningkatan Efisiensi'
            ],
            [
                'value' => '24/7',
                'label' => 'Ketersediaan'
            ]
        ];

        $highlightSystems = [
            [
                'id' => 'ipds',
                'title' => 'Halo IP System',
                'subtitle' => 'Dukungan IT & Ticketing',
                'description' => 'Alat layanan pelanggan IT yang menghasilkan tiket untuk staf non-IT melaporkan dan menyelesaikan masalah teknis secara efisien. Halo IP menyederhanakan seluruh proses dukungan dari pelaporan hingga penyelesaian.',
                'icon' => 'ticket',
                'color' => 'blue',
                'image' => 'images/highlights/halo-ipds.jpg',
                'features' => [
                    'Ticketing Intuitif',
                    'Pelacakan Real-time',
                    'Manajemen Prioritas',
                    'Knowledge Base'
                ]
            ],
            [
                'id' => 'finance',
                'title' => 'Integration Administrasi Keuangan',
                'subtitle' => 'Pelacakan Anggaran & Pengeluaran',
                'description' => 'Sistem manajemen keuangan yang mengintegrasikan perencanaan anggaran, pelacakan pengeluaran, dan pelaporan keuangan untuk memastikan alokasi sumber daya yang optimal dan transparansi keuangan.',
                'icon' => 'dollar-sign',
                'color' => 'emerald',
                'image' => 'images/highlights/financial-management.jpg',
                'features' => [
                    'Perencanaan Anggaran',
                    'Pelacakan Pengeluaran',
                    'Laporan Keuangan',
                    'Dukungan Audit'
                ]
            ]
        ];

        $workflowSteps = [
            [
                'title' => 'Akses Terpadu',
                'description' => 'Single sign-on memberikan akses ke semua sistem terintegrasi, menghilangkan kebutuhan login berulang.',
                'icon' => 'users'
            ],
            [
                'title' => 'Pembaruan Real-time',
                'description' => 'Semua data disinkronkan secara real-time di seluruh sistem, memastikan semua pihak bekerja dengan informasi terbaru.',
                'icon' => 'clock'
            ],
            [
                'title' => 'Wawasan yang Dapat Ditindaklanjuti',
                'description' => 'Analitik canggih memberikan wawasan berarti untuk pengambilan keputusan yang lebih baik dan peningkatan kinerja.',
                'icon' => 'trending-up'
            ]
        ];

        return view('new-homepage.welcome', compact(
            'features',
            'benefits',
            'stats',
            'highlightSystems',
            'workflowSteps'
        ));
    }

    /**
     * Show the welcome page for authenticated users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function welcome()
    {
        $user = auth()->user();

        // Simplified dashboard - task tracking moved to Employee Performance Integration
        // Get user's team information if available
        $userTim = null;
        if (class_exists('\App\Models\UserAssignment')) {
            $userAssignment = \App\Models\UserAssignment::with('tim')
                ->where('user_id', $user->id)
                ->first();
            $userTim = $userAssignment?->tim ?? null;
        }

        return view('new-homepage.dashboard', compact(
            'user',
            'userTim'
        ));
    }

    /**
     * Show the new improved welcome page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newWelcome()
    {
        $features = [
            [
                'title' => 'Employee Performance Integration',
                'description' => 'Dashboard dinamis untuk melacak tugas pegawai, memantau progres kerja, dan memvisualisasikan metrik kinerja.',
                'benefits' => [
                    'Pelacakan tugas dan progres secara real-time',
                    'Visualisasi kinerja berbasis persentase',
                    'Dashboard KPI yang dapat disesuaikan untuk manajemen',
                    'Laporan dan analitik kinerja otomatis'
                ]
            ],
            [
                'title' => 'Knowledge Management System',
                'description' => 'Repositori terpusat untuk pengetahuan organisasi, praktik terbaik, dan dokumentasi untuk memfasilitasi berbagi dan pelestarian pengetahuan.',
                'benefits' => [
                    'Repositori pengetahuan terpusat',
                    'Dokumentasi dan sumber daya yang dapat dicari',
                    'Berbagi pengetahuan kolaboratif',
                    'Pelestarian pengetahuan institusional'
                ]
            ],
            [
                'title' => 'Halo IP System',
                'description' => 'Sistem terintegrasi untuk melaporkan, melacak, dan menyelesaikan masalah IT, memastikan dukungan teknis yang cepat dan efisien.',
                'benefits' => [
                    'Penugasan dan prioritas tiket otomatis',
                    'Pembaruan status dan notifikasi real-time',
                    'Pelacakan dan penyelesaian masalah yang komprehensif',
                    'Analitik kinerja untuk tim dukungan IT'
                ]
            ],
            [
                'title' => 'Integration Administrasi Keuangan',
                'description' => 'Sistem untuk mengelola administrasi dan keuangan proyek besar, melacak progres survei, dan memastikan transparansi keuangan.',
                'benefits' => [
                    'Pengelolaan anggaran dan kontrak proyek',
                    'Pelacakan dokumen survei secara real-time',
                    'Laporan keuangan untuk audit',
                    'Dukungan untuk tim internal dan eksternal'
                ]
            ],
            [
                'title' => 'Padamu Negeri System',
                'description' => 'Platform terpusat untuk dokumentasi reformasi birokrasi, menyederhanakan pengelolaan dokumen dan proses administrasi.',
                'benefits' => [
                    'Dokumentasi reformasi birokrasi terpusat',
                    'Akses mudah ke dokumen administrasi',
                    'Otomatisasi proses birokrasi',
                    'Peningkatan transparansi dan akuntabilitas'
                ]
            ],
            [
                'title' => 'Sistem Validasi BAHTERA',
                'description' => 'Batam Harmonisasi dan Evaluasi Terpadu Rekaman Akomodasi - Alat untuk memvalidasi hasil Survei Tingkat Penghunian Kamar Hotel (VHTS), membantu pegawai BPS Batam memastikan akurasi data.',
                'benefits' => [
                    'Validasi data survei yang cepat dan akurat',
                    'Otomatisasi proses verifikasi',
                    'Laporan hasil survei yang terintegrasi',
                    'Dukungan untuk analisis data hotel'
                ]
            ]
        ];

        return view('new-homepage.welcome-new', compact('features'));
    }

    /**
     * Show the under development page for new applications.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function underDevelopment()
    {
        return view('new-homepage.under-development');
    }
}