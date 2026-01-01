<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient; // ✅ AGREGAR
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // ✅ AGREGAR
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'edad' => ['required', 'integer', 'min:1', 'max:120'],
            'sexo' => ['required', 'in:M,F'],
        ]);
        
        // ✅ USAR TRANSACCIÓN PARA CREAR USUARIO Y PERFIL DE PACIENTE
        $user = null;
        
        DB::transaction(function () use ($request, &$user) {
            // Crear usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'paciente', // ✅ ASIGNAR ROL AUTOMÁTICAMENTE
            ]);

            // ✅ CREAR PERFIL DE PACIENTE AUTOMÁTICAMENTE
            Patient::create([
                'user_id' => $user->id,
                'edad' => $request->edad,
                'sexo' => $request->sexo,
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}