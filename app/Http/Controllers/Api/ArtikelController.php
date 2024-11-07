<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     *     path="/api/artikel",
     *     summary="Ambil daftar Artikel",
     *     tags={"Artikel"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan daftar Artikel",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API Artikel"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="title", type="string", example="Breaking Artikel"),
     *                         @OA\Property(property="content", type="string", example="This is the content of the Artikel."),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-01T12:00:00Z")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mendapatkan daftar Artikel",
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
            $Artikel = Artikel::where('status', 1)->orderBy('created_at', 'desc')->get();
            $Artikel = $Artikel->map(function ($Artikel) {
                $Artikel->created_at = $Artikel->created_at->format('l, j F Y');
                $Artikel->gambar = url('storage/' . $Artikel->gambar);
            });
            return $this->successResponse($Artikel, 'API Artikel', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/artikel/{id}",
     *     summary="Ambil detail Artikel",
     *     tags={"Artikel"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID Artikel",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mendapatkan detail Artikel",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API Artikel"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Breaking Artikel"),
     *                 @OA\Property(property="content", type="string", example="This is the content of the Artikel."),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-09-01T12:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mendapatkan detail Artikel",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkankan data"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Artikel tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Artikel tidak ditemukan"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $Artikel = Artikel::where('status', 1)->find($id);

            if (!$Artikel) {
                return $this->errorResponse(null, 'Artikel tidak ditemukan', 404);
            }

            return $this->successResponse($Artikel, 'API Artikel', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkankan data', 500);
        }
    }
}
