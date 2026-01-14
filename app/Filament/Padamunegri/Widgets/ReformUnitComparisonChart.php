<?php

namespace App\Filament\Padamunegri\Widgets;

use App\Services\PenilaianPilarService;
use Filament\Widgets\ChartWidget;

class ReformUnitComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Reform: Perbandingan Nilai Unit 2025 vs 2026';
    protected static ?int $sort = 20;

    protected function getData(): array
    {
        $service = app(PenilaianPilarService::class);

        $data2025 = $service->getReformData(2025);
        $data2026 = $service->getReformData(2026);

        $labels = collect($data2025['pillars'])->map(fn($p) => $p['pilar'])->toArray();
        $baseline = collect($data2025['pillars'])->map(fn($p) => (float) $p['total_nilai_unit'])->toArray();
        $current = collect($data2026['pillars'])->map(fn($p) => (float) $p['total_nilai_unit'])->toArray();

        return [
            'datasets' => [
                [
                    'label' => '2025',
                    'data' => $baseline,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // blue-500
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => '2026',
                    'data' => $current,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)', // green-500
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'indexAxis' => 'x', // vertical bars
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'display' => true,
                    ],
                ],
                'x' => [
                    'stacked' => true,
                    'ticks' => [
                        'autoSkip' => false,
                        'maxRotation' => 45,
                        'minRotation' => 0,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}