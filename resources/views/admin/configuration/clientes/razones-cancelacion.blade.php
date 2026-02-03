@extends('admin.configuration._layout')

@section('config_title', 'Razones de Cancelación')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Razones de Cancelación</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Razones de Cancelación (Una por línea)</label>
                <textarea name="cancellation_reasons" rows="8" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Cambio de planes
Enfermedad
Falta de tiempo
No pude llegar
Otro motivo">{{ $settings['cancellation_reasons'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Estas opciones aparecerán cuando un cliente o administrador cancele una cita.</small>
            </div>
            
             <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="require_cancellation_reason" value="1" {{ ($settings['require_cancellation_reason'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Hacer obligatorio seleccionar una razón</span>
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