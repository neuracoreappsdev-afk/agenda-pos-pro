<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creator Dashboard | Master Admin</title>
    <!-- Mismas fuentes que el Admin -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind (CDN por simplicidad como en admin) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --bg-main: #0f172a;
            --bg-sidebar: #1e293b;
            --bg-header: #1e293b;
            --border-color: rgba(255,255,255,0.1);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-tertiary: #64748b;
            --accent-blue: #38bdf8; 
            --accent-purple: #8b5cf6;
            --hover-bg: rgba(255,255,255,0.05);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
        }

        /* Sidebar similar al Admin */
        .creator-sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .brand-section {
            padding: 0 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--text-primary); /* Negro como el logo admin */
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--text-primary);
            background: var(--hover-bg);
        }

        .nav-item.active {
            border-left-color: var(--text-primary);
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        /* Main Area */
        .creator-main {
            margin-left: 260px;
            padding: 32px;
            min-height: 100vh;
        }

        /* Cards estilo Admin Premium Dark */
        .std-card {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }
        
        .std-card:hover {
            border-color: var(--accent-blue);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--accent-blue);
            color: #0f172a;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 700;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* Badge Styles */
        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-success { background: rgba(74, 222, 128, 0.1); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.2); }
        .badge-warning { background: rgba(251, 191, 36, 0.1); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.2); }
        .badge-danger { background: rgba(248, 113, 113, 0.1); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.2); }
        .badge-info { background: rgba(56, 189, 248, 0.1); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2); }

        .lucide { width: 18px; height: 18px; }
    </style>
</head>
<body>

    <aside class="creator-sidebar" style="background:var(--bg-sidebar); border-right:1px solid rgba(255,255,255,0.05);">
        <div class="brand-section" style="margin-bottom:40px;">
            <div class="brand-icon" style="background:var(--accent-blue); color:#0f172a; box-shadow: 0 0 15px rgba(56, 189, 248, 0.3);">
                <i data-lucide="zap" style="width:20px; height:20px;"></i>
            </div>
            <div>
                <div style="font-weight:800; font-size:16px; letter-spacing:-0.5px; color:white;">MASTER HUB</div>
                <div style="font-size:10px; color:var(--accent-blue); font-weight:700; text-transform:uppercase; letter-spacing:1px;">Core Engine v4.0</div>
            </div>
        </div>

        <nav style="display:flex; flex-direction:column; gap:4px; padding:0 12px;">
            <div style="padding: 0 12px; margin-bottom: 8px; font-size: 10px; font-weight:800; color:var(--text-tertiary); text-transform: uppercase; letter-spacing:1.5px;">
                Infraestructura
            </div>
            <a href="{{ url('creator/dashboard') }}" class="nav-item {{ Request::is('creator/dashboard') ? 'active' : '' }}" style="border-radius:12px; border:none; margin-bottom:4px;">
                <i data-lucide="activity"></i> Centro de Mando
            </a>
            <a href="#" class="nav-item" style="border-radius:12px; margin-bottom:4px;">
                <i data-lucide="server"></i> Gestión de Nodos
            </a>
            
            <div style="padding: 0 12px; margin-top: 24px; margin-bottom: 8px; font-size: 10px; font-weight:800; color:var(--text-tertiary); text-transform: uppercase; letter-spacing:1.5px;">
                SaaS & Billing
            </div>
            <a href="{{ url('creator/support') }}" class="nav-item {{ Request::is('creator/support*') ? 'active' : '' }}" style="border-radius:12px; margin-bottom:4px;">
                <i data-lucide="life-buoy"></i> Soporte Global
            </a>
            <a href="#" class="nav-item" style="border-radius:12px; margin-bottom:4px;">
                <i data-lucide="credit-card"></i> Revenue Global
            </a>
            <a href="#" class="nav-item" style="border-radius:12px; margin-bottom:4px;">
                <i data-lucide="terminal"></i> Logs del Sistema
            </a>

            <div style="margin-top:auto; padding:12px;">
                <a href="{{ url('admin/dashboard') }}" class="nav-item" style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.05); border-radius:12px; justify-content:center; color:white; font-weight:700;">
                    <i data-lucide="chevron-left"></i> Panel Admin
                </a>
            </div>
        </nav>
    </aside>

    <main class="creator-main">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
