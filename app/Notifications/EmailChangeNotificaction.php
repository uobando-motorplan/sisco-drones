<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailChangeNotificaction extends Notification
{
    use Queueable;

    protected $new_email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($new_email)
    {
        $this->new_email = $new_email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Acabas de cambiar tu correo electrónico')
                    ->greeting('Hola '.$notifiable->name.',')
                    ->line('Este es un mensaje automático de notificación para informarte que tu correo electrónico fue cambiado recientemente.')
                    ->line('Tu nueva dirección de correo electrónico para iniciar sesión es <strong>'.$this->new_email.'<strong>.')
                    ->line('Si no cambiaste tu correo electrónico, escríbenos a <a href="mailto:drones@motorplan-ecu.com">drones@motorplan-ecu.com<a>.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
