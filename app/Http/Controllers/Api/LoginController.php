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
        // Validación de entrada
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Datos incompletos',
                'errores' => $validator->errors()
            ], 422);
        }

        // Intentar acceder
        $registro = $this->usuarioRepo->acceder($request->usuario, $request->password);
        
        if ($registro != null) {
            $token = $jwt->crearToken([
                'usuario' => $request->usuario,
                'id' => $registro->id ?? null, // Incluye el ID si lo necesitas
                // Puedes agregar más datos: rol, permisos, etc.
            ]);

            return response()->json([
                'estado' => 'ok',
                'mensaje' => 'Login exitoso',
                'token' => $token,
                'usuario' => [
                    'id' => $registro->id,
                    'usuario' => $registro->usuario,
                    'nombre' => $registro->nombre ?? null,
                    // Otros datos que necesites en el frontend
                ]
            ], 200);
        }

        return response()->json([
            'estado' => 'error',
            'mensaje' => 'Credenciales incorrectas'
        ], 401);
    }

    public function logout(Request $request)
    {
        // Aquí podrías invalidar el token si tu JWTService lo permite
        return response()->json([
            'estado' => 'ok',
            'mensaje' => 'Sesión cerrada exitosamente'
        ], 200);
    }

    public function refresh(Request $request, JWTService $jwt)
    {
        // Opcionalmente, renovar el token
        $tokenActual = $request->bearerToken();
        
        if (!$tokenActual) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Token no proporcionado'
            ], 401);
        }

        try {
            $decodificado = $jwt->validarToken($tokenActual);
            $nuevoToken = $jwt->crearToken([
                'usuario' => $decodificado->usuario
            ]);

            return response()->json([
                'estado' => 'ok',
                'token' => $nuevoToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Token inválido'
            ], 401);
        }
    }
}