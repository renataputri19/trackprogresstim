<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class PenguatanAkuntabilitasReform extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'II. REFORM';
    protected static ?string $navigationLabel = 'Penguatan Akuntabilitas';
    protected static ?int $navigationSort = 4;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Penguatan Akuntabilitas - REFORM';
    }

    protected static string $view = 'filament.padamunegri.pages.penguatan-akuntabilitas-reform';
}
