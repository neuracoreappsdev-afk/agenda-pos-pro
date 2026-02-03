@extends('admin.configuration._layout')

@section('config_title', 'Impuestos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Impuestos</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Nombre del Impuesto Principal</label>
                <input type="text" name="tax_name" value="{{ $settings['tax_name'] ?? 'IVA' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Ej: IVA, IGV, VAT">
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Tarifa General (%)</label>
                <div class="input-group">
                    <input type="number" step="0.01" name="tax_rate" value="{{ $settings['tax_rate'] ?? '19' }}" class="form-control" placeholder="19">
                    <span class="input-group-addon">%</span>
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="tax_included" value="1" {{ ($settings['tax_included'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Los precios ingresados ya incluyen el impuesto</span>
                </label>
            </div>

            <div class="form-group" style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 15px;">Retenciones e Impuestos Adicionales</h4>
                
                <div class="form-group">
                     <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="retentions_enabled" value="1" {{ ($settings['retentions_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <span style="font-size: 14px; color: #4b5563;">Habilitar Retenciones en la Fuente</span>
                    </label>
                </div>
                
                <div class="form-group" style="margin-top: 10px;">
                     <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="consumo_tax_enabled" value="1" {{ ($settings['consumo_tax_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <span style="font-size: 14px; color: #4b5563;">Habilitar Impoconsumo (Impuesto al Consumo)</span>
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