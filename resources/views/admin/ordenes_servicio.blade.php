@extends('admin/dashboard_layout')

@section('content')

<style>
    .os-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 60vh; /* Ajuste para que no quede tan abajo */
        padding-top: 60px;
    }

    .selector-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 80px;
    }

    .sede-label {
        font-size: 14px;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
    }

    .sede-select-wrapper {
        position: relative;
    }

    .sede-select {
        appearance: none;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 10px 40px 10px 15px;
        font-size: 14px;
        color: #4b5563;
        width: 250px;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .select-arrow {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        font-size: 10px;
        color: #6b7280;
    }

    .btn-send {
        background-color: #34a853;
        color: white;
        border: none;
        padding: 8px 30px;
        border-radius: 4px;
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
        column-gap: 120px; /* Mayor espacio como en la imagen */
        row-gap: 80px;
        width: 100%;
        max-width: 900px;
        justify-items: center;
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
        margin-bottom: 12px;
        color: #4b5563;
    }
    
    .icon-card:hover .icon-circle {
        color: #111827;
    }

    .icon-label {
        font-size: 15px;
        font-weight: 400;
    }

    /* SVG Styling */
    .icon-svg {
        width: 44px;
        height: 44px;
        fill: currentColor;
    }

    @media (max-width: 768px) {
        .icons-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
    }
</style>

<div class="os-container">
    
    <!-- Selector de Sede -->
    <div class="selector-container">
        <label class="sede-label">
            <!-- Icono Edificio -->
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M19,2H9C7.89,2 7,2.89 7,4V10H3V20H5V22H11V20H13V22H19V20H21V4C21,2.89 20.1,2 19,2M9,4H19V10H9V4M9,12H13V18H9V12M15,12H19V18H15V12Z" />
            </svg>
            Sede
        </label>
        
        <div class="sede-select-wrapper">
            <select class="sede-select">
                <option>Holguínes Trade</option>
            </select>
            <span class="select-arrow">▼</span>
        </div>

        <button class="btn-send">Enviar</button>
    </div>

    <!-- Grid de Botones -->
    <div class="icons-grid">
        <!-- Digitar Venta -->
        <a href="{{ url('admin/digitar-venta') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono billete con numero 1 -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M2,5H22V19H2V5M20,17V7H4V17H20M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9M12,10.5A1.5,1.5 0 0,0 10.5,12A1.5,1.5 0 0,0 12,13.5A1.5,1.5 0 0,0 13.5,12A1.5,1.5 0 0,0 12,10.5Z" />
                    <!-- Badge 1 -->
                    <circle cx="12" cy="12" r="7" fill="none" stroke="currentColor" stroke-width="2" />
                    <text x="12" y="16" font-size="11" text-anchor="middle" fill="currentColor" font-weight="bold">1</text>
                </svg>
            </div>
            <span class="icon-label">Digitar Venta</span>
        </a>

        <!-- Informes -->
        <a href="{{ url('admin/informes') }}" class="icon-card">
            <div class="icon-circle">
                 <!-- Icono Gráfico Barras -->
                 <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M22 21H2V3h2v18h18v-2zM8 17H6v-5h2v5zm6 0h-2v-9h2v9zm6 0h-2V6h2v11z" fill="currentColor"/>
                </svg>
            </div>
            <span class="icon-label">Informes</span>
        </a>

        <!-- Fichas Técnica -->
        <a href="{{ url('admin/fichas-tecnica') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Documento -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z" />
                </svg>
            </div>
            <span class="icon-label">Fichas Técnica</span>
        </a>

        <!-- Agenda (Segunda Fila) -->
        <a href="{{ url('admin/appointments') }}" class="icon-card">
            <div class="icon-circle">
                <!-- Icono Calendario con Puntos -->
                <svg class="icon-svg" viewBox="0 0 24 24">
                    <path d="M19,4H18V2H16V4H8V2H6V4H5A2,2 0 0,0 3,6V20A2,2 0 0,0 5,22H19A2,2 0 0,0 21,20V6A2,2 0 0,0 19,4M19,20H5V10H19V20M19,8H5V6H19V8M7,12H9V14H7V12M11,12H13V14H11V12M15,12H17V14H15V12Z" />
                    <!-- Puntos simulados -->
                    <circle cx="8" cy="13" r="1.5" fill="currentColor"/>
                    <circle cx="12" cy="13" r="1.5" fill="currentColor"/>
                </svg>
            </div>
            <span class="icon-label">Agenda</span>
        </a>

         <!-- Espaciadores para mantener el grid alineado -->
        <div></div>
        <div></div>

    </div>
</div>

@endsection
