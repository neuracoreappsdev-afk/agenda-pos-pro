@extends('admin.configuration._layout')

@section('config_title', 'Bloqueo de Precios de Servicios')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Seguridad en Precios de Servicios</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Define las reglas para la modificación de precios de servicios y aplicaciones de descuentos.</p>
            
            <div style="margin-bottom: 30px; padding: 20px; background: #eff6ff; border-radius: 12px; display: flex; align-items: flex-start; gap: 15px; border: 1px solid #dbeafe;">
                <div style="font-size: 24px;">🔧</div>
                <div>
                    <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 700; color: #1e40af;">Control de Facturación</h4>
                    <p style="margin: 0; color: #1e3a8a; font-size: 13px; line-height: 1.5;">
                        A diferencia de los productos, los servicios suelen tener precios más volátiles. Estas opciones aseguran que los cambios sean regulados.
                    </p>
                </div>
            </div>

            <div style="display: grid; gap: 20px;">
                <!-- Bloqueo Total -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight: 700; color: #111827; font-size: 14px;">Bloquear Edición de Precio Unitario</div>
                        <div style="font-size: 12px; color: #6b7280;">Force el uso del precio base definido en el catálogo de servicios.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="lock_service_prices" value="0">
                        <input type="checkbox" name="lock_service_prices" value="1" {{ ($settings['lock_service_prices'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>

                <!-- Descuento Máximo -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                    <label style="font-weight: 700; color: #111827; display: block; margin-bottom: 10px; font-size: 14px;">Porcentaje Máximo de Descuento en Servicios (%)</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <input type="number" name="max_service_discount" value="{{ $settings['max_service_discount'] ?? '0' }}" class="form-control" style="max-width: 120px;" min="0" max="100">
                        <span style="color: #6b7280; font-size: 13px;">Límite para el personal de recepción o estilistas en la caja.</span>
                    </div>
                </div>

                <!-- Anulación por Admin -->
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f9fafb; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight: 700; color: #111827; font-size: 14px;">Autorización Administrativa Requerida</div>
                        <div style="font-size: 12px; color: #6b7280;">Habilitar un "Override" si un supervisor ingresa sus credenciales.</div>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="allow_manager_service_override" value="0">
                        <input type="checkbox" name="allow_manager_service_override" value="1" {{ ($settings['allow_manager_service_override'] ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Políticas de Servicio</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>
@endsection