@extends('admin.configuration._layout')

@section('config_title', 'Correos Electrónicos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Correos Electrónicos</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Driver de Correo</label>
                <select name="email_driver" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="smtp" {{ ($settings['email_driver'] ?? '') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="ses" {{ ($settings['email_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                    <option value="mailgun" {{ ($settings['email_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Host (Servidor)</label>
                <input type="text" name="email_host" value="{{ $settings['email_host'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="smtp.gmail.com">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Puerto</label>
                <input type="number" name="email_port" value="{{ $settings['email_port'] ?? '587' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Usuario</label>
                <input type="text" name="email_username" value="{{ $settings['email_username'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Contraseña</label>
                <input type="password" name="email_password" value="{{ $settings['email_password'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Encriptación</label>
                <select name="email_encryption" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="tls" {{ ($settings['email_encryption'] ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                    <option value="ssl" {{ ($settings['email_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                    <option value="none" {{ ($settings['email_encryption'] ?? '') == 'none' ? 'selected' : '' }}>Ninguna</option>
                </select>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection