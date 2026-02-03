@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Devoluciones y Notas Crédito</h1>
        <div class="breadcrumb">Informes / Operaciones / Control de Reversiones</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Devuelto</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($stats['total_refunded'], 0) }}</div>
            <div class="stat-desc">Acumulado histórico</div>
        </div>
        <div class="stat-card">
            <h3>Índice del Mes</h3>
            <div class="stat-value">{{ $stats['rate'] }}%</div>
            <div class="stat-desc">Monto: $ {{ number_format($stats['amount_month'], 0) }}</div>
        </div>
        <div class="stat-card">
            <h3>Motivo Principal</h3>
            <div class="stat-value" style="font-size:18px; color:#6b7280; font-weight:700;">{{ $stats['top_reason'] }}</div>
            <div class="stat-desc">Causa recurrente registrada</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        @if($refunds->count() > 0)
            <div class="card-header" style="padding:15px 20px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
                <h3 style="margin:0; font-size:14px; font-weight:700;">Historial de Devoluciones</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>ID Venta</th>
                        <th>Motivo</th>
                        <th style="text-align:right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($refunds as $r)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($r->created_at)) }}</td>
                        <td>#{{ $r->sale_id }}</td>
                        <td>{{ $r->reason }}</td>
                        <td style="text-align:right; font-weight:700; color:#ef4444;">$ {{ number_format($r->amount, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="placeholder-content" style="padding:80px 40px; text-align:center;">
                 <div style="font-size:48px; margin-bottom:15px;">🔄</div>
                 <h3 style="font-size:20px; font-weight:800; color:#1f2937;">Historial de Devoluciones</h3>
                 <p style="color:#6b7280; max-width:400px; margin:0 auto 25px;">
                     Actualmente no existen registros de devoluciones en el periodo. Todas las notas crédito aparecerán listadas aquí.
                 </p>
            </div>
        @endif
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
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }
</style>
@endsection
