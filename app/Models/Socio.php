<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    protected $table = 'socios';
    protected $primaryKey = 'id_socio';
    
    protected $fillable = [
        'id_usuario',
        'numero_socio',
        'fecha_ingreso',
        'fecha_baja',
        'motivo_baja',
        'tipo_membresia',
        'cuota_mensual',
        'observaciones'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_socio', 'id_socio');
    }
}