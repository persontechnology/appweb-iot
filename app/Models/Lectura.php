<?php

namespace App\Models;

use App\Events\LecturaGuardadoEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    use HasFactory;



    // una lectura pertenece a  un dispositivo
    public function device()
    {
        return $this->belongsTo(Dispositivo::class, 'dev_eui');

    }


    // una lectura pertenece a  una alerta
    public function alerta()
    {
        return $this->belongsTo(Alerta::class);
    }

    // disparar evento para notificacion en tiempo real
    // protected $dispatchesEvents = [
    //     'created' => LecturaGuardadoEvent::class,
    // ];
}
