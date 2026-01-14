<?php

namespace App\Filament\Padamunegri\Pages;
use App\Services\PenilaianPilarService;


use Filament\Pages\Page;

class ReformPenilaian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Penilaian Pilar Reform';
    protected static ?string $title = 'Penilaian Pilar Reform';


    public $reform;

    public function mount(PenilaianPilarService $service)
    {
        $year = (int) session('padamu_year', 2025);
        $this->reform = $service->getReformData($year);
    }

    protected static string $view = 'filament.padamunegri.pages.reform-penilaian';
}
