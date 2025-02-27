<?php

namespace App\Http\Controllers\Api;

use App\Events\EnviarDispositivoEvent;
use App\Events\LecturaGuardadoEvent;
use App\Events\NotificarDispositivoEvento;
use App\Http\Controllers\Controller;
use App\Models\Alerta;
use App\Models\Application;
use App\Models\Dispositivo;
use App\Models\Horario;
use App\Models\Lectura;
use App\Models\PuntosLocalizacion;
use App\Models\SensorData;
use App\Notifications\EnviarEmailUsuariosAsignadosLectura;
use App\Notifications\Lecturas\EnviarCorreoDistancia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

use function Laravel\Prompts\error;

class GatewayController extends Controller
{
    public function sensor(Request $request)
    {
        //crear datos del sensor 

        error_log($request);

        $this->guardarDatosSensor($request);
        // error_log($request);
        try {
            // Obtener la información del dispositivo y del objeto de la solicitud
            $deviceInfo = $request->json('deviceInfo');
            $object = $request->json('object');

            // Verificar si se recibieron los datos del dispositivo y del objeto
            if (!$deviceInfo || !$object) {
                throw new \Exception('NO EXISTE DEVICE INFO O OBJECT');
                Log::info('NO EXISTE DEVICE INFO O OBJECT');
            }

            // Obtener el ID de la aplicación del dispositivo
            $applicationId = $deviceInfo['applicationId'];

            $dev_eui = $deviceInfo['devEui'];

            $dispositivo = Dispositivo::with(['tipoDispositivo'])->where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();


            if (!isset($dispositivo)) {
                throw new \Exception('NO EXISTE DISPOSITIVO ' . $dev_eui);
            }

            // Verificar el horario para la aplicación actual
            $alertas = $this->verificarHorario($applicationId, $dispositivo->tipo_dispositivo_id) ?? [];

            // Verificar si existe un horario para la aplicación actual
            if (count($alertas) < 1) {

                throw new \Exception('NO EXISTE HORARIO PARA LA APLICACIÓN ' . $applicationId);

                return "no existe horario";
            }

            Log::info('dispositivo', [$dispositivo]);
            $tipoDiposotivo = $dispositivo->tipoDispositivo;
            if (isset($tipoDiposotivo)) {
                Log::info("INGRESO COMPARACION DE TIPO");
                if ($tipoDiposotivo->nombre == "Environment Monitoring Sensor") {
                    Log::info("COMPARACION DE TIPO DISTANCIA");
                    if (isset($object['distance'])) {
                        $alerta = $alertas->first();
                        Log::info("datos guuardar", [$deviceInfo['devEui'], $alerta['id'], $request]);
                        $lectura = $this->crearLectura($deviceInfo['devEui'], $alerta['id'], $request);
                    } else {
                        Log::error('EN EL DISPOSITIVO TIPO DISTANCA MAL CONFIGURADO', [$dispositivo]);
                    }
                }
                if (isset($object['battery'])) {
                    $dispositivo->battery_level = $object['battery'];
                    $dispositivo->save();
                }
            } else {
                Log::error('EL DISPOSITIVO NO TIENE TIPO', [$dispositivo]);
            }
            return "biennn";
            // Verificar si las alertas se activan con los datos del objeto
            //$dispositivoTracking=Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
            if (isset($object['motion_status']) && $object['motion_status'] == "moving") {
                $puntosLOcalizacion = $this->crearPuntosLocalizacion($dev_eui, $object, $request);
            } else if (isset($object['distance'])) {
                // Verificar si las alertas se activan con los datos del objeto
                // if ($this->verificarAlertas($object, $horario->alerta)) {
                //     $lectura = $this->crearLectura($deviceInfo['devEui'], $horario->alerta_id, $request);
                //     // Enviar correos electrónicos a los usuarios asignados a la alerta si es necesario

                //     // $dispositivoTracking
                //     $dispositivo = Dispositivo::where('dev_eui', DB::raw("decode('$dev_eui', 'hex')"))->first();
                //     $aplicacion = Application::with('configuraciones')->find($applicationId);
                //     if ($lectura->alerta->puede_enviar_email && $dispositivo && $aplicacion) {
                //         $configuraciones = collect($aplicacion->configuraciones ?? []);
                //         $porcentajeLlenado = $this->calcularPorcentajeLlenado($object['distance'], $aplicacion->distance);
                //         $rangoLlenado = $this->determinarRangoLlenado($porcentajeLlenado, $configuraciones);

                //         if (isset($rangoLlenado['notification']) && $rangoLlenado['notification']) {

                //             $this->enviarEmailUsuariosAsignadosLecturaDistancia($lectura, $rangoLlenado, $porcentajeLlenado);
                //         }
                //     }

                //     $dispositivo = $lectura->buscarDispositivoDevEui($deviceInfo['devEui']);
                //     $this->sentReaTime($dispositivo, $lectura);
                // }
            } else if (isset($object['press'])) {
                // if ($this->verificarAlertas($object, $horario->alerta)) {
                //     $lectura = $this->crearLectura($deviceInfo['devEui'], $horario->alerta_id, $request);
                //     $dispositivo = $lectura->buscarDispositivoDevEui($deviceInfo['devEui']);
                //     $this->sentReaTime($dispositivo, $lectura);
                // }
            }
            Log::info('fin');
            return "okerr";
        } catch (\Exception $th) {
            return  $th->getMessage();
            // Capturar cualquier excepción y registrarla en los registros de errores
            error_log('OCURRIO UN ERROR: ' . $th->getMessage());
        }
    }
    //
    function obtenerHoraio($alerta)
    {

        if (isset($alerta)) {
            $horarios = collect($alerta['horarios']) ?? [];
            if (isset($horarios) && count($horarios) > 0) {
                return $horarios->first();
            }
        }
        return null;
    }

    //ENVIAR DATOS A LAS NOTIFICACIONES
    function sentReaTime($dispositivo, $lectura)
    {
        $data = array(
            'dev_eui_hex' => $dispositivo->dev_eui_hex,
            'last_seen_at' => $dispositivo->last_seen_at,
            'name' => $dispositivo->name,
            'battery_level' => $dispositivo->battery_level,
            'use_tracking' => $dispositivo->use_tracking,
            'created_at' => $lectura->created_at,
            'id_lectura' => $lectura->id,
            'ver_lectura_url' => route('lecturas.show', $lectura->id),
            'description' => $dispositivo->description,
            'tenant_id' => $lectura->tenant_id
        );

        event(new NotificarDispositivoEvento($data));
    }

    //Calculos para la distancia
    function calcularPorcentajeLlenado($nivelActual, $nivelMaximo)
    {
        // Calcular el nivel invertido
        $nivelInvertido = $nivelMaximo - $nivelActual;

        // Calcular el porcentaje de llenado
        $porcentajeLlenado = ($nivelInvertido / $nivelMaximo) * 100;

        return $porcentajeLlenado;
    }

    function determinarRangoLlenado($porcentajeLlenado, $niveles)
    {
        $rango = null;

        // Recorrer los niveles para determinar el rango
        foreach ($niveles as $nivel) {
            if ($porcentajeLlenado <= $nivel['valor']) {
                $rango = $nivel;
                break;
            }
        }

        return $rango;
    }
    // crear putos de localizacion para el gps o dispositivoa que tengan atributo tracking
    public function crearPuntosLocalizacion($dev_eui, $object, $request)
    {
        try {
            if (isset($object['latitude']) && isset($object['longitude'])) {
                $data = json_decode($request->getContent(), true);
                $puntosLocalizacion = new PuntosLocalizacion();
                $puntosLocalizacion->estado = 1;
                $puntosLocalizacion->data = $data;
                $puntosLocalizacion->tipo = 'LOCALIZACION';
                $puntosLocalizacion->dato = 'TEST';
                $puntosLocalizacion->error = '';
                $validationResult = $this->validateCoordinates($object['latitude'], $object['longitude']);
                $puntosLocalizacion->latitud = $object['latitude'];
                $puntosLocalizacion->longitud = $object['longitude'];
                if ($validationResult['estado']) {
                    $puntosLocalizacion->exactitud = '1';
                    $puntosLocalizacion->dev_eui = $dev_eui;
                    Log::info('PUNTO DE UBICACION GUARDADO', [json_decode($request->getContent(), true)]);
                    $puntosLocalizacion->save();
                    return $puntosLocalizacion;
                }
                return null;
            }
        } catch (\Exception $ex) {
            Log::error('PUNTO DE UBICACION NO GUARDADO' . $ex);
            return null;
        }
    }

    private function validateCoordinates($latitude, $longitude)
    {
        $data = [
            'estado' => true,
            'error' => ''
        ];

        if (!isset($latitude) || !isset($longitude)) {
            return [
                'estado' => false,
                'error' => 'Las coordenadas no son numéricas: ' . json_encode($latitude) . ', ' . json_encode($longitude),
            ];
        }

        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return [
                'estado' => false,
                'error' => 'Las coordenadas están fuera de los límites válidos: ' . json_encode($latitude) . ', ' . json_encode($longitude)
            ];
        }

        if ($latitude < -5.0 || $latitude > 1.7 || $longitude < -81.1 || $longitude > -75.2) {
            return [
                'estado' => false,
                'error' => 'Las coordenadas no pertenecen a Ecuador: ' . json_encode($latitude) . ', ' . json_encode($longitude)
            ];
        }

        return $data;
    }


    private function guardarDatosSensor($request)
    {

        $sensorData = new SensorData();
        $sensorData->fecha = Carbon::now();
        $sensorData->data = json_decode($request->getContent(), true);;
        $sensorData->save();
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
                Log::info($alertaUser);
                Queue::push(function ($job) use ($alertaUser, $lectura) {
                    $alertaUser->user->notify(new EnviarEmailUsuariosAsignadosLectura($lectura, $alertaUser->alerta));
                    $job->delete();
                });
            }
        } catch (\Throwable $th) {
            Log::error('EMAIL ERROR ' . $th->getMessage());
        }
    }
    public function enviarEmailUsuariosAsignadosLecturaDistancia($lectura, $rangoLlenado, $porcentajeLlenado)
    {
        // error_log('entro a enviar email');
        // Enviar correos electrónicos a los usuarios asignados a la alerta asociada a la lectura
        try {
            foreach ($lectura->alerta->alertaUsers as $alertaUser) {
                Log::info($alertaUser);
                Queue::push(function ($job) use ($alertaUser, $rangoLlenado, $porcentajeLlenado,) {
                    $alertaUser->user->notify(new EnviarCorreoDistancia($rangoLlenado, $porcentajeLlenado, $alertaUser->user));
                    $job->delete();
                });
            }
        } catch (\Throwable $th) {
            Log::error('EMAIL ERROR ' . $th->getMessage());
        }
    }
    public function crearLectura($dev_eui, $alerta_id, $request)
    {
        // Crear una nueva instancia de Lectura y guardarla en la base de datos

        try {
            $lectura = new Lectura();
            $lectura->dev_eui = $dev_eui;
            $lectura->alerta_id = $alerta_id;
            $lectura->data = json_decode($request->getContent(), true);
            $lectura->tenant_id = $lectura->alerta->application->tenant_id;
            $lectura->save();
            Log::info('LECTURA CREADO');
            return $lectura;
        } catch (\Throwable $th) {
            Log::error('LECTURA NO CREADO ', [$th->getMessage()]);
        }
    }

    public function verificarHorario($applicationId, $tipoDispositivoId)
    {
        // Obtener el número del día de la semana actual y la hora actual
        $numeroDiaHoy = date('N');
        $horaActual = Carbon::now()->format('H:i:s');

        // Buscar el horario activo para el día actual y la aplicación proporcionada
        return Alerta::where('estado', true)
            ->with(['horarios' => function ($horarios) {
                $horarios->where('estado', true);
            }])
            ->whereHas('horarios', function ($query) use ($numeroDiaHoy, $horaActual) {
                $query->where('numero_dia', $numeroDiaHoy)
                    ->where('estado', true)
                    ->whereTime('hora_apertura', '<=', $horaActual)
                    ->whereTime('hora_cierre', '>=', $horaActual);
            })
            ->whereHas('tipoDispositivos', function ($query) use ($tipoDispositivoId) {
                $query->where('tipo_dispositivo_id', $tipoDispositivoId);
            })
            ->where('application_id', $applicationId)
            ->get();
    }
}
