<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;

class AppointmentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $message;
    public $userId;
    public $appointmentId;
    public $appointmentDate;

    /**
     * Constructor del evento
     */
    public function __construct($userId, $title, $message, $appointmentId = null, $appointmentDate = null)
    {
        $this->userId = $userId;
        $this->title = $title;
        $this->message = $message;
        $this->appointmentId = $appointmentId;
        $this->appointmentDate = $appointmentDate;
    }

    /**
     * Canal privado por usuario
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    /**
     * Nombre del evento en el frontend
     */
    public function broadcastAs(): string
    {
        return 'appointment.notification';
    }

    /**
     * Datos enviados al cliente
     */
    public function broadcastWith(): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'appointment_id' => $this->appointmentId,
            'appointment_date' => $this->appointmentDate,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}