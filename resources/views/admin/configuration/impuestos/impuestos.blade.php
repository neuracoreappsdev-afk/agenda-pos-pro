@extends('admin.configuration._layout')

@section('config_title', 'Impuestos')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Configura los impuestos aplicables a tus servicios y productos</p>
        <button class="btn btn-primary" onclick="openModal('taxModal')">➕ Nuevo Impuesto</button>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Impuesto</th>
                <th>Porcentaje</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>IVA</strong></td>
                <td>19%</td>
                <td><span class="badge badge-info">General</span></td>
                <td><span class="badge badge-success">Activo</span></td>
                <td>
                    <button class="btn-edit" onclick="editTax(1)">Editar</button>
                </td>
            </tr>
            <tr>
                <td><strong>INC (Consumo)</strong></td>
                <td>8%</td>
                <td><span class="badge badge-info">Servicios</span></td>
                <td><span class="badge badge-success">Activo</span></td>
                <td>
                    <button class="btn-edit" onclick="editTax(2)">Editar</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div id="taxModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Impuesto</h3>
            <button class="modal-close" onclick="closeModal('taxModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Nombre del Impuesto *</label>
                <input type="text" class="form-control" placeholder="Ej: IVA" required>
            </div>
            <div class="form-group">
                <label>Porcentaje *</label>
                <input type="number" class="form-control" placeholder="19" min="0" max="100" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Aplicable a</label>
                <select class="form-control">
                    <option>Todos</option>
                    <option>Solo Servicios</option>
                    <option>Solo Productos</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" checked>
                    <span>Incluido en precio</span>
                </label>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('taxModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Impuesto</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editTax(id) { openModal('taxModal'); }
document.getElementById('taxModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});
</script>

@endsection
