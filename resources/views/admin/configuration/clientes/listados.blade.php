@extends('admin.configuration._layout')

@section('config_title', 'Configuración de Listados')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Configuración de Listados</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Clientes por Página</label>
                <select name="clients_per_page" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="10" {{ ($settings['clients_per_page'] ?? '') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ ($settings['clients_per_page'] ?? '') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ ($settings['clients_per_page'] ?? '') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($settings['clients_per_page'] ?? '') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Orden Predeterminado</label>
                <select name="clients_default_sort" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="created_at_desc" {{ ($settings['clients_default_sort'] ?? '') == 'created_at_desc' ? 'selected' : '' }}>Más recientes primero</option>
                    <option value="name_asc" {{ ($settings['clients_default_sort'] ?? '') == 'name_asc' ? 'selected' : '' }}>Alfabético (A-Z)</option>
                    <option value="last_visit_desc" {{ ($settings['clients_default_sort'] ?? '') == 'last_visit_desc' ? 'selected' : '' }}>Última Visita (Reciente)</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="show_inactive_clients" value="1" {{ ($settings['show_inactive_clients'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Mostrar clientes inactivos en el listado principal</span>
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