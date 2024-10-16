<?php

namespace App\Filament\Resources\KalkulatorFuzzyResource\Pages;

use App\Filament\Resources\KalkulatorFuzzyResource;
use App\Services\FuzzyService;
use App\Services\GroqService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateKalkulatorFuzzy extends CreateRecord
{

    protected static string $resource = KalkulatorFuzzyResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $fuzzyService = new FuzzyService();
        $groqService = new GroqService();
        $hitungZcore = $fuzzyService->hitungZCore($data);
        $hitungFuzzy = $fuzzyService->hitungFuzzy($hitungZcore);
        $ruleFuzzy = $fuzzyService->ruleFuzzy($hitungFuzzy);
        $pesan = "Berikan kesimpulan dalam bahasa Indonesia. Hasil dari Stunting Fuzzy Analyzer untuk bayi dengan nama: " . $data['nama_bayi'] . " adalah sebagai berikut. Bayi bernama " . $data['nama_bayi'] . " dengan jenis kelamin " . $data['jenis_kelamin'] . " memiliki berat badan " . $data['berat_badan'] . " kg, usia " . $data['usia'] . " bulan, dan tinggi badan " . $data['tinggi_badan'] . " cm. Berdasarkan hasil perhitungan Z-Score, Z-Score BBU (Berat Badan terhadap Usia) adalah " . $hitungZcore[0]["z_score"] . ", Z-Score TBU (Tinggi Badan terhadap Usia) adalah " . $hitungZcore[1]["z_score"] . ", dan Z-Score BBTB (Berat Badan terhadap Tinggi Badan) adalah " . $hitungZcore[2]["z_score"] . ". Hasil dari Fuzzy Analysis menunjukkan bahwa Fuzzy BBU adalah " . json_encode($hitungFuzzy["BBU"]) . ", Fuzzy TBU adalah " . json_encode($hitungFuzzy["TBU"]) . ", dan Fuzzy BBTB adalah " . json_encode($hitungFuzzy["BBTB"]) . ". Berdasarkan analisis fuzzy, kondisi anak tersebut adalah " . json_encode($ruleFuzzy) . ".";
        $response = $groqService->getAssistantResponse($pesan);
        $normalizedResponse = htmlspecialchars($response['choices'][0]['message']['content'], ENT_QUOTES, 'UTF-8');

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
            "kesimpulan" => $normalizedResponse,
        ]);

        return $kalkulatorFuzzy;
    }
}
