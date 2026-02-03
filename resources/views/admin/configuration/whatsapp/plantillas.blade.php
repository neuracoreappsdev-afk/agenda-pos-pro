@extends('admin.configuration._layout')

@section('config_title', 'Plantillas WhatsApp Business')

@section('config_content')
<style>
    .template-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 20px;
    }
    .template-card:hover { border-color: #1a73e8; }
    .template-info h4 { margin: 0 0 5px 0; font-size: 16px; font-weight: 700; color: #111827; }
    .template-category { font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6b7280; background: #f3f4f6; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 10px; }
    .template-text { font-size: 14px; color: #4b5563; line-height: 1.6; background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #f3f4f6; }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Plantillas Homologadas</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Gestiona las plantillas que han sido aprobadas por Meta para envíos masivos o automáticos.</p>
        </div>
        <button class="btn btn-primary" onclick="openTemplateModal()">➕ Nueva Plantilla</button>
    </div>

    <div id="templates-list">
        @forelse($plantillas as $p)
        <div class="template-card">
            <div class="template-info">
                <span class="template-category">{{ $p['categoria'] ?? 'S/C' }}</span>
                <h4>{{ $p['nombre'] }}</h4>
                <div class="template-text">{{ $p['mensaje'] }}</div>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <button class="btn-edit" onclick="editTemplate({{ json_encode($p) }})">✏️</button>
                <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteTemplate({{ $p['id'] }})">🗑️</button>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 50px; background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; color: #6b7280;">
            No hay plantillas registradas.
        </div>
        @endforelse
    </div>
</div>

<div id="templateModal" class="modal">
    <div class="modal-content" style="max-width: 550px;">
        <div class="modal-header">
            <h3>Nueva Plantilla</h3>
            <button class="modal-close" onclick="closeModal('templateModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="templateForm">
                <input type="hidden" id="templateId">
                <div class="form-group">
                    <label>Nombre de la Plantilla (Meta ID) *</label>
                    <input type="text" id="templateName" class="form-control" placeholder="Ej: confirmacion_cita" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select id="templateCategory" class="form-control">
                        <option value="Servicio">Servicio / Transaccional</option>
                        <option value="Marketing">Marketing / Promo</option>
                        <option value="Autenticación">Autenticación (OTP)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contenido del Mensaje *</label>
                    <textarea id="templateText" class="form-control" rows="5" placeholder="Escribe el mensaje de la plantilla..." required></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('templateModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Plantilla</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let plantillas = {!! json_encode($plantillas) !!};

function openTemplateModal() {
    document.querySelector('#templateModal h3').textContent = 'Nueva Plantilla';
    document.getElementById('templateId').value = '';
    document.getElementById('templateForm').reset();
    document.getElementById('templateModal').classList.add('active');
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editTemplate(p) {
    document.querySelector('#templateModal h3').textContent = 'Editar Plantilla';
    document.getElementById('templateId').value = p.id;
    document.getElementById('templateName').value = p.nombre;
    document.getElementById('templateCategory').value = p.categoria || 'Servicio';
    document.getElementById('templateText').value = p.mensaje;
    document.getElementById('templateModal').classList.add('active');
}

function deleteTemplate(id) {
    if (!confirm('¿Eliminar esta plantilla?')) return;
    plantillas = plantillas.filter(p => p.id !== id);
    saveTemplates();
}

document.getElementById('templateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('templateId').value;
    const p = {
        id: id ? parseInt(id) : (plantillas.length > 0 ? Math.max(...plantillas.map(t => t.id)) + 1 : 1),
        nombre: document.getElementById('templateName').value,
        categoria: document.getElementById('templateCategory').value,
        mensaje: document.getElementById('templateText').value
    };
    if (id) {
        const index = plantillas.findIndex(t => t.id == id);
        plantillas[index] = p;
    } else {
        plantillas.push(p);
    }
    saveTemplates();
});

function saveTemplates() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('whatsapp_templates', JSON.stringify(plantillas));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('templateModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal('templateModal');
});
</script>
@endsection