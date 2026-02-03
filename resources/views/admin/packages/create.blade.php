@extends('admin/dashboard_layout')

@section('content')

<?php
    // Cargar especialistas para la pestaña de especialistas
    try {
        $specialists_list = \App\Models\Specialist::where('active', 1)->orderBy('name')->get();
    } catch (\Exception $e) {
        $specialists_list = collect([]);
    }
    
    // Modo edición o creación
    $isEdit = isset($package) && $package;
    $pkg = $isEdit ? $package : null;
?>

<style>
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-container {
        background: white;
        width: 95%;
        max-width: 1000px;
        height: 90vh;
        max-height: 900px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        margin: auto;
    }
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
    }
    .modal-body {
        flex: 1;
        overflow-y: auto;
        padding: 0;
    }
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9fafb;
    }
    
    /* Tabs */
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        overflow-x: auto;
    }
    .tab-btn {
        padding: 14px 20px;
        border: none;
        background: none;
        font-size: 13px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        white-space: nowrap;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .tab-btn:hover { color: #1a73e8; }
    .tab-btn.active {
        color: #1a73e8;
        border-bottom-color: #1a73e8;
        background: white;
    }
    
    .tab-content {
        display: none;
        padding: 24px;
    }
    .tab-content.active { display: block; }
    
    /* Form Elements */
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 8px;
        color: #374151;
    }
    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }
    .form-input:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }
    
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }
    
    /* Toggle Switch */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .toggle-label {
        font-size: 14px;
        color: #374151;
    }
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
    }
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
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
        transition: 0.3s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #1a73e8;
    }
    input:checked + .slider:before {
        transform: translateX(20px);
    }
    
    /* Specialists Grid */
    .specialists-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-top: 15px;
    }
    .specialist-checkbox {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
    }
    .specialist-checkbox:hover {
        background: #f3f4f6;
    }
    .specialist-checkbox input:checked + span {
        font-weight: 600;
        color: #1a73e8;
    }
    
    /* Buttons */
    .btn-cancel {
        background: white;
        color: #dc2626;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: 1px solid #dc2626;
        cursor: pointer;
    }
    .btn-cancel:hover { background: #fef2f2; }
    
    .btn-delete {
        background: #dc2626;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-delete:hover { background: #b91c1c; }
    
    .btn-save {
        background: #1a73e8;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-save:hover { background: #1557b0; }
    
    /* Radio buttons */
    .radio-group {
        margin-top: 10px;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        cursor: pointer;
        font-size: 14px;
    }
    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        accent-color: #1a73e8;
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
    }
    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    /* Error box */
    .error-box {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
        padding: 15px;
        border-radius: 8px;
        margin: 20px;
    }
</style>

<div class="modal-overlay" style="position:fixed; background:rgba(0,0,0,0.6); display:flex; align-items:center; justify-content:center;">
    <div class="modal-container">
        
        <div class="modal-header">
            <h2 class="modal-title">{{ $isEdit ? 'Editar' : 'Agregar' }} Servicio</h2>
            <a href="{{ url('admin/packages') }}" class="modal-close">&times;</a>
        </div>
        
        @if($errors->any())
        <div class="error-box">
            <strong>Error:</strong>
            <ul style="margin:5px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ $isEdit ? url('admin/packages/'.$pkg->id.'/update') : url('admin/packages') }}" method="POST" id="packageForm">
            {{ csrf_field() }}
            
            <div class="tabs-nav">
                <button type="button" class="tab-btn active" onclick="switchTab('detalles')">Detalles</button>
                <button type="button" class="tab-btn" onclick="switchTab('especialistas')">Especialistas</button>
                <button type="button" class="tab-btn" onclick="switchTab('sedes')">Sedes</button>
                <button type="button" class="tab-btn" onclick="switchTab('precio')">Precio</button>
                <button type="button" class="tab-btn" onclick="switchTab('productos')">Productos</button>
                <button type="button" class="tab-btn" onclick="switchTab('descuentos')">Descuentos Administrativos</button>
                <button type="button" class="tab-btn" onclick="switchTab('configuracion')">Configuración</button>
            </div>
            
            <div class="modal-body">
                
                <!-- TAB: DETALLES -->
                <div id="tab-detalles" class="tab-content active">
                    <div class="form-grid-2">
                        <div>
                            <div class="form-group">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="package_name" class="form-input" 
                                       value="{{ $isEdit ? $pkg->package_name : old('package_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">SKU</label>
                                <input type="text" name="sku" class="form-input" placeholder="SKU"
                                       value="{{ $isEdit ? ($pkg->sku ?? '') : old('sku') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Categoría</label>
                                <select name="category" class="form-input">
                                    <option value="">Sin categoría</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ ($isEdit && $pkg->category == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="form-group">
                                <label class="form-label">Descripción</label>
                                <textarea name="package_description" class="form-input" rows="5" placeholder="Descripción">{{ $isEdit ? $pkg->package_description : old('package_description') }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-grid-3" style="margin-top:20px;">
                        <div class="form-group">
                            <label class="form-label">Precio ($) *</label>
                            <input type="number" name="package_price" class="form-input" 
                                   value="{{ $isEdit ? $pkg->package_price : old('package_price', 0) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Duración (min) *</label>
                            <input type="number" name="package_time" class="form-input" 
                                   value="{{ $isEdit ? $pkg->package_time : old('package_time', 30) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Comisión (%)</label>
                            <input type="number" name="commission_percentage" class="form-input" step="0.01"
                                   value="{{ $isEdit ? ($pkg->commission_percentage ?? 0) : old('commission_percentage', 0) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Prioridad (Orden POS)</label>
                            <input type="number" name="display_order" class="form-input"
                                   value="{{ $isEdit ? ($pkg->display_order ?? 0) : old('display_order', 0) }}">
                        </div>
                    </div>
                    
                    <div style="margin-top:30px;">
                        <div class="toggle-row">
                            <span class="toggle-label">Servicio Activo</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="active" value="1" {{ (!$isEdit || $pkg->active) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <span class="toggle-label">Activar para Reservas en Línea</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="show_in_reservations" value="1" {{ (!$isEdit || $pkg->show_in_reservations) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="toggle-row">
                            <span class="toggle-label">Desea para este servicio aplicar una comisión diferente a la de la categoría</span>
                            <label class="toggle-switch">
                                <input type="checkbox" name="custom_commission" value="1" {{ ($isEdit && $pkg->custom_commission) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- TAB: ESPECIALISTAS -->
                <div id="tab-especialistas" class="tab-content">
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Sede</label>
                            <select class="form-input" name="location_filter" id="locationFilter">
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Especialidad</label>
                            <select class="form-input">
                                <option value="">Especialidades ...</option>
                                <option>Manicurista</option>
                                <option>Estilista</option>
                                <option>Esteticista</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin:20px 0;">
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" id="applyAllSpecialists" style="width:18px; height:18px;">
                            <span>Aplicar Servicio a todos los Especialistas en todas las sedes</span>
                        </label>
                    </div>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                        <div>
                            <strong>Seleccione Especialista:</strong>
                            <label style="display:inline-flex; align-items:center; gap:5px; margin-left:15px; cursor:pointer;">
                                <input type="checkbox" id="selectAllSpecialists" style="width:16px; height:16px; accent-color:#1a73e8;">
                                <span style="color:#1a73e8; font-weight:600;">Todas</span>
                            </label>
                        </div>
                        <a href="{{ url('admin/specialists/create') }}" style="color:#1a73e8; text-decoration:none; font-size:13px;">Crear Especialista</a>
                    </div>
                    
                    <div class="specialists-grid">
                        @foreach($specialists_list as $sp)
                        <label class="specialist-checkbox">
                            <input type="checkbox" name="specialists[]" value="{{ $sp->id }}"
                                {{ ($isEdit && $pkg->specialists && $pkg->specialists->contains($sp->id)) ? 'checked' : '' }}>
                            <span>{{ $sp->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    
                    @if($specialists_list->count() == 0)
                    <div class="empty-state">
                        <div class="empty-icon">👤</div>
                        <p>No hay especialistas registrados</p>
                        <a href="{{ url('admin/specialists/create') }}" style="color:#1a73e8;">Crear Especialista</a>
                    </div>
                    @endif
                </div>
                
                <!-- TAB: SEDES -->
                <div id="tab-sedes" class="tab-content">
                    <div class="form-group">
                        <label class="form-label">Seleccione las sedes donde aplica este servicio:</label>
                        <div style="margin-top:15px; display: flex; flex-direction: column; gap: 10px;">
                            @foreach($locations as $loc)
                            <label style="display:flex; align-items:center; gap:10px; padding:12px; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer;">
                                <input type="checkbox" name="sedes[]" value="{{ $loc->id }}" checked style="width:18px; height:18px; accent-color:#1a73e8;">
                                <span>{{ $loc->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- TAB: PRECIO -->
                <div id="tab-precio" class="tab-content">
                    <div class="form-grid-2" style="margin-bottom:20px;">
                        <div class="form-group">
                            <select class="form-input">
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="checkbox" style="width:16px; height:16px;">
                                <span>Aplicar este precio(s) a todas las sedes</span>
                            </label>
                        </div>
                    </div>
                    
                    <table style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                <th style="padding:12px; text-align:left;">Título</th>
                                <th style="padding:12px; text-align:left;">Precio de Venta a Cliente</th>
                                <th style="padding:12px; text-align:left;">Precio de Oferta</th>
                                <th style="padding:12px; text-align:left;">Duración</th>
                                <th style="padding:12px; text-align:center;">Bloquear Precio en Caja</th>
                                <th style="padding:12px; text-align:center;">Mostrar en Reservas</th>
                                <th style="padding:12px;"></th>
                            </tr>
                        </thead>
                        <tbody id="pricesTable">
                            <tr>
                                <td style="padding:12px;"><input type="text" class="form-input" placeholder="Título Precio de Venta" style="width:150px;"></td>
                                <td style="padding:12px;"><input type="number" class="form-input" value="{{ $isEdit ? $pkg->package_price : 0 }}" style="width:100px;"></td>
                                <td style="padding:12px;"><input type="number" class="form-input" value="0" style="width:80px;"></td>
                                <td style="padding:12px;">
                                    <select class="form-input" style="width:100px;">
                                        <option>30m</option>
                                        <option>45m</option>
                                        <option>1h</option>
                                        <option>1h30m</option>
                                        <option>2h</option>
                                    </select>
                                </td>
                                <td style="padding:12px; text-align:center;"><input type="checkbox"></td>
                                <td style="padding:12px; text-align:center;"><input type="checkbox" checked></td>
                                <td style="padding:12px; text-align:center;"><button type="button" style="color:#dc2626; background:none; border:none; cursor:pointer; font-size:18px;">&times;</button></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <button type="button" onclick="addPriceRow()" style="margin-top:15px; padding:10px 20px; background:white; border:1px solid #d1d5db; border-radius:8px; cursor:pointer;">
                        Crear Nuevo Precio de Venta
                    </button>
                </div>
                
                <!-- TAB: RECETA / COSTOS (Ex-Productos) -->
                <div id="tab-productos" class="tab-content">
                    <div class="form-group">
                        <label class="form-label" style="font-size:16px;">Receta del Servicio (Control de Inventario)</label>
                        <p style="font-size:13px; color:#6b7280; margin-bottom:15px;">Selecciona los productos que se <strong>descontarán automáticamente</strong> del inventario cada vez que se venda este servicio.</p>
                        
                        <div style="display:flex; gap:10px; align-items:flex-end; background:#f3f4f6; padding:15px; border-radius:8px;">
                            <div style="flex:1;">
                                <label class="form-label">Producto / Insumo</label>
                                <select id="recipeProductSelect" class="form-input">
                                    <option value="">Seleccione Insumo ...</option>
                                    @foreach(\App\Models\Product::orderBy('name')->get() as $prod)
                                        <option value="{{ $prod->id }}" data-unit="{{ $prod->unit ?? 'Unidad' }}">{{ $prod->name }} (Stock: {{ $prod->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="width:120px;">
                                <label class="form-label">Cantidad</label>
                                <input type="number" id="recipeQuantity" class="form-input" placeholder="0" step="0.1">
                            </div>
                            <div style="width:100px;">
                                <label class="form-label">Unidad</label>
                                <input type="text" id="recipeUnit" class="form-input" readonly value="Unidad" style="background:#e5e7eb;">
                            </div>
                            <button type="button" onclick="addRecipeItem()" class="btn-save" style="height:42px; display:flex; align-items:center;">+ Agregar</button>
                        </div>
                    </div>
                    
                    <table style="width:100%; border-collapse:collapse; font-size:13px; margin-top:20px; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid #e5e7eb;">
                                <th style="padding:12px; text-align:left;">Producto</th>
                                <th style="padding:12px; text-align:left;">Cantidad a Descontar</th>
                                <th style="padding:12px; text-align:center;">Opción</th>
                            </tr>
                        </thead>
                        <tbody id="recipeTableBody">
                            <!-- Existing Recipes (if editing) -->
                            @if(isset($pkg) && $pkg->recipes)
                                @foreach($pkg->recipes as $index => $recipe)
                                <tr id="recipeRow_existing_{{ $recipe->id }}">
                                    <td style="padding:12px;">
                                        {{ $recipe->product->name ?? 'Producto Eliminado' }}
                                        <input type="hidden" name="recipes[{{ $index }}][product_id]" value="{{ $recipe->product_id }}">
                                    </td>
                                    <td style="padding:12px;">
                                        <input type="number" name="recipes[{{ $index }}][quantity]" value="{{ $recipe->quantity }}" class="form-input" style="width:80px; display:inline-block;" step="0.01">
                                        {{ $recipe->unit }}
                                        <input type="hidden" name="recipes[{{ $index }}][unit]" value="{{ $recipe->unit }}">
                                    </td>
                                    <td style="padding:12px; text-align:center;">
                                        <button type="button" onclick="document.getElementById('recipeRow_existing_{{ $recipe->id }}').remove()" style="color:#ef4444; font-weight:bold;">&times; Quitar</button>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    
                    <div id="emptyRecipeState" class="empty-state" style="{{ (isset($pkg) && $pkg->recipes && count($pkg->recipes) > 0) ? 'display:none;' : '' }}">
                        <div class="empty-icon" style="opacity:0.6;">🧪</div>
                        <p><strong>No hay receta definida</strong></p>
                        <p style="font-size:13px;">Agrega productos para calcular costos y descontar inventario.</p>
                    </div>
                </div>
                
                <!-- TAB: DESCUENTOS ADMINISTRATIVOS -->
                <div id="tab-descuentos" class="tab-content">
                    <div style="margin-bottom:20px;">
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" checked style="width:16px; height:16px;">
                            <span>Aplicar estos Descuentos Administrativos a todas las sedes</span>
                        </label>
                    </div>
                    
                    <div class="empty-state">
                        <div class="empty-icon">✏️</div>
                        <p><strong>Descuentos Administrativos</strong></p>
                        <p style="color:#9ca3af;">Agrege descuentos</p>
                        <button type="button" class="btn-save" style="margin-top:15px;">Agregar Descuento</button>
                    </div>
                </div>
                
                <!-- TAB: CONFIGURACIÓN -->
                <div id="tab-configuracion" class="tab-content">
                    <div class="toggle-row">
                        <span class="toggle-label">Bloquear Cantidad en Caja</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="block_qty_pos" value="1" {{ ($isEdit && $pkg->block_qty_pos) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div class="toggle-row">
                        <span class="toggle-label">Require abono para reservas en línea</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="require_deposit" value="1" {{ ($isEdit && $pkg->require_deposit) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    
                    <div style="margin-top:25px;">
                        <label class="form-label">Servicio con Horario Extendido</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="extended_schedule" value="no_aplica" {{ ($isEdit && $pkg->extended_schedule == 'no_aplica') ? 'checked' : '' }}>
                                <span>No Aplica</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="extended_schedule" value="agenda" {{ (!$isEdit || $pkg->extended_schedule == 'agenda') ? 'checked' : '' }}>
                                <span>Agenda/Calendario (Recomendado)</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="extended_schedule" value="reservas">
                                <span>Reservas en Línea</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="extended_schedule" value="agenda_reservas" {{ ($isEdit && $pkg->extended_schedule == 'agenda_reservas') ? 'checked' : '' }}>
                                <span>Agenda y Reservas en Línea</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="toggle-row" style="margin-top:25px;">
                        <span class="toggle-label">Activar Fidelización</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="enable_loyalty" value="1" {{ ($isEdit && $pkg->enable_loyalty) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <a href="{{ url('admin/packages') }}" class="btn-cancel">Cancelar</a>
                <div style="display:flex; gap:12px;">
                    @if($isEdit)
                    <button type="button" class="btn-delete" onclick="confirmDelete()">Eliminar</button>
                    @endif
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($isEdit)
<form id="deleteForm" method="POST" action="{{ url('admin/packages/'.$pkg->id.'/delete') }}" style="display:none;">
    {{ csrf_field() }}
</form>
@endif

<script>
function switchTab(tabName, event) {
    document.querySelectorAll('.tab-content').forEach(div => div.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-' + tabName).classList.add('active');
    
    if (event) {
        event.target.classList.add('active');
    } else {
        // Encontrar el botón y activarlo
        document.querySelectorAll('.tab-btn').forEach(btn => {
            if (btn.getAttribute('onclick') && btn.getAttribute('onclick') === "switchTab('" + tabName + "')") {
                btn.classList.add('active');
            }
        });
    }
}

function confirmDelete() {
    if(confirm('¿Estás seguro de eliminar este servicio?')) {
        document.getElementById('deleteForm').submit();
    }
}

// Select all specialists
document.getElementById('selectAllSpecialists')?.addEventListener('change', function() {
    document.querySelectorAll('input[name="specialists[]"]').forEach(cb => cb.checked = this.checked);
});

// Dynamic price rows
var priceRowIndex = 1;

function addPriceRow() {
    priceRowIndex++;
    var tbody = document.getElementById('pricesTable');
    var newRow = document.createElement('tr');
    newRow.id = 'priceRow_' + priceRowIndex;
    newRow.innerHTML = `
        <td style="padding:12px;"><input type="text" name="prices[${priceRowIndex}][title]" class="form-input" placeholder="Título Precio ${priceRowIndex}" style="width:150px;"></td>
        <td style="padding:12px;"><input type="number" name="prices[${priceRowIndex}][price]" class="form-input" value="0" style="width:100px;"></td>
        <td style="padding:12px;"><input type="number" name="prices[${priceRowIndex}][offer]" class="form-input" value="0" style="width:80px;"></td>
        <td style="padding:12px;">
            <select name="prices[${priceRowIndex}][duration]" class="form-input" style="width:100px;">
                <option value="30">30m</option>
                <option value="45">45m</option>
                <option value="60">1h</option>
                <option value="90">1h30m</option>
                <option value="120">2h</option>
            </select>
        </td>
        <td style="padding:12px; text-align:center;"><input type="checkbox" name="prices[${priceRowIndex}][block_price]"></td>
        <td style="padding:12px; text-align:center;"><input type="checkbox" name="prices[${priceRowIndex}][show_reservations]" checked></td>
        <td style="padding:12px; text-align:center;"><button type="button" onclick="removePriceRow(${priceRowIndex})" style="color:#dc2626; background:none; border:none; cursor:pointer; font-size:18px;">&times;</button></td>
    `;
    tbody.appendChild(newRow);
}

function removePriceRow(index) {
    var row = document.getElementById('priceRow_' + index);
    if (row) {
        row.remove();
    }
}

// Logic for Recipes
var recipeIndex = 1000;

document.getElementById('recipeProductSelect')?.addEventListener('change', function() {
    var option = this.options[this.selectedIndex];
    var unit = option.getAttribute('data-unit') || 'Unidad';
    document.getElementById('recipeUnit').value = unit;
});

function addRecipeItem() {
    var select = document.getElementById('recipeProductSelect');
    var productId = select.value;
    var productName = select.options[select.selectedIndex].text;
    var qty = document.getElementById('recipeQuantity').value;
    var unit = document.getElementById('recipeUnit').value;

    if (!productId || qty <= 0) {
        alert("Por favor selecciona un producto y una cantidad válida mayor a 0.");
        return;
    }

    recipeIndex++;
    var tbody = document.getElementById('recipeTableBody');
    var row = document.createElement('tr');
    row.id = 'recipeRow_' + recipeIndex;
    row.innerHTML = `
        <td style="padding:12px;">
            ${productName}
            <input type="hidden" name="recipes[${recipeIndex}][product_id]" value="${productId}">
        </td>
        <td style="padding:12px;">
            <input type="number" name="recipes[${recipeIndex}][quantity]" value="${qty}" class="form-input" style="width:80px; display:inline-block;" step="0.01">
            ${unit}
            <input type="hidden" name="recipes[${recipeIndex}][unit]" value="${unit}">
        </td>
        <td style="padding:12px; text-align:center;">
            <button type="button" onclick="document.getElementById('recipeRow_${recipeIndex}').remove()" style="color:#ef4444; font-weight:bold;">&times; Quitar</button>
        </td>
    `;
    tbody.appendChild(row);

    // Clear inputs
    select.value = "";
    document.getElementById('recipeQuantity').value = "";
    document.getElementById('emptyRecipeState').style.display = 'none';
}
</script>

@endsection
