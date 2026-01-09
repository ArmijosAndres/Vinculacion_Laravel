<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuditoriaController,
    ConfiguracionSistemaController,
    PagoController,
    RequisitoMembresiaController,
    RolController,
    SocioController,
    SolicitudRegistroController,
    UsuarioController,
    LoginController
};

// Rutas públicas
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// Rutas protegidas con JWT
Route::middleware('jwt')->group(function () {
    // Auth
    Route::get('/me', [LoginController::class, 'me']);
    
    // Recursos
    Route::apiResource('roles', RolController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::apiResource('socios', SocioController::class);
    Route::apiResource('pagos', PagoController::class);
    Route::apiResource('solicitudes-registro', SolicitudRegistroController::class);
    Route::apiResource('requisitos-membresia', RequisitoMembresiaController::class);
    Route::apiResource('auditorias', AuditoriaController::class);
    Route::apiResource('configuracion-sistema', ConfiguracionSistemaController::class);
});
