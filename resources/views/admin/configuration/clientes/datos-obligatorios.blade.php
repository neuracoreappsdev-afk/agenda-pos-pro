@extends('admin.configuration._layout')

@section('config_title', 'Datos Obligatorios')

@section('config_content')
<style>
    .option-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .option-card:hover {
        border-color: #1a73e8;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .option-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .option-icon {
        font-size: 20px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e5e7eb;
    }
    .option-title {
        font-weight: 600;
        color: #1f2937;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Configuración de Campos Requeridos</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Selecciona los datos que tus clientes deben suministrar obligatoriamente al registrarse.</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Email -->
                <div class="option-card">
                    <div class="option-info">
                        <div class="option-icon">📧</div>
                        <span class="option-title">Correo Electrónico</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="client_email_required" value="0">
                        <input type="checkbox" name="client_email_required" value="1" {{ ($settings['client_email_required'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Teléfono -->
                <div class="option-card">
                    <div class="option-info">
                        <div class="option-icon">📱</div>
                        <span class="option-title">Teléfono / Celular</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="client_phone_required" value="0">
                        <input type="checkbox" name="client_phone_required" value="1" {{ ($settings['client_phone_required'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Dirección -->
                <div class="option-card">
                    <div class="option-info">
                        <div class="option-icon">🏠</div>
                        <span class="option-title">Dirección de Residencia</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="client_address_required" value="0">
                        <input type="checkbox" name="client_address_required" value="1" {{ ($settings['client_address_required'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Cumpleaños -->
                <div class="option-card">
                    <div class="option-info">
                        <div class="option-icon">🎂</div>
                        <span class="option-title">Fecha de Cumpleaños</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="client_birthday_required" value="0">
                        <input type="checkbox" name="client_birthday_required" value="1" {{ ($settings['client_birthday_required'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Género -->
                <div class="option-card">
                    <div class="option-info">
                        <div class="option-icon">⚧️</div>
                        <span class="option-title">Género</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="client_gender_required" value="0">
                        <input type="checkbox" name="client_gender_required" value="1" {{ ($settings['client_gender_required'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>
@endsection