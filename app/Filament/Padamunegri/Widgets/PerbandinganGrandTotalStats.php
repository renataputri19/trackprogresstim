<?php

namespace App\Filament\Padamunegri\Widgets;

use App\Services\PenilaianPilarService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PerbandinganGrandTotalStats extends StatsOverviewWidget
{
    protected static ?int $sort = 5;
    protected ?string $heading = 'Ringkasan Grand Total';

    protected function getStats(): array
    {
        $service = app(PenilaianPilarService::class);

        $pemenuhan2025 = $service->getPemenuhanData(2025);
        $pemenuhan2026 = $service->getPemenuhanData(2026);
        $reform2025 = $service->getReformData(2025);
        $reform2026 = $service->getReformData(2026);

        $baselineUnit = (float) $pemenuhan2025['summary']['total_nilai_unit'] + (float) $reform2025['summary']['total_nilai_unit'];
        $currentUnit = (float) $pemenuhan2026['summary']['total_nilai_unit'] + (float) $reform2026['summary']['total_nilai_unit'];
        $deltaUnit = $currentUnit - $baselineUnit;
        $pctUnit = $baselineUnit !== 0.0 ? ($deltaUnit / $baselineUnit) * 100 : null;

        $baselineTpi = (float) $pemenuhan2025['summary']['total_nilai_tpi'] + (float) $reform2025['summary']['total_nilai_tpi'];
        $currentTpi = (float) $pemenuhan2026['summary']['total_nilai_tpi'] + (float) $reform2026['summary']['total_nilai_tpi'];
        $deltaTpi = $currentTpi - $baselineTpi;
        $pctTpi = $baselineTpi !== 0.0 ? ($deltaTpi / $baselineTpi) * 100 : null;

        $unitUp = $deltaUnit >= 0;
        $tpiUp = $deltaTpi >= 0;

        return [
            Stat::make('Grand Total Nilai Unit (2026)', number_format($currentUnit, 2))
                ->description(($deltaUnit >= 0 ? '+' : '') . number_format($deltaUnit, 2) . ($pctUnit !== null ? ' (' . number_format($pctUnit, 2) . '%)' : ''))
                ->descriptionIcon($unitUp ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($unitUp ? 'success' : 'danger'),

            Stat::make('Grand Total Nilai TPI (2026)', number_format($currentTpi, 2))
                ->description(($deltaTpi >= 0 ? '+' : '') . number_format($deltaTpi, 2) . ($pctTpi !== null ? ' (' . number_format($pctTpi, 2) . '%)' : ''))
                ->descriptionIcon($tpiUp ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($tpiUp ? 'success' : 'danger'),
        ];
    }
}