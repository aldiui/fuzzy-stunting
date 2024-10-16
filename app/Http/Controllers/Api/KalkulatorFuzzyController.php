<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FuzzyService;
use App\Services\GroqService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KalkulatorFuzzyController extends Controller
{
    use ApiResponder;

    private $fuzzyService;
    private $groqService;

    public function __construct(FuzzyService $fuzzyService, GroqService $groqService)
    {
        $this->fuzzyService = $fuzzyService;
        $this->groqService = $groqService;
    }

    /**
     * @OA\Get(
     *     path="/api/kalkulator-fuzzy",
     *     summary="Ambil riwayat perhitungan fuzzy pengguna",
     *     tags={"Kalkulator Fuzzy"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Jumlah data per halaman",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Halaman yang ingin dilihat",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil riwayat perhitungan fuzzy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ambil data"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nama_bayi", type="string", example="Bayi 1"),
     *                     @OA\Property(property="kondisi_anak", type="string", example="Normal"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mengambil riwayat perhitungan fuzzy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkankan data"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $user = auth('api')->user();
            $kalkulatorFuzzy = $user->kalkulatorFuzzies()->orderBy('created_at', 'DESC')->paginate($request->get('per_page', 5));
            return $this->successResponse($kalkulatorFuzzy, 'Ambil data', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/kalkulator-fuzzy/{id}",
     *     summary="Ambil data perhitungan fuzzy berdasarkan ID",
     *     tags={"Kalkulator Fuzzy"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Kalkulator Fuzzy",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ambil data"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nama_bayi", type="string", example="Bayi 1"),
     *                 @OA\Property(property="kondisi_anak", type="string", example="Normal"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Data tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkankan data"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mendapatkan data",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkankan data"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $user = auth('api')->user();
            $kalkulatorFuzzy = $user->kalkulatorFuzzies()->find($id);

            if (!$kalkulatorFuzzy) {
                return $this->errorResponse(null, 'Gagal mendapatkankan data', 404);
            }

            return $this->successResponse($kalkulatorFuzzy, 'Ambil data', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/kalkulator-fuzzy",
     *     summary="Hitung data kalkulator fuzzy",
     *     tags={"Kalkulator Fuzzy"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_bayi", "jenis_kelamin", "usia", "berat_badan", "tinggi_badan"},
     *             @OA\Property(property="nama_bayi", type="string", example="Bayi 1"),
     *             @OA\Property(property="jenis_kelamin", type="string", enum={"Laki - Laki", "Perempuan"}, example="Laki - Laki"),
     *             @OA\Property(property="usia", type="integer", example=12),
     *             @OA\Property(property="berat_badan", type="number", format="float", example=10.5),
     *             @OA\Property(property="tinggi_badan", type="number", format="float", example=75.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Data fuzzy berhasil dihitung",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data fuzzy berhasil dihitung."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="z_score_bbu", type="float", example=-1.2),
     *                 @OA\Property(property="z_score_tbu", type="float", example=0.8),
     *                 @OA\Property(property="z_score_bbtb", type="float", example=1.1),
     *                 @OA\Property(property="fuzzy_bbu", type="string", example="Normal"),
     *                 @OA\Property(property="fuzzy_tbu", type="string", example="Pendek"),
     *                 @OA\Property(property="fuzzy_bbtb", type="string", example="Normal"),
     *                 @OA\Property(property="kondisi_anak", type="string", example="Normal")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Kesalahan validasi",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Kesalahan validasi"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal menghitung data fuzzy",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal menghitung data fuzzy"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $params = $request->all();
            $validator = Validator::make($params, [
                'nama_bayi' => 'required|string|max:255',
                'jenis_kelamin' => 'required|string|in:Laki - Laki,Perempuan',
                'usia' => 'required|integer',
                'berat_badan' => 'required|numeric',
                'tinggi_badan' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Kesalahan validasi', 422);
            }

            $hitungZcore = $this->fuzzyService->hitungZCore($params);
            $hitungFuzzy = $this->fuzzyService->hitungFuzzy($hitungZcore);
            $ruleFuzzy = $this->fuzzyService->ruleFuzzy($hitungFuzzy);
            $pesan = "Berikan kesimpulan dalam bahasa Indonesia. Hasil dari Stunting Fuzzy Analyzer untuk bayi dengan nama: " . $params['nama_bayi'] . " adalah sebagai berikut. Bayi bernama " . $params['nama_bayi'] . " dengan jenis kelamin " . $params['jenis_kelamin'] . " memiliki berat badan " . $params['berat_badan'] . " kg, usia " . $params['usia'] . " bulan, dan tinggi badan " . $params['tinggi_badan'] . " cm. Berdasarkan hasil perhitungan Z-Score, Z-Score BBU (Berat Badan terhadap Usia) adalah " . $hitungZcore[0]["z_score"] . ", Z-Score TBU (Tinggi Badan terhadap Usia) adalah " . $hitungZcore[1]["z_score"] . ", dan Z-Score BBTB (Berat Badan terhadap Tinggi Badan) adalah " . $hitungZcore[2]["z_score"] . ". Hasil dari Fuzzy Analysis menunjukkan bahwa Fuzzy BBU adalah " . json_encode($hitungFuzzy["BBU"]) . ", Fuzzy TBU adalah " . json_encode($hitungFuzzy["TBU"]) . ", dan Fuzzy BBTB adalah " . json_encode($hitungFuzzy["BBTB"]) . ". Berdasarkan analisis fuzzy, kondisi anak tersebut adalah " . json_encode($ruleFuzzy) . ".";
            $response = $this->groqService->getAssistantResponse($pesan);
            $normalizedResponse = htmlspecialchars($response['choices'][0]['message']['content'], ENT_QUOTES, 'UTF-8');

            $user = auth('api')->user();
            $kalkulatorFuzzy = $user->kalkulatorFuzzies()->create([
                "user_id" => $user->id,
                "index_fuzzy_id" => $ruleFuzzy['final_rules']['id'],
                "nama_bayi" => $params['nama_bayi'],
                "jenis_kelamin" => $params['jenis_kelamin'],
                "berat_badan" => $params['berat_badan'],
                "usia" => $params['usia'],
                "tinggi_badan" => $params['tinggi_badan'],
                "z_score_bbu" => $hitungZcore[0]["z_score"],
                "z_score_tbu" => $hitungZcore[1]["z_score"],
                "z_score_bbtb" => $hitungZcore[2]["z_score"],
                "fuzzy_bbu" => $hitungFuzzy["BBU"],
                "fuzzy_tbu" => $hitungFuzzy["TBU"],
                "fuzzy_bbtb" => $hitungFuzzy["BBTB"],
                "kondisi_anak" => $ruleFuzzy,
                "kesimpulan" => $normalizedResponse,
            ]);

            return $this->successResponse($kalkulatorFuzzy, 'Data fuzzy berhasil dihitung.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal menghitung data fuzzy', 500);
        }
    }
}
