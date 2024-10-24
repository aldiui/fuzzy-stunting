<?php

namespace App\Filament\Widgets;

use App\Models\KalkulatorFuzzy;
use Filament\Widgets\ChartWidget;

class BarChartKalkulatorFuzzyWidget extends ChartWidget
{
    protected static ?string $heading = 'Bar Chart Kalkulator Fuzzy';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Hasil Kalkulator Fuzzy',
                    'data' => [
                        KalkulatorFuzzy::where('index_fuzzy_id', 1)->count(),
                        KalkulatorFuzzy::where('index_fuzzy_id', 2)->count(),
                        KalkulatorFuzzy::where('index_fuzzy_id', 3)->count(),
                        KalkulatorFuzzy::where('index_fuzzy_id', 4)->count(),
                        KalkulatorFuzzy::where('index_fuzzy_id', 5)->count(),
                    ],
                    'backgroundColor' => [
                        '#ff6384',
                        '#36a2eb',
                        '#ffcd56',
                        '#4bc0c0',
                        '#9966ff',
                    ],
                ],
            ],
            'labels' => [
                'Stunting Parah',
                'Stunting Sedang',
                'Stunting Ringan',
                'Normal',
                'Obesitas',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

}