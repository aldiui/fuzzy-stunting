<?php

namespace App\Filament\Resources\KalkulatorFuzzyResource\Pages;

use Filament\Actions;
use App\Models\KalkulatorFuzzy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\HtmlString;
use App\Imports\KalkulatorFuzzyImport;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\KalkulatorFuzzyResource;

class ListKalkulatorFuzzies extends ListRecords
{
    protected static string $resource = KalkulatorFuzzyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('PDF')
                ->color('danger')
                ->icon('heroicon-s-document')
                ->action(function () {
                    $records = KalkulatorFuzzy::with('indexFuzzy')->get();

                    $options = [
                        'margin_top' => 20,
                        'margin_right' => 20,
                        'margin_bottom' => 20,
                        'margin_left' => 20,
                    ];

                    $pdfContent = Pdf::loadView('components.pdf_kalkulator_fuzzy', [
                        'records' => $records->map(function ($record) {
                            $record->fuzzy_bbu = $this->encodeJsonSafely($record->fuzzy_bbu);
                            $record->fuzzy_tbu = $this->encodeJsonSafely($record->fuzzy_tbu);
                            $record->fuzzy_bbtb = $this->encodeJsonSafely($record->fuzzy_bbtb);
                            $record->fuzzy_kondisi_anak = $this->encodeJsonSafely($record->fuzzy_kondisi_anak);
                            return $record;
                        }),
                    ])->setOptions($options)->setPaper('a4', 'landscape');

                    return response()->streamDownload(function () use ($pdfContent) {
                        echo $pdfContent->output();
                    }, 'kalkulator_fuzzy.pdf');
                }),

            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->label("Import")
                ->modalDescription(new HtmlString('Download Format Excel <a class="underline text-blue-600" href="/format_kalkulator.xlsx">disini</a>'))
                ->use(KalkulatorFuzzyImport::class),
            Actions\CreateAction::make(),
        ];
    }

    private function encodeJsonSafely($data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);
    }
}