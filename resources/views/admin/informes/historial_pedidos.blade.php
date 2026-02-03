@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Historial de Pedidos (Compras)</h1>
        <div class="breadcrumb">Informes / Inventario / Abastecimiento</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Pedidos Realizados</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $invoices->total() }}</div>
            <div class="stat-desc">Órdenes de compra procesadas</div>
        </div>
        <div class="stat-card">
            <h3>Facturas Pendientes Pago</h3>
            <div class="stat-value" style="color:#f59e0b;">{{ $invoices->where('status', '!=', 'paid')->count() }}</div>
            <div class="stat-desc">Compromisos financieros actuales</div>
        </div>
        <div class="stat-card">
            <h3>Inversión Stock</h3>
            <div class="stat-value" style="color:#111827;">$ {{ number_format($invoices->sum('total'), 0) }}</div>
            <div class="stat-desc">Costo total de abastecimiento</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Registro Histórico de Adquisiciones</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Proveedor</th>
                    <th style="text-align:right">Subtotal</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($inv->invoice_date)) }}</td>
                    <td><span style="font-weight:700;">{{ $inv->invoice_number ?: '#'.$inv->id }}</span></td>
                    <td>{{ $inv->provider ? $inv->provider->provider_name : 'Proveedor Externo' }}</td>
                    <td style="text-align:right;">$ {{ number_format($inv->subtotal, 0) }}</td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($inv->total, 0) }}</td>
                    <td style="text-align:center;">
                        <span class="status-badge {{ $inv->status ?: 'paid' }}">{{ strtoupper($inv->status ?: 'RECIBIDO') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-container">
            {!! $invoices->render() !!}
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
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

    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.paid { background: #dcfce7; color: #166534; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    
    .pagination-container { padding: 20px; display: flex; justify-content: center; }
</style>
@endsection
