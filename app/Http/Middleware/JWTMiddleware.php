<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            $request->attributes->set('jwt_user', (array) $decoded);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token inválido',
                'msg' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
//el metodo del bearer token obtiene el token del encabezado de autorizacion. Si no hay token, devuelve un error 401. Si el token es valido, decodifica el token y agrega la informacion del usuario a los atributos de la solicitud para su uso posterior.