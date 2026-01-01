@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">
    
    <!-- Hero Section - Bienvenida -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px; overflow: hidden;">
                <div class="card-body p-5 text-white text-center">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                            <i class="fas fa-user-circle" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                    <h1 class="display-4 fw-bold mb-3">¡Bienvenido, {{ Auth::user()->name }}!</h1>
                    <p class="lead mb-4" style="font-size: 1.3rem;">
                        <span class="badge" style="background: rgba(255,255,255,0.3); padding: 0.8rem 1.5rem; border-radius: 50px; font-size: 1.1rem;">
                            <i class="fas fa-{{ 
                                Auth::user()->role === 'admin' ? 'user-shield' : 
                                (Auth::user()->role === 'medico' ? 'user-md' : 'user') 
                            }} me-2"></i>
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    </p>
                    <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                        <i class="far fa-calendar-alt me-2"></i>
                        {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row g-4 mb-5">
        @if(Auth::user()->role === 'paciente' && Auth::user()->patient)
            <!-- Estadísticas de Paciente -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt" style="font-size: 3rem; color: #667eea;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-primary mb-2">
                            {{ Auth::user()->patient->appointments->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Total de Citas</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-success mb-2">
                            {{ Auth::user()->patient->appointments->where('status', 'confirmed')->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Citas Confirmadas</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clock" style="font-size: 3rem; color: #ffc107;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-warning mb-2">
                            {{ Auth::user()->patient->appointments->where('status', 'pending')->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Citas Pendientes</p>
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()->role === 'medico' && Auth::user()->doctor)
            <!-- Estadísticas de Médico -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-check" style="font-size: 3rem; color: #667eea;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-primary mb-2">
                            {{ Auth::user()->doctor->appointments->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Total de Citas</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-hourglass-half" style="font-size: 3rem; color: #ffc107;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-warning mb-2">
                            {{ Auth::user()->doctor->appointments->where('status', 'pending')->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Por Revisar</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-check-circle" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-success mb-2">
                            {{ Auth::user()->doctor->appointments->where('status', 'confirmed')->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Confirmadas</p>
                    </div>
                </div>
            </div>
        @endif

        @if(Auth::user()->role === 'admin')
            <!-- Estadísticas de Admin -->
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-users" style="font-size: 3rem; color: #17a2b8;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-info mb-2">
                            {{ \App\Models\Patient::count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Pacientes</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-user-md" style="font-size: 3rem; color: #6f42c1;"></i>
                        </div>
                        <h2 class="display-4 fw-bold" style="color: #6f42c1;">
                            {{ \App\Models\Doctor::count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Doctores</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-calendar-alt" style="font-size: 3rem; color: #28a745;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-success mb-2">
                            {{ \App\Models\Appointment::count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Total Citas</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 stat-card" style="border-radius: 15px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-clock" style="font-size: 3rem; color: #ffc107;"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-warning mb-2">
                            {{ \App\Models\Appointment::where('status', 'pending')->count() }}
                        </h2>
                        <p class="text-muted mb-0 fw-semibold">Pendientes</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Acceso Rápido -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Acceso Rápido
                    </h3>
                    <div class="row g-3">
                        @if(Auth::user()->role === 'paciente')
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('appointments.create') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                                    Nueva Cita
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('appointments.index') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                                    Mis Citas
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'medico')
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('appointments.index') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-calendar-check fa-2x d-block mb-2"></i>
                                    Gestionar Citas
                                </a>
                            </div>
                        @endif

                        @if(Auth::user()->role === 'admin')
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('doctors.index') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-user-md fa-2x d-block mb-2"></i>
                                    Doctores
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('patients.index') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-users fa-2x d-block mb-2"></i>
                                    Pacientes
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('appointments.index') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; font-weight: 600;">
                                    <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
                                    Todas las Citas
                                </a>
                            </div>
                        @endif

                        <div class="col-12 col-md-6 col-lg-3">
                            <a href="{{ route('profile.show') }}" class="btn w-100 py-3 text-white" style="background: linear-gradient(135deg, #868f96 0%, #596164 100%); border-radius: 12px; font-weight: 600;">
                                <i class="fas fa-user-circle fa-2x d-block mb-2"></i>
                                Mi Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .stat-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
    }
</style>
@endsection