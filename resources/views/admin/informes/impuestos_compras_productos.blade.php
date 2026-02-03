@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Impuesto Compras Productos</h1>
        <div class="breadcrumb">Informes / Inventario / Impuestos Descontables</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/impuestos-compras-productos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Tributario</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Base Compras</h3>
            <div class="stat-value">$ {{ number_format($data->base, 0) }}</div>
            <div class="stat-desc">Valor neto de adquisiciones</div>
        </div>
        <div class="stat-card">
            <h3>IVA Descontable</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($data->tax, 0) }}</div>
            <div class="stat-desc">Impuesto pagado a proveedores</div>
        </div>
        <div class="stat-card">
            <h3>Total Desembolso</h3>
            <div class="stat-value" style="color:#111827;">$ {{ number_format($data->total, 0) }}</div>
            <div class="stat-desc">Monto total facturado por terceros</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Relación de Facturas de Compra (Soportes)</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Factura / Ref</th>
                    <th>Proveedor</th>
                    <th style="text-align:right">Base</th>
                    <th style="text-align:right">IVA</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($inv->invoice_date)) }}</td>
                    <td><span style="font-weight:700;">{{ $inv->invoice_number ?: '#'.$inv->id }}</span></td>
                    <td>{{ $inv->provider ? $inv->provider->provider_name : 'Genérico' }}</td>
                    <td style="text-align:right;">$ {{ number_format($inv->subtotal, 0) }}</td>
                    <td style="text-align:right; color:#10b981;">$ {{ number_format($inv->tax, 0) }}</td>
                    <td style="text-align:right; font-weight:800;">$ {{ number_format($inv->total, 0) }}</td>
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
</style>
@endsection
