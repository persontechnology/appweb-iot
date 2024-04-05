<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Alerta extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre',
        'estado',
        'application_id',
        'device_profile_id',
    ];
    

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    // una alerta tiene varias lecturas.
    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }
}
