@extends('admin.configuration._layout')

@section('config_title', 'Líneas de Servicios')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Líneas de Servicios</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Líneas de Servicios (Una por línea)</label>
                <textarea name="service_lines" rows="8" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Corte y Peinado
Coloración
Tratamientos Capilares
Manicure y Pedicure
Depilación">{{ $settings['service_lines'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Agrupe sus servicios en líneas para facilitar la búsqueda y reporte.</small>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Color Distintivo por Defecto</label>
                <input type="color" name="default_service_color" value="{{ $settings['default_service_color'] ?? '#3b82f6' }}" class="form-control" style="height: 40px; width: 60px; padding: 0; border: none;">
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection