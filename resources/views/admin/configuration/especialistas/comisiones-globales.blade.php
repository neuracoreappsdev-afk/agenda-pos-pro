@extends('admin.configuration._layout')

@section('config_title', 'Comisiones Globales')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Comisiones Globales</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Comisión Base por Servicios (%)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="global_service_commission" value="{{ $settings['global_service_commission'] ?? '0' }}" class="form-control" placeholder="0">
                    <span class="input-group-addon">%</span>
                </div>
                <small style="color: #6b7280; font-size: 12px;">Comisión por defecto aplicada a todos los especialistas si no tienen una específica.</small>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Comisión Base por Productos (%)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="global_product_commission" value="{{ $settings['global_product_commission'] ?? '0' }}" class="form-control" placeholder="0">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="commission_on_tax" value="1" {{ ($settings['commission_on_tax'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Calcular comisión sobre el valor CON impuestos (IVA incluido)</span>
                </label>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection