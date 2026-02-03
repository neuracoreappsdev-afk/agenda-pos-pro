@extends('admin.configuration._layout')

@section('config_title', 'Especialidades')

@section('config_content')

<style>
    .page-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .btn-create {
        background: #2563eb;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-create:hover {
        background: #1d4ed8;
    }

    /* Table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 14px 12px;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        font-size: 14px;
    }

    .data-table th.sortable {
        cursor: pointer;
    }

    .data-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 14px;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
    }

    .col-num {
        width: 40px;
        color: #9ca3af;
    }

    .btn-editar {
        background: #2563eb;
        color: white;
        padding: 6px 16px;
        border-radius: 4px;
        border: none;
        font-size: 13px;
        cursor: pointer;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.show { display: flex; }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
    }

    .modal-body { padding: 24px; }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel {
        background: white;
        color: #ef4444;
        border: 1px solid #fecaca;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        display: none;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .btn-save {
        background: #2563eb;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
    }

    .footer-right {
        display: flex;
        gap: 10px;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Especialidades</h1>
        <button type="button" class="btn-create" onclick="openModal()">Crear Nuevo</button>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th></th>
                <th class="sortable">Nombre ↕</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($especialidades as $index => $esp)
            <tr>
                <td class="col-num">{{ $index + 1 }}</td>
                <td>{{ $esp['nombre'] }}</td>
                <td>
                    <button type="button" class="btn-editar" onclick="editarEspecialidad({{ $esp['id'] }}, '{{ $esp['nombre'] }}')">Editar</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">
                    <div class="empty-state">No hay especialidades</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal-overlay" id="espModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Agregar Especialidad</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-input" id="espNombre" placeholder="Nombre">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <div class="footer-right">
                <button type="button" class="btn-delete" id="btnEliminar" onclick="eliminarEspecialidad()">Eliminar</button>
                <button type="button" class="btn-save" onclick="guardarEspecialidad()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="currentEspId" value="">

<script>
    function openModal() {
        document.getElementById('currentEspId').value = '';
        document.getElementById('espNombre').value = '';
        document.querySelector('.modal-title').textContent = 'Agregar Especialidad';
        document.querySelector('.btn-save').textContent = 'Crear';
        document.getElementById('btnEliminar').style.display = 'none';
        document.getElementById('espModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('espModal').classList.remove('show');
    }

    function editarEspecialidad(id, nombre) {
        document.getElementById('currentEspId').value = id;
        document.getElementById('espNombre').value = nombre;
        document.querySelector('.modal-title').textContent = 'Agregar Especialidad';
        document.querySelector('.btn-save').textContent = 'Guardar';
        document.getElementById('btnEliminar').style.display = 'block';
        document.getElementById('espModal').classList.add('show');
    }

    function guardarEspecialidad() {
        const id = document.getElementById('currentEspId').value;
        const nombre = document.getElementById('espNombre').value;

        if (!nombre.trim()) {
            showToast('Ingrese un nombre', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (id) formData.append('id', id);
        formData.append('nombre', nombre);

        fetch('{{ url("admin/configuration/save-especialidad") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                showToast('Especialidad guardada correctamente', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error al guardar', 'error');
            }
        })
        .catch(err => showToast('Error de conexión', 'error'));
    }

    function eliminarEspecialidad() {
        const id = document.getElementById('currentEspId').value;
        if (!id) return;

        if (!confirm('¿Está seguro de eliminar esta especialidad?')) return;

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('id', id);

        fetch('{{ url("admin/configuration/delete-especialidad") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                showToast('Especialidad eliminada', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error al eliminar', 'error');
            }
        })
        .catch(err => showToast('Error de conexión', 'error'));
    }

    document.getElementById('espModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>

@endsection