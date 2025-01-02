<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class ManajemenPerubahanReform extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'II. REFORM';
    protected static ?string $navigationLabel = 'Manajemen Perubahan';
    protected static ?int $navigationSort = 1;

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Manajemen Perubahan - REFORM';
    }

    protected static string $view = 'filament.padamunegri.pages.manajemen-perubahan-reform';
}
