@extends('admin.configuration._layout')

@section('config_title', 'Conceptos de Caja')

@section('config_content')

<style>
    .config-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f3f4f6;
        padding: 32px;
    }

    .tabs-nav {
        display: flex;
        gap: 30px;
        margin-bottom: 32px;
        border-bottom: 2px solid #f3f4f6;
    }

    .tab-link {
        padding: 12px 0;
        font-weight: 700;
        color: #6b7280;
        text-decoration: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
        cursor: pointer;
    }

    .tab-link.active {
        color: #6366f1;
        border-bottom-color: #6366f1;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 16px;
        background: #f9fafb;
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .badge-ingresos { background: #dcfce7; color: #166534; }
    .badge-gastos { background: #fee2e2; color: #991b1b; }
    .badge-retiros { background: #fef3c7; color: #92400e; }

    .btn-add {
        background: #111827;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-btn {
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #f9fafb;
        border-color: #6366f1;
        color: #6366f1;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        overflow: hidden;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: #111827; margin: 0 0 4px 0;">Conceptos Contables</h2>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Define los tipos de movimientos para tu caja.</p>
        </div>
        <button class="btn-add" onclick="openModal()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nuevo Concepto
        </button>
    </div>

    <div class="tabs-nav">
        <div class="tab-link active">Todos los Conceptos</div>
        <div class="tab-link">Entidades de Seguridad</div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Nombre del Concepto</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th style="text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($conceptos as $index => $item)
            <tr>
                <td style="font-weight: 700; color: #111827;">{{ $item['nombre'] }}</td>
                <td>
                    <span class="badge badge-{{ strtolower($item['categoria'] ?? 'gastos') }}">
                        {{ $item['categoria'] ?? 'Gastos' }}
                    </span>
                </td>
                <td>
                    <span style="display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ ($item['activo'] ?? true) ? '#10b981' : '#9ca3af' }};"></span>
                        {{ ($item['activo'] ?? true) ? 'Habilitado' : 'Deshabilitado' }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <div style="display: flex; gap: 8px; justify-content: flex-end;">
                        <button class="action-btn" onclick='editConcepto({{ $index }}, @json($item))'>
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button class="action-btn" onclick="deleteConcepto({{ $index }})" style="color: #ef4444;">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="padding: 60px; text-align: center; color: #9ca3af;">
                    <p>No has creado ningún concepto todavía.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- MODAL -->
<div id="conceptoModal" class="modal">
    <div class="modal-content">
        <div style="padding: 24px 32px; background: #f9fafb; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="modalTitle" style="font-weight: 800; color: #111827; margin: 0;">Nuevo Concepto</h3>
            <button onclick="closeModal()" style="background:none; border:none; font-size:24px; cursor:pointer; color:#9ca3af;">&times;</button>
        </div>
        <form action="{{ url('admin/configuration/conceptos/save') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="index" id="conceptoIndex">
            <div style="padding: 32px;">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display:block; font-weight:700; color:#374151; margin-bottom:8px;">Nombre del Concepto</label>
                    <input type="text" name="nombre" id="inputNombre" class="form-control" placeholder="Eje: Pago de Arriendo, Venta de Producto..." required>
                </div>
                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display:block; font-weight:700; color:#374151; margin-bottom:8px;">Categoría</label>
                    <select name="categoria" id="inputCategoria" class="form-control" required>
                        <option value="Gastos">Gastos (Egresos)</option>
                        <option value="Ingresos">Ingresos</option>
                        <option value="Retiros">Retiros de Socios / Caja</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="switch-container">
                        <input type="checkbox" name="activo" id="inputActivo" value="1" checked>
                        <span style="font-weight: 600; margin-left: 10px;">Concepto Habilitado</span>
                    </label>
                </div>
            </div>
            <div style="padding: 24px 32px; background: #f9fafb; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn" onclick="closeModal()" style="background:#fff; border:1px solid #e5e7eb; color:#374151; font-weight:600; padding:10px 20px; border-radius:10px; cursor:pointer;">Cancelar</button>
                <button type="submit" class="btn" style="background:#111827; color:white; font-weight:700; padding:10px 30px; border-radius:10px; border:none; cursor:pointer;">Guardar Concepto</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').innerText = 'Nuevo Concepto';
    document.getElementById('conceptoIndex').value = '';
    document.getElementById('inputNombre').value = '';
    document.getElementById('inputCategoria').value = 'Gastos';
    document.getElementById('inputActivo').checked = true;
    document.getElementById('conceptoModal').style.display = 'flex';
}

function editConcepto(index, data) {
    document.getElementById('modalTitle').innerText = 'Editar Concepto';
    document.getElementById('conceptoIndex').value = index;
    document.getElementById('inputNombre').value = data.nombre;
    document.getElementById('inputCategoria').value = data.categoria || 'Gastos';
    document.getElementById('inputActivo').checked = data.activo;
    document.getElementById('conceptoModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('conceptoModal').style.display = 'none';
}

function deleteConcepto(index) {
    if(confirm('¿Estás seguro de eliminar este concepto?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("admin/configuration/conceptos/delete") }}';
        form.innerHTML = `
            {{ csrf_field() }}
            <input type="hidden" name="index" value="${index}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection