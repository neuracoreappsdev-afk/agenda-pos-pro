@extends('admin.configuration._layout')

@section('config_title', 'Configuración General de Especialistas')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Configuración General de Especialistas</h3>
            
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="show_specialist_photo" value="0">
                    <input type="checkbox" name="show_specialist_photo" value="1" {{ ($settings['show_specialist_photo'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; font-weight: 600; color: #374151;">Mostrar foto del especialista en reserva online</span>
                </label>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="allow_select_specialist" value="0">
                    <input type="checkbox" name="allow_select_specialist" value="1" {{ ($settings['allow_select_specialist'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; font-weight: 600; color: #374151;">Permitir al cliente elegir especialista preferido</span>
                </label>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="random_specialist_allocation" value="0">
                    <input type="checkbox" name="random_specialist_allocation" value="1" {{ ($settings['random_specialist_allocation'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; font-weight: 600; color: #374151;">Asignación aleatoria si no se selecciona especialista</span>
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