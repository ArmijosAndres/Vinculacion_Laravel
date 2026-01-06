<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;

class JWTService
{
    private $key;

    public function __construct()
    {
        $this->key = env('JWT_SECRET');
    }

    public function crearToken($data)
    {
        $payload = [
            'iss' => "laravel-api",
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addHours(12)->timestamp,
            'data' => $data
        ];

        return JWT::encode($payload, $this->key, 'HS256');
    }

    public function validarToken($token)
    {
        return JWT::decode($token, new Key($this->key, 'HS256'));
    }
}
