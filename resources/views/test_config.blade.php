<!DOCTYPE html>
<html>
<head>
    <title>Test Configuration Navigation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-link {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #f0f0f0;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }
        .test-link:hover {
            background: #e0e0e0;
        }
        .success { background: #d4edda !important; }
        .error { background: #f8d7da !important; }
    </style>
</head>
<body>
    <h1>Prueba de Navegación - Configuración</h1>
    <p>Click en cada enlace para probar:</p>
    
    <a href="{{ url('admin/configuration') }}" class="test-link" target="_blank">1. Página Principal de Configuración</a>
    <a href="{{ url('admin/configuration/detalles-negocio') }}" class="test-link" target="_blank">2. Detalles del Negocio</a>
    <a href="{{ url('admin/configuration/horarios-festivos') }}" class="test-link" target="_blank">3. Horarios y Festivos</a>
    <a href="{{ url('admin/configuration/formas-pago') }}" class="test-link" target="_blank">4. Formas de Pago</a>
    <a href="{{ url('admin/configuration/comisiones') }}" class="test-link" target="_blank">5. Comisiones</a>
    
    <h2>Estado</h2>
    <p>Si algún enlacemuestra error 404 o 500, reporta el número.</p>
</body>
</html>
