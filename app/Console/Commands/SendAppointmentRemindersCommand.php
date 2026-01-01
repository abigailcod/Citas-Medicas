<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendAppointmentReminders;

class SendAppointmentRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviar recordatorios de citas programadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Enviando recordatorios...');
        
        SendAppointmentReminders::dispatch();
        
        $this->info('✅ Recordatorios enviados correctamente');
        
        return Command::SUCCESS;
    }
}