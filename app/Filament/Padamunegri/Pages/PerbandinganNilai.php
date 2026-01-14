<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;
use App\Services\PenilaianPilarService;

class PerbandinganNilai extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Perbandingan Nilai';
    protected static ?string $title = 'Perbandingan Nilai';
    protected static ?int $navigationSort = 1; // place above Nilai
    protected static ?string $slug = 'perbandingan-nilai';
    protected static bool $shouldRegisterNavigation = true;

    protected static string $view = 'filament.padamunegri.pages.perbandingan-nilai';

    public int $baselineYear = 2025;
    public int $currentYear = 2026;

    public array $pemenuhan2025 = [];
    public array $pemenuhan2026 = [];
    public array $reform2025 = [];
    public array $reform2026 = [];

    public array $grandTotal2025 = [];
    public array $grandTotal2026 = [];

    public array $comparison = [];

    public function mount(PenilaianPilarService $service)
    {
        // Always compare fixed baseline and current years
        $this->pemenuhan2025 = $service->getPemenuhanData($this->baselineYear);
        $this->pemenuhan2026 = $service->getPemenuhanData($this->currentYear);
        $this->reform2025 = $service->getReformData($this->baselineYear);
        $this->reform2026 = $service->getReformData($this->currentYear);

        $this->grandTotal2025 = [
            'total_bobot' => number_format(
                (float) $this->pemenuhan2025['summary']['total_bobot'] + (float) $this->reform2025['summary']['total_bobot'],
                2
            ),
            'total_nilai_unit' => number_format(
                (float) $this->pemenuhan2025['summary']['total_nilai_unit'] + (float) $this->reform2025['summary']['total_nilai_unit'],
                2
            ),
            'total_nilai_tpi' => number_format(
                (float) $this->pemenuhan2025['summary']['total_nilai_tpi'] + (float) $this->reform2025['summary']['total_nilai_tpi'],
                2
            ),
        ];

        $this->grandTotal2026 = [
            'total_bobot' => number_format(
                (float) $this->pemenuhan2026['summary']['total_bobot'] + (float) $this->reform2026['summary']['total_bobot'],
                2
            ),
            'total_nilai_unit' => number_format(
                (float) $this->pemenuhan2026['summary']['total_nilai_unit'] + (float) $this->reform2026['summary']['total_nilai_unit'],
                2
            ),
            'total_nilai_tpi' => number_format(
                (float) $this->pemenuhan2026['summary']['total_nilai_tpi'] + (float) $this->reform2026['summary']['total_nilai_tpi'],
                2
            ),
        ];

        // Prepare comparison structure including diffs and percentage changes
        $baselineUnit = (float) $this->grandTotal2025['total_nilai_unit'];
        $baselineTpi = (float) $this->grandTotal2025['total_nilai_tpi'];
        $currentUnit = (float) $this->grandTotal2026['total_nilai_unit'];
        $currentTpi = (float) $this->grandTotal2026['total_nilai_tpi'];

        $this->comparison = [
            'baseline_year' => $this->baselineYear,
            'current_year' => $this->currentYear,
            'grand_total' => [
                'baseline' => [
                    'total_nilai_unit' => number_format($baselineUnit, 2),
                    'total_nilai_tpi' => number_format($baselineTpi, 2),
                ],
                'current' => [
                    'total_nilai_unit' => number_format($currentUnit, 2),
                    'total_nilai_tpi' => number_format($currentTpi, 2),
                ],
                'diff' => [
                    'total_nilai_unit' => number_format($currentUnit - $baselineUnit, 2),
                    'total_nilai_tpi' => number_format($currentTpi - $baselineTpi, 2),
                ],
                'pct' => [
                    'total_nilai_unit' => $baselineUnit != 0.0 ? number_format((($currentUnit - $baselineUnit) / $baselineUnit) * 100, 2) : null,
                    'total_nilai_tpi' => $baselineTpi != 0.0 ? number_format((($currentTpi - $baselineTpi) / $baselineTpi) * 100, 2) : null,
                ],
            ],
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Padamunegri\Widgets\PemenuhanUnitComparisonChart::class,
            \App\Filament\Padamunegri\Widgets\ReformUnitComparisonChart::class,
            \App\Filament\Padamunegri\Widgets\PerbandinganGrandTotalStats::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int
    {
        // Stack widgets vertically to avoid cramped layouts
        return 1;
    }
}