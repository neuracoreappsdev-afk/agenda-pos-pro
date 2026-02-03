@extends('admin.configuration._layout')

@section('config_title', 'Mensajes de WhatsApp')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Configura mensajes automáticos para diferentes eventos</p>
        <button class="btn btn-primary" onclick="openModal('msgModal')">➕ Nuevo Mensaje</button>
    </div>

    <div style="display: grid; gap: 15px;">
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; font-weight: 600;">📅 Confirmación de Reserva</h4>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #6b7280;">Se envía cuando el cliente hace una reserva</p>
                    <div style="background: #f9fafb; padding: 12px; border-radius: 6px; font-size: 13px; font-family: monospace;">
                        Hola {nombre}! Tu reserva para {servicio} el {fecha} a las {hora} ha sido confirmada. Te esperamos! 😊
                    </div>
                </div>
                <div style="display: flex; gap: 8px; margin-left: 15px;">
                    <button class="btn btn-secondary" onclick="editMsg(1)">✏️</button>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; font-weight: 600;">⏰ Recordatorio de Cita</h4>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #6b7280;">Se envía 2 horas antes de la cita</p>
                    <div style="background: #f9fafb; padding: 12px; border-radius: 6px; font-size: 13px; font-family: monospace;">
                        Hola {nombre}! Te recordamos tu cita de {servicio} hoy a las {hora}. Nos vemos pronto! 🎉
                    </div>
                </div>
                <div style="display: flex; gap: 8px; margin-left: 15px;">
                    <button class="btn btn-secondary" onclick="editMsg(2)">✏️</button>
                    <label class="toggle-switch">
                        <input type="checkbox" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>

        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; opacity: 0.6;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; font-weight: 600;">❌ Cancelación de Reserva</h4>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #6b7280;">Se envía cuando se cancela una reserva</p>
                    <div style="background: #f9fafb; padding: 12px; border-radius: 6px; font-size: 13px; font-family: monospace;">
                        Tu reserva para {servicio} el {fecha} ha sido cancelada. Puedes reagendar cuando gustes.
                    </div>
                </div>
                <div style="display: flex; gap: 8px; margin-left: 15px;">
                    <button class="btn btn-secondary" onclick="editMsg(3)">✏️</button>
                    <label class="toggle-switch">
                        <input type="checkbox">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="msgModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Nuevo Mensaje WhatsApp</h3>
            <button class="modal-close" onclick="closeModal('msgModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Nombre del Mensaje *</label>
                <input type="text" class="form-control" placeholder="Ej: Bienvenida" required>
            </div>
            <div class="form-group">
                <label>Evento Disparador</label>
                <select class="form-control">
                    <option>Nueva Reserva</option>
                    <option>Recordatorio de Cita</option>
                    <option>Cancelación</option>
                    <option>Pago Confirmado</option>
                    <option>Cumpleaños</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mensaje *</label>
                <textarea class="form-control" rows="4" placeholder="Usa {nombre}, {fecha}, {hora}, {servicio}" required></textarea>
                <small style="color: #6b7280;">Variables disponibles: {nombre}, {fecha}, {hora}, {servicio}, {especialista}</small>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" checked>
                    <span>Mensaje activo</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('msgModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<style>
.toggle-switch { position: relative; width: 50px; height: 26px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #d1d5db; transition: 0.3s; border-radius: 26px; }
.toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 4px; bottom: 4px; background-color: white; transition: 0.3s; border-radius: 50%; }
input:checked + .toggle-slider { background-color: #10b981; }
input:checked + .toggle-slider:before { transform: translateX(24px); }
</style>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editMsg(id) { openModal('msgModal'); }
document.getElementById('msgModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal('msgModal'); });
</script>
@endsection
