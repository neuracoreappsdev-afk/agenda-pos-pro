@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Comisiones por Especialista</h1>
        <div class="breadcrumb">Informes / Especialistas / Liquidación General</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/consultar-participaciones') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar Liquidaciones</button>
        </form>
    </div>

    <!-- KPIs Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Comisiones</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($totalComisiones, 0) }}</div>
            <div class="stat-desc">Monto total a liquidar</div>
        </div>
        <div class="stat-card">
            <h3>Venta Asistida</h3>
            <?php $totalVendido = $commissionsBySpecialist->sum('total_vendido'); ?>
            <div class="stat-value">$ {{ number_format($totalVendido, 0) }}</div>
            <div class="stat-desc">Volumen total de ventas</div>
        </div>
        <div class="stat-card">
            <h3>Tasa Promedio</h3>
            <?php $avgRate = $totalVendido > 0 ? ($totalComisiones / $totalVendido) * 100 : 0; ?>
            <div class="stat-value">{{ number_format($avgRate, 1) }}%</div>
            <div class="stat-desc">Porcentaje efectivo</div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Resumen de Participaciones</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th>Categoría</th>
                    <th style="text-align:right">Total Vendido</th>
                    <th style="text-align:center">% Real</th>
                    <th style="text-align:right">Comisión</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specialists as $sp)
                <?php
                    $stats = $commissionsBySpecialist->get($sp->id);
                    $spTotal = $stats ? $stats->total_vendido : 0;
                    $spCommission = $stats ? $stats->total_comision : 0;
                    $impliedRate = $spTotal > 0 ? ($spCommission / $spTotal) * 100 : 0;
                ?>
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $sp->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">ID #{{ $sp->id }}</div>
                    </td>
                    <td><span class="badge-cat">{{ $sp->category ?: 'General' }}</span></td>
                    <td style="text-align:right; font-weight:600;">$ {{ number_format($spTotal, 0) }}</td>
                    <td style="text-align:center; font-family:monospace; color:#6b7280;">{{ number_format($impliedRate, 1) }}%</td>
                    <td style="text-align:right; font-weight:900; color:#10b981; font-size:15px;">$ {{ number_format($spCommission, 0) }}</td>
                    <td style="text-align:center;">
                        <span class="badge-status">Pendiente</span>
                    </td>
                </tr>
                @endforeach
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
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

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

    .badge-cat { background: #f3f4f6; color: #4b5563; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .badge-status { background: #fff7ed; color: #c2410c; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; border: 1px solid #ffedd5; }
</style>
@endsection
