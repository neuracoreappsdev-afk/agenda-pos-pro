@extends('admin/dashboard_layout')

@section('content')
<?php
    $meses_abr = [
        'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr',
        'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago',
        'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
    ];
?>
<div class="report-container">
    <div class="report-header">
        <h1>Ventas Mandato Detallado</h1>
        <div class="breadcrumb">Informes / Ventas / Productos de Terceros</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-mandato-detallado') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Detalle</button>
        </form>
    </div>

    <div class="card-table">
        <div class="card-header">
            <h3>Items Bajo Modalidad de Mandato (Productos)</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Venta #</th>
                    <th>Producto / Ítem</th>
                    <th style="text-align:right">Cobro al Cliente</th>
                    <th style="text-align:right">Comisión Mandato</th>
                    <th style="text-align:right">Neto Tercero</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ date('d', strtotime($row->sale->sale_date)) }} {{ $meses_abr[date('M', strtotime($row->sale->sale_date))] }}, {{ date('Y', strtotime($row->sale->sale_date)) }}</td>
                    <td><span style="font-weight:700;">#{{ $row->sale_id }}</span></td>
                    <td><div style="font-weight:700; color:#1a73e8;">{{ $row->item_name }}</div></td>
                    <td style="text-align:right;">$ {{ number_format($row->total, 0) }}</td>
                    <td style="text-align:right; color:#10b981;">$ {{ number_format($row->commission_value, 0) }}</td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($row->total - $row->commission_value, 0) }}</td>
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

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
