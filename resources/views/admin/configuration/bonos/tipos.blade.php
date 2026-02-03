@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Bonos')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Tipos de Bonos</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Prefijo del Código de Nube</label>
                <input type="text" name="voucher_code_prefix" value="{{ $settings['voucher_code_prefix'] ?? 'BONO-' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;" placeholder="BONO-">
                <small style="color: #6b7280; font-size: 12px;">Prefijo para los códigos generados automáticamente.</small>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Validez por Defecto (Días)</label>
                <input type="number" name="voucher_validity_days" value="{{ $settings['voucher_validity_days'] ?? '365' }}" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div class="form-group" style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 15px;">
                <label style="display:block; font-weight:600; margin-bottom:10px;">Políticas de Uso</label>
                
                <div style="display: grid; grid-template-columns: 1fr; gap: 10px;">
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="voucher_partial_use" value="1" {{ ($settings['voucher_partial_use'] ?? '') == '1' ? 'checked' : '' }}>
                            <span style="font-size: 14px; color: #4b5563;">Permitir uso parcial (Saldo a favor)</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="voucher_transferable" value="1" {{ ($settings['voucher_transferable'] ?? '') == '1' ? 'checked' : '' }}>
                            <span style="font-size: 14px; color: #4b5563;">Permitir transferir bonos a otros clientes</span>
                        </label>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="voucher_online_payment" value="1" {{ ($settings['voucher_online_payment'] ?? '') == '1' ? 'checked' : '' }}>
                            <span style="font-size: 14px; color: #4b5563;">Permitir pago con bonos en reserva online</span>
                        </label>
                    </div>
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