@extends('admin.configuration._layout')

@section('config_title', 'Estados de Reservas')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Define los estados del ciclo de vida de una reserva</p>
        <button class="btn btn-primary" onclick="openModal('statusModal')">➕ Nuevo Estado</button>
    </div>

    <div style="display: grid; gap: 12px;">
        <div style="background: white; border-left: 4px solid #3b82f6; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">📝</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">Pendiente</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Reserva creada, esperando confirmación</p>
            </div>
            <span class="badge" style="background: #dbeafe; color: #1e40af;">Por Defecto</span>
            <button class="btn btn-secondary" onclick="editStatus(1)">✏️</button>
        </div>

        <div style="background: white; border-left: 4px solid #10b981; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #d1fae5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">✅</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">Confirmada</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Cliente confirmó su asistencia</p>
            </div>
            <button class="btn btn-secondary" onclick="editStatus(2)">✏️</button>
        </div>

        <div style="background: white; border-left: 4px solid #8b5cf6; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #f3e8ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">⏳</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">En Proceso</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Cliente está siendo atendido</p>
            </div>
            <button class="btn btn-secondary" onclick="editStatus(3)">✏️</button>
        </div>

        <div style="background: white; border-left: 4px solid #22c55e; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #dcfce7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">🎉</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">Completada</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Servicio finalizado exitosamente</p>
            </div>
            <button class="btn btn-secondary" onclick="editStatus(4)">✏️</button>
        </div>

        <div style="background: white; border-left: 4px solid #ef4444; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #fee2e2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">❌</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">Cancelada</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Reserva fue cancelada</p>
            </div>
            <button class="btn btn-secondary" onclick="editStatus(5)">✏️</button>
        </div>

        <div style="background: white; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 18px; display: flex; align-items: center; gap: 15px;">
            <div style="width: 40px; height: 40px; background: #fef3c7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">⚠️</div>
            <div style="flex: 1;">
                <h4 style="margin: 0 0 3px 0; font-weight: 600;">No Show</h4>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Cliente no asistió</p>
            </div>
            <button class="btn btn-secondary" onclick="editStatus(6)">✏️</button>
        </div>
    </div>
</div>

<div id="statusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Estado</h3>
            <button class="modal-close" onclick="closeModal('statusModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Nombre del Estado *</label>
                <input type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Color</label>
                <input type="color" class="form-control" value="#3b82f6">
            </div>
            <div class="form-group">
                <label>Icono (Emoji)</label>
                <input type="text" class="form-control" placeholder="📝" maxlength="2">
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox">
                    <span>Estado por defecto</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('statusModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Estado</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editStatus(id) { openModal('statusModal'); }
document.getElementById('statusModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal('statusModal'); });
</script>
@endsection
