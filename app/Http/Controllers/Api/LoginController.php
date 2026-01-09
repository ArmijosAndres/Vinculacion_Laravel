<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\JWTService;
use App\Repositories\UsuarioRepository;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $usuarioRepo;

    public function __construct(UsuarioRepository $usuarioRepo)
    {
        $this->usuarioRepo = $usuarioRepo;
    }

    public function login(Request $request, JWTService $jwt)
    {
        // Aceptar 'email', 'cedula' o 'usuario' desde el frontend
        $loginField = $request->email ?? $request->cedula ?? $request->usuario;
        
        if (!$loginField) {
            return response()->json([
                'success' => false,
                'message' => 'Debe proporcionar email o cédula'
            ], 422);
        }

        $usuario = $this->usuarioRepo->acceder($loginField, $request->password);
        
        if ($usuario) {
            $usuario->load('rol', 'socio');
            
            // Mapear nombre_rol a lo que espera el frontend
            $rolNombre = strtolower($usuario->rol->nombre_rol ?? 'socio');
            // Convertir 'Secretaria' a 'secretario' para el frontend
            $roleMap = [
                'presidente' => 'presidente',
                'secretaria' => 'secretario',
                'tesorero' => 'tesorero',
                'socio' => 'socio',
                'usuario' => 'socio'
            ];
            $role = $roleMap[$rolNombre] ?? 'socio';
            
            $token = $jwt->crearToken([
                'id' => $usuario->id_usuario,
                'cedula' => $usuario->cedula,
                'email' => $usuario->email,
                'rol' => $role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'token' => $token,
                'user' => [
                    'id' => $usuario->id_usuario,
                    'cedula' => $usuario->cedula,
                    'email' => $usuario->email,
                    'nombre' => $usuario->nombres,
                    'apellidos' => $usuario->apellidos,
                    'role' => $role,
                    'numero_socio' => $usuario->socio->numero_socio ?? null
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas'
        ], 401);
    }

    public function me(Request $request, JWTService $jwt)
    {
        $token = $request->bearerToken();
        
        try {
            $decoded = $jwt->validarToken($token);
            $usuario = $this->usuarioRepo->buscarPorId($decoded->data->id);
            
            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }

            $rolNombre = strtolower($usuario->rol->nombre_rol ?? 'socio');
            $roleMap = [
                'presidente' => 'presidente',
                'secretaria' => 'secretario',
                'tesorero' => 'tesorero',
                'socio' => 'socio',
                'usuario' => 'socio'
            ];
            $role = $roleMap[$rolNombre] ?? 'socio';

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $usuario->id_usuario,
                    'cedula' => $usuario->cedula,
                    'email' => $usuario->email,
                    'nombre' => $usuario->nombres . ' ' . $usuario->apellidos,
                    'role' => $role,
                    'numero_socio' => $usuario->socio->numero_socio ?? null
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Token inválido'], 401);
        }
    }

    public function logout()
    {
        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada'
        ]);
    }
}