<?php

namespace App\Services;

use App\Models\Criterion;

class PenilaianPilarService
{
    public function getPenilaianData(int $year = 2025)
    {
        return [
            'pemenuhan' => $this->getPemenuhanData($year),
            'reform' => $this->getReformData($year),
        ];
    }

    public function getPemenuhanData(int $year = 2025)
    {
        $pillars = [
            [
                'pilar' => 'Manajemen Perubahan',
                'categories' => [
                    ['penilaian' => 'Penyusunan Tim Kerja', 'bobot' => 0.50, 'selectedCategory' => 'Penyusunan Tim Kerja'],
                    ['penilaian' => 'Rencana Pembangunan Zona Integritas', 'bobot' => 1.00, 'selectedCategory' => 'Rencana Pembangunan Zona Integritas'],
                    ['penilaian' => 'Pemantauan dan Evaluasi Pembangunan WBK/WBBM', 'bobot' => 1.00, 'selectedCategory' => 'Pemantauan dan Evaluasi Pembangunan WBK/WBBM'],
                    ['penilaian' => 'Perubahan pola pikir dan budaya kerja', 'bobot' => 1.50, 'selectedCategory' => 'Perubahan Pola Pikir dan Budaya Kerja'],
                ],
            ],
            [
                'pilar' => 'Penataan Tatalaksana',
                'categories' => [
                    ['penilaian' => 'Prosedur Operasional Tetap (SOP) Kegiatan Utama', 'bobot' => 1.00, 'selectedCategory' => 'Prosedur Operasional Tetap (SOP) Kegiatan Utama'],
                    ['penilaian' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE)', 'bobot' => 2.00, 'selectedCategory' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE)'],
                    ['penilaian' => 'Keterbukaan Informasi Publik', 'bobot' => 0.50, 'selectedCategory' => 'Keterbukaan Informasi Publik'],
                ],
            ],
            [
                'pilar' => 'Penataan Sistem Manajemen SDM Aparatur',
                'categories' => [
                    ['penilaian' => 'Perencanaan Kebutuhan Pegawai', 'bobot' => 0.25, 'selectedCategory' => 'Perencanaan Kebutuhan Pegawai'],
                    ['penilaian' => 'Pola Mutasi Internal', 'bobot' => 0.50, 'selectedCategory' => 'Pola Mutasi Internal'],
                    ['penilaian' => 'Pengembangan Pegawai', 'bobot' => 1.25, 'selectedCategory' => 'Pengembangan Pegawai'],
                    ['penilaian' => 'Penetapan Kinerja Individu', 'bobot' => 2.00, 'selectedCategory' => 'Penetapan Kinerja Individu'],
                    ['penilaian' => 'Penegakan Aturan', 'bobot' => 0.75, 'selectedCategory' => 'Penegakan Aturan'],
                    ['penilaian' => 'Sistem Informasi Kepegawaian', 'bobot' => 0.25, 'selectedCategory' => 'Sistem Informasi Kepegawaian'],
                ],
            ],
            [
                'pilar' => 'Penguatan Akuntabilitas',
                'categories' => [
                    ['penilaian' => 'Keterlibatan Pimpinan', 'bobot' => 2.50, 'selectedCategory' => 'Keterlibatan Pimpinan'],
                    ['penilaian' => 'Pengelolaan Akuntabilitas Kinerja', 'bobot' => 2.50, 'selectedCategory' => 'Pengelolaan Akuntabilitas Kinerja'],
                ],
            ],
            [
                'pilar' => 'Penguatan Pengawasan',
                'categories' => [
                    ['penilaian' => 'Pengendalian Gratifikasi', 'bobot' => 1.50, 'selectedCategory' => 'Pengendalian Gratifikasi'],
                    ['penilaian' => 'Penerapan Sistem Pengendalian Intern Pemerintah (SPIP)', 'bobot' => 1.50, 'selectedCategory' => 'Penerapan SPIP'],
                    ['penilaian' => 'Pengaduan Masyarakat', 'bobot' => 1.50, 'selectedCategory' => 'Pengaduan Masyarakat'],
                    ['penilaian' => 'Whistle-Blowing System', 'bobot' => 1.50, 'selectedCategory' => 'Whistle-Blowing System'],
                    ['penilaian' => 'Penanganan Benturan Kepentingan', 'bobot' => 1.50, 'selectedCategory' => 'Penanganan Benturan Kepentingan'],
                ],
            ],
            [
                'pilar' => 'Peningkatan Kualitas Pelayanan Publik',
                'categories' => [
                    ['penilaian' => 'Standar Pelayanan', 'bobot' => 1.00, 'selectedCategory' => 'Standar Pelayanan'],
                    ['penilaian' => 'Budaya Pelayanan Prima', 'bobot' => 1.00, 'selectedCategory' => 'Budaya Pelayanan Prima'],
                    ['penilaian' => 'Pengelolaan Pengaduan', 'bobot' => 1.00, 'selectedCategory' => 'Pengelolaan Pengaduan'],
                    ['penilaian' => 'Penilaian Kepuasan terhadap Pelayanan', 'bobot' => 1.00, 'selectedCategory' => 'Penilaian Kepuasan'],
                    ['penilaian' => 'Pemanfaatan Teknologi Informasi', 'bobot' => 1.00, 'selectedCategory' => 'Pemanfaatan Teknologi Informasi'],
                ],
            ],
        ];
        // Process each pillar and calculate values
        $grandTotalBobot = 0;
        $grandTotalNilaiUnit = 0;
        $grandTotalNilaiTpi = 0;
    
        $processedPillars = collect($pillars)->map(function ($pilar) use (&$grandTotalBobot, &$grandTotalNilaiUnit, &$grandTotalNilaiTpi) {
            $totalBobot = 0;
            $totalNilaiUnit = 0;
            $totalNilaiTpi = 0;
    
            $categories = collect($pilar['categories'])->map(function ($category) use (&$totalBobot, &$totalNilaiUnit, &$totalNilaiTpi, $year) {
                $avgNilaiUnit = Criterion::where('category', $category['selectedCategory'])
                    ->where('year', $year)
                    ->pluck('nilai_unit')
                    ->map(fn($value) => $value ?? 0) // Replace null with 0
                    ->average();

                $avgNilaiTpi = Criterion::where('category', $category['selectedCategory'])
                    ->where('year', $year)
                    ->pluck('nilai_tpi')
                    ->map(fn($value) => $value ?? 0) // Replace null with 0
                    ->average();
    
                $nilaiUnitWeighted = $avgNilaiUnit * $category['bobot'];
                $nilaiTpiWeighted = $avgNilaiTpi * $category['bobot'];
    
                $totalBobot += $category['bobot'];
                $totalNilaiUnit += $nilaiUnitWeighted;
                $totalNilaiTpi += $nilaiTpiWeighted;
    
                return [
                    'penilaian' => $category['penilaian'],
                    'bobot' => $category['bobot'],
                    'nilai_unit' => number_format($nilaiUnitWeighted, 2),
                    'nilai_tpi' => number_format($nilaiTpiWeighted, 2),
                ];
            });
    
            $grandTotalBobot += $totalBobot;
            $grandTotalNilaiUnit += $totalNilaiUnit;
            $grandTotalNilaiTpi += $totalNilaiTpi;
    
            return [
                'pilar' => $pilar['pilar'],
                'categories' => $categories,
                'total_bobot' => number_format($totalBobot, 2),
                'total_nilai_unit' => number_format($totalNilaiUnit, 2),
                'total_nilai_tpi' => number_format($totalNilaiTpi, 2),
            ];
        });
    
        return [
            'pillars' => $processedPillars,
            'summary' => [
                'total_bobot' => number_format($grandTotalBobot, 2),
                'total_nilai_unit' => number_format($grandTotalNilaiUnit, 2),
                'total_nilai_tpi' => number_format($grandTotalNilaiTpi, 2),
            ],
        ];
    }


    public function getReformData(int $year = 2025)
    {
        $reformCategories = [
            [
                'pilar' => 'Manajemen Perubahan',
                'categories' => [
                    ['penilaian' => 'Komitmen dalam perubahan', 'bobot' => 2, 'selectedCategory' => 'Komitmen dalam perubahan'],
                    ['penilaian' => 'Komitmen Pimpinan', 'bobot' => 1.00, 'selectedCategory' => 'Komitmen Pimpinan'],
                    ['penilaian' => 'Membangun Budaya Kerja', 'bobot' => 1.00, 'selectedCategory' => 'Membangun Budaya Kerja'],
                ],
            ],
            [
                'pilar' => 'Penataan Tatalaksana',
                'categories' => [
                    ['penilaian' => 'Peta Proses Bisnis Mempengaruhi Penyederhanaan Jabatan', 'bobot' => 0.5, 'selectedCategory' => 'Peta Proses Bisnis Mempengaruhi Penyederhanaan Jabatan'],
                    ['penilaian' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE) yang Terintegrasi', 'bobot' => 1.00, 'selectedCategory' => 'Sistem Pemerintahan Berbasis Elektronik (SPBE) yang Terintegrasi'],
                    ['penilaian' => 'Transformasi Digital Memberikan Nilai Manfaat', 'bobot' => 2, 'selectedCategory' => 'Transformasi Digital Memberikan Nilai Manfaat'],
                ],
            ],
            [
                'pilar' => 'Penataan Sistem Manajemen SDM Aparatur',
                'categories' => [
                    ['penilaian' => 'Kinerja Individu', 'bobot' => 1.5, 'selectedCategory' => 'Kinerja Individu'],
                    ['penilaian' => 'Assessment Pegawai', 'bobot' => 1.50, 'selectedCategory' => 'Assessment Pegawai'],
                    ['penilaian' => 'Pelanggaran Disiplin Pegawai', 'bobot' => 2.00, 'selectedCategory' => 'Pelanggaran Disiplin Pegawai'],
                ],
            ],
            [
                'pilar' => 'Penguatan Akuntabilitas',
                'categories' => [
                    ['penilaian' => 'Meningkatnya capaian kinerja unit kerja', 'bobot' => 2.00, 'selectedCategory' => 'Meningkatnya capaian kinerja unit kerja'],
                    ['penilaian' => 'Pemberian Reward and Punishment', 'bobot' => 1.50, 'selectedCategory' => 'Pemberian Reward and Punishment'],
                    ['penilaian' => 'Kerangka Logis Kinerja', 'bobot' => 1.50, 'selectedCategory' => 'Kerangka Logis Kinerja'],
                ],
            ],
            [
                'pilar' => 'Penguatan Pengawasan',
                'categories' => [
                    ['penilaian' => 'Mekanisme Pengendalian', 'bobot' => 2.50, 'selectedCategory' => 'Mekanisme Pengendalian'],
                    ['penilaian' => 'Penanganan Pengaduan Masyarakat', 'bobot' => 3.00, 'selectedCategory' => 'Penanganan Pengaduan Masyarakat'],
                    ['penilaian' => 'Penyampaian Laporan Harta Kekayaan', 'bobot' => 2.00, 'selectedCategory' => 'Penyampaian Laporan Harta Kekayaan'],
                ],
            ],
            [
                'pilar' => 'Peningkatan Kualitas Pelayanan Publik',
                'categories' => [
                    [
                        'penilaian' => 'Upaya dan/atau Inovasi Pelayanan Publik',
                        'bobot' => 2.5,
                        'selectedCategories' => [
                            'Peningkatan Kualitas Pelayanan Publik - Criteria',
                            'Peningkatan Kualitas Pelayanan Publik - Reform',
                        ], // Use multiple categories for averaging
                    ],
                    [
                        'penilaian' => 'Penanganan Pengaduan Pelayanan dan Konsultasi',
                        'bobot' => 2.5,
                        'selectedCategory' => 'Penanganan Pengaduan Pelayanan dan Konsultasi',
                    ],
                ],
            ],
        ];
        

        $reformData = collect($reformCategories)->map(function ($pilar) use ($year) {
            $totalBobot = 0;
            $totalNilaiUnit = 0;
            $totalNilaiTpi = 0;
        
            $categories = collect($pilar['categories'])->map(function ($category) use (&$totalBobot, &$totalNilaiUnit, &$totalNilaiTpi, $year) {
                $avgNilaiUnit = 0;
                $avgNilaiTpi = 0;
        
                if (isset($category['selectedCategories'])) {
                    // Handle multiple selectedCategories for averaging
                    $nilaiUnits = collect($category['selectedCategories'])->map(function ($selectedCategory) use ($year) {
                        return Criterion::where('category', $selectedCategory)
                            ->where('year', $year)
                            ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                            ->pluck('nilai_unit')
                            ->map(fn($value) => $value ?? 0) // Replace null with 0
                            ->average();
                    });
        
                    $nilaiTpis = collect($category['selectedCategories'])->map(function ($selectedCategory) use ($year) {
                        return Criterion::where('category', $selectedCategory)
                            ->where('year', $year)
                            ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                            ->pluck('nilai_tpi')
                            ->map(fn($value) => $value ?? 0) // Replace null with 0
                            ->average();
                    });
        
                    $avgNilaiUnit = $nilaiUnits->average();
                    $avgNilaiTpi = $nilaiTpis->average();
                } else {
                    // Handle single selectedCategory
                    $avgNilaiUnit = Criterion::where('category', $category['selectedCategory'])
                        ->where('year', $year)
                        ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                        ->pluck('nilai_unit')
                        ->map(fn($value) => $value ?? 0) // Replace null with 0
                        ->average();
        
                    $avgNilaiTpi = Criterion::where('category', $category['selectedCategory'])
                        ->where('year', $year)
                        ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                        ->pluck('nilai_tpi')
                        ->map(fn($value) => $value ?? 0) // Replace null with 0
                        ->average();
                }
        
                $nilaiUnitWeighted = $avgNilaiUnit * $category['bobot'];
                $nilaiTpiWeighted = $avgNilaiTpi * $category['bobot'];
        
                $totalBobot += $category['bobot'];
                $totalNilaiUnit += $nilaiUnitWeighted;
                $totalNilaiTpi += $nilaiTpiWeighted;
        
                return [
                    'penilaian' => $category['penilaian'],
                    'bobot' => $category['bobot'],
                    'nilai_unit' => number_format($nilaiUnitWeighted, 2),
                    'nilai_tpi' => number_format($nilaiTpiWeighted, 2),
                ];
            });
        
            return [
                'pilar' => $pilar['pilar'],
                'categories' => $categories,
                'total_bobot' => number_format($totalBobot, 2),
                'total_nilai_unit' => number_format($totalNilaiUnit, 2),
                'total_nilai_tpi' => number_format($totalNilaiTpi, 2),
            ];
        });
        
        // Add Grand Total
        $grandTotalBobot = $reformData->sum('total_bobot');
        $grandTotalNilaiUnit = $reformData->sum('total_nilai_unit');
        $grandTotalNilaiTpi = $reformData->sum('total_nilai_tpi');
        
        return [
            'pillars' => $reformData,
            'summary' => [
                'total_bobot' => number_format($grandTotalBobot, 2),
                'total_nilai_unit' => number_format($grandTotalNilaiUnit, 2),
                'total_nilai_tpi' => number_format($grandTotalNilaiTpi, 2),
            ],
        ];
    }





    
}
