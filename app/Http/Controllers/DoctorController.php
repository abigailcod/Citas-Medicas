<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Muestra la lista de doctores.
     */
    public function index()
    {
        // Traemos los doctores con sus datos de usuario para mostrar en la tabla
        $doctors = Doctor::with('user')->get();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Guarda el nuevo doctor y su usuario asociado de forma segura.
     */
    public function store(Request $request)
    {
        // 1. Validación de datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Email único
            'password' => 'required|min:6',
            'especialidad' => 'required|string|max:255',
            'numero_colegiatura' => 'nullable|string|max:50', // ✅ NUEVO
        ]);

        try {
            // 2. Transacción: Garantiza que se creen AMBOS o NINGUNO
            DB::transaction(function () use ($request) {
                // A. Crear Usuario (Login)
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => 'medico', // Rol obligatorio para login
                ]);

                // B. Crear Perfil de Doctor vinculado al ID del usuario
                Doctor::create([
                    'user_id' => $user->id,
                    'especialidad' => $request->especialidad,
                    'numero_colegiatura' => $request->numero_colegiatura, // ✅ NUEVO
                ]);
            });

            return redirect()->route('doctors.index')->with('success', 'Médico registrado exitosamente.');
            
        } catch (\Exception $e) {
            // Si falla la BD, mostramos error sin romper la página
            return back()->withErrors(['error' => 'Error al guardar: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Doctor $doctor)
    {
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Actualiza los datos del usuario y del doctor.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Validamos email único ignorando el ID actual para que no falle al guardar el mismo correo
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'especialidad' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'numero_colegiatura' => 'nullable|string|max:50', // ✅ NUEVO
        ]);

        // Actualizar tabla 'users'
        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        // Si escribió nueva contraseña, la actualizamos
        if ($request->filled('password')) {
            $doctor->user->update(['password' => Hash::make($request->password)]);
        }

        // Actualizar tabla 'doctors'
        $doctor->update([
            'especialidad' => $request->especialidad,
            'numero_colegiatura' => $request->numero_colegiatura, // ✅ NUEVO
        ]);

        return redirect()->route('doctors.index')->with('success', 'Médico actualizado correctamente.');
    }

    /**
     * Elimina al doctor y su usuario.
     */
    public function destroy(Doctor $doctor)
    {
        // Borramos al USUARIO. Por la cascada en la BD, el doctor se borra solo.
        $doctor->user->delete(); 
        
        return redirect()->route('doctors.index')->with('success', 'Médico eliminado.');
    }
}