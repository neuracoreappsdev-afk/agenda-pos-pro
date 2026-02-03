@extends('admin/dashboard_layout')

@section('content')
<div class="app-main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ trans('messages.providers') }}</h1>
            <div class="breadcrumb">
                <a href="{{ url('admin/dashboard') }}">{{ trans('messages.home') }}</a> / 
                <a href="{{ url('admin/compras') }}">{{ trans('messages.purchases') }}</a> / 
                {{ trans('messages.providers') }}
            </div>
        </div>
        <div>
            <button class="btn btn-primary" onclick="openProviderModal()">
                <span>+</span> Nuevo Proveedor
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Proveedores</div>
            <div class="stat-value">{{ count($providers) }}</div>
            <div class="stat-trend">Registrados en sistema</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Compras del Mes</div>
            <div class="stat-value">${{ number_format($stats['purchases_month'], 0) }}</div>
            <div class="stat-trend">{{ date('F Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Saldo Pendiente</div>
            <div class="stat-value" style="color:#ef4444;">${{ number_format($stats['pending_balance'], 0) }}</div>
            <div class="stat-trend">Por pagar</div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="{{ url('admin/compras/proveedores') }}" style="display: flex; gap: 10px; width: 100%;">
            <input type="text" name="search" class="search-input" placeholder="Buscar por nombre, NIT o ciudad..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-secondary">Buscar</button>
        </form>
    </div>

    <!-- Table -->
    <div class="content-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Contacto</th>
                    <th>NIT</th>
                    <th>Ciudad</th>
                    <th>Condiciones</th>
                    <th>Estado</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($providers as $p)
                <tr>
                    <td>
                        <div class="provider-name">{{ $p->company_name }}</div>
                        <div class="provider-email">{{ $p->email }}</div>
                    </td>
                    <td>
                        <div>{{ $p->contact_name ?: 'N/A' }}</div>
                        <div class="contact-phone">{{ $p->phone }}</div>
                    </td>
                    <td><span class="nit-badge">{{ $p->nit ?: 'Sin NIT' }}</span></td>
                    <td>{{ $p->city ?: 'N/A' }}</td>
                    <td><span class="terms-badge">{{ $p->payment_terms }}</span></td>
                    <td>
                        <span class="status-badge {{ $p->active ? 'active' : 'inactive' }}">
                            {{ $p->active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-icon" title="Editar" onclick="editProvider({{ $p->id }})">✏️</button>
                        <button class="btn-icon" title="Ver Historial" onclick="window.location='{{ url('admin/compras/facturas?provider_id=' . $p->id) }}'">📄</button>
                        <button class="btn-icon danger" title="Eliminar" onclick="deleteProvider({{ $p->id }})">🗑️</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px; color: #9ca3af;">
                        <div style="font-size:24px; margin-bottom:10px;">🏢</div>
                        No hay proveedores registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Agregar/Editar Proveedor -->
<div id="providerModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Nuevo Proveedor</h2>
            <button class="modal-close" onclick="closeProviderModal()">&times;</button>
        </div>
        <form id="providerForm" method="POST" action="{{ url('admin/compras/proveedores/store') }}">
            {{ csrf_field() }}
            <input type="hidden" id="providerId" name="id" value="">
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Nombre de Empresa *</label>
                    <input type="text" name="company_name" id="company_name" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Contacto</label>
                    <input type="text" name="contact_name" id="contact_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>NIT / Cédula</label>
                    <input type="text" name="nit" id="nit" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Ciudad</label>
                    <input type="text" name="city" id="city" class="form-control">
                </div>
                <div class="form-group">
                    <label>Condiciones de Pago</label>
                    <select name="payment_terms" id="payment_terms" class="form-control">
                        <option value="Contado">Contado</option>
                        <option value="15 días">15 días</option>
                        <option value="30 días">30 días</option>
                        <option value="45 días">45 días</option>
                        <option value="60 días">60 días</option>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Dirección</label>
                    <input type="text" name="address" id="address" class="form-control">
                </div>
                <div class="form-group full-width">
                    <label>Notas</label>
                    <textarea name="notes" id="notes" class="form-control" rows="2"></textarea>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeProviderModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #111827; margin: 0; }
    .breadcrumb { color: #6b7280; font-size: 13px; margin-top: 5px; }
    .breadcrumb a { color: #1a73e8; text-decoration: none; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .stat-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; }
    .stat-value { font-size: 28px; font-weight: 800; color: #1f2937; margin: 5px 0; }
    .stat-trend { font-size: 12px; color: #9ca3af; }

    .search-bar { background: white; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e5e7eb; }
    .search-input { flex: 1; padding: 10px 15px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }

    .content-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; vertical-align: middle; }
    .data-table tr:hover { background: #f9fafb; }

    .provider-name { font-weight: 700; color: #1f2937; }
    .provider-email { font-size: 12px; color: #6b7280; }
    .contact-phone { font-size: 12px; color: #6b7280; }
    .nit-badge { background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; }
    .terms-badge { background: #dbeafe; color: #1e40af; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }

    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; padding: 5px; opacity: 0.6; transition: all 0.2s; }
    .btn-icon:hover { opacity: 1; transform: scale(1.1); }
    .btn-icon.danger:hover { opacity: 1; }
    
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
    .btn-primary { background: #1a73e8; color: white; }
    .btn-primary:hover { background: #1557b0; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

    /* Modal */
    .modal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
    .modal-content { background: white; border-radius: 16px; width: 600px; max-width: 90%; max-height: 90vh; overflow-y: auto; }
    .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .modal-header h2 { margin: 0; font-size: 18px; }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280; }
    .modal-footer { padding: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 10px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { font-size: 13px; font-weight: 600; color: #374151; }
    .form-control { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.1); }
</style>

<script>
function openProviderModal() {
    document.getElementById('modalTitle').textContent = 'Nuevo Proveedor';
    document.getElementById('providerForm').reset();
    document.getElementById('providerId').value = '';
    document.getElementById('providerModal').style.display = 'flex';
}

function closeProviderModal() {
    document.getElementById('providerModal').style.display = 'none';
}

function editProvider(id) {
    fetch('{{ url("admin/compras/proveedores") }}/' + id + '/json')
        .then(r => r.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Editar Proveedor';
            document.getElementById('providerId').value = data.id;
            document.getElementById('company_name').value = data.company_name || '';
            document.getElementById('contact_name').value = data.contact_name || '';
            document.getElementById('nit').value = data.nit || '';
            document.getElementById('email').value = data.email || '';
            document.getElementById('phone').value = data.phone || '';
            document.getElementById('city').value = data.city || '';
            document.getElementById('address').value = data.address || '';
            document.getElementById('payment_terms').value = data.payment_terms || 'Contado';
            document.getElementById('notes').value = data.notes || '';
            document.getElementById('providerModal').style.display = 'flex';
        });
}

function deleteProvider(id) {
    if (confirm('¿Está seguro de eliminar este proveedor?')) {
        window.location = '{{ url("admin/compras/proveedores") }}/' + id + '/delete';
    }
}
</script>
@endsection
