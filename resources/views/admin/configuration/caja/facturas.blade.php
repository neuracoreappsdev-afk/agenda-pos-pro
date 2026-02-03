@extends('admin.configuration._layout')

@section('config_title', 'Facturas y Recibos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Facturas y Recibos</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Prefijo de Factura</label>
                    <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'FAC-' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="FAC-">
                </div>

                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Siguiente Número</label>
                    <input type="number" name="next_invoice_number" value="{{ $settings['next_invoice_number'] ?? '1' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                </div>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Resolución de Facturación / Nota Legal</label>
                <input type="text" name="invoice_resolution" value="{{ $settings['invoice_resolution'] ?? '' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Res. DIAN No. 123456789 de 2024">
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Texto al Pie de Factura</label>
                <textarea name="invoice_footer_text" rows="3" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="Gracias por su compra.">{{ $settings['invoice_footer_text'] ?? '' }}</textarea>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="invoice_auto_send" value="1" {{ ($settings['invoice_auto_send'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Enviar factura automáticamente al correo del cliente</span>
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