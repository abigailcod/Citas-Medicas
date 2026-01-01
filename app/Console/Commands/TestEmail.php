<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Enviar un email de prueba';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            Mail::raw('Este es un email de prueba desde Laravel', function($message) use ($email) {
                $message->to($email)
                       ->subject('Prueba Email - Sistema de Citas');
            });
            
            $this->info("✅ Email enviado correctamente a: {$email}");
            $this->info("Revisa tu bandeja de entrada (y spam si no lo ves)");
        } catch (\Exception $e) {
            $this->error("❌ Error al enviar email: " . $e->getMessage());
        }
    }
}