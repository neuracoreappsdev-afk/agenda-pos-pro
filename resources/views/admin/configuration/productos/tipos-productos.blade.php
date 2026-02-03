@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Productos')

@section('config_content')
<style>
    .prod-type-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .prod-type-card:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .prod-type-icon {
        width: 55px;
        height: 55px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    .prod-type-info {
        flex: 1;
    }
    .prod-type-name {
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .prod-type-count {
        font-size: 12px;
        color: #6b7280;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Categorías de Productos</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Organiza tu inventario por tipos para facilitar la búsqueda y reportes.</p>
        </div>
        <button class="btn btn-primary" onclick="openProdModal()">➕ Nuevo Tipo</button>
    </div>

    <div id="prod-types-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
        @forelse($tipos as $tipo)
        <div class="prod-type-card">
            <div class="prod-type-icon" style="background: {{ $tipo['color'] ?? '#1a73e8' }}">
                {{ $tipo['icono'] ?? '📦' }}
            </div>
            <div class="prod-type-info">
                <h4 class="prod-type-name">{{ $tipo['nombre'] }}</h4>
                <span class="prod-type-count">{{ $tipo['productos'] ?? 0 }} productos</span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn-edit" onclick="editProd({{ json_encode($tipo) }})">✏️</button>
                <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteProd({{ $tipo['id'] }})">🗑️</button>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 50px; background: #f9fafb; border: 2px dashed #e5e7eb; border-radius: 12px; color: #6b7280;">
            No hay tipos de productos configurados.
        </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="prodModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Nuevo Tipo de Producto</h3>
            <button class="modal-close" onclick="closeModal('prodModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="prodForm">
                <input type="hidden" id="prodId">
                <div class="form-group">
                    <label>Nombre de Categoría *</label>
                    <input type="text" id="prodName" class="form-control" placeholder="Ej: Cuidado Facial" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Icono (Emoji)</label>
                        <input type="text" id="prodIcon" class="form-control" placeholder="🧴" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="color" id="prodColor" class="form-control" value="#1a73e8" style="height: 42px; padding: 5px;">
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('prodModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Tipo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let tipos = {!! json_encode($tipos) !!};

function openProdModal() {
    document.querySelector('#prodModal h3').textContent = 'Nuevo Tipo';
    document.getElementById('prodId').value = '';
    document.getElementById('prodForm').reset();
    document.getElementById('prodModal').classList.add('active');
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editProd(tipo) {
    document.querySelector('#prodModal h3').textContent = 'Editar Tipo';
    document.getElementById('prodId').value = tipo.id;
    document.getElementById('prodName').value = tipo.nombre;
    document.getElementById('prodIcon').value = tipo.icono || '';
    document.getElementById('prodColor').value = tipo.color || '#1a73e8';
    document.getElementById('prodModal').classList.add('active');
}

function deleteProd(id) {
    if (!confirm('¿Eliminar este tipo de producto?')) return;
    tipos = tipos.filter(t => t.id !== id);
    saveTipos();
}

document.getElementById('prodForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('prodId').value;
    const tipo = {
        id: id ? parseInt(id) : (tipos.length > 0 ? Math.max(...tipos.map(t => t.id)) + 1 : 1),
        nombre: document.getElementById('prodName').value,
        icono: document.getElementById('prodIcon').value || '📦',
        color: document.getElementById('prodColor').value,
        productos: id ? (tipos.find(t => t.id == id).productos || 0) : 0
    };

    if (id) {
        const index = tipos.findIndex(t => t.id == id);
        tipos[index] = tipo;
    } else {
        tipos.push(tipo);
    }
    saveTipos();
});

function saveTipos() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('product_types', JSON.stringify(tipos));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('prodModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal('prodModal');
});
</script>
@endsection
