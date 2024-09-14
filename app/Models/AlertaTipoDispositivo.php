<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaTipoDispositivo extends Model
{
    use HasFactory;

    protected $fillable=[
        'alerta_id',
        'tipo_dispositivo_id',
    ];


    // Relación con Alerta
    public function alerta()
    {
        return $this->belongsTo(Alerta::class);
    }

    // Relación con TipoDispositivo
    public function tipoDispositivo()
    {
        return $this->belongsTo(TipoDispositivo::class);
    }
    
    
}
