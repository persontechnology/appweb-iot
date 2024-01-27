<?php

namespace App\Http\Controllers;

use Bluerhinos\phpMQTT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestMqttController extends Controller
{
    public function index()  {
        
        $host   = "broker.mqttdashboard.com"; 
        $port     = 1883;
        $username = "datos";
        $password = "1234567";
        $message = [
            "type"=> "ns-api", 
            "method"=> "GET", 
            "url"=> "/api/urdevices",
            "body"=> [
            ]
        ];

        $mqtt = new phpMQTT ($host, $port, "ClientID".rand());

        if ($mqtt->connect(true,NULL,$username,$password)) {
            $mqtt->publish("milesight/request",json_encode($message), 0);
            $mqtt->close();
            return $message;
          }else{
            return 'erroR';
          }

    }

    public function index2()  {
        
        $host   = "broker.mqttdashboard.com"; 
        $port     = 1883;
        $username = "datos";
        $password = "1234567";
        $message = [
            "type"=> "ns-api", 
            "method"=> "GET", 
            "url"=> "/api/urdevices",
            "body"=> [
            ]
        ];

        $mqtt = new phpMQTT ($host, $port, "ClientID".rand());

        if(!$mqtt->connect(true, NULL, $username, $password)) {
            exit(1);
        }
        
        $mqtt->debug = true;
        
        $topics['milesight/response'] = array('qos' => 0, 'function' => function($topic, $msg){
            Log::info("Received $msg from $topic topic");
        });
        $mqtt->subscribe($topics, 0);
        
        while($mqtt->proc()) {
        
        }
        
        $mqtt->close();
    }
 
    
}
