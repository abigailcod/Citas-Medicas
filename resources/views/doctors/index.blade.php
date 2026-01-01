@extends('layouts.app')

@section('content')
<div class="container py-4 fade-in">
    <div class="card-modern">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-user-md me-2"></i> Lista de Médicos
            </h4>
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('doctors.create') }}" class="btn-modern" style="background: var(--medical-green); color: white;">
                    <i class="fas fa-plus me-1"></i> Nuevo Médico
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert-modern alert alert-success fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            @endif

            @if($doctors->count() > 0)
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Especialidad</th>
                                <th>N° Colegiatura</th> {{-- ✅ NUEVA COLUMNA --}}
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($doctors as $doctor)
                                <tr>
                                    <td>{{ $doctor->id }}</td>
                                    <td>
                                        <div class="fw-bold">Dr. {{ $doctor->user->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>{{ $doctor->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge-modern" style="background: var(--medical-blue); color: white;">
                                            {{ $doctor->especialidad }}
                                        </span>
                                    </td>
                                    <td> {{-- ✅ NUEVA COLUMNA --}}
                                        @if($doctor->numero_colegiatura)
                                            <span class="badge-modern" style="background: var(--medical-purple); color: white;">
                                                <i class="fas fa-id-card me-1"></i>{{ $doctor->numero_colegiatura }}
                                            </span>
                                        @else
                                            <span class="text-muted small">
                                                <i class="fas fa-minus-circle me-1"></i>No registrado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @if(Auth::user()->role === 'admin')
                                                <a href="{{ route('doctors.edit', $doctor->id) }}" 
                                                   class="btn-modern" 
                                                   style="background: var(--warning-color); color: #2D2D2D; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                                    <i class="fas fa-edit me-1"></i> Editar
                                                </a>
                                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST" style="display:inline;">
                                                    @csrf 
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn-modern" 
                                                            style="background: var(--danger-color); color: white; font-size: 0.85rem; padding: 0.4rem 0.8rem;"
                                                            onclick="return confirm('¿Eliminar este médico?')">
                                                        <i class="fas fa-trash me-1"></i> Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted py-4 mb-0">
                    <i class="fas fa-user-md fa-2x mb-2 text-secondary"></i>
                    <br>
                    No hay médicos registrados.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection