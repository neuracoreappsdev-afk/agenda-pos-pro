<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <?php
        $isHealth = isset($isHealth) ? $isHealth : false;
        $isAuto = isset($isAuto) ? $isAuto : false;
        $isOptica = isset($isOptica) ? $isOptica : false;
        $isContador = isset($isContador) ? $isContador : false;
        $isLegal = isset($isLegal) ? $isLegal : false;
        $isRealEstate = isset($isRealEstate) ? $isRealEstate : false;
        $isGym = isset($isGym) ? $isGym : false;
        $isPsico = isset($isPsico) ? $isPsico : false;
        $isOdonto = isset($isOdonto) ? $isOdonto : false;
    ?>
    <title>AgendaPOS PRO · Panel de Control</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        indigo: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            600: '#10a37f',
                            700: '#15803d',
                            800: '#166534',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --bg-main: #ffffff;
            --bg-sidebar: #f9fafb;
            --bg-header: #ffffff;
            --border-color: #e5e7eb;
            --text-primary: #374151;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --accent-blue: #10a37f;
            --hover-bg: #f3f4f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: var(--bg-main);
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.5;
        }

        /* Minimal Mode for Iframe */
        @if(Request::get('minimal'))
        .app-header, .sidebar, #adminChatFAB {
            display: none !important;
        }
        .app-container {
            height: 100vh !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .content-container {
            padding: 0 !important;
        }
        @endif

        /* Header */
        .app-header {
            height: 60px;
            background: var(--bg-header);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 24px;
            position: sticky;
            top: 0;
            z-index: 100;
            justify-content: space-between;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 700;
            color: #000;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .logo-icon {
            width: 10px;
            height: 10px;
            background: #000;
            border-radius: 2px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .business-name {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .date-display {
            font-size: 13px;
            color: var(--text-tertiary);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent-blue);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
        }

        /* Main Layout */
        .app-container {
            display: flex;
            height: calc(100vh - 60px);
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-sidebar);
            border-right: 1px solid var(--border-color);
            overflow-y: auto;
            padding: 16px 0;
            z-index: 1000;
            position: relative;
        }

        .sidebar-section {
            padding: 0 16px;
            margin-bottom: 24px;
        }

        .sidebar-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .menu-item:hover {
            background: var(--hover-bg);
        }

        .menu-item.active {
            background: #ffffff;
            color: var(--accent-blue);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.08);
            border: 1px solid var(--border-color);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background: var(--bg-main);
            overflow-y: auto;
            padding: 24px;
        }

        .welcome-section {
            margin-bottom: 32px;
        }

        .welcome-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .welcome-subtitle {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 24px;
        }

        .tab {
            padding: 12px 16px;
            color: var(--text-secondary);
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }

        .tab:hover {
            color: var(--text-primary);
        }

        .tab.active {
            color: var(--text-primary);
            border-bottom-color: #000;
        }

        /* News Section */
        .news-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .news-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .news-icon {
            font-size: 20px;
        }

        .news-title {
            font-size: 18px;
            font-weight: 600;
        }

        .news-date {
            font-size: 12px;
            color: var(--text-tertiary);
            margin-bottom: 12px;
        }

        .news-content {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .news-link {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .news-link:hover {
            text-decoration: underline;
        }

        /* Quick Access */
        .quick-access-section {
            margin-bottom: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .quick-access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 16px;
        }

        .quick-access-item {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.2s;
            cursor: pointer;
        }

        .quick-access-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            border-color: var(--text-tertiary);
        }

        .quick-access-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        .quick-access-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                position: fixed;
                left: -260px;
                top: 60px;
                height: calc(100vh - 60px);
                z-index: 99;
                transition: left 0.3s;
            }

            .sidebar.open {
                left: 0;
            }

            .quick-access-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }

        @media (max-width: 480px) {
            .quick-access-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        /* Header Right Styles */
    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .location-selector {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #6b7280;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }

    .date-display {
        color: #9ca3af;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .header-icon-btn {
        position: relative;
        cursor: pointer;
        color: #6b7280;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: background 0.2s;
    }
    .header-icon-btn:hover { background-color: #f3f4f6; }

    .notification-container .badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background-color: #ef4444; /* Red */
        color: white;
        font-size: 10px;
        font-weight: bold;
        padding: 2px 5px;
        border-radius: 10px;
        min-width: 15px;
        text-align: center;
        border: 1px solid white;
    }

    .user-profile-circle {
        width: 36px;
        height: 36px;
        background-color: #1a73e8; /* Blue */
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
    }

    /* Submenu Styles */
        .submenu {
            display: none;
            padding-left: 12px;
            background: #f1f2f3;
            border-radius: 8px;
            margin-top: 2px;
            margin-bottom: 2px;
        }

        .has-submenu.open .submenu {
            display: block;
        }
        
        .has-submenu .arrow {
            margin-left: auto;
            font-size: 10px;
            transition: transform 0.2s;
        }

        .has-submenu.open .arrow {
            transform: rotate(180deg);
        }

        /* Hub Styles - OpenAI Style */
        .header-hub-btn {
            background: #10a37f;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            white-space: nowrap;
        }
        .header-hub-btn:hover {
            background: #1a7f64;
            transform: none;
        }

        .hub-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(8px);
            z-index: 100000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .hub-modal {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            border-radius: 32px;
            padding: 48px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.4);
            animation: hubFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        .hub-close {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 24px;
            cursor: pointer;
            color: #94a3b8;
            transition: color 0.2s;
        }
        .hub-close:hover { color: #1e293b; }
        @keyframes hubFadeIn { from { transform: scale(0.9) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
        .hub-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 32px;
        }
        .hub-card {
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            padding: 32px 24px;
            border-radius: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        .hub-card:hover {
            background: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.1);
            transform: translateY(-8px);
        }
        .hub-card.upcoming {
            filter: grayscale(1);
            opacity: 0.7;
            cursor: not-allowed;
            pointer-events: none;
            position: relative;
        }
        .hub-upcoming-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f1f5f9;
            color: #475569;
            font-size: 8px;
            font-weight: 800;
            padding: 4px 8px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .hub-card .hub-icon { font-size: 48px; margin-bottom: 16px; display: block; }
        .hub-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #1e293b; }
        .hub-card p { font-size: 13px; color: #64748b; line-height: 1.5; }

        .submenu {
            display: none;
            padding-left: 28px;
            margin-top: 4px;
        }

        .submenu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
            border-radius: 6px;
            margin-bottom: 2px;
            transition: all 0.2s;
        }

        .submenu-item:hover {
            color: var(--text-primary);
            background: rgba(0,0,0,0.03);
        }

        .submenu-item.active {
            color: var(--accent-blue);
            font-weight: 600;
            background: rgba(59, 130, 246, 0.05);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header class="app-header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <a href="{{ url('admin/dashboard') }}" class="logo">
                <div class="logo-icon"></div>
                <span>AgendaPOS PRO</span>
            </a>
            <span class="business-name">Holguines Trade</span>
        </div>
        
        <?php
            // Cargar datos reales para el header
            try {
                $lowStockProducts = \App\Models\Product::whereRaw('quantity <= min_quantity')->limit(10)->get();
                $notifications = \App\Models\Notification::where('is_read', 0)->orderBy('created_at', 'desc')->limit(10)->get();
                
                // Detección de Vertical
                $businessVertical = \App\Models\BusinessSetting::getValue('business_type', 'belleza');
                $isHealth = ($businessVertical == 'salud');
                $isAuto = ($businessVertical == 'auto');
                $isOptica = ($businessVertical == 'optica');
                $isContador = ($businessVertical == 'contabilidad');
                $isLegal = ($businessVertical == 'legal');
                $isRealEstate = ($businessVertical == 'inmobiliaria');
                $isGym = ($businessVertical == 'gym');
                $isPsico = ($businessVertical == 'psicologia');
                $isOdonto = ($businessVertical == 'odontologia');
            } catch (\Exception $e) {
                $lowStockProducts = collect([]);
                $notifications = collect([]);
                $businessVertical = 'belleza';
                $isHealth = $isAuto = $isOptica = $isContador = $isLegal = $isRealEstate = $isGym = $isPsico = $isOdonto = false;
            }
        ?>
        
        <div class="header-right">
            <span class="date-display">📅 {{ date('Y-m-d') }}</span>
            
            <!-- Icono de Ayuda -->
            <div class="header-icon-btn" title="Ayuda">
                <span style="font-size:18px;">❓</span>
            </div>
            
            <!-- Productos con Existencias Bajas -->
            <div class="header-dropdown" id="stockDropdown">
                <div class="header-icon-btn" onclick="toggleDropdown('stockDropdown')" title="Productos con Existencias Bajas">
                    <span style="font-size:18px;">📦</span>
                    @if($lowStockProducts->count() > 0)
                    <span class="notification-badge">{{ $lowStockProducts->count() }}</span>
                    @endif
                </div>
                <div class="dropdown-panel" id="stockPanel">
                    <div class="dropdown-header">
                        <strong>Productos con Existencias Bajas</strong>
                    </div>
                    <div class="dropdown-content">
                        @if($lowStockProducts->count() > 0)
                            @foreach($lowStockProducts as $product)
                            <div class="stock-item">
                                <div class="stock-name">{{ $product->name }}</div>
                                <div class="stock-details">Cantidad: {{ $product->quantity }} | Cantidad Mínima: {{ $product->min_quantity }}</div>
                                <a href="{{ url('admin/productos') }}" class="stock-action">Agregar Stock</a>
                            </div>
                            @endforeach
                        @else
                            <div style="padding:20px; text-align:center; color:#9ca3af;">
                                <div style="font-size:32px; margin-bottom:10px;">✅</div>
                                <p>No hay productos con stock bajo</p>
                            </div>
                        @endif
                    </div>
                    <div class="dropdown-footer">
                        <a href="{{ url('admin/informes/productos-existencias-bajas') }}">📋 Ver Todas</a>
                    </div>
                </div>
            </div>
            
            <!-- Calendario -->
            <div class="header-icon-btn" onclick="window.location.href='{{ url('admin/appointments') }}'" title="Calendario">
                <span style="font-size:18px;">📅</span>
            </div>
            
            <!-- Configuración -->
            <div class="header-icon-btn" onclick="window.location.href='{{ url('admin/configuration') }}'" title="Configuración">
                <span style="font-size:18px;">⚙️</span>
            </div>
            
            <!-- Notificaciones -->
            <div class="header-dropdown" id="notifDropdown">
                <div class="header-icon-btn" onclick="toggleDropdown('notifDropdown')" title="Notificaciones">
                    <span style="font-size:18px;">🔔</span>
                    @if($notifications->count() > 0)
                    <span class="notification-badge notification-badge-red">{{ $notifications->count() }}</span>
                    @endif
                </div>
                <div class="dropdown-panel" id="notifPanel">
                    <div class="dropdown-header">
                        <strong>Notificaciones</strong>
                    </div>
                    <div class="dropdown-content">
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notif)
                            <div class="notif-item notif-new">
                                <div class="notif-title">{{ $notif->title }}</div>
                                <div class="notif-text">{{ $notif->message }}</div>
                                <div class="notif-time">📅 {{ date('d/m/Y H:i', strtotime($notif->created_at)) }}</div>
                            </div>
                            @endforeach
                        @else
                            <div style="padding:20px; text-align:center; color:#9ca3af;">
                                <div style="font-size:32px; margin-bottom:10px;">🔔</div>
                                <p>No hay notificaciones nuevas</p>
                            </div>
                        @endif
                    </div>
                    <div class="dropdown-footer">
                        <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                            <input type="checkbox" id="notifMarkRead"> Marcar como leídas
                        </label>
                        <a href="{{ url('admin/configuration/listado-notificaciones') }}">Ver Todas</a>
                    </div>
                </div>
            </div>

            <!-- Selector de Vertical -->
            <button class="header-hub-btn" onclick="openVerticalHub()">
                <span>🚀 Cambiar Sector</span>
            </button>
            
            <!-- Avatar de Usuario -->
            <div class="user-avatar" style="cursor:pointer;" onclick="window.location.href='{{ url('admin/configuration/usuarios') }}'">L</div>
        </div>
    </header>
    
    <style>
        /* Header Dropdowns */
        .header-dropdown {
            position: relative;
        }
        .header-icon-btn {
            position: relative;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }
        .header-icon-btn:hover {
            background: #f3f4f6;
        }
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background: #1a73e8;
            color: white;
            font-size: 10px;
            font-weight: 600;
            min-width: 16px;
            height: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }
        .notification-badge-red {
            background: #dc2626;
        }
        
        .dropdown-panel {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            width: 400px; /* Mas ancho para leer bien */
            max-height: 480px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.2);
            margin-top: 12px;
            overflow: hidden;
            z-index: 10000; /* Prioridad máxima */
        }
        .dropdown-panel.show {
            display: block !important;
        }
        
        .dropdown-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        .dropdown-content {
            max-height: 320px;
            overflow-y: auto;
        }
        .dropdown-footer {
            padding: 12px 20px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .dropdown-footer a {
            color: #1a73e8;
            text-decoration: none;
        }
        
        /* Stock Items */
        .stock-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        .stock-item:hover {
            background: #f9fafb;
        }
        .stock-name {
            font-weight: 600;
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .stock-details {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 6px;
        }
        .stock-action {
            font-size: 12px;
            color: #1a73e8;
            text-decoration: none;
        }
        .stock-action:hover {
            text-decoration: underline;
        }
        
        /* Notification Items */
        .notif-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
        }
        .notif-item:hover {
            background: #f9fafb;
        }
        .notif-item.notif-new {
            background: #eff6ff;
            border-left: 3px solid #1a73e8;
        }
        .notif-title {
            font-weight: 600;
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .notif-text {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.5;
            white-space: normal;
            word-break: break-word;
            margin-top: 4px;
        }
        .notif-time {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 6px;
        }
    </style>
    
    <script>
        function toggleDropdown(dropdownId) {
            const panel = document.getElementById(dropdownId.replace('Dropdown', 'Panel'));
            if (!panel) return;
            
            const isShown = panel.classList.contains('show');
            
            // Cerrar todos
            document.querySelectorAll('.dropdown-panel').forEach(p => p.classList.remove('show'));
            
            // Toggle actual
            if (!isShown) {
                panel.classList.add('show');
            }
        }
        
        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.header-dropdown')) {
                document.querySelectorAll('.dropdown-panel').forEach(panel => {
                    panel.classList.remove('show');
                });
            }
        });

        // Marcar notificaciones como leídas
        document.addEventListener('DOMContentLoaded', function() {
            const checkRead = document.getElementById('notifMarkRead');
            if (checkRead) {
                checkRead.addEventListener('change', function() {
                    if (this.checked) {
                        fetch('{{ url("admin/notificaciones/read-all") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Quitar badge rojo
                                const badge = document.querySelector('#notifDropdown .notification-badge');
                                if (badge) badge.style.display = 'none';
                                // Quitar clase 'new' de los items
                                document.querySelectorAll('.notif-item').forEach(el => el.classList.remove('notif-new'));
                            }
                        })
                        .catch(err => console.error(err));
                    }
                });
            }
        });

        function openVerticalHub() {
            document.getElementById('verticalHub').style.display = 'flex';
        }

        function closeVerticalHub() {
            document.getElementById('verticalHub').style.display = 'none';
        }
    </script>

    <!-- Vertical Hub Modal -->
    <div class="hub-overlay" id="verticalHub" onclick="if(event.target == this) closeVerticalHub()">
        <div class="hub-modal">
            <span class="hub-close" onclick="closeVerticalHub()">&times;</span>
            <div style="text-align: center; margin-bottom: 10px;">
                <h2 style="font-size: 28px; font-weight: 800; color: #1e293b;">Centro de Comando Multi-Vertical</h2>
                <p style="color: #64748b;">Selecciona el ecosistema que deseas gestionar en este momento.</p>
            </div>
            <div class="hub-grid">
                <!-- Belleza -->
                <a href="{{ url('admin/test-vertical/belleza') }}" class="hub-card">
                    <span class="hub-icon">💅</span>
                    <h3>SaaS Belleza</h3>
                    <p>Peluquerías, Barberías y Spas.</p>
                </a>
                <!-- Legal -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">⚖️</span>
                    <h3>Abogados (Legal PRO)</h3>
                    <p>Gestión de procesos y expedientes judiciales.</p>
                </a>
                <!-- Inmobiliaria -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">🏠</span>
                    <h3>Inmobiliaria (Real Estate)</h3>
                    <p>Control de inventario de propiedades y ventas.</p>
                </a>
                <!-- Fitness -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">🏋️</span>
                    <h3>Entrenadores Personales (Fitness)</h3>
                    <p>Agendamiento de entrenos y clases personalizadas.</p>
                </a>
                <!-- Contabilidad -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">📑</span>
                    <h3>Auditoría & Finanzas</h3>
                    <p>Contabilidad, finanzas y consultoría corporativa.</p>
                </a>
                <!-- Psicología -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">🧠</span>
                    <h3>Psicología & Terapia</h3>
                    <p>Sesiones individuales y seguimiento clínico.</p>
                </a>
                <!-- Odontología -->
                <a href="javascript:void(0)" class="hub-card upcoming">
                    <span class="hub-upcoming-badge">Próximamente</span>
                    <span class="hub-icon">🦷</span>
                    <h3>Odontología</h3>
                    <p>Historias clínicas, odontogramas y ortodoncia.</p>
                </a>
            </div>
            <div style="margin-top: 32px; text-align: center;">
                <p style="font-size: 13px; color: #94a3b8;">La IA adaptará automáticamente el Dashboard y las sugerencias según tu elección.</p>
            </div>
        </div>
    </div>


    <!-- Main Container -->
    <div class="app-container">
            <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-title">{{ trans('messages.section_main') }}</div>
                <a href="{{ url('admin/dashboard') }}" class="menu-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
                    <span class="menu-icon">🏠</span>
                    <span>@if($isHealth) Panel Médico @elseif($isAuto) Taller Central @elseif($isOptica) Panel Optometría @elseif($isContador) Consola de Auditoría @elseif($isLegal) Despacho Jurídico @elseif($isRealEstate) Panel Inmobiliario @elseif($isGym) Centro de Entrenamiento @elseif($isPsico) Centro Terapéutico @elseif($isOdonto) Clínica Dental @else {{ trans('messages.home') }} @endif</span>
                </a>
                <a href="{{ url('admin/panel-control') }}" class="menu-item {{ Request::is('admin/panel-control*') ? 'active' : '' }}">
                    <span class="menu-icon">📊</span>
                    <span>@if($isHealth) Indicadores Clínicos @elseif($isAuto) Métricas Taller @elseif($isOptica) Análisis de Ventas @elseif($isContador) Métricas de Clientes @elseif($isLegal) Estado de Casos @elseif($isRealEstate) Análisis de Mercado @elseif($isGym) Métricas de Suscriptores @elseif($isPsico) Progreso Terapéutico @elseif($isOdonto) Gestión de Pacientes @else {{ trans('messages.panel_control') }} @endif</span>
                </a>
                <a href="{{ url('admin/crear-factura') }}" class="menu-item {{ Request::is('admin/crear-factura*') || Request::is('admin/caja*') ? 'active' : '' }}">
                    <span class="menu-icon">💰</span>
                    <span>@if($isHealth) Facturación Médica @elseif($isAuto) Facturacion y POS @elseif($isOptica) Punto de Venta @elseif($isContador) Cobro de Honorarios @elseif($isLegal) Cobranza Legal @elseif($isRealEstate) Comisiones y Ventas @elseif($isGym) Suscripciones y Pagos @else {{ trans('messages.cashier') }} @endif</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">@if($isAuto) Agenda de Servicios @elseif($isOptica) Citas de Optometría @elseif($isContador) Agenda de Asesorías @elseif($isLegal) Agenda de Audiencias @elseif($isRealEstate) Agenda de Visitas @elseif($isGym) Horarios de Clases @else {{ trans('messages.section_services') }} @endif</div>
                
                {{-- Waitlist: Solo para Belleza, Barberia, Gym (Clases llenas) y Restaurantes --}}
                @if(!$isOdonto && !$isPsico && !$isContador && !$isLegal && !$isRealEstate && !$isOptica)
                <a href="{{ url('admin/waitlist') }}" class="menu-item {{ Request::is('admin/waitlist*') || Request::is('admin/agenda-staff*') ? 'active' : '' }}">
                    <span class="menu-icon">⏳</span>
                    <span>@if($isGym) Lista de Espera @else {{ trans('messages.waitlist') }} @endif</span>
                </a>
                @endif

                <a href="{{ url('admin/appointments') }}" class="menu-item {{ Request::is('admin/appointments*') ? 'active' : '' }}">
                    <span class="menu-icon">🗓️</span>
                    <span>@if($isAuto) Citas de Taller @elseif($isOptica) Agenda de Exámenes @elseif($isContador) Citas y Auditorías @elseif($isLegal) Audiencias @elseif($isRealEstate) Visitas Guiadas @elseif($isGym) Entrenamientos @else {{ trans('messages.agenda') }} @endif</span>
                </a>
                
                {{-- Turnos/Walk-in: Solo para negocios de flujo rápido (Barbería, Spa, Urgencias Auto) --}}
                @if(!$isOdonto && !$isPsico && !$isContador && !$isLegal && !$isRealEstate && !$isGym)
                <a href="{{ url('admin/turnos') }}" class="menu-item {{ Request::is('admin/turnos*') ? 'active' : '' }}">
                    <span class="menu-icon">⏱️</span>
                    <span>{{ trans('messages.walk_in') }}</span>
                </a>
                @endif
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">@if($isHealth) Gestión Clínica @elseif($isAuto) Gestión de Flota @elseif($isOptica) Gestión Visual @elseif($isOdonto) Gestión Dental @elseif($isContador) Gestión de Empresas @elseif($isLegal) Gestión Jurídica @elseif($isRealEstate) Gestión Inmobiliaria @elseif($isGym) Gestión de Socios @else {{ trans('messages.section_management') }} @endif</div>
                <a href="{{ url('admin/clientes') }}" class="menu-item {{ Request::is('admin/clientes*') ? 'active' : '' }}">
                    <span class="menu-icon">@if($isHealth) 🏥 @elseif($isAuto) 🚗 @elseif($isOptica) 👓 @elseif($isOdonto) 🦷 @elseif($isContador) 🏢 @elseif($isLegal) ⚖️ @elseif($isRealEstate) 🏠 @elseif($isGym) 🏋️ @else 👥 @endif</span>
                    <span>@if($isHealth) Pacientes @elseif($isAuto) Propietarios @elseif($isOptica) Pacientes / Clientes @elseif($isOdonto) Pacientes @elseif($isContador) Empresas Aliadas @elseif($isLegal) Clientes / Poderantes @elseif($isRealEstate) Clientes / Prospectos @elseif($isGym) Socios / Alumnos @else {{ trans('messages.clients') }} @endif</span>
                </a>

                @if($isHealth || $isAuto || $isOptica || $isContador || $isLegal || $isRealEstate || $isGym || $isOdonto)
                <a href="{{ url($isHealth ? 'admin/historias-clinicas' : ($isAuto ? 'admin/historial-vehicular' : ($isOptica ? 'admin/formulas-opticas' : ($isOdonto ? 'admin/historias-clinicas-odontologia' : ($isContador ? 'admin/reportes-contables' : ($isLegal ? 'admin/expedientes-digitales' : ($isRealEstate ? 'admin/fichas-propiedades' : 'admin/fichas-antropometricas'))))))) }}" class="menu-item {{ Request::is('admin/historias-clinicas*') || Request::is('admin/historias-clinicas-odontologia*') || Request::is('admin/historial-vehicular*') || Request::is('admin/formulas-opticas*') || Request::is('admin/reportes-contables*') || Request::is('admin/expedientes-digitales*') || Request::is('admin/fichas-propiedades*') || Request::is('admin/fichas-antropometricas*') ? 'active' : '' }}">
                    <span class="menu-icon">@if($isHealth) 📁 @elseif($isAuto) 🔧 @elseif($isOptica) 👁️ @elseif($isOdonto) 🦷 @elseif($isContador) 📑 @elseif($isLegal) ⚖️ @elseif($isRealEstate) 🔑 @elseif($isGym) 💪 @endif</span>
                    <span>@if($isHealth) Historias Clínicas @elseif($isAuto) Historial Vehicular @elseif($isOptica) Fórmulas Ópticas @elseif($isOdonto) Odontograma Digital @elseif($isContador) Libros y Reportes @elseif($isLegal) Expedientes Digitales @elseif($isRealEstate) Fichas de Propiedad @elseif($isGym) Rutinas y Fichas @endif</span>
                </a>
                @endif
                
                <!-- Ventas con Submenu -->
                <div class="has-submenu {{ Request::is('admin/ventas*') ? 'open' : '' }}" onclick="this.classList.toggle('open')">
                    <a href="javascript:void(0)" class="menu-item">
                        <span class="menu-icon">📈</span>
                        <span>@if($isHealth) Cobros y Planes @elseif($isAuto) Órdenes y Servicios @elseif($isOptica) Ventas y Presupuestos @elseif($isContador) Facturación y Fees @elseif($isLegal) Contratos y Honorarios @elseif($isRealEstate) Ventas y Apartados @elseif($isGym) Membresías y Planes @elseif($isOdonto) Presupuestos @else {{ trans('messages.sales') }} @endif</span>
                        <span class="arrow">▼</span>
                    </a>
                    <div class="submenu" onclick="event.stopPropagation()">
                        <a href="{{ url('admin/ventas/bonos') }}" class="submenu-item {{ Request::is('admin/ventas/bonos*') ? 'active' : '' }}">
                            <span class="submenu-icon">🎫</span>
                            <span>@if($isHealth) Vouchers / Prepago @elseif($isAuto) Bonos de Servicio @elseif($isOptica) Bonos y Promociones @elseif($isContador) Pagos Anticipados @else {{ trans('messages.vouchers_title') }} @endif</span>
                        </a>
                        <a href="{{ url('admin/ventas/planes') }}" class="submenu-item {{ Request::is('admin/ventas/planes*') ? 'active' : '' }}">
                            <span class="submenu-icon">📅</span>
                            <span>@if($isHealth) Planes de Tratamiento @elseif($isAuto) Cotizaciones @elseif($isOptica) Planes de Financiamiento @elseif($isContador) Planes Mensuales @else {{ trans('messages.plans_title') }} @endif</span>
                        </a>
                        <a href="{{ url('admin/ventas/devoluciones') }}" class="submenu-item {{ Request::is('admin/ventas/devoluciones*') ? 'active' : '' }}">
                            <span class="submenu-icon">↩️</span>
                            <span>{{ trans('messages.refunds_returns') }}</span>
                        </a>
                    </div>
                </div>

                <!-- Compras con Submenu -->
                <div class="has-submenu {{ Request::is('admin/compras*') ? 'open' : '' }}" onclick="this.classList.toggle('open')">
                    <a href="javascript:void(0)" class="menu-item">
                        <span class="menu-icon">🛒</span>
                        <span>@if($isHealth) Suministros @elseif($isAuto) Refacciones @elseif($isOptica) Pedidos a Laboratorio @elseif($isContador) Gastos y Egresos @elseif($isLegal) Costos Juridicos @elseif($isRealEstate) Remodelaciones @else {{ trans('messages.purchases') }} @endif</span>
                        <span class="arrow">▼</span>
                    </a>
                    <div class="submenu" onclick="event.stopPropagation()">
                        <a href="{{ url('admin/compras/proveedores') }}" class="submenu-item {{ Request::is('admin/compras/proveedores') ? 'active' : '' }}">
                            <span class="submenu-icon">🏢</span>
                            <span>{{ trans('messages.providers') }}</span>
                        </a>
                        <a href="{{ url('admin/compras/facturas') }}" class="submenu-item {{ Request::is('admin/compras/facturas') ? 'active' : '' }}">
                            <span class="submenu-icon">📄</span>
                            <span>{{ trans('messages.invoices') }}</span>
                        </a>
                    </div>
                </div>

                <a href="{{ url('admin/cuenta-empresa') }}" class="menu-item {{ Request::is('admin/cuenta-empresa*') ? 'active' : '' }}">
                    <span class="menu-icon">🏦</span>
                    <span>{{ trans('messages.income_expense') }}</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">@if($isHealth) Recursos Médicos @elseif($isAuto) Almacén y Taller @elseif($isOptica) Inventario Óptico @elseif($isContador) Activos y Personal @elseif($isLegal) Equipo Jurídico @elseif($isRealEstate) Catálogo Inmuebles @elseif($isPsico) Archivo Terapéutico @elseif($isOdonto) Insumos Dentales @else {{ trans('messages.section_catalog') }} @endif</div>
                <!-- Productos con Submenu -->
                <div class="has-submenu {{ Request::is('admin/productos*') || Request::is('admin/inventario*') || Request::is('admin/traslados*') ? 'open' : '' }}" onclick="this.classList.toggle('open')">
                    <a href="javascript:void(0)" class="menu-item">
                        <span class="menu-icon">📦</span>
                        <span>@if($isHealth) Insumos y Farmacia @elseif($isAuto) Repuestos y Aceites @elseif($isOptica) Monturas y Lentes @elseif($isContador) Suministros Oficina @elseif($isLegal) Biblioteca Legal @elseif($isRealEstate) Propiedades Disponibles @elseif($isPsico) Recursos de Apoyo @elseif($isOdonto) Materiales Cirugía @else {{ trans('messages.inventory') }} @endif</span>
                        <span class="arrow">▼</span>
                    </a>
                    <div class="submenu" onclick="event.stopPropagation()">
                        <a href="{{ url('admin/productos') }}" class="submenu-item {{ Request::is('admin/productos') ? 'active' : '' }}">
                            <span class="submenu-icon">📝</span>
                            <span>Existencias Actuales</span>
                        </a>
                        <a href="{{ url('admin/inventario') }}" class="submenu-item {{ Request::is('admin/inventario*') ? 'active' : '' }}">
                            <span class="submenu-icon">📊</span>
                            <span>{{ trans('messages.stocks') }}</span>
                        </a>
                    </div>
                </div>
                <a href="{{ url('admin/specialists') }}" class="menu-item {{ Request::is('admin/specialists*') ? 'active' : '' }}">
                    <span class="menu-icon">👤</span>
                    <span>@if($isHealth) Cuerpo Médico @elseif($isAuto) Mecánicos y Técnicos @elseif($isOptica) Optometristas @elseif($isContador) Auxiliares y Devs @elseif($isLegal) Abogados / Litigantes @elseif($isRealEstate) Agentes / Vendedores @elseif($isPsico) Red de Psicólogos @elseif($isOdonto) Dentistas y Auxiliares @else {{ trans('messages.specialists') }} @endif</span>
                </a>
                <a href="{{ url('admin/servicios') }}" class="menu-item {{ Request::is('admin/servicios*') || Request::is('admin/packages*') ? 'active' : '' }}">
                    <span class="menu-icon">⚙️</span>
                    <span>@if($isHealth) Procedimientos @elseif($isAuto) Mantenimientos @elseif($isOptica) Exámenes Digitales @elseif($isContador) Tipos de Asesoría @elseif($isLegal) Tipo de Casos @elseif($isRealEstate) Servicios de Corretaje @elseif($isPsico) Tipos de Terapia @elseif($isOdonto) Tratamientos @else {{ trans('messages.services') }} @endif</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">Inteligencia Artificial</div>
                <a href="{{ url('admin/agente-ia') }}" class="menu-item {{ Request::is('admin/agente-ia*') ? 'active' : '' }}">
                    <span class="menu-icon">🤖</span>
                    <span>@if($isHealth) Recepcionista IA @elseif($isAuto) Diagnóstico IA @elseif($isOptica) Asesor Visual IA @elseif($isContador) Auditor Fiscal IA @elseif($isLegal) Consultoría Legal IA @elseif($isRealEstate) Valuador Inmobiliario IA @elseif($isPsico) Orientador IA @elseif($isOdonto) Diagnóstico Odonto IA @else Call Center IA @endif</span>
                </a>
                <a href="{{ url('admin/agente-contador') }}" class="menu-item {{ Request::is('admin/agente-contador*') ? 'active' : '' }}">
                    <span class="menu-icon">💼</span>
                    <span>@if($isContador) Análisis Normativo IA @elseif($isLegal) Auditor de Procesos @elseif($isPsico) Auditor de Sesiones @else Contador IA @endif</span>
                </a>
                <a href="{{ url('admin/agente-nomina') }}" class="menu-item {{ Request::is('admin/agente-nomina*') ? 'active' : '' }}">
                    <span class="menu-icon">👥</span>
                    <span>@if($isHealth) Gestión Honorarios @elseif($isAuto) Nómina Mecánicos @elseif($isOptica) Nómina Especialistas @elseif($isContador) Auditoría de Nómina @elseif($isLegal) Honorarios Asociados @else Portal Nómina IA @endif</span>
                </a>
                <a href="{{ url('admin/agente-estratega') }}" class="menu-item {{ Request::is('admin/agente-estratega*') ? 'active' : '' }}">
                    <span class="menu-icon">🚀</span>
                    <span>Crecimiento IA</span>
                </a>
                <a href="{{ url('admin/agente-fidelizacion') }}" class="menu-item {{ Request::is('admin/agente-fidelizacion*') ? 'active' : '' }}">
                    <span class="menu-icon">💝</span>
                    <span>@if($isHealth) Vínculo con Paciente @elseif($isAuto) Seguimiento Vehículo @elseif($isOptica) Post-Venta Visual @elseif($isContador) Retención de Empresas @elseif($isLegal) Seguimiento de Casos @elseif($isRealEstate) CRM Propietarios @elseif($isPsico) Conexión Emocional @elseif($isOdonto) Seguimiento Post-Cirugía @else {{ trans('messages.loyalty_ia') }} @endif</span>
                </a>
            </div>

            <div class="sidebar-section">
                <div class="sidebar-title">{{ trans('messages.section_system') }}</div>
                <!-- Idiomas con Submenu -->
                <div class="has-submenu" onclick="this.classList.toggle('open')">
                    <a href="javascript:void(0)" class="menu-item">
                        <span class="menu-icon">🌐</span>
                        <span>{{ trans('messages.select_language') }}</span>
                        <span class="arrow">▼</span>
                    </a>
                    <div class="submenu" onclick="event.stopPropagation()">
                        <a href="{{ url('lang/es') }}" class="submenu-item {{ App::getLocale() == 'es' ? 'active' : '' }}">🇪🇸 {{ trans('messages.spanish') }}</a>
                        <a href="{{ url('lang/en') }}" class="submenu-item {{ App::getLocale() == 'en' ? 'active' : '' }}">🇺🇸 {{ trans('messages.english') }}</a>
                        <a href="{{ url('lang/pt') }}" class="submenu-item {{ App::getLocale() == 'pt' ? 'active' : '' }}">🇧🇷 {{ trans('messages.portuguese') }}</a>
                        <a href="{{ url('lang/fr') }}" class="submenu-item {{ App::getLocale() == 'fr' ? 'active' : '' }}">🇫🇷 {{ trans('messages.french') }}</a>
                        <a href="{{ url('lang/it') }}" class="submenu-item {{ App::getLocale() == 'it' ? 'active' : '' }}">🇮🇹 {{ trans('messages.italian') }}</a>
                        <a href="{{ url('lang/zh') }}" class="submenu-item {{ App::getLocale() == 'zh' ? 'active' : '' }}">🇨🇳 {{ trans('messages.chinese') }}</a>
                        <a href="{{ url('lang/hi') }}" class="submenu-item {{ App::getLocale() == 'hi' ? 'active' : '' }}">🇮🇳 {{ trans('messages.hindi') }}</a>
                        <a href="{{ url('lang/ar') }}" class="submenu-item {{ App::getLocale() == 'ar' ? 'active' : '' }}">🇸🇦 {{ trans('messages.arabic') }}</a>
                    </div>
                </div>
                <a href="{{ url('admin/subscription') }}" class="menu-item {{ Request::is('admin/subscription*') ? 'active' : '' }}" style="background: linear-gradient(90deg, #f0fdf4 0%, #dcfce7 100%); color: #166534; margin-bottom: 10px; border: 1px solid #bbf7d0;">
                    <span class="menu-icon">💳</span>
                    <span>{{ trans('messages.subscription') }}</span>
                </a>
                
                <a href="{{ url('admin/configuration') }}" class="menu-item {{ Request::is('admin/configuration*') ? 'active' : '' }}">
                    <span class="menu-icon">⚙️</span>
                    <span>{{ trans('messages.settings') }}</span>
                </a>
                <a href="#" class="menu-item" onclick="event.preventDefault(); if(confirm('{{ App::getLocale() == 'es' ? "¿Cerrar sesión?" : (App::getLocale() == 'en' ? "Logout?" : "Sair?") }}')) window.location.href='{{ url('admin/logout') }}';">
                    <span class="menu-icon">🚪</span>
                    <span>{{ trans('messages.logout') }}</span>
                </a>
            </div>

            <style>
                /* Reverted Language Styles to standard submenu */
            </style>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
            
            <!-- Neura Core Professional Footer -->
            <footer class="neura-footer">
                <div class="neura-footer-content">
                    <span class="neura-footer-text">Software desarrollado por</span>
                    <span class="neura-footer-brand">Neura Core</span>
                    <span class="neura-footer-divider">|</span>
                    <span class="neura-footer-year">© {{ date('Y') }}</span>
                </div>
            </footer>
            
            <style>
                .neura-footer {
                    margin-top: 60px;
                    padding: 25px 0;
                    border-top: 1px solid #e5e7eb;
                    text-align: center;
                }
                
                .neura-footer-content {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    flex-wrap: wrap;
                }
                
                .neura-footer-text {
                    font-size: 12px;
                    color: #9ca3af;
                    font-weight: 400;
                }
                
                .neura-footer-brand {
                    font-size: 13px;
                    font-weight: 600;
                    color: #1a73e8;
                    letter-spacing: 0.5px;
                }
                
                .neura-footer-divider {
                    color: #d1d5db;
                    font-size: 12px;
                }
                
                .neura-footer-year {
                    font-size: 11px;
                    color: #9ca3af;
                }
            </style>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.menu-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>

    <!-- Toast Notification System -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px;"></div>
    
    <style>
        .toast {
            position: relative;
            min-width: 320px;
            max-width: 420px;
            padding: 18px 24px;
            border-radius: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-size: 14px;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(12px);
            overflow: hidden;
            color: white;
        }

        .toast-glass {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
            z-index: -1;
        }
        
        .toast-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .toast-error { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .toast-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .toast-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        
        .toast-icon {
            font-size: 24px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.2);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
        
        .toast-content { flex: 1; }
        .toast-title { font-weight: 800; text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; margin-bottom: 4px; opacity: 0.9; }
        .toast-message { font-weight: 500; line-height: 1.5; }
        .toast-close { background: none; border: none; color: white; opacity: 0.5; cursor: pointer; font-size: 20px; padding: 0; transition: opacity 0.2s; }
        .toast-close:hover { opacity: 1; }

        /* NeuraModal Styles */
        .neura-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(8px);
            z-index: 10001;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        .neura-modal-overlay.active { opacity: 1; visibility: visible; }
        
        .neura-modal-container {
            background: white;
            width: 90%;
            max-width: 450px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            transform: scale(0.9);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .active .neura-modal-container { transform: scale(1); }
        
        .neura-modal-header { padding: 32px 32px 16px; text-align: center; }
        .neura-modal-icon { font-size: 48px; margin-bottom: 16px; }
        .neura-modal-title { font-size: 22px; font-weight: 900; color: #1e293b; margin: 0; }
        
        .neura-modal-body { padding: 0 32px 32px; text-align: center; color: #64748b; font-size: 15px; line-height: 1.6; }
        .neura-modal-footer { padding: 24px 32px 32px; display: flex; gap: 12px; }
        
        .neura-modal-btn {
            flex: 1;
            padding: 14px;
            border-radius: 16px;
            font-weight: 800;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .neura-modal-btn.confirm.success { background: #10b981; color: white; }
        .neura-modal-btn.confirm.danger { background: #ef4444; color: white; }
        .neura-modal-btn.confirm.warning { background: #f59e0b; color: white; }
        .neura-modal-btn.confirm.info { background: #3b82f6; color: white; }
        .neura-modal-btn.cancel { background: #f1f5f9; color: #64748b; }
        .neura-modal-btn:hover { transform: translateY(-2px); filter: brightness(1.1); }
        
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    </style>
    
    <script>
        // Global Toast Notification System
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: '✨',
                error: '🚫',
                warning: '⚠️',
                info: 'ℹ️'
            };
            
            const titles = {
                success: 'Completado',
                error: 'Error',
                warning: 'Atención',
                info: 'Información'
            };
            
            toast.innerHTML = `
                <div class="toast-glass"></div>
                <span class="toast-icon">${icons[type]}</span>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="closeToast(this.parentElement)">&times;</button>
            `;
            
            container.appendChild(toast);
            
            // Auto remove after duration
            setTimeout(() => {
                closeToast(toast);
            }, duration);
        }
        
        function closeToast(toast) {
            if (toast && toast.parentElement) {
                toast.style.animation = 'slideOut 0.3s ease-out forwards';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        /**
         * NeuraModal - Professional Modal System
         * replaces alert() and confirm()
         */
        function showNeuraModal(options) {
            const {
                title = 'Notificación',
                message = '',
                type = 'info', // info, success, warning, danger
                confirmText = 'Entendido',
                cancelText = null,
                onConfirm = null,
                onCancel = null
            } = options;

            const modalHtml = `
            <div id="neuraModal" class="neura-modal-overlay active">
                <div class="neura-modal-container">
                    <div class="neura-modal-header ${type}">
                        <div class="neura-modal-icon">${type === 'success' ? '✅' : type === 'warning' ? '⚠️' : type === 'danger' ? '🚨' : 'ℹ️'}</div>
                        <h3 class="neura-modal-title">${title}</h3>
                    </div>
                    <div class="neura-modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="neura-modal-footer">
                        ${cancelText ? `<button class="neura-modal-btn cancel" id="neuraCancelBtn">${cancelText}</button>` : ''}
                        <button class="neura-modal-btn confirm ${type}" id="neuraConfirmBtn">${confirmText}</button>
                    </div>
                </div>
            </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById('neuraModal');
            
            document.getElementById('neuraConfirmBtn').addEventListener('click', () => {
                if (onConfirm) onConfirm();
                closeNeuraModal(modal);
            });

            if (cancelText) {
                document.getElementById('neuraCancelBtn').addEventListener('click', () => {
                    if (onCancel) onCancel();
                    closeNeuraModal(modal);
                });
            }
        }

        function closeNeuraModal(modal) {
            modal.classList.remove('active');
            setTimeout(() => modal.remove(), 300);
        }

        // Auto-trigger toasts on Laravel session flash messages
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
        @endif
    </script>

    <!-- Floating Staff Chat Widget (Admin) -->
    <div id="adminChatFAB" onclick="toggleAdminChat()" title="Chats con Staff" style="position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px; background: #1a73e8; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; box-shadow: 0 4px 15px rgba(26, 115, 232, 0.4); cursor: pointer; z-index: 9999; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        💬
        <span style="position: absolute; top: 0; right: 0; background: #ef4444; color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 12px; font-weight: bold; display: flex; align-items: center; justify-content: center; border: 2px solid white; display: none;" id="adminChatTotalBadge">0</span>
    </div>

    <!-- Admin Chat Overlay -->
    <div id="adminChatOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="width: 90%; max-width: 900px; height: 85vh; background: white; border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
            <div style="padding: 15px 20px; border-bottom: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: space-between; background: #f8f9fa;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">Bandeja de Entrada - Staff</h3>
                <button onclick="toggleAdminChat()" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">✕</button>
            </div>
            <iframe id="adminChatIframe" src="" style="width: 100%; flex: 1; border: none;"></iframe>
        </div>
    </div>

    <script>
        function toggleAdminChat() {
            const overlay = document.getElementById('adminChatOverlay');
            const iframe = document.getElementById('adminChatIframe');
            if (overlay.style.display === 'none' || overlay.style.display === '') {
                iframe.src = "{{ url('admin/chats') }}?minimal=1";
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            } else {
                overlay.style.display = 'none';
                document.body.style.overflow = 'auto';
                iframe.src = "";
                updateAdminUnreadCount();
            }
        }

        function updateAdminUnreadCount() {
            fetch("{{ url('admin/chats/unread-count') }}")
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('adminChatTotalBadge');
                    if (data.total > 0) {
                        badge.innerText = data.total;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                });
        }

        // Poll every 30s
        setInterval(updateAdminUnreadCount, 30000);
        updateAdminUnreadCount();
    </script>

    <style>
        #adminChatFAB:hover {
            transform: scale(1.1);
            background: #1557b0;
        }
    </style>


    @stack('scripts')
</body>
</html>
