@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Traslados de Inventario</h1>
        <div class="breadcrumb">{{ trans('messages.home') }} / Inventario / Traslados</div>
    </div>

    <div class="actions-bar">
        <a href="{{ url('admin/traslados/create') }}" class="btn btn-primary">+ Nuevo Traslado</a>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Pendientes</h3>
            <div class="stat-value" style="color:#f59e0b;">{{ $stats['pending'] }}</div>
            <div class="stat-desc">Por enviar</div>
        </div>
        <div class="stat-card">
            <h3>En Tránsito</h3>
            <div class="stat-value" style="color:#3b82f6;">{{ $stats['in_transit'] }}</div>
            <div class="stat-desc">En camino</div>
        </div>
        <div class="stat-card">
            <h3>Completados</h3>
            <div class="stat-value" style="color:#10b981;">{{ $stats['completed'] }}</div>
            <div class="stat-desc">Este mes</div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="card-table">
        <div class="card-header">
            <h3>Historial de Traslados</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $t)
                <tr>
                    <td><span class="transfer-number">#{{ $t->transfer_number }}</span></td>
                    <td>{{ date('d M Y', strtotime($t->transfer_date)) }}</td>
                    <td>{{ $t->from_location }}</td>
                    <td>{{ $t->to_location }}</td>
                    <td>{{ $t->items_count ?? 0 }} items</td>
                    <td>
                        @if($t->status == 'completed')
                            <span class="status-badge completed">Completado</span>
                        @elseif($t->status == 'in_transit')
                            <span class="status-badge transit">En Tránsito</span>
                        @elseif($t->status == 'cancelled')
                            <span class="status-badge cancelled">Cancelado</span>
                        @else
                            <span class="status-badge pending">Pendiente</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-icon" title="Ver Detalle" onclick="alert('Detalle del traslado')">👁️</button>
                        @if($t->status == 'pending')
                        <button class="btn-icon" title="Enviar" onclick="alert('Marcar como en tránsito')">🚚</button>
                        @elseif($t->status == 'in_transit')
                        <button class="btn-icon" title="Completar" onclick="alert('Marcar como completado')">✅</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <div style="font-size:32px; margin-bottom:10px;">📦</div>
                        <p>No hay traslados registrados</p>
                        <a href="{{ url('admin/traslados/create') }}" class="btn btn-primary" style="margin-top:15px;">Crear Primer Traslado</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .actions-bar { margin: 25px 0; }
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
    .btn-primary { background: #1a73e8; color: white; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 12px; padding: 25px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 36px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1f2937; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    .data-table tr:hover { background: #f9fafb; }
    
    .transfer-number { background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 600; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.completed { background: #dcfce7; color: #166534; }
    .status-badge.transit { background: #dbeafe; color: #1e40af; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
    
    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; padding: 5px; opacity: 0.6; transition: all 0.2s; }
    .btn-icon:hover { opacity: 1; }
    
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
</style>
@endsection
