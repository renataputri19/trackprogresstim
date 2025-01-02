<?php

namespace App\Services;

use App\Models\Criterion;

class PenilaianReformService
{
    public function getReformPenilaianData()
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
        

        $reformData = collect($reformCategories)->map(function ($pilar) {
            $totalBobot = 0;
            $totalNilaiUnit = 0;
            $totalNilaiTpi = 0;
        
            $categories = collect($pilar['categories'])->map(function ($category) use (&$totalBobot, &$totalNilaiUnit, &$totalNilaiTpi) {
                $avgNilaiUnit = 0;
                $avgNilaiTpi = 0;
        
                if (isset($category['selectedCategories'])) {
                    // Handle multiple selectedCategories for averaging
                    $nilaiUnits = collect($category['selectedCategories'])->map(function ($selectedCategory) {
                        return Criterion::where('category', $selectedCategory)
                            ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                            ->pluck('nilai_unit')
                            ->map(fn($value) => $value ?? 0) // Replace null with 0
                            ->average();
                    });
        
                    $nilaiTpis = collect($category['selectedCategories'])->map(function ($selectedCategory) {
                        return Criterion::where('category', $selectedCategory)
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
                        ->where('pilihan_jawaban', '!=', 'Jumlah') // Exclude "Jumlah"
                        ->pluck('nilai_unit')
                        ->map(fn($value) => $value ?? 0) // Replace null with 0
                        ->average();
        
                    $avgNilaiTpi = Criterion::where('category', $category['selectedCategory'])
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
            'data' => $reformData,
            'summary' => [
                'total_bobot' => number_format($grandTotalBobot, 2),
                'total_nilai_unit' => number_format($grandTotalNilaiUnit, 2),
                'total_nilai_tpi' => number_format($grandTotalNilaiTpi, 2),
            ],
        ];
    }
}