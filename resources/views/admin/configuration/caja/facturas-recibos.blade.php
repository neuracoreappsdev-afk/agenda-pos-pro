@extends('admin.configuration._layout')

@section('config_title', 'Facturas y Recibos')

@section('config_content')
<style>
    .billing-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 25px;
        background: #f3f4f6;
        padding: 6px;
        border-radius: 12px;
        overflow-x: auto;
    }
    .billing-tab-btn {
        padding: 10px 20px;
        border: none;
        background: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .billing-tab-btn.active {
        background: white;
        color: #1a73e8;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .billing-content-section {
        display: none;
    }
    .billing-content-section.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .setting-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    .setting-label {
        font-weight: 700;
        color: #111827;
        font-size: 14px;
        margin-bottom: 4px;
        display: block;
    }
    .setting-desc {
        color: #6b7280;
        font-size: 12px;
    }
    .fe-provider-card {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
    }
    .fe-provider-card.active {
        border-color: #1a73e8;
        background: #f0f7ff;
    }
</style>

<div class="billing-tabs">
    <button class="billing-tab-btn active" onclick="showBillingTab('general')">General</button>
    <button class="billing-tab-btn" onclick="showBillingTab('cajas')">Cajas / POS</button>
    <button class="billing-tab-btn" onclick="showBillingTab('electronica')">Factura Electrónica</button>
    <button class="billing-tab-btn" onclick="showBillingTab('resoluciones')">Resoluciones DIAN</button>
</div>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}

    <!-- SECCIÓN GENERAL -->
    <div id="section-general" class="billing-content-section active">
        <div class="config-card">
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 20px;">Configuración de Venta</h3>
            
            <div class="setting-row">
                <div>
                    <span class="setting-label">Tipo de Descuento</span>
                    <span class="setting-desc">General por factura o ítem por ítem.</span>
                </div>
                <select name="tipo_descuento" class="form-control" style="width: auto;">
                    <option value="general" {{ ($config['tipo_descuento'] ?? '') == 'general' ? 'selected' : '' }}>General</option>
                    <option value="items" {{ ($config['tipo_descuento'] ?? '') == 'items' ? 'selected' : '' }}>Por Ítem</option>
                </select>
            </div>

            <div class="setting-row">
                <div>
                    <span class="setting-label">Apertura y Cierre de Caja</span>
                    <span class="setting-desc">Exigir flujo de apertura al iniciar el día.</span>
                </div>
                <label class="switch">
                    <input type="hidden" name="habilitar_apertura_cierre" value="0">
                    <input type="checkbox" name="habilitar_apertura_cierre" value="1" {{ ($config['habilitar_apertura_cierre'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="setting-row">
                <div>
                    <span class="setting-label">Personal Obligatorio</span>
                    <span class="setting-desc">Exigir seleccionar el especialista para facturar.</span>
                </div>
                <label class="switch">
                    <input type="hidden" name="personal_obligatorio_servicios" value="0">
                    <input type="checkbox" name="personal_obligatorio_servicios" value="1" {{ ($config['personal_obligatorio_servicios'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <div class="setting-row">
                <div>
                    <span class="setting-label">Control de Eliminación de Ítems</span>
                    <span class="setting-desc">Pedir clave de supervisor para borrar productos del carrito.</span>
                </div>
                <label class="switch">
                    <input type="hidden" name="control_eliminacion" value="0">
                    <input type="checkbox" name="control_eliminacion" value="1" {{ ($config['control_eliminacion'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
    </div>

    <!-- SECCIÓN CAJAS -->
    <div id="section-cajas" class="billing-content-section">
        <div class="config-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #111827;">Terminales POS</h3>
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Funcionalidad de nueva caja bajo mantenimiento')">➕ Nueva Caja</button>
            </div>
            
            <div style="display: grid; gap: 12px;">
                @foreach($cajas as $caja)
                <div style="display: flex; align-items: center; gap: 15px; padding: 15px; border: 1px solid #e5e7eb; border-radius: 12px;">
                    <div style="font-size: 24px;">🖥️</div>
                    <div style="flex: 1;">
                        <h4 style="margin: 0; font-size: 14px; font-weight: 700;">{{ $caja['nombre'] }}</h4>
                        <span style="font-size: 12px; color: {{ $caja['activo'] ? '#10b981' : '#ef4444' }};">● {{ $caja['activo'] ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="alert('Editando caja...')">Configurar</button>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- SECCIÓN ELECTRÓNICA -->
    <div id="section-electronica" class="billing-content-section">
        <div class="config-card">
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin-bottom: 8px;">Facturación Electrónica</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Configura tu proveedor de facturación para cumplimiento legal.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 30px;">
                <input type="hidden" name="pos_electronico_provider" id="fe_provider" value="{{ $pos_electronico['provider'] ?? '' }}">
                <div class="fe-provider-card {{ ($pos_electronico['provider'] ?? '') == 'siigo' ? 'active' : '' }}" onclick="selectFE('siigo')">
                    <div style="font-weight: 700;">Siigo</div>
                </div>
                <div class="fe-provider-card {{ ($pos_electronico['provider'] ?? '') == 'alegra' ? 'active' : '' }}" onclick="selectFE('alegra')">
                    <div style="font-weight: 700;">Alegra</div>
                </div>
                <div class="fe-provider-card {{ ($pos_electronico['provider'] ?? '') == 'facture' ? 'active' : '' }}" onclick="selectFE('facture')">
                    <div style="font-weight: 700;">Facture</div>
                </div>
            </div>

            <div style="background: #f9fafb; padding: 25px; border-radius: 12px; border: 1px solid #f3f4f6;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Prefijo de Facturación</label>
                        <input type="text" name="fe_prefix" value="{{ $pos_electronico['prefix'] ?? 'FE' }}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Consecutivo Inicial</label>
                        <input type="number" name="fe_start" value="{{ $pos_electronico['start'] ?? '1' }}" class="form-control">
                    </div>
                </div>
                <div class="form-group" style="margin-top: 20px;">
                    <label>Ambiente de Transmisión</label>
                    <select name="fe_env" class="form-control">
                        <option value="pruebas" {{ ($pos_electronico['env'] ?? '') == 'pruebas' ? 'selected' : '' }}>🧪 Pruebas (Sandbox)</option>
                        <option value="produccion" {{ ($pos_electronico['env'] ?? '') == 'produccion' ? 'selected' : '' }}>🚀 Producción (Real)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN RESOLUCIONES -->
    <div id="section-resoluciones" class="billing-content-section">
        <div class="config-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 700; color: #111827;">Resoluciones DIAN Vigentes</h3>
                <button type="button" class="btn btn-primary btn-sm" onclick="alert('Cargar nueva resolución...')">Cargar Resolución</button>
            </div>

            @forelse($resoluciones as $res)
            <div style="padding: 15px; background: white; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <span style="font-size: 10px; font-weight: 700; color: #1a73e8; text-transform: uppercase;">{{ $res['prefijo'] }} {{ $res['cons_inicial'] }}-{{ $res['cons_final'] }}</span>
                        <h4 style="margin: 4px 0; font-size: 14px; font-weight: 700;">Res. {{ $res['numero_autorizacion'] }}</h4>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">Válida hasta: {{ $res['hasta'] }}</p>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 18px; font-weight: 800; color: #111827;">{{ $res['nuevo_consecutivo'] ?? $res['cons_inicial'] }}</span>
                        <div style="font-size: 9px; color: #9ca3af;">Consecutivo Actual</div>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 40px; background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; color: #6b7280;">
                No hay resoluciones activas configuradas.
            </div>
            @endforelse
        </div>
    </div>

    <div class="btn-group" style="margin-top: 30px;">
        <button type="submit" class="btn btn-primary" style="padding: 12px 35px;">Guardar Configuración de Caja</button>
        <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: transparent; color: #6b7280;">Descartar Cambios</a>
    </div>
</form>

<script>
function showBillingTab(tabId) {
    document.querySelectorAll('.billing-tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.billing-content-section').forEach(sec => sec.classList.remove('active'));
    
    event.currentTarget.classList.add('active');
    document.getElementById('section-' + tabId).classList.add('active');
}

function selectFE(provider) {
    document.getElementById('fe_provider').value = provider;
    document.querySelectorAll('.fe-provider-card').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');
}
</script>
@endsection
