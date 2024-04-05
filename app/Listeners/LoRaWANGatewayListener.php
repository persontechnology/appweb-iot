<?php

namespace App\Listeners;

use App\Events\GatewayDataUpdated;
use App\Events\LoRaWANGatewayEvent;
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoRaWANGatewayListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LoRaWANGatewayEvent $event): void
    {
        $data=$event->data;
        error_log($data);
        $gateway=Gateway::where('mac',$data['mac'])->first();
        if($gateway){
            $gateway->conectado='SI';
            $gateway->save();
            event(new GatewayDataUpdated($gateway));
        }
        
        


    }
}
