@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Reutilizamos estilos base de clientes para consistencia */
    .header-tabs {
        display: flex;
        gap: 30px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 24px;
        padding-left: 10px;
    }

    .tab-item {
        padding-bottom: 12px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid transparent;
        text-decoration: none;
    }

    .tab-item.active {
        color: #1d4ed8;
        border-bottom-color: #1d4ed8;
        font-weight: 600;
    }
    
    .tab-icon { font-size: 16px; }

    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 18px;
        color: #1f2937;
        font-weight: 500;
    }

    .btn-create {
        background-color: #1a73e8;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
    }
    .btn-create:hover { background-color: #1557b0; }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 20px;
        width: 100%;
        max-width: 400px;
    }

    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
    }

    .filter-input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        color: #4b5563;
        background: white;
        width: 100%;
    }

    .table-responsive {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        min-height: 200px;
    }

    table { width: 100%; border-collapse: collapse; }

    th {
        font-size: 12px;
        color: #1f2937;
        font-weight: 700;
        text-align: left;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        background-color: #fff;
    }

    .empty-state {
        padding: 40px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

    /* FAB + Button */
    .fab-plus {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background-color: #1a73e8;
        border-radius: 50%;
        color: white;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
        cursor: pointer;
        z-index: 100;
        border: none;
    }
</style>

<div class="header-tabs">
    <a href="{{ url('admin/clientes') }}" class="tab-item">
        <span class="tab-icon">😊</span> Clientes
    </a>
    <a href="{{ url('admin/clientes/empresas') }}" class="tab-item active">
        <span class="tab-icon">🏢</span> Empresas
    </a>
    <a href="{{ url('admin/clientes/importar') }}" class="tab-item">
        <span class="tab-icon">⬇️</span> Importar Clientes
    </a>
</div>

<div class="action-bar">
    <div class="page-title">Empresas</div>
    <button class="btn-create">Crear Nuevo</button>
</div>

<div class="filter-group">
    <label class="filter-label">Buscador</label>
    <input type="text" class="filter-input" placeholder="Nombre o Identificación">
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Nombre de la Empresa</th>
                <th>Nombre Comercial</th>
                <th>Teléfono Fijo</th>
                <th>Celular</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" class="empty-state">
                    No hay empresas
                </td>
            </tr>
        </tbody>
    </table>
</div>

<button class="fab-plus">+</button>

@endsection
