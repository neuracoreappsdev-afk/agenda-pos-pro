@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Comisión Detallada</h1>
        <div class="breadcrumb">Informes / Especialistas / Detalle Comisiones</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/participacion-detallada') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Ver Detalle</button>
        </form>
    </div>

    <!-- KPI Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Comisiones</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($items->sum('commission_value'), 0) }}</div>
            <div class="stat-desc">Venta asistida devengada</div>
        </div>
        <div class="stat-card">
            <h3>Items Comisionados</h3>
            <div class="stat-value">{{ $items->count() }}</div>
            <div class="stat-desc">Servicios y productos</div>
        </div>
        <div class="stat-card">
            <h3>Comisión Promedio</h3>
            <div class="stat-value">$ {{ number_format($items->count() > 0 ? $items->sum('commission_value') / $items->count() : 0, 0) }}</div>
            <div class="stat-desc">Por cada ítem</div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Auditoría de Comisiones por Ítem</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th>Fecha / Venta</th>
                    <th>Ítem / Servicio</th>
                    <th style="text-align:right">Valor Ítem</th>
                    <th style="text-align:right">Comisión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $item->specialist ? $item->specialist->name : 'N/A' }}</div>
                        <div style="font-size:11px; color:#6b7280;">ID: {{ $item->specialist_id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ date('d M, Y', strtotime($item->sale->sale_date)) }}</div>
                        <div style="font-size:11px; color:#1a73e8;">Venta #{{ $item->sale_id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#374151;">{{ $item->item_name }}</div>
                        <span class="badge-type {{ $item->item_type }}">{{ ucfirst($item->item_type) }}</span>
                    </td>
                    <td style="text-align:right; font-weight:600;">$ {{ number_format($item->total, 0) }}</td>
                    <td style="text-align:right; font-weight:800; color:#10b981;">$ {{ number_format($item->commission_value, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:50px; color:#9ca3af;">No hay comisiones detalladas para el periodo seleccionado</td>
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
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .group { display: flex; flex-direction: column; gap: 5px; }
    .group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    .data-table tr:hover { background: #f9fafb; }

    .badge-type { font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; }
    .badge-type.servicio { background: #eff6ff; color: #1d4ed8; }
    .badge-type.producto { background: #f0fdf4; color: #15803d; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection
