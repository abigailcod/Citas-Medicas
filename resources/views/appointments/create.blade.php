@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-modern shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus"></i> Crear Nueva Cita Médica
                    </h4>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle"></i> Errores encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        {{-- ========================================== --}}
                        {{-- ADMIN: puede elegir paciente              --}}
                        {{-- ========================================== --}}
                        @if(auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label for="patient_id" class="form-label fw-bold">
                                    <i class="fas fa-user"></i> Seleccionar Paciente <span class="text-danger">*</span>
                                </label>
                                <select name="patient_id" id="patient_id" class="form-select" required>
                                    <option value="">-- Seleccione un paciente --</option>
                                    @foreach ($patients as $patient)
                                        <option value="{{ $patient->id }}">
                                            {{ $patient->user->name }} ({{ $patient->edad }} años - {{ $patient->sexo }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- ========================================== --}}
                        {{-- PACIENTE: paciente automático             --}}
                        {{-- ========================================== --}}
                        @if(auth()->user()->role === 'paciente')
                            @if(auth()->user()->patient)
                                <input type="hidden" name="patient_id" value="{{ auth()->user()->patient->id }}">
                                
                                <div class="alert alert-info alert-modern">
                                    <i class="fas fa-info-circle"></i> 
                                    Está solicitando una cita para: <strong>{{ auth()->user()->name }}</strong>
                                </div>
                            @else
                                <div class="alert alert-danger alert-modern">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Error:</strong> Tu perfil de paciente no está completo. 
                                    Por favor, contacta al administrador.
                                </div>
                            @endif
                        @endif

                        {{-- ========================================== --}}
                        {{-- SELECCIONAR MÉDICO                        --}}
                        {{-- ========================================== --}}
                        @if(auth()->user()->role !== 'medico')
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label fw-bold">
                                    <i class="fas fa-user-md"></i> Seleccionar Médico <span class="text-danger">*</span>
                                </label>
                                <select name="doctor_id" id="doctor_id" class="form-select" required>
                                    <option value="">-- Seleccione un médico --</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">
                                            Dr. {{ $doctor->user->name }} - {{ $doctor->especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- ========================================== --}}
                        {{-- MÉDICO: doctor automático                 --}}
                        {{-- ========================================== --}}
                        @if(auth()->user()->role === 'medico')
                            <input type="hidden" name="doctor_id" value="{{ auth()->user()->doctor->id }}">
                            
                            <div class="alert alert-info alert-modern">
                                <i class="fas fa-info-circle"></i> 
                                Está creando una cita como: <strong>Dr. {{ auth()->user()->name }}</strong>
                            </div>
                        @endif

                        {{-- ========================================== --}}
                        {{-- FECHA Y HORA                               --}}
                        {{-- ========================================== --}}
                        <div class="mb-3">
                            <label for="appointment_date" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt"></i> Fecha y Hora de la Cita <span class="text-danger">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="appointment_date" 
                                   id="appointment_date" 
                                   class="form-control" 
                                   min="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            <small class="form-text text-muted">
                                <i class="fas fa-lightbulb"></i> Seleccione una fecha y hora futura para su cita
                            </small>
                        </div>

                        {{-- ========================================== --}}
                        {{-- MOTIVO (OPCIONAL)                         --}}
                        {{-- ========================================== --}}
                        <div class="mb-3">
                            <label for="motivo" class="form-label fw-bold">
                                <i class="fas fa-clipboard"></i> Motivo de la Consulta (Opcional)
                            </label>
                            <textarea name="motivo" 
                                      id="motivo" 
                                      class="form-control" 
                                      rows="3" 
                                      placeholder="Describa brevemente el motivo de su consulta..."></textarea>
                        </div>

                        {{-- ========================================== --}}
                        {{-- BOTONES                                    --}}
                        {{-- ========================================== --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-modern">
                                <i class="fas fa-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success btn-modern">
                                <i class="fas fa-check"></i> Crear Cita
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection