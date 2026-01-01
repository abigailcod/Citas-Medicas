@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-primary">
            @if(Auth::user()->role === 'medico')
                <i class="fas fa-stethoscope"></i> Gestión de Citas (Médico)
            @elseif(Auth::user()->role === 'paciente')
                <i class="fas fa-calendar-check"></i> Mis Citas Médicas
            @else
                <i class="fas fa-list"></i> Lista de Citas
            @endif
        </h2>
        
        {{-- Solo PACIENTES pueden crear citas --}}
        @if(Auth::user()->role === 'paciente')
            <a href="{{ route('appointments.create') }}" class="btn btn-success btn-modern">
                <i class="fas fa-plus-circle"></i> Solicitar Nueva Cita
            </a>
        @endif

        {{-- ADMIN también puede crear citas --}}
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('appointments.create') }}" class="btn btn-success btn-modern">
                <i class="fas fa-plus-circle"></i> Crear Nueva Cita
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-modern alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- ✅ INSERTA AQUÍ EL FORMULARIO DE BÚSQUEDA --}}
    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'medico')
    <div class="card card-modern mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}" class="row g-3">
                
                {{-- Filtro por Fecha --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-calendar-alt"></i> Fecha
                    </label>
                    <input type="date" name="date" class="form-control" 
                           value="{{ request('date') }}">
                </div>

                {{-- Filtro por Paciente (solo admin) --}}
                @if(Auth::user()->role === 'admin')
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-user"></i> Paciente
                    </label>
                    <select name="patient_id" class="form-select">
                        <option value="">Todos los pacientes</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" 
                                {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Filtro por Médico (solo admin) --}}
                @if(Auth::user()->role === 'admin')
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-user-md"></i> Médico
                    </label>
                    <select name="doctor_id" class="form-select">
                        <option value="">Todos los médicos</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" 
                                {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Filtro por Estado --}}
                <div class="col-md-3">
                    <label class="form-label small text-muted">
                        <i class="fas fa-info-circle"></i> Estado
                    </label>
                    <select name="status" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pendiente
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                            Confirmada
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            Cancelada
                        </option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary btn-modern">
                        <i class="fas fa-redo"></i> Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>
    </div>
    @endif
    {{-- ✅ FIN DEL FORMULARIO DE BÚSQUEDA --}}
    <div class="card card-modern">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-modern table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th><i class="fas fa-calendar-alt"></i> FECHA Y HORA</th>
                            <th><i class="fas fa-user"></i> PACIENTE</th>
                            <th><i class="fas fa-user-md"></i> MÉDICO</th>
                            <th class="text-center"><i class="fas fa-info-circle"></i> ESTADO</th>
                            <th class="text-center"><i class="fas fa-cogs"></i> ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td>
                                    <div class="fw-bold text-primary">
                                        <i class="fas fa-calendar"></i> 
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="far fa-clock"></i> 
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <i class="fas fa-user-circle text-info"></i>
                                        {{ $appointment->patient->user->name ?? 'Usuario Eliminado' }}
                                    </div>
                                    @if(Auth::user()->role === 'medico')
                                        <div class="small text-muted">
                                            <i class="fas fa-birthday-cake"></i> 
                                            {{ $appointment->patient->edad ?? '?' }} años
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        <i class="fas fa-user-md text-primary"></i>
                                        Dr. {{ $appointment->doctor->user->name ?? 'No disponible' }}
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-stethoscope"></i>
                                        {{ $appointment->doctor->especialidad ?? '' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-modern rounded-pill
                                        @if($appointment->status == 'confirmed') bg-success 
                                        @elseif($appointment->status == 'cancelled') bg-danger 
                                        @else bg-warning text-dark 
                                        @endif">
                                        @if($appointment->status == 'confirmed')
                                            <i class="fas fa-check-circle"></i> Confirmada
                                        @elseif($appointment->status == 'cancelled')
                                            <i class="fas fa-times-circle"></i> Cancelada
                                        @else
                                            <i class="fas fa-clock"></i> Pendiente
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        
                                        {{-- ========================================== --}}
                                        {{-- ACCIONES MÉDICO                            --}}
                                        {{-- ========================================== --}}
                                        @if(Auth::user()->role === 'medico')
                                            
                                            {{-- Botones rápidos solo si está PENDIENTE --}}
                                            @if($appointment->status === 'pending')
                                                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" style="display: inline;">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button type="submit" class="btn btn-sm btn-success btn-modern" title="Aceptar Cita">
                                                        <i class="fas fa-check"></i> Aceptar
                                                    </button>
                                                </form>
                                                <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" style="display: inline;">
                                                    @csrf @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-modern" title="Rechazar Cita">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Botón Editar SIEMPRE visible para médicos --}}
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm btn-modern" title="Editar Cita">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        
                                        {{-- ========================================== --}}
                                        {{-- ACCIONES PACIENTE: Cancelar                --}}
                                        {{-- ========================================== --}}
                                        @elseif(Auth::user()->role === 'paciente' && $appointment->status === 'pending')
                                            <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de cancelar esta cita?');" style="display: inline;">
                                                @csrf @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-modern">
                                                    <i class="fas fa-ban"></i> Cancelar Solicitud
                                                </button>
                                            </form>
                                        @endif

                                        {{-- ========================================== --}}
                                        {{-- ACCIONES ADMIN: Editar / Eliminar          --}}
                                        {{-- ========================================== --}}
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('appointments.edit', $appointment->id) }}" class="btn btn-warning btn-sm btn-modern">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <form action="{{ route('appointments.destroy', $appointment->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-modern" onclick="return confirm('¿Está seguro de eliminar esta cita?')">
                                                    <i class="fas fa-trash-alt"></i> Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-center">
                                        <i class="fas fa-calendar-times fa-4x text-muted mb-3 d-block" style="opacity: 0.3;"></i>
                                        <h5 class="text-muted">No hay citas registradas</h5>
                                        <p class="text-muted small">
                                            @if(Auth::user()->role === 'paciente')
                                                Comienza solicitando una nueva cita
                                            @elseif(Auth::user()->role === 'medico')
                                                No tienes solicitudes de citas pendientes
                                            @else
                                                Comienza creando una nueva cita
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection