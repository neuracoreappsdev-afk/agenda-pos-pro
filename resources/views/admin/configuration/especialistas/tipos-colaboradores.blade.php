@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Colaboradores')

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

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-active .status-dot { background: #10b981; }
    .status-active .status-text { color: #10b981; font-weight: 500; }
    .status-inactive .status-dot { background: #9ca3af; }
    .status-inactive .status-text { color: #9ca3af; }

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
        max-width: 550px;
        max-height: 90vh;
        overflow-y: auto;
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

    .toggle-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
    }

    .toggle-text {
        font-size: 14px;
        color: #374151;
    }

    .toggle-desc {
        font-size: 12px;
        color: #1a73e8;
        margin-top: 4px;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    input:checked + .slider { background-color: #2563eb; }
    input:checked + .slider:before { transform: translateX(20px); }

    .section-title {
        font-weight: 600;
        font-size: 14px;
        color: #374151;
        margin: 20px 0 15px;
    }

    .commission-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .commission-input {
        width: 100px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        text-align: right;
    }

    .type-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
    }

    .type-btn.percent { background: #dbeafe; color: #2563eb; }
    .type-btn.percent.active { background: #2563eb; color: white; }
    .type-btn.fixed { background: #fef3c7; color: #d97706; }
    .type-btn.fixed.active { background: #d97706; color: white; }

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

    .btn-save {
        background: #2563eb;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Tipos de Colaboradores</h1>
        <button type="button" class="btn-create" onclick="openModal()">Crear Nuevo</button>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th></th>
                <th class="sortable">Nombre</th>
                <th class="sortable">Colaboración Activa ↕</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($tipos as $index => $tipo)
            <tr>
                <td class="col-num">{{ $index + 1 }}</td>
                <td>{{ $tipo['nombre'] }}</td>
                <td>
                    <span class="status-badge {{ $tipo['colaboracion_activa'] ? 'status-active' : 'status-inactive' }}">
                        <span class="status-dot"></span>
                        <span class="status-text">{{ $tipo['colaboracion_activa'] ? 'Activo' : 'Inactivo' }}</span>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn-editar" onclick="editarTipo({{ $tipo['id'] }}, '{{ $tipo['nombre'] }}', {{ $tipo['colaboracion_activa'] ? 'true' : 'false' }})">Editar</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4">
                    <div class="empty-state">No hay Especialistas</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal-overlay" id="tipoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Tipos de Colaboradores</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-input" id="tipoNombre" placeholder="Nombre">
            </div>

            <div class="toggle-row">
                <label class="switch">
                    <input type="checkbox" id="colaboracionActiva" checked>
                    <span class="slider"></span>
                </label>
                <span class="toggle-text">Colaboración Activa</span>
            </div>

            <div class="toggle-row">
                <label class="switch">
                    <input type="checkbox" id="servicioCompartido">
                    <span class="slider"></span>
                </label>
                <span class="toggle-text">Servicio Compartido</span>
            </div>

            <div class="toggle-row">
                <label class="switch">
                    <input type="checkbox" id="esPrincipal">
                    <span class="slider"></span>
                </label>
                <span class="toggle-text">Es Colaborador Principal</span>
            </div>

            <div class="form-group">
                <label class="form-label">Descontar Desde</label>
                <select class="form-input" id="descontarDesde">
                    <option value="general">Configuración General</option>
                    <option value="precio_venta">Precio Total de Venta</option>
                    <option value="participacion">Participación Especialista</option>
                    <option value="empresa">Empresa</option>
                </select>
            </div>

            <div class="toggle-row">
                <label class="switch">
                    <input type="checkbox" id="compartirConsumo">
                    <span class="slider"></span>
                </label>
                <div>
                    <span class="toggle-text">Compartir Productos de consumo</span>
                    <div class="toggle-desc">Aplica al calcular comision del colaborador después de productos de consumo en el total de venta</div>
                </div>
            </div>

            <div class="section-title">Comisiones</div>
            <div class="commission-row">
                <span>Activar</span>
                <label class="switch">
                    <input type="checkbox" id="activarComision">
                    <span class="slider"></span>
                </label>
                <input type="number" class="commission-input" id="comisionValor" value="0">
                <button type="button" class="type-btn percent active" onclick="setType(this)">%</button>
                <button type="button" class="type-btn fixed" onclick="setType(this)">$</button>
            </div>

            <div class="toggle-row">
                <label class="switch">
                    <input type="checkbox" id="editarCaja">
                    <span class="slider"></span>
                </label>
                <span class="toggle-text">Puede editar comision en caja</span>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <button type="button" class="btn-save" onclick="guardarTipo()">Crear</button>
        </div>
    </div>
</div>

<input type="hidden" id="currentTipoId" value="">

<script>
    function openModal() {
        document.getElementById('currentTipoId').value = '';
        document.getElementById('tipoNombre').value = '';
        document.getElementById('colaboracionActiva').checked = true;
        document.querySelector('.btn-save').textContent = 'Crear';
        document.getElementById('tipoModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('tipoModal').classList.remove('show');
    }

    function editarTipo(id, nombre, activo) {
        document.getElementById('currentTipoId').value = id;
        document.getElementById('tipoNombre').value = nombre;
        document.getElementById('colaboracionActiva').checked = activo;
        document.querySelector('.btn-save').textContent = 'Guardar';
        document.getElementById('tipoModal').classList.add('show');
    }

    function setType(btn) {
        btn.parentElement.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function guardarTipo() {
        const id = document.getElementById('currentTipoId').value;
        const nombre = document.getElementById('tipoNombre').value;
        const activo = document.getElementById('colaboracionActiva').checked;

        if (!nombre.trim()) {
            showToast('Ingrese un nombre', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        if (id) formData.append('id', id);
        formData.append('nombre', nombre);
        formData.append('colaboracion_activa', activo ? '1' : '0');

        fetch('{{ url("admin/configuration/save-tipo-colaborador") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                showToast('Tipo guardado correctamente', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error al guardar', 'error');
            }
        })
        .catch(err => showToast('Error de conexión', 'error'));
    }

    document.getElementById('tipoModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>

@endsection