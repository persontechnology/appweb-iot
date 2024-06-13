<?php

namespace App\Models;

use App\Events\LecturaGuardadoEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Lectura extends Model
{
    use HasFactory;

    protected $fillable=[
        'gateway_dev_eui'
    ];

    // protected $hidden = ['dev_eui'];
    protected $casts = [
        'birthday'  => 'date:Y-m-d',
        'created_at' => 'datetime',
        'dev_eui' => 'string',
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    
    // una lectura pertenece a  un dispositivo
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dev_eui','dev_eui');
    }
    public function dipositivoXlecturaId($id) {
        $lect= $this->find($id);
        $dis=$lect->buscarDispositivoDevEui($lect->dev_eui);
        return $dis;
    }

    // esta funcion es importante, ya que de aqui ingresamos a la lectura pa accer al dev_eui
    public function xId($id)  {
        return $this->find($id);
    }

    // buscar dispositivo por dev_eui
    public function buscarDispositivoDevEui($dev_eui) {
        return Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")->first();
    }

    public function ubicacionDispositivoPorDevEui($dev_eui) {
        $dispositivo= Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
        return [$dispositivo->latitude??'',$dispositivo->longitude ?? ''];
    }

    // buscar dispositivo por dev_eui fabian
    static function buscarDispositivoUsoDevEui($dev_eui) {
        return Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
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


    // Método para obtener lecturas basadas en el tenant del usuario
    public static function obtenerLecturasPorTenant($tenantId)
    {
        return self::whereHas('alerta', function(Builder $query) use ($tenantId) {
            $query->whereHas('application', function (Builder $query) use ($tenantId) {
                $query->whereHas('tenant', function (Builder $query) use ($tenantId) {
                    $query->where('id', $tenantId);
                });
            });
        })->latest();
    }
    // disparar evento para notificacion en tiempo real
    // protected $dispatchesEvents = [
    //     'created' => LecturaGuardadoEvent::class,
    // ];

    // una lectura tiene un tenant
    public function tenant()  {
        return $this->belongsTo(Tenant::class);
    }

    // Función para obtener el total de lecturas con estado false.
    public static function totalLecturasEstadoFalse()
    {
        $tenant_id = Auth::user()->tenant_id;
        
        return self::where('tenant_id', $tenant_id)
                   ->where('estado', false)
                   ->count();
    }


}
