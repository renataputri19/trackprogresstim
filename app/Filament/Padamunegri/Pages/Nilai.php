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

    public $pemenuhan;
    public $reform;
    public $grandTotal;

    public function mount(PenilaianPilarService $service)
    {
        $this->pemenuhan = $service->getPemenuhanData();
        $this->reform = $service->getReformData();

        // Calculate Grand Total
        $this->grandTotal = [
            'total_bobot' => number_format(
                $this->pemenuhan['summary']['total_bobot'] + $this->reform['summary']['total_bobot'],
                2
            ),
            'total_nilai_unit' => number_format(
                $this->pemenuhan['summary']['total_nilai_unit'] + $this->reform['summary']['total_nilai_unit'],
                2
            ),
            'total_nilai_tpi' => number_format(
                $this->pemenuhan['summary']['total_nilai_tpi'] + $this->reform['summary']['total_nilai_tpi'],
                2
            ),
        ];
    }

    protected static string $view = 'filament.padamunegri.pages.nilai';
}
