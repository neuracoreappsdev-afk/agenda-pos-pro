@extends('admin.configuration._layout')

@section('config_title', 'Bloqueo de Precios')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Seguridad en Precios</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Controla quién puede modificar los precios y aplicar descuentos en el punto de venta.</p>
            
            <div style="margin-bottom: 30px; padding: 20px; background: #fff1f2; border-radius: 12px; display: flex; align-items: flex-start; gap: 15px; border: 1px solid #fee2e2;">
                <div style="font-size: 24px;">🛡️</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 700; color: #9f1239;">Políticas de Restricción</h4>
                    <p style="margin: 0; color: #914151; font-size: 13px; line-height: 1.5;">
                        Estas configuraciones evitan que el personal de caja realice cambios no autorizados en los precios de los productos durante la facturación.
                    </p>
                </div>
            </div>

            <div style="display: grid; gap: 20px;">
                <!-- Bloqueo Total -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight: 700; color: #111827; font-size: 14px;">Bloqueo de Edición Manual</div>
                        <div style="font-size: 12px; color: #6b7280;">Impide cambiar el precio unitario en el POS.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="lock_product_prices" value="0">
                        <input type="checkbox" name="lock_product_prices" value="1" {{ ($settings['lock_product_prices'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Descuento Máximo -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                    <label style="font-weight: 700; color: #111827; display: block; margin-bottom: 10px; font-size: 14px;">Descuento Máximo Permitido (%)</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <input type="number" name="max_product_discount" value="{{ $settings['max_product_discount'] ?? '0' }}" class="form-control" style="max-width: 120px;" min="0" max="100">
                        <span style="color: #6b7280; font-size: 13px;">El cajero no podrá aplicar descuentos mayores a este porcentaje.</span>
                    </div>
                </div>

                <!-- Anulación por Admin -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight: 700; color: #111827; font-size: 14px;">Desbloqueo Administrativo</div>
                        <div style="font-size: 12px; color: #6b7280;">Permitir cambios si un administrador autoriza con su clave.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="allow_manager_product_override" value="0">
                        <input type="checkbox" name="allow_manager_product_override" value="1" {{ ($settings['allow_manager_product_override'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Políticas</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>
@endsection