<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChangeNotificaction extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
                    ->subject('Acabas de cambiar tu contraseña')
                    ->greeting('Hola '.$notifiable->name.',')
                    ->line('Este es un mensaje automático de notificación para informarte que tu contraseña fue cambiada recientemente.')
                    ->line('Si no cambiaste tu contraseña, escríbenos a <a href="mailto:drones@motorplan-ecu.com">drones@motorplan-ecu.com<a>.')
                    ->line('Solo un recordatorio:')
                    ->line('<ul><li>Nunca compartas tu contraseña con nadie.</li><li>Crea contraseñas que sean difíciles de adivinar y no use información personal. Asegúrate de incluir letras mayúsculas y minúsculas, números y símbolos.</li><li>Usa diferentes contraseñas para cada una de sus cuentas en línea.</li></ul>');
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
