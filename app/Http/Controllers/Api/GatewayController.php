<?php

namespace App\Http\Controllers\Api;

use App\Events\LecturaGuardadoEvent;
use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Lectura;
use App\Notifications\EnviarEmailUsuariosAsignadosLectura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

class GatewayController extends Controller
{
    public function sensor(Request $request)
    {
        error_log($request);
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
            
            // object{
            //     'distance':152,
            //     'press':'short'
            // }

            // Verificar si las alertas se activan con los datos del objeto
            if ($this->verificarAlertas($object, $horario->alerta)=='si') {
                // Crear una nueva lectura
                $lectura = $this->crearLectura($deviceInfo['devEui'], $horario->alerta_id, $object);
                
                // Enviar correos electrónicos a los usuarios asignados a la alerta si es necesario
                if ($lectura->alerta->puede_enviar_email) {
                    $this->enviarEmailUsuariosAsignadosLectura($lectura);
                }
                
                // Emitir un evento para notificar la lectura guardada en tiempo real
                event(new LecturaGuardadoEvent('PERFECTO FUNCIONO NOTIFICACION EN TEIMPO REAL'));
            }
        } catch (\Throwable $th) {
            // Capturar cualquier excepción y registrarla en los registros de errores
            error_log('OCURRIO UN ERROR: ' . $th->getMessage());
        }
    }

    private function verificarAlertas($object, $alerta)
    {
        // Recorrer todos los tipos de alerta asociados a la alerta actual
        foreach ($alerta->alertasTipos as $alertaTipo) {
            // Verificar si alguna condición coincide con los datos del objeto
            if ($this->verificarCondicion($object, $alertaTipo)) {
                return 'si';
            }
        }
        return 'no';
    }

    private function verificarCondicion($object, $alertaTipo)
    {
        // Obtener el parámetro, la condición y el valor de la alertaTipo actual
        $parametro = $alertaTipo->parametro;
        $condicion = $alertaTipo->condicion;
        $valor = $alertaTipo->valor;
        

        // Convertir el valor del objeto a numérico si es posible
        $valorObjeto = is_numeric($object[$parametro]) ? (float) $object[$parametro] : $object[$parametro];

        if($parametro==='distance' && is_numeric($valorObjeto)){
            $valorObjeto=$valorObjeto/1000;
        }
        
        
        // Verificar si la condición coincide con los datos del objeto
        switch ($condicion) {
            case '=':
                return isset($object[$parametro]) && $valorObjeto == $valor;
            case '!=':
                return isset($object[$parametro]) && $valorObjeto != $valor;
            case '>':
                return isset($object[$parametro]) && $valorObjeto > $valor;
            case '<':
                return isset($object[$parametro]) && $valorObjeto < $valor;
            default:
                return false;
        }
    }

    public function enviarEmailUsuariosAsignadosLectura($lectura)
    {
        // Enviar correos electrónicos a los usuarios asignados a la alerta asociada a la lectura
        foreach ($lectura->alerta->alertaUsers as $alertaUser) {
            Queue::push(function ($job) use ($alertaUser, $lectura) {
                $alertaUser->user->notify(new EnviarEmailUsuariosAsignadosLectura($lectura,$alertaUser->alerta));
                $job->delete();
            });
        }
    }

    public function crearLectura($dev_eui, $alerta_id, $object)
    {
        // Crear una nueva instancia de Lectura y guardarla en la base de datos
        
        $lectura = new Lectura();
        $lectura->dev_eui =$dev_eui;
        $lectura->alerta_id = $alerta_id;
        $lectura->data = json_encode($object);
        $lectura->save();
        return $lectura;
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
