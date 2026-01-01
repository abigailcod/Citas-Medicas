<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $emailSubject;  // ← Cambiado de $subject
    public $emailMessage;  // ← Cambiado de $message

    public function __construct($appointment, $subject, $message)
    {
        $this->appointment = $appointment;
        $this->emailSubject = $subject;   // ← Cambiado  
        $this->emailMessage = $message;   // ← Cambiado
    }

    public function build()
    {
        return $this->subject($this->emailSubject)  // ← Cambiado
                    ->view('emails.appointment')
                    ->with([
                        'appointment' => $this->appointment,
                        'subject' => $this->emailSubject,
                        'emailMessage' => $this->emailMessage
                    ]);
    }
}