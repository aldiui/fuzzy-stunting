<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Stunting",
 *     description="Dokumentasi API untuk Sistem Stunting"
 * )
 */
class AuthController extends Controller
{
    use ApiResponder;

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login pengguna",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login berhasil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email tidak ditemukan atau password salah."),
     *             @OA\Property(property="data", type="null")
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
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal melakukan login"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:3|max:50',
                'password' => 'required|min:8|max:20',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Kesalahan validasi', 422);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return $this->errorResponse(null, 'Email tidak ditemukan', 401);
            }

            if (!$token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->errorResponse(null, 'Password salah', 401);
            }

            return $this->successResponse(compact('token'), 'Login berhasil.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal melakukan login', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register pengguna",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama","email","password","konfirmasi_password"},
     *             @OA\Property(property="nama", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="konfirmasi_password", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Register berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login berhasil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
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
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal melakukan login"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama' => 'required|min:3|max:50',
                'email' => 'required|email|min:3|max:50|unique:users',
                'password' => 'required|min:8|max:20',
                'konfirmasi_password' => 'required|min:8|max:20|same:password',
            ]);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 'Kesalahan validasi', 422);
            }

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if (!$token = auth('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
                return $this->errorResponse(null, 'Password salah', 401);
            }

            return $this->successResponse(compact('token'), 'Register berhasil.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal melakukan register', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Logout pengguna",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logout berhasil."),
     *             @OA\Property(property="data", type="null")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Gagal melakukan logout"),
     *             @OA\Property(property="data", type="null")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            auth('api')->logout();
            return $this->successResponse(null, 'Logout berhasil.', 200);
        } catch (Exception $e) {
            return $this->errorResponse(null, 'Gagal melakukan logout', 500);
        }
    }
}