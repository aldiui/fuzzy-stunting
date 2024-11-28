<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\DataDashboard;
use Filament\Navigation\NavigationItem;
use App\Filament\Widgets\DataStatsWidget;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\ChartKalkulatorFuzzy;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\BarChartKalkulatorFuzzyWidget;
use App\Filament\Widgets\PieChartKalkulatorFuzzyWidget;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('/')
            ->brandLogo(fn () => view('logo'))
            ->favicon('logo')
            ->darkMode(false)
            ->login()
            ->spa()
            ->profile()
            ->maxContentWidth('full')
            ->sidebarWidth('18rem')
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('5rem')
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->viteTheme('resources/css/app.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                DataStatsWidget::class,
                PieChartKalkulatorFuzzyWidget::class,
                BarChartKalkulatorFuzzyWidget::class,
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
            ])
            ->navigationGroups([
                'Data Master',
                'Data Transaksi',
                'Pengaturan',
            ])
            ->navigationItems([
                NavigationItem::make('Dokumentasi API')
                    ->url(url('api/documentation'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                    ->group('Pengaturan')
                ]
            );
    }
}