@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Control de Stock e Inventario</h1>
        <div class="breadcrumb">Informes / Catálogo / Auditoría de Existencias</div>
    </div>

    <div class="filter-bar">
        <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
            <div style="font-size:14px; color:#4b5563; font-weight:600;">⚠️ Los ítems en rojo requieren compra inmediata</div>
            <button type="button" class="btn-filter" onclick="window.print()" style="background:#1f2937;">Imprimir Reporte</button>
        </div>
    </div>

    <!-- KPIs Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Valor Inventario</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($valorInventario, 0) }}</div>
            <div class="stat-desc">Capital invertido en stock</div>
        </div>
        <div class="stat-card">
            <h3>Críticos / Bajo Mín.</h3>
            <div class="stat-value" style="color:#ef4444;">{{ $lowStock->count() }}</div>
            <div class="stat-desc">Productos para reposición</div>
        </div>
        <div class="stat-card">
            <h3>Total Referencias</h3>
            <div class="stat-value">{{ $totalProductos }}</div>
            <div class="stat-desc">Variedad de productos</div>
        </div>
    </div>

    <!-- Critical Stock Table -->
    @if($lowStock->count() > 0)
    <div class="card-table" style="margin-top:30px; border: 2px solid #fee2e2;">
        <div class="card-header" style="background:#fef2f2; border-bottom:1px solid #fee2e2;">
            <h3 style="color:#991b1b;">⚠️ ALERTAS DE REPOSICIÓN (STOCK BAJO)</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Stock Actual</th>
                    <th style="text-align:center">Mín. Requerido</th>
                    <th style="text-align:center">Déficit</th>
                    <th style="text-align:right">Costo Reposición</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStock as $lp)
                <tr style="background:#fffafa;">
                    <td>
                        <div style="font-weight:700; color:#b91c1c;">{{ $lp->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $lp->sku ?: 'No SKU' }}</div>
                    </td>
                    <td style="text-align:center;"><div class="stock-badge-critical">{{ $lp->quantity }}</div></td>
                    <td style="text-align:center; font-weight:600;">{{ $lp->min_quantity }}</td>
                    <td style="text-align:center; color:#ef4444; font-weight:800;">-{{ max(0, $lp->min_quantity - $lp->quantity) }}</td>
                    <td style="text-align:right; font-weight:700;">$ {{ number_format($lp->cost, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Full Inventory -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>INVENTARIO COMPLETO</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th style="text-align:center">Existencias</th>
                    <th style="text-align:right">Costo Unit.</th>
                    <th style="text-align:right">Valoración</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $p->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $p->sku ?: 'No SKU' }}</div>
                    </td>
                    <td><span class="cat-pill">{{ $p->category ?: 'General' }}</span></td>
                    <td style="text-align:center; font-weight:700;">{{ $p->quantity }}</td>
                    <td style="text-align:right; color:#6b7280;">$ {{ number_format($p->cost, 0) }}</td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($p->quantity * $p->cost, 0) }}</td>
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
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; text-transform: uppercase; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    
    .stock-badge-critical { background: #fee2e2; color: #b91c1c; padding: 4px 12px; border-radius: 20px; font-weight: 800; display: inline-block; border: 1px solid #fecaca; }
    .cat-pill { background: #f3f4f6; color: #6b7280; padding: 2px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }

    @media print {
        .filter-bar, .breadcrumb, .btn-filter { display: none !important; }
        .grid-stats { grid-template-columns: repeat(3, 1fr) !important; }
        .card-table { break-inside: avoid; }
        body { background: white !important; }
    }
</style>
@endsection
