<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Dispositivo extends Model
{
    use HasFactory;

    protected $table='device';
    protected $primaryKey = 'dev_eui';
    protected $keyType = 'string';
    public $incrementing = false;
    
    // Conversión de tipos para los atributos del modelo
    protected $casts = [
        'dev_eui' => 'string',  // Convierte 'gateway_id' a tipo 'string'
        'join_eui' => 'string',
        'dev_addr'=>'string',
        'secondary_dev_addr'=>'string',
        'device_session'=>'string',
        'last_seen_at' => 'datetime:Y-m-d H:i',
    ];

    

    public function setDevEuiAttribute($value)
    {
        // Decodifica el valor hexadecimal a binario
        $gatewayIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;
        // Asigna el valor binario al atributo 'dev_eui'
        $this->attributes['dev_eui'] = $gatewayIdBinary;
    }
    public function setJoinEuiAttribute($value)
    {
        // Decodifica el valor hexadecimal a binario
        $gatewayIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;
        // Asigna el valor binario al atributo 'join_eui'
        $this->attributes['join_eui'] = $gatewayIdBinary;
    }
    
    public function getDevEuiAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

    public function getJoinEuiAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

     // un dispositivo, o device tiene varias lecturas
     public function lecturas()
     {
         return $this->hasMany(Lectura::class, 'dev_eui', 'dev_eui');
     }


    // un dispositivo pertenece a una aplicacion
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    // un dispsitivo pertenece a un device profile
    public function deviceprofile(): BelongsTo
    {
        return $this->belongsTo(DeviceProfile::class, 'device_profile_id');
    }
    
}
