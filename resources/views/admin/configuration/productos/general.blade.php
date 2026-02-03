@extends('admin.configuration._layout')

@section('config_title', 'Configuración General de Productos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Parámetros Globales</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Configura el comportamiento general de tu catálogo de productos e inventario.</p>

            <!-- Identificación SKU -->
            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #111827;">Codificación Automática (SKU)</h4>
                        <p style="margin: 0; font-size: 12px; color: #6b7280;">Generar códigos únicos al crear nuevos productos.</p>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="auto_generate_sku" value="0">
                        <input type="checkbox" name="auto_generate_sku" value="1" {{ ($settings['auto_generate_sku'] ?? true) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 13px; font-weight: 600; color: #374151;">Prefijo Global para SKU</label>
                    <input type="text" name="sku_prefix" value="{{ $settings['sku_prefix'] ?? 'PROD-' }}" class="form-control" placeholder="PROD-" style="max-width: 200px;">
                </div>
            </div>

            <!-- Escáner -->
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 20px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6; margin-bottom: 20px;">
                <div>
                    <div style="font-weight: 700; color: #111827; font-size: 14px;">Modo Escáner de Barras</div>
                    <div style="font-size: 12px; color: #6b7280;">Optimiza la búsqueda en el POS para lectores de códigos de barras.</div>
                </div>
                <label class="switch">
                    <input type="hidden" name="enable_barcode_scanner" value="0">
                    <input type="checkbox" name="enable_barcode_scanner" value="1" {{ ($settings['enable_barcode_scanner'] ?? false) ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>
            </div>

            <!-- Inventario y Stock -->
            <div style="background: #eff6ff; border: 1px solid #dbeafe; border-radius: 12px; padding: 25px;">
                <h4 style="margin: 0 0 20px 0; font-size: 15px; font-weight: 700; color: #1e40af; display: flex; align-items: center; gap: 8px;">
                    📦 Control de Inventario
                </h4>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-size: 13px; font-weight: 600; color: #1e40af;">Nivel de Stock Bajo (Alerta)</label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="number" name="stock_warning_limit" value="{{ $settings['stock_warning_limit'] ?? '5' }}" class="form-control" style="max-width: 100px;">
                        <span style="font-size: 12px; color: #60a5fa;">Unidades restantes para mostrar advertencia.</span>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 700; color: #1e40af; font-size: 14px;">Prevenir Stock Negativo</div>
                        <div style="font-size: 12px; color: #60a5fa;">Bloquear la venta si no hay existencias físicas.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="prevent_stock_negative" value="0">
                        <input type="checkbox" name="prevent_stock_negative" value="1" {{ ($settings['prevent_stock_negative'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
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
@endsection