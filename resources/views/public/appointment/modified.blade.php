<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Modificada - {{ $businessName }}</title>
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
            max-width: 440px;
            width: 100%;
            text-align: center;
            overflow: hidden;
            border: 1px solid #2a2a2a;
        }
        .card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 48px 32px;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            backdrop-filter: blur(10px);
        }
        .title { 
            color: white; 
            font-size: 26px; 
            font-weight: 700; 
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .subtitle { 
            color: rgba(255,255,255,0.85); 
            font-size: 14px; 
        }
        .card-body { 
            padding: 36px 32px; 
        }
        .new-date {
            background: #262626;
            border: 1px solid #10a37f;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 28px;
        }
        .new-date-label { 
            font-size: 11px; 
            color: #10a37f; 
            font-weight: 600; 
            letter-spacing: 1.5px;
            margin-bottom: 8px; 
        }
        .new-date-value { 
            font-size: 18px; 
            font-weight: 700; 
            color: #ffffff;
            line-height: 1.4;
        }
        .message {
            font-size: 14px;
            color: #a3a3a3;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .note {
            background: #262626;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 16px;
            font-size: 13px;
            color: #fbbf24;
            margin-bottom: 28px;
            text-align: left;
        }
        .note strong {
            color: #fcd34d;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 163, 127, 0.25);
        }
        .footer {
            padding: 20px;
            background: #141414;
            border-top: 1px solid #262626;
            font-size: 11px;
            color: #525252;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="success-icon">📅</div>
            <h1 class="title">Cita Reagendada</h1>
            <p class="subtitle">Tu nueva fecha ha sido confirmada</p>
        </div>
        <div class="card-body">
            <?php
                $firstApt = $appointments->first();
                $dateCarbon = \Carbon\Carbon::parse($firstApt->appointment_datetime);
            ?>
            <div class="new-date">
                <div class="new-date-label">NUEVA FECHA Y HORA</div>
                <div class="new-date-value">{{ $dateCarbon->format('l, d M Y') }}</div>
                <div class="new-date-value" style="font-size: 24px; margin-top: 4px;">{{ $dateCarbon->format('h:i A') }}</div>
            </div>
            
            <p class="message">
                Tu cita ha sido modificada exitosamente. El horario anterior está disponible nuevamente.
            </p>
            
            <div class="note">
                ⏳ <strong>Estado: Pendiente de Confirmación</strong><br>
                Por favor confirma tu nueva cita para asegurar tu reserva.
            </div>
            
            <a href="{{ url('cita/' . $token) }}" class="btn">Ver y Confirmar Cita</a>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ $businessName }}
        </div>
    </div>
</body>
</html>
