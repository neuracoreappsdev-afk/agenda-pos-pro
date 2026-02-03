@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Productos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Tipos de Productos</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Categorías de Productos (Una por línea)</label>
                <textarea name="product_categories" rows="6" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Cuidado Facial
Cuidado Capilar
Corporal
Maquillaje
Accesorios">{{ $settings['product_categories'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Estas categorías aparecerán al crear nuevos productos.</small>
            </div>
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px;">Alertas de Inventario</h4>
                
                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Nivel de Stock Bajo (Global)</label>
                    <input type="number" name="stock_warning_limit" value="{{ $settings['stock_warning_limit'] ?? '5' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <small style="color: #6b7280; font-size: 12px;">Se mostrará una alerta cuando el stock de un producto sea igual o menor a este número.</small>
                </div>
                
                <div class="form-group" style="margin-top: 15px;">
                     <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="prevent_stock_negative" value="1" {{ ($settings['prevent_stock_negative'] ?? '') == '1' ? 'checked' : '' }}>
                        <span style="font-size: 14px; color: #4b5563;">Impedir ventas sin stock (Inventario negativo)</span>
                    </label>
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