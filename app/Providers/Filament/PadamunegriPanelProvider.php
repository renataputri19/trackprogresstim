<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;

use App\Filament\Padamunegri\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationItem;

class PadamunegriPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('padamunegri')
            ->path('padamunegri')
            ->brandName('PADAMU NEGRI') // Set the panel name here
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigationItems([
                NavigationItem::make('Home')
                ->url('/')
                ->icon('heroicon-o-home')
                ->group('Other'),

                NavigationItem::make('Welcome')
                    ->url('/welcome')
                    ->icon('heroicon-o-home')
                    ->group('Other'),
            ])
            ->discoverResources(in: app_path('Filament/Padamunegri/Resources'), for: 'App\\Filament\\Padamunegri\\Resources')
            ->discoverPages(in: app_path('Filament/Padamunegri/Pages'), for: 'App\\Filament\\Padamunegri\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                // No widgets configured for now
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
