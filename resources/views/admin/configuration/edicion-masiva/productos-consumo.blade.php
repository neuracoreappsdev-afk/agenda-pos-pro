@extends('admin.configuration._layout')

@section('config_title', 'Productos de Consumo')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Productos de Consumo</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Categoría de Insumos Internos</label>
                <select name="internal_consumption_category" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="" disabled selected>Seleccione una categoría...</option>
                    <option value="insumos">Insumos de Cabina</option>
                    <option value="cafeteria">Cafetería</option>
                    <option value="aseo">Aseo y Mantenimiento</option>
                </select>
                <small style="color: #6b7280; font-size: 12px;">Los productos en esta categoría no aparecerán en el catálogo de venta al público.</small>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="track_internal_usage" value="1" {{ ($settings['track_internal_usage'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Registrar uso interno como "Gasto Operativo" automáticamente</span>
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