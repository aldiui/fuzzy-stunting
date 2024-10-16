<?php

namespace App\Filament\Resources\IndexFuzzyResource\Pages;

use App\Filament\Resources\IndexFuzzyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditIndexFuzzy extends EditRecord
{
    protected static string $resource = IndexFuzzyResource::class;

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
            'range_awal' => $data['range_awal'],
            'range_akhir' => $data['range_akhir'],
            'range_awal_fuzzy' => $data['range_awal_fuzzy'],
            'range_akhir_fuzzy' => $data['range_akhir_fuzzy'],
            'himpunan_fuzzy_awal' => $data['himpunan_fuzzy_awal'],
            'himpunan_fuzzy_akhir' => $data['himpunan_fuzzy_akhir'],
            'tipe' => $tipe,
            'interval' => json_encode($interval),
        ]);

        return $record;
    }
}
