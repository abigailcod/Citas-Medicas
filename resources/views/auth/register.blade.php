<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Médico</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        
        .register-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            padding: 40px 30px;
            color: white;
            text-align: center;
        }
        
        .register-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .register-header .icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .register-body {
            padding: 40px 30px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #11998e;
            box-shadow: 0 0 0 0.2rem rgba(17, 153, 142, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(17, 153, 142, 0.3);
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control, .input-group .form-select {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <!-- Header -->
        <div class="register-header">
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Crear Cuenta</h1>
            <p class="mb-0">Regístrate como paciente</p>
        </div>

        <!-- Body -->
        <div class="register-body">
            <!-- Mensajes de error -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <strong>Errores:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Nombre Completo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="name" class="form-control" 
                               placeholder="Tu nombre completo" 
                               value="{{ old('name') }}" 
                               required autofocus>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" 
                               placeholder="tu@email.com" 
                               value="{{ old('email') }}" 
                               required>
                    </div>
                </div>

                <!-- Edad -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Edad</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-birthday-cake"></i>
                        </span>
                        <input type="number" name="edad" class="form-control" 
                               placeholder="Tu edad" 
                               value="{{ old('edad') }}" 
                               min="1" max="120" 
                               required>
                    </div>
                </div>

                <!-- Sexo -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Sexo</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-venus-mars"></i>
                        </span>
                        <select name="sexo" class="form-select" required>
                            <option value="">Selecciona...</option>
                            <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ old('sexo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" 
                               placeholder="Mínimo 8 caracteres" 
                               required>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label class="form-label fw-bold">Confirmar Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Repite tu contraseña" 
                               required>
                    </div>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-success btn-register w-100 mb-3">
                    <i class="fas fa-user-plus"></i> Crear Cuenta
                </button>

                <!-- Link a Login -->
                <div class="text-center">
                    <span class="text-muted">¿Ya tienes cuenta?</span>
                    <a href="{{ route('login') }}" class="text-decoration-none fw-bold">
                        Inicia sesión aquí
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>