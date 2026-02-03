@extends('admin.configuration._layout')

@section('config_title', 'Métodos de Pago')

@section('config_content')
<style>
    .payment-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
    .payment-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s;
    }
    .payment-card:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .payment-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    .payment-info {
        flex: 1;
    }
    .payment-name {
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .status-badge {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 10px;
        font-weight: 600;
        text-transform: uppercase;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 4px;">Medios de Pago</h3>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Configura los métodos que tus clientes pueden usar para pagar tus servicios y productos.</p>
        </div>
        <button class="btn btn-primary" onclick="openPaymentModal()">➕ Nuevo Método</button>
    </div>

    <div id="payment-list" class="payment-grid">
        @foreach($formas_pago as $forma)
        <div class="payment-card">
            <div class="payment-icon-wrapper" style="background: {{ $forma['color'] ?? '#3b82f6' }}">
                {{ $forma['icono'] ?? '💳' }}
            </div>
            <div class="payment-info">
                <h4 class="payment-name">{{ $forma['nombre'] }}</h4>
                <span class="status-badge" style="{{ ($forma['activo'] ?? true) ? 'background:#dcfce7; color:#166534;' : 'background:#fee2e2; color:#991b1b;' }}">
                    {{ ($forma['activo'] ?? true) ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
            <div style="display: flex; gap: 8px;">
                <button class="btn-edit" onclick="editPayment({{ json_encode($forma) }})">✏️</button>
                <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deletePayment({{ $forma['id'] }})">🗑️</button>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <div class="modal-header">
            <h3>Nuevo Método de Pago</h3>
            <button class="modal-close" onclick="closeModal('paymentModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="paymentForm">
                <input type="hidden" id="paymentId">
                <div class="form-group">
                    <label>Nombre del Método *</label>
                    <input type="text" id="paymentName" class="form-control" placeholder="Ej: Nequi / Daviplata" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Icono (Emoji)</label>
                        <input type="text" id="paymentIcon" class="form-control" placeholder="📱" maxlength="2">
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <input type="color" id="paymentColor" class="form-control" value="#3b82f6" style="height: 42px; padding: 5px;">
                    </div>
                </div>
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" id="paymentActive" checked>
                        <span>Habilitar para ventas en POS</span>
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('paymentModal')">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Método</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let formas = {!! json_encode($formas_pago) !!};

function openPaymentModal() {
    document.querySelector('#paymentModal h3').textContent = 'Nuevo Método';
    document.getElementById('paymentId').value = '';
    document.getElementById('paymentForm').reset();
    document.getElementById('paymentActive').checked = true;
    document.getElementById('paymentModal').classList.add('active');
}

function closeModal(id) { document.getElementById(id).classList.remove('active'); }

function editPayment(forma) {
    document.querySelector('#paymentModal h3').textContent = 'Editar Método';
    document.getElementById('paymentId').value = forma.id;
    document.getElementById('paymentName').value = forma.nombre;
    document.getElementById('paymentIcon').value = forma.icono || '💳';
    document.getElementById('paymentColor').value = forma.color || '#3b82f6';
    document.getElementById('paymentActive').checked = forma.activo !== false;
    document.getElementById('paymentModal').classList.add('active');
}

function deletePayment(id) {
    if (!confirm('¿Eliminar este método de pago?')) return;
    formas = formas.filter(f => f.id !== id);
    saveFormas();
}

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('paymentId').value;
    const forma = {
        id: id ? parseInt(id) : (formas.length > 0 ? Math.max(...formas.map(f => f.id)) + 1 : 1),
        nombre: document.getElementById('paymentName').value,
        icono: document.getElementById('paymentIcon').value || '💳',
        color: document.getElementById('paymentColor').value,
        activo: document.getElementById('paymentActive').checked
    };

    if (id) {
        const index = formas.findIndex(f => f.id == id);
        formas[index] = forma;
    } else {
        formas.push(forma);
    }
    saveFormas();
});

function saveFormas() {
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('formas_pago_json', JSON.stringify(formas));

    fetch('{{ url("admin/configuration/save") }}', {
        method: 'POST',
        body: formData
    })
    .then(() => location.reload())
    .catch(err => alert('Error al guardar'));
}

document.getElementById('paymentModal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeModal('paymentModal');
});
</script>
@endsection
