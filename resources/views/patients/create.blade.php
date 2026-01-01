@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registrar Nuevo Paciente</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>¡Errores encontrados!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('patients.store') }}" method="POST">
                        @csrf

                        {{-- DATOS DE USUARIO --}}
                        <h5 class="text-secondary mb-3">📋 Datos de Usuario</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="{{ old('name') }}" required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" 
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" 
                                   required minlength="6">
                            <small class="form-text text-muted">Mínimo 6 caracteres</small>
                        </div>

                        <hr class="my-4">

                        {{-- DATOS ESPECÍFICOS DEL PACIENTE --}}
                        <h5 class="text-secondary mb-3">🩺 Datos del Paciente</h5>

                        <div class="mb-3">
                            <label for="edad" class="form-label">Edad <span class="text-danger">*</span></label>
                            <input type="number" name="edad" id="edad" class="form-control" 
                                   value="{{ old('edad') }}" min="0" max="120" required>
                        </div>

                        <div class="mb-3">
                            <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" id="sexo" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <option value="Masculino" {{ old('sexo') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Femenino" {{ old('sexo') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="Otro" {{ old('sexo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                💾 Guardar Paciente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection