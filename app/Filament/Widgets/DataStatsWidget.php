<?php

namespace App\Filament\Widgets;

use App\Models\Bbu;
use App\Models\Tbu;
use App\Models\Bbtb;
use App\Models\RuleFuzzy;
use App\Models\IndexFuzzy;
use App\Models\VariabelFuzzy;
use App\Models\KalkulatorFuzzy;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DataStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        return [
            Stat::make('Z-Score BB/U', Bbu::count())
                ->icon('heroicon-o-scale')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Z-Score TB/U', Tbu::count())
                ->icon('heroicon-o-arrow-up-circle')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Z-Score BB/TB', Bbtb::count())
                ->icon('heroicon-o-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Variabel Fuzzy', VariabelFuzzy::count())
                ->icon('heroicon-o-variable')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Index Fuzzy', IndexFuzzy::count())
                ->icon('heroicon-o-viewfinder-circle')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Rule Fuzzy', RuleFuzzy::count())
                ->icon('heroicon-o-arrow-turn-left-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Kalkulator Fuzzy', KalkulatorFuzzy::count())
                ->icon('heroicon-o-calculator')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
        ];
    }
}