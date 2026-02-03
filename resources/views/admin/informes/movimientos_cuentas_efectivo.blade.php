@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Cuentas de Efectivo</h1>
        <div class="breadcrumb">Informes / Caja / Flujo de Efectivo</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/movimientos-cuentas-efectivo') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar Cuentas</button>
        </form>
    </div>

    <!-- KPI Cards -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Disponibilidad</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($totalIngresos, 0) }}</div>
            <div class="stat-desc">Recaudado en el periodo</div>
        </div>
        <div class="stat-card">
            <h3>Egresos de Caja</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalEgresos, 0) }}</div>
            <div class="stat-desc">Salidas y retiros</div>
        </div>
        <div class="stat-card">
            <h3>Saldo en Libros</h3>
            <div class="stat-value" style="color:#111827;">$ {{ number_format($saldoNeto, 0) }}</div>
            <div class="stat-desc">Diferencia neta</div>
        </div>
    </div>

    <div class="charts-container">
        <div class="stat-card" style="text-align:left;">
            <h3>Ingresos por Origen</h3>
            <div class="method-list">
                <?php $idx = 0; $colors = ['#1a73e8', '#10b981', '#f59e0b', '#7c3aed']; ?>
                @foreach($ingresosPorMetodo as $method => $amount)
                    <?php 
                        $pct = $totalIngresos > 0 ? ($amount / $totalIngresos) * 100 : 0; 
                        $color = $colors[$idx % count($colors)];
                        $idx++;
                    ?>
                    <div class="method-item" style="margin-top:15px;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:5px; font-size:13px;">
                            <span style="font-weight:600;">{{ $method ?: 'Otros' }}</span>
                            <span style="font-weight:700;">$ {{ number_format($amount) }}</span>
                        </div>
                        <div style="background:#f3f4f6; height:8px; border-radius:10px; overflow:hidden;">
                            <div style="background:{{ $color }}; width:{{ $pct }}%; height:100%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="stat-card">
            <h3>Estado de Liquidez</h3>
            <div style="display:flex; height:200px; align-items:flex-end; justify-content:space-around; padding-top:20px;">
                <div style="display:flex; flex-direction:column; align-items:center; width:60px;">
                    <div style="background:#10b981; width:100%; height:100%; border-radius:8px 8px 0 0;"></div>
                    <span style="font-size:10px; margin-top:8px; font-weight:700;">ING</span>
                </div>
                <div style="display:flex; flex-direction:column; align-items:center; width:60px;">
                    <?php $h = $totalIngresos > 0 ? min(100, ($totalEgresos / $totalIngresos) * 100) : 0; ?>
                    <div style="background:#ef4444; width:100%; height:{{ $h }}%; border-radius:8px 8px 0 0;"></div>
                    <span style="font-size:10px; margin-top:8px; font-weight:700;">EGR</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-table">
        <div class="card-header">
            <h3>Libro Auxiliar de Caja</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th style="text-align:right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $mov)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($mov->movement_date)) }}</td>
                    <td>
                        <span style="padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; background:{{ in_array($mov->type, ['ingreso', 'income', 'tip']) ? '#dcfce7; color:#166534;' : '#fee2e2; color:#991b1b;' }}">
                            {{ in_array($mov->type, ['ingreso', 'income', 'tip']) ? 'INGRESO' : 'EGRESO' }}
                        </span>
                    </td>
                    <td><div style="font-weight:600;">{{ $mov->concept }}</div></td>
                    <td style="text-align:right; font-weight:700; color: {{ in_array($mov->type, ['ingreso', 'income', 'tip']) ? '#10b981' : '#ef4444' }}">
                        $ {{ number_format($mov->amount) }}
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

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .charts-container { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 25px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
