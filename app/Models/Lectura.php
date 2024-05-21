<?php

namespace App\Models;

use App\Events\LecturaGuardadoEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lectura extends Model
{
    use HasFactory;


    // protected $hidden = ['dev_eui'];

    protected $casts = [
        'birthday'  => 'date:Y-m-d',
        'created_at' => 'datetime:Y-m-d H:00',
        'dev_eui' => 'string',
    ];
    
    // una lectura pertenece a  un dispositivo
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dev_eui','dev_eui');
    }
    

    // esta funcion es importante, ya que de aqui ingresamos a la lectura pa accer al dev_eui
    public function xId($id)  {
        return $this->find($id);
    }

    // buscar dispositivo por dev_eui
    public function buscarDispositivoDevEui($dev_eui) {
        return Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
    }

    public function ubicacionDispositivoPorDevEui($dev_eui) {
        $dispositivo= Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
        return [$dispositivo->latitude??'',$dispositivo->longitude ?? ''];
    }



    // una lectura pertenece a  una alerta
    public function alerta()
    {
        return $this->belongsTo(Alerta::class);
    }
    
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

    // disparar evento para notificacion en tiempo real
    // protected $dispatchesEvents = [
    //     'created' => LecturaGuardadoEvent::class,
    // ];
}
