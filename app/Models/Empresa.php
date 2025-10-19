<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
     protected $table = 'empresas';

    protected $fillable = [
        'nit',
        'razon_social',
        'siglas',
        'nombre_representante_legal',
        'nombre_contador',
        'matricula_contador',
        'nombre_revisor_fiscal',
        'matricula_revisor_fiscal',
        'url_logo',
        'estado',
    ];
}
