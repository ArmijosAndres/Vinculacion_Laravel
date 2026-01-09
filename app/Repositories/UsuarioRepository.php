<?php

namespace App\Repositories;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioRepository
{
    public function acceder($login, $password)
    {
        // Buscar por cédula O por email
        $usuario = Usuario::where('cedula', $login)
                          ->orWhere('email', $login)
                          ->where('estado', 'activo')
                          ->first();
        
        if ($usuario && Hash::check($password, $usuario->password_hash)) {
            return $usuario;
        }
        
        return null;
    }

    public function buscarPorId($id)
    {
        return Usuario::with('rol', 'socio')->find($id);
    }
}