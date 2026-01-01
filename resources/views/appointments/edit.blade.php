@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Cita Médica #{{ $appointment->id }}
                    </h4>
                </div>
                <div class="card-body">
                    
                    {{-- Formulario de Edición --}}
                    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Información Informativa (No editable para evitar errores de asignación) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted">
                                    <i class="fas fa-user"></i> Paciente
                                </label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $appointment->patient->user->name ?? 'Usuario Eliminado' }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">
                                    <i class="fas fa-user-md"></i> Médico
                                </label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $appointment->doctor->user->name ?? 'No disponible' }}" readonly>
                            </div>
                        </div>

                        {{-- Campo: Fecha y Hora --}}
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt"></i> Fecha y Hora
                            </label>
                            <input type="datetime-local" 
                                   name="appointment_date"
                                   id="appointment_date"
                                   class="form-control @error('appointment_date') is-invalid @enderror"
                                   value="{{ old('appointment_date', \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Campo: Estado (Aquí es donde el médico corrige si se equivocó) --}}
                        @if(auth()->user()->role === 'medico' || auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-info-circle"></i> Estado de la Cita
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $appointment->status === 'pending' ? 'selected' : '' }}>
                                        🟡 Pendiente
                                    </option>
                                    <option value="confirmed" {{ $appointment->status === 'confirmed' ? 'selected' : '' }}>
                                        🟢 Confirmada
                                    </option>
                                    <option value="cancelled" {{ $appointment->status === 'cancelled' ? 'selected' : '' }}>
                                        🔴 Cancelada
                                    </option>
                                </select>
                                <div class="form-text text-muted">
                                    <i class="fas fa-lightbulb"></i> Si te equivocaste al aceptar/rechazar, cambia el estado aquí y guarda.
                                </div>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif {{-- ⭐ ESTE ES EL @endif QUE FALTABA --}}

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-modern">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary btn-modern">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection