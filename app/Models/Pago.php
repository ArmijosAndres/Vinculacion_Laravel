<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    
    protected $fillable = [
        'id_socio',
        'mes',
        'anio',
        'monto',
        'fecha_pago',
        'metodo_pago',
        'numero_comprobante',
        'comprobante_url',
        'estado_pago',
        'observaciones',
        'registrado_por',
        'aprobado_por',
        'fecha_aprobacion'
    ];

    public function socio()
    {
        return $this->belongsTo(Socio::class, 'id_socio', 'id_socio');
    }
}