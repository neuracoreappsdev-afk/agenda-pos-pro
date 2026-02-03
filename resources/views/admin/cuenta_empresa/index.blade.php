@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Centered Layout */
    .selection-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 60vh; /* Takes up most of the screen */
    }

    .selection-title {
        font-size: 18px;
        color: #1f2937;
        margin-bottom: 40px;
        font-weight: 500;
    }

    .selection-button {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        max-width: 400px;
        padding: 20px 30px;
        margin-bottom: 20px;
        background: white;
        border: 2px solid #1f2937; /* Dark border */
        border-radius: 12px;
        text-decoration: none;
        color: #1f2937;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.2s;
    }

    .selection-button:hover {
        background-color: #f9fafb;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .button-icon {
        font-size: 20px;
        margin-right: 15px;
    }
    
    .arrow-icon {
        font-size: 18px;
        font-weight: 700;
    }

</style>

<!-- Breadcrumb mockup (optional based on other views) -->
<div style="margin-bottom:20px; color:#6b7280; font-size:12px;">
    Sedes / <span style="color:#1f2937; font-weight:600;">Gastos</span>
</div>

<div class="selection-container">
    <h2 class="selection-title">¿Qué Tipo de movimiento deseas realizar?</h2>

    <!-- Expenses Button -->
    <a href="{{ url('admin/cuenta-empresa/egresos') }}" class="selection-button">
        <div style="display:flex; align-items:center;">
            <span class="button-icon">⬅️</span> <!-- Using emoji for now, visual match -->
            <span>Egresos</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

    <!-- Income Button -->
    <a href="{{ url('admin/cuenta-empresa/ingresos') }}" class="selection-button">
        <div style="display:flex; align-items:center;">
            <span class="button-icon">➡️</span>
            <span>Ingresos</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

    <!-- Report Button -->
    <a href="{{ url('admin/cuenta-empresa/informes') }}" class="selection-button">
        <div style="display:flex; align-items:center;">
            <span class="button-icon">📄</span>
            <span>Ver Informe</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

</div>

@endsection
