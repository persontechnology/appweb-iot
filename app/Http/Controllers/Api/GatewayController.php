<?php

namespace App\Http\Controllers\Api;

use App\Events\LecturaGuardadoEvent;
use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Lectura;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{

    public function list()  {
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjaGlycHN0YWNrIiwiaXNzIjoiY2hpcnBzdGFjayIsInN1YiI6ImFhNGZkNjJmLTc4Y2ItNGM2Yy1iOGI4LWI2NGU5NGIyNTYwNCIsInR5cCI6ImtleSJ9.2GUE7KMWbD8iYuVC0o0bWNU4mxjH3c4gieY_I29cFJ8'
            ])->get('http://192.168.1.54:8090/api/gateways?limit=10&offset=2');
            $data = $response->json();
           return $data;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function info()  {

        $response = Http::withHeaders([
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJjaGlycHN0YWNrIiwiaXNzIjoiY2hpcnBzdGFjayIsInN1YiI6ImFhNGZkNjJmLTc4Y2ItNGM2Yy1iOGI4LWI2NGU5NGIyNTYwNCIsInR5cCI6ImtleSJ9.2GUE7KMWbD8iYuVC0o0bWNU4mxjH3c4gieY_I29cFJ8',
                'Accept' => 'application/json',

        ])->get('http://192.168.1.54:8090/api/gateways/24e124fffef802cc/state');

        // Obtener el cuerpo de la respuesta
        $data = $response->json();

       return $data;

            
    }


    public function sensor(Request $request) {
         // Accediendo a deviceInfo
         $deviceInfo = $request->json('deviceInfo');
         // Accediendo a object
         $object = $request->json('object');
         // Verificando si deviceInfo y object existen en la solicitud
         if ($deviceInfo && $object) {
             // Hacer lo que necesites con $deviceInfo y $object
             $applicationId=$deviceInfo['applicationId'];
             $deviceProfileId=$deviceInfo['deviceProfileId'];
             $verificarHorario=$this->verificarHorario($applicationId,$deviceProfileId);
             // verificar horario
             if($verificarHorario){
                // crear lectura
                try {
                    $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$deviceInfo['devEui']])->binary_value;
                    $lectura=$this->crearLectura($deviceIdBinary,$verificarHorario->alerta->id);
                    // crear notificaicon en tiempo real en segundo plano..
                    error_log('LECTURA CREADO');
                    event(new LecturaGuardadoEvent('PERFECTO FUNCIONO NOTIFICACION EN TEIMPO REAL'));

                    // ..............
                } catch (\Throwable $th) {
                    error_log('OCURRIO UN ERROR AL GUARDAR LECTURA '.$th->getMessage());    
                }
             }else{
                error_log('NO EXISTE HORARIO');
             }
         }
    }


    public function crearLectura($dev_eui,$alerta_id) {
        $lectura = new Lectura();
        $lectura->dev_eui = $dev_eui;
        $lectura->alerta_id = $alerta_id;
        $lectura->save();
        return $lectura;
    }

    public function verificarHorario($applicationId,$deviceProfileId) {
         // Obtener el número del día de la semana actual (1 para lunes, 7 para domingo)
         $numeroDiaHoy = date('N');
        
         // Obtener la hora actual en formato 'HH:MM:SS'
         $horaActual = Carbon::now()->format('H:i:s');
        
         // Realizar la consulta para buscar los horarios activos para el día de hoy
         $horario = Horario::where('numero_dia', $numeroDiaHoy)
                 ->where('numero_dia', $numeroDiaHoy)
                 ->where('estado', true)
                 ->whereTime('hora_apertura', '<=', $horaActual)
                 ->whereTime('hora_cierre', '>=', $horaActual)
                 ->whereHas('alerta', function($query) use ($applicationId, $deviceProfileId) {
                     $query->where('estado', true)
                     ->where('application_id', $applicationId)
                     ->where('device_profile_id', $deviceProfileId);
                 })
                 ->with('alerta')
                 ->first();
         return $horario;
    }


}
