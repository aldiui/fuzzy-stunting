<?php

namespace App\Filament\Resources\VariabelFuzzyResource\Pages;

use App\Filament\Resources\VariabelFuzzyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditVariabelFuzzy extends EditRecord
{
    protected static string $resource = VariabelFuzzyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $tipe = $data['tipe'];

        $interval = [];
        $interval[] = $data['interval_1'];
        $interval[] = $data['interval_2'];
        $interval[] = $data['interval_3'];

        if ($tipe == "Trapesium") {
            $interval[] = $data['interval_4'];
        }

        $record->update([
            'nama' => $data['nama'],
            'kriteria' => $data['kriteria'],
            'range_awal' => $data['range_awal'],
            'range_akhir' => $data['range_akhir'],
            'himpunan_fuzzy_awal' => $data['himpunan_fuzzy_awal'],
            'himpunan_fuzzy_akhir' => $data['himpunan_fuzzy_akhir'],
            'tipe' => $tipe,
            'interval' => json_encode($interval),
        ]);

        return $record;
    }
}
