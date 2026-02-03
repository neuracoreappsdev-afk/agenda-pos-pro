@extends('admin.configuration._layout')

@section('config_title', 'Notificaciones')

@section('config_content')

<style>
    .page-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 25px 0;
    }

    /* Filters Section */
    .filters-row {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .filter-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #374151;
        background: white;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .btn-filter {
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #f3f4f6;
    }

    .btn-consultar {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }

    .btn-consultar:hover {
        background: #1d4ed8;
    }

    .btn-add {
        background: #2563eb;
        color: white;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add:hover {
        background: #1d4ed8;
    }

    /* Table Styles */
    .notifications-table-container {
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .notifications-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 1200px;
    }

    .notifications-table thead {
        background: #f9fafb;
    }

    .notifications-table th {
        text-align: left;
        padding: 14px 12px;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }

    .notifications-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: middle;
    }

    .notifications-table tbody tr:hover {
        background: #f9fafb;
    }

    .notifications-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Column specific styles */
    .col-num {
        width: 40px;
        text-align: center;
        color: #6b7280;
    }

    .col-fecha {
        white-space: nowrap;
    }

    .col-hora {
        white-space: nowrap;
    }

    .col-titulo {
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #1a73e8;
        cursor: pointer;
    }

    .col-titulo:hover {
        text-decoration: underline;
    }

    .col-cliente {
        max-width: 200px;
        color: #1a73e8;
    }

    .col-identificacion, .col-celular {
        white-space: nowrap;
    }

    .col-sede {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        color: #1a73e8;
    }

    /* Medio badges */
    .medio-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .medio-whatsapp {
        background: #dcfce7;
        color: #166534;
    }

    .medio-sms {
        background: #dbeafe;
        color: #1e40af;
    }

    .medio-email {
        background: #fef3c7;
        color: #92400e;
    }

    /* Status indicators */
    .status-container {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .status-sent {
        background: #10b981;
    }

    .status-pending {
        background: #f59e0b;
    }

    .status-failed {
        background: #ef4444;
    }

    .status-text-success {
        color: #10b981;
        font-weight: 500;
    }

    .status-text-warning {
        color: #f59e0b;
    }

    .status-text-secondary {
        color: #9ca3af;
        font-size: 11px;
    }

    /* Action buttons */
    .btn-action {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        border: 1px solid;
        transition: all 0.2s;
    }

    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-delete:hover {
        background: #fee2e2;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .pagination-info {
        font-size: 13px;
        color: #6b7280;
    }

    .pagination-buttons {
        display: flex;
        gap: 5px;
    }

    .page-btn {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        background: white;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
    }

    .page-btn:hover {
        background: #f3f4f6;
    }

    .page-btn.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
</style>

<div class="page-container">
    <h1 class="page-title">Notificaciones</h1>

    <!-- Filters -->
    <div class="filters-row">
        <div class="filter-group">
            <label class="filter-label">Rango de Fechas</label>
            <select class="filter-select" id="filterFecha">
                <option value="hoy">Hoy</option>
                <option value="ayer" selected>Ayer</option>
                <option value="ultima_semana">Última Semana</option>
                <option value="ultimo_mes">Último Mes</option>
                <option value="personalizado">Personalizado</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Sede</label>
            <select class="filter-select" id="filterSede">
                <option value="">Sedes ...</option>
                <option value="1">Holguines Trade Center</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="button" class="btn-filter" onclick="toggleMoreFilters()">
                <span>⚙️</span> Más Filtros
            </button>
            <button type="button" class="btn-filter btn-consultar" onclick="consultarNotificaciones()">
                Consultar
            </button>
            <button type="button" class="btn-add" title="Agregar notificación manual">
                +
            </button>
        </div>
    </div>

    <!-- More Filters (hidden by default) -->
    <div id="moreFilters" style="display: none; margin-bottom: 20px; padding: 15px; background: #f9fafb; border-radius: 8px;">
        <div class="filters-row" style="margin-bottom: 0;">
            <div class="filter-group">
                <label class="filter-label">Medio de Notificación</label>
                <select class="filter-select" id="filterMedio">
                    <option value="">Todos</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="sms">SMS</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Estado</label>
                <select class="filter-select" id="filterEstado">
                    <option value="">Todos</option>
                    <option value="enviado">Enviados</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="fallido">Fallidos</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Cliente</label>
                <input type="text" class="filter-select" id="filterCliente" placeholder="Buscar por nombre...">
            </div>
        </div>
    </div>

    <!-- Notifications Table -->
    <div class="notifications-table-container">
        <table class="notifications-table">
            <thead>
                <tr>
                    <th class="col-num">#</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Título</th>
                    <th>Cliente</th>
                    <th>Identificación Cliente</th>
                    <th>Celular Cliente</th>
                    <th>Sede</th>
                    <th>Medio de Recordatorio</th>
                    <th>Enviado</th>
                    <th>Enviado el</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($notificaciones as $index => $notif)
                <tr>
                    <td class="col-num">{{ $index + 1 }}</td>
                    <td class="col-fecha">{{ $notif['fecha'] }}</td>
                    <td class="col-hora">{{ $notif['hora'] }}</td>
                    <td class="col-titulo" title="{{ $notif['titulo'] }}">{{ $notif['titulo'] }}</td>
                    <td class="col-cliente">{{ $notif['cliente_nombre'] }}</td>
                    <td class="col-identificacion">{{ $notif['cliente_identificacion'] }}</td>
                    <td class="col-celular">{{ $notif['cliente_celular'] }}</td>
                    <td class="col-sede" title="{{ $notif['sede'] }}">{{ $notif['sede'] }}</td>
                    <td>
                        @if($notif['medio'] == 'Whatsapp')
                            <span class="medio-badge medio-whatsapp">Whatsapp</span>
                        @elseif($notif['medio'] == 'Sms')
                            <span class="medio-badge medio-sms">Sms</span>
                        @else
                            <span class="medio-badge medio-email">Email</span>
                        @endif
                    </td>
                    <td>
                        <div class="status-container">
                            @if($notif['enviado'])
                                <span class="status-indicator">
                                    <span class="status-dot status-sent"></span>
                                    <span class="status-text-success">✓Enviado</span>
                                </span>
                            @else
                                <span class="status-indicator">
                                    <span class="status-dot status-pending"></span>
                                    <span class="status-text-warning">{{ $notif['enviado_mensaje'] }}</span>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($notif['enviado_el'])
                            {{ $notif['enviado_el'] }}
                        @else
                            <span style="color: #9ca3af;">-</span>
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn-action btn-delete" onclick="eliminarNotificacion({{ $notif['id'] }})">
                            Eliminar
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">
                        <div class="empty-state">
                            <div class="empty-icon">📭</div>
                            <h4>No hay notificaciones</h4>
                            <p>No se encontraron notificaciones con los filtros seleccionados</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Mostrando <strong>{{ count($notificaciones) }}</strong> de <strong>{{ count($notificaciones) }}</strong> notificaciones
        </div>
        <div class="pagination-buttons">
            <button class="page-btn">« Anterior</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">Siguiente »</button>
        </div>
    </div>
</div>

<script>
    function toggleMoreFilters() {
        const filters = document.getElementById('moreFilters');
        filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
    }

    function consultarNotificaciones() {
        const fecha = document.getElementById('filterFecha').value;
        const sede = document.getElementById('filterSede').value;
        const medio = document.getElementById('filterMedio')?.value || '';
        const estado = document.getElementById('filterEstado')?.value || '';
        const cliente = document.getElementById('filterCliente')?.value || '';
        
        // Build query params and reload or fetch via AJAX
        const params = new URLSearchParams({
            fecha: fecha,
            sede: sede,
            medio: medio,
            estado: estado,
            cliente: cliente
        });
        
        // For now, just show toast - in production this would reload with filters
        showToast('Consultando notificaciones...', 'info');
        
        // window.location.href = '{{ url("admin/configuration/listado-notificaciones") }}?' + params.toString();
    }

    function eliminarNotificacion(id) {
        if (confirm('¿Está seguro de eliminar esta notificación?')) {
            // AJAX call to delete
            showToast('Notificación eliminada', 'success');
        }
    }
</script>

@endsection