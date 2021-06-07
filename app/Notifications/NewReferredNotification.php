<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReferredNotification extends Notification
{
    use Queueable;

    protected $quotation;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($quotation)
    {
        $this->quotation = $quotation;
        $this->url = env('SISCO_URL').'quotations/'.$this->quotation->id;
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
                    ->subject('Nuevo prospecto referido')
                    ->greeting('Hola '.$notifiable->name.',')
                    ->line('Este es un mensaje automático de notificación para informarle que el referidor <strong>'.$this->quotation->drone->getFullName().'</strong> le ha referido a <strong>'.$this->quotation->customer->getFullName().'</strong> como prospecto, quien está interesado en <strong>'.$this->quotation->plan->product->name.'</strong>.')
                    ->action('Ver oportunidad comercial', $this->url);
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
            'title' => 'Nuevo prospecto referido',
            'text' => $this->quotation->customer->getFullName().' fue referido por '.$this->quotation->drone->getFullName().'.',
            'link' => $this->url,
            'icon' => 'mdi mdi-near-me',
            'color' => 'bg-info'
        ];
    }
}
