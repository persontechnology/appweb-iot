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
        error_log($request);
         // Accediendo a deviceInfo
         $deviceInfo = $request->json('deviceInfo');
         // Accediendo a object
         $object = $request->json('object');
         // Verificando si deviceInfo y object existen en la solicitud
         if ($deviceInfo && $object) {
             // Hacer lo que necesites con $deviceInfo y $object
             $applicationId=$deviceInfo['applicationId'];
             $verificarHorario=$this->verificarHorario($applicationId);
             // verificar horario
             if($verificarHorario){
                // crear lectura
                try {
                    // decodificar el deveui del dispositivo
                    $deviceIdBinary = DB::selectOne("SELECT decode(?, 'hex') as binary_value", [$deviceInfo['devEui']])->binary_value;

                    // crear objeto

                    

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
         }else{
            error_log('NO EXISTE DEVICE');
         }
    }


    public function crearLectura($dev_eui,$alerta_id) {
        $lectura = new Lectura();
        $lectura->dev_eui = $dev_eui;
        $lectura->alerta_id = $alerta_id;
        $lectura->save();
        return $lectura;
    }

    public function verificarHorario($applicationId) {
         // Obtener el número del día de la semana actual (1 para lunes, 7 para domingo)
         $numeroDiaHoy = date('N');
        
         // Obtener la hora actual en formato 'HH:MM:SS'
         $horaActual = Carbon::now()->format('H:i:s');
        
         // Realizar la consulta para buscar los horarios activos para el día de hoy
         $horario = Horario::where('numero_dia', $numeroDiaHoy)
                 ->where('estado', true)
                 ->whereTime('hora_apertura', '<=', $horaActual)
                 ->whereTime('hora_cierre', '>=', $horaActual)
                 ->whereHas('alerta', function($query) use ($applicationId) {
                     $query->where('estado', true)
                     ->where('application_id', $applicationId);
                 })
                 ->with('alerta')
                 ->first();
         return $horario;
    }




    // esto es lo que recibimos del device
    // public function informacionDevice() {
    //     "deduplicationId": "f37950b7-7628-4701-b387-513b676fbb60",
    //     "time": "2024-04-24T11:33:35.335+00:00",
    //     "deviceInfo": {
    //         "tenantId": "b387e3b3-21fe-46c8-a08b-ee1f6729112f",
    //         "tenantName": "PERSON TECHNOLOGY",
    //         "applicationId": "268a028f-3ed8-4f79-a8b2-780edfb0bd2b",
    //         "applicationName": "ap 1",
    //         "deviceProfileId": "8d106d1f-c0f8-4cb9-9335-e012c6d8b450",
    //         "deviceProfileName": "PULSO",
    //         "deviceName": "PULSO edit",
    //         "devEui": "24e124535d387374",
    //         "deviceClassEnabled": "CLASS_A",
    //         "tags": {}
    //     },
    //     "devAddr": "00754d8f",
    //     "adr": true,
    //     "dr": 5,
    //     "fCnt": 94,
    //     "fPort": 85,
    //     "confirmed": true,
    //     "data": "/y4B",
    //     "object": {
    //         "press": "short"
    //     },
    //     "rxInfo": [
    //         {
    //             "gatewayId": "24e124fffef86c30",
    //             "uplinkId": 29621,
    //             "gwTime": "2024-04-24T11:33:35.335256+00:00",
    //             "nsTime": "2024-04-24T11:33:35.370301478+00:00",
    //             "timeSinceGpsEpoch": "1397993633.335s",
    //             "rssi": -49,
    //             "snr": 13.8,
    //             "location": {
    //                 "latitude": -0.2746571512146767,
    //                 "longitude": -78.54125976562501
    //             },
    //             "context": "L6VMQQ==",
    //             "metadata": {
    //                 "region_common_name": "AU915",
    //                 "region_config_id": "au915_0"
    //             },
    //             "crcStatus": "CRC_OK"
    //         }
    //     ],
    //     "txInfo": {
    //         "frequency": 916800000,
    //         "modulation": {
    //             "lora": {
    //                 "bandwidth": 125000,
    //                 "spreadingFactor": 7,
    //                 "codeRate": "CR_4_5"
    //             }
    //         }
    //     }
    // }

}
