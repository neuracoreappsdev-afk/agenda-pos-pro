<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portal Colaboradores · AgendaPOS PRO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1f2937;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 28px;
            font-weight: 700;
            color: #000;
            letter-spacing: -0.02em;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: #6366f1;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 700;
        }

        .login-card {
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }

        .error-message {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            border: 1px solid #fecaca;
        }

        .success-message {
            background: #f0fdf4;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
            background: #fff;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .footer-note {
            text-align: center;
            margin-top: 32px;
            font-size: 12px;
            color: #9ca3af;
        }

        .help-text {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
        }

        .help-text a {
            color: #6366f1;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo">
                <div class="logo-icon">✨</div>
                <span>AgendaPOS <b>PRO</b></span>
            </div>
            <p style="margin-top: 8px; color: #6b7280; font-size: 14px;">Portal de Colaboradores</p>
        </div>

        <div class="login-card">
            @if(Session::has('error'))
                <div class="error-message">
                    {{ Session::get('error') }}
                </div>
            @endif

            @if(Session::has('info'))
                <div class="success-message">
                    {{ Session::get('info') }}
                </div>
            @endif

            <form method="POST" action="{{ url('colaborador/login') }}">
                {!! csrf_field() !!}
                
                <div class="form-group">
                    <label class="form-label">Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="nombre@ejemplo.com"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label class="form-label">PIN de Acceso</label>
                    <input 
                        type="password" 
                        name="pin" 
                        class="form-input" 
                        placeholder="••••"
                        maxlength="6"
                        required
                    >
                </div>

                <button type="submit" class="btn-submit">
                    Ingresar al Portal
                </button>

                <div class="help-text">
                    ¿No recuerdas tu PIN? <br>
                    <a href="https://wa.me/your-number" target="_blank">Contactar a Soporte</a>
                </div>
            </form>
        </div>

        <div class="footer-note">
            AgendaPOS PRO © 2025 · <a href="{{ url('admin/login') }}" style="color: inherit;">Administración</a>
        </div>
    </div>
</body>
</html>
