<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoDispositivo extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre'
    ];

     // Relación muchos a muchos con Alerta
     public function alertas()
     {
         return $this->belongsToMany(Alerta::class, 'alerta_tipo_dispositivos', 'tipo_dispositivo_id', 'alerta_id');
     }
}
