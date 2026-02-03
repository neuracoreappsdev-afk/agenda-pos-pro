@extends('admin.configuration._layout')

@section('config_title', 'Configuración de Comisiones')

@section('config_content')

@if(session('success'))
<div id="flash-message" style="background: #10b981; color: white; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<style>
.commission-section {
    background: #f9fafb;
    padding: 16px 20px;
    margin-bottom: 16px;
    border-radius: 6px;
}

.commission-section h4 {
    margin: 0 0 16px 0;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.option-group {
    margin-bottom: 20px;
}

.option-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #374151;
    font-size: 14px;
}

.radio-group,
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-left: 8px;
}

.radio-option,
.checkbox-option {
   display: flex;
    align-items: center;
    gap: 10px;
}

.radio-option input[type="radio"],
.checkbox-option input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.radio-option label,
.checkbox-option label {
    margin: 0;
    cursor: pointer;
    font-weight: 400;
    color: #6b7280;
}

.info-icon {
    color: #3b82f6;
    cursor: help;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #3b82f6;
}

input:checked + .toggle-slider:before {
    transform: translateX(20px);
}
</style>

<form method="POST" action="{{ url('admin/configuration/comisiones/save') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    
    <div class="config-card" style="border: none; box-shadow: none; background: transparent;">
        <!-- Header with Title -->
        <div style="margin-bottom: 30px;">
            <h2 style="font-size: 24px; font-weight: 800; color: #111827; margin-bottom: 10px;">Configuración de Comisiones</h2>
            <p style="color: #6b7280; font-size: 14px;">Define las reglas de negocio para el cálculo de participaciones.</p>
        </div>

        <!-- Sede Selector with Style -->
        <div style="background: white; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div style="width: 45px; height: 45px; background: #eff6ff; color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🏢</div>
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase;">Seleccionar Sede</label>
                    <select class="form-control" style="border: none; padding: 0; font-size: 16px; font-weight: 600; color: #1e293b; background: transparent; cursor: pointer; min-width: 250px;">
                        @foreach($sedes as $sede)
                            <option value="{{ $sede['id'] }}">{{ $sede['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                 <p style="margin: 0; color: #6b7280; font-size: 14px;">
                    Estado: <span class="status-badge status-active">● Activadas</span>
                </p>
            </div>
        </div>

        <!-- COMISIONES TAB -->
        <div class="tab-content active">
            
            <!-- Descuentos de facturación -->
            <div class="commission-section">
                <h4>Descuentos de facturación</h4>
                <div class="checkbox-option">
                    <input type="checkbox" name="calcular_despues_descuento" id="calcular_despues_descuento" value="1" {{ isset($config['calcular_despues_descuento']) && $config['calcular_despues_descuento'] ? 'checked' : '' }}>
                    <label for="calcular_despues_descuento">Calcular Participaciones por precio de venta del artículo después del descuento</label>
                </div>
            </div>

            <!-- Reportes -->
            <div class="commission-section">
                <h4>Reportes</h4>
                
                <div class="option-group">
                    <div class="checkbox-option">
                        <input type="checkbox" name="mostrar_totales_cero" id="mostrar_totales_cero" value="1" {{ isset($config['mostrar_totales_cero']) && $config['mostrar_totales_cero'] ? 'checked' : '' }}>
                        <label for="mostrar_totales_cero">Mostrar totales a Pagar de participaciones en Cero</label>
                    </div>
                </div>

                <div class="option-group">
                    <label>Tipo de Plantilla de Pago</label>
                    <select name="tipo_plantilla_pago" class="form-control" style="max-width: 300px;">
                        <option value="defecto" {{ isset($config['tipo_plantilla_pago']) && $config['tipo_plantilla_pago'] == 'defecto' ? 'selected' : '' }}>Por defecto</option>
                        <option value="detallada" {{ isset($config['tipo_plantilla_pago']) && $config['tipo_plantilla_pago'] == 'detallada' ? 'selected' : '' }}>Detallada</option>
                        <option value="resumida" {{ isset($config['tipo_plantilla_pago']) && $config['tipo_plantilla_pago'] == 'resumida' ? 'selected' : '' }}>Resumida</option>
                    </select>
                </div>
            </div>

            <!-- Colaboradores -->
            <div class="commission-section">
                <h4>Colaboradores</h4>
                
                <div class="option-group">
                    <label>Descontar valor de Colaborador</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="descontar_colaborador" id="colaborador_no" value="no_descontar" {{ !isset($config['descontar_colaborador']) || $config['descontar_colaborador'] == 'no_descontar' ? 'checked' : '' }}>
                            <label for="colaborador_no">No Descontar</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="descontar_colaborador" id="colaborador_precio" value="precio_total" {{ isset($config['descontar_colaborador']) && $config['descontar_colaborador'] == 'precio_total' ? 'checked' : '' }}>
                            <label for="colaborador_precio">Precio Total de Venta</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="descontar_colaborador" id="colaborador_participacion" value="participacion" {{ isset($config['descontar_colaborador']) && $config['descontar_colaborador'] == 'participacion' ? 'checked' : '' }}>
                            <label for="colaborador_participacion">Participación Especialista</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <label>Calcular comisión del colaborador desde</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="calcular_colaborador_desde" id="col_precio" value="precio_total" {{ !isset($config['calcular_colaborador_desde']) || $config['calcular_colaborador_desde'] == 'precio_total' ? 'checked' : '' }}>
                            <label for="col_precio">Precio Total de Venta</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="calcular_colaborador_desde" id="col_participacion" value="participacion" {{ isset($config['calcular_colaborador_desde']) && $config['calcular_colaborador_desde'] == 'participacion' ? 'checked' : '' }}>
                            <label for="col_participacion">Participación Especialista</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="calcular_colaborador_desde" id="col_despues" value="despues_productos" {{ isset($config['calcular_colaborador_desde']) && $config['calcular_colaborador_desde'] == 'despues_productos' ? 'checked' : '' }}>
                            <label for="col_despues">Después de productos de consumo en el total de venta</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Descuentos administrativos -->
            <div class="commission-section">
                <h4>Descuentos administrativos</h4>
                
                <div class="option-group">
                    <label>Aplicar descuentos Administrativos desde</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="aplicar_desc_admin_desde" id="desc_precio" value="precio_total" {{ !isset($config['aplicar_desc_admin_desde']) || $config['aplicar_desc_admin_desde'] == 'precio_total' ? 'checked' : '' }}>
                            <label for="desc_precio">Precio Total de Venta</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="aplicar_desc_admin_desde" id="desc_participacion" value="participacion" {{ isset($config['aplicar_desc_admin_desde']) && $config['aplicar_desc_admin_desde'] == 'participacion' ? 'checked' : '' }}>
                            <label for="desc_participacion">Participación Especialista</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <label>Descontar descuento administrativo por cantidad del servicio 
                        <span class="info-icon" title="Información adicional">ℹ</span>
                    </label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_cantidad" id="desc_una_vez" value="una_vez" {{ !isset($config['desc_admin_cantidad']) || $config['desc_admin_cantidad'] == 'una_vez' ? 'checked' : '' }}>
                            <label for="desc_una_vez">Descontar una sola vez</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_cantidad" id="desc_cada_cantidad" value="cada_cantidad" {{ isset($config['desc_admin_cantidad']) && $config['desc_admin_cantidad'] == 'cada_cantidad' ? 'checked' : '' }}>
                            <label for="desc_cada_cantidad">Descontar por cada cantidad del servicio</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <label>Calcular el descuento administrativo antes o después del descuento de venta en caja</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_momento" id="desc_antes" value="antes" {{ !isset($config['desc_admin_momento']) || $config['desc_admin_momento'] == 'antes' ? 'checked' : '' }}>
                            <label for="desc_antes">Antes del descuento en caja</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_momento" id="desc_despues" value="despues" {{ isset($config['desc_admin_momento']) && $config['desc_admin_momento'] == 'despues' ? 'checked' : '' }}>
                            <label for="desc_despues">Después del descuento en caja</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="desc_admin_forma_pago" id="desc_admin_forma_pago" value="1" {{ isset($config['desc_admin_forma_pago']) && $config['desc_admin_forma_pago'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <label for="desc_admin_forma_pago">Activar descuentos administrativos por forma de pago</label>
                    </div>
                </div>

                <div class="option-group">
                    <label>Descuento administrativo por forma de pago</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_fp_aplicar" id="fp_todos" value="todos" {{ !isset($config['desc_admin_fp_aplicar']) || $config['desc_admin_fp_aplicar'] == 'todos' ? 'checked' : '' }}>
                            <label for="fp_todos">Aplicar todos los descuentos</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_fp_aplicar" id="fp_primero" value="primero" {{ isset($config['desc_admin_fp_aplicar']) && $config['desc_admin_fp_aplicar'] == 'primero' ?'checked' : '' }}>
                            <label for="fp_primero">Solo aplicar primer descuento</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <label>Calcular descuento administrativo por forma de pago desde</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_fp_desde" id="fp_precio_servicio" value="precio_servicio" {{ !isset($config['desc_admin_fp_desde']) || $config['desc_admin_fp_desde'] == 'precio_servicio' ? 'checked' : '' }}>
                            <label for="fp_precio_servicio">Precio de venta del servicio</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="desc_admin_fp_desde" id="fp_valor_total" value="valor_total" {{ isset($config['desc_admin_fp_desde']) && $config['desc_admin_fp_desde'] == 'valor_total' ? 'checked' : '' }}>
                            <label for="fp_valor_total">Valor total del medio de pago</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <label>Descontar descuento administrativo por forma de pago a especialistas</label>
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" name="desc_fp_cada_especialista" id="fp_cada_especialista" value="1" {{ isset($config['desc_fp_cada_especialista']) && $config['desc_fp_cada_especialista'] ? 'checked' : '' }}>
                            <label for="fp_cada_especialista">Descontar a cada especialista</label>
                        </div>
                        <div class="checkbox-option">
                            <input type="checkbox" name="desc_fp_dividir" id="fp_dividir" value="1" {{ isset($config['desc_fp_dividir']) && $config['desc_fp_dividir'] ? 'checked' : '' }}>
                            <label for="fp_dividir">Dividir entre las especialistas asociadas al descuento administrativo</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Productos de consumo -->
            <div class="commission-section">
                <h4>Productos de consumo</h4>
                
                <div class="option-group">
                    <label>Descontar Productos de consumo a</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="descontar_productos_a" id="prod_precio" value="precio_total" {{ !isset($config['descontar_productos_a']) || $config['descontar_productos_a'] == 'precio_total' ? 'checked' : '' }}>
                            <label for="prod_precio">Precio Total de Venta</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="descontar_productos_a" id="prod_participacion" value="participacion" {{ isset($config['descontar_productos_a']) && $config['descontar_productos_a'] == 'participacion' ? 'checked' : '' }}>
                            <label for="prod_participacion">Participación Especialista</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="descontar_productos_a" id="prod_empresa" value="empresa" {{ isset($config['descontar_productos_a']) && $config['descontar_productos_a'] == 'empresa' ? 'checked' : '' }}>
                            <label for="prod_empresa">Empresa</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Impuestos -->
            <div class="commission-section">
                <h4>Impuestos</h4>
                
                <div class="option-group">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="pagar_antes_impuestos_productos" id="pagar_antes_impuestos_productos" value="1" {{ isset($config['pagar_antes_impuestos_productos']) && $config['pagar_antes_impuestos_productos'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <label for="pagar_antes_impuestos_productos">Pagar Participacion antes de impuestos de productos</label>
                    </div>
                </div>

                <div class="option-group">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="pagar_antes_impuestos_servicios" id="pagar_antes_impuestos_servicios" value="1" {{ isset($config['pagar_antes_impuestos_servicios']) && $config['pagar_antes_impuestos_servicios'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <label for="pagar_antes_impuestos_servicios">Pagar Participacion antes de impuestos de servicios</label>
                    </div>
                </div>
            </div>

            <!-- Otras configuraciones -->
            <div class="commission-section">
                <h4>Otras configuraciones</h4>
                
                <div class="option-group">
                    <label>Descuento de productos de consumo y descuentos administrativos en caja</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="descuento_caja" id="caja_todos" value="todos" {{ !isset($config['descuento_caja']) || $config['descuento_caja'] == 'todos' ? 'checked' : '' }}>
                            <label for="caja_todos">Descontar todos</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="descuento_caja" id="caja_mayor" value="mayor_valor" {{ isset($config['descuento_caja']) && $config['descuento_caja'] == 'mayor_valor' ? 'checked' : '' }}>
                            <label for="caja_mayor">Descontar al servicio de mayor valor por especialista</label>
                        </div>
                    </div>
                </div>

                <div class="option-group">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="aplicar_productos_comision_fija" id="aplicar_productos_comision_fija" value="1" {{ isset($config['aplicar_productos_comision_fija']) && $config['aplicar_productos_comision_fija'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <label for="aplicar_productos_comision_fija">Aplicar productos de consumo a servicios con comisión fija 
                            <span class="info-icon" title="Información adicional">ℹ</span>
                        </label>
                    </div>
                </div>

                <div class="option-group">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="aplicar_desc_admin_comision_fija" id="aplicar_desc_admin_comision_fija" value="1" {{ isset($config['aplicar_desc_admin_comision_fija']) && $config['aplicar_desc_admin_comision_fija'] ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <label for="aplicar_desc_admin_comision_fija">Aplicar descuentos administrativos a servicios con comisión fija 
                            <span class="info-icon" title="Información adicional">ℹ</span>
                        </label>
                    </div>
                </div>
            </div>

        </div>

        <!-- Submit Section -->
        <div style="background: white; padding: 20px 30px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: white; border: 1px solid #cbd5e1; color: #475569; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none;">Cancelar</a>
            <button type="submit" class="btn btn-primary" style="background: #2563eb; color: white; border: none; padding: 12px 45px; border-radius: 8px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);">Guardar Cambios</button>
        </div>
    </div>
</form>

<style>
    .commission-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 25px;
    }
    .commission-section h4 {
        color: #1e293b;
        font-size: 16px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .status-badge {
        background: #ecfdf5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(function() {
            flash.style.transition = 'opacity 0.5s';
            flash.style.opacity = '0';
            setTimeout(function() { flash.remove(); }, 500);
        }, 3000);
    }
});
</script>

@endsection