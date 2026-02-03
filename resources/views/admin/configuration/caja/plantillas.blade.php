@extends('admin.configuration._layout')

@section('config_title', 'Plantillas de Facturación')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Plantillas de Facturación</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Estilo de Plantilla</label>
                    <select name="invoice_template_style" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                        <option value="modern" {{ ($settings['invoice_template_style'] ?? '') == 'modern' ? 'selected' : '' }}>Moderna (Sin bordes)</option>
                        <option value="classic" {{ ($settings['invoice_template_style'] ?? '') == 'classic' ? 'selected' : '' }}>Clásica (Con líneas)</option>
                        <option value="thermal" {{ ($settings['invoice_template_style'] ?? '') == 'thermal' ? 'selected' : '' }}>Térmica (POS)</option>
                    </select>
                </div>
                
                 <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Color Principal</label>
                    <input type="color" name="invoice_primary_color" value="{{ $settings['invoice_primary_color'] ?? '#000000' }}" class="form-control" style="height: 40px; width: 100%; padding: 0; border: none;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Encabezado Personalizado</label>
                <textarea name="invoice_custom_header" rows="3" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Información adicional para la cabecera...">{{ $settings['invoice_custom_header'] ?? '' }}</textarea>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="show_logo_invoice" value="1" {{ ($settings['show_logo_invoice'] ?? '1') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Mostrar logotipo del negocio en la factura</span>
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