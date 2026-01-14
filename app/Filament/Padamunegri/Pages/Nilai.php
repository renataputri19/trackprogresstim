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
    protected static ?int $navigationSort = 2; // ensure below Perbandingan Nilai

    public $year;
    public $pemenuhan;
    public $reform;
    public $grandTotal;

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

        // No comparison logic here; comparison is handled on Perbandingan Nilai page
    }

    protected static string $view = 'filament.padamunegri.pages.nilai';
}
