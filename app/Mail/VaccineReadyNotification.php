<?php

// app/Mail/VaccineReadyNotification.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VaccineReadyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.vaccine_ready')
            ->subject('Vaccine Dose Notification')
            ->with([
                'message' => $this->message,
            ]);
    }
}
