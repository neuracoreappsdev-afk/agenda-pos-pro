@extends('admin.configuration._layout')

@section('config_title', 'Gestión de Usuarios')

@section('config_content')

<style>
    /* Premium UI Styles */
    :root {
        --primary-color: #4f46e5;
        --primary-hover: #4338ca;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --text-main: #111827;
        --text-secondary: #6b7280;
        --bg-card: #ffffff;
        --bg-hover: #f9fafb;
    }

    .config-card {
        background: transparent;
        padding: 0;
        box-shadow: none;
        border: none;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .search-group {
        display: flex;
        gap: 15px;
        flex: 1;
    }

    .search-input {
        flex: 1;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        min-width: 250px;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .filter-select {
        padding: 10px 35px 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        background-color: white;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        appearance: none;
        cursor: pointer;
        min-width: 200px;
    }

    .btn-create {
        background: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-create:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        color: white;
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
    }

    .users-table th {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: var(--text-main);
        vertical-align: middle;
    }

    .users-table tr:last-child td {
        border-bottom: none;
    }

    .users-table tr:hover {
        background-color: #f8fafc;
    }

    .avatar-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .avatar {
        width: 40px;
        height: 40px;
        background: #e0e7ff;
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        color: var(--text-main);
    }

    .user-email {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-active {
        background: #ecfdf5;
        color: #059669;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    .role-badge {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        margin-right: 4px;
        margin-bottom: 4px;
        display: inline-block;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 35px;
        border-radius: 20px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 20px;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-main);
    }

    .btn-action {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .btn-edit {
        background: white;
        border-color: #e2e8f0;
        color: var(--primary-color);
    }
    
    .btn-edit:hover {
        border-color: var(--primary-color);
        background: #f5f3ff;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 13px;
        color: #374151;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
</style>

<div class="config-card">
    <div style="margin-bottom: 35px;">
        <h2 style="font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 10px;">Usuarios</h2>
        <p style="color: #6b7280; font-size: 15px; margin: 0;">Gestión de usuarios y accesos al sistema.</p>
    </div>

    @if(session('success'))
        <div id="flash-message" class="alert-floating success" style="position:fixed; top:20px; right:20px; background:white; border-left:5px solid #10b981; padding:16px 20px; border-radius:12px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); display:flex; gap:12px; z-index:9999; font-weight:500; color:#065f46;">
            <span style="font-size:20px;">✓</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="toolbar">
        <div class="search-group">
            <input type="text" class="search-input" placeholder="Buscar por nombre o email..." id="searchInput">
            
            <select class="filter-select" id="sedeFilter">
                <option value="">Todas las Sedes</option>
                @foreach($sedes as $sede)
                    <option value="{{ $sede['id'] ?? '' }}">{{ $sede['name'] ?? 'Sede' }}</option>
                @endforeach
            </select>
            
            <select class="filter-select" id="statusFilter">
                <option value="all">Todos los Estados</option>
                <option value="active">Activos</option>
                <option value="inactive">Inactivos</option>
            </select>
        </div>
        
        <button class="btn-create" onclick="openUserModal()">
            <span>＋</span> Crear Nuevo
        </button>
    </div>

    <div class="table-container">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Nombre de Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Sede</th>
                    <th>Activo</th>
                    <th>Roles</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="avatar-cell">
                            <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <div class="user-info">
                                <span class="user-name">{{ $user->name }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="color: #6b7280;">{{ $user->username ?? strtolower(str_replace(' ', '', $user->name)) }}</td>
                    <td style="color: #6b7280;">{{ $user->email }}</td>
                    <td style="color: #6b7280;">
                        {{ $user->sede_id ? 'Sede Asignada' : 'Todas' }}
                    </td>
                    <td>
                        @if(!empty($user->active) || !isset($user->active))
                            <span class="status-badge status-active">● Activo</span>
                        @else
                            <span class="status-badge status-inactive">● Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <span class="role-badge">Staff</span>
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <button class="btn-action btn-edit" 
                                data-user="{{ htmlspecialchars(json_encode($user), ENT_QUOTES, 'UTF-8') }}"
                                onclick="editUser(this)">Editar</button>
                        <button class="btn-action" 
                                onclick="deleteUser('{{ $user->id }}')" 
                                style="margin-left:5px; color:#ef4444; border-color:#e2e8f0; background:white;">✕</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #9ca3af;">
                        No hay usuarios registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create/Edit User -->
<div id="userModal" class="modal">
    <div class="modal-content" style="max-width: 900px; padding: 0; border-radius: 12px; overflow: hidden;">
        <!-- Header -->
        <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fff;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main);">Usuarios</h3>
            <button class="modal-close" onclick="closeModal('userModal'); resetUserForm();" style="background:none; border:none; font-size: 24px; cursor: pointer; color: #94a3b8;">×</button>
        </div>

        <!-- Tabs Navigation -->
        <div style="padding: 0 25px; border-bottom: 1px solid #e2e8f0; background: #fff;">
            <div class="tabs-nav" style="display: flex; gap: 20px;">
                <button type="button" class="tab-btn active" onclick="switchTab('general')">General</button>
                <button type="button" class="tab-btn" onclick="switchTab('horarios')">Horarios</button>
                <button type="button" class="tab-btn" onclick="switchTab('sedes')">Sedes</button>
                <button type="button" class="tab-btn" onclick="switchTab('roles')">Roles</button>
                <button type="button" class="tab-btn" onclick="switchTab('notificaciones')">Notificaciones</button>
            </div>
        </div>
        
        <form action="{{ url('admin/configuration/usuarios') }}" method="POST" style="padding: 25px; background: #f8fafc;">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="user_id">

            <!-- Tab: General -->
            <div id="tab-general" class="tab-content active">
                <style>
                    .row-gap { display: flex; gap: 20px; margin-bottom: 15px; }
                    .col-1-2 { flex: 1; }
                    .tab-btn {
                        padding: 15px 5px;
                        background: none;
                        border: none;
                        border-bottom: 2px solid transparent;
                        font-weight: 600;
                        color: #64748b;
                        cursor: pointer;
                        font-size: 14px;
                        transition: all 0.2s;
                    }
                    .tab-btn.active {
                        color: var(--primary-color);
                        border-bottom-color: var(--primary-color);
                    }
                    .tab-content { display: none; }
                    .tab-content.active { display: block; animation: fadeIn 0.3s; }
                    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                    
                    .form-label { font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px; display: block; }
                    .form-input { 
                        width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;
                        transition: border-color 0.2s;
                    }
                    .form-input:focus { border-color: var(--primary-color); outline: none; }
                </style>

                <!-- Row 1: Nombre | Celular -->
                <div class="row-gap">
                    <div class="col-1-2">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="name" class="form-input" placeholder="Ingrese su Nombre" required>
                    </div>
                     <div class="col-1-2">
                        <div class="row-gap" style="margin-bottom: 0;">
                            <div style="flex: 0 0 140px;">
                                <label class="form-label">Indicador</label>
                                <select name="country_code" class="form-input" style="padding-left: 30px; background-image: url('https://flagcdn.com/w20/co.png'); background-repeat: no-repeat; background-position: 8px center;">
                                    <option value="+57">(+57) Colombia</option>
                                    <option value="+1">(+1) USA</option>
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label class="form-label">Celular</label>
                                <input type="text" name="phone" class="form-input" placeholder="Celular">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Email | Username -->
                <div class="row-gap">
                     <div class="col-1-2">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-input" placeholder="Ingrese su Correo Electrónico" required>
                    </div>
                    <div class="col-1-2">
                        <label class="form-label">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-input" placeholder="Ingrese su Nombre de Usuario">
                    </div>
                </div>

                <!-- Row 3: Internal Code | ID Block -->
                <div class="row-gap">
                     <div class="col-1-2">
                        <label class="form-label">Codigo Interno</label>
                        <input type="text" name="internal_code" class="form-input" placeholder="Ingrese su Codigo Interno">
                    </div>
                    <div class="col-1-2">
                         <div class="row-gap" style="margin-bottom: 0;">
                             <div style="flex: 1;">
                                <label class="form-label">Tipo Id.</label>
                                <select name="id_type" class="form-input">
                                    <option value="cc">Cédula de Ciudadanía</option>
                                    <option value="nit">NIT</option>
                                    <option value="ce">Cédula de Extranjería</option>
                                </select>
                            </div>
                            <div style="flex: 1;">
                                <label class="form-label">Identificación</label>
                                <input type="text" name="identification" class="form-input" placeholder="Identificación">
                            </div>
                             <div style="flex: 0 0 60px;">
                                <label class="form-label">DV</label>
                                <input type="text" name="dv" class="form-input" placeholder="DV">
                            </div>
                         </div>
                    </div>
                </div>

                <!-- Row 4: Active | Password -->
                <div class="row-gap" style="align-items: center;">
                    <div class="col-1-2">
                        <div style="display: flex; align-items: center; justify-content: space-between; border: 1px solid #e2e8f0; padding: 10px 15px; border-radius: 8px;">
                            <label class="form-label" style="margin:0;">Usuario Activo</label>
                            <label class="switch">
                              <input type="checkbox" name="active" checked>
                              <span class="slider round"></span>
                            </label>
                        </div>
                         <style>
                            .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
                            .switch input { opacity: 0; width: 0; height: 0; }
                            .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
                            .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
                            input:checked + .slider { background-color: var(--primary-color); }
                            input:checked + .slider:before { transform: translateX(20px); }
                        </style>
                    </div>
                    <div class="col-1-2">
                         <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label class="form-label" style="margin-bottom:0;">Contraseña <span style="font-weight:400; font-size:11px; color:#94a3b8;">(Opcional)</span></label>
                            <button type="button" onclick="generatePassword()" style="background: none; border: none; color: var(--primary-color); font-size: 11px; font-weight: 700; cursor: pointer;">⚡ Generar</button>
                         </div>
                         <input type="password" name="password" id="passwordInput" class="form-input" placeholder="Establecer contraseña">
                    </div>
                </div>
            </div>

            <!-- Tab: Horarios -->
            <div id="tab-horarios" class="tab-content">
                <style>
                    /* CUSTOM TIME WHEEL STYLES FOR THIS MODAL */
                    /* Reusing the logic from Horarios view */
                    .time-trigger {
                        background: #f9fafb;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 10px 14px;
                        font-weight: 600;
                        font-size: 14px;
                        color: #111827;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        justify-content: space-between;
                        transition: all 0.2s;
                        width: 100%;
                    }
                    .time-trigger:hover {
                        border-color: var(--primary-color);
                        background: white;
                    }
                    .time-icon {
                        color: #94a3b8;
                    }
                </style>
                <div style="background: white; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center; margin-bottom: 20px;">
                     <div style="margin-bottom: 15px;">
                        <span style="font-weight: 600; color: #334155; margin-right: 15px;">Activar Horario para ingreso a Lizto</span>
                         <label class="switch" style="vertical-align: middle;">
                              <input type="checkbox" name="schedule_active">
                              <span class="slider round"></span>
                        </label>
                     </div>
                </div>

                <div class="row-gap">
                    <div class="col-1-2">
                        <label class="form-label">Hora Entrada</label>
                        <div class="time-trigger" onclick="openTimePicker('schedule_start', 'Hora Entrada')">
                            <span id="display_schedule_start">--:--</span>
                            <i class="far fa-clock time-icon"></i>
                        </div>
                        <input type="hidden" name="schedule_start" id="input_schedule_start">
                    </div>
                     <div class="col-1-2">
                        <label class="form-label">Hora Salida</label>
                        <div class="time-trigger" onclick="openTimePicker('schedule_end', 'Hora Salida')">
                            <span id="display_schedule_end">--:--</span>
                            <i class="far fa-clock time-icon"></i>
                        </div>
                        <input type="hidden" name="schedule_end" id="input_schedule_end">
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="schedule_apply_all" style="width: 18px; height: 18px;">
                        <span style="color: #475569; font-weight: 500;">Asignar este horario a otros usuarios</span>
                    </label>
                </div>
            </div>

            <!-- Tab: Sedes -->
            <div id="tab-sedes" class="tab-content">
                 <div style="margin-bottom: 20px;">
                    <label class="form-label">Seleccione Sedes:</label>
                     <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 15px;">
                        <input type="checkbox" id="checkAllSedes" onchange="toggleCheckboxes('sedes-list', this.checked)">
                        <span style="font-weight: 600;">Todas</span>
                    </label>
                    
                    <div id="sedes-list" style="display: flex; flex-direction: column; gap: 10px; max-height: 300px; overflow-y: auto;">
                        @foreach($sedes as $sede)
                        <label style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #f1f5f9; border-radius: 8px; background: white; cursor: pointer;">
                            <input type="checkbox" name="sedes[]" value="{{ $sede['id'] }}" style="width: 18px; height: 18px;">
                            <span style="color: #334155;">{{ $sede['name'] }}</span>
                        </label>
                        @endforeach
                    </div>
                 </div>
            </div>

            <!-- Tab: Roles -->
            <div id="tab-roles" class="tab-content">
                <div style="margin-bottom: 20px;">
                    <label class="form-label">Seleccione Roles:</label>
                     <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin-bottom: 15px;">
                        <input type="checkbox" id="checkAllRoles" onchange="toggleCheckboxes('roles-list', this.checked)">
                        <span style="font-weight: 600;">Todas</span>
                    </label>
                    
                    <div id="roles-list" style="display: flex; flex-direction: column; gap: 10px;">
                         @foreach($roles as $role)
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px; border: 1px solid #f1f5f9; border-radius: 8px; background: white; cursor: pointer;">
                                <input type="checkbox" name="roles[]" value="{{ $role['id'] }}" style="width: 18px; height: 18px;">
                                <span style="color: #334155;">{{ $role['nombre'] }}</span>
                            </label>
                         @endforeach
                    </div>
                 </div>
            </div>

            <!-- Tab: Notificaciones -->
            <div id="tab-notificaciones" class="tab-content">
                <div style="text-align: center; padding: 30px; color: #94a3b8;">
                    <p>Configuración de notificaciones disponible próximamente.</p>
                    <!-- Placeholder as no screenshot provided for this exact tab, sticking to structure -->
                     <label style="display: flex; align-items: center; gap: 10px; justify-content: center; margin-top: 10px;">
                        <input type="checkbox" name="notify_email" checked disabled> Notificar por Email
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('userModal'); resetUserForm();" style="padding: 10px 25px; border: 1px solid #cbd5e1; background: white; border-radius: 8px; cursor: pointer; color: #475569; font-weight: 600;">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="background: var(--success-color); color: white; border: none; padding: 10px 40px; border-radius: 8px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);">Crear</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Search and Filter Logic
    function filterUsers() {
        const searchText = document.getElementById('searchInput').value.toLowerCase();
        const sedeFilter = document.getElementById('sedeFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value; // active, inactive, all
        
        const rows = document.querySelectorAll('.users-table tbody tr');
        
        rows.forEach(row => {
            const name = row.querySelector('.user-name').innerText.toLowerCase();
            const email = row.querySelector('td:nth-child(3)').innerText.toLowerCase();
            const sede = row.querySelector('td:nth-child(4)').innerText.toLowerCase(); // Sede column
            // Status is implicitly checked by badge class or text
            const isInactive = row.querySelector('.status-inactive');
            const isActive = !isInactive;
            
            // Search Match
            const matchesSearch = name.includes(searchText) || email.includes(searchText);
            
            // Sede Match (if filter empty, always true. If filter has ID, we need to match NAME ideally, but simpler is to match text if we put text in option, or use data attribute)
            // Current dropdown values are IDs, but table shows Names. 
            // Fix: Let's use the selected text from dropdown to match the table content for simplicity, 
            // OR robustly: put data-sede-id on the TR.
            // Going with text match for now as table has the name.
            const selectedSedeText = document.getElementById('sedeFilter').options[document.getElementById('sedeFilter').selectedIndex].text.toLowerCase();
            const matchesSede = (document.getElementById('sedeFilter').value === "") ? true : sede.includes(selectedSedeText);
            
            // Status Match
            let matchesStatus = true;
            if(statusFilter === 'active') matchesStatus = isActive;
            if(statusFilter === 'inactive') matchesStatus = isInactive;
            
            if (matchesSearch && matchesSede && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Attach listeners
    document.getElementById('searchInput').addEventListener('keyup', filterUsers);
    document.getElementById('sedeFilter').addEventListener('change', filterUsers);
    document.getElementById('statusFilter').addEventListener('change', filterUsers);

    // Password Generator
    function generatePassword() {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
        let password = "";
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('passwordInput').value = password;
        // Optional: Toggle type to text temporarily to show it?
        // document.getElementById('passwordInput').type = 'text';
    }

    // Tab Switching Logic
    function switchTab(tabId) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        
        // Show selected
        document.getElementById('tab-' + tabId).classList.add('active');
        // Activate button
        const btns = document.querySelectorAll('.tab-btn');
        if(tabId === 'general') btns[0].classList.add('active');
        if(tabId === 'horarios') btns[1].classList.add('active');
        if(tabId === 'sedes') btns[2].classList.add('active');
        if(tabId === 'roles') btns[3].classList.add('active');
        if(tabId === 'notificaciones') btns[4].classList.add('active');
    }

    // Check All Logic
    function toggleCheckboxes(containerId, isChecked) {
        const container = document.getElementById(containerId);
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = isChecked);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var flash = document.getElementById('flash-message');
        if (flash) {
            setTimeout(function() {
                flash.style.opacity = '0';
                setTimeout(function() { flash.style.display = 'none'; }, 500);
            }, 3000);
        }
    });

    function openUserModal() {
        document.getElementById('userModal').classList.add('active');
        switchTab('general'); // Reset to general
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    function resetUserForm() {
        var modal = document.getElementById('userModal');
        var form = modal.querySelector('form');
        form.reset();
        form.action = "{{ url('admin/configuration/usuarios') }}"; 
        modal.querySelector('h3').innerText = 'Usuarios'; 
        document.getElementById('user_id').value = '';
        modal.querySelector('button[type="submit"]').innerText = 'Crear';
        
        document.getElementById('checkAllSedes').checked = false;
        document.getElementById('checkAllRoles').checked = false;
    }

    function editUser(btn) {
        var data = JSON.parse(btn.dataset.user);
        var modal = document.getElementById('userModal');
        var form = modal.querySelector('form');
        
        resetUserForm();
        
        form.action = "{{ url('admin/configuration/usuarios') }}"; // In Laravel we usually handle update via hidden ID or separate route. Controller handles both if ID present?
        // Actually route for Update is POST /usuarios/update usually or PUT.
        // Let's check routes... we have Route::post('usuarios/update', 'ConfigurationController@updateUsuario');
        // So we should change action.
        form.action = "{{ url('admin/configuration/usuarios/update') }}";

        document.getElementById('user_id').value = data.id;
        modal.querySelector('h3').innerText = 'Editar Usuario';
        modal.querySelector('button[type="submit"]').innerText = 'Actualizar';
        
        // Map General Fields
        form.querySelector('[name="name"]').value = data.name;
        form.querySelector('[name="email"]').value = data.email;
        if(data.username) form.querySelector('[name="username"]').value = data.username;
        if(data.phone) form.querySelector('[name="phone"]').value = data.phone;
        if(data.identification) form.querySelector('[name="identification"]').value = data.identification;
        // Map Codes/ID types if they exist in DB...
        
        if (data.roles) {
            var roles = Array.isArray(data.roles) ? data.roles : JSON.parse(data.roles);
            roles.forEach(roleId => {
                var cb = form.querySelector('#roles-list input[value="' + roleId + '"]');
                if(cb) cb.checked = true;
            });
        }
        
        if(data.sede_id) {
             // If multi-sede supported later:
             // var cb = form.querySelector('#sedes-list input[value="' + data.sede_id + '"]');
             // if(cb) cb.checked = true;
             // But currently user has 1 sede_id in DB usually.
             // We can check it.
        }
        
        form.querySelector('[name="active"]').checked = (data.active !== 0 && data.active !== false && data.active !== '0');

        openUserModal();
    }
    
    function deleteUser(id) {
        if(confirm('¿Estás seguro de que deseas eliminar este usuario? Esta acción no se puede deshacer.')) {
            window.location.href = "{{ url('admin/configuration/usuarios/delete') }}/" + id;
        }
    }
</script>
@endsection

<!-- GLOBAL TIME PICKER MODAL -->
<div id="timeWheelModal" class="wheel-modal">
    <div class="wheel-content">
        <div style="text-align:center; font-weight:700; font-size:18px; margin-bottom:10px;" id="wheelTitle">Selecciona Hora</div>
        <div style="text-align:center; font-size:13px; color:#9ca3af; margin-bottom:20px;">Desliza para ajustar</div>
        
        <div class="wheel-interface">
            <div class="highlight-bar"></div>
            <div class="wheel-scroller" id="wheelScroller">
                <!-- Generated JS intervals -->
            </div>
        </div>

        <button type="button" class="btn-confirm-time" onclick="confirmTimeSelection()">Confirmar Hora</button>
    </div>
</div>

<style>
    /* WHEEL PICKER STYLES (Copied for isolation) */
    .wheel-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: flex-end; /* Sheet from bottom on mobile */
        justify-content: center;
    }
    .wheel-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 24px 24px 0 0;
        padding: 24px;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding-bottom: 40px;
    }
    @media (min-width: 769px) { .wheel-modal { align-items: center; } .wheel-content { border-radius: 24px; } }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .wheel-interface {
        position: relative;
        height: 200px;
        overflow: hidden;
        mask-image: linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%);
        margin: 20px 0;
    }
    .wheel-scroller {
        height: 100%;
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        -ms-overflow-style: none; /* Hide scrollbar */
        scrollbar-width: none;
    }
    .wheel-scroller::-webkit-scrollbar { display: none; }
    .wheel-item {
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #64748b; /* Gris más oscuro */
        scroll-snap-align: center;
        transition: all 0.2s;
    }
    .wheel-item.active { color: #000000; font-weight: 800; transform: scale(1.15); }
    .highlight-bar {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 50px;
        transform: translateY(-50%);
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        pointer-events: none;
    }
    .btn-confirm-time {
        width: 100%;
        background: #111827;
        color: white;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        border: none;
        margin-top: 20px;
        cursor: pointer;
    }
</style>

<script>
    /* ... Time Wheel Logic ... */
    let activeInputId = null;

    function generateIntervals() {
        const times = [];
        for (let i = 0; i < 24; i++) {
            for (let j = 0; j < 60; j += 5) {
                const h = i.toString().padStart(2, '0');
                const m = j.toString().padStart(2, '0');
                times.push(`${h}:${m}`);
            }
        }
        return times;
    }

    function initWheel() {
        const scroller = document.getElementById('wheelScroller');
        if (scroller.children.length > 5) return; 

        scroller.innerHTML = '<div style="height:75px; flex-shrink:0;"></div>';
        const times = generateIntervals();
        times.forEach(time => {
            const div = document.createElement('div');
            div.className = 'wheel-item';
            div.textContent = time;
            div.dataset.val = time;
            scroller.appendChild(div);
        });
        scroller.insertAdjacentHTML('beforeend', '<div style="height:75px; flex-shrink:0;"></div>');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
               if (entry.isIntersecting) {
                   document.querySelectorAll('.wheel-item').forEach(i => i.classList.remove('active'));
                   entry.target.classList.add('active');
               } 
            });
        }, { root: scroller, threshold: 0.5, rootMargin: "-45% 0px -45% 0px" });
        
        document.querySelectorAll('.wheel-item').forEach(el => observer.observe(el));
    }

    function openTimePicker(inputId, title) {
        activeInputId = inputId;
        document.getElementById('wheelTitle').textContent = title;
        document.getElementById('timeWheelModal').style.display = 'flex';
        initWheel(); 
        
        // Scroll to current
        let currentVal = document.getElementById('input_' + activeInputId).value;
        if(!currentVal) currentVal = "08:00"; // Default
        
        setTimeout(() => {
            const items = document.querySelectorAll('.wheel-item');
            for(let item of items) {
                if (item.dataset.val === currentVal) {
                    item.scrollIntoView({ block: "center" });
                    break;
                }
            }
        }, 100);
    }
    
    function confirmTimeSelection() {
        const activeItem = document.querySelector('.wheel-item.active');
        if (activeItem && activeInputId) {
            const val = activeItem.dataset.val;
            document.getElementById('input_' + activeInputId).value = val;
            document.getElementById('display_' + activeInputId).textContent = val;
            document.getElementById('display_' + activeInputId).style.color = '#111827'; 
            document.getElementById('timeWheelModal').style.display = 'none';
        }
    }
    
    document.getElementById('timeWheelModal').addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
</script>