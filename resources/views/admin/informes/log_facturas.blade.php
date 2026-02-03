@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Log de Facturación</h1>
        <div class="breadcrumb">Informes / Auditoría / Control de Emisión</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Documentos Emitidos</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $sales->total() }}</div>
            <div class="stat-desc">Facturas registradas en el sistema</div>
        </div>
        <div class="stat-card">
            <h3>Monto Auditado</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($sales->sum('total'), 0) }}</div>
            <div class="stat-desc">Valor total de la muestra actual</div>
        </div>
        <div class="stat-card">
            <h3>Integridad</h3>
            <div class="stat-value">100%</div>
            <div class="stat-desc">Sin saltos en consecutivos detectados</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Historial de Transacciones Finalizadas</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Factura #</th>
                    <th>Operador</th>
                    <th>Cliente</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:center">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $s)
                <tr>
                    <td>{{ date('d/m/Y H:i', strtotime($s->sale_date)) }}</td>
                    <td><span style="font-weight:800; color:#1a73e8;">#{{ $s->id }}</span></td>
                    <td>
                        <div style="font-weight:600;">{{ $s->user ? $s->user->username : 'Cajero Central' }}</div>
                    </td>
                    <td>{{ $s->customer_name ?: 'Consumidor Final' }}</td>
                    <td style="text-align:right; font-weight:900;">$ {{ number_format($s->total, 0) }}</td>
                    <td style="text-align:center;">
                        <button class="btn-view">Ver PDF</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-container">
            {!! $sales->render() !!}
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

    .btn-view { background: #fff; border: 1px solid #d1d5db; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; }
    .pagination-container { padding: 20px; display: flex; justify-content: center; }
</style>
@endsection
