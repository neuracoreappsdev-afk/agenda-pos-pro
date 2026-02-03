@extends('admin.configuration._layout')

@section('config_title', 'Link de Registro de Clientes')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Link de Registro de Clientes</h3>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Habilitar Registro Público</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="switch">
                        <input type="checkbox" name="enable_public_registration" value="1" {{ ($settings['enable_public_registration'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span style="color: #4b5563; font-size: 14px;">Permitir que los clientes se registren por sí mismos</span>
                </div>
            </div>

            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Mensaje de Bienvenida</label>
                <textarea name="registration_welcome_message" rows="4" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="¡Bienvenido a nuestra familia! Gracias por registrarte.">{{ $settings['registration_welcome_message'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Este mensaje aparecerá en la pantalla al completar el registro.</small>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Redirección Post-Registro</label>
                <input type="text" name="registration_redirect_url" value="{{ $settings['registration_redirect_url'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="https://mi-negocio.com/gracias">
                <small style="color: #6b7280; font-size: 12px;">Opcional: URL a la que será redirigido el cliente tras registrarse.</small>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection