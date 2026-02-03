<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background-color: #f8f8f8; padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
        .content { padding: 20px; }
        .footer { font-size: 0.8em; color: #777; text-align: center; margin-top: 20px; }
        .highlight { color: #2563eb; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Actualización de tu Cita</h2>
        </div>
        <div class="content">
            <p>Hola <strong>{{ $customerName }}</strong>,</p>
            <p>Te informamos que tu cita ha sido reprogramada con éxito.</p>
            <p><strong>Nuevos Detalles:</strong></p>
            <ul>
                <li><strong>Servicio:</strong> {{ $serviceName }}</li>
                <li><strong>Fecha:</strong> {{ $newDate }}</li>
                <li><strong>Hora:</strong> <span class="highlight">{{ $newTime }}</span></li>
                <li><strong>Especialista:</strong> {{ $specialistName }}</li>
            </ul>
            <p>¡Gracias por confiar en <strong>{{ $businessName }}</strong>!</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
