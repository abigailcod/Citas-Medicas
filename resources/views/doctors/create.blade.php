@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            {{-- AGREGADO: Manejo de errores de validación --}}
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
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Nuevo Médico</h4>
                </div>
                <div class="card-body">
                    {{-- Este formulario envía los datos al método store que creamos con DB::transaction --}}
                    <form action="{{ route('doctors.store') }}" method="POST">
                        @csrf

                        <h5 class="text-secondary mb-3">Datos de Cuenta</h5>
                        
                        {{-- Datos de Usuario (se crearán en la tabla 'users') --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Ej: Dr. Juan Pérez">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="correo@hospital.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Mínimo 6 caracteres">
                        </div>

                        <hr class="my-4">
                        <h5 class="text-secondary mb-3">Información Profesional</h5>

                        {{-- Datos Específicos del Doctor (se crearán en la tabla 'doctors') --}}
                        <div class="mb-3">
                            <label class="form-label">Especialidad</label>
                            <select name="especialidad" class="form-select" required>
                                <option value="">Seleccione una especialidad...</option>
                                <option value="Medicina General" {{ old('especialidad') == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                                <option value="Cardiología" {{ old('especialidad') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                <option value="Pediatría" {{ old('especialidad') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                <option value="Traumatología" {{ old('especialidad') == 'Traumatología' ? 'selected' : '' }}>Traumatología</option>
                                <option value="Dermatología" {{ old('especialidad') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                <option value="Ginecología" {{ old('especialidad') == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                            </select>
                        </div>

                        {{-- ✅ NUEVO: Número de Colegiatura --}}
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-id-card"></i> Número de Colegiatura
                            </label>
                            <input type="text" 
                                   name="numero_colegiatura" 
                                   class="form-control @error('numero_colegiatura') is-invalid @enderror" 
                                   value="{{ old('numero_colegiatura') }}" 
                                   placeholder="Ej: CMP-123456 o COL-789012">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Número de registro profesional (opcional)
                            </small>
                            @error('numero_colegiatura')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Médico</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection