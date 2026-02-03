@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Facturación de Comisiones</h1>
        <div class="breadcrumb">Informes / Contabilidad / Corte de Cartera de Staff</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/facturacion-comisiones') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <div class="group" style="flex:1;">
                <label>Buscar Especialista</label>
                <input type="text" name="name" value="{{ $name ?? '' }}" class="control" placeholder="Nombre completo..." style="width:100%;">
            </div>
            <button type="submit" class="btn-filter">Generar Corte</button>
            <button type="button" class="btn-filter" style="background:#1f2937;" onclick="window.print()">Imprimir Cuentas de Cobro</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total a Pagar</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($stats->sum('total_commission'), 0) }}</div>
            <div class="stat-desc">Monto consolidado por comisiones</div>
        </div>
        <div class="stat-card">
            <h3>Venta Generada</h3>
            <div class="stat-value">$ {{ number_format($stats->sum('total_sales'), 0) }}</div>
            <div class="stat-desc">Productividad total del periodo</div>
        </div>
        <div class="stat-card">
            <h3>Staff Involucrado</h3>
            <div class="stat-value">{{ $stats->count() }}</div>
            <div class="stat-desc">Colaboradores con venta activa</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Consolidado para Liquidación Fiscal</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th style="text-align:right">Venta Bruta</th>
                    <th style="text-align:right">Comisión Devengada</th>
                    <th style="text-align:center">Estado Pago</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stats as $row)
                <?php $sp = $specialists[$row->specialist_id] ?? null; ?>
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $sp ? $sp->name : 'N/A' }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $sp ? $sp->category : '-' }}</div>
                    </td>
                    <td style="text-align:right; font-weight:600;">$ {{ number_format($row->total_sales, 0) }}</td>
                    <td style="text-align:right; font-weight:900; color:#10b981;">$ {{ number_format($row->total_commission, 0) }}</td>
                    <td style="text-align:center;">
                        <span class="status-badge pending">PENDIENTE COBRO</span>
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
    
    .status-badge.pending { background: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; }
</style>
@endsection
