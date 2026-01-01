<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0 20px 0;
        }
        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .appointment-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-icon {
            font-size: 20px;
            width: 30px;
            flex-shrink: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 120px;
        }
        .detail-value {
            color: #212529;
            flex: 1;
        }
        .highlight-box {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .highlight-box .date {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .highlight-box .time {
            font-size: 24px;
            color: #764ba2;
            font-weight: 600;
        }
        .alert-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #dee2e6;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon">🏥</div>
            <h1>Sistema de Citas Médicas</h1>
            <p>Notificación de Cita</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 style="color: #333; margin-bottom: 15px;">{{ $subject }}</h2>
            
            <!-- Status Badge -->
            <div>
                <span class="status-badge status-{{ $appointment->status }}">
                    @if($appointment->status == 'confirmed')
                        ✅ CONFIRMADA
                    @elseif($appointment->status == 'cancelled')
                        ❌ CANCELADA
                    @elseif($appointment->status == 'pending')
                        ⏳ PENDIENTE
                    @else
                        {{ strtoupper($appointment->status) }}
                    @endif
                </span>
            </div>

            <p style="font-size: 16px; margin: 15px 0;">{{ $emailMessage ?? 'Detalles de su cita médica' }}</p>

            <!-- Fecha y Hora Destacada -->
            <div class="highlight-box">
                <div style="font-size: 14px; color: #6c757d; margin-bottom: 10px;">📅 FECHA Y HORA DE LA CITA</div>
                <div class="date">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </div>
                <div class="time">
                    🕐 {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}
                </div>
            </div>

            <!-- Detalles de la Cita -->
            <div class="appointment-card">
                <h3 style="color: #667eea; margin-bottom: 15px;">📋 Detalles de la Cita</h3>
                
                <div class="detail-row">
                    <span class="detail-icon">👤</span>
                    <span class="detail-label">Paciente:</span>
                    <span class="detail-value">{{ $appointment->patient->user->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-icon">👨‍⚕️</span>
                    <span class="detail-label">Doctor:</span>
                    <span class="detail-value">Dr. {{ $appointment->doctor->user->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-icon">🩺</span>
                    <span class="detail-label">Especialidad:</span>
                    <span class="detail-value">{{ $appointment->doctor->especialidad }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-icon">📍</span>
                    <span class="detail-label">Estado:</span>
                    <span class="detail-value">
                        @if($appointment->status == 'confirmed')
                            <strong style="color: #28a745;">Confirmada</strong>
                        @elseif($appointment->status == 'cancelled')
                            <strong style="color: #dc3545;">Cancelada</strong>
                        @elseif($appointment->status == 'pending')
                            <strong style="color: #ffc107;">Pendiente de confirmación</strong>
                        @else
                            {{ ucfirst($appointment->status) }}
                        @endif
                    </span>
                </div>

                @if($appointment->motivo)
                <div class="detail-row">
                    <span class="detail-icon">💬</span>
                    <span class="detail-label">Motivo:</span>
                    <span class="detail-value">{{ $appointment->motivo }}</span>
                </div>
                @endif

                <div class="detail-row">
                    <span class="detail-icon">🆔</span>
                    <span class="detail-label">ID de Cita:</span>
                    <span class="detail-value">#{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <!-- Información Adicional según Estado -->
            @if($appointment->status == 'confirmed')
            <div class="alert-box" style="background-color: #d4edda; border-left-color: #28a745;">
                <strong>✅ Su cita ha sido confirmada</strong><br>
                Por favor, llegue 10 minutos antes de su cita. Si necesita cancelar o reprogramar, 
                hágalo con al menos 24 horas de anticipación.
            </div>
            @elseif($appointment->status == 'cancelled')
            <div class="alert-box" style="background-color: #f8d7da; border-left-color: #dc3545;">
                <strong>❌ Esta cita ha sido cancelada</strong><br>
                Si desea agendar una nueva cita, por favor ingrese al sistema o contacte con nosotros.
            </div>
            @elseif($appointment->status == 'pending')
            <div class="alert-box">
                <strong>⏳ Su cita está pendiente de confirmación</strong><br>
                El doctor revisará su solicitud y le notificaremos cuando sea confirmada.
            </div>
            @endif

            <!-- Recordatorio -->
            <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 8px;">
                <p style="margin: 0; font-size: 14px; color: #6c757d;">
                    <strong>💡 Recordatorio:</strong> Puede gestionar sus citas ingresando al sistema 
                    con su usuario y contraseña.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin-bottom: 10px;">Este es un correo automático, por favor no responder directamente.</p>
            <p style="margin-bottom: 10px;">Si tiene alguna consulta, contacte con nosotros a través del sistema.</p>
            <hr style="border: none; border-top: 1px solid #dee2e6; margin: 15px 0;">
            <p>© {{ date('Y') }} Sistema de Citas Médicas</p>
            <p>Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>