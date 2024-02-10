<?php

namespace App\Listeners;

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
        $gateway=Gateway::where('mac',$data['mac'])->first();
        if($gateway){
            $gateway->conectado='NO';
            $gateway->save();
            error_log('GATEWAY SI');
        }
        
    }
}
