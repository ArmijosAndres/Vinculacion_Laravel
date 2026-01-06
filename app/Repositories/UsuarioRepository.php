<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;  

class UsuarioRepository
{
    public function acceder($usuario, $password)
    {
        return DB::selectOne("select * from usuarios where usuario = ? and password = ?", [
            $usuario,
            $password
        ]);
    }
}
