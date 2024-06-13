<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificarDispositivoEvento implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dispositivo;

    public function __construct($dispositivo)
    {
        $this->dispositivo=$dispositivo;

        // error_log($this->dispositivo);
    }


    public function broadcastOn()
    {
        return new Channel('canal-notificar-dispositivo');
    }
}
