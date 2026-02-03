@extends('admin.configuration._layout')

@section('config_title', 'Fidelización Externa')

@section('config_content')
<style>
    .loyalty-banner {
        background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
        border-radius: 12px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .platform-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .platform-card.active {
        border-color: #1a73e8;
        background: #f0f7ff;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <div class="loyalty-banner">
                <div>
                    <h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 700;">Premia la Lealtad de tus Clientes</h3>
                    <p style="margin: 0; font-size: 14px; opacity: 0.9;">Conecta con plataformas externas para gestionar puntos y recompensas automáticamente.</p>
                </div>
                <div style="font-size: 40px;">🎁</div>
            </div>

            <!-- Selector de Plataforma -->
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 15px;">Selecciona tu Plataforma</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <input type="hidden" name="loyalty_ext_platform" id="loyalty_ext_platform" value="{{ $settings['loyalty_ext_platform'] ?? 'leal' }}">
                
                <div class="platform-card {{ ($settings['loyalty_ext_platform'] ?? 'leal') == 'leal' ? 'active' : '' }}" onclick="selectPlatform('leal')">
                    <div style="font-weight: 700; text-align: center;">LEAL</div>
                </div>

                <div class="platform-card {{ ($settings['loyalty_ext_platform'] ?? '') == 'puntos_colombia' ? 'active' : '' }}" onclick="selectPlatform('puntos_colombia')">
                    <div style="font-weight: 700; text-align: center;">Puntos Col.</div>
                </div>

                <div class="platform-card {{ ($settings['loyalty_ext_platform'] ?? '') == 'tuya' ? 'active' : '' }}" onclick="selectPlatform('tuya')">
                    <div style="font-weight: 700; text-align: center;">Tuya</div>
                </div>

                <div class="platform-card {{ ($settings['loyalty_ext_platform'] ?? '') == 'custom' ? 'active' : '' }}" onclick="selectPlatform('custom')">
                    <div style="font-weight: 700; text-align: center;">Custom API</div>
                </div>
            </div>

            <!-- Configuración -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #f3f4f6; padding-bottom: 15px;">
                    <div>
                        <div style="font-weight: 700; color: #111827; font-size: 14px;">Habilitar Acumulación Externa</div>
                        <div style="font-size: 12px; color: #6b7280;">Sincronizar puntos de la venta con la plataforma externa.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="loyalty_ext_enabled" value="0">
                        <input type="checkbox" name="loyalty_ext_enabled" value="1" {{ ($settings['loyalty_ext_enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <div style="display: grid; gap: 20px;">
                    <div class="form-group">
                        <label>API Key / App ID</label>
                        <input type="text" name="loyalty_ext_key" value="{{ $settings['loyalty_ext_key'] ?? '' }}" class="form-control" placeholder="Ej: pk_live_...">
                    </div>
                    <div class="form-group">
                        <label>Secret Key / Password</label>
                        <input type="password" name="loyalty_ext_secret" value="{{ $settings['loyalty_ext_secret'] ?? '' }}" class="form-control">
                    </div>
                </div>

                <div style="margin-top: 25px; padding: 15px; background: #fffbeb; border-radius: 8px; border: 1px solid #fef3c7;">
                    <p style="margin: 0; color: #92400e; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                        <span>💡</span> Esta integración permite que tus clientes rediman puntos directamente en tu caja usando su saldo externo.
                    </p>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Integración</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>

<script>
function selectPlatform(slug) {
    document.getElementById('loyalty_ext_platform').value = slug;
    document.querySelectorAll('.platform-card').forEach(opt => opt.classList.remove('active'));
    event.currentTarget.classList.add('active');
}
</script>
@endsection