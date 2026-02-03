<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>AgendaPOS PRO · Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f9fafb; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); width: 100%; max-width: 400px; border: 1px solid #e5e7eb; }
        .logo { text-align: center; font-weight: 700; font-size: 24px; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        .label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
        .input { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; }
        .btn { width: 100%; padding: 14px; background: #000; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
        .error { color: #991b1b; background: #fef2f2; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #fecaca; }
        .footer { text-align: center; margin-top: 20px; font-size: 14px; }
        .footer a { color: #6366f1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Registrar Cuenta</div>
        
        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('admin/register') }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <label class="label">Nombre de Usuario</label>
                <input type="text" name="username" class="input" required>
            </div>
            <div class="form-group">
                <label class="label">Correo Electrónico</label>
                <input type="email" name="email" class="input" required>
            </div>
            <div class="form-group">
                <label class="label">Contraseña</label>
                <input type="password" name="password" class="input" required>
            </div>
            <button type="submit" class="btn">Crear Cuenta</button>
        </form>
        
        <div class="footer">
            ¿Ya tiene cuenta? <a href="{{ url('admin/login') }}">Iniciar Sesión</a>
        </div>
    </div>
</body>
</html>
