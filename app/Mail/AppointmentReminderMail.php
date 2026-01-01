<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $hoursUntil;

    /**
     * Create a new message instance.
     */
    public function __construct(Appointment $appointment, $hoursUntil = 24)
    {
        $this->appointment = $appointment;
        $this->hoursUntil = $hoursUntil;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⏰ Recordatorio de Cita Médica - MedicCitas',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}