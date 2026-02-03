@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Estilos compartidos */
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

    .page-title {
        font-size: 18px;
        color: #1f2937;
        font-weight: 500;
        margin-bottom: 20px;
    }

    /* Import Card */
    .import-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 30px;
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    .import-icon {
        font-size: 48px;
        color: #1a73e8;
        margin-bottom: 20px;
    }

    .import-instructions {
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 30px;
        line-height: 1.5;
    }

    .btn-download-template {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1a73e8;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 30px;
        border: 1px dashed #1a73e8;
        padding: 8px 16px;
        border-radius: 6px;
    }
    
    .btn-download-template:hover { background-color: #eff6ff; }

    .file-drop-zone {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 40px;
        margin-bottom: 20px;
        background-color: #f9fafb;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    
    .file-drop-zone:hover { border-color: #1a73e8; }

    .btn-upload {
        background-color: #1a73e8;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
    }
    .btn-upload:hover { background-color: #1557b0; }

</style>

<div class="header-tabs">
    <a href="{{ url('admin/clientes') }}" class="tab-item">
        <span class="tab-icon">😊</span> Clientes
    </a>
    <a href="{{ url('admin/clientes/empresas') }}" class="tab-item">
        <span class="tab-icon">🏢</span> Empresas
    </a>
    <a href="{{ url('admin/clientes/importar') }}" class="tab-item active">
        <span class="tab-icon">⬇️</span> Importar Clientes
    </a>
</div>

<div class="page-title">Importar Clientes</div>

<div class="import-card">
    <div class="import-icon">📂</div>
    
    <h3 style="margin-bottom: 10px; color:#111827;">Sube tu archivo de clientes</h3>
    <p class="import-instructions">
        Descarga la plantilla, rellénala con tus datos y súbela aquí para importar tus clientes masivamente.
        Formatos soportados: .xlsx, .csv
    </p>

    <a href="#" class="btn-download-template">
        ⬇️ Descargar Plantilla de Ejemplo
    </a>

    <div class="file-drop-zone">
        <p style="color:#6b7280; margin-bottom:10px;">Arrastra tu archivo aquí o</p>
        <button style="background:#fff; border:1px solid #d1d5db; padding:6px 12px; border-radius:4px; color:#374151; cursor:pointer;">Seleccionar Archivo</button>
    </div>

    <button class="btn-upload">Importar Clientes</button>
</div>

@endsection
