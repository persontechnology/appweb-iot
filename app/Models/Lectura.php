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
        'data' => 'array',
    ];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    

    // paso 1
    // esta funcion es importante, ya que de aqui ingresamos a la lectura pa accer al dev_eui
    // public function xId($id)  {
    //     return $this->find($id);
    // }

    // paso 2
    public function dipositivoXlecturaId($id) {
        $lect= $this->find($id);
        $dis=$lect->buscarDispositivoDevEui($lect->dev_eui);
        return $dis;
    }

    // paso3
    // una lectura pertenece a  un dispositivo
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class, 'dev_eui','dev_eui');
    }

    // buscar dispositivo por dev_eui
    public function buscarDispositivoDevEui($dev_eui) {
        return Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->selectRaw("encode(dev_eui, 'hex') as dev_eui_hex, *")->first();
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
    

    // una lectura tiene un tenant
    public function tenant()  {
        return $this->belongsTo(Tenant::class,'tenant_id');
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
