@extends('admin.configuration._layout')

@section('config_title', 'Programa de Fidelización')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Programa de Fidelización</h3>
            
            {{-- Estado del Programa --}}
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Estado del Programa</label>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <label class="switch">
                        <input type="checkbox" name="loyalty_enabled" value="1" {{ ($settings['loyalty_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <span style="color: #4b5563; font-size: 14px;">Habilitar programa de puntos</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Gana 1 punto por cada:</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" name="loyalty_earn_ratio" value="{{ $settings['loyalty_earn_ratio'] ?? '1000' }}" class="form-control" placeholder="1000">
                    </div>
                    <small style="color: #6b7280; font-size: 12px;">Ej: Por cada $1000 gastados, el cliente gana 1 punto.</small>
                </div>

                <div class="form-group">
                    <label style="display:block; font-weight:600; margin-bottom:5px;">Valor de Redención (1 punto = ):</label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="number" name="loyalty_redeem_value" value="{{ $settings['loyalty_redeem_value'] ?? '10' }}" class="form-control" placeholder="10">
                    </div>
                    <small style="color: #6b7280; font-size: 12px;">Ej: 1 punto equivale a $10 pesos de descuento.</small>
                </div>
            </div>
            
            <div class="form-group" style="margin-top:20px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Vencimiento de Puntos</label>
                <select name="loyalty_expiration" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                    <option value="never" {{ ($settings['loyalty_expiration'] ?? '') == 'never' ? 'selected' : '' }}>Nunca vencen</option>
                    <option value="3_months" {{ ($settings['loyalty_expiration'] ?? '') == '3_months' ? 'selected' : '' }}>3 Meses</option>
                    <option value="6_months" {{ ($settings['loyalty_expiration'] ?? '') == '6_months' ? 'selected' : '' }}>6 Meses</option>
                    <option value="1_year" {{ ($settings['loyalty_expiration'] ?? '') == '1_year' ? 'selected' : '' }}>1 Año</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 20px;">
                <label style="display:block; font-weight:600; margin-bottom:10px;">Configuración de Niveles VIP</label>
                <div style="padding: 15px; border: 1px solid #e5e7eb; border-radius: 6px; background: #f9fafb;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                        <input type="checkbox" name="loyalty_tiers_enabled" value="1" {{ ($settings['loyalty_tiers_enabled'] ?? '') == '1' ? 'checked' : '' }}>
                        <span style="font-weight: 500;">Habilitar Niveles VIP Automáticos</span>
                    </div>
                    
                    <p style="font-size: 13px; color: #6b7280; margin-bottom: 0;">
                        El sistema asignará automáticamente niveles (Bronce, Plata, Oro) basado en el gasto anual del cliente.
                    </p>
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