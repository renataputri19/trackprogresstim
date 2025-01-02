<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Page;

class ContohFileDanMateriRB extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    // protected static ?string $navigationGroup = 'Dashboard'; // Group it under Dashboard
    protected static ?string $navigationLabel = 'Contoh File dan Materi RB 2024'; // The label for navigation

    // Correctly override the getTitle method
    public function getTitle(): string
    {
        return 'Contoh File dan Materi RB 2024';
    }


    protected static string $view = 'filament.padamunegri.pages.contoh-file-dan-materi-r-b';
}
