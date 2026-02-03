@extends('admin.configuration._layout')

@section('config_title', 'Integración Contable')

@section('config_content')
<style>
    .software-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .software-option {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: white;
    }
    .software-option.active {
        border-color: #1a73e8;
        background: #f0f7ff;
    }
    .software-logo {
        height: 40px;
        margin-bottom: 10px;
        object-fit: contain;
    }
    .integration-group {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Software de Contabilidad</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Sincroniza tus ventas, inventarios y gastos con tu software contable favorito.</p>

            <div class="software-grid">
                <input type="hidden" name="accounting_software" id="accounting_software" value="{{ $settings['accounting_software'] ?? 'siigo' }}">
                
                <div class="software-option {{ ($settings['accounting_software'] ?? 'siigo') == 'siigo' ? 'active' : '' }}" onclick="selectSoftware('siigo')">
                    <div style="font-size: 24px; margin-bottom: 5px;">🟦</div>
                    <div style="font-weight: 700; font-size: 14px;">Siigo Nube</div>
                </div>
                
                <div class="software-option {{ ($settings['accounting_software'] ?? '') == 'alegra' ? 'active' : '' }}" onclick="selectSoftware('alegra')">
                    <div style="font-size: 24px; margin-bottom: 5px;">🟧</div>
                    <div style="font-weight: 700; font-size: 14px;">Alegra</div>
                </div>

                <div class="software-option {{ ($settings['accounting_software'] ?? '') == 'quickbooks' ? 'active' : '' }}" onclick="selectSoftware('quickbooks')">
                    <div style="font-size: 24px; margin-bottom: 5px;">🟩</div>
                    <div style="font-weight: 700; font-size: 14px;">QuickBooks</div>
                </div>

                <div class="software-option {{ ($settings['accounting_software'] ?? '') == 'custom' ? 'active' : '' }}" onclick="selectSoftware('custom')">
                    <div style="font-size: 24px; margin-bottom: 5px;">⚙️</div>
                    <div style="font-weight: 700; font-size: 14px;">API Custom</div>
                </div>
            </div>

            <!-- Estado -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6; margin-bottom: 25px;">
                <div>
                    <div style="font-weight: 700; color: #111827; font-size: 14px;">Habilitar Sincronización Automática</div>
                    <div style="font-size: 12px; color: #6b7280;">Enviar facturas al software contable inmediatamente después de la venta.</div>
                </div>
                <label class="switch">
                    <input type="hidden" name="accounting_enabled" value="0">
                    <input type="checkbox" name="accounting_enabled" value="1" {{ ($settings['accounting_enabled'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <!-- Credenciales -->
            <div class="integration-group">
                <h4 style="margin: 0 0 20px 0; font-size: 15px; font-weight: 700; color: #111827;">🔑 Configuración de Acceso</h4>
                
                <div class="form-group">
                    <label>API Endpoint / URL</label>
                    <input type="text" name="accounting_api_url" value="{{ $settings['accounting_api_url'] ?? '' }}" class="form-control" placeholder="https://api.siigo.com/v1/">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div class="form-group">
                        <label>Usuario / API Key</label>
                        <input type="text" name="accounting_api_user" value="{{ $settings['accounting_api_user'] ?? '' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Token de Acceso</label>
                        <input type="password" name="accounting_api_token" value="{{ $settings['accounting_api_token'] ?? '' }}" class="form-control">
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" style="background: white; border: 1px solid #d1d5db; color: #374151;" onclick="alert('Validando credenciales...')">
                        🧪 Probar Conexión
                    </button>
                </div>
            </div>

            <!-- Preferencias -->
            <div class="integration-group">
                <h4 style="margin: 0 0 15px 0; font-size: 15px; font-weight: 700; color: #111827;">⚙️ Módulos a Sincronizar</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="sync_invoices" value="1" {{ ($settings['sync_invoices'] ?? false) ? 'checked' : '' }}>
                        <span style="font-size: 13px;">Facturas (Ventas)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="sync_clients" value="1" {{ ($settings['sync_clients'] ?? false) ? 'checked' : '' }}>
                        <span style="font-size: 13px;">Clientes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="sync_products" value="1" {{ ($settings['sync_products'] ?? false) ? 'checked' : '' }}>
                        <span style="font-size: 13px;">Inventario</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" name="sync_expenses" value="1" {{ ($settings['sync_expenses'] ?? false) ? 'checked' : '' }}>
                        <span style="font-size: 13px;">Gastos</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Configuración</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>

<script>
function selectSoftware(slug) {
    document.getElementById('accounting_software').value = slug;
    document.querySelectorAll('.software-option').forEach(opt => opt.classList.remove('active'));
    event.currentTarget.classList.add('active');
}
</script>
@endsection