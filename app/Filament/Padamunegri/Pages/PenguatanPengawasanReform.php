<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenguatanPengawasanReform extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'II. REFORM';
    protected static ?string $navigationLabel = 'Penguatan Pengawasan';
    protected static ?int $navigationSort = 5;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penguatan Pengawasan - REFORM';
    }

    protected static string $view = 'filament.padamunegri.pages.penguatan-pengawasan-reform';
}
