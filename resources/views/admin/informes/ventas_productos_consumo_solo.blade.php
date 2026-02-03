@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Consumo Interno de Productos</h1>
        <div class="breadcrumb">Informes / Inventario / Uso de Insumos</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-productos-consumo-solo') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar Consumo</button>
        </form>
    </div>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Items Consumidos</h3>
            <div class="stat-value" style="color:#d97706;">{{ abs($consumo->sum('quantity')) }}</div>
            <div class="stat-desc">Unidades retiradas para uso</div>
        </div>
        <div class="stat-card">
            <h3>Costo Total</h3>
            <?php 
                $totalConsumo = $consumo->sum(function($c) { 
                    return abs($c->quantity) * ($c->product ? $c->product->cost : 0); 
                });
            ?>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalConsumo, 0) }}</div>
            <div class="stat-desc">Valorización al costo actual</div>
        </div>
        <div class="stat-card">
            <h3>Productos Diferentes</h3>
            <div class="stat-value">{{ $consumo->pluck('product_id')->unique()->count() }}</div>
            <div class="stat-desc">Gama de insumos utilizados</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Razón / Notas</th>
                    <th style="text-align:center">Cantidad</th>
                    <th style="text-align:right">Costo Estimado</th>
                    <th style="text-align:center">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consumo as $c)
                <tr>
                    <td><div style="font-weight:700;">{{ $c->product ? $c->product->name : 'N/A' }}</div></td>
                    <td>{{ $c->reason }}</td>
                    <td style="text-align:center;">{{ abs($c->quantity) }}</td>
                    <td style="text-align:right; font-weight:700;">$ {{ number_format(abs($c->quantity) * ($c->product ? $c->product->cost : 0), 0) }}</td>
                    <td style="text-align:center; color:#6b7280;">{{ date('d/m/Y', strtotime($c->created_at)) }}</td>
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
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
