<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define los comandos Artisan de la aplicación.
     */
    protected $commands = [
        // Aquí se registran automáticamente
    ];

    /**
     * Define la programación de comandos automáticos.
     */
    protected function schedule(Schedule $schedule)
    {
        // ✅ BACKUP DIARIO A LAS 2:00 AM
        $schedule->command('backup:database')
                 ->daily()
                 ->at('02:00');

        // 🔥 RECORDATORIOS DE CITAS - Cada hora
        $schedule->command('appointments:send-reminders')
                 ->hourly()
                 ->withoutOverlapping() // Evita que se ejecute si aún está corriendo
                 ->onSuccess(function () {
                     \Illuminate\Support\Facades\Log::info('✅ Recordatorios de citas enviados correctamente');
                 })
                 ->onFailure(function () {
                     \Illuminate\Support\Facades\Log::error('❌ Error al enviar recordatorios de citas');
                 });

        // 📊 OPCIONAL: Limpiar citas antiguas canceladas (cada semana)
        // $schedule->call(function () {
        //     \App\Models\Appointment::where('status', 'cancelled')
        //         ->where('appointment_date', '<', now()->subMonths(6))
        //         ->delete();
        // })->weekly()->sundays()->at('04:00');

        // 🔔 OPCIONAL: Recordatorio adicional 1 hora antes
        // $schedule->command('appointments:send-urgent-reminders')
        //          ->everyFifteenMinutes();
    }

    /**
     * Registrar los comandos para la aplicación.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}