<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // 1. AGREGADO: Relación con la tabla users
            // Esto corrige el error "Unknown column 'user_id'"
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // 2. AGREGADO: Columnas específicas del paciente que tu SQL intenta guardar
            $table->integer('edad');
            $table->string('sexo'); // Ej: 'Femenino', 'Masculino'

            // 3. ELIMINADO: 'name' y 'email'
            // Generalmente estos datos ya están en la tabla 'users', así que no los duplicamos aquí
            // para mantener la base de datos normalizada.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
