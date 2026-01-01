<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * 🆕 AGREGAR ESTE MÉTODO NUEVO
     * Mostrar el perfil del usuario (Vista de solo lectura)
     */
    public function show(): View
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // 🆕 AGREGAR ESTO: Actualizar datos específicos según rol
        
        // Si es paciente, actualizar edad y sexo
        if ($user->role === 'paciente' && $user->patient) {
            if ($request->has('edad')) {
                $user->patient->update([
                    'edad' => $request->edad,
                    'sexo' => $request->sexo,
                ]);
            }
        }

        // Si es médico, actualizar especialidad
        if ($user->role === 'medico' && $user->doctor) {
            if ($request->has('especialidad')) {
                $user->doctor->update([
                    'especialidad' => $request->especialidad,
                ]);
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}