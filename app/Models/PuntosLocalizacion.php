<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PuntosLocalizacion extends Model
{
    use HasFactory;
    protected $table = 'puntos_localizaciones';
    

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:00',
        'dev_eui' => 'string',
    ];

    public function setDevEuiAttribute($value)
    {
        // Decodifica el valor hexadecimal a binario
        $gatewayIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$value])->binary_value;
        // Asigna el valor binario al atributo 'dev_eui'
        $this->attributes['dev_eui'] = $gatewayIdBinary;
    }

    public function getDevEuiAttribute($value)
    {
        // Convierte el valor binario a hexadecimal
        return bin2hex(stream_get_contents($value));
    }

}
