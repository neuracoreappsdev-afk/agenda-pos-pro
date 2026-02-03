@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Saldos de Inventario</h1>
        <div class="breadcrumb">Informes / Inventario / Valoración de Existencias</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/saldos-inventario') }}" class="date-form">
            <div class="group">
                <label>Corte a la Fecha</label>
                <input type="date" name="date" value="{{ $date }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Saldos</button>
            <button type="button" class="btn-filter" style="background:#1f2937;" onclick="window.print()">Imprimir Inventario</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Valor Inventario (Costo)</h3>
            <?php 
                $totalVal = $products->sum(function($p){ return $p->quantity * $p->cost; });
            ?>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($totalVal, 0) }}</div>
            <div class="stat-desc">Capital invertido en almacén</div>
        </div>
        <div class="stat-card">
            <h3>PVP Total Potencial</h3>
            <?php 
                $totalPvp = $products->sum(function($p){ return $p->quantity * $p->price; });
            ?>
            <div class="stat-value" style="color:#1a73e8;">$ {{ number_format($totalPvp, 0) }}</div>
            <div class="stat-desc">Valor total a precio de venta</div>
        </div>
        <div class="stat-card">
            <h3>Margen Potencial</h3>
            <?php $margin = $totalPvp > 0 ? (($totalPvp - $totalVal) / $totalPvp) * 100 : 0; ?>
            <div class="stat-value">{{ round($margin) }}%</div>
            <div class="stat-desc">Rentabilidad bruta esperada</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Consolidado de Existencias y Valoración</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto / Referencia</th>
                    <th style="text-align:center">Stock</th>
                    <th style="text-align:right">Costo Unit.</th>
                    <th style="text-align:right">Valoración Costo</th>
                    <th style="text-align:right">Valoración PVP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $p->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $p->sku ?: 'No SKU' }}</div>
                    </td>
                    <td style="text-align:center;"><span class="stock-pill {{ $p->quantity <= $p->min_quantity ? 'low' : '' }}">{{ $p->quantity }}</span></td>
                    <td style="text-align:right;">$ {{ number_format($p->cost, 0) }}</td>
                    <td style="text-align:right; font-weight:700;">$ {{ number_format($p->quantity * $p->cost, 0) }}</td>
                    <td style="text-align:right; font-weight:700; color:#1a73e8;">$ {{ number_format($p->quantity * $p->price, 0) }}</td>
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
    
    .stock-pill { background: #f3f4f6; color: #374151; padding: 3px 12px; border-radius: 20px; font-weight: 700; }
    .stock-pill.low { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
</style>
@endsection
