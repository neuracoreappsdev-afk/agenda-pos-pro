<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Cita - {{ $businessName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0d0d0d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: #1a1a1a;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #2a2a2a;
        }

        .card-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #262626 100%);
            padding: 32px 28px 28px;
            text-align: center;
            border-bottom: 1px solid #333;
        }

        .business-logo {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%);
            border-radius: 18px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            font-weight: 700;
            color: white;
            box-shadow: 0 8px 24px rgba(16, 163, 127, 0.3);
        }

        .business-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
        }

        .business-name {
            color: #ffffff;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .card-title {
            color: #737373;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 16px;
        }

        .status-pendiente { background: rgba(251, 191, 36, 0.15); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }
        .status-confirmada { background: rgba(16, 163, 127, 0.15); color: #10a37f; border: 1px solid rgba(16, 163, 127, 0.3); }
        .status-cancelada { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
        .status-completada { background: rgba(59, 130, 246, 0.15); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); }

        .card-body {
            padding: 28px;
        }

        .section-title {
            font-size: 10px;
            color: #737373;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 14px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #262626;
        }

        .info-row:last-child { border-bottom: none; }

        .info-icon {
            width: 42px;
            height: 42px;
            background: #262626;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-content { flex: 1; }

        .info-label {
            font-size: 11px;
            color: #737373;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
            color: #e5e5e5;
            margin-top: 2px;
        }

        .services-section {
            margin-top: 28px;
        }

        .service-item {
            background: #262626;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 10px;
            border-left: 3px solid #10a37f;
        }

        .service-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 15px;
        }

        .service-meta {
            font-size: 12px;
            color: #a3a3a3;
            margin-top: 6px;
        }

        .service-specialist {
            font-size: 12px;
            color: #10a37f;
            margin-top: 8px;
            font-weight: 500;
        }

        .actions-section {
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px dashed #333;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px 20px;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-confirm {
            background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%);
            color: white;
        }

        .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 163, 127, 0.25);
        }

        .btn-modify {
            background: #262626;
            color: #3b82f6;
            border: 1px solid #404040;
        }

        .btn-modify:hover {
            background: #333333;
            border-color: #3b82f6;
        }

        .btn-cancel {
            background: transparent;
            color: #ef4444;
            border: 1px solid #333;
        }

        .btn-cancel:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: #ef4444;
        }

        .footer {
            text-align: center;
            padding: 20px 28px;
            background: #141414;
            border-top: 1px solid #262626;
            font-size: 12px;
            color: #525252;
        }

        .footer a {
            color: #10a37f;
            text-decoration: none;
        }

        .footer p {
            margin-bottom: 6px;
        }

        /* Alerta de Conflicto */
        .conflict-alert {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 16px;
            padding: 24px;
            margin-top: 28px;
            text-align: center;
        }

        .conflict-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .conflict-title {
            color: #92400e;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .conflict-message {
            color: #78350f;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .conflict-details {
            background: rgba(245, 158, 11, 0.15);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #92400e;
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
        }

        .btn-whatsapp svg {
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="business-logo">
                @if($businessLogo)
                    <img src="{{ url($businessLogo) }}" alt="{{ $businessName }}">
                @else
                    {{ substr($businessName, 0, 2) }}
                @endif
            </div>
            <div class="business-name">{{ $businessName }}</div>
            <div class="card-title">Confirmación de Cita</div>
            
            @php
                $status = $appointments->first()->estatus ?? 'pendiente';
                $statusLabels = [
                    'pendiente' => '⏳ Pendiente de Confirmación',
                    'confirmada' => '✓ Cita Confirmada',
                    'cancelada' => '✕ Cita Cancelada',
                    'completada' => '✓ Cita Completada'
                ];
            @endphp
            <span class="status-badge status-{{ $status }}">{{ $statusLabels[$status] ?? $status }}</span>
        </div>

        <div class="card-body">
            <!-- Cliente -->
            <div class="section-title">Cliente</div>
            <div class="info-row">
                <div class="info-icon">👤</div>
                <div class="info-content">
                    <div class="info-label">Nombre</div>
                    <div class="info-value">{{ $customer->customer_first_name ?? '' }} {{ $customer->customer_last_name ?? '' }}</div>
                </div>
            </div>

            <!-- Fecha -->
            <?php
                $firstApt = $appointments->first();
                $dateCarbon = \Carbon\Carbon::parse($firstApt->appointment_datetime);
            ?>
            <div class="info-row">
                <div class="info-icon">📅</div>
                <div class="info-content">
                    <div class="info-label">Fecha</div>
                    <div class="info-value">{{ $dateCarbon->format('l, d \d\e F \d\e Y') }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">🕐</div>
                <div class="info-content">
                    <div class="info-label">Hora de inicio</div>
                    <div class="info-value">{{ $dateCarbon->format('h:i A') }}</div>
                </div>
            </div>

            <!-- Servicios -->
            <div class="services-section">
                <div class="section-title">Servicios Agendados ({{ count($appointments) }})</div>
                
                @foreach($appointments as $apt)
                <div class="service-item">
                    <div class="service-name">{{ $apt->package->package_name ?? 'Servicio' }}</div>
                    <div class="service-meta">
                        ⏱️ {{ $apt->package->package_time ?? 60 }} min · 
                        💰 ${{ number_format($apt->package->package_price ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="service-specialist">
                        Con {{ $apt->specialist->name ?? 'Especialista' }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Alerta de Conflicto de Horarios -->
            @if(isset($conflicts) && count($conflicts) > 0)
            <div class="conflict-alert">
                <div class="conflict-icon">⚠️</div>
                <div class="conflict-title">¡Conflicto de Horario Detectado!</div>
                <div class="conflict-message">
                    Lo sentimos mucho, no podrás tomar este servicio porque ya existe otra cita confirmada y se nos cruzaría el horario, pero puedes comunicarte directamente con nosotros para que podamos mirar opciones y puedan ser atendidos.
                </div>
                <div class="conflict-details">
                    @foreach($conflicts as $conflict)
                    <div style="margin-bottom: 8px;">
                        📋 <strong>{{ $conflict['other_service'] }}</strong> - {{ $conflict['other_date'] }} ({{ $conflict['other_time'] }})
                    </div>
                    @endforeach
                </div>
                @php
                    $whatsappNumber = preg_replace('/[^0-9]/', '', $businessWhatsapp ?? $businessPhone ?? '');
                    $whatsappMessage = urlencode("Hola, el motivo de mi mensaje es para mirar la posibilidad de que todos mis servicios puedan ser atendidos.");
                @endphp
                @if($whatsappNumber)
                <a href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}" target="_blank" class="btn-whatsapp">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Contactar por WhatsApp
                </a>
                @endif
            </div>
            @endif

            <!-- Acciones -->
            @if($status === 'pendiente')
            <div class="actions-section">
                <div class="section-title">Gestionar tu cita</div>
                <a href="{{ url('cita/confirmar/' . $token) }}" class="btn btn-confirm">
                    ✓ Confirmar mi cita
                </a>
                <a href="{{ url('cita/modificar/' . $token) }}" class="btn btn-modify">
                    ✏️ Modificar fecha/hora
                </a>
                <a href="{{ url('cita/cancelar/' . $token) }}" class="btn btn-cancel" onclick="return confirm('¿Estás seguro de cancelar tu cita?')">
                    ✕ Cancelar cita
                </a>
            </div>
            @elseif($status === 'confirmada')
            <div class="actions-section">
                <div class="section-title">¿Necesitas cambiar algo?</div>
                <a href="{{ url('cita/modificar/' . $token) }}" class="btn btn-modify">
                    ✏️ Modificar fecha/hora
                </a>
                <a href="{{ url('cita/cancelar/' . $token) }}" class="btn btn-cancel" onclick="return confirm('¿Estás seguro de cancelar tu cita?')">
                    ✕ Cancelar cita
                </a>
            </div>
            @endif
        </div>

        <div class="footer">
            @if($businessPhone)
            <p>📞 ¿Preguntas? <a href="tel:{{ $businessPhone }}">{{ $businessPhone }}</a></p>
            @endif
            @if($businessAddress)
            <p>📍 {{ $businessAddress }}</p>
            @endif
            <p style="margin-top: 12px; font-size: 11px; color: #404040;">© {{ date('Y') }} {{ $businessName }}</p>
        </div>
    </div>
</body>
</html>
