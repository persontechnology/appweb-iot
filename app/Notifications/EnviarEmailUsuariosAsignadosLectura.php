<?php

namespace App\Notifications;

use App\Models\Alerta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use PDF;
class EnviarEmailUsuariosAsignadosLectura extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $lectura;
    


    public function __construct($lectura)
    {
        $this->lectura = $lectura;
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
        $lectura=$this->lectura;
        $alerta=$lectura->alerta;


        $headerHtml = view()->make('pdf.header')->render();
        $footerHtml = view()->make('pdf.footer')->render();
        $pdf = PDF::loadView('lecturas.pdf',['lectura'=>$lectura])
        ->setOption('header-html', $headerHtml)
        ->setOption('footer-html', $footerHtml);

        return (new MailMessage)
                ->subject($alerta->nombre)
                    ->line('ALERTA: '.$alerta->nombre)
                    ->line('APLICACIÓN: '.$alerta->application->name)
                    ->line('FECHA: '.$alerta->created_at)
                    ->line('DEV_EUI: '.$lectura->dev_eui)
                    ->line('INQUILINO: '.$lectura->tenant->name)
                    ->line('Gracias por usar nuestra aplicación!, adjunto encontrata archivo PDF, con toda la información')
                    ->attachData($pdf->output(),'Lectura-'.$lectura->id.'.pdf');
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
