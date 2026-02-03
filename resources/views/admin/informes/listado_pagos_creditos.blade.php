@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Listado de Pagos de Créditos</h1>
        <div class="breadcrumb">Informes / Cartera / Cuentas por Cobrar</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/listado-pagos-creditos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <div class="group" style="flex:1;">
                <label>Buscar por Nombre</label>
                <input type="text" name="name" value="{{ $name ?? '' }}" class="control" placeholder="Nombre del cliente..." style="width:100%;">
            </div>
            <button type="submit" class="btn-filter">Filtrar Cartera</button>
            <button type="button" class="btn-filter" style="background:#1f2937;" onclick="window.print()">Imprimir Cobros</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Cartera Total</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($sales->sum('total'), 0) }}</div>
            <div class="stat-desc">Monto total vendido a crédito</div>
        </div>
        <div class="stat-card">
            <h3>Facturas Pendietes</h3>
            <div class="stat-value">{{ $sales->count() }}</div>
            <div class="stat-desc">Documentos por recaudar</div>
        </div>
        <div class="stat-card">
            <h3>Ticket Promedio Crédito</h3>
            <?php $avg = $sales->count() > 0 ? $sales->sum('total') / $sales->count() : 0; ?>
            <div class="stat-value" style="color:#1a73e8;">$ {{ number_format($avg, 0) }}</div>
            <div class="stat-desc">Valor medio de deuda</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Detalle de Cuentas por Cobrar a Clientes</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Factura #</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th style="text-align:right">Monto Deuda</th>
                    <th style="text-align:center">Días Transcurridos</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $s)
                <?php 
                    $days = floor((time() - strtotime($s->sale_date)) / (60 * 60 * 24));
                ?>
                <tr>
                    <td><span style="font-weight:700;">#{{ $s->id }}</span></td>
                    <td>{{ date('d/m/Y', strtotime($s->sale_date)) }}</td>
                    <td>
                        <div style="font-weight:700;">{{ $s->customer_name ?: ($s->customer ? $s->customer->name : 'N/A') }}</div>
                        <div style="font-size:11px; color:#6b7280;">Tel: {{ $s->customer ? $s->customer->contact_number : '-' }}</div>
                    </td>
                    <td style="text-align:right; font-weight:800; color:#ef4444;">$ {{ number_format($s->total, 0) }}</td>
                    <td style="text-align:center;"><div class="days-pill {{ $days > 30 ? 'late' : '' }}">{{ $days }} días</div></td>
                    <td style="text-align:center;">
                        <span class="status-badge error">PENDIENTE</span>
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
    
    .days-pill { background: #f3f4f6; color: #4b5563; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .days-pill.late { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .status-badge.error { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; }
</style>
@endsection
