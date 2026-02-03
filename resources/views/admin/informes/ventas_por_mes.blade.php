@extends('admin/dashboard_layout')

@section('content')
<?php
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];
?>
<div class="report-container">
    <div class="report-header">
        <h1>Ventas Consolidadas por Mes</h1>
        <div class="breadcrumb">Informes / Ventas / Cierre Mensual</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-por-mes') }}" class="date-form">
            <div class="group">
                <label>Año de Consulta</label>
                <select name="year" class="control">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-filter">Ver Año</button>
        </form>
    </div>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Mejor Mes</h3>
            <?php $topMonth = $data->sortByDesc('total')->first(); ?>
            <div class="stat-value" style="color:#10b981;">
                {{ $topMonth ? $meses[(int)date('m', strtotime($topMonth->month . '-01'))] : 'N/A' }}
            </div>
            <div class="stat-desc">$ {{ $topMonth ? number_format($topMonth->total, 0) : 0 }} totales</div>
        </div>
        <div class="stat-card">
            <h3>Total Anual</h3>
            <div class="stat-value" style="color:#1a73e8;">$ {{ number_format($data->sum('total'), 0) }}</div>
            <div class="stat-desc">Recaudo total del año {{ $year }}</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Comparativa Mensual de Ingresos</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th style="text-align:center">Transacciones</th>
                    <th style="text-align:right">Total Ingresos</th>
                    <th>Crecimiento / Mix</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalYear = $data->sum('total'); ?>
                @foreach($data as $row)
                <?php $percentage = $totalYear > 0 ? ($row->total / $totalYear) * 100 : 0; ?>
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">
                            {{ $meses[(int)date('m', strtotime($row->month . '-01'))] }} {{ date('Y', strtotime($row->month . '-01')) }}
                        </div>
                    </td>
                    <td style="text-align:center;"><span class="count-bubble">{{ $row->transacciones }}</span></td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($row->total, 0) }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="flex:1; height:6px; background:#e5e7eb; border-radius:10px;">
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
