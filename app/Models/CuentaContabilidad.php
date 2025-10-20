<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaContabilidad extends Model
{

     protected $table = 'cuentas_contables';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
        'naturaleza',
        'nivel',
        'padre_id',
        'activa',
        'descripcion'
    ];
}
