<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenguatanPengawasanPemenuhan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'I. PEMENUHAN';
    protected static ?string $navigationLabel = 'Penguatan Pengawasan';
    protected static ?int $navigationSort = 5;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penguatan Pengawasan - PEMENUHAN';
    }

    protected static string $view = 'filament.padamunegri.pages.penguatan-pengawasan-pemenuhan';
}
