@extends('admin/dashboard_layout')

@section('content')

<style>
    .header-tabs {
        display: flex;
        gap: 30px;
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 24px;
    }

    .tab-item {
        padding-bottom: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
    }

    .tab-item.active {
        color: #1f2937;
        border-bottom-color: #1a73e8;
        font-weight: 600;
    }

    .upload-container {
        background: white;
        padding: 40px;
        border-radius: 12px;
        border: 2px dashed #d1d5db;
        text-align: center;
        margin-bottom: 30px;
        transition: all 0.3s ease;
    }

    .upload-container:hover {
        border-color: #1a73e8;
        background: #f8fbff;
    }

    .upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 15px;
    }

    .table-container {
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        padding: 12px 16px;
        background: #f9fafb;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    td {
        padding: 12px 16px;
        font-size: 14px;
        color: #4b5563;
        border-bottom: 1px solid #f3f4f6;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
</style>

<div class="action-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="font-size: 18px; font-weight: 500;">Recepción de Documentos</h2>
    <button class="btn-save" style="background: #1a73e8; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer;">Subir Documento</button>
</div>

<div class="header-tabs">
    <a href="{{ url('admin/compras/facturas') }}" class="tab-item">Compras</a>
    <a href="{{ url('admin/compras/recepcion-documentos') }}" class="tab-item active">Recepción de documentos</a>
    <a href="{{ url('admin/configuration/importar-productos') }}" class="tab-item">Importar Compras</a>
</div>

<div class="upload-container">
    <div class="upload-icon">📄</div>
    <h3 style="font-size: 16px; color: #1f2937; margin-bottom: 5px;">Arrastra tus documentos aquí</h3>
    <p style="font-size: 14px; color: #6b7280;">Soporta PDF, JPG, PNG (Max 10MB)</p>
    <input type="file" style="display: none;" id="fileInput">
    <button onclick="document.getElementById('fileInput').click()" style="margin-top: 15px; background: white; border: 1px solid #d1d5db; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: 500;">Seleccionar Archivo</button>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre del Archivo</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="5" style="text-align: center; padding: 30px; color: #9ca3af;">
                    No hay documentos pendientes por procesar
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
