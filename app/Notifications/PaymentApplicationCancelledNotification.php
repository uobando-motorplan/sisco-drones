<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentApplicationCancelledNotification extends Notification
{
    use Queueable;

    protected $payment_application;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($payment_application)
    {
        $this->payment_application = $payment_application;
        $this->url = env('SISCO_URL').'payment-applications/'.$this->payment_application->id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
                    ->subject('Solicitud de pago anulada')
                    ->greeting('Hola '.$notifiable->name.',')
                    ->line('Este es un mensaje automático de notificación para informarle que la <strong>solicitud de pago #'.$this->payment_application->id.'</strong> fue anulada por el  referidor <strong>'.$this->payment_application->drone->getFullName().'</strong>.')
                    ->action('Consultar solicitud de pago #'.$this->payment_application->id, $this->url);
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
            'title' => 'Solicitud de pago anulada',
            'text' => 'El referidor '.$this->payment_application->drone->getFullName().' anuló la solicitud de pago #'.$this->payment_application->id.'.',
            'link' => $this->url,
            'icon' => 'mdi mdi-near-me',
            'color' => 'bg-info'
        ];
    }
}
