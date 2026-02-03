@extends('admin.configuration._layout')

@section('config_title', 'Zonas de Clientes')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Zonas de Clientes</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Listado de Zonas (Una por línea)</label>
                <textarea name="client_zones" rows="8" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Norte
Sur
Centro
Occidente
Oriente">{{ $settings['client_zones'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Utilice estas zonas para segmentar su base de datos de clientes.</small>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Zona por Defecto</label>
                <input type="text" name="default_client_zone" value="{{ $settings['default_client_zone'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Centro">
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection