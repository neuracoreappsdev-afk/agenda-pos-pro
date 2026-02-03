@extends('admin.configuration._layout')

@section('config_title', 'POS y POS Electrónico')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">POS y POS Electrónico</h3>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Habilitar Sistema POS</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="switch">
                        <input type="checkbox" name="enable_pos" value="1" {{ ($settings['enable_pos'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span style="color: #4b5563; font-size: 14px;">Activar interfaz de Punto de Venta</span>
                </div>
            </div>

            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Caja / Drawer por Defecto</label>
                <select name="default_pos_drawer" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="main" {{ ($settings['default_pos_drawer'] ?? '') == 'main' ? 'selected' : '' }}>Caja Principal</option>
                    <option value="reception" {{ ($settings['default_pos_drawer'] ?? '') == 'reception' ? 'selected' : '' }}>Recepción</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px;">Impresión de Recibos (Tirilla)</h4>
                
                <div class="form-group">
                     <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="print_receipt_automatically" value="1" {{ ($settings['print_receipt_automatically'] ?? '') == '1' ? 'checked' : '' }}>
                        <span style="font-size: 14px; color: #4b5563;">Imprimir automáticamente al finalizar venta</span>
                    </label>
                </div>
                
                <div class="form-group" style="margin-top: 10px;">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Ancho de Papel</label>
                    <select name="receipt_width" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                        <option value="58mm" {{ ($settings['receipt_width'] ?? '') == '58mm' ? 'selected' : '' }}>58mm</option>
                        <option value="80mm" {{ ($settings['receipt_width'] ?? '') == '80mm' ? 'selected' : '' }}>80mm</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection