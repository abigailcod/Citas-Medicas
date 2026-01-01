<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Lista de Pacientes
     */
    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('patients.index', compact('patients'));
    }

    /**
     * Formulario de Creación
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Guardar Paciente + Usuario (Transacción)
     */
    public function store(Request $request)
    {
        $request->validate([
            // Datos de Usuario
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            // Datos de Paciente
            'edad' => 'required|integer|min:0|max:120',
            'sexo' => 'required|string|in:Masculino,Femenino,Otro',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Crear Usuario
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'paciente', // Rol importante
                ]);

                // 2. Crear Ficha de Paciente vinculada
                Patient::create([
                    'user_id' => $user->id,
                    'edad' => $request->edad,
                    'sexo' => $request->sexo,
                ]);
            });

            return redirect()->route('patients.index')->with('success', 'Paciente registrado correctamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Formulario de Edición
     */
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    /**
     * Actualizar Paciente + Usuario
     */
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->user_id,
            'edad' => 'required|integer|min:0',
            'sexo' => 'required|string',
        ]);

        // Actualizar Usuario
        $patient->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $patient->user->update(['password' => Hash::make($request->password)]);
        }

        // Actualizar Paciente
        $patient->update([
            'edad' => $request->edad,
            'sexo' => $request->sexo,
        ]);

        return redirect()->route('patients.index')->with('success', 'Paciente actualizado.');
    }

    /**
     * Eliminar (Cascada)
     */
    public function destroy(Patient $patient)
    {
        $patient->user->delete(); // Borra usuario y paciente por cascada
        return redirect()->route('patients.index')->with('success', 'Paciente eliminado.');
    }
}