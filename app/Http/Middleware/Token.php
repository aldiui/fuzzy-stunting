<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponder;
use Closure;
use Illuminate\Support\Facades\App;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class Token
{
    use ApiResponder;

    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('Authorization')) {
            $token = $request->bearerToken();

            try {
                $user = JWTAuth::setToken($token)->authenticate();

                if (!$user || !$user->id) {
                    return $this->errorResponse(null, 'Sesi Anda telah berakhir. Silahkan login kembali.', 401);
                }

                $request->merge(['user' => $user]);

            } catch (JWTException $e) {
                return $this->errorResponse(null, 'Sesi Anda telah berakhir. Silahkan login kembali.', 401);
            } catch (\Exception $e) {
                return $this->errorResponse(null, 'Terjadi kesalahan. Silahkan coba lagi.', 500);
            }
        } else {
            return $this->errorResponse(null, 'Token tidak ditemukan', 401);
        }

        return $next($request);
    }
}