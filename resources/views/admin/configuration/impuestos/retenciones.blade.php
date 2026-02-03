@extends('admin.configuration._layout')

@section('config_title', 'Retenciones')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Configura retenciones fiscales aplicables</p>
        <button class="btn btn-primary" onclick="openModal('retModal')">➕ Nueva Retención</button>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Porcentaje</th>
                <th>Concepto</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Retención en la Fuente</strong></td>
                <td>2.5%</td>
                <td>Honorarios</td>
                <td><span class="badge badge-success">Activo</span></td>
                <td><button class="btn-edit" onclick="editRet(1)">Editar</button></td>
            </tr>
            <tr>
                <td><strong>Retención IVA</strong></td>
                <td>15%</td>
                <td>Servicios gravados</td>
                <td><span class="badge badge-success">Activo</span></td>
                <td><button class="btn-edit" onclick="editRet(2)">Editar</button></td>
            </tr>
            <tr>
                <td><strong>Retención ICA</strong></td>
                <td>0.966%</td>
                <td>Industria y Comercio</td>
                <td><span class="badge" style="background: #e5e7eb; color: #6b7280;">Inactivo</span></td>
                <td><button class="btn-edit" onclick="editRet(3)">Editar</button></td>
            </tr>
        </tbody>
    </table>
</div>

<div id="retModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nueva Retención</h3>
            <button class="modal-close" onclick="closeModal('retModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Tipo de Retención *</label>
                <input type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Porcentaje *</label>
                <input type="number" class="form-control" step="0.001" min="0" max="100" required>
            </div>
            <div class="form-group">
                <label>Concepto</label>
                <input type="text" class="form-control">
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" checked>
                    <span>Activo</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('retModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editRet(id) { openModal('retModal'); }
document.getElementById('retModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal('retModal'); });
</script>
@endsection