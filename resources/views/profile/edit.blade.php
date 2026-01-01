@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <!-- Mensajes -->
            @if(session('success'))
                <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Errores encontrados:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Tarjeta de Edición -->
            <div class="card card-modern shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
                
                <!-- Header -->
                <div class="card-header text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 2rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 fw-bold">
                            <i class="fas fa-user-edit me-2"></i>Editar Mi Perfil
                        </h3>
                        <a href="{{ route('profile.show') }}" class="btn btn-light" style="border-radius: 50px;">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Información General -->
                        <div class="section-form mb-4">
                            <h5 class="text-primary fw-bold mb-4">
                                <i class="fas fa-user-circle me-2"></i>Información General
                            </h5>

                            <!-- Nombre -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-user text-primary me-2"></i>Nombre Completo <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       class="form-control form-control-lg" 
                                       style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                       value="{{ old('name', $user->name) }}" 
                                       required 
                                       placeholder="Ingresa tu nombre completo">
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope text-primary me-2"></i>Correo Electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       class="form-control form-control-lg" 
                                       style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                       value="{{ old('email', $user->email) }}" 
                                       required 
                                       placeholder="tu@email.com">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Este será tu correo de acceso al sistema
                                </small>
                            </div>
                        </div>

                        <!-- Información Específica de PACIENTE -->
                        @if($user->role === 'paciente' && $user->patient)
                            <hr class="my-4">
                            <div class="section-form mb-4">
                                <h5 class="text-success fw-bold mb-4">
                                    <i class="fas fa-notes-medical me-2"></i>Información Médica
                                </h5>

                                <div class="row">
                                    <!-- Edad -->
                                    <div class="col-md-6 mb-4">
                                        <label for="edad" class="form-label fw-bold">
                                            <i class="fas fa-birthday-cake text-success me-2"></i>Edad <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               name="edad" 
                                               id="edad" 
                                               class="form-control form-control-lg" 
                                               style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                               value="{{ old('edad', $user->patient->edad) }}" 
                                               min="1" 
                                               max="120" 
                                               required 
                                               placeholder="Tu edad">
                                    </div>

                                    <!-- Sexo -->
                                    <div class="col-md-6 mb-4">
                                        <label for="sexo" class="form-label fw-bold">
                                            <i class="fas fa-venus-mars text-success me-2"></i>Sexo <span class="text-danger">*</span>
                                        </label>
                                        <select name="sexo" 
                                                id="sexo" 
                                                class="form-select form-select-lg" 
                                                style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                                required>
                                            <option value="">Selecciona...</option>
                                            <option value="Masculino" {{ old('sexo', $user->patient->sexo) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ old('sexo', $user->patient->sexo) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                            <option value="Otro" {{ old('sexo', $user->patient->sexo) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Información Específica de MÉDICO -->
                        @if($user->role === 'medico' && $user->doctor)
                            <hr class="my-4">
                            <div class="section-form mb-4">
                                <h5 class="text-info fw-bold mb-4">
                                    <i class="fas fa-stethoscope me-2"></i>Información Profesional
                                </h5>

                                <!-- Especialidad -->
                                <div class="mb-4">
                                    <label for="especialidad" class="form-label fw-bold">
                                        <i class="fas fa-hospital text-info me-2"></i>Especialidad <span class="text-danger">*</span>
                                    </label>
                                    <select name="especialidad" 
                                            id="especialidad" 
                                            class="form-select form-select-lg" 
                                            style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                            required>
                                        <option value="">Selecciona tu especialidad...</option>
                                        <option value="Medicina General" {{ old('especialidad', $user->doctor->especialidad) == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                                        <option value="Cardiología" {{ old('especialidad', $user->doctor->especialidad) == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                        <option value="Pediatría" {{ old('especialidad', $user->doctor->especialidad) == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                        <option value="Traumatología" {{ old('especialidad', $user->doctor->especialidad) == 'Traumatología' ? 'selected' : '' }}>Traumatología</option>
                                        <option value="Dermatología" {{ old('especialidad', $user->doctor->especialidad) == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                        <option value="Neurología" {{ old('especialidad', $user->doctor->especialidad) == 'Neurología' ? 'selected' : '' }}>Neurología</option>
                                        <option value="Ginecología" {{ old('especialidad', $user->doctor->especialidad) == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                                        <option value="Oftalmología" {{ old('especialidad', $user->doctor->especialidad) == 'Oftalmología' ? 'selected' : '' }}>Oftalmología</option>
                                        <option value="Psiquiatría" {{ old('especialidad', $user->doctor->especialidad) == 'Psiquiatría' ? 'selected' : '' }}>Psiquiatría</option>
                                    </select>
                                </div>
                            </div>
                        @endif

                        <!-- Cambiar Contraseña (OPCIONAL) -->
                        <hr class="my-4">
                        <div class="section-form mb-4">
                            <h5 class="text-danger fw-bold mb-4">
                                <i class="fas fa-lock me-2"></i>Cambiar Contraseña (Opcional)
                            </h5>
                            <div class="alert alert-info" style="border-radius: 12px;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Nota:</strong> Deja estos campos vacíos si no deseas cambiar tu contraseña.
                            </div>

                            <div class="row">
                                <!-- Nueva Contraseña -->
                                <div class="col-md-6 mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-key text-danger me-2"></i>Nueva Contraseña
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control form-control-lg" 
                                           style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                           placeholder="Mínimo 8 caracteres">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>Debe tener al menos 8 caracteres
                                    </small>
                                </div>

                                <!-- Confirmar Contraseña -->
                                <div class="col-md-6 mb-4">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-key text-danger me-2"></i>Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="form-control form-control-lg" 
                                           style="border-radius: 12px; border: 2px solid #e0e0e0;"
                                           placeholder="Repite la contraseña">
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary btn-lg" style="border-radius: 50px; padding: 0.8rem 2rem;">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; border-radius: 50px; padding: 0.8rem 2.5rem; font-weight: 600;">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <!-- Sección de Eliminar Cuenta -->
            <div class="card card-modern shadow-lg border-danger mt-4" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header bg-danger text-white" style="padding: 1.5rem;">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Zona de Peligro
                    </h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Una vez que elimines tu cuenta, <strong>todos tus datos serán permanentemente eliminados</strong>. Esta acción no se puede deshacer.
                    </p>
                    <button type="button" class="btn btn-danger btn-lg" style="border-radius: 50px;" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-trash-alt me-2"></i>Eliminar Mi Cuenta
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación de Cuenta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <div class="alert alert-danger mb-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>¡Advertencia!</strong> Esta acción es irreversible.
                    </div>
                    <p class="mb-4">¿Estás absolutamente seguro de que deseas eliminar tu cuenta? Se perderán:</p>
                    <ul class="text-danger">
                        <li>Todos tus datos personales</li>
                        <li>Historial de citas</li>
                        <li>Acceso al sistema</li>
                    </ul>
                    
                    <div class="mb-3">
                        <label for="password_delete" class="form-label fw-bold">
                            Ingresa tu contraseña actual para confirmar:
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password_delete" 
                               class="form-control form-control-lg" 
                               style="border-radius: 12px;"
                               required 
                               placeholder="Tu contraseña actual">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 50px;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 50px;">
                        <i class="fas fa-trash-alt me-2"></i>Sí, Eliminar Mi Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
