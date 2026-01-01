<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Events\AppointmentUpdated;
use App\Events\AppointmentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 
use Ladumor\OneSignal\OneSignal;
use Carbon\Carbon;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    // ✅ F12: BÚSQUEDA DE CITAS IMPLEMENTADA
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Appointment::with(['patient.user', 'doctor.user']);

        // Filtros según rol
        if ($user->role === 'paciente') {
            $query->where('patient_id', $user->patient->id);
        } elseif ($user->role === 'medico') {
            $query->where('doctor_id', $user->doctor->id);
        }

        // ✅ FILTROS DE BÚSQUEDA (F12)
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('patient_id') && $user->role === 'admin') {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        
        // Para los selectores de búsqueda
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();

        return view('appointments.index', compact('appointments', 'patients', 'doctors'));
    }

    public function create()
    {
        $user = Auth::user();

        // Validación: Solo pacientes y admin pueden crear citas
        if ($user->role === 'medico') {
            return redirect()->route('appointments.index')
                ->with('warning', 'Los médicos no pueden crear citas. Solo pueden aceptar o rechazar solicitudes.');
        }

        $doctors = Doctor::with('user')->get();
        $patients = ($user->role === 'admin') ? Patient::with('user')->get() : [];
        
        return view('appointments.create', compact('patients', 'doctors'));
    }

    // ✅ F11: ALERTAS DE DISPONIBILIDAD IMPLEMENTADAS
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de rol
        if ($user->role === 'medico') {
            return redirect()->route('appointments.index')
                ->with('warning', 'Los médicos no pueden crear citas.');
        }

        // Forzar IDs según rol
        if ($user->role === 'paciente') {
            $request->merge(['patient_id' => $user->patient->id]);
        }

        // Validación
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:now',
            'motivo'           => 'nullable|string|max:500',
        ]);

        // ✅ F11: VALIDAR DISPONIBILIDAD HORARIA
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            $doctor = Doctor::with('user')->find($request->doctor_id);
            return back()
                ->withInput()
                ->with('warning', '⚠️ El Dr. ' . $doctor->user->name . ' ya tiene una cita en ese horario. Por favor, selecciona otra fecha/hora.');
        }

        $appointment = Appointment::create([
            'patient_id'       => $request->patient_id,
            'doctor_id'        => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'status'           => 'pending',
            'motivo'           => $request->motivo,
        ]);

        event(new AppointmentUpdated($appointment));

        // Notificación al doctor
        $doctor = Doctor::with('user')->find($request->doctor_id);
        $patient = Patient::with('user')->find($request->patient_id);
        
        $this->sendNotification(
            $doctor->user_id,
            "Nueva Solicitud de Cita",
            "El paciente {$patient->user->name} ha solicitado una cita para el " . 
            Carbon::parse($request->appointment_date)->format('d/m/Y H:i')
        );

        // 📧 NUEVO: Enviar email al doctor
        $this->sendEmail(
            $doctor->user->email,
            $appointment,
            "Nueva Solicitud de Cita",
            "Tiene una nueva solicitud de cita del paciente {$patient->user->name}"
        );
        return redirect()->route('appointments.index')
            ->with('success', '✅ Cita solicitada correctamente.');
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors  = Doctor::with('user')->get();
        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    // ✅ MEJORA: VALIDACIÓN DE DISPONIBILIDAD AL REPROGRAMAR
    public function update(Request $request, Appointment $appointment)
    {
        // Validación
        $validated = $request->validate([
            'appointment_date' => 'nullable|date|after:now',
            'status'           => 'required|in:pending,confirmed,cancelled',
        ]);

        // ✅ F11: VALIDAR DISPONIBILIDAD AL CAMBIAR FECHA
        if ($request->filled('appointment_date') && 
            $request->appointment_date != $appointment->appointment_date) {
            
            $existingAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('id', '!=', $appointment->id) // Excluir la cita actual
                ->first();

            if ($existingAppointment) {
                $doctor = Doctor::with('user')->find($appointment->doctor_id);
                return back()
                    ->withInput()
                    ->with('warning', '⚠️ El Dr. ' . $doctor->user->name . ' ya tiene una cita en ese horario. Por favor, selecciona otra fecha/hora.');
            }
        }

        // Actualizar
        $dataToUpdate = ['status' => $validated['status']];

        if ($request->filled('appointment_date')) {
            $dataToUpdate['appointment_date'] = $validated['appointment_date'];
        }

        $appointment->update($dataToUpdate);
        event(new AppointmentUpdated($appointment));

        // Notificación al paciente
        $patient = Patient::with('user')->find($appointment->patient_id);
        $doctor  = Doctor::with('user')->find($appointment->doctor_id);

        if ($validated['status'] === 'confirmed') {
        // Notificar al paciente
        event(new AppointmentNotification(
            $patient->user_id,
            '¡Cita Confirmada!',
            'Su cita con Dr. ' . $doctor->user->name . ' ha sido confirmada para el ' .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i'),
            $appointment->id,
            $appointment->appointment_date
        ));

        // Notificación OneSignal (la mantienes)
        $this->sendNotification(
            $patient->user_id,
            "¡Cita Confirmada!",
            "Su cita con Dr. {$doctor->user->name} ha sido confirmada para el " .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i')
        );
        // 📧 NUEVO: Enviar email al paciente
        $this->sendEmail(
            $patient->user->email,
            $appointment,
            "¡Cita Confirmada!",
            "Su cita ha sido confirmada exitosamente"
        );

        } elseif ($validated['status'] === 'cancelled') {
        // Notificar al paciente
        event(new AppointmentNotification(
            $patient->user_id,
            'Cita Cancelada',
            'Su cita con Dr. ' . $doctor->user->name . ' del ' .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') .
            ' ha sido cancelada.',
            $appointment->id,
            $appointment->appointment_date
        ));

        // Notificación OneSignal (la mantienes)
        $this->sendNotification(
            $patient->user_id,
            "Cita Cancelada",
            "Su cita con Dr. {$doctor->user->name} del " .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') .
            " ha sido cancelada."
        );
        // 📧 NUEVO: Enviar email al paciente
        $this->sendEmail(
            $patient->user->email,
            $appointment,
            "Cita Cancelada",
            "Lamentamos informarle que su cita ha sido cancelada"
        );

        }

        return redirect()->route('appointments.index')
            ->with('success', '✅ Cita actualizada correctamente.');
    }

    public function destroy(Appointment $appointment)
    {
        // 🔥 AGREGAR ESTAS LÍNEAS - NOTIFICACIÓN ANTES DE ELIMINAR
        $patient = Patient::with('user')->find($appointment->patient_id);
        $doctor  = Doctor::with('user')->find($appointment->doctor_id);
        
           // Notificar al paciente
        event(new AppointmentNotification(
            $patient->user_id,
            'Cita Eliminada',
            'Su cita con Dr. ' . $doctor->user->name . ' del ' .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') .
            ' ha sido eliminada.',
            $appointment->id,
            $appointment->appointment_date
        ));

        // Notificar al médico
        event(new AppointmentNotification(
            $doctor->user_id,
            'Cita Eliminada',
            'La cita con el paciente ' . $patient->user->name . ' del ' .
            Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') .
            ' ha sido eliminada.',
            $appointment->id,
            $appointment->appointment_date
        ));

        // 📧 NUEVO: Enviar emails antes de eliminar
        $this->sendEmail(
            $patient->user->email,
            $appointment,
            "Cita Eliminada",
            "Su cita ha sido eliminada del sistema"
        );

        $this->sendEmail(
            $doctor->user->email,
            $appointment,
            "Cita Eliminada",
            "La cita con el paciente {$patient->user->name} ha sido eliminada"
        );
        
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', '✅ Cita eliminada correctamente.');
    }

    private function sendNotification($userId, $title, $message)
    {
        try {
            Log::info("Intentando enviar notificación a usuario: {$userId}");
            Log::info("Título: {$title}");
            Log::info("Mensaje: {$message}");

            $fields = [
                'include_aliases' => [
                    'external_id' => [(string)$userId]
                ],
                'target_channel' => 'push',
                'headings' => ['en' => $title],
                'contents' => ['en' => $message],
            ];

            OneSignal::sendPush($fields, $message);
            Log::info("Notificación enviada exitosamente");

        } catch (\Exception $e) {
            Log::error('Error enviando notificación OneSignal: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
        }
    }
    /**
     * Enviar email de notificación
     */
    private function sendEmail($userEmail, $appointment, $subject, $message)
    {
        try {
            if ($userEmail) {
                Mail::to($userEmail)->send(
                    new AppointmentMail($appointment, $subject, $message)
                );
                Log::info("Email enviado exitosamente a: {$userEmail}");
            }
        } catch (\Exception $e) {
            Log::error('Error enviando email: ' . $e->getMessage());
            // No detenemos la ejecución si falla el email
        }
    }
}