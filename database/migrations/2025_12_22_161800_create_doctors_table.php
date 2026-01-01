<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            
            // 1. AGREGADO: La columna vital para relacionar con la tabla users
            $table->foreignId('user_id')
                  ->constrained('users') // Esto asegura que el ID exista en la tabla users
                  ->onDelete('cascade'); // Si borras el usuario, se borra el doctor

            // 2. CAMBIADO: De 'specialty' a 'especialidad' para coincidir con tu error SQL
            // y quitamos 'name' porque el nombre ya suele estar en la tabla 'users'
            $table->string('especialidad')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};