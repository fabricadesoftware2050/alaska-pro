<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table = 'facturas';

    protected $fillable = [
        'uuid',
        'dni_cliente',
        'cliente',
        'contrato',
        'periodo',
        'generada_por',
        'fecha_generada',
        'fecha_limite_pago',
        'valor',
        'tipo',
        'estado',
    ];

}
