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
        $this->reform = $service->getReformData();
    }

    protected static string $view = 'filament.padamunegri.pages.reform-penilaian';
}
