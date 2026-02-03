@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Clientes')

@section('config_content')

<style>
    .client-type-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.2s;
    }
    
    .client-type-card:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border-color: #1a73e8;
    }
    
    .type-color {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 0 0 1px #e5e7eb;
        transition: all 0.2s;
    }
    
    .type-color:hover {
        transform: scale(1.1);
    }
    
    .type-info {
        flex: 1;
    }
    
    .type-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .type-description {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    
    .type-stats {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 8px 12px;
        background: #f9fafb;
        border-radius: 6px;
    }
    
    .stat-item {
        font-size: 13px;
        color: #6b7280;
    }
    
    .stat-number {
        font-weight: 600;
        color: #1f2937;
    }
    
    .type-actions {
        display: flex;
        gap: 8px;
    }
    
    .icon-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .icon-btn.edit {
        background: #eff6ff;
        color: #1a73e8;
    }
    
    .icon-btn.edit:hover {
        background: #1a73e8;
        color: white;
    }
    
    .icon-btn.delete {
        background: #fee2e2;
        color: #ef4444;
    }
    
    .icon-btn.delete:hover {
        background: #ef4444;
        color: white;
    }
    
    .add-type-btn {
        width: 100%;
        padding: 20px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        color: #6b7280;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .add-type-btn:hover {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-color: #1a73e8;
        color: #1a73e8;
    }
</style>

<div class="config-card">
    <div style="margin-bottom: 30px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">
            Organiza a tus clientes por categorías para personalizar tu atención y estrategias de marketing
        </p>
    </div>

    <!-- Lista de Tipos Existentes -->
    <div id="client-types-list">
        @foreach($tipos as $tipo)
        <div class="client-type-card">
            <div class="type-color" style="background-color: {{ $tipo['color'] }}" title="Color del tipo"></div>
            <div class="type-info">
                <h4 class="type-name">{{ $tipo['nombre'] }}</h4>
                <p class="type-description">{{ $tipo['descripcion'] ?? '' }}</p>
            </div>
            <div class="type-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $tipo['clientes'] ?? 0 }}</span> clientes
                </div>
                <div class="stat-item">
                    Descuento: <span class="stat-number">{{ $tipo['descuento'] ?? 0 }}%</span>
                </div>
            </div>
            <div class="type-actions">
                <button class="icon-btn edit" onclick="editType({{ json_encode($tipo) }})" title="Editar">✏️</button>
                <button class="icon-btn delete" onclick="deleteType({{ $tipo['id'] }}, '{{ $tipo['nombre'] }}')" title="Eliminar">🗑️</button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Botón Agregar Nuevo Tipo -->
    <button class="add-type-btn" onclick="openAddTypeModal()">
        ➕ Agregar Nuevo Tipo de Cliente
    </button>

    <div class="btn-group" style="margin-top: 30px;">
        <button type="button" class="btn btn-primary" onclick="saveTypes()">Guardar Cambios</button>
        <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<!-- Modal para Agregar/Editar Tipo -->
<div id="typeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Tipo de Cliente</h3>
            <button class="modal-close" onclick="closeModal('typeModal')">×</button>
        </div>
        
        <form id="typeForm">
            <input type="hidden" id="typeId">
            <div class="form-group">
                <label>Nombre del Tipo</label>
                <input type="text" id="typeName" class="form-control" placeholder="Ej: VIP, Premium, etc.">
            </div>
            
            <div class="form-group">
                <label>Descripción</label>
                <textarea id="typeDescription" class="form-control" placeholder="Descripción breve del tipo de cliente"></textarea>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Color Identificador</label>
                    <input type="color" id="typeColor" class="form-control" value="#1a73e8">
                </div>
                
                <div class="form-group">
                    <label>Descuento por Defecto (%)</label>
                    <input type="number" id="typeDiscount" class="form-control" placeholder="0" min="0" max="100">
                </div>
            </div>
            
            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" id="typeApplyAuto">
                    <span>Aplicar descuento automáticamente en ventas</span>
                </label>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('typeModal')">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveNewType()">Guardar Tipo</button>
            </div>
        </form>
    </div>
</div>

<script>
let tipos = {!! json_encode($tipos) !!};

function openAddTypeModal() {
    document.querySelector('#typeModal h3').textContent = 'Nuevo Tipo de Cliente';
    document.getElementById('typeId').value = '';
    document.getElementById('typeForm').reset();
    document.getElementById('typeModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function editType(tipo) {
    document.querySelector('#typeModal h3').textContent = 'Editar Tipo de Cliente';
    document.getElementById('typeId').value = tipo.id;
    document.getElementById('typeName').value = tipo.nombre;
    document.getElementById('typeDescription').value = tipo.descripcion || '';
    document.getElementById('typeColor').value = tipo.color || '#1a73e8';
    document.getElementById('typeDiscount').value = tipo.descuento || 0;
    document.getElementById('typeApplyAuto').checked = tipo.aplicar_auto || false;
    document.getElementById('typeModal').classList.add('active');
}

function deleteType(id, name) {
    if (confirm('¿Estás seguro de eliminar el tipo "' + name + '"?')) {
        tipos = tipos.filter(t => t.id !== id);
        saveTypes();
    }
}

function saveNewType() {
    const id = document.getElementById('typeId').value;
    const tipo = {
        id: id ? parseInt(id) : (tipos.length > 0 ? Math.max(...tipos.map(t => t.id)) + 1 : 1),
        nombre: document.getElementById('typeName').value,
        descripcion: document.getElementById('typeDescription').value,
        color: document.getElementById('typeColor').value,
        descuento: document.getElementById('typeDiscount').value,
        aplicar_auto: document.getElementById('typeApplyAuto').checked,
        clientes: id ? tipos.find(t => t.id == id).clientes : 0
    };

    if (id) {
        const index = tipos.findIndex(t => t.id == id);
        tipos[index] = tipo;
    } else {
        tipos.push(tipo);
    }

    saveTypes();
}

function saveTypes() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('tipos_clientes', JSON.stringify(tipos));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('typeModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});
</script>

@endsection