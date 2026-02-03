@extends('admin.configuration._layout')

@section('config_title', 'Configuración Formulario Registro de Clientes')

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

    .form-control:focus {
        border-color: #2563eb;
        outline: none;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
    }

    /* Toggle Switch */
    .switch-container {
        display: flex;
        align-items: center;
        gap: 12px;
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

    .switch-label {
        font-size: 14px;
        color: #374151;
    }

    /* Table */
    .fields-table {
        width: 100%;
        border-collapse: collapse;
    }

    .fields-table th {
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
    }

    .fields-table td {
        padding: 12px 10px;
        font-size: 14px;
        color: #374151;
        border-bottom: 1px solid #f3f4f6;
    }

    .fields-table select {
        padding: 6px 10px;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        font-size: 13px;
        min-width: 120px;
        background: white;
    }

    /* Placeholder */
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

    .sede-placeholder h4 {
        font-size: 16px;
        color: #6b7280;
        margin: 0 0 5px 0;
    }

    .sede-placeholder p {
        font-size: 14px;
        margin: 0;
    }

    .btn-save {
        background-color: #2563eb;
        color: white;
        padding: 8px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
    }

    .btn-save:hover {
        background-color: #1d4ed8;
    }

    .header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
</style>

<div class="config-card">
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
        <h4>Sede</h4>
        <p>Seleccione una sede</p>
    </div>

    <!-- Configuración (oculta hasta seleccionar sede) -->
    <div id="sedeConfig" style="display: none;">
        <div class="header-row">
            <div class="switch-container" style="margin-bottom: 0;">
                <label class="switch">
                    <input type="checkbox" name="registro_sin_contrasena" id="registroSinContrasena" value="1" {{ $settings['registro_sin_contrasena'] ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
                <span class="switch-label">El cliente puede registrarse sin contraseña</span>
            </div>
            <button type="button" class="btn-save" onclick="saveFormularioConfig()">Guardar</button>
        </div>

        <table class="fields-table">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Valor</th>
                    <th>Mostrar en Formulario</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nombres</strong></td>
                    <td>
                        <select name="campo_nombres_valor" id="campoNombresValor">
                            <option value="obligatorio" {{ $settings['campo_nombres_valor'] == 'obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                            <option value="opcional" {{ $settings['campo_nombres_valor'] == 'opcional' ? 'selected' : '' }}>Opcional</option>
                        </select>
                    </td>
                    <td>
                        <select name="campo_nombres_mostrar" id="campoNombresMostrar">
                            <option value="1" {{ $settings['campo_nombres_mostrar'] ? 'selected' : '' }}>Mostrar</option>
                            <option value="0" {{ !$settings['campo_nombres_mostrar'] ? 'selected' : '' }}>No Mostrar</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Apellidos</strong></td>
                    <td>
                        <select name="campo_apellidos_valor" id="campoApellidosValor">
                            <option value="obligatorio" {{ $settings['campo_apellidos_valor'] == 'obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                            <option value="opcional" {{ $settings['campo_apellidos_valor'] == 'opcional' ? 'selected' : '' }}>Opcional</option>
                        </select>
                    </td>
                    <td>
                        <select name="campo_apellidos_mostrar" id="campoApellidosMostrar">
                            <option value="1" {{ $settings['campo_apellidos_mostrar'] ? 'selected' : '' }}>Mostrar</option>
                            <option value="0" {{ !$settings['campo_apellidos_mostrar'] ? 'selected' : '' }}>No Mostrar</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Identificación</strong></td>
                    <td>
                        <select name="campo_identificacion_valor" id="campoIdentificacionValor">
                            <option value="obligatorio" {{ $settings['campo_identificacion_valor'] == 'obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                            <option value="opcional" {{ $settings['campo_identificacion_valor'] == 'opcional' ? 'selected' : '' }}>Opcional</option>
                        </select>
                    </td>
                    <td>
                        <select name="campo_identificacion_mostrar" id="campoIdentificacionMostrar">
                            <option value="1" {{ $settings['campo_identificacion_mostrar'] ? 'selected' : '' }}>Mostrar</option>
                            <option value="0" {{ !$settings['campo_identificacion_mostrar'] ? 'selected' : '' }}>No Mostrar</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Email</strong></td>
                    <td>
                        <select name="campo_email_valor" id="campoEmailValor">
                            <option value="obligatorio" {{ $settings['campo_email_valor'] == 'obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                            <option value="opcional" {{ $settings['campo_email_valor'] == 'opcional' ? 'selected' : '' }}>Opcional</option>
                        </select>
                    </td>
                    <td>
                        <select name="campo_email_mostrar" id="campoEmailMostrar">
                            <option value="1" {{ $settings['campo_email_mostrar'] ? 'selected' : '' }}>Mostrar</option>
                            <option value="0" {{ !$settings['campo_email_mostrar'] ? 'selected' : '' }}>No Mostrar</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><strong>Celular</strong></td>
                    <td>
                        <select name="campo_celular_valor" id="campoCelularValor">
                            <option value="obligatorio" {{ $settings['campo_celular_valor'] == 'obligatorio' ? 'selected' : '' }}>Obligatorio</option>
                            <option value="opcional" {{ $settings['campo_celular_valor'] == 'opcional' ? 'selected' : '' }}>Opcional</option>
                        </select>
                    </td>
                    <td>
                        <select name="campo_celular_mostrar" id="campoCelularMostrar">
                            <option value="1" {{ $settings['campo_celular_mostrar'] ? 'selected' : '' }}>Mostrar</option>
                            <option value="0" {{ !$settings['campo_celular_mostrar'] ? 'selected' : '' }}>No Mostrar</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
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

    function saveFormularioConfig() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('registro_sin_contrasena', document.getElementById('registroSinContrasena').checked ? '1' : '0');
        formData.append('campo_nombres_valor', document.getElementById('campoNombresValor').value);
        formData.append('campo_nombres_mostrar', document.getElementById('campoNombresMostrar').value);
        formData.append('campo_apellidos_valor', document.getElementById('campoApellidosValor').value);
        formData.append('campo_apellidos_mostrar', document.getElementById('campoApellidosMostrar').value);
        formData.append('campo_identificacion_valor', document.getElementById('campoIdentificacionValor').value);
        formData.append('campo_identificacion_mostrar', document.getElementById('campoIdentificacionMostrar').value);
        formData.append('campo_email_valor', document.getElementById('campoEmailValor').value);
        formData.append('campo_email_mostrar', document.getElementById('campoEmailMostrar').value);
        formData.append('campo_celular_valor', document.getElementById('campoCelularValor').value);
        formData.append('campo_celular_mostrar', document.getElementById('campoCelularMostrar').value);
        
        fetch('{{ url("admin/configuration/save-formulario-registro") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if(response.ok) {
                showToast('Configuración guardada exitosamente', 'success');
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