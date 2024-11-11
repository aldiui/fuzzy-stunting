<?php

namespace App\Imports;

use App\Models\KalkulatorFuzzy;
use App\Services\FuzzyService;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KalkulatorFuzzyImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        $importedCount = 0;

        foreach ($collection as $row) {
            try {
                $data = $this->extractData($row);

                if ($this->processRow($data)) {
                    $importedCount++;
                }
            } catch (\Exception $e) {
                Log::error('Error processing row: ' . $e->getMessage(), ['row' => $row->toArray()]);
            }
        }

        $this->sendNotification($importedCount);
    }

    private function extractData($row): array
    {
        return [
            "nama_bayi" => $row['nama_bayi'],
            "usia" => $row['usia'],
            "berat_badan" => $row['berat_badan'],
            "tinggi_badan" => $row['tinggi_badan'],
            "jenis_kelamin" => $row['jenis_kelamin'],
        ];
    }

    private function processRow(array $data): bool
    {
        $fuzzyService = new FuzzyService();
        $hitungZcore = $fuzzyService->hitungZCore($data);
        $hitungFuzzy = $fuzzyService->hitungFuzzy($hitungZcore);
        $ruleFuzzy = $fuzzyService->ruleFuzzy($hitungFuzzy);

        $user = auth()->user();

        $kalkulatorFuzzy = $user->kalkulatorFuzzies()->create([
            "index_fuzzy_id" => $ruleFuzzy['final_rules']['id'],
            "nama_bayi" => $data['nama_bayi'],
            "jenis_kelamin" => $data['jenis_kelamin'],
            "berat_badan" => $data['berat_badan'],
            "usia" => $data['usia'],
            "tinggi_badan" => $data['tinggi_badan'],
            "z_score_bbu" => $hitungZcore[0]["z_score"],
            "z_score_tbu" => $hitungZcore[1]["z_score"],
            "z_score_bbtb" => $hitungZcore[2]["z_score"],
            "fuzzy_bbu" => $hitungFuzzy["BBU"],
            "fuzzy_tbu" => $hitungFuzzy["TBU"],
            "fuzzy_bbtb" => $hitungFuzzy["BBTB"],
            "kondisi_anak" => $ruleFuzzy,
            "kesimpulan" => "-",
        ]);

        return true;
    }

    private function sendNotification(int $importedCount): void
    {
        Notification::make()
            ->title('Import Completed')
            ->body("Successfully imported {$importedCount} Kalkulator Fuzzy records.")
            ->success()
            ->send();
    }
}
