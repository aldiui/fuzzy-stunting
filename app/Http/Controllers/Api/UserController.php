<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Mengambil detail pengguna yang terautentikasi",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data pengguna",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengguna berhasil ditemukan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Gagal mengambil data pengguna",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal mendapatkan data pengguna"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $user = auth('api')->user();
            return $this->successResponse($user, 'Data pengguna berhasil ditemukan.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal mendapatkan data pengguna', 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/user",
     *     summary="Memperbarui detail pengguna yang terautentikasi",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "nama"},
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="password", type="string", nullable=true, example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil memperbarui data pengguna",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengguna berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-09-01T00:00:00Z")
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
     *         description="Gagal memperbarui data pengguna",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal memperbarui data pengguna"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function update(Request $request)
    {
        try {
            $user = auth('api')->user();

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:3|max:50|unique:users,email,' . $user->id,
                'nama' => 'required|min:3|max:50',
                'password' => 'nullable|min:8|max:20',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Kesalahan validasi', 422);
            }

            $user->update([
                'email' => $request->email,
                'name' => $request->nama,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            return $this->successResponse($user, 'Data pengguna berhasil diperbarui.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal memperbarui data pengguna', 500);
        }
    }
}