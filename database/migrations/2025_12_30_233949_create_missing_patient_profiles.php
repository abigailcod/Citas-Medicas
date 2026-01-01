<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Obtener usuarios con rol paciente que NO tienen perfil
        $users = DB::table('users')
            ->where('role', 'paciente')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('patients')
                    ->whereColumn('patients.user_id', 'users.id');
            })
            ->get();

        // Crear perfil de paciente para cada usuario sin perfil
        foreach ($users as $user) {
            DB::table('patients')->insert([
                'user_id' => $user->id,
                'edad' => null,
                'sexo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // No hacer nada
    }
};