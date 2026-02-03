<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Reserva tu Cita</title>

    <!-- Google Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 3 (Legacy support) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <style>
        :root {
            --font-main: 'Inter', sans-serif;
            --font-heading: 'Poppins', sans-serif;
            
            /* Colores extraídos de las imágenes */
            --primary: #000000;         /* Botón Continuar */
            --primary-hover: #333333;
            --accent: #2d9cdb;          /* Azul iconos/selección */
            --success: #25d366;         /* Verde WhatsApp */
            --bg-body: #f8f9fa;         /* Fondo general */
            --bg-card: #ffffff;         /* Fondo tarjetas */
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-pill: 999px;
            
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-float: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: var(--font-main);
            background-color: var(--bg-body);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
            margin: 0;
            padding: 0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-heading);
            color: var(--text-main);
            margin-top: 0;
        }

        /* Reset de botones Bootstrap */
        .btn {
            border-radius: var(--radius-pill);
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
        }

        .btn-black {
            background-color: var(--primary);
            color: white;
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-black:hover {
            background-color: var(--primary-hover);
            color: white;
        }

        .container-app {
            max-width: 768px; /* Ancho tipo app móvil/tablet */
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            box-shadow: 0 0 40px rgba(0,0,0,0.05);
            position: relative;
            padding-bottom: 80px; /* Espacio para botón flotante */
        }

        /* Header estilo App */
        .app-header {
            padding: 16px 20px;
            background: white;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-pills-custom {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: none; /* Firefox */
        }
        .nav-pills-custom::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .nav-pill-item {
            padding: 8px 16px;
            border-radius: var(--radius-pill);
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            border: 1px solid var(--border-color);
            color: var(--text-muted);
            background: white;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .nav-pill-item.active {
            background-color: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .nav-pill-item i {
            font-size: 12px;
        }

        /* Utilidades */
        .mt-4 { margin-top: 24px; }
        .mb-4 { margin-bottom: 24px; }
        .p-4 { padding: 24px; }
        .text-center { text-align: center; }
        .d-flex { display: flex; }
        .align-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .w-100 { width: 100%; }

    </style>
</head>  
<body>

    <div class="container-app">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('/js/moment.js') }}"></script>

</body>
</html>
