@extends('admin/dashboard_layout')

@section('content')

<style>
    .caja-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        padding-top: 40px;
    }

    .page-title {
        font-size: 20px;
        color: #374151;
        margin-bottom: 25px;
        font-weight: 500;
    }

    .date-filter-form {
        display: flex;
        gap: 0;
        margin-bottom: 60px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .date-input {
        border: 1px solid #d1d5db;
        border-right: none;
        padding: 10px 15px;
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
        font-size: 14px;
        color: #374151;
        outline: none;
        width: 180px;
        background: #fff;
    }

    .btn-send {
        background-color: #34a853; /* Google Green variant - similar to image */
        color: white;
        border: 1px solid #34a853;
        padding: 10px 25px;
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s;
    }

    .btn-send:hover {
        background-color: #2e8b46;
    }

    /* Grid de Iconos */
    .icons-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        column-gap: 80px;
        row-gap: 60px;
        width: 100%;
        max-width: 800px;
    }

    .icon-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #4b5563;
        transition: transform 0.2s;
        cursor: pointer;
    }

    .icon-card:hover {
        transform: translateY(-5px);
        color: #111827;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        color: #4b5563; /* Color gris oscuro de los iconos */
    }
    
    .icon-card:hover .icon-circle {
        color: #111827;
    }

    .icon-label {
        font-size: 16px;
        font-weight: 400;
    }

    /* SVG Styling */
    .icon-svg {
        width: 48px;
        height: 48px;
        fill: currentColor;
    }

    @media (max-width: 768px) {
        .icons-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
    }
</style>

<div class="caja-container">
    <h1 class="page-title">{{ trans('messages.cashier') }} {{ session('business_name', 'Holguines Trade') }}</h1> <!-- Nombre dinámico -->

    <div class="date-filter-form">
        <input type="date" class="date-input" value="{{ date('Y-m-d') }}">
        <button class="btn-send">{{ trans('messages.send') }}</button>
    </div>

    <div class="icons-grid">
        <!-- Facturar -->
        <a href="{{ url('admin/crear-factura') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono billete/factura -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M2,5H22V19H2V5M20,17V7H4V17H20M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9M12,10.5A1.5,1.5 0 0,0 10.5,12A1.5,1.5 0 0,0 12,13.5A1.5,1.5 0 0,0 13.5,12A1.5,1.5 0 0,0 12,10.5Z" />
                    <!-- Badge 1 -->
                    <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" stroke-width="2" />
                    <text x="12" y="16" font-size="12" text-anchor="middle" fill="currentColor" font-weight="bold">1</text>
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.invoice') }}</span>
        </a>

        <!-- Ventas -->
        <a href="{{ url('admin/ventas') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Thumbs Up / Mano -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                   <path d="M5,9V21H1V9H5M9,21A2,2 0 0,1 7,19V9C7,8.45 7.22,7.95 7.59,7.59L14.17,1L15.23,2.06C15.5,2.33 15.67,2.7 15.67,3.11L15.64,3.43L14.69,8H21C22.11,8 23,8.9 23,10V12C23,12.26 22.95,12.5 22.86,12.73L19.84,19.78C19.54,20.5 18.83,21 18,21H9M9,19H18.03L21,12V10H12.21L13.34,4.68L9,9.03V19Z" />
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.sales') }}</span>
        </a>

        <!-- Caja de Día -->
        <a href="#" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Canasta -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M12,2L16,6H8L12,2M4,7V21H20V7H4M6,9H18V19H6V9Z" />
                    <path d="M9 11h2v6H9zm4 0h2v6h-2z" />
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.daily_cash') }}</span>
        </a>

        <!-- Movimientos -->
        <a href="#" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Flechas Intercambio -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M21,9L17,5V8H10V10H17V13M7,11L3,15L7,19V16H14V14H7V11Z" />
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.movements') }}</span>
        </a>

        <!-- Creditos -->
        <a href="#" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Libro/Nota -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M19,3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3M19,19H5V5H19V19M17,11H7V9H17V11M17,15H7V13H17V15Z" />
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.credits_title') }}</span>
        </a>

        <!-- Informes -->
        <a href="{{ url('admin/informes') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Gráfico Barras -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M16,6L12,10L8,6L2,12L4,14L8,10L12,14L18,8V12H20V4H12V6M22,21H2V19H22V21M17,14h2v3h-2v-3m-5-2h2v5h-2v-5m-5,2h2v3H7v-3Z" transform="scale(1, -1) translate(0, -24)" />
                     <!-- Mejor icono de barras simple -->
                    <path d="M22 21H2V3h2v18h18v-2zM8 17H6v-5h2v5zm6 0h-2v-9h2v9zm6 0h-2V6h2v11z" fill="currentColor"/>
                </svg>
            </div>
            <span class="icon-label">{{ trans('messages.reports') }}</span>
        </a>
    </div>
</div>

@endsection
