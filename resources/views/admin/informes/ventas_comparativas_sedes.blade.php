@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Comparativa de Sedes</h1>
        <div class="breadcrumb">Informes / Ventas / Benchmarking Interno</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-comparativas-sedes') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Comparar Sedes</button>
        </form>
    </div>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Sede Líder</h3>
            <?php $topSede = $data->sortByDesc('ventas_actual')->first(); ?>
            <div class="stat-value" style="color:#1a73e8;">{{ $topSede ? ($topSede->sede ?: 'Principal') : 'N/A' }}</div>
            <div class="stat-desc">Mayor recaudo en el periodo</div>
        </div>
        <div class="stat-card">
            <h3>Total Red</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($data->sum('ventas_actual'), 0) }}</div>
            <div class="stat-desc">Venta consolidada nacional</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Efectividad por Punto de Venta</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Sede / Ubicación</th>
                    <th style="text-align:center">Transacciones</th>
                    <th style="text-align:right">Ventas Totales</th>
                    <th style="text-align:right">Ticket Promedio</th>
                    <th>Participación</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalConsolidado = $data->sum('ventas_actual'); ?>
                @foreach($data as $row)
                <?php $percentage = $totalConsolidado > 0 ? ($row->ventas_actual / $totalConsolidado) * 100 : 0; ?>
                <tr>
                    <td><div style="font-weight:700; color:#111827;">{{ $row->sede ?: 'SEDE PRINCIPAL' }}</div></td>
                    <td style="text-align:center;"><span class="count-bubble">{{ $row->transacciones }}</span></td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($row->ventas_actual, 0) }}</td>
                    <td style="text-align:right; color:#6b7280;">$ {{ number_format($row->transacciones > 0 ? $row->ventas_actual / $row->transacciones : 0, 0) }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="flex:1; height:6px; background:#e5e7eb; border-radius:10px; overflow:hidden;">
                                <div style="height:100%; background:#1a73e8; width:{{ $percentage }}%;"></div>
                            </div>
                            <span style="font-size:11px; font-weight:700;">{{ round($percentage) }}%</span>
                        </div>
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

    .grid-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
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
    .count-bubble { background: #f3f4f6; color: #1f2937; padding: 2px 10px; border-radius: 15px; font-weight: 800; font-size: 12px; }
</style>
@endsection
