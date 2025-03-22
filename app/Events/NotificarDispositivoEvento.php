<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificarDispositivoEvento implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dispositivo;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $dispositivo
     * @return void
     */
    public function __construct($dispositivo)
    {
        $this->dispositivo = $dispositivo;

        // Log para verificar el contenido del dispositivo
        Log::info('NotificarDispositivoEvento', ['dispositivo' => $this->dispositivo]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Canal en el que se emitirá el evento
        return new Channel('canal-notificar-dispositivo');
    }

    /**
     * Get the name of the event.
     *
     * @return string
     */
    public function broadcastAs()
    {
        // Asegúrate de que este nombre coincida con lo que estás escuchando en el frontend
        return 'NotificarDispositivoEvento';
    }
}
