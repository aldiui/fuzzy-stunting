<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\GroqService;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    use ApiResponder;

    private $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    /**
     * @OA\Get(
     *     path="/api/chat",
     *     summary="Ambil riwayat obrolan pengguna",
     *     tags={"Chat"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan riwayat obrolan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API Chat"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="message", type="string", example="Hello"),
     *                     @OA\Property(property="role", type="string", example="user"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mendapatkan riwayat obrolan",
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
            $chats = $user->chats()->orderBy('created_at', 'desc')->get();
            return $this->successResponse($chats, 'API Chat', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/chat",
     *     summary="Kirim pesan obrolan",
     *     tags={"Chat"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Apa itu stunting?")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pesan obrolan berhasil dikirim",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API Chat"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="message", type="string", example="Stunting adalah kondisi...")
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
     *         description="Gagal mengirim pesan obrolan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkankan data"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pesan' => 'required|min:15',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Kesalahan validasi', 422);
            }

            $pesan = $request->pesan;
            $user = auth('api')->user();

            $user->chats()->create([
                'pesan' => $pesan,
                'role' => 'user',
            ]);

            $response = $this->groqService->getAssistantResponse($pesan);

            $chat = $user->chats()->create([
                'pesan' => $response['choices'][0]['message']['content'],
                'role' => 'assistant',
            ]);

            return $this->successResponse($chat, 'API Chat', 201);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }
}
