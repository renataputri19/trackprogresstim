<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;
use App\Services\PenilaianPilarService;

class PenilaianPilar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?string $navigationLabel = 'Penilaian Pilar Pemenuhan';
    protected static ?string $title = 'Penilaian Pilar Pemenuhan';

    public $pemenuhan;

    public function mount(PenilaianPilarService $service)
    {
        $this->pemenuhan = $service->getPemenuhanData();
    }
    

    protected static string $view = 'filament.padamunegri.pages.penilaian-pilar';
}

