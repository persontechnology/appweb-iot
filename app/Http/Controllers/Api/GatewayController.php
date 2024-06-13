<?php

namespace App\Http\Controllers\Api;

use App\Events\EnviarDispositivoEvent;
use App\Events\LecturaGuardadoEvent;
use App\Events\NotificarDispositivoEvento;
use App\Http\Controllers\Controller;
use App\Models\Dispositivo;
use App\Models\Horario;
use App\Models\Lectura;
use App\Models\PuntosLocalizacion;
use App\Notifications\EnviarEmailUsuariosAsignadosLectura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class GatewayController extends Controller
{
    public function sensor(Request $request)
    {
        // error_log($request);
        try {
            // Obtener la información del dispositivo y del objeto de la solicitud
            $deviceInfo = $request->json('deviceInfo');
            $object = $request->json('object');
            
            // Verificar si se recibieron los datos del dispositivo y del objeto
            if (!$deviceInfo || !$object) {
                throw new \Exception('NO EXISTE DEVICE INFO O OBJECT');
            }
            
            // Obtener el ID de la aplicación del dispositivo
            $applicationId = $deviceInfo['applicationId'];
            
            // Verificar el horario para la aplicación actual
            $horario = $this->verificarHorario($applicationId);
            
            // Verificar si existe un horario para la aplicación actual
            if (!$horario) {
                throw new \Exception('NO EXISTE HORARIO PARA LA APLICACIÓN ' . $applicationId);
            }
            


            // consultar si el dispositivo tiene y tracking, si tiene tracking guadar PuntoLocalizacion.
            // caso contrario generamos lectura para los otros dispositivos
            $dev_eui=$deviceInfo['devEui'];
            $dispositivoTracking=Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();

            if ($dispositivoTracking && $dispositivoTracking->use_tracking) {
                    $puntosLOcalizacion=$this->crearPuntosLocalizacion($dev_eui,$object);
            } else {
                
                // Verificar si las alertas se activan con los datos del objeto
                if ($this->verificarAlertas($object, $horario->alerta)) {
                    // Crear una nueva lectura
                    $lectura = $this->crearLectura($deviceInfo['devEui'], $horario->alerta_id, $request);
                    
                    
                    $lecturaCreada=Lectura::find($lectura->id);
                    // Enviar correos electrónicos a los usuarios asignados a la alerta si es necesario
                   try {
                        if ($lectura->alerta->puede_enviar_email) {
                            $this->enviarEmailUsuariosAsignadosLectura($lectura);
                        }
                   } catch (\Throwable $th) {
                        error_log('NO ENVIADO EMAIL '.$th->getMessage());
                   }
                    
                    $dispositivo=$lectura->buscarDispositivoDevEui($deviceInfo['devEui']);
                                        
                    // Emitir un evento para notificar la lectura guardada en tiempo real
                    // error_log('###########################################');

                    $data = array(
                        'dev_eui_hex'=>$dispositivo->dev_eui_hex,
                        'last_seen_at'=>$dispositivo->last_seen_at,
                        'name'=>$dispositivo->name,
                        'battery_level'=>$dispositivo->battery_level,
                        'use_tracking'=>$dispositivo->use_tracking,
                        'created_at'=>$lectura->created_at,
                        'id_lectura'=>$lectura->id,
                        'ver_lectura_url'=>route('lecturas.show',$lectura->id),
                        'description'=>$dispositivo->description,
                        'tenant_id'=>$lectura->tenant_id
                    );

                    

                    event(new NotificarDispositivoEvento($data));
                    // error_log('###########################################');
                    // event(new LecturaGuardadoEvent($data));
                }


            }
            
        
            
        } catch (\Exception $th) {
            // Capturar cualquier excepción y registrarla en los registros de errores
            error_log('OCURRIO UN ERROR: ' . $th->getMessage());
        }
    }


    // crear putos de localizacion para el gps o dispositivoa que tengan atributo tracking
    public function crearPuntosLocalizacion($dev_eui,$object) {


        if (isset($object['latitude']) && isset($object['longitude'])) {
            $puntosLocalizacion= new PuntosLocalizacion();
            $puntosLocalizacion->estado=1;
            $puntosLocalizacion->tipo='LOCALIZACION';
            $puntosLocalizacion->dato='TEST';
            $puntosLocalizacion->error='';
            $puntosLocalizacion->latitud=$object['latitude'];
            $puntosLocalizacion->longitud=$object['longitude'];
            $puntosLocalizacion->exactitud='1';
            $puntosLocalizacion->dev_eui=$dev_eui;
            $puntosLocalizacion->save();
            return $puntosLocalizacion;
        }
        return null;

        
    }


    private function verificarAlertas($object, $alerta)
    {
        
        
        // Recorrer todos los tipos de alerta asociados a la alerta actual
        foreach ($alerta->alertasTipos as $alertaTipo) {
            // Verificar si alguna condición coincide con los datos del objeto
            if ($this->verificarCondicion($object, $alertaTipo)) {
                return true;
            }
        }
        return false;
    }

    private function verificarCondicion($object, $alertaTipo)
    {
        
        // Obtener el parámetro, la condición y el valor de la alertaTipo actual
        $parametro = $alertaTipo->parametro;
        $condicion = $alertaTipo->condicion;
        $valor = $alertaTipo->valor;
        
        if (!isset($object[$parametro])) {
            return false;
        }

        // Convertir el valor del objeto a numérico si es posible
        $valorObjeto = is_numeric($object[$parametro]) ? (float) $object[$parametro] : $object[$parametro];
        
        // if($parametro=='distance' && is_numeric($valorObjeto)){
        //     $valorObjeto=$valorObjeto/1000;
        // }
        
        
        // Verificar si la condición coincide con los datos del objeto
        switch ($condicion) {
            case '=':
                return $valorObjeto == $valor;
            case '!=':
                return $valorObjeto != $valor;
            case '>':
                return $valorObjeto > $valor;
            case '<':
                return $valorObjeto < $valor;
            default:
                return false;
        }
    }

    public function enviarEmailUsuariosAsignadosLectura($lectura)
    {
        // error_log('entro a enviar email');
        // Enviar correos electrónicos a los usuarios asignados a la alerta asociada a la lectura
        try {
            foreach ($lectura->alerta->alertaUsers as $alertaUser) {
                error_log($alertaUser);
                Queue::push(function ($job) use ($alertaUser, $lectura) {
                    error_log('aqui esta la vrg');
                    $alertaUser->user->notify(new EnviarEmailUsuariosAsignadosLectura($lectura));
                    $job->delete();
                });
            }
        } catch (\Throwable $th) {
            error_log('EMAIL ERROR '.$th->getMessage());
        }
    }

    public function crearLectura($dev_eui, $alerta_id, $request)
    {
        // Crear una nueva instancia de Lectura y guardarla en la base de datos
        
        try {
            $lectura = new Lectura();
            $lectura->dev_eui =$dev_eui;
            $lectura->alerta_id = $alerta_id;
            $lectura->data = json_decode($request->getContent(), true);
            $lectura->tenant_id=$lectura->alerta->application->tenant_id;
            $lectura->save();
            error_log('LECTURA CREADO');
            return $lectura;
        } catch (\Throwable $th) {
            error_log('LECTURA NO CREADO '.$th->getMessage());
        }
    }

    public function verificarHorario($applicationId)
    {
        // Obtener el número del día de la semana actual y la hora actual
        $numeroDiaHoy = date('N');
        $horaActual = Carbon::now()->format('H:i:s');
        
        // Buscar el horario activo para el día actual y la aplicación proporcionada
        return Horario::where('numero_dia', $numeroDiaHoy)
            ->where('estado', true)
            ->whereTime('hora_apertura', '<=', $horaActual)
            ->whereTime('hora_cierre', '>=', $horaActual)
            ->whereHas('alerta', function ($query) use ($applicationId) {
                $query->where('estado', true)
                    ->where('application_id', $applicationId);
            })
            ->first();
    }
}
