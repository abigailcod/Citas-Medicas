<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Médico</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 900px;
            display: flex;
        }
        
        .login-left {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            padding: 60px 40px;
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .login-left .icon {
            font-size: 5rem;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .login-right {
            padding: 60px 50px;
            flex: 1;
        }
        
        .login-right h2 {
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #1e88e5;
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(30, 136, 229, 0.3);
        }
        
        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
        
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
            }
            .login-left {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Lado Izquierdo (Información) -->
        <div class="login-left">
            <div class="icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h1>Sistema de Citas Médicas</h1>
            <p>Gestiona tus citas médicas de forma rápida y segura. Bienvenido de vuelta.</p>
        </div>

        <!-- Lado Derecho (Formulario) -->
        <div class="login-right">
            <h2><i class="fas fa-sign-in-alt"></i> Iniciar Sesión</h2>

            <!-- Mensajes de error -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Mensaje de sesión -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

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
                               required autofocus>
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
                               placeholder="••••••••" 
                               required>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>

                <!-- Botón -->
                <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                    <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                </button>

                <!-- Enlaces -->
                <div class="text-center">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                    <br>
                    <span class="text-muted">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold">
                        Regístrate aquí
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>