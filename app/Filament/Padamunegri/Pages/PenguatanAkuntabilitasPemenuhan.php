<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenguatanAkuntabilitasPemenuhan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'I. PEMENUHAN';
    protected static ?string $navigationLabel = 'Penguatan Akuntabilitas';
    protected static ?int $navigationSort = 4;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penguatan Akuntabilitas - PEMENUHAN';
    }

    protected static string $view = 'filament.padamunegri.pages.penguatan-akuntabilitas-pemenuhan';
}
