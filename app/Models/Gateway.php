<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre',
        'modelo',
        'fcc_id',
        'direccion_ip',
        'usuario',
        'password',
        'imei',
        'mac',
        'foto',
        'estado',
        'conectado',
        'lat',
        'lng',
        'descripcion',
        'categoria_gateway_id'
    ];
}
