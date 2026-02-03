@extends('admin.configuration._layout')

@section('config_title', 'Líneas de Servicios')

@section('config_content')
<style>
    .service-line-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .service-line-card:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .service-line-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    .service-line-info {
        flex: 1;
    }
    .service-line-name {
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .service-line-count {
        font-size: 12px;
        color: #6b7280;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Categorías de Servicios</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Organiza tus servicios por líneas de negocio (Peluquería, Estética, Spa, etc.).</p>
        </div>
        <button class="btn btn-primary" onclick="openLineModal()">➕ Nueva Línea</button>
    </div>

    <div id="service-lines-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
        @forelse($lineas as $linea)
        <div class="service-line-card">
            <div class="service-line-icon" style="background: {{ $linea['color'] ?? '#1a73e8' }}">
                {{ $linea['icono'] ?? '⚙️' }}
            </div>
            <div class="service-line-info">
                <h4 class="service-line-name">{{ $linea['nombre'] }}</h4>
                <span class="service-line-count">{{ $linea['servicios'] ?? 0 }} servicios activos</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn-edit" onclick="editLine({{ json_encode($linea) }})">✏️</button>
                <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteLine({{ $linea['id'] }})">🗑️</button>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; color: #6b7280;">
            No hay líneas de servicios configuradas.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="lineModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Nueva Línea de Servicio</h3>
            <button class="modal-close" onclick="closeModal('lineModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="lineForm">
                <input type="hidden" id="lineId">
                <div class="form-group">
                    <label>Nombre de la Línea *</label>
                    <input type="text" id="lineName" class="form-control" placeholder="Ej: Estética Facial" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Icono (Emoji)</label>
                        <input type="text" id="lineIcon" class="form-control" placeholder="🧖" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="color" id="lineColor" class="form-control" value="#1a73e8" style="height: 42px; padding: 5px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('lineModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Línea</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let lineas = {!! json_encode($lineas) !!};

function openLineModal() {
    document.querySelector('#lineModal h3').textContent = 'Nueva Línea';
    document.getElementById('lineId').value = '';
    document.getElementById('lineForm').reset();
    document.getElementById('lineModal').classList.add('active');
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editLine(linea) {
    document.querySelector('#lineModal h3').textContent = 'Editar Línea';
    document.getElementById('lineId').value = linea.id;
    document.getElementById('lineName').value = linea.nombre;
    document.getElementById('lineIcon').value = linea.icono || '';
    document.getElementById('lineColor').value = linea.color || '#1a73e8';
    document.getElementById('lineModal').classList.add('active');
}

function deleteLine(id) {
    if (!confirm('¿Eliminar esta línea de servicio?')) return;
    lineas = lineas.filter(l => l.id !== id);
    saveLineas();
}

document.getElementById('lineForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('lineId').value;
    const linea = {
        id: id ? parseInt(id) : (lineas.length > 0 ? Math.max(...lineas.map(l => l.id)) + 1 : 1),
        nombre: document.getElementById('lineName').value,
        icono: document.getElementById('lineIcon').value || '⚙️',
        color: document.getElementById('lineColor').value,
        servicios: id ? (lineas.find(l => l.id == id).servicios || 0) : 0
    };

    if (id) {
        const index = lineas.findIndex(l => l.id == id);
        lineas[index] = linea;
    } else {
        lineas.push(linea);
    }
    saveLineas();
});

function saveLineas() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('service_lines', JSON.stringify(lineas));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('lineModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal('lineModal');
});
</script>
@endsection
