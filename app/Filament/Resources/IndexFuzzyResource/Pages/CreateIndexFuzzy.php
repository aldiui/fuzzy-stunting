<?php

namespace App\Filament\Resources\IndexFuzzyResource\Pages;

use App\Models\IndexFuzzy;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\IndexFuzzyResource;

class CreateIndexFuzzy extends CreateRecord
{
    protected static string $resource = IndexFuzzyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $tipe = $data['tipe'];

        $interval = [];
        $interval[] = $data['interval_1'];
        $interval[] = $data['interval_2'];
        $interval[] = $data['interval_3'];

        if ($tipe == "Trapesium") {
            $interval[] = $data['interval_4'];
        }

        $indexFuzzy = IndexFuzzy::create([
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

        return $indexFuzzy;
    }
}