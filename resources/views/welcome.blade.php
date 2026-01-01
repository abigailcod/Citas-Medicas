<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedicCitas - Sistema de Citas Médicas</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 800px;
            height: 800px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Navbar */
        .navbar-welcome {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 1.5rem 0;
            z-index: 100;
        }

        .navbar-welcome .navbar-brand {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-welcome .navbar-brand i {
            font-size: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .navbar-welcome .btn-login,
        .navbar-welcome .btn-register {
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 1rem;
        }

        .navbar-welcome .btn-login {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
        }

        .navbar-welcome .btn-login:hover {
            background: white;
            color: #1e88e5;
            transform: translateY(-2px);
        }

        .navbar-welcome .btn-register {
            background: white;
            color: #1e88e5;
            border: 2px solid white;
        }

        .navbar-welcome .btn-register:hover {
            background: transparent;
            color: white;
            transform: translateY(-2px);
        }

        /* Hero Content */
        .hero-content {
            position: relative;
            z-index: 10;
            color: white;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 4rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
            animation: fadeInUp 1.2s ease;
        }

        .hero-content .cta-buttons {
            animation: fadeInUp 1.4s ease;
        }

        .hero-content .btn-primary-custom {
            padding: 1rem 3rem;
            font-size: 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            background: white;
            color: #1e88e5;
            border: none;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .hero-content .btn-primary-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
        }

        .hero-content .btn-secondary-custom {
            padding: 1rem 3rem;
            font-size: 1.2rem;
            border-radius: 50px;
            font-weight: 600;
            background: transparent;
            color: white;
            border: 3px solid white;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .hero-content .btn-secondary-custom:hover {
            background: white;
            color: #1e88e5;
            transform: translateY(-5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-card i {
            font-size: 3.5rem;
            color: #1e88e5;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.8;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-content p {
                font-size: 1.2rem;
            }

            .hero-content .btn-primary-custom,
            .hero-content .btn-secondary-custom {
                padding: 0.8rem 2rem;
                font-size: 1rem;
                display: block;
                margin: 1rem auto;
                width: 80%;
            }

            .navbar-welcome .btn-login,
            .navbar-welcome .btn-register {
                margin: 0.5rem 0;
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-welcome">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a href="/" class="navbar-brand">
                    <i class="fas fa-heartbeat"></i>
                    MedicCitas
                </a>
                <div class="d-flex align-items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-register">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register">
                            <i class="fas fa-user-plus me-2"></i>Registrarse
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>
                    <i class="fas fa-heartbeat"></i> MedicCitas
                </h1>
                <p>Sistema integral de gestión de citas médicas</p>
                <p class="mb-4">Agenda, gestiona y controla todas tus citas médicas en un solo lugar</p>
                
                <div class="cta-buttons">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary-custom">
                            <i class="fas fa-home me-2"></i>Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary-custom">
                            <i class="fas fa-user-plus me-2"></i>Comenzar Gratis
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-secondary-custom">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">¿Por qué elegir MedicCitas?</h2>
                <p class="text-muted fs-5">Una solución completa para la gestión de citas médicas</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Gestión de Citas</h3>
                        <p>Agenda, modifica y cancela citas de forma rápida y sencilla. Control total sobre tu agenda médica.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-bell"></i>
                        <h3>Notificaciones en Tiempo Real</h3>
                        <p>Recibe alertas instantáneas sobre tus citas, cambios de estado y recordatorios importantes.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-user-md"></i>
                        <h3>Gestión de Médicos</h3>
                        <p>Administra fácilmente información de médicos, especialidades y disponibilidad.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-users"></i>
                        <h3>Control de Pacientes</h3>
                        <p>Mantén un registro completo y organizado de todos tus pacientes.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Seguridad Total</h3>
                        <p>Protección de datos con autenticación segura y roles de usuario bien definidos.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="feature-card">
                        <i class="fas fa-database"></i>
                        <h3>Backups Automáticos</h3>
                        <p>Tu información siempre segura con respaldos automáticos de la base de datos.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-2">
                <i class="fas fa-heartbeat"></i> MedicCitas © {{ date('Y') }}
            </p>
            <small>Sistema de Gestión de Citas Médicas | Cuidando tu salud con tecnología</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>