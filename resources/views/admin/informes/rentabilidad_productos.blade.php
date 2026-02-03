@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Rentabilidad de Productos</h1>
        <div class="breadcrumb">Informes / Información General / Rentabilidad Productos</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/rentabilidad-productos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Analizar Inventario</button>
        </form>
    </div>

    <!-- KPI Cards -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Ventas Brutas</h3>
            <div class="stat-value">$ {{ number_format($totalRev, 0) }}</div>
            <div class="stat-desc">Ingresos por productos</div>
        </div>
        <div class="stat-card">
            <h3>Costo Mercancía</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalCost, 0) }}</div>
            <div class="stat-desc">Valor de adquisición (COGS)</div>
        </div>
        <div class="stat-card">
            <h3>Comisiones</h3>
            <div class="stat-value" style="color:#6366f1;">$ {{ number_format($totalComm, 0) }}</div>
            <div class="stat-desc">Venta asistida</div>
        </div>
        <div class="stat-card">
            <h3>Utilidad Neta</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($netProfit, 0) }}</div>
            <div class="stat-desc">Ganancia real final</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Desempeño de Inventario</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Referencia / Producto</th>
                    <th style="text-align:center">Cant</th>
                    <th style="text-align:right">Revenue</th>
                    <th style="text-align:right">Costo Total</th>
                    <th style="text-align:right">Profit</th>
                    <th style="text-align:right">Margen %</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr>
                    <td><strong>{{ $item->item_name }}</strong></td>
                    <td style="text-align:center">{{ round($item->total_qty) }}</td>
                    <td style="text-align:right; font-weight:600;">$ {{ number_format($item->total_revenue, 0) }}</td>
                    <td style="text-align:right; color:#ef4444;">$ {{ number_format($item->total_cost, 0) }}</td>
                    <td style="text-align:right; font-weight:700; color:#1f2937;">$ {{ number_format($item->profit, 0) }}</td>
                    <td style="text-align:right;">
                        <span class="margin-badge {{ $item->margin > 30 ? 'high' : ($item->margin > 15 ? 'medium' : 'low') }}">
                            {{ round($item->margin, 1) }}%
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px; color:#9ca3af;">No hay datos de rentabilidad para este periodo</td>
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

    .grid-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 25px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 26px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 12px; }
    .stat-card h3 { color: #4b5563; font-size: 12px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    .data-table tr:hover { background: #f9fafb; }

    .margin-badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .margin-badge.high { background: #d1fae5; color: #065f46; }
    .margin-badge.medium { background: #fef3c7; color: #92400e; }
    .margin-badge.low { background: #fee2e2; color: #991b1b; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endsection
