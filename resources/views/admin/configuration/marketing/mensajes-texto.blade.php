@extends('admin.configuration._layout')

@section('config_title', 'Mensajes de Texto (SMS)')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Mensajes de Texto (SMS)</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Proveedor de SMS</label>
                <select name="sms_provider" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="twilio" {{ ($settings['sms_provider'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                    <option value="aws" {{ ($settings['sms_provider'] ?? '') == 'aws' ? 'selected' : '' }}>AWS SNS</option>
                    <option value="generic" {{ ($settings['sms_provider'] ?? '') == 'generic' ? 'selected' : '' }}>Gateway Genérico</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Account SID / API Key</label>
                <input type="text" name="sms_api_key" value="{{ $settings['sms_api_key'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Auth Token / Secret</label>
                <input type="password" name="sms_api_secret" value="{{ $settings['sms_api_secret'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Número de Envío (From)</label>
                <input type="text" name="sms_from_number" value="{{ $settings['sms_from_number'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="+1234567890">
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection