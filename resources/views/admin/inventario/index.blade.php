@extends('admin/dashboard_layout')

@section('content')

<style>
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: flex-end; /* Align bottom for label and button */
        margin-bottom: 40px;
    }

    .select-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .sede-select {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        color: #4b5563;
        width: 300px;
        background: white;
    }

    .btn-download-template {
        background-color: #1a73e8;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    /* Buttons List */
    .menu-list {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .menu-button {
        background: white;
        border: 1px solid #1f2937; /* Dark border */
        border-radius: 12px;
        padding: 15px 25px;
        width: 100%;
        max-width: 500px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: transform 0.1s;
        text-decoration: none;
        color: #1f2937;
    }

    .menu-button:hover {
        transform: scale(1.01);
        background-color: #f9fafb;
    }

    .btn-content {
        display: flex;
        align-items: center;
        gap: 15px;
        font-size: 16px;
        font-weight: 500;
    }

    .btn-icon { font-size: 20px; }
    .arrow-icon { font-weight: bold; font-size: 18px; }

</style>

<!-- Header -->
<div class="header-actions">
    <div class="select-group">
        <label>Sede</label>
        <select class="sede-select">
            <option>Holguines Trade Center.</option>
        </select>
    </div>
    <a href="#" class="btn-download-template">
        <span>⬇</span> Descargar Plantilla
    </a>
</div>

<!-- Menu Options -->
<div class="menu-list">
    
    <a href="#" class="menu-button">
        <div class="btn-content">
            <span class="btn-icon">🗄️</span>
            <span>Iniciar Inventario</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

    <a href="#" class="menu-button">
        <div class="btn-content">
            <span class="btn-icon">📋</span>
            <span>Informes de Inventarios</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

    <a href="#" class="menu-button">
        <div class="btn-content">
            <span class="btn-icon">☑️</span>
            <span>Realizar Ajustes de Costo</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

    <a href="{{ url('admin/configuration/importar-productos') }}" class="menu-button">
        <div class="btn-content">
            <span class="btn-icon">📥</span>
            <span>Importar archivo inventario</span>
        </div>
        <span class="arrow-icon">➔</span>
    </a>

</div>

@endsection
