<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validador de datos de registro
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'edad' => ['required', 'integer', 'min:1', 'max:120'],
            'sexo' => ['required', 'string', 'in:Masculino,Femenino,Otro'],
        ]);
    }

    /**
     * Crear un nuevo usuario + paciente después de la validación
     */
    protected function create(array $data)
    {
        // Usamos transacción para crear usuario y paciente juntos
        return DB::transaction(function () use ($data) {
            // 1. Crear el usuario
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'paciente', // Siempre será paciente al registrarse
            ]);

            // 2. Crear el registro de paciente vinculado
            Patient::create([
                'user_id' => $user->id,
                'edad' => $data['edad'],
                'sexo' => $data['sexo'],
            ]);

            return $user;
        });
    }
}