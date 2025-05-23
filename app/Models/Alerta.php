<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'estado',
        'application_id',
        'puede_enviar_email',
    ];

    protected static function booted()
    {
        static::created(function ($alerta) {
            $alerta->crearHorarios();
        });
    }


    // crear horario automaticamente
    public function crearHorarios()
    {
        $dias = [
            'Lunes' => 1,
            'Martes' => 2,
            'Miércoles' => 3,
            'Jueves' => 4,
            'Viernes' => 5,
            'Sábado' => 6,
            'Domingo' => 7
        ];
        $existentes = Horario::where('alerta_id', $this->id)->pluck('dia')->toArray();
        foreach ($dias as $dia => $numero) {
            if (!in_array($dia, $existentes)) {
                Horario::create([
                    'dia' => $dia,
                    'numero_dia' => $numero,
                    'alerta_id' => $this->id
                ]);
            }
        }
    }

    // alerta -> horarios
    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }

    // alerta -> lecturas
    public function lecturas()
    {
        return $this->hasMany(Lectura::class);
    }

    // application <- alerta
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    // usuarios asignados en alertas para enviar correos
    public function alertaUsers()
    {
        return $this->hasMany(AlertaUser::class, 'alerta_id', 'id');
    }

    // Relación muchos a muchos con TipoDispositivo

    public function deviceprofiles(): BelongsToMany
    {
        return $this->belongsToMany(DeviceProfile::class, 'alerta_device_profile', 'alerta_id', 'device_profile_id');
    }
}
