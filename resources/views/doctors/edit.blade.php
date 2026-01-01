@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- Manejo de errores de validación --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>¡Atención!</strong> Por favor corrige los siguientes errores:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">✎ Editar Médico</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctors.update', $doctor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="text-secondary mb-3">Datos de Cuenta</h5>
                        
                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $doctor->user->name) }}" 
                                   required 
                                   placeholder="Ej: Dr. Juan Pérez">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" 
                                   name="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $doctor->user->email) }}" 
                                   required 
                                   placeholder="correo@hospital.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contraseña (Opcional) --}}
                        <div class="mb-3">
                            <label class="form-label">Nueva Contraseña</label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   minlength="6" 
                                   placeholder="Dejar vacío para mantener la actual">
                            <small class="form-text text-muted">
                                💡 Solo llenar si desea cambiar la contraseña
                            </small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h5 class="text-secondary mb-3">Información Profesional</h5>

                        {{-- Especialidad --}}
                        <div class="mb-3">
                            <label class="form-label">Especialidad</label>
                            <select name="especialidad" class="form-select @error('especialidad') is-invalid @enderror" required>
                                <option value="">Seleccione una especialidad...</option>
                                <option value="Medicina General" {{ old('especialidad', $doctor->especialidad) == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                                <option value="Cardiología" {{ old('especialidad', $doctor->especialidad) == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                <option value="Pediatría" {{ old('especialidad', $doctor->especialidad) == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                <option value="Traumatología" {{ old('especialidad', $doctor->especialidad) == 'Traumatología' ? 'selected' : '' }}>Traumatología</option>
                                <option value="Dermatología" {{ old('especialidad', $doctor->especialidad) == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                <option value="Ginecología" {{ old('especialidad', $doctor->especialidad) == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                            </select>
                            @error('especialidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ Número de Colegiatura --}}
                        <div class="mb-3">
                            <label class="form-label">
                                🆔 Número de Colegiatura
                            </label>
                            <input type="text" 
                                   name="numero_colegiatura" 
                                   class="form-control @error('numero_colegiatura') is-invalid @enderror" 
                                   value="{{ old('numero_colegiatura', $doctor->numero_colegiatura) }}" 
                                   placeholder="Ej: CMP-123456 o COL-789012">
                            <small class="form-text text-muted">
                                💡 Número de registro profesional (opcional)
                            </small>
                            @error('numero_colegiatura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                                ← Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning text-dark">
                                💾 Actualizar Médico
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection