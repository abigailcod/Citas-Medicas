@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tarjeta de Perfil -->
            <div class="card card-modern shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                
                <!-- Header con Avatar -->
                <div class="profile-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 3rem 2rem; text-align: center; position: relative;">
                    <div class="avatar-container" style="position: relative; display: inline-block; margin-bottom: 1rem;">
                        <div class="avatar-circle" style="width: 150px; height: 150px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); margin: 0 auto;">
                            <i class="fas fa-user" style="font-size: 5rem; color: #667eea;"></i>
                        </div>
                        <span class="badge position-absolute" style="bottom: 10px; right: -10px; font-size: 1rem; padding: 0.5rem 1rem; border-radius: 50px; {{ 
                            $user->role === 'admin' ? 'background: #dc3545;' : 
                            ($user->role === 'medico' ? 'background: #17a2b8;' : 'background: #28a745;') 
                        }}">
                            <i class="fas fa-{{ 
                                $user->role === 'admin' ? 'user-shield' : 
                                ($user->role === 'medico' ? 'user-md' : 'user') 
                            }}"></i>
                        </span>
                    </div>
                    <h2 class="text-white fw-bold mb-2">{{ $user->name }}</h2>
                    <p class="text-white mb-0 opacity-75">
                        <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                    </p>
                    <div class="mt-3">
                        <span class="badge" style="background: rgba(255,255,255,0.3); font-size: 1.1rem; padding: 0.6rem 1.2rem; border-radius: 50px;">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                    
                    <!-- Botón Editar -->
                    <a href="{{ route('profile.edit') }}" class="btn btn-light btn-lg mt-3" style="border-radius: 50px; padding: 0.7rem 2rem; font-weight: 600;">
                        <i class="fas fa-edit me-2"></i>Editar Perfil
                    </a>
                </div>

                <div class="card-body p-4">
                    
                    <!-- Información General -->
                    <div class="section-info mb-4">
                        <h4 class="text-primary mb-3 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Información General
                        </h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-box p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <small class="text-muted d-block mb-1">Nombre Completo</small>
                                    <strong class="d-block"><i class="fas fa-user text-primary me-2"></i>{{ $user->name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <small class="text-muted d-block mb-1">Correo Electrónico</small>
                                    <strong class="d-block"><i class="fas fa-envelope text-primary me-2"></i>{{ $user->email }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <small class="text-muted d-block mb-1">Rol en el Sistema</small>
                                    <strong class="d-block"><i class="fas fa-id-badge text-primary me-2"></i>{{ ucfirst($user->role) }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box p-3" style="background: #f8f9fa; border-radius: 12px;">
                                    <small class="text-muted d-block mb-1">Miembro desde</small>
                                    <strong class="d-block"><i class="fas fa-calendar text-primary me-2"></i>{{ $user->created_at->format('d/m/Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información Específica de Paciente -->
                    @if($user->role === 'paciente' && $user->patient)
                        <hr class="my-4">
                        <div class="section-info mb-4">
                            <h4 class="text-success mb-3 fw-bold">
                                <i class="fas fa-notes-medical me-2"></i>Información Médica
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background: #d4edda; border-radius: 12px;">
                                        <small class="text-muted d-block mb-1">Edad</small>
                                        <strong class="d-block"><i class="fas fa-birthday-cake text-success me-2"></i>{{ $user->patient->edad }} años</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background: #d4edda; border-radius: 12px;">
                                        <small class="text-muted d-block mb-1">Sexo</small>
                                        <strong class="d-block"><i class="fas fa-venus-mars text-success me-2"></i>{{ $user->patient->sexo }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de Paciente -->
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Mis Estadísticas</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->patient->appointments->count() }}</h3>
                                    <small>Total de Citas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->patient->appointments->where('status', 'confirmed')->count() }}</h3>
                                    <small>Confirmadas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->patient->appointments->where('status', 'pending')->count() }}</h3>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Información Específica de Médico -->
                    @if($user->role === 'medico' && $user->doctor)
                        <hr class="my-4">
                        <div class="section-info mb-4">
                            <h4 class="text-info mb-3 fw-bold">
                                <i class="fas fa-stethoscope me-2"></i>Información Profesional
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background: #d1ecf1; border-radius: 12px;">
                                        <small class="text-muted d-block mb-1">Especialidad</small>
                                        <strong class="d-block"><i class="fas fa-hospital text-info me-2"></i>{{ $user->doctor->especialidad }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box p-3" style="background: #d1ecf1; border-radius: 12px;">
                                        <small class="text-muted d-block mb-1">Número de Colegiatura</small>
                                        <strong class="d-block">
                                            <i class="fas fa-id-card text-info me-2"></i>
                                            {{ $user->doctor->numero_colegiatura ?? 'No registrado' }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Estadísticas de Médico -->
                        <div class="row g-3">
                            <div class="col-12">
                                <h5 class="fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Mis Estadísticas</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->doctor->appointments->count() }}</h3>
                                    <small>Total de Citas</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-hourglass-half fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->doctor->appointments->where('status', 'pending')->count() }}</h3>
                                    <small>Por Revisar</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-4" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 15px; color: white;">
                                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                                    <h3 class="fw-bold mb-0">{{ $user->doctor->appointments->where('status', 'confirmed')->count() }}</h3>
                                    <small>Confirmadas</small>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection