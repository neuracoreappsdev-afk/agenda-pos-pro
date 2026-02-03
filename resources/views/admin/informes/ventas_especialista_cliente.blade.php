@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Ventas Especialista / Cliente</h1>
        <div class="breadcrumb">Informes / Especialistas / Relación de Ventas</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-especialistas-clientes') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Relación</button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="card-table">
        <div class="card-header">
            <h3>Registro de Ventas Cruzadas</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Ítem / Servicio</th>
                    <th style="text-align:right">Subtotal</th>
                    <th style="text-align:right">Comisión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                <tr>
                    <td><div style="font-weight:700;">{{ $row->specialist_name }}</div></td>
                    <td>
                        <div style="font-weight:600; color:#1a73e8;">{{ $row->first_name }} {{ $row->last_name }}</div>
                        @if(!$row->first_name) <span style="color:#9ca3af; font-size:11px;">Mostrador / Express</span> @endif
                    </td>
                    <td><span style="font-size:12px; font-weight:600;">{{ date('d/m/y H:i', strtotime($row->sale_date)) }}</span></td>
                    <td><div style="font-size:13px; color:#4b5563;">{{ $row->item_name }}</div></td>
                    <td style="text-align:right; font-weight:700; color:#111827;">$ {{ number_format($row->total, 0) }}</td>
                    <td style="text-align:right; font-weight:700; color:#10b981;">$ {{ number_format($row->commission_value, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px; color:#9ca3af;">No se encontraron registros en este periodo</td>
                </tr>
                @endforelse
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
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
