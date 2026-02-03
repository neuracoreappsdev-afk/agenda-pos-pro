@extends('admin.configuration._layout')

@section('config_title', 'Descuentos y Promociones')

@section('config_content')

<style>
    .discount-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        position: relative;
        transition: all 0.2s;
    }
    
    .discount-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    
    .discount-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .discount-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .discount-info {
        flex: 1;
    }
    
    .discount-name {
        font-size: 17px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .discount-description {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    
    .discount-value {
        font-size: 24px;
        font-weight: 700;
        color: #10b981;
    }
    
    .discount-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
        margin: 15px 0;
    }
    
    .detail-box {
        text-align: center;
    }
    
    .detail-label {
        font-size: 11px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .detail-value {
        font-size: 14px;
        color: #1f2937;
        font-weight: 600;
    }
    
    .discount-status {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .status-scheduled {
        background: #fef3c7;
        color: #92400e;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">
            Crea y gestiona descuentos y promociones para atraer y fidelizar clientes
        </p>
        <button class="btn btn-primary" onclick="openDiscountModal()">
            ➕ Nuevo Descuento
        </button>
    </div>

    @forelse($descuentos as $desc)
    <div class="discount-card" style="{{ !($desc['active'] ?? true) ? 'opacity: 0.6;' : '' }}">
        <span class="discount-status {{ ($desc['active'] ?? true) ? 'status-active' : 'status-inactive' }}">
            {{ ($desc['active'] ?? true) ? 'ACTIVO' : 'INACTIVO' }}
        </span>
        <div class="discount-header">
            <div class="discount-icon">{{ $desc['icon'] ?? '🏷️' }}</div>
            <div class="discount-info">
                <h4 class="discount-name">{{ $desc['nombre'] }}</h4>
                <p class="discount-description">{{ $desc['descripcion'] ?? '' }}</p>
            </div>
            <div class="discount-value">{{ $desc['valor'] }}{{ ($desc['tipo'] ?? 'porcentaje') == 'porcentaje' ? '%' : '$' }}</div>
        </div>
        
        <div class="discount-details">
            <div class="detail-box">
                <div class="detail-label">Código</div>
                <div class="detail-value">{{ $desc['codigo'] ?: 'N/A' }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label">Usos</div>
                <div class="detail-value">{{ $desc['usos'] ?? 0 }} / {{ $desc['limite'] ?: '∞' }}</div>
            </div>
            <div class="detail-box">
                <div class="detail-label">Vigencia</div>
                <div class="detail-value">
                    @if($desc['fecha_inicio'] && $desc['fecha_fin'])
                        {{ $desc['fecha_inicio'] }} - {{ $desc['fecha_fin'] }}
                    @else
                        Permanente
                    @endif
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-secondary" style="flex: 1;" onclick="editDiscount({{ json_encode($desc) }})">✏️ Editar</button>
            <button class="btn btn-secondary" onclick="deleteDiscount({{ $desc['id'] }})">🗑️</button>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 40px; color: #6b7280; background: #f9fafb; border-radius: 12px; border: 2px dashed #e5e7eb;">
        <p>No hay descuentos configurados. ¡Crea el primero!</p>
    </div>
    @endforelse
</div>

<!-- Modal Crear/Editar Descuento -->
<div id="discountModal" class="modal">
    <div class="modal-content" style="max-width: 600px; border-radius: 16px;">
        <div class="modal-header" style="background: white; padding: 25px 30px; border-bottom: 1px solid #f3f4f6;">
            <h3 style="font-size: 20px; font-weight: 800; color: #111827;">Nuevo Descuento</h3>
            <button class="modal-close" onclick="closeModal('discountModal')" style="font-size: 28px;">×</button>
        </div>
        
        <div class="modal-body" style="padding: 30px;">
            <form id="discountForm">
                <input type="hidden" id="discountId">
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Nombre del Descuento *</label>
                    <input type="text" id="discountName" class="form-control" placeholder="Ej: Promo Verano, Bienvenida..." required style="padding: 12px; border-color: #e5e7eb;">
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Descripción</label>
                    <textarea id="discountDescription" class="form-control" rows="2" placeholder="Explica brevemente de qué trata esta promo..." style="padding: 12px; border-color: #e5e7eb; min-height: 80px;"></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; background: #f9fafb; padding: 20px; border-radius: 12px; border: 1px solid #f3f4f6;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Tipo</label>
                        <select id="discountType" class="form-control" style="padding: 10px; border-color: #e5e7eb;">
                            <option value="porcentaje">Porcentaje (%)</option>
                            <option value="fijo">Valor Fijo ($)</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Valor *</label>
                        <input type="number" id="discountValue" class="form-control" placeholder="15" min="0" required style="padding: 10px; border-color: #e5e7eb;">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Código de Cupón (Opcional)</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="discountCode" class="form-control" placeholder="SUMMER2026" style="padding: 12px; border-color: #e5e7eb; letter-spacing: 1px; font-weight: 700; text-transform: uppercase;">
                        <button type="button" class="btn" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db; font-weight: 600;" onclick="generateCode()">🎲 Generar</button>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Fecha Inicio</label>
                        <input type="date" id="discountStart" class="form-control" style="padding: 10px; border-color: #e5e7eb;">
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-weight: 600; color: #374151; margin-bottom: 8px;">Fecha Fin</label>
                        <input type="date" id="discountEnd" class="form-control" style="padding: 10px; border-color: #e5e7eb;">
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; background: #f9fafb; padding: 15px 20px; border-radius: 12px; margin-bottom: 30px;">
                    <div class="form-group" style="margin-bottom: 0; flex: 1; margin-right: 20px;">
                        <label style="font-weight: 600; color: #374151; margin-bottom: 4px; font-size: 13px;">Límite de Usos</label>
                        <input type="number" id="discountLimit" class="form-control" placeholder="∞" style="padding: 8px; border-color: #e5e7eb;">
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-weight: 600; color: #374151; font-size: 13px;">Activo</span>
                        <label class="switch">
                            <input type="checkbox" id="discountActive" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 12px;">
                    <button type="button" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; padding: 12px 25px;" onclick="closeModal('discountModal')">Cancelar</button>
                    <button type="button" class="btn btn-primary" style="padding: 12px 35px; font-weight: 700; box-shadow: 0 4px 6px rgba(26, 115, 232, 0.2);" onclick="saveNewDiscount()">Guardar Descuento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let descuentos = {!! json_encode($descuentos) !!};

function openDiscountModal() {
    document.querySelector('#discountModal h3').textContent = 'Nuevo Descuento';
    document.getElementById('discountId').value = '';
    document.getElementById('discountForm').reset();
    document.getElementById('discountModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function editDiscount(desc) {
    document.querySelector('#discountModal h3').textContent = 'Editar Descuento';
    document.getElementById('discountId').value = desc.id;
    document.getElementById('discountName').value = desc.nombre;
    document.getElementById('discountDescription').value = desc.descripcion || '';
    document.getElementById('discountType').value = desc.tipo || 'porcentaje';
    document.getElementById('discountValue').value = desc.valor || 0;
    document.getElementById('discountCode').value = desc.codigo || '';
    document.getElementById('discountStart').value = desc.fecha_inicio || '';
    document.getElementById('discountEnd').value = desc.fecha_fin || '';
    document.getElementById('discountLimit').value = desc.limite || '';
    document.getElementById('discountActive').checked = desc.active !== false;
    document.getElementById('discountModal').classList.add('active');
}

function deleteDiscount(id) {
    if (confirm('¿Eliminar este descuento?')) {
        descuentos = descuentos.filter(d => d.id !== id);
        saveDescuentos();
    }
}

function generateCode() {
    const code = Math.random().toString(36).substring(2, 10).toUpperCase();
    document.getElementById('discountCode').value = code;
}

function saveNewDiscount() {
    const id = document.getElementById('discountId').value;
    const desc = {
        id: id ? parseInt(id) : (descuentos.length > 0 ? Math.max(...descuentos.map(d => d.id)) + 1 : 1),
        nombre: document.getElementById('discountName').value,
        descripcion: document.getElementById('discountDescription').value,
        tipo: document.getElementById('discountType').value,
        valor: document.getElementById('discountValue').value,
        codigo: document.getElementById('discountCode').value,
        fecha_inicio: document.getElementById('discountStart').value,
        fecha_fin: document.getElementById('discountEnd').value,
        limite: document.getElementById('discountLimit').value,
        active: document.getElementById('discountActive').checked,
        usos: id ? (descuentos.find(d => d.id == id).usos || 0) : 0
    };

    if (id) {
        const index = descuentos.findIndex(d => d.id == id);
        descuentos[index] = desc;
    } else {
        descuentos.push(desc);
    }

    saveDescuentos();
}

function saveDescuentos() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('descuentos', JSON.stringify(descuentos));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('discountModal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('active');
});
</script>

@endsection