<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Cambiar PIN - Portal Colaborador</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --bg: #f8fafc;
            --text: #1e293b;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .change-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            background: #eff6ff;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        h1 { font-size: 24px; font-weight: 800; margin-bottom: 10px; color: #0f172a; }
        p { font-size: 15px; color: #64748b; margin-bottom: 30px; line-height: 1.5; }

        .form-group { text-align: left; margin-bottom: 20px; }
        .label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px; margin-left: 4px; }
        
        .input-wrapper { position: relative; }
        .input {
            width: 100%;
            padding: 16px;
            background: #f1f5f9;
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            text-align: center;
            letter-spacing: 4px;
            transition: all 0.3s;
        }
        .input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }
        .btn-submit:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .error-alert {
            background: #fef2f2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }

        .info-alert {
            background: #eff6ff;
            color: #1e40af;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            border: 1px solid #bfdbfe;
        }
    </style>
</head>
<body>

<div class="change-card">
    <div class="icon-wrapper">🛡️</div>
    <h1>Actualiza tu PIN</h1>
    <p>Por seguridad, debes cambiar tu clave temporal por una nueva de 4 dígitos.</p>

    @if(session('info'))
        <div class="info-alert">{{ session('info') }}</div>
    @endif

    @if($errors->any())
        <div class="error-alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('colaborador/cambiar-pin') }}">
        {!! csrf_field() !!}

        <div class="form-group">
            <label class="label">NUEVO PIN (4 DÍGITOS)</label>
            <div class="input-wrapper">
                <input type="password" name="pin" class="input" maxlength="4" pattern="\d*" inputmode="numeric" required placeholder="••••">
            </div>
        </div>

        <div class="form-group">
            <label class="label">CONFIRMA TU NUEVO PIN</label>
            <div class="input-wrapper">
                <input type="password" name="pin_confirmation" class="input" maxlength="4" pattern="\d*" inputmode="numeric" required placeholder="••••">
            </div>
        </div>

        <button type="submit" class="btn-submit">Actualizar y Entrar</button>
    </form>
</div>

</body>
</html>
