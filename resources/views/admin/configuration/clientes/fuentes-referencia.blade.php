@extends('admin.configuration._layout')

@section('config_title', 'Fuentes de Referencia')

@section('config_content')

<style>
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
    }
    .data-table th {
        text-align: left;
        padding: 12px 15px;
        background: #f9fafb;
        color: #4b5563;
        font-weight: 600;
        font-size: 13px;
        border-bottom: 1px solid #e5e7eb;
    }
    .data-table td {
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .btn-edit {
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit:hover {
        background: #e5e7eb;
        color: #111827;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        width: 100%;
        max-width: 450px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        padding: 0;
        overflow: hidden;
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9fafb;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
    }
    .modal-body {
        padding: 25px;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Fuentes de Referencia</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">¿Cómo nos conocieron tus clientes? Rastrea tus canales de adquisición</p>
        </div>
        <button class="btn btn-primary" style="display: flex; align-items: center; gap: 8px; padding: 12px 24px;" onclick="openModal('refModal')">
            <span style="font-size: 18px;">+</span> Nueva Fuente
        </button>
    </div>

    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fuente</th>
                    <th style="text-align: center;">Clientes</th>
                    <th style="text-align: center;">Porcentaje</th>
                    <th style="text-align: right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fuentes as $fuente)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 20px;">{{ $fuente['icono'] ?? '📍' }}</span>
                            <strong>{{ $fuente['nombre'] }}</strong>
                        </div>
                    </td>
                    <td style="text-align: center;">{{ $fuente['clientes'] ?? 0 }}</td>
                    <td style="text-align: center;">
                        <div style="background: #ecfdf5; color: #059669; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 700; display: inline-block;">
                            {{ $fuente['porcentaje'] ?? 0 }}%
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <button class="btn-edit" onclick="editRef({{ json_encode($fuente) }})">Editar</button>
                            <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteRef({{ $fuente['id'] }})">Eliminar</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="refModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nueva Fuente de Referencia</h3>
            <button class="modal-close" onclick="closeModal('refModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="refForm">
                <input type="hidden" id="refId">
                <div class="form-group">
                    <label>Nombre de la Fuente *</label>
                    <input type="text" class="form-control" id="refNombre" placeholder="Ej: Instagram, Google, Volantes..." required>
                </div>
                <div class="form-group">
                    <label>Icono (Emoji)</label>
                    <input type="text" class="form-control" id="refIcono" placeholder="📱">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" style="background: white; border: 1px solid #d1d5db; color: #374151;" onclick="closeModal('refModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="padding: 10px 30px;">Guardar Fuente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let fuentes = {!! json_encode($fuentes) !!};

function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { 
    document.getElementById(id).classList.remove('active'); 
    document.getElementById('refId').value = '';
    document.getElementById('refNombre').value = '';
}

function editRef(fuente) {
    document.getElementById('refId').value = fuente.id;
    document.getElementById('refNombre').value = fuente.nombre;
    document.getElementById('refIcono').value = fuente.icono || '';
    document.querySelector('#refModal h3').textContent = 'Editar Fuente';
    openModal('refModal');
}

function deleteRef(id) {
    if(!confirm('¿Estás seguro de eliminar esta fuente?')) return;
    fuentes = fuentes.filter(f => f.id !== id);
    saveFuentes();
}

document.getElementById('refForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('refId').value;
    const nombre = document.getElementById('refNombre').value;
    const icono = document.getElementById('refIcono').value;

    if (id) {
        const index = fuentes.findIndex(f => f.id == id);
        fuentes[index].nombre = nombre;
        fuentes[index].icono = icono;
    } else {
        const newId = fuentes.length > 0 ? Math.max(...fuentes.map(f => f.id)) + 1 : 1;
        fuentes.push({ id: newId, nombre: nombre, icono: icono, clientes: 0, porcentaje: 0 });
    }
    
    saveFuentes();
});

function saveFuentes() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('fuentes_referencia', JSON.stringify(fuentes));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('refModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal('refModal');
});
</script>

@endsection