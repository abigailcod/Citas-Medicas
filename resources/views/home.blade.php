@extends('layouts.app')

@section('content')
<div class="container">

    <div class="mb-4">
        <h1 class="fw-bold text-primary">Bienvenido, {{ Auth::user()->name }}</h1>
        <p class="text-muted">
            Rol: 
            <span class="badge bg-dark">
                {{ ucfirst(Auth::user()->role) }}
            </span>
        </p>
    </div>

    <div class="row g-4">

        {{-- ADMIN --}}
        @if(Auth::user()->isAdmin())
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">👨‍⚕️ Doctores</h5>
                        <p class="text-muted">Gestiona médicos del sistema</p>
                        <a href="{{ route('doctors.index') }}" class="btn btn-primary w-100">
                            Ver Doctores
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">🧑‍🤝‍🧑 Pacientes</h5>
                        <p class="text-muted">Administra pacientes registrados</p>
                        <a href="{{ route('patients.index') }}" class="btn btn-success w-100">
                            Ver Pacientes
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- ADMIN Y MEDICO --}}
        @if(Auth::user()->isAdmin() || Auth::user()->isMedico())
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">📅 Citas Médicas</h5>
                        <p class="text-muted">Gestiona citas médicas</p>
                        <a href="{{ route('appointments.index') }}" class="btn btn-info w-100">
                            Ver Citas
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- PACIENTE --}}
        @if(Auth::user()->isPaciente())
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <h5 class="fw-bold">➕ Nueva Cita</h5>
                        <p class="text-muted">Solicita una nueva cita médica</p>
                        <a href="{{ route('appointments.create') }}" class="btn btn-warning w-100">
                            Crear Cita
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection

