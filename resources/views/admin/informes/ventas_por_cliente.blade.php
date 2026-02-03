@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Ventas por Cliente (Top 50)</h1>
        <div class="breadcrumb">Informes / Ventas / Fidelización</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-por-cliente') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Ranking</button>
        </form>
    </div>

    <div class="card-table">
        <div class="card-header">
            <h3>Ranking de Clientes por Consumo</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th style="text-align:center">Visitas / Compras</th>
                    <th style="text-align:right">Total Invertido</th>
                    <th style="text-align:right">Ticket Promedio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#1a73e8;">{{ $row->customer ? ($row->customer->first_name . ' ' . $row->customer->last_name) : 'Cliente Genérico' }}</div>
                        <div style="font-size:11px; color:#6b7280;">ID #{{ $row->customer_id ?: '0' }}</div>
                    </td>
                    <td style="text-align:center;"><span style="background:#eff6ff; color:#1d4ed8; padding:2px 10px; border-radius:15px; font-weight:800; font-size:12px;">{{ $row->total_compras }}</span></td>
                    <td style="text-align:right; font-weight:800; color:#111827; font-size:15px;">$ {{ number_format($row->total_invertido, 0) }}</td>
                    <td style="text-align:right; color:#4b5563;">$ {{ number_format($row->total_invertido / $row->total_compras, 0) }}</td>
                    <td>
                        <span style="background:#dcfce7; color:#166534; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700;">VIP</span>
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

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
