@extends('admin.configuration._layout')

@section('config_title', 'Integración WhatsApp API')

@section('config_content')
<style>
    .integration-status-card {
        padding: 20px 25px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        border: 1px solid #e5e7eb;
    }
    .status-active { background: #ecfdf5; border-color: #10b981; }
    .status-inactive { background: #f9fafb; border-color: #e5e7eb; }
    
    .settings-group {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 25px;
    }
    .help-icon {
        color: #9ca3af;
        cursor: help;
        font-size: 14px;
        margin-left: 5px;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}

    <!-- Estado de la Integración -->
    <div class="integration-status-card {{ ($settings['whatsapp_enabled'] ?? false) ? 'status-active' : 'status-inactive' }}">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                {{ ($settings['whatsapp_enabled'] ?? false) ? '✅' : '🔌' }}
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #111827;">Estado de WhatsApp</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">
                    {{ ($settings['whatsapp_enabled'] ?? false) ? 'La integración está activa y lista para enviar.' : 'La integración está desactivada.' }}
                </p>
            </div>
        </div>
        <label class="switch">
            <input type="hidden" name="whatsapp_enabled" value="0">
            <input type="checkbox" name="whatsapp_enabled" value="1" {{ ($settings['whatsapp_enabled'] ?? false) ? 'checked' : '' }}>
            <span class="slider round"></span>
        </label>
    </div>

    <!-- Credenciales -->
    <div class="settings-group">
        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <span>🔑 Credenciales de Conexión</span>
        </h3>
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label>Proveedor de API</label>
            <select name="whatsapp_provider" class="form-control">
                <option value="whatsapp_business" {{ ($settings['whatsapp_provider'] ?? '') == 'whatsapp_business' ? 'selected' : '' }}>WhatsApp Cloud API (Meta)</option>
                <option value="twilio" {{ ($settings['whatsapp_provider'] ?? '') == 'twilio' ? 'selected' : '' }}>Twilio</option>
                <option value="custom" {{ ($settings['whatsapp_provider'] ?? '') == 'custom' ? 'selected' : '' }}>API Personalizada</option>
            </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div class="form-group">
                <label>Phone Number ID</label>
                <input type="text" name="whatsapp_phone_id" class="form-control" value="{{ $settings['whatsapp_phone_id'] ?? '' }}" placeholder="Ej: 1083457912...">
            </div>
            <div class="form-group">
                <label>WhatsApp Business ID</label>
                <input type="text" name="whatsapp_business_id" class="form-control" value="{{ $settings['whatsapp_business_id'] ?? '' }}" placeholder="Ej: 219348579...">
            </div>
        </div>

        <div class="form-group">
            <label>Access Token (Permanente)</label>
            <textarea name="whatsapp_api_token" class="form-control" rows="3" placeholder="Pegue aquí su Access Token de Meta...">{{ $settings['whatsapp_api_token'] ?? '' }}</textarea>
            <small style="color: #6b7280;">Este token permite que el sistema envíe mensajes en su nombre.</small>
        </div>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f3f4f6;">
            <button type="button" class="btn btn-secondary" style="background: white; border: 1px solid #d1d5db; color: #374151;" onclick="alert('Funcionalidad de prueba en desarrollo...')">
                🧪 Probar Conexión
            </button>
        </div>
    </div>

    <!-- Funcionalidades -->
    <div class="settings-group">
        <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 20px;">⚙️ Automatizaciones Activas</h3>
        
        <div style="display: grid; gap: 15px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 600; font-size: 14px;">Notificaciones de Citas</div>
                    <div style="font-size: 12px; color: #6b7280;">Enviar confirmación y recordatorios automáticos.</div>
                </div>
                <label class="switch">
                    <input type="hidden" name="whatsapp_booking_notifications" value="0">
                    <input type="checkbox" name="whatsapp_booking_notifications" value="1" {{ ($settings['whatsapp_booking_notifications'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 8px;">
                <div>
                    <div style="font-weight: 600; font-size: 14px;">Respuestas Inteligentes</div>
                    <div style="font-size: 12px; color: #6b7280;">Responder automáticamente fuera de horario o dudas comunes.</div>
                </div>
                <label class="switch">
                    <input type="hidden" name="whatsapp_auto_reply" value="0">
                    <input type="checkbox" name="whatsapp_auto_reply" value="1" {{ ($settings['whatsapp_auto_reply'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <div class="btn-group">
        <button type="submit" class="btn btn-primary" style="padding: 12px 35px;">Guardar Configuración</button>
        <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
    </div>
</form>
@endsection