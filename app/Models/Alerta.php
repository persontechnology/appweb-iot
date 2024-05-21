<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable=[
        'nombre',
        'estado',
        'application_id',
        'puede_enviar_email'
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
     public function alertaUsers(){
        return $this->hasMany(AlertaUser::class, 'alerta_id', 'id');

    }


    // una alerta tiene varios tipos de alertas

    public function alertasTipos(): HasMany
    {
        return $this->hasMany(AlertaTipo::class);
    }


    // Define la relación uno a uno
    public function alertaTipo()
    {
        return $this->hasOne(AlertaTipo::class);
    }

    // Método para obtener alertaTipo por alerta_id
    public static function getAlertaTipoByAlertaId($alertaId)
    {
        $alerta = self::find($alertaId);
        return $alerta ? $alerta->alertaTipo : null;
    }
}
