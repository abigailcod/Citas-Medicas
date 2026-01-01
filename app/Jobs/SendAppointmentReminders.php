<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Mail\AppointmentReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendAppointmentReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Recordatorio 24 horas antes
        $appointments24h = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'confirmed')
            ->whereBetween('appointment_date', [
                now()->addHours(23)->addMinutes(50),
                now()->addHours(24)->addMinutes(10)
            ])
            ->get();

        foreach ($appointments24h as $appointment) {
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentReminderMail($appointment, 24));
        }

        // Recordatorio 2 horas antes
        $appointments2h = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'confirmed')
            ->whereBetween('appointment_date', [
                now()->addHours(1)->addMinutes(50),
                now()->addHours(2)->addMinutes(10)
            ])
            ->get();

        foreach ($appointments2h as $appointment) {
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentReminderMail($appointment, 2));
        }
    }
}