<?php

namespace App\Services;

use App\Models\Bbtb;
use App\Models\Bbu;
use App\Models\IndexFuzzy;
use App\Models\RuleFuzzy;
use App\Models\Tbu;
use App\Models\VariabelFuzzy;

class FuzzyService
{
    public function hitungZCore(array $data)
    {
        $jenisKelamin = $data['jenis_kelamin'];
        $beratBadan = $data['berat_badan'];
        $usia = $data['usia'];
        $tinggiBadan = round($data['tinggi_badan'], 0);
        $status = $usia < 24 ? true : false;
        $kriteria = ['BBU', 'TBU', 'BBTB'];
        $hasilZcore = [];

        foreach ($kriteria as $key) {
            $z_scores = [1, 2, 3, 0, -1, -2, -3];
            switch ($key) {
                case 'BBU':
                    $variabelZcore = Bbu::where('umur', $usia)
                        ->where('jenis_kelamin', $jenisKelamin)
                        ->first();

                    if ($variabelZcore) {
                        $deviasi_values = [
                            $variabelZcore->standar_deviasi_plus_1,
                            $variabelZcore->standar_deviasi_plus_2,
                            $variabelZcore->standar_deviasi_plus_3,
                            $variabelZcore->standar_deviasi_median,
                            $variabelZcore->standar_deviasi_minus_1,
                            $variabelZcore->standar_deviasi_minus_2,
                            $variabelZcore->standar_deviasi_minus_3,
                        ];

                        if (in_array($beratBadan, $deviasi_values)) {
                            $index = array_search($beratBadan, $deviasi_values);
                            $hitung = $z_scores[$index];
                        } elseif ($beratBadan > $variabelZcore->standar_deviasi_plus_3) {
                            $hitung = 3;
                        } elseif ($beratBadan < $variabelZcore->standar_deviasi_minus_3) {
                            $hitung = -3;
                        } else {
                            $median = $variabelZcore->standar_deviasi_median;
                            if ($beratBadan < $median) {
                                $hitung = ($beratBadan - $median) / ($median - $variabelZcore->standar_deviasi_minus_1);
                            } else {
                                $hitung = ($beratBadan - $median) / ($variabelZcore->standar_deviasi_plus_1 - $median);
                            }
                        }

                        $hasilZcore[] = [
                            "kriteria" => $key,
                            "z_score" => round($hitung, 2),
                            "variabel_zcore" => $variabelZcore,
                        ];
                    }
                    break;

                case 'TBU':
                    $variabelZcore = Tbu::where('umur', $usia)
                        ->where('jenis_kelamin', $jenisKelamin)
                        ->first();

                    if ($variabelZcore) {
                        $deviasi_values = [
                            $variabelZcore->standar_deviasi_plus_1,
                            $variabelZcore->standar_deviasi_plus_2,
                            $variabelZcore->standar_deviasi_plus_3,
                            $variabelZcore->standar_deviasi_median,
                            $variabelZcore->standar_deviasi_minus_1,
                            $variabelZcore->standar_deviasi_minus_2,
                            $variabelZcore->standar_deviasi_minus_3,
                        ];

                        if (in_array($tinggiBadan, $deviasi_values)) {
                            $index = array_search($tinggiBadan, $deviasi_values);
                            $hitung = $z_scores[$index];
                        } elseif ($tinggiBadan > $variabelZcore->standar_deviasi_plus_3) {
                            $hitung = 3;
                        } elseif ($tinggiBadan < $variabelZcore->standar_deviasi_minus_3) {
                            $hitung = -3;
                        } else {
                            $median = $variabelZcore->standar_deviasi_median;

                            if ($tinggiBadan < $median) {
                                $hitung = ($tinggiBadan - $median) / ($median - $variabelZcore->standar_deviasi_minus_1);
                            } else {
                                $hitung = ($tinggiBadan - $median) / ($variabelZcore->standar_deviasi_plus_1 - $median);
                            }
                        }

                        $hasilZcore[] = [
                            "kriteria" => $key,
                            "z_score" => round($hitung, 2),
                            "variabel_zcore" => $variabelZcore,
                        ];
                    }
                    break;

                case 'BBTB':
                    $variabelZcore = Bbtb::where('tinggi_badan', $tinggiBadan)
                        ->where('status_usia_dibawah_24_bulan', $status)
                        ->where('jenis_kelamin', $jenisKelamin)
                        ->first();

                    if ($variabelZcore) {
                        $deviasi_values = [
                            $variabelZcore->standar_deviasi_plus_1,
                            $variabelZcore->standar_deviasi_plus_2,
                            $variabelZcore->standar_deviasi_plus_3,
                            $variabelZcore->standar_deviasi_median,
                            $variabelZcore->standar_deviasi_minus_1,
                            $variabelZcore->standar_deviasi_minus_2,
                            $variabelZcore->standar_deviasi_minus_3,
                        ];

                        if (in_array($beratBadan, $deviasi_values)) {
                            $index = array_search($beratBadan, $deviasi_values);
                            $hitung = $z_scores[$index];
                        } elseif ($beratBadan > $variabelZcore->standar_deviasi_plus_3) {
                            $hitung = 3;
                        } elseif ($beratBadan < $variabelZcore->standar_deviasi_minus_3) {
                            $hitung = -3;
                        } else {
                            $median = $variabelZcore->standar_deviasi_median;

                            if ($beratBadan < $median) {
                                $hitung = ($beratBadan - $median) / ($median - $variabelZcore->standar_deviasi_minus_1);
                            } else {
                                $hitung = ($beratBadan - $median) / ($variabelZcore->standar_deviasi_plus_1 - $median);
                            }
                        }

                        $hasilZcore[] = [
                            "kriteria" => $key,
                            "z_score" => round($hitung, 2),
                            "variabel_zcore" => $variabelZcore,
                        ];
                    }
                    break;

                default:
                    break;
            }
        }

        return $hasilZcore;
    }

    public function hitungFuzzy(array $data)
    {
        $hasilFinal = [];

        foreach ($data as $key) {
            $zscore = $key['z_score'];
            $variabelFuzzy = VariabelFuzzy::where('kriteria', $key['kriteria'])
                ->where('himpunan_fuzzy_awal', '<=', $zscore)
                ->where('himpunan_fuzzy_akhir', '>=', $zscore)
                ->orderBy('id', 'asc')
                ->get();

            $hasil = [];

            foreach ($variabelFuzzy as $fuzzyItem) {
                $intervalOriginal = $fuzzyItem->interval;
                $interval = $fuzzyItem->interval;

                if ($fuzzyItem->tipe == "Trapesium") {
                    $interval = array_values(array_unique($interval));
                }

                if ($fuzzyItem->tipe == "Segitiga" && $zscore == $interval[1]) {
                    $hitungFuzzy = 1;
                } elseif ($fuzzyItem->tipe == "Trapesium" && $zscore <= $interval[0] && $intervalOriginal[0] == $intervalOriginal[1]) {
                    $hitungFuzzy = 1;
                } elseif ($fuzzyItem->tipe == "Trapesium" && $zscore >= $interval[2] && $intervalOriginal[2] == $intervalOriginal[3]) {
                    $hitungFuzzy = 1;
                } else {
                    if ($zscore >= $interval[0] && $zscore <= $interval[1]) {
                        $hitungFuzzy = ($zscore - $interval[0]) / ($interval[1] - $interval[0]);
                    } elseif ($zscore >= $interval[1] && $zscore <= $interval[2]) {
                        $hitungFuzzy = ($interval[2] - $zscore) / ($interval[2] - $interval[1]);
                    }
                }

                if ($hitungFuzzy != 0) {
                    $hasil[] = [
                        "id" => $fuzzyItem->id,
                        "status" => $fuzzyItem->nama,
                        "tipe" => $fuzzyItem->tipe,
                        "range_awal" => $fuzzyItem->range_awal,
                        "range_akhir" => $fuzzyItem->range_akhir,
                        "himpunan_fuzzy_awal" => $fuzzyItem->himpunan_fuzzy_awal,
                        "himpunan_fuzzy_akhir" => $fuzzyItem->himpunan_fuzzy_akhir,
                        "interval" => $fuzzyItem->interval,
                        "fuzzy" => round($hitungFuzzy, 2),
                    ];
                }
            }

            $hasilFinal[$key['kriteria']] = $hasil;
        }

        return $hasilFinal;
    }

    public function ruleFuzzy(array $data)
    {
        $rule = [
            'BBU' => array_column($data['BBU'], 'id'),
            'TBU' => array_column($data['TBU'], 'id'),
            'BBTB' => array_column($data['BBTB'], 'id'),
        ];

        $ruleFuzzy = RuleFuzzy::with('indexFuzzy', 'variabelFuzzyBbu', 'variabelFuzzyTbu', 'variabelFuzzyBbtb')->whereIn('variabel_fuzzy_bbu_id', $rule['BBU'])
            ->whereIn('variabel_fuzzy_tbu_id', $rule['TBU'])
            ->whereIn('variabel_fuzzy_bbtb_id', $rule['BBTB'])
            ->orderBy('id', 'asc')
            ->get();

        foreach ($ruleFuzzy as $rule) {
            $fuzzyBbu = $this->getFuzzyValue($data['BBU'], $rule->variabel_fuzzy_bbu_id);
            $fuzzyTbu = $this->getFuzzyValue($data['TBU'], $rule->variabel_fuzzy_tbu_id);
            $fuzzyBbtb = $this->getFuzzyValue($data['BBTB'], $rule->variabel_fuzzy_bbtb_id);

            $rule->min = min($fuzzyBbu, $fuzzyTbu, $fuzzyBbtb);
            $rule->calculate = round($rule->min * $rule->indexFuzzy->range_akhir, 2);
        }

        $sumMin = $ruleFuzzy->sum('min');
        if ($sumMin != 0) {
            $wa = round($ruleFuzzy->sum('calculate') / $sumMin, 2);
        } else {
            $wa = 0;
        }
        $finalRule = IndexFuzzy::where('range_awal', '<=', $wa)
            ->where('range_akhir', '>=', $wa)
            ->first();

        return [
            "rules" => $ruleFuzzy,
            "weight_average" => $wa,
            "final_rules" => $finalRule,
        ];
    }

    private function getFuzzyValue(array $dataArray, $id)
    {
        foreach ($dataArray as $item) {
            if ($item['id'] == $id) {
                return $item['fuzzy'];
            }
        }
        return 0;
    }
}