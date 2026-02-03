@extends('admin.configuration._layout')

@section('config_title', 'Estados de Reservas')

@section('config_content')

<style>
    .page-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 25px 0;
    }

    /* Estados Table */
    .estados-table {
        width: 100%;
        border-collapse: collapse;
    }

    .estados-table th {
        text-align: left;
        padding: 14px 12px;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        font-size: 14px;
    }

    .estados-table th.sortable {
        cursor: pointer;
    }

    .estados-table th.sortable:hover {
        color: #1a73e8;
    }

    .estados-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 14px;
    }

    .estados-table tbody tr:hover {
        background: #f9fafb;
    }

    .col-num {
        width: 40px;
        color: #9ca3af;
    }

    .estado-nombre {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .color-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .estado-text {
        color: #1a73e8;
        font-weight: 500;
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

    .status-active .status-dot {
        background: #10b981;
    }

    .status-active .status-text {
        color: #10b981;
        font-weight: 500;
    }

    .status-inactive .status-dot {
        background: #9ca3af;
    }

    .status-inactive .status-text {
        color: #9ca3af;
    }

    .btn-editar {
        background: #2563eb;
        color: white;
        padding: 6px 16px;
        border-radius: 4px;
        border: none;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-editar:hover {
        background: #1d4ed8;
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

    .modal-overlay.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
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
        line-height: 1;
    }

    .modal-close:hover {
        color: #374151;
    }

    .modal-body {
        padding: 24px;
    }

    .estado-name-display {
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .section-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
        text-align: center;
    }

    /* Color Picker Grid */
    .color-picker-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-bottom: 25px;
    }

    .color-option {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s;
    }

    .color-option:hover {
        transform: scale(1.1);
    }

    .color-option.selected {
        border-color: #1f2937;
        box-shadow: 0 0 0 2px white inset;
    }

    /* Toggle */
    .toggle-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .toggle-label {
        font-size: 14px;
        color: #374151;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
        border-radius: 26px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2563eb;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    /* Modal Footer */
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

    .btn-cancel:hover {
        background: #fef2f2;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
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

    .btn-save:hover {
        background: #1d4ed8;
    }

    .footer-right {
        display: flex;
        gap: 10px;
    }
</style>

<div class="page-container">
    <h1 class="page-title">Estados de Reservas</h1>

    <table class="estados-table">
        <thead>
            <tr>
                <th class="col-num"></th>
                <th class="sortable">Nombre ↕</th>
                <th class="sortable">Mostrar eventos con este estado en la agenda ↕</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($estados as $index => $estado)
            <tr>
                <td class="col-num">{{ $index + 1 }}</td>
                <td>
                    <div class="estado-nombre">
                        <span class="color-dot" style="background-color: {{ $estado['color'] }};"></span>
                        <span class="estado-text">{{ $estado['nombre'] }}</span>
                    </div>
                </td>
                <td>
                    <span class="status-badge {{ $estado['activo'] ? 'status-active' : 'status-inactive' }}">
                        <span class="status-dot"></span>
                        <span class="status-text">{{ $estado['activo'] ? 'Activo' : 'Inactivo' }}</span>
                    </span>
                </td>
                <td>
                    <button type="button" class="btn-editar" 
                            onclick="editarEstado({{ $estado['id'] }}, '{{ $estado['nombre'] }}', '{{ $estado['color'] }}', {{ $estado['activo'] ? 'true' : 'false' }})">
                        Editar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Agregar de Estado de Reserva</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="estado-name-display" id="modalEstadoName">Cita Cancelada</div>
            
            <div class="section-label">Color</div>
            <div class="color-picker-grid" id="colorPicker">
                <!-- Row 1 -->
                <div class="color-option" style="background: #f87171;" onclick="selectColor(this, '#f87171')"></div>
                <div class="color-option" style="background: #fb923c;" onclick="selectColor(this, '#fb923c')"></div>
                <div class="color-option" style="background: #fbbf24;" onclick="selectColor(this, '#fbbf24')"></div>
                <div class="color-option" style="background: #a3e635;" onclick="selectColor(this, '#a3e635')"></div>
                <div class="color-option" style="background: #34d399;" onclick="selectColor(this, '#34d399')"></div>
                <div class="color-option" style="background: #22d3ee;" onclick="selectColor(this, '#22d3ee')"></div>
                <div class="color-option" style="background: #60a5fa;" onclick="selectColor(this, '#60a5fa')"></div>
                <div class="color-option" style="background: #a78bfa;" onclick="selectColor(this, '#a78bfa')"></div>
                <div class="color-option" style="background: #f472b6;" onclick="selectColor(this, '#f472b6')"></div>
                <div class="color-option selected" style="background: #ef4444;" onclick="selectColor(this, '#ef4444')"></div>
                <div class="color-option" style="background: #3b82f6;" onclick="selectColor(this, '#3b82f6')"></div>
                <div class="color-option" style="background: #6366f1;" onclick="selectColor(this, '#6366f1')"></div>
                <!-- Row 2 -->
                <div class="color-option" style="background: #1e3a5f;" onclick="selectColor(this, '#1e3a5f')"></div>
                <div class="color-option" style="background: #14b8a6;" onclick="selectColor(this, '#14b8a6')"></div>
                <div class="color-option" style="background: #10b981;" onclick="selectColor(this, '#10b981')"></div>
                <div class="color-option" style="background: #84cc16;" onclick="selectColor(this, '#84cc16')"></div>
                <div class="color-option" style="background: #facc15;" onclick="selectColor(this, '#facc15')"></div>
                <div class="color-option" style="background: #f97316;" onclick="selectColor(this, '#f97316')"></div>
                <div class="color-option" style="background: #ef4444;" onclick="selectColor(this, '#ef4444')"></div>
                <div class="color-option" style="background: #ec4899;" onclick="selectColor(this, '#ec4899')"></div>
                <div class="color-option" style="background: #f9a8d4;" onclick="selectColor(this, '#f9a8d4')"></div>
                <div class="color-option" style="background: #fcd34d;" onclick="selectColor(this, '#fcd34d')"></div>
                <div class="color-option" style="background: #fde68a;" onclick="selectColor(this, '#fde68a')"></div>
                <div class="color-option" style="background: #d1d5db;" onclick="selectColor(this, '#d1d5db')"></div>
            </div>

            <div class="toggle-section">
                <span class="toggle-label">Mostrar eventos con este estado en la agenda</span>
                <label class="switch">
                    <input type="checkbox" id="modalActivo" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
            <div class="footer-right">
                <button type="button" class="btn-delete" onclick="eliminarEstado()">Eliminar</button>
                <button type="button" class="btn-save" onclick="guardarEstado()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="currentEstadoId" value="">
<input type="hidden" id="currentColor" value="#ef4444">

<script>
    function editarEstado(id, nombre, color, activo) {
        document.getElementById('currentEstadoId').value = id;
        document.getElementById('currentColor').value = color;
        document.getElementById('modalEstadoName').textContent = nombre;
        document.getElementById('modalActivo').checked = activo;
        
        // Select the correct color
        document.querySelectorAll('.color-option').forEach(opt => {
            opt.classList.remove('selected');
            if (rgbToHex(opt.style.backgroundColor).toLowerCase() === color.toLowerCase()) {
                opt.classList.add('selected');
            }
        });
        
        document.getElementById('editModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('editModal').classList.remove('show');
    }

    function selectColor(el, color) {
        document.querySelectorAll('.color-option').forEach(opt => opt.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('currentColor').value = color;
    }

    function guardarEstado() {
        const id = document.getElementById('currentEstadoId').value;
        const color = document.getElementById('currentColor').value;
        const activo = document.getElementById('modalActivo').checked;
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('id', id);
        formData.append('color', color);
        formData.append('activo', activo ? '1' : '0');
        
        fetch('{{ url("admin/configuration/save-estado-reserva") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if(response.ok) {
                showToast('Estado actualizado correctamente', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error al guardar', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        });
    }

    function eliminarEstado() {
        if (confirm('¿Está seguro de eliminar este estado?')) {
            showToast('Estado eliminado', 'success');
            closeModal();
        }
    }

    // Utility function
    function rgbToHex(rgb) {
        if (/^#/.test(rgb)) return rgb;
        let sep = rgb.indexOf(",") > -1 ? "," : " ";
        rgb = rgb.substr(4).split(")")[0].split(sep);
        let r = (+rgb[0]).toString(16),
            g = (+rgb[1]).toString(16),
            b = (+rgb[2]).toString(16);
        if (r.length == 1) r = "0" + r;
        if (g.length == 1) g = "0" + g;
        if (b.length == 1) b = "0" + b;
        return "#" + r + g + b;
    }

    // Close modal on overlay click
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>

@endsection