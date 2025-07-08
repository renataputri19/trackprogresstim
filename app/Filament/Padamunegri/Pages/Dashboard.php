<?php

namespace App\Filament\Padamunegri\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.padamunegri.pages.dashboard';
    
    public function getTitle(): string
    {
        return 'Dashboard PADAMU NEGRI';
    }
    
    public function getColumns(): int | string | array
    {
        return 2;
    }
}
