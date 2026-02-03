@extends('admin.configuration._layout')

@section('config_title', 'Actualizar Comisiones Facturadas')

@section('config_content')
<form action="{{ url('admin/configuration/recalc-commissions') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Actualizar Comisiones Facturadas</h3>
            
            <div style="margin-bottom: 20px; padding: 15px; background: #fff7ed; border-left: 4px solid #f97316; border-radius: 4px;">
                <p style="margin: 0; color: #9a3412; font-size: 14px;">
                    ⚠️ Esta herramienta recalculará las comisiones de facturas ya existentes basadas en la configuración actual del especialista. Use con precaución.
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Fecha Inicial</label>
                    <input type="date" name="recalc_start_date" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                </div>

                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Fecha Final</label>
                    <input type="date" name="recalc_end_date" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="confirm_recalc" value="1">
                    <span style="font-size: 14px; font-weight: 600; color: #dc2626;">Confirmo que deseo recalcular comisiones para este periodo</span>
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