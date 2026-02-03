@extends('admin/dashboard_layout')

@section('content')

<?php
    // Cargar servicios (packages) para la pestaña de servicios
    try {
        $packages = \App\Models\Package::orderBy('package_name')->get();
    } catch (\Exception $e) {
        $packages = collect([]);
    }
    
    // Modo edición o creación
    $isEdit = isset($specialist) && $specialist;
    $sp = $isEdit ? $specialist : null;
?>

<!-- Flatpickr for "WOW" time picker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    /* Layout */
    .create-wrapper {
        display: flex;
        gap: 30px;
        align-items: flex-start;
    }

    /* Left Menu */
    .left-menu {
        width: 250px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .photo-section {
        padding: 25px;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
    }

    .photo-preview {
        width: 150px;
        height: 200px;
        margin: 0 auto 15px;
        border-radius: 12px;
        overflow: hidden;
        border: 3px solid #e5e7eb;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-placeholder {
        color: white;
        font-size: 48px;
        font-weight: 600;
    }

    .photo-upload-text {
        font-size: 11px;
        color: #9ca3af;
        margin-bottom: 10px;
    }

    .btn-remove-photo {
        background: #ef4444;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        border: none;
        font-size: 12px;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .specialist-name-display {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .menu-item {
        display: block;
        padding: 15px 20px;
        color: #4b5563;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        border-left: 3px solid transparent;
        cursor: pointer;
        transition: background 0.2s;
    }

    .menu-item:hover { background: #f9fafb; }

    .menu-item.active {
        color: #1a73e8;
        background: #eff6ff;
        border-left-color: #1a73e8;
        font-weight: 600;
    }

    /* Right Content */
    .form-content {
        flex: 1;
        background: white;
        border-radius: 8px;
        padding: 30px;
        min-height: 500px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* General Form Elements */
    .section-title { font-size: 16px; font-weight: 600; margin-bottom: 20px; color: #1f2937; }
    
    .btn-create {
        background-color: #2e7d32;
        color: white;
        padding: 10px 30px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: background 0.2s;
    }
    .btn-create:hover { background-color: #1b5e20; }

    .btn-delete {
        background-color: #ef4444;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-cancel {
        border: 1px solid #ef4444; color: #ef4444; background: white;
        padding: 10px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none;
        font-size: 14px;
    }

    /* Tab Content Visibility */
    .tab-content { display: none; }
    .tab-content.active { display: block; }
    
    /* Forms */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 80px; gap: 15px; }
    .form-group { margin-bottom: 20px; }
    .label { display: block; font-size: 13px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .input { 
        width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; 
        border-radius: 6px; font-size: 14px; color: #374151; 
        transition: border-color 0.2s;
    }
    .input:focus { border-color: #1a73e8; outline: none; }
    
    /* Toggles */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .toggle-label { font-size: 14px; font-weight: 500; color: #374151; }

    .toggle-switch { position: relative; width: 50px; height: 26px; }
    .toggle-switch input { opacity: 0; width: 0; height: 0; }
    .slider { 
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; 
        background-color: #ccc; transition: .3s; border-radius: 26px; 
    }
    .slider:before { 
        position: absolute; content: ""; height: 20px; width: 20px; 
        left: 3px; bottom: 3px; background-color: white; 
        transition: .3s; border-radius: 50%; 
    }
    input:checked + .slider { background-color: #1a73e8; }
    input:checked + .slider:before { transform: translateX(24px); }

    /* Specialties chips */
    .chip-container { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .chip {
        background: #eff6ff;
        color: #1a73e8;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .chip-remove {
        color: #ef4444;
        cursor: pointer;
        font-weight: bold;
    }

    /* Services */
    .service-category {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 10px;
        overflow: hidden;
    }
    .service-category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 15px;
        background: #f9fafb;
        cursor: pointer;
    }
    .cat-name { color: #1a73e8; font-weight: 700; font-size: 14px; }
    .mark-all-btn { 
        background: #1a73e8; color: white; padding: 4px 12px; 
        border-radius: 12px; font-size: 11px; font-weight: 600; 
        cursor: pointer; border: none;
    }
    .service-items {
        padding: 15px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .service-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #4b5563;
    }
    .service-item input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #1a73e8;
    }

    /* Schedule Modern Design */
    .schedule-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .schedule-row {
        display: flex; 
        align-items: center; 
        justify-content: space-between;
        padding: 16px 20px; 
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: all 0.2s ease;
    }
    .schedule-row:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }
    .schedule-row.inactive {
        background: #f9fafb;
        opacity: 0.8;
    }
    .day-toggle { 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        width: 180px;
    }
    .day-name {
        font-weight: 700; 
        font-size: 15px;
        color: #1f2937;
    }
    .time-inputs { 
        display: flex; 
        align-items: center; 
        gap: 12px; 
    }
    .time-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    .time-input { 
        padding: 12px 20px; 
        border: 2px solid #f1f5f9; 
        border-radius: 50px; 
        color: #1e293b; 
        width: 140px; 
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        background: #f8fafc;
        transition: all 0.2s ease;
        text-align: center;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .time-input:hover {
        border-color: #cbd5e1;
        background: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .time-input:focus {
        border-color: #1a73e8;
        outline: none;
        background: #fff;
    }
    .time-icon {
        display: none;
    }
    
    /* Clean iOS White Time Picker Design - Spacious Edition */
    .flatpickr-calendar.hasTime.noCalendar {
        width: 280px !important; /* Increased from 200px */
        border-radius: 24px !important;
        border: none !important;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15) !important;
        padding: 20px !important; /* More breathing room */
        background: #ffffff !important;
    }

    .flatpickr-time {
        height: 220px !important; /* Increased from 180px */
        max-height: 220px !important;
        background: #ffffff !important;
        border-radius: 12px !important;
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 10px !important; /* Side padding */
    }

    /* Central Highlight Line (iOS Style) */
    .flatpickr-time::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 10px;
        right: 10px;
        height: 60px; /* Increased from 50px */
        margin-top: -30px;
        background: rgba(0,0,0,0.03) !important; 
        border-top: 1px solid rgba(0,0,0,0.06) !important;
        border-bottom: 1px solid rgba(0,0,0,0.06) !important;
        pointer-events: none;
        z-index: 10;
        border-radius: 12px;
    }
    
    /* Remove old glass effect */
    .flatpickr-time::after {
        display: none !important;
    }

    /* Time Inputs (Hours/Minutes) */
    .flatpickr-time .flatpickr-hour, 
    .flatpickr-time .flatpickr-minute, 
    .flatpickr-time .flatpickr-am-pm {
        font-size: 36px !important; /* Increased from 30px */
        font-weight: 400 !important; /* Thinner iOS style */
        color: #1f2937 !important; 
        height: 100% !important;
        z-index: 5 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: ns-resize !important;
        letter-spacing: -0.5px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        text-shadow: none !important;
        transition: color 0.2s ease;
    }

    /* Hover/Focus States */
    .flatpickr-time .flatpickr-hour:hover, 
    .flatpickr-time .flatpickr-minute:hover, 
    .flatpickr-time .flatpickr-am-pm:hover,
    .flatpickr-time .flatpickr-hour:focus, 
    .flatpickr-time .flatpickr-minute:focus, 
    .flatpickr-time .flatpickr-am-pm:focus {
        background: transparent !important;
        color: #000000 !important;
        font-weight: 500 !important;
    }

    .flatpickr-time input {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-family: inherit !important;
    }
    
    .flatpickr-time .numInputWrapper span {
        display: none !important;
    }

    /* Colon Separator */
    .flatpickr-time .flatpickr-time-separator {
        font-size: 32px !important; /* Increased */
        font-weight: 300 !important;
        color: #d1d5db !important; /* Very Light Grey */
        z-index: 5 !important;
        padding-bottom: 6px; 
    }

    /* Arrow Buttons (Standard Flatpickr) - Ensure they match */
    .numInputWrapper:hover {
        background: transparent !important;
    }

    .time-separator {
        font-weight: 600;
        color: #9ca3af;
        font-size: 14px;
        margin: 0 5px;
    }

    /* Tooltip/Label for Copy All */
    .btn-copy-all {
        background: #eff6ff;
        color: #1a73e8;
        border: 1px dashed #1a73e8;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-copy-all:hover {
        background: #1a73e8;
        color: white;
        border-style: solid;
    }

    /* Sub tabs */
    .sub-tabs { display: flex; gap: 30px; border-bottom: 2px solid #e5e7eb; margin-bottom: 20px; }
    .sub-tab { 
        padding-bottom: 10px; font-weight: 600; color: #6b7280; 
        cursor: pointer; margin-bottom: -2px; border-bottom: 2px solid transparent;
    }
    .sub-tab.active { color: #1f2937; border-bottom-color: #1a73e8; }

    /* Header Actions */
    .header-actions {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        align-items: center;
    }
    .breadcrumb { font-size: 14px; color: #1a73e8; }
    .action-buttons { display: flex; gap: 10px; }

    /* Blocks */
    .block-card {
        background: #fdf2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .block-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .block-date { font-weight: 700; color: #991b1b; font-size: 13px; }
    .block-time { color: #b91c1c; font-size: 12px; }
    .block-reason { color: #6b7280; font-size: 11px; font-style: italic; }
    .btn-remove-block { color: #ef4444; cursor: pointer; font-weight: bold; font-size: 18px; border: none; background: none; }

    /* Exception Cards */
    .exception-card {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .exception-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .exception-date { font-weight: 700; color: #065f46; font-size: 13px; }
    .exception-time { color: #047857; font-size: 12px; }
    .btn-remove-exception { color: #ef4444; cursor: pointer; font-weight: bold; font-size: 18px; border: none; background: none; }

    /* Modal */
    .custom-modal-overlay {
        display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); z-index: 10000; align-items: center; justify-content: center;
    }
    .modal-content-small {
        background: white; padding: 30px; border-radius: 12px; width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
</style>

<form method="POST" action="{{ $isEdit ? url('admin/specialists/'.$sp->id.'/update') : url('admin/specialists') }}" enctype="multipart/form-data" id="specialistForm">
    {{ csrf_field() }}

    <!-- Header Actions -->
    <div class="header-actions">
        <div class="breadcrumb">Especialistas / {{ $isEdit ? 'Edición' : 'Creación' }}</div>
        <div class="action-buttons">
            <a href="{{ url('admin/specialists') }}" class="btn-cancel">Cancelar</a>
            @if($isEdit)
                <button type="button" class="btn-delete" onclick="confirmDelete()">Eliminar</button>
            @endif
            <button type="submit" class="btn-create">{{ $isEdit ? 'Guardar' : 'Crear' }}</button>
        </div>
    </div>

    <!-- Mostrar errores si existen -->
    @if($errors->any())
    <div style="background:#fee2e2; border:1px solid #ef4444; color:#991b1b; padding:15px; border-radius:8px; margin-bottom:20px;">
        <strong>Error:</strong>
        <ul style="margin:5px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('success'))
    <div style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:15px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
    @endif

    <div class="create-wrapper">
        <!-- LEFT MENU -->
        <div class="left-menu">
            <div class="photo-section">
                <div class="photo-preview" id="photoPreview">
                    @if($isEdit && $sp->avatar)
                        <img src="{{ $sp->avatar }}" alt="{{ $sp->name }}" id="previewImg">
                    @else
                        <span class="photo-placeholder" id="photoPlaceholder">?</span>
                    @endif
                </div>
                <div class="photo-upload-text">Máximo 0.3MB</div>
                <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none" onchange="previewAvatar(this)">
                <button type="button" class="btn-remove-photo" onclick="document.getElementById('avatarInput').click()">
                    {{ $isEdit && $sp->avatar ? 'Cambiar' : 'Subir' }} Foto
                </button>
                <div class="specialist-name-display" id="nameDisplay">
                    {{ $isEdit ? $sp->name : 'Nuevo Especialista' }}
                </div>
            </div>

            <div onclick="switchTab('miembros')" id="menu-miembros" class="menu-item active">Miembros</div>
            <div onclick="switchTab('sedes')" id="menu-sedes" class="menu-item">Sedes</div>
            <div onclick="switchTab('servicios')" id="menu-servicios" class="menu-item">Servicios</div>
            <div onclick="switchTab('comisiones')" id="menu-comisiones" class="menu-item">Comisiones</div>
            <div onclick="switchTab('horario')" id="menu-horario" class="menu-item">Horario</div>
            <div onclick="switchTab('usuario')" id="menu-usuario" class="menu-item">Usuario Móvil</div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="form-content">
            
            <!-- === TAB 1: MIEMBROS === -->
            <div id="tab-miembros" class="tab-content active">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="label">Nombre *</label>
                        <input type="text" name="name" class="input" placeholder="Nombre" 
                               value="{{ $isEdit ? $sp->name : old('name') }}" required
                               oninput="updateNameDisplay(this.value)">
                    </div>
                    <div class="form-group">
                        <label class="label">Apellido</label>
                        <input type="text" name="last_name" class="input" placeholder="Apellido"
                               value="{{ $isEdit ? ($sp->last_name ?? '') : old('last_name') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="label">Tipo Id.</label>
                        <select name="id_type" class="input">
                            <option value="cc" {{ ($isEdit && $sp->id_type == 'cc') ? 'selected' : '' }}>Cédula de ciudadanía</option>
                            <option value="ce" {{ ($isEdit && $sp->id_type == 'ce') ? 'selected' : '' }}>Cédula de extranjería</option>
                            <option value="nit" {{ ($isEdit && $sp->id_type == 'nit') ? 'selected' : '' }}>NIT</option>
                            <option value="pasaporte" {{ ($isEdit && $sp->id_type == 'pasaporte') ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                    </div>
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label class="label">Identificación</label>
                            <input type="text" name="identification" class="input" 
                                   value="{{ $isEdit ? ($sp->identification ?? '') : old('identification') }}">
                        </div>
                        <div class="form-group">
                            <label class="label">DV</label>
                            <input type="text" name="dv" class="input" style="width:60px;"
                                   value="{{ $isEdit ? ($sp->dv ?? '') : old('dv') }}">
                        </div>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="label">Indicador</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <span style="font-size:20px;">🇨🇴</span>
                            <select name="country_code" class="input" style="width:120px;">
                                <option value="+57" {{ ($isEdit && $sp->country_code == '+57') ? 'selected' : '' }}>(+57) Colombia</option>
                                <option value="+1" {{ ($isEdit && $sp->country_code == '+1') ? 'selected' : '' }}>(+1) USA</option>
                                <option value="+34" {{ ($isEdit && $sp->country_code == '+34') ? 'selected' : '' }}>(+34) España</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="label">Celular</label>
                        <input type="tel" name="phone" class="input" placeholder="3001234567"
                               value="{{ $isEdit ? ($sp->phone ?? '') : old('phone') }}">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="label">Email</label>
                        <input type="email" name="email" class="input" placeholder="correo@ejemplo.com"
                               value="{{ $isEdit ? ($sp->email ?? '') : old('email') }}">
                    </div>
                    <div class="form-group">
                        <label class="label">Nombre para mostrar al cliente</label>
                        <input type="text" name="display_name" class="input" placeholder="Nombre público"
                               value="{{ $isEdit ? ($sp->display_name ?? '') : old('display_name') }}">
                        <small style="color:#9ca3af; font-size:11px;">Este nombre se mostrará en Reservas en Línea</small>
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="label">Especialidades</label>
                        <select name="title" class="input">
                            <option value="">Seleccionar especialidad...</option>
                            @foreach($specialties as $esp)
                                <option value="{{ $esp['nombre'] }}" {{ ($isEdit && $sp->title == $esp['nombre']) ? 'selected' : '' }}>
                                    {{ $esp['nombre'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="label">Código</label>
                        <input type="text" name="code" class="input" placeholder="Código interno"
                               value="{{ $isEdit ? ($sp->code ?? '') : old('code') }}">
                    </div>
                </div>

                <!-- Toggles -->
                <div style="margin-top: 30px;">
                    <div class="toggle-row">
                        <span class="toggle-label">Especialista Activo</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="active" value="1" {{ (!$isEdit || $sp->active !== false) ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <span class="toggle-label">Mostrar en Reservas</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_in_reservations" value="1" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <span class="toggle-label">Mostrar en Agenda</span>
                        <label class="toggle-switch">
                            <input type="checkbox" name="show_in_agenda" value="1" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- === TAB 2: SEDES === -->
            <div id="tab-sedes" class="tab-content">
                <h3 class="section-title">Seleccione Sede Principal:</h3>
                <div style="padding: 15px; border: 1px solid #e5e7eb; border-radius: 8px;">
                    <select name="location_id" class="input">
                        <option value="">Seleccionar sede...</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ ($isEdit && $sp->location_id == $location->id) ? 'selected' : '' }}>
                                {{ $location->name }} ({{ $location->address }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <p style="font-size: 12px; color: #6b7280; margin-top: 10px;">
                    ℹ️ El especialista será asignado a esta sede para el filtrado en los agentes de IA.
                </p>
            </div>

            <!-- === TAB 3: SERVICIOS === -->
            <div id="tab-servicios" class="tab-content">
                <h3 class="section-title">Seleccione Servicios:</h3>
                <div style="margin-bottom: 20px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" id="all-services" style="width:18px; height:18px; accent-color:#1a73e8;">
                        <span style="font-weight:600; color:#1a73e8;">Todas</span>
                    </label>
                </div>

                @if($packages->count() > 0)
                    <?php
                        // Agrupar por categoría
                        $categories = $packages->groupBy('category');
                    ?>
                    
                    @foreach($categories as $category => $categoryPackages)
                    <div class="service-category">
                        <div class="service-category-header" onclick="toggleCategory('cat-{{ str_slug($category ?: 'general') }}')">
                            <span class="cat-name">☰ {{ strtoupper($category ?: 'GENERAL') }}</span>
                            <button type="button" class="mark-all-btn">Marcar Todos {{ $categoryPackages->count() }} servicios</button>
                        </div>
                        <div class="service-items" id="cat-{{ str_slug($category ?: 'general') }}" style="display:grid;">
                            @foreach($categoryPackages as $pkg)
                            <label class="service-item">
                                <input type="checkbox" name="services[]" value="{{ $pkg->id }}"
                                    {{ ($isEdit && $sp->packages->contains($pkg->id)) ? 'checked' : '' }}>
                                {{ $pkg->package_name }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align:center; padding:40px; color:#9ca3af;">
                        <p>No hay servicios registrados.</p>
                        <a href="{{ url('admin/packages') }}" style="color:#1a73e8;">Ir a Servicios</a>
                    </div>
                @endif
            </div>

            <!-- === TAB 4: COMISIONES === -->
            <div id="tab-comisiones" class="tab-content">
                <div style="color:#1a73e8; font-weight:500; margin-bottom:20px;">
                    Las comisiones están activadas <span style="cursor:pointer; text-decoration:underline;">Desactivar</span>
                </div>

                <div id="add-comm-trigger" style="margin-bottom:30px;">
                    <span style="color:#1a73e8; cursor:pointer; font-weight:600;" onclick="showCommissionForm()">
                        + Agregar comisión diferente a este especialista
                    </span>
                </div>

                <div id="commission-form" style="display:none; border:1px solid #e5e7eb; padding:20px; border-radius:8px;">
                    <div style="color:#1a73e8; font-size:13px; margin-bottom:15px;">
                        ℹ Se actualizarán las comisiones a partir de ahora
                    </div>
                    <div class="form-group">
                        <label class="label">Buscar Categoría</label>
                        <select class="input">
                            <option>Categoría</option>
                            <option>MANICURA</option>
                            <option>ESTILISTAS</option>
                            <option>ESTÉTICA</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:10px; margin-top:20px;">
                        <button type="button" class="btn-cancel" onclick="hideCommissionForm()">Cancelar</button>
                        <button type="button" class="btn-create">Guardar</button>
                    </div>
                </div>
            </div>

            <!-- === TAB 5: HORARIO === -->
            <div id="tab-horario" class="tab-content">
                <div class="sub-tabs">
                    <div class="sub-tab active" id="subtab-staff" onclick="switchSubTab('staff')">Horarios de Staff</div>
                    <div class="sub-tab" id="subtab-block" onclick="switchSubTab('block')">Bloqueos de tiempo</div>
                    <div class="sub-tab" id="subtab-exception" onclick="switchSubTab('exception')">Excepciones (Días habilitados)</div>
                </div>

                <!-- SubTab: Horarios de Staff -->
                <div id="view-horario-staff">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <label class="toggle-switch" style="transform:scale(0.8);">
                                <input type="checkbox" checked onchange="toggleAllSchedule(this.checked)">
                                <span class="slider"></span>
                            </label>
                            <span style="font-weight:700; color: #475569; font-size: 14px;">Asignar horarios personalizados</span>
                        </label>
                        <button type="button" class="btn-copy-all" onclick="copyFirstDayToAll()">
                            <span>📋</span> Copiar lunes a todos
                        </button>
                    </div>

                    <div class="schedule-container">
                        <?php $days_list = [
                            'Lunes' => 'monday', 
                            'Martes' => 'tuesday', 
                            'Miércoles' => 'wednesday', 
                            'Jueves' => 'thursday', 
                            'Viernes' => 'friday', 
                            'Sábado' => 'saturday', 
                            'Domingo' => 'sunday'
                        ]; ?>
                        
                        @foreach($days_list as $day_name => $day_slug)
                        <?php 
                            $is_active = true;
                            $start_time = '09:00';
                            $end_time = '18:00';
                            
                            if($isEdit && isset($sp->working_hours[$day_slug])) {
                                $is_active = !empty($sp->working_hours[$day_slug]['is_working']);
                                $start_time = $sp->working_hours[$day_slug]['start'] ?? '09:00';
                                $end_time = $sp->working_hours[$day_slug]['end'] ?? '18:00';
                            }
                        ?>
                        <div class="schedule-row {{ !$is_active ? 'inactive' : '' }}" id="row-{{ $day_slug }}">
                            <div class="day-toggle">
                                <label class="toggle-switch">
                                    <input type="checkbox" name="working_hours[{{ $day_slug }}][is_working]" value="1" 
                                           {{ $is_active ? 'checked' : '' }} 
                                           onchange="toggleDayRow('{{ $day_slug }}', this.checked)">
                                    <span class="slider"></span>
                                </label>
                                <span class="day-name">{{ $day_name }}</span>
                            </div>
                            
                            <div class="time-inputs" style="{{ !$is_active ? 'opacity:0.4; pointer-events:none;' : '' }}">
                                <div class="time-wrapper">
                                    <input type="text" name="working_hours[{{ $day_slug }}][start]" 
                                           class="time-input timepicker-input" 
                                           value="{{ $start_time }}" 
                                           placeholder="Inicio"
                                           id="start-{{ $day_slug }}">
                                </div>
                                <span class="time-separator">a</span>
                                <div class="time-wrapper">
                                    <input type="text" name="working_hours[{{ $day_slug }}][end]" 
                                           class="time-input timepicker-input" 
                                           value="{{ $end_time }}" 
                                           placeholder="Fin"
                                           id="end-{{ $day_slug }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- SubTab: Bloqueos de Tiempo -->
                <div id="view-bloqueos-tiempo" style="display:none;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                        <h3 class="section-title" style="margin:0;">Bloqueos de tiempo</h3>
                        <button type="button" class="btn-create" style="background:#1a73e8;" onclick="openBlockModal()">Crear Nuevo</button>
                    </div>
                    
                    <div id="blocks-list">
                        @if($isEdit && !empty($sp->time_blocks))
                            @foreach($sp->time_blocks as $idx => $block)
                                <div class="block-card" id="block-card-{{ $idx }}">
                                    <div class="block-info">
                                        <span class="block-date">{{ $block['date'] }}</span>
                                        <span class="block-time">
                                            @if(!empty($block['all_day']))
                                                <strong>Todo el día</strong>
                                            @else
                                                {{ $block['start'] }} - {{ $block['end'] }}
                                            @endif
                                        </span>
                                        @if(!empty($block['reason']))
                                            <span class="block-reason">{{ $block['reason'] }}</span>
                                        @endif
                                    </div>
                                    <button type="button" class="btn-remove-block" onclick="removeBlock({{ $idx }})">×</button>
                                    <input type="hidden" name="time_blocks[{{ $idx }}][date]" value="{{ $block['date'] }}">
                                    <input type="hidden" name="time_blocks[{{ $idx }}][start]" value="{{ $block['start'] }}">
                                    <input type="hidden" name="time_blocks[{{ $idx }}][end]" value="{{ $block['end'] }}">
                                    <input type="hidden" name="time_blocks[{{ $idx }}][reason]" value="{{ $block['reason'] ?? '' }}">
                                    <input type="hidden" name="time_blocks[{{ $idx }}][all_day]" value="{{ !empty($block['all_day']) ? '1' : '0' }}">
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div id="no-blocks-msg" style="border:1px solid #e5e7eb; padding:40px; text-align:center; color:#9ca3af; border-radius:8px; {{ ($isEdit && !empty($sp->time_blocks)) ? 'display:none;' : '' }}">
                        No hay bloqueos de tiempo registrados.
                    </div>
                </div>

                <!-- SubTab: Excepciones de Horario -->
                <div id="view-exceptions" style="display:none;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                        <div>
                            <h3 class="section-title" style="margin:0;">Horario de Excepciones</h3>
                            <p style="font-size:12px; color:#6b7280; margin-top:4px;">
                                Añade días específicos en los que el especialista trabajará, ignorando su horario laboral base o descansos.
                            </p>
                        </div>
                        <button type="button" class="btn-create" style="background:#10b981;" onclick="openExceptionModal()">Habilitar Día Especial</button>
                    </div>
                    
                    <div id="exceptions-list">
                        @if($isEdit && !empty($sp->schedule_exceptions))
                            @foreach($sp->schedule_exceptions as $idx => $exc)
                                <div class="exception-card" id="exception-card-{{ $idx }}">
                                    <div class="exception-info">
                                        <span class="exception-date">{{ $exc['date'] }}</span>
                                        <span class="exception-time">{{ $exc['start'] }} - {{ $exc['end'] }}</span>
                                    </div>
                                    <button type="button" class="btn-remove-exception" onclick="removeException({{ $idx }})">×</button>
                                    <input type="hidden" name="schedule_exceptions[{{ $idx }}][date]" value="{{ $exc['date'] }}">
                                    <input type="hidden" name="schedule_exceptions[{{ $idx }}][start]" value="{{ $exc['start'] }}">
                                    <input type="hidden" name="schedule_exceptions[{{ $idx }}][end]" value="{{ $exc['end'] }}">
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div id="no-exceptions-msg" style="border:1px solid #e5e7eb; padding:40px; text-align:center; color:#9ca3af; border-radius:8px; {{ ($isEdit && !empty($sp->schedule_exceptions)) ? 'display:none;' : '' }}">
                        No hay excepciones registradas.
                    </div>
                </div>
            </div>

            <!-- === TAB 6: USUARIO MÓVIL === -->
            <div id="tab-usuario" class="tab-content">
                <div style="text-align:center; padding:40px;">
                    <h3 style="font-size:18px; font-weight:600; margin-bottom:10px; color:#1f2937;">Usuario Móvil</h3>
                    <p style="color:#6b7280; margin-bottom:30px;">Habilitar acceso desde la aplicación móvil</p>
                    
                    <!-- Toggle Switch con estilos inline -->
                    <div style="display:inline-block; position:relative; width:60px; height:34px;">
                        <input type="checkbox" name="mobile_user" id="mobileUserToggle" value="1" 
                               {{ ($isEdit && $sp->mobile_user) ? 'checked' : '' }} 
                               onchange="toggleMobileUserPanel()"
                               style="opacity:0; width:0; height:0; position:absolute;">
                        <span id="toggleSlider" onclick="document.getElementById('mobileUserToggle').click()" 
                              style="position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; 
                                     background-color:{{ ($isEdit && $sp->mobile_user) ? '#1a73e8' : '#ccc' }}; 
                                     transition:.4s; border-radius:34px;">
                            <span style="position:absolute; content:''; height:26px; width:26px; left:4px; bottom:4px; 
                                         background-color:white; transition:.4s; border-radius:50%;
                                         transform:{{ ($isEdit && $sp->mobile_user) ? 'translateX(26px)' : 'translateX(0)' }};"></span>
                        </span>
                    </div>

                    <div id="mobileUserPanel" style="margin-top:40px; border-top:1px solid #f3f4f6; padding-top:30px; {{ ($isEdit && $sp->mobile_user) ? '' : 'display:none;' }}">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; max-width:500px; margin:0 auto; text-align:left;">
                            <div style="margin-bottom:20px;">
                                <label style="display:block; font-size:13px; font-weight:700; color:#1f2937; margin-bottom:6px;">Teléfono Colaborador</label>
                                <input type="text" id="mobileUserPhoneDisplay" readonly 
                                       style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; background:#f9fafb; color:#374151;" 
                                       value="{{ $isEdit ? $sp->phone : '' }}">
                            </div>
                            <div style="margin-bottom:20px;">
                                <label style="display:block; font-size:13px; font-weight:700; color:#1f2937; margin-bottom:6px;">Correo Electrónico</label>
                                <input type="text" id="mobileUserEmailDisplay" readonly 
                                       style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:14px; background:#f9fafb; color:#374151;" 
                                       value="{{ $isEdit ? $sp->email : '' }}">
                            </div>
                        </div>
                         
                        <div style="margin-top:20px;">
                            @if($isEdit)
                            <button type="button" id="btnSendInvite" onclick="sendMobileInvite()"
                                    style="background-color:#1a73e8; color:white; padding:12px 24px; border-radius:6px; font-weight:600; border:none; cursor:pointer; font-size:14px;">
                                📨 Enviar contraseña por correo
                            </button>
                            <div id="inviteStatus" style="margin-top:15px; font-weight:600; font-size:14px;"></div>
                            @else
                            <div style="background:#fff8e1; border:1px solid #ffca28; color:#856404; padding:15px; border-radius:8px; font-size:13px; display:inline-block;">
                                ℹ️ Primero debes <b>Crear el Especialista</b> para poder enviarle su clave de acceso.
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

@if($isEdit)
<form id="deleteForm" method="POST" action="{{ url('admin/specialists/'.$sp->id) }}" style="display:none;">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="DELETE">
</form>
@endif

<script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(div => div.classList.remove('active'));
        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));
        document.getElementById('tab-' + tabName).classList.add('active');
        document.getElementById('menu-' + tabName).classList.add('active');
    }

    function switchSubTab(type) {
        document.getElementById('subtab-staff').classList.remove('active');
        document.getElementById('subtab-block').classList.remove('active');
        document.getElementById('view-horario-staff').style.display = 'none';
        document.getElementById('view-bloqueos-tiempo').style.display = 'none';
        
        if(type === 'staff') {
            document.getElementById('subtab-staff').classList.add('active');
            document.getElementById('view-horario-staff').style.display = 'block';
        } else {
            document.getElementById('subtab-block').classList.add('active');
            document.getElementById('view-bloqueos-tiempo').style.display = 'block';
        }
    }

    function showCommissionForm() {
        document.getElementById('commission-form').style.display = 'block';
        document.getElementById('add-comm-trigger').style.display = 'none';
    }

    function hideCommissionForm() {
        document.getElementById('commission-form').style.display = 'none';
        document.getElementById('add-comm-trigger').style.display = 'block';
    }

    function updateNameDisplay(value) {
        document.getElementById('nameDisplay').textContent = value || 'Nuevo Especialista';
        const placeholder = document.getElementById('photoPlaceholder');
        if(placeholder) {
            placeholder.textContent = value ? value.charAt(0).toUpperCase() : '?';
        }
    }

    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                preview.innerHTML = '<img src="' + e.target.result + '" id="previewImg">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleCategory(catId) {
        const el = document.getElementById(catId);
        el.style.display = el.style.display === 'none' ? 'grid' : 'none';
    }

    // Funcionalidad "Marcar Todos" para servicios de una categoría
    document.querySelectorAll('.mark-all-btn').forEach(btn => {
        btn.onclick = function(e) {
            e.stopPropagation();
            const categoryDiv = this.closest('.service-category').querySelector('.service-items');
            const checkboxes = categoryDiv.querySelectorAll('input[type="checkbox"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Marcar Todos ' + checkboxes.length + ' servicios' : 'Desmarcar Todos';
            
            // Si los items estaban ocultos, los mostramos
            categoryDiv.style.display = 'grid';
        };
    });

    // Toggle para "Todas las sedes"
    const allSedes = document.getElementById('all-sedes');
    if(allSedes) {
        allSedes.onchange = function() {
            document.querySelectorAll('input[name="sedes[]"]').forEach(cb => cb.checked = this.checked);
        };
    }

    // Toggle para "Todos los servicios"
    const allServices = document.getElementById('all-services');
    if(allServices) {
        allServices.onchange = function() {
            document.querySelectorAll('input[name="services[]"]').forEach(cb => cb.checked = this.checked);
            // Actualizar textos de botones de categoría
            document.querySelectorAll('.mark-all-btn').forEach(btn => {
                const count = btn.closest('.service-category').querySelectorAll('input[type="checkbox"]').length;
                btn.textContent = this.checked ? 'Desmarcar Todos' : 'Marcar Todos ' + count + ' servicios';
            });
        };
    }

    function toggleMobileUserPanel() {
        const panel = document.getElementById('mobileUserPanel');
        const toggle = document.getElementById('mobileUserToggle');
        const slider = document.getElementById('toggleSlider');
        const knob = slider.querySelector('span');
        
        panel.style.display = toggle.checked ? 'block' : 'none';
        slider.style.backgroundColor = toggle.checked ? '#1a73e8' : '#ccc';
        knob.style.transform = toggle.checked ? 'translateX(26px)' : 'translateX(0)';
        
        // Sync values just in case
        if(toggle.checked) {
            syncMobileDisplay();
        }
    }

    function syncMobileDisplay() {
        const phone = document.querySelector('input[name="phone"]').value;
        const email = document.querySelector('input[name="email"]').value;
        document.getElementById('mobileUserPhoneDisplay').value = phone || 'Sin teléfono';
        document.getElementById('mobileUserEmailDisplay').value = email || 'Sin correo';
    }

    // Sync when typing in members tab
    document.querySelector('input[name="phone"]').addEventListener('input', syncMobileDisplay);
    document.querySelector('input[name="email"]').addEventListener('input', syncMobileDisplay);

    function sendMobileInvite() {
        const btn = document.getElementById('btnSendInvite');
        const status = document.getElementById('inviteStatus');
        
        // Obtener ID desde la URL (más confiable)
        const urlParts = window.location.pathname.split('/');
        const specId = urlParts[urlParts.length - 2]; // /admin/specialists/5/edit -> 5
        
        if (!specId || specId === 'create') {
            status.innerHTML = '❌ Guarda el especialista primero antes de enviar la invitación.';
            status.style.color = '#ef4444';
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Enviando...';
        status.innerHTML = '⌛ Procesando envío de invitación...';
        status.style.color = '#1a73e8';

        fetch("{{ url('admin/specialists/send-invite') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                specialist_id: specId
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('Error del servidor: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            if(data.success) {
                status.innerHTML = '✅ ' + data.message;
                status.style.color = '#2e7d32';
                btn.textContent = '✓ Enviado con éxito';
                btn.style.background = '#2e7d32';
                setTimeout(() => {
                    btn.disabled = false;
                    btn.textContent = '📨 Enviar contraseña nuevamente';
                    btn.style.background = '#1a73e8';
                }, 4000);
            } else {
                status.innerHTML = '❌ ' + (data.error || 'No se pudo enviar');
                status.style.color = '#ef4444';
                btn.disabled = false;
                btn.textContent = '📨 Reintentar envío';
            }
        })
        .catch(err => {
            console.error('Error en sendMobileInvite:', err);
            status.innerHTML = '❌ ' + err.message;
            status.style.color = '#ef4444';
            btn.disabled = false;
            btn.textContent = '📨 Reintentar envío';
        });
    }

    function confirmDelete() {
        if(confirm('¿Estás seguro de eliminar este especialista? Esta acción no se puede deshacer.')) {
            document.getElementById('deleteForm').submit();
        }
    }

    /* Blocks Logic */
    let blockIndex = {{ ($isEdit && !empty($sp->time_blocks)) ? count($sp->time_blocks) : 0 }};

    function openBlockModal() {
        document.getElementById('timeBlockModal').style.display = 'flex';
        // Initialize flatpickr on modal inputs
        flatpickr("#blockStart, #blockEnd", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: "h:i K",
            time_24hr: false,
            minuteIncrement: 1,
            altInputClass: "time-input",
            onOpen: function(selectedDates, dateStr, instance) {
                const timeContainer = instance.calendarContainer.querySelector('.flatpickr-time');
                if (timeContainer) {
                    timeContainer.onwheel = (e) => {
                        e.preventDefault();
                        const delta = e.deltaY > 0 ? -1 : 1;
                        if (e.target.classList.contains('flatpickr-hour')) instance.changeHour(instance.hourElement.valueAsNumber + delta);
                        else if (e.target.classList.contains('flatpickr-minute')) instance.changeMinute(instance.minuteElement.valueAsNumber + delta);
                    };
                }
            }
        });
    }

    function closeBlockModal() {
        document.getElementById('timeBlockModal').style.display = 'none';
        document.getElementById('blockForm').reset();
    }

    function addBlock() {
        const date = document.getElementById('blockDate').value;
        let start = document.getElementById('blockStart').value;
        let end = document.getElementById('blockEnd').value;
        const reason = document.getElementById('blockReason').value;
        const isAllDay = document.getElementById('blockAllDay').checked;

        if(!date || (!isAllDay && (!start || !end))) {
            alert('Por favor completa fecha y horarios.');
            return;
        }

        if(isAllDay) {
            start = '00:00';
            end = '23:59';
        }

        const container = document.getElementById('blocks-list');
        const noMsg = document.getElementById('no-blocks-msg');
        
        const card = document.createElement('div');
        card.className = 'block-card';
        card.id = `block-card-${blockIndex}`;
        card.innerHTML = `
            <div class="block-info">
                <span class="block-date">${date}</span>
                <span class="block-time">${isAllDay ? '<strong>Todo el día</strong>' : (start + ' - ' + end)}</span>
                ${reason ? `<span class="block-reason">${reason}</span>` : ''}
            </div>
            <button type="button" class="btn-remove-block" onclick="removeBlock(${blockIndex})">×</button>
            <input type="hidden" name="time_blocks[${blockIndex}][date]" value="${date}">
            <input type="hidden" name="time_blocks[${blockIndex}][start]" value="${start}">
            <input type="hidden" name="time_blocks[${blockIndex}][end]" value="${end}">
            <input type="hidden" name="time_blocks[${blockIndex}][reason]" value="${reason}">
            <input type="hidden" name="time_blocks[${blockIndex}][all_day]" value="${isAllDay ? '1' : '0'}">
        `;

        container.appendChild(card);
        noMsg.style.display = 'none';
        blockIndex++;
        closeBlockModal();
    }

    function toggleAllDay(checked) {
        const start = document.getElementById('blockStart');
        const end = document.getElementById('blockEnd');
        if(checked) {
            start.disabled = true;
            end.disabled = true;
            start.style.background = '#f3f4f6';
            end.style.background = '#f3f4f6';
        } else {
            start.disabled = false;
            end.disabled = false;
            start.style.background = 'white';
            end.style.background = 'white';
        }
    }

    function removeBlock(idx) {
        const card = document.getElementById(`block-card-${idx}`);
        if(card) {
            card.remove();
        }
        
        const container = document.getElementById('blocks-list');
        const noMsg = document.getElementById('no-blocks-msg');
        if(container.children.length === 0) {
            noMsg.style.display = 'block';
        }
    }

    /* Exceptions Logic */
    let exceptionIndex = {{ ($isEdit && !empty($sp->schedule_exceptions)) ? count($sp->schedule_exceptions) : 0 }};

    function openExceptionModal() {
        document.getElementById('scheduleExceptionModal').style.display = 'flex';
        flatpickr("#excStart, #excEnd", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: "h:i K",
            time_24hr: false,
            minuteIncrement: 1,
            altInputClass: "time-input",
            onOpen: function(selectedDates, dateStr, instance) {
                const timeContainer = instance.calendarContainer.querySelector('.flatpickr-time');
                if (timeContainer) {
                    timeContainer.onwheel = (e) => {
                        e.preventDefault();
                        const delta = e.deltaY > 0 ? -1 : 1;
                        if (e.target.classList.contains('flatpickr-hour')) instance.changeHour(instance.hourElement.valueAsNumber + delta);
                        else instance.changeMinute(instance.minuteElement.valueAsNumber + delta);
                    };
                }
            }
        });
    }

    function closeExceptionModal() {
        document.getElementById('scheduleExceptionModal').style.display = 'none';
        document.getElementById('exceptionForm').reset();
    }

    function addException() {
        const date = document.getElementById('excDate').value;
        const start = document.getElementById('excStart').value;
        const end = document.getElementById('excEnd').value;

        if(!date || !start || !end) {
            alert('Por favor completa fecha y horarios.');
            return;
        }

        const container = document.getElementById('exceptions-list');
        const noMsg = document.getElementById('no-exceptions-msg');
        
        const card = document.createElement('div');
        card.className = 'exception-card';
        card.id = `exception-card-${exceptionIndex}`;
        card.innerHTML = `
            <div class="exception-info">
                <span class="exception-date">${date}</span>
                <span class="exception-time">${start} - ${end}</span>
            </div>
            <button type="button" class="btn-remove-exception" onclick="removeException(${exceptionIndex})">×</button>
            <input type="hidden" name="schedule_exceptions[${exceptionIndex}][date]" value="${date}">
            <input type="hidden" name="schedule_exceptions[${exceptionIndex}][start]" value="${start}">
            <input type="hidden" name="schedule_exceptions[${exceptionIndex}][end]" value="${end}">
        `;

        container.appendChild(card);
        noMsg.style.display = 'none';
        exceptionIndex++;
        closeExceptionModal();
    }

    function removeException(idx) {
        const card = document.getElementById(`exception-card-${idx}`);
        if(card) {
            card.remove();
        }
        
        const container = document.getElementById('exceptions-list');
        const noMsg = document.getElementById('no-exceptions-msg');
        if(container.children.length === 0) {
            noMsg.style.display = 'block';
        }
    }

    /* Schedule Logic */
    function initTimePickers() {
        flatpickr(".timepicker-input", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            altInput: true,
            altFormat: "h:i K",
            time_24hr: false,
            minuteIncrement: 1,
            allowInput: true,
            altInputClass: "time-input",
            onOpen: function(selectedDates, dateStr, instance) {
                setTimeout(() => {
                    const timeContainer = instance.calendarContainer.querySelector('.flatpickr-time');
                    if (timeContainer) {
                        timeContainer.onwheel = (e) => {
                            e.preventDefault();
                            const delta = e.deltaY > 0 ? -1 : 1;
                            if (e.target.classList.contains('flatpickr-hour')) instance.changeHour(instance.hourElement.valueAsNumber + delta);
                            else if (e.target.classList.contains('flatpickr-minute')) instance.changeMinute(instance.minuteElement.valueAsNumber + delta);
                        };
                    }
                }, 10);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initTimePickers();
    });

    function toggleDayRow(day, checked) {
        const row = document.getElementById('row-' + day);
        const inputs = row.querySelector('.time-inputs');
        if(checked) {
            row.classList.remove('inactive');
            inputs.style.opacity = '1';
            inputs.style.pointerEvents = 'auto';
        } else {
            row.classList.add('inactive');
            inputs.style.opacity = '0.4';
            inputs.style.pointerEvents = 'none';
        }
    }

    function toggleAllSchedule(checked) {
        const rows = document.querySelectorAll('.schedule-row');
        rows.forEach(row => {
            const toggle = row.querySelector('input[type="checkbox"]');
            toggle.checked = checked;
            const day = row.id.replace('row-', '');
            toggleDayRow(day, checked);
        });
    }

    function copyFirstDayToAll() {
        const startVal = document.getElementById('start-monday').value;
        const endVal = document.getElementById('end-monday').value;
        
        const slugs = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        slugs.forEach(slug => {
            const startInput = document.getElementById('start-' + slug);
            const endInput = document.getElementById('end-' + slug);
            
            if(startInput && endInput) {
                startInput._flatpickr.setDate(startVal);
                endInput._flatpickr.setDate(endVal);
            }
        });
        
        alert('✅ Horarios del lunes copiados a toda la semana.');
    }

    // Sobreescribir switchSubTab
    window.switchSubTab = function(type) {
        document.getElementById('subtab-staff').classList.remove('active');
        document.getElementById('subtab-block').classList.remove('active');
        document.getElementById('subtab-exception').classList.remove('active');
        document.getElementById('view-horario-staff').style.display = 'none';
        document.getElementById('view-bloqueos-tiempo').style.display = 'none';
        document.getElementById('view-exceptions').style.display = 'none';
        
        if(type === 'staff') {
            document.getElementById('subtab-staff').classList.add('active');
            document.getElementById('view-horario-staff').style.display = 'block';
        } else if(type === 'block') {
            document.getElementById('subtab-block').classList.add('active');
            document.getElementById('view-bloqueos-tiempo').style.display = 'block';
        } else if(type === 'exception') {
            document.getElementById('subtab-exception').classList.add('active');
            document.getElementById('view-exceptions').style.display = 'block';
        }
    };
</script>

<!-- Modal Excepción Horario -->
<div id="scheduleExceptionModal" class="custom-modal-overlay">
    <div class="modal-content-small">
        <h3 style="margin-top:0; margin-bottom:20px; font-size:18px;">Habilitar Día Especial</h3>
        <p style="font-size:12px; color:#6b7280; margin-bottom:20px;">Este día se considerará laboral aunque caiga en un día marcado como descanso.</p>
        <form id="exceptionForm">
            <div class="form-group">
                <label class="label">Fecha</label>
                <input type="date" id="excDate" class="input" value="{{ date('Y-m-d') }}">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label class="label">Hora Inicio</label>
                    <div class="time-wrapper">
                        <input type="text" id="excStart" class="time-input" style="width:100%;" value="09:00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="label">Hora Fin</label>
                    <div class="time-wrapper">
                        <input type="text" id="excEnd" class="time-input" style="width:100%;" value="18:00">
                    </div>
                </div>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" class="btn-cancel" onclick="closeExceptionModal()">Cancelar</button>
                <button type="button" class="btn-create" onclick="addException()" style="background:#10b981;">Habilitar Día</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bloqueo Tiempo -->
<div id="timeBlockModal" class="custom-modal-overlay">
    <div class="modal-content-small">
        <h3 style="margin-top:0; margin-bottom:20px; font-size:18px;">Nuevo Bloqueo de Tiempo</h3>
        <form id="blockForm">
            <div class="form-group">
                <label class="label">Fecha</label>
                <input type="date" id="blockDate" class="input" value="{{ date('Y-m-d') }}">
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label class="label">Hora Inicio</label>
                    <div class="time-wrapper">
                        <input type="text" id="blockStart" class="time-input" style="width:100%;" value="09:00">
                    </div>
                </div>
                <div class="form-group">
                    <label class="label">Hora Fin</label>
                    <div class="time-wrapper">
                        <input type="text" id="blockEnd" class="time-input" style="width:100%;" value="10:00">
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-top: -10px; margin-bottom: 20px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" id="blockAllDay" onchange="toggleAllDay(this.checked)" style="width:18px; height:18px; accent-color:#1a73e8;">
                    <span style="font-size:14px; font-weight:600; color:#1f2937;">Todo el día</span>
                </label>
            </div>
            <div class="form-group">
                <label class="label">Motivo (Opcional)</label>
                <input type="text" id="blockReason" class="input" placeholder="Ej: Almuerzo, Cita médica...">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" class="btn-cancel" onclick="closeBlockModal()">Cancelar</button>
                <button type="button" class="btn-create" onclick="addBlock()" style="background:#1a73e8;">Añadir Bloqueo</button>
            </div>
        </form>
    </div>
</div>

@endsection
