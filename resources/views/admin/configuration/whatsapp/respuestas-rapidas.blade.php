@extends('admin.configuration._layout')

@section('config_title', 'Respuestas Rápidas WhatsApp')

@section('config_content')
<style>
    .quick-reply-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .quick-reply-card:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .quick-reply-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .quick-reply-title {
        font-weight: 700;
        font-size: 15px;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .quick-reply-shortcut {
        background: #f3f4f6;
        color: #4b5563;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
    }
    .quick-reply-body {
        background: #f9fafb;
        padding: 15px;
        border-radius: 8px;
        font-size: 14px;
        color: #374151;
        line-height: 1.5;
        border-left: 4px solid #1a73e8;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Biblioteca de Respuestas</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Gestiona tus atajos para responder más rápido a tus clientes.</p>
        </div>
        <button class="btn btn-primary" onclick="openQuickModal()">➕ Nueva Respuesta</button>
    </div>

    <div id="quick-replies-list" style="display: grid; gap: 15px;">
        @forelse($respuestas as $resp)
        <div class="quick-reply-card">
            <div class="quick-reply-header">
                <div class="quick-reply-title">
                    <span>{{ $resp['titulo'] }}</span>
                    <span class="quick-reply-shortcut">{{ $resp['atajo'] ?: 'N/A' }}</span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn-edit" onclick="editQuick({{ json_encode($resp) }})">✏️</button>
                    <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteQuick({{ $resp['id'] }})">🗑️</button>
                </div>
            </div>
            <div class="quick-reply-body">
                {{ $resp['mensaje'] }}
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; color: #6b7280;">
            No tienes respuestas rápidas configuradas.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="quickModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Nueva Respuesta Rápida</h3>
            <button class="modal-close" onclick="closeModal('quickModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="quickForm">
                <input type="hidden" id="quickId">
                <div class="form-group">
                    <label>Título / Nombre *</label>
                    <input type="text" id="quickTitle" class="form-control" placeholder="Ej: Horarios de Atención" required>
                </div>
                <div class="form-group">
                    <label>Atajo (Slug)</label>
                    <input type="text" id="quickShortcut" class="form-control" placeholder="Ej: /horarios">
                </div>
                <div class="form-group">
                    <label>Mensaje *</label>
                    <textarea id="quickMessage" class="form-control" rows="5" placeholder="Escribe la respuesta predefinida..." required></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('quickModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Respuesta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let respuestas = {!! json_encode($respuestas) !!};

function openQuickModal() {
    document.querySelector('#quickModal h3').textContent = 'Nueva Respuesta';
    document.getElementById('quickId').value = '';
    document.getElementById('quickForm').reset();
    document.getElementById('quickModal').classList.add('active');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function editQuick(resp) {
    document.querySelector('#quickModal h3').textContent = 'Editar Respuesta';
    document.getElementById('quickId').value = resp.id;
    document.getElementById('quickTitle').value = resp.titulo;
    document.getElementById('quickShortcut').value = resp.atajo || '';
    document.getElementById('quickMessage').value = resp.mensaje;
    document.getElementById('quickModal').classList.add('active');
}

function deleteQuick(id) {
    if (!confirm('¿Seguro que quieres eliminar esta respuesta?')) return;
    respuestas = respuestas.filter(r => r.id !== id);
    saveQuick();
}

document.getElementById('quickForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('quickId').value;
    const resp = {
        id: id ? parseInt(id) : (respuestas.length > 0 ? Math.max(...respuestas.map(r => r.id)) + 1 : 1),
        titulo: document.getElementById('quickTitle').value,
        atajo: document.getElementById('quickShortcut').value,
        mensaje: document.getElementById('quickMessage').value
    };

    if (id) {
        const index = respuestas.findIndex(r => r.id == id);
        respuestas[index] = resp;
    } else {
        respuestas.push(resp);
    }
    
    saveQuick();
});

function saveQuick() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('whatsapp_quick_replies', JSON.stringify(respuestas));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('quickModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal('quickModal');
});
</script>
@endsection