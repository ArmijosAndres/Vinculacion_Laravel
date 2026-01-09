<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    protected $fillable = [
        'id_rol',
        'cedula',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'password_hash',
        'estado',
        'fecha_aprobacion',
        'aprobado_por'
    ];

    protected $hidden = [
        'password_hash',
    ];

    // Hashear password automáticamente
    public function setPasswordHashAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }

    // Verificar password
    public function verificarPassword($password)
    {
        return Hash::check($password, $this->password_hash);
    }

    // Relación con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Relación con Socio
    public function socio()
    {
        return $this->hasOne(Socio::class, 'id_usuario', 'id_usuario');
    }
}