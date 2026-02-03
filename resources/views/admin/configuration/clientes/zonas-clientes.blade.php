@extends('admin.configuration._layout')

@section('config_title', 'Zonas de Clientes')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Organiza clientes por zonas geográficas para análisis y promociones</p>
        <button class="btn btn-primary" onclick="openModal('zoneModal')">➕ Nueva Zona</button>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Zona</th>
                <th>Clientes</th>
                <th>Ventas (Mes)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>🏙️ Centro</strong></td>
                <td>145</td>
                <td>$4,230,000</td>
                <td>
                    <button class="btn-edit" onclick="editZone(1)">Editar</button>
                    <button class="btn-edit" style="background: #ef4444;" onclick="deleteZone(1)">Eliminar</button>
                </td>
            </tr>
            <tr>
                <td><strong>🏘️ Zona Norte</strong></td>
                <td>89</td>
                <td>$2,890,000</td>
                <td>
                    <button class="btn-edit" onclick="editZone(2)">Editar</button>
                    <button class="btn-edit" style="background: #ef4444;" onclick="deleteZone(2)">Eliminar</button>
                </td>
            </tr>
            <tr>
                <td><strong>🌳 Zona Sur</strong></td>
                <td>67</td>
                <td>$1,950,000</td>
                <td>
                    <button class="btn-edit" onclick="editZone(3)">Editar</button>
                    <button class="btn-edit" style="background: #ef4444;" onclick="deleteZone(3)">Eliminar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div id="zoneModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nueva Zona</h3>
            <button class="modal-close" onclick="closeModal('zoneModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Nombre de la Zona *</label>
                <input type="text" class="form-control" placeholder="Ej: Centro" required>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea class="form-control" rows="2" placeholder="Barrios o sectores incluidos"></textarea>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('zoneModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Zona</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editZone(id) { openModal('zoneModal'); }
function deleteZone(id) { if(confirm('¿Eliminar zona?')) alert('Eliminada'); }
document.getElementById('zoneModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal('zoneModal'); });
</script>
@endsection
