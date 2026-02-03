<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Cancelada - {{ $businessName }}</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            padding: 48px 32px;
        }
        .cancel-icon {
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
        .business-name {
            font-size: 13px;
            font-weight: 600;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 24px;
        }
        .message {
            font-size: 15px;
            color: #a3a3a3;
            line-height: 1.7;
            margin-bottom: 28px;
        }
        .note {
            background: #262626;
            border: 1px solid #404040;
            border-radius: 12px;
            padding: 18px;
            font-size: 13px;
            color: #a3a3a3;
            margin-bottom: 28px;
            text-align: left;
        }
        .note strong {
            color: #e5e5e5;
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
            <div class="cancel-icon">✕</div>
            <h1 class="title">Cita Cancelada</h1>
            <p class="subtitle">Tu reserva ha sido cancelada correctamente</p>
        </div>
        <div class="card-body">
            <div class="business-name">{{ $businessName }}</div>
            <p class="message">
                Tu cita ha sido cancelada. El horario que tenías reservado ahora está disponible para otros clientes.
            </p>
            <div class="note">
                💡 <strong>¿Cambiaste de opinión?</strong><br>
                Puedes agendar una nueva cita cuando gustes.
            </div>
            <a href="{{ url('booking') }}" class="btn">Agendar nueva cita</a>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ $businessName }}
        </div>
    </div>
</body>
</html>
