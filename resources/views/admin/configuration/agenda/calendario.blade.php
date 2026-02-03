@extends('admin.configuration._layout')

@section('config_title', 'Configuración de Agenda')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Calendario y Agenda</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Personaliza el comportamiento de tu agenda de trabajo y las reglas de asignación.</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <!-- Reglas de Agendamiento -->
                <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px;">
                    <h4 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #111827; border-bottom: 1px solid #f3f4f6; padding-bottom: 10px;">
                        📅 Reglas de Agendamiento
                    </h4>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <div>
                            <div style="font-weight: 600; font-size: 13px;">Cliente Obligatorio</div>
                            <div style="font-size: 11px; color: #6b7280;">Exigir datos antes de agendar.</div>
                        </div>
                        <label class="switch">
                            <input type="hidden" name="cliente_obligatorio_agenda" value="0">
                            <input type="checkbox" name="cliente_obligatorio_agenda" value="1" {{ ($settings['cliente_obligatorio_agenda'] ?? true) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 15px;">
                        <label style="font-size: 13px; font-weight: 600;">Intervalo de Tiempo (Min)</label>
                        <select name="intervalo_tiempo" class="form-control">
                            @foreach([10, 15, 20, 30, 45, 60] as $min)
                            <option value="{{ $min }}" {{ ($settings['intervalo_tiempo'] ?? 30) == $min ? 'selected' : '' }}>{{ $min }} Minutos</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="font-size: 13px; font-weight: 600;">Vista Predeterminada</label>
                        <select name="vista_predeterminada" class="form-control">
                            <option value="especialistas" {{ ($settings['vista_predeterminada'] ?? '') == 'especialistas' ? 'selected' : '' }}>Por Especialistas</option>
                            <option value="dia" {{ ($settings['vista_predeterminada'] ?? '') == 'dia' ? 'selected' : '' }}>Por Día (Sede)</option>
                            <option value="semana" {{ ($settings['vista_predeterminada'] ?? '') == 'semana' ? 'selected' : '' }}>Por Semana</option>
                        </select>
                    </div>
                </div>

                <!-- Política de Bloqueo -->
                <div style="background: #fff1f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 20px;">
                    <h4 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #9f1239; border-bottom: 1px solid #fecaca; padding-bottom: 10px;">
                        🛡️ Protección contra Ausentismo
                    </h4>
                    
                    <p style="font-size: 12px; color: #914151; line-height: 1.4; margin-bottom: 15px;">
                        Bloquea automáticamente a clientes que falten a sus citas.
                    </p>

                    <div style="display: grid; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #9f1239;">
                            Bloquear por <input type="number" name="bloqueo_dias" value="{{ $settings['bloqueo_dias'] ?? 0 }}" style="width: 60px; padding: 4px; border-radius: 6px; border: 1px solid #fecaca;"> días
                        </div>
                        <div style="font-size: 13px; color: #9f1239;">
                            al acumular <input type="number" name="bloqueo_veces" value="{{ $settings['bloqueo_veces'] ?? 0 }}" style="width: 60px; padding: 4px; border-radius: 6px; border: 1px solid #fecaca;"> faltas
                        </div>
                        <div style="font-size: 13px; color: #9f1239;">
                            motivo: 
                            <select name="bloqueo_motivo" style="padding: 4px; border-radius: 6px; border: 1px solid #fecaca;">
                                <option value="cancelada" {{ ($settings['bloqueo_motivo'] ?? '') == 'cancelada' ? 'selected' : '' }}>Cita Cancelada</option>
                                <option value="no_asistio" {{ ($settings['bloqueo_motivo'] ?? '') == 'no_asistio' ? 'selected' : '' }}>No Asistió</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Opciones Visuales -->
                <div style="background: #f0f7ff; border: 1px solid #cce3ff; border-radius: 12px; padding: 20px;">
                    <h4 style="margin: 0 0 15px 0; font-size: 14px; font-weight: 700; color: #1e40af; border-bottom: 1px solid #dbeafe; padding-bottom: 10px;">
                        🖥️ Visualización y Filtros
                    </h4>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-size: 13px; color: #1e40af;">Mostrar Especialistas Bloqueados</span>
                        <label class="switch">
                            <input type="hidden" name="mostrar_bloqueados_dia" value="0">
                            <input type="checkbox" name="mostrar_bloqueados_dia" value="1" {{ ($settings['mostrar_bloqueados_dia'] ?? true) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-size: 13px; color: #1e40af;">Botón "Cualquier Especialista"</span>
                        <label class="switch">
                            <input type="hidden" name="especialista_primero_disponible" value="0">
                            <input type="checkbox" name="especialista_primero_disponible" value="1" {{ ($settings['especialista_primero_disponible'] ?? false) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 13px; color: #1e40af;">Mostrar Todas las Sedes</span>
                        <label class="switch">
                            <input type="hidden" name="mostrar_citas_todas_sedes" value="0">
                            <input type="checkbox" name="mostrar_citas_todas_sedes" value="1" {{ ($settings['mostrar_citas_todas_sedes'] ?? false) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Configuración de Agenda</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>
@endsection