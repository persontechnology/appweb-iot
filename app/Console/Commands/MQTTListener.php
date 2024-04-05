<?php

namespace App\Console\Commands;

use App\Events\LoRaWANGatewayEvent;
use Bluerhinos\phpMQTT;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PhpMqtt\Client\MqttClient;

class MQTTListener extends Command
{
    
    protected $signature = 'mqtt:listen';

    
    protected $description = 'Escuche mensajes MQTT';

    
    public function handle()
    {
        
        $server = config('services.mqtt.host');
        $port = config('services.mqtt.port')   ;
        $username = config('services.mqtt.username');
        $password = config('services.mqtt.password');
        $clientId = 'mqtt-subscriber-'.Str::random(10);
        
        pcntl_async_signals(true);

       try {
            $mqtt = new MqttClient($server, $port, $clientId);
            pcntl_signal(SIGINT, function (int $signal, $info) use ($mqtt) {
                $mqtt->interrupt();
            });
            $mqtt->connect();
            $mqtt->subscribe('milesight/uplink', function ($topic, $message, $retained, $matchedWildcards) {
                // resultado
                error_log($message);
                // {"obj":{"applicationID":"1","applicationName":"cloud","data":"/y4B","devEUI":"24e124535d387374","deviceName":"SW","fCnt":83,"fPort":85,"rxInfo":[{"altitude":0,"latitude":0,"loRaSNR":13.8,"longitude":0,"mac":"24e124fffef86c30","name":"Local Gateway","rssi":-52,"time":"2024-02-08T17:57:55.265399Z"}],"time":"2024-02-08T17:57:55.265399Z","txInfo":{"adr":true,"codeRate":"4/5","dataRate":{"bandwidth":125,"modulation":"LORA","spreadFactor":7},"frequency":917400000}},"press":"short"}
                $resultado_array = json_decode($message, true);
                $datos_obj = $resultado_array['obj'];
                $rx_info = $datos_obj['rxInfo'][0];
                error_log($rx_info['mac']);

                event(new LoRaWANGatewayEvent([
                    'mac'=>$rx_info['mac'],
                    'devEUI'=>$datos_obj['devEUI'],
                    'deviceName'=>$datos_obj['deviceName'],
                    
                ]));
                
            }, 0);
            $mqtt->loop(true);
            $mqtt->disconnect();
       } catch (\PhpMqtt\Client\Exceptions\MqttClientException $th) {
            error_log($th->getMessage());
       }

    }
}
