<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Gateway extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'gateway';

    // Nombre de la columna que actúa como clave primaria
    protected $primaryKey = 'gateway_id';

    // Tipo de dato de la clave primaria
    protected $keyType = 'string';

    // Indica si la clave primaria es autoincremental
    public $incrementing = false;

    // Conversión de tipos para los atributos del modelo
    protected $casts = [
        'gateway_id' => 'string',  // Convierte 'gateway_id' a tipo 'string'
    ];

    /**
     * Mutador para el atributo 'gateway_id'.
     *
     * Convierte un valor hexadecimal a su representación binaria antes de almacenarlo.
     *
     * @param string $value Valor hexadecimal del 'gateway_id'
     */
    public function setGatewayIdAttribute($value)
    {
        // Decodifica el valor hexadecimal a binario
        $gatewayIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;

        // Asigna el valor binario al atributo 'gateway_id'
        $this->attributes['gateway_id'] = $gatewayIdBinary;
    }

    /**
     * Accesor para el atributo 'gateway_id'.
     *
     * Convierte el valor binario a su representación hexadecimal antes de recuperarlo.
     *
     * @param mixed $value Valor binario del 'gateway_id'
     * @return string Representación hexadecimal del 'gateway_id'
     */
    public function getGatewayIdAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }
    
   


    // un gateway esta en un tenant
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }


}
