<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceKeys extends Model
{
    use HasFactory;
    protected $table='device_keys';
    protected $primaryKey = 'dev_eui';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'dev_eui' => 'string',
        'nwk_key' => 'string',
        'app_key' => 'string',
    ];

    public function getDevEuiAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

    public function getNwkKeyAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

    public function getAppKeyAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

    
}
