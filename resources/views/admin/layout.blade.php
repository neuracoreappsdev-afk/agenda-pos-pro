<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lina Lucio · Agenda Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', explode(',', session('app_branding.font_family', 'Inter'))[0]) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #ffffff;
            --bg-panel: #f7f7f8; /* ChatGPT sidebar gray */
            --bg-header: #ffffff;
            --border-subtle: #e5e5e5;
            --border-strong: #d9d9d9;
            --text-main: #000000;
            --text-muted: #666666;
            --accent: {{ session('app_branding.primary_color', '#000000') }};
            --accent-hover: {{ session('app_branding.secondary_color', '#333333') }};
            --radius-lg: 12px;
            --radius-md: 8px;
            --radius-sm: 6px;
            --shadow-soft: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "{{ session('app_branding.font_family', 'Inter, sans-serif') }}";
            background: var(--bg-main);
            color: var(--text-main);
            font-size: 14px;
            line-height: 1.5;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .app-header {
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-subtle);
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .app-header-inner {
            width: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .app-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: -0.02em;
        }

        .app-logo-mark {
            width: 24px;
            height: 24px;
            background: var(--accent);
            color: #fff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: var(--bg-panel);
        }

        .app-shell {
            flex: 1;
            padding: 32px 24px;
            display: flex;
            justify-content: center;
            background: var(--bg-main);
        }

        .app-main {
            width: 100%;
            max-width: 1200px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 13px;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff;
            border: 1px solid var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            border-color: var(--accent-hover);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-subtle);
        }

        .btn-outline:hover {
            background: var(--bg-panel);
            border-color: var(--border-strong);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 4px;
            background: var(--bg-panel);
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 500;
            border: 1px solid var(--border-subtle);
        }

        @media (max-width: 640px) {
            .app-header-inner {
                flex-direction: column;
                align-items: flex-start;
                height: auto;
                padding: 12px 0;
            }
            .app-header {
                height: auto;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
<header class="app-header">
    <div class="app-header-inner">
        <div class="app-logo">
            <div class="app-logo-mark">LL</div>
            <div>
                {{ \App\Models\BusinessSetting::getValue('company_name', 'Lina Lucio') }}
            </div>
        </div>
        <nav style="display: flex; gap: 8px;">
            <a href="{{ url('admin/appointments') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('admin/appointments') ? 'active' : '' }}">Citas</a>
            <a href="{{ url('admin/waitlist') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('admin/waitlist') ? 'active' : '' }}">Lista de Espera</a>
            <a href="{{ url('admin/packages') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('admin/packages') ? 'active' : '' }}">Servicios</a>
            <a href="{{ url('admin/turnos') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('admin/turnos') ? 'active' : '' }}">Turnos</a>
            <a href="{{ url('admin/configuration') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('admin/configuration') ? 'active' : '' }}">Configuración</a>
        </nav>
        <div style="display: flex; align-items: center; gap: 12px;">
            <span class="chip">Admin</span>
            <a href="{{ url('booking') }}" target="_blank" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Ver Web</a>
        </div>
    </div>
</header>

<div class="app-shell">
    <main class="app-main">
        @yield('content')
    </main>
</div>

<footer class="app-footer">
    Lina Lucio Spa de Cejas · Agenda creada con amor y tecnología.
</footer>

@stack('scripts')
</body>
</html>
