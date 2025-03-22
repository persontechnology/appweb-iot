<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'configuration_id',
        'sensor',          // 'distancia', 'gps', 'button'
        'condition_type',  // 'rango', 'evento', 'estado'
        'min_value',       // Min valor para rangos
        'max_value',       // Max valor para rangos
        'event',           // Evento asociado a la condición
        'alert'            // Si debe generar alerta (booleano)
    ];

    /**
     * Relación inversa con Configuration
     */
    public function configuration()
    {
        return $this->belongsTo(Configuration::class);
    }

    /**
     * Método estático para facilitar la creación de reglas de configuración.
     */
    public static function createRule($configurationId, $sensor, $conditionType, $minValue = null, $maxValue = null, $event = null, $alert = false)
    {
        return self::create([
            'configuration_id' => $configurationId,
            'sensor' => $sensor,
            'condition_type' => $conditionType,
            'min_value' => $minValue,
            'max_value' => $maxValue,
            'event' => $event,
            'alert' => $alert
        ]);
    }
}
