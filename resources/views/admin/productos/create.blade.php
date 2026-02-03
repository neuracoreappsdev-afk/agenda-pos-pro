@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Header Styles */
    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .breadcrumb {
        font-size: 13px;
        color: #1a73e8;
    }
    .breadcrumb span { color: #6b7280; }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn-cancel {
        border: 1px solid #ef4444; /* Red border */
        color: #ef4444;
        background: white;
        padding: 6px 16px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-create {
        background-color: #2e7d32; /* Green */
        color: white;
        border: none;
        padding: 6px 20px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
    }

    /* Form Container */
    .form-section {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px; /* Slightly rounded */
        padding: 24px;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 20px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 10px;
    }

    /* Grid Layout */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 300px; /* Main info + Image column */
        gap: 30px;
    }

    .fields-col {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-input, .form-select {
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        color: #4b5563;
        width: 100%;
    }
    
    .form-input:focus, .form-select:focus {
        border-color: #1a73e8;
        outline: none;
    }

    .input-with-action {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .action-link {
        color: #1a73e8;
        font-size: 12px;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid #1a73e8;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Image Upload Box */
    .image-upload-box {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #9ca3af;
        text-align: center;
        font-size: 12px;
    }
    .image-placeholder {
        width: 100%; height: 100%; background: #f9fafb;
        display: flex; align-items: center; justify-content: center;
    }

    /* Switch Toggle */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 10px;
    }
    
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider { background-color: #1a73e8; }
    input:checked + .slider:before { transform: translateX(20px); }

    .switch-label { font-size: 13px; font-weight: 500; color: #374151; }

    /* Tables inside form */
    .inner-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    
    .inner-table th {
        text-align: left;
        font-size: 12px;
        color: #374151;
        padding: 8px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .inner-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #f3f4f6;
    }

    .btn-apply {
        background: white;
        border: 1px solid #1a73e8;
        color: #1a73e8;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
    }

    .info-link {
        color: #1a73e8; font-size:13px; text-decoration:none; display:inline-block; margin-top:10px; border:1px solid #dbeafe; padding: 6px 12px; border-radius:4px;
    }

    .remove-x { color: red; font-weight: bold; cursor: pointer; margin-left:10px; }

</style>

<div class="header-actions">
    <div class="breadcrumb">Productos <span>/</span> Creación</div>
    <div class="action-buttons">
        <button class="btn-cancel" onclick="window.history.back()">Cancelar</button>
        <button class="btn-create">Crear</button>
    </div>
</div>

<!-- Section 1: Información del Producto -->
<div class="form-section">
    <div class="section-title">Información del Producto</div>
    
    <div class="form-grid">
        <div class="fields-col">
            <div class="form-group">
                <label class="form-label">Nombre del Producto</label>
                <input type="text" class="form-input" placeholder="Nombre del Producto">
            </div>

            <div class="form-group">
                <label class="form-label">Código de Barras 1</label>
                <div class="input-with-action">
                    <input type="text" class="form-input" placeholder="Código de Barras">
                    <span class="remove-x">✖</span>
                </div>
                <div style="margin-top:5px;">
                     <span class="action-link">Agregar otro código de barras</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Marca</label>
                <select class="form-select"><option>Seleccione la Marca</option></select>
            </div>

            <div class="form-group">
                <label class="form-label">Línea</label>
                <select class="form-select"><option>Seleccione la Linea</option></select>
            </div>

            <div class="form-group">
                <label class="form-label">Categoría</label>
                <select class="form-select"><option>Seleccione la Categoria</option></select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Proveedor</label>
                <select class="form-select"><option>Seleccione el Proveedor</option></select>
            </div>

            <div class="form-group">
                <label class="form-label">Tipos de Producto</label>
                <select class="form-select"><option>Tipo de Productos ...</option></select>
                <div style="font-size:12px; color:#6b7280; margin-top:4px;">No ha Seleccionado Tipos de Producto</div>
            </div>

             <div>
                <a href="#" class="info-link">Ver Más Información del Producto ⌄</a>
            </div>
        </div>

        <!-- Right Column: Image & SKU -->
        <div class="fields-col">
            <div class="image-upload-box">
                <div class="image-placeholder">
                    <span>Cargar Imagen</span>
                </div>
                <div style="margin-top:5px;">Máximo 0.2MB</div>
            </div>

            <div class="form-group">
                <label class="form-label">SKU</label>
                <input type="text" class="form-input" value="34">
            </div>
            
            <div class="switch-container">
                <label class="toggle-switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
                <span class="switch-label">Producto Activo</span>
            </div>
        </div>
    </div>
</div>

<!-- Section 2: Cantidades -->
<div class="form-section">
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f3f4f6; padding-bottom:10px; margin-bottom:15px;">
        <div class="section-title" style="border:none; margin:0; padding:0;">Cantidades</div>
        <div class="switch-container" style="margin:0;">
             <label class="toggle-switch">
                <input type="checkbox" checked>
                <span class="slider"></span>
            </label>
            <span class="switch-label">Activar Control de Cantidades</span>
        </div>
    </div>

    <table class="inner-table">
        <thead>
            <tr>
                <th style="width:30%;"></th>
                <th>Cantidad Inicial</th>
                <th>Alerta Cantidad Mínima</th>
                <th>Precio de Compra</th>
                <th>Activo en sede</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="display:flex; align-items:center; gap:10px;">
                    <span style="font-size:16px;">🏛️</span>
                    <span style="font-size:13px; font-weight:600;">Sede Holguínes Trade Center.</span>
                </td>
                <td><input type="number" class="form-input" value="0" style="width:80px;"></td>
                <td><input type="number" class="form-input" value="0" style="width:80px;"></td>
                <td><input type="text" class="form-input" value="$ 0" style="width:100px;"></td> <!-- Text for currency format -->
                <td>
                    <label class="toggle-switch" style="transform:scale(0.8);">
                        <input type="checkbox" checked>
                        <span class="slider"></span>
                    </label>
                </td>
                <td><button class="btn-apply">Aplicar</button></td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top:20px; display:flex; flex-direction:column; gap:10px;">
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Vender Cantidades en Negativo</span>
        </div>
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Bloquear Cantidad en Caja</span>
        </div>
    </div>
</div>

<!-- Section 3: Información de Venta -->
<div class="form-section">
    <div class="section-title">Información de Venta</div>
    
    <div style="display:flex; gap:20px; align-items:center; margin-bottom:20px;">
        <div style="flex:1; display:flex; align-items:center; gap:10px;">
             <span style="font-size:16px;">🏛️</span>
             <label class="form-label" style="min-width:40px;">Sedes</label>
             <select class="form-select">
                 <option>Holguínes Trade Center.</option>
             </select>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" id="apply_all">
            <label for="apply_all" style="font-size:13px; color:#4b5563;">Aplicar este precio(s) a todas las sedes</label>
        </div>
    </div>

    <table class="inner-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Precio de Venta a Cliente</th>
                <th>Precio de Oferta</th>
                <th>Bloquear Precio en Caja</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" class="form-input" value="Titulo Precio de Venta"></td>
                <td><input type="text" class="form-input" value="$ 0"></td>
                <td><input type="text" class="form-input" value="$ 0"></td>
                <td style="text-align:center;"><input type="checkbox"></td>
                <td style="text-align:center;"><span class="remove-x">✖</span></td>
            </tr>
        </tbody>
    </table>
    
    <div style="margin-top:15px;">
        <button class="btn-cancel" style="border-color:#1a73e8; color:#1a73e8;">Crear Nuevo Precio de Venta</button>
    </div>
    
    <div style="margin-top:20px;">
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Activar Comisiones Para el Personal</span>
        </div>
    </div>
</div>

<!-- Section 4: Impuesto de Venta -->
<div class="form-section">
    <div class="section-title">Impuesto de Venta</div>
    <div class="form-group">
        <select class="form-select" style="max-width:300px;"><option>No Aplica</option></select>
    </div>
</div>

<!-- Section 5: Other Toggles -->
<div class="form-section">
    <div style="display:flex; flex-direction:column; gap:15px;">
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Activar Variantes del Producto</span>
        </div>
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Activar Puntos de Fidelización</span>
        </div>
        <div class="switch-container">
            <label class="toggle-switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
            <span class="switch-label">Activar Tienda en Línea</span>
        </div>
    </div>
</div>

@endsection
