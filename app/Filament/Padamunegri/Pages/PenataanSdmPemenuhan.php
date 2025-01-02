<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenataanSdmPemenuhan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'I. PEMENUHAN';
    protected static ?string $navigationLabel = 'Penataan Sistem Manajemen SDM Aparatur';
    protected static ?int $navigationSort = 3;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penataan Sistem Manajemen SDM Aparatur - PEMENUHAN';
    }

    protected static string $view = 'filament.padamunegri.pages.penataan-sdm-pemenuhan';
}
