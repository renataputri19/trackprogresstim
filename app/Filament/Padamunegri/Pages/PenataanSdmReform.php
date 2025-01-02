<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenataanSdmReform extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'II. REFORM';
    protected static ?string $navigationLabel = 'Penataan Sistem Manajemen SDM Aparatur';
    protected static ?int $navigationSort = 3;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penataan Sistem Manajemen SDM Aparatur - REFORM';
    }

    protected static string $view = 'filament.padamunegri.pages.penataan-sdm-reform';
}
