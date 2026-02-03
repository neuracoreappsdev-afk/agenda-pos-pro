<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a tu Agenda Móvil</title>
    <style>
        body { font-family: 'Outfit', sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .header { background: linear-gradient(135deg, #2563eb, #8b5cf6); color: white; padding: 40px 20px; text-align: center; }
        .content { padding: 40px 30px; }
        .pin-box { background-color: #f1f5f9; border-radius: 16px; padding: 30px; text-align: center; margin: 30px 0; border: 2px dashed #cbd5e1; }
        .pin-code { font-size: 32px; font-weight: 800; letter-spacing: 5px; color: #2563eb; }
        .btn { display: inline-block; padding: 16px 32px; background-color: #2563eb; color: white !important; text-decoration: none; border-radius: 12px; font-weight: 700; margin-top: 20px; text-align: center; }
        .footer { text-align: center; padding: 30px; font-size: 13px; color: #64748b; }
        .warning { font-size: 12px; color: #ef4444; font-weight: 600; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Hola, {{ $specialistName }}!</h1>
            <p>Se ha habilitado tu acceso a la Agenda Móvil</p>
        </div>
        <div class="content">
            <p>Bienvenido al equipo de <strong>{{ $businessName }}</strong>. Ahora puedes gestionar tus citas, ver tus comisiones y controlar tu agenda desde cualquier lugar.</p>
            
            <p>Tus credenciales de acceso son:</p>
            <ul>
                <li><strong>Usuario:</strong> {{ $email }}</li>
                <li><strong>URL de Acceso:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></li>
            </ul>

            <div class="pin-box">
                <p style="margin-bottom: 15px; color: #64748b; font-weight: 600;">TU CLAVE TEMPORAL</p>
                <div class="pin-code">{{ $pin }}</div>
                <p class="warning">⚠️ Deberás cambiar esta clave al ingresar por primera vez.</p>
            </div>

            <p style="text-align: center;">
                <a href="{{ $loginUrl }}" class="btn">Ingresar a mi Agenda</a>
            </p>

            <p style="margin-top: 30px; font-size: 14px; color: #64748b;">
                Si tienes problemas para ingresar, contacta al administrador del sistema.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $businessName }} PRO. Versión Móvil Especialistas.
        </div>
    </div>
</body>
</html>
