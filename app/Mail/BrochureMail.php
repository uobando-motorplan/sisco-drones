<?php

namespace App\Mail;

use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BrochureMail extends Mailable
{
    use Queueable, SerializesModels;

    public $brochure;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($brochure)
    {
        $this->brochure = $brochure;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@motorplan-ecu.com', 'CasaPlan-MotorPlan')
            ->subject('Tu catálogo personalizado '.$this->brochure->quotation->plan->product->name)
            ->view('emails.brochure');
    }
}
