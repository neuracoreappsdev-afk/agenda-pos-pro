@extends('admin.configuration._layout')

@section('config_title', 'Configuración de POS y POS Electrónico')

@section('config_content')

<style>
    .config-section {
        background: white;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 20px 0;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
        font-size: 14px;
    }

    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        background: white;
    }

    .form-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .radio-group {
        display: flex;
        gap: 24px;
        margin-bottom: 16px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .radio-option input[type="radio"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .radio-option label {
        margin: 0;
        cursor: pointer;
        font-weight: 400;
        color: #374151;
    }

    .info-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        padding: 16px;
        margin-top: 16px;
    }

    .info-box ul {
        margin: 0;
        padding-left: 20px;
    }

    .info-box li {
        color: #1e40af;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .info-box li:last-child {
        margin-bottom: 0;
    }

    .info-box strong {
        font-weight: 600;
    }

    .btn-save {
        background: #3b82f6;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #2563eb;
    }
</style>

@if(session('success'))
<div id="flash-message" style="padding: 12px 16px; background: #d1fae5; border: 1px solid #10b981; border-radius: 6px; margin-bottom: 20px; color: #065f46;">
    {{ session('success') }}
</div>
@endif

<form action="{{ url('admin/configuration/pos-electronico/save') }}" method="POST">
    {{ csrf_field() }}

    <div class="config-section">
        <div class="form-group">
            <label>Sede</label>
            <select name="sede" id="sedeSelect" required>
                <option value="">Seleccione una sede</option>
                @if(!empty($sedes))
                    @foreach($sedes as $sede)
                    <option value="{{ $sede['id'] }}" {{ (isset($configuracion['sede']) && $configuracion['sede'] == $sede['id']) ? 'selected' : '' }}>
                        {{ $sede['nombre'] }}
                    </option>
                    @endforeach
                @else
                    <option value="principal">Holguines Trade Center</option>
                @endif
            </select>
        </div>
    </div>

    <div class="config-section">
        <h3 class="section-title">Tipo de Facturación</h3>
        
        <div class="form-group">
            <label>Tipo de Facturación</label>
            <div class="radio-group">
                <div class="radio-option">
                    <input type="radio" id="tipo_recibos" name="tipo_facturacion" value="recibos" 
                           {{ (!isset($configuracion['tipo_facturacion']) || $configuracion['tipo_facturacion'] == 'recibos') ? 'checked' : '' }}>
                    <label for="tipo_recibos">Recibos</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="tipo_facture" name="tipo_facturacion" value="facture" 
                           {{ (isset($configuracion['tipo_facturacion']) && $configuracion['tipo_facturacion'] == 'facture') ? 'checked' : '' }}>
                    <label for="tipo_facture">Facture</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="tipo_mixto" name="tipo_facturacion" value="mixto" 
                           {{ (isset($configuracion['tipo_facturacion']) && $configuracion['tipo_facturacion'] == 'mixto') ? 'checked' : '' }}>
                    <label for="tipo_mixto">Mixto</label>
                </div>
                <div class="radio-option">
                    <input type="radio" id="tipo_mandato" name="tipo_facturacion" value="mandato" 
                           {{ (isset($configuracion['tipo_facturacion']) && $configuracion['tipo_facturacion'] == 'mandato') ? 'checked' : '' }}>
                    <label for="tipo_mandato">Mandato</label>
                </div>
            </div>

            <div class="info-box">
                <ul>
                    <li>La venta de <strong>Productos y Servicios</strong> generan <strong>Recibos (Ticket)</strong></li>
                    <li>El tipo de facturación <strong>Recibos No</strong> genera <strong>Impuestos</strong></li>
                    <li>La venta de <strong>Bonos/Planes</strong> generan <strong>Recibos de Bono</strong> (No genera Impuestos)</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="config-section">
        <h3 class="section-title">Configuración de Facturación Electrónica</h3>
        
        <div class="form-group">
            <label>Proveedor de Facturación Electrónica</label>
            <select name="proveedor_fe" id="proveedorFE">
                <option value="">No usar facturación electrónica</option>
                <option value="facture" {{ (isset($configuracion['proveedor_fe']) && $configuracion['proveedor_fe'] == 'facture') ? 'selected' : '' }}>Facture</option>
                <option value="alegra" {{ (isset($configuracion['proveedor_fe']) && $configuracion['proveedor_fe'] == 'alegra') ? 'selected' : '' }}>Alegra</option>
                <option value="siigo" {{ (isset($configuracion['proveedor_fe']) && $configuracion['proveedor_fe'] == 'siigo') ? 'selected' : '' }}>Siigo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ambiente</label>
            <select name="ambiente_fe">
                <option value="produccion" {{ (isset($configuracion['ambiente_fe']) && $configuracion['ambiente_fe'] == 'produccion') ? 'selected' : '' }}>Producción</option>
                <option value="pruebas" {{ (!isset($configuracion['ambiente_fe']) || $configuracion['ambiente_fe'] == 'pruebas') ? 'selected' : '' }}>Pruebas</option>
            </select>
        </div>

        <div class="form-group">
            <label>Prefijo de Facturación</label>
            <input type="text" name="prefijo_facturacion" class="form-control" 
                   value="{{ $configuracion['prefijo_facturacion'] ?? '' }}" 
                   placeholder="Ej: SETP" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div class="form-group">
            <label>Número Inicial de Factura</label>
            <input type="number" name="numero_inicial" class="form-control" 
                   value="{{ $configuracion['numero_inicial'] ?? '1' }}" 
                   placeholder="1" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div class="form-group">
            <label>Resolución DIAN</label>
            <input type="text" name="resolucion_dian" class="form-control" 
                   value="{{ $configuracion['resolucion_dian'] ?? '' }}" 
                   placeholder="Número de resolución" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>

        <div class="form-group">
            <label>Fecha de Resolución</label>
            <input type="date" name="fecha_resolucion" class="form-control" 
                   value="{{ $configuracion['fecha_resolucion'] ?? '' }}" 
                   style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px;">
        </div>
    </div>

    <div style="margin-top: 24px;">
        <button type="submit" class="btn-save">Guardar</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(function() {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(function() { flash.remove(); }, 500);
        }, 3000);
    }
});
</script>

@endsection
