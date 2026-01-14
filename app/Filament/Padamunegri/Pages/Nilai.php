<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;
use App\Services\PenilaianPilarService;

class Nilai extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Nilai';
    protected static ?string $title = 'Nilai';

    public $year;
    public $pemenuhan;
    public $reform;
    public $grandTotal;
    public $comparison;

    public function mount(PenilaianPilarService $service)
    {
        $this->year = (int) session('padamu_year', 2025);

        // Data for selected year
        $this->pemenuhan = $service->getPemenuhanData($this->year);
        $this->reform = $service->getReformData($this->year);

        // Calculate Grand Total for selected year
        $this->grandTotal = [
            'total_bobot' => number_format(
                (float) $this->pemenuhan['summary']['total_bobot'] + (float) $this->reform['summary']['total_bobot'],
                2
            ),
            'total_nilai_unit' => number_format(
                (float) $this->pemenuhan['summary']['total_nilai_unit'] + (float) $this->reform['summary']['total_nilai_unit'],
                2
            ),
            'total_nilai_tpi' => number_format(
                (float) $this->pemenuhan['summary']['total_nilai_tpi'] + (float) $this->reform['summary']['total_nilai_tpi'],
                2
            ),
        ];

        // Comparison: always compare 2026 to baseline 2025 if current year is 2026
        $this->comparison = null;
        if ($this->year === 2026) {
            $baselinePemenuhan = $service->getPemenuhanData(2025);
            $baselineReform = $service->getReformData(2025);

            $baselineGrandTotalUnit = (float) $baselinePemenuhan['summary']['total_nilai_unit'] + (float) $baselineReform['summary']['total_nilai_unit'];
            $baselineGrandTotalTpi = (float) $baselinePemenuhan['summary']['total_nilai_tpi'] + (float) $baselineReform['summary']['total_nilai_tpi'];

            $currentGrandTotalUnit = (float) $this->grandTotal['total_nilai_unit'];
            $currentGrandTotalTpi = (float) $this->grandTotal['total_nilai_tpi'];

            $this->comparison = [
                'baseline_year' => 2025,
                'current_year' => 2026,
                'baseline' => [
                    'total_nilai_unit' => number_format($baselineGrandTotalUnit, 2),
                    'total_nilai_tpi' => number_format($baselineGrandTotalTpi, 2),
                ],
                'current' => [
                    'total_nilai_unit' => number_format($currentGrandTotalUnit, 2),
                    'total_nilai_tpi' => number_format($currentGrandTotalTpi, 2),
                ],
                'diff' => [
                    'total_nilai_unit' => number_format($currentGrandTotalUnit - $baselineGrandTotalUnit, 2),
                    'total_nilai_tpi' => number_format($currentGrandTotalTpi - $baselineGrandTotalTpi, 2),
                ],
            ];
        }
    }

    protected static string $view = 'filament.padamunegri.pages.nilai';
}
