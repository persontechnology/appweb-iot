<?php

namespace App\Notifications;

use App\Models\Alerta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EnviarEmailUsuariosAsignadosLectura extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $lectura;
    protected $alerta;


    public function __construct($lectura,$alerta)
    {
        $this->lectura = $lectura;
        $this->alerta = $alerta;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $alertaTipo = Alerta::getAlertaTipoByAlertaId($this->alerta->id);

        return (new MailMessage)
                    ->line('Mensaje')
                    ->line($alertaTipo->mensaje)
                    ->line('Datos.')
                    ->line($this->lectura->data)
                    // ->action('Notification Action', url('/'))
                    ->line('Gracias por usar nuestra aplicación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
