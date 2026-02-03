@extends('admin.configuration._layout')

@section('config_title', 'Actualización Masiva de Costos')

@section('config_content')
<form action="{{ url('admin/configuration/update-bulk-costs') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Actualización Masiva de Costos</h3>
            
            <div style="margin-bottom: 20px; padding: 15px; background: #fff7ed; border-left: 4px solid #f97316; border-radius: 4px;">
                <p style="margin: 0; color: #9a3412; font-size: 14px;">
                    ⚠️ Esta acción actualizará los costos de TODOS los productos o de la categoría seleccionada.
                </p>
            </div>

            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Aplicar a Categoría</label>
                <select name="cost_update_category" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="all">Todas las Categorías</option>
                    {{-- Aquí se deberían cargar las categorías dinámicamente, por ahora placeholder --}}
                </select>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Porcentaje de Aumento (%)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="cost_increase_percent" value="0" class="form-control" placeholder="0">
                    <span class="input-group-addon">%</span>
                </div>
                <small style="color: #6b7280; font-size: 12px;">Ingrese un valor negativo para disminuir el costo.</small>
            </div>
            
            <div class="form-group" style="margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="confirm_cost_update" value="1">
                    <span style="font-size: 14px; font-weight: 600; color: #dc2626;">Confirmo actualización masiva</span>
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