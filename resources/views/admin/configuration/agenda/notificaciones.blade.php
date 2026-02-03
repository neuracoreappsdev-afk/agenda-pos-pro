@extends('admin.configuration._layout')

@section('config_title', 'Configuración de Notificaciones de Reservas/Agenda')

@section('config_content')

<style>
    .config-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }

    /* Tabs */
    .tabs-container {
        margin-bottom: 20px;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: #1f2937;
    }

    .tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #111827;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        padding: 10px 12px;
        font-size: 14px;
        color: #374151;
        background-color: white;
    }

    /* Toggle Switch */
    .toggle-container {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .switch input {
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
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2563eb;
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    .toggle-text {
        flex: 1;
    }

    .toggle-title {
        font-weight: 600;
        font-size: 14px;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .toggle-desc {
        font-size: 13px;
        color: #1a73e8;
        margin: 0;
    }

    /* Buttons Config Section */
    .buttons-config {
        background: #f9fafb;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .buttons-config-title {
        text-align: center;
        font-weight: 600;
        font-size: 15px;
        color: #374151;
        margin-bottom: 20px;
    }

    .button-toggle-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
    }

    .button-toggle-content {
        flex: 1;
    }

    .button-toggle-content h4 {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .button-toggle-content p {
        font-size: 12px;
        color: #1a73e8;
        margin: 0;
    }

    /* Reminder Config */
    .reminder-section {
        background: #f9fafb;
        border-radius: 10px;
        padding: 20px;
    }

    .reminder-title {
        text-align: center;
        font-weight: 600;
        font-size: 15px;
        color: #374151;
        margin-bottom: 20px;
    }

    .reminder-row {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .reminder-row select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        min-width: 140px;
    }

    .btn-add {
        background: #2563eb;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-remove {
        background: #ef4444;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-save {
        background: #2563eb;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
    }

    .btn-save:hover {
        background: #1d4ed8;
    }

    .header-row {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    .sede-placeholder {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .sede-placeholder-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #d1d5db;
    }

    .custom-reminder {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        background: white;
        margin-bottom: 10px;
    }

    .reminder-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
</style>

<div class="config-card">
    <!-- Tabs -->
    <div class="tabs-container">
        <button type="button" class="tab-btn active" onclick="showTab('recordatorios')">Recordatorios</button>
    </div>

    <!-- Sede Selector -->
    <div class="form-group">
        <label class="form-label">Sede</label>
        <select class="form-control" id="sedeSelector" onchange="toggleSedeConfig()">
            <option value="">Sedes ...</option>
            <option value="1">Holguines Trade Center.</option>
        </select>
    </div>

    <!-- Placeholder cuando no hay sede seleccionada -->
    <div id="sedePlaceholder" class="sede-placeholder">
        <div class="sede-placeholder-icon">📊</div>
        <h4 style="font-size: 16px; color: #6b7280; margin: 0 0 5px;">Sede</h4>
        <p style="font-size: 14px; margin: 0;">Seleccione una sede</p>
    </div>

    <!-- Main Config (oculto hasta seleccionar sede) -->
    <div id="sedeConfig" style="display: none;">

        <div class="header-row">
            <button type="button" class="btn-save" onclick="saveRecordatorios()">Guardar recordatorios</button>
        </div>

        <!-- Toggle Activar Recordatorios -->
        <div class="toggle-container">
            <label class="switch">
                <input type="checkbox" name="recordatorios_activos" id="recordatoriosActivos" value="1" {{ $settings['recordatorios_activos'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
            <div class="toggle-text">
                <h4 class="toggle-title">Activar Recordatorios</h4>
                <p class="toggle-desc">Active los recordatorios para recordar a sus clientes sus próximos servicios o reservas.</p>
            </div>
        </div>

        <!-- Configurar Botones -->
        <div class="buttons-config">
            <div class="buttons-config-title">Configurar botones de confirmación de cita</div>
            
            <div class="button-toggle-item">
                <label class="switch">
                    <input type="checkbox" name="mostrar_boton_confirmar" id="mostrarBotonConfirmar" value="1" {{ $settings['mostrar_boton_confirmar'] ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <div class="button-toggle-content">
                    <h4>Mostrar Botón de Confirmar</h4>
                    <p>Muestra u oculta el botón de Confirmar en las notificaciones del cliente</p>
                </div>
            </div>

            <div class="button-toggle-item">
                <label class="switch">
                    <input type="checkbox" name="mostrar_boton_cancelar" id="mostrarBotonCancelar" value="1" {{ $settings['mostrar_boton_cancelar'] ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <div class="button-toggle-content">
                    <h4>Mostrar Botón de Cancelar</h4>
                    <p>Muestra u oculta el botón de Cancelar en las notificaciones del cliente</p>
                </div>
            </div>

            <div class="button-toggle-item" style="margin-bottom: 0;">
                <label class="switch">
                    <input type="checkbox" name="mostrar_boton_reagendar" id="mostrarBotonReagendar" value="1" {{ $settings['mostrar_boton_reagendar'] ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <div class="button-toggle-content">
                    <h4>Mostrar Botón de Reagendar</h4>
                    <p>Muestra u oculta el botón de Reagendar en las notificaciones del cliente</p>
                </div>
            </div>
        </div>

        <!-- Configurar Recordatorios -->
        <div class="reminder-section">
            <div class="reminder-title">Configurar recordatorios para tus clientes antes de las citas</div>
            
            <!-- Tabla de recordatorios -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 100px; gap: 10px; margin-bottom: 15px; font-weight: 600; font-size: 13px; color: #6b7280;">
                <div style="text-align: center;">Notificación</div>
                <div style="text-align: center;">Notificar a Clientes por</div>
                <div></div>
            </div>

            <!-- Recordatorio fijo -->
            <div class="reminder-row">
                <select id="reminder_time_default">
                    <option value="10_minutos" selected>10 minutos antes</option>
                    <option value="30_minutos">30 minutos antes</option>
                    <option value="1_hora">1 hora antes</option>
                    <option value="2_horas">2 horas antes</option>
                    <option value="24_horas">24 horas antes</option>
                </select>
                <select id="reminder_channel_default">
                    <option value="email_sms" selected>Email y Sms</option>
                    <option value="email">Solo Email</option>
                    <option value="sms">Solo SMS</option>
                    <option value="whatsapp">WhatsApp</option>
                </select>
                <button type="button" class="btn-add" onclick="addReminder()">Agregar</button>
            </div>

            <!-- Contenedor de recordatorios personalizados -->
            <div id="customReminders">
                <!-- Los recordatorios personalizados se agregan aquí -->
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSedeConfig() {
        const selector = document.getElementById('sedeSelector');
        const placeholder = document.getElementById('sedePlaceholder');
        const config = document.getElementById('sedeConfig');
        
        if (selector.value) {
            placeholder.style.display = 'none';
            config.style.display = 'block';
        } else {
            placeholder.style.display = 'block';
            config.style.display = 'none';
        }
    }

    function showTab(tab) {
        // Por ahora solo tenemos una pestaña
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
    }

    let reminderCount = 0;

    function addReminder() {
        reminderCount++;
        const container = document.getElementById('customReminders');
        
        const reminderHtml = `
            <div class="custom-reminder" id="reminder_${reminderCount}">
                <div class="reminder-row" style="margin-bottom: 10px;">
                    <span style="font-size: 18px;">⚠️</span>
                    <select name="reminder_type_${reminderCount}">
                        <option value="personalizado" selected>Personalizado</option>
                    </select>
                    <select name="reminder_channel_${reminderCount}">
                        <option value="email_sms" selected>Email y Sms</option>
                        <option value="email">Solo Email</option>
                        <option value="sms">Solo SMS</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <button type="button" class="btn-remove" onclick="removeReminder(${reminderCount})">×</button>
                </div>
                <div class="reminder-row">
                    <input type="number" name="reminder_value_${reminderCount}" value="3" style="width: 60px; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;">
                    <select name="reminder_unit_${reminderCount}">
                        <option value="minutos">minutos</option>
                        <option value="horas" selected>horas</option>
                        <option value="dias">días</option>
                    </select>
                    <span style="color: #6b7280;">antes</span>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', reminderHtml);
    }

    function removeReminder(id) {
        const el = document.getElementById('reminder_' + id);
        if (el) el.remove();
    }

    function saveRecordatorios() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('recordatorios_activos', document.getElementById('recordatoriosActivos').checked ? '1' : '0');
        formData.append('mostrar_boton_confirmar', document.getElementById('mostrarBotonConfirmar').checked ? '1' : '0');
        formData.append('mostrar_boton_cancelar', document.getElementById('mostrarBotonCancelar').checked ? '1' : '0');
        formData.append('mostrar_boton_reagendar', document.getElementById('mostrarBotonReagendar').checked ? '1' : '0');
        
        fetch('{{ url("admin/configuration/save-notificaciones") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if(response.ok) {
                showToast('Configuración de recordatorios guardada', 'success');
            } else {
                showToast('Error al guardar la configuración', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        });
    }
</script>

@endsection