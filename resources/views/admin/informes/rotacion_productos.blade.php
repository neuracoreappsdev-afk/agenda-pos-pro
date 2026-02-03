@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Análisis de Rotación de Productos</h1>
        <div class="breadcrumb">Informes / Inventario / Eficiencia de Stock</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/rotacion-productos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Calcular Rotación</button>
        </form>
    </div>

    <!-- Rotation Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Alta Rotación (>50%)</h3>
            <div class="stat-value" style="color:#10b981;">{{ collect($data)->where('rotation', '>', 50)->count() }}</div>
            <div class="stat-desc">Productos con flujo constante</div>
        </div>
        <div class="stat-card">
            <h3>Baja Rotación (<10%)</h3>
            <div class="stat-value" style="color:#ef4444;">{{ collect($data)->where('rotation', '<', 10)->count() }}</div>
            <div class="stat-desc">Posible stock inmovilizado</div>
        </div>
        <div class="stat-card">
            <h3>Promedio de Rotación</h3>
            <?php $avgRot = collect($data)->avg('rotation'); ?>
            <div class="stat-value" style="color:#1a73e8;">{{ round($avgRot) }} %</div>
            <div class="stat-desc">Eficiencia global del inventario</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3>Índice de Rotación por SKU</h3>
            <span style="font-size:12px; color:#6b7280;">Ordenado por % de Rotación Descendente</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Vendido</th>
                    <th style="text-align:center">Stock Actual</th>
                    <th style="text-align:center">Total Gest.</th>
                    <th>Nivel de Rotación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $row->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">SKU: {{ $row->sku ?: 'N/A' }}</div>
                    </td>
                    <td style="text-align:center;"><span class="count-bubble" style="background:#dcfce7; color:#166534;">{{ $row->sold }}</span></td>
                    <td style="text-align:center;"><span class="count-bubble">{{ $row->stock }}</span></td>
                    <td style="text-align:center; font-weight:600;">{{ $row->sold + $row->stock }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="flex:1; height:8px; background:#e5e7eb; border-radius:10px; overflow:hidden;">
                                <?php 
                                    $color = '#ef4444'; // Red
                                    if($row->rotation > 50) $color = '#10b981'; // Green
                                    elseif($row->rotation > 20) $color = '#f59e0b'; // Orange
                                ?>
                                <div style="height:100%; background:{{ $color }}; width:{{ $row->rotation }}%;"></div>
                            </div>
                            <span style="font-size:12px; font-weight:800; min-width:40px;">{{ round($row->rotation) }}%</span>
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
    .count-bubble { background: #f3f4f6; color: #1f2937; padding: 2px 10px; border-radius: 15px; font-weight: 800; font-size: 12px; }
</style>
@endsection
