@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Comisiones de Pago Dividido</h1>
        <div class="breadcrumb">Informes / Especialistas / Pagos Multi-Asesor</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/participaciones-pago-dividido') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar Ventas</button>
        </form>
    </div>

    <!-- Results Section -->
    <div class="card-table">
        <div class="card-header">
            <h3>Ventas con Participación Múltiple</h3>
        </div>
        <div class="split-list">
            @forelse($data as $saleId => $items)
            <div class="sale-group">
                <div class="sale-group-header">
                    <span class="sale-badge">Venta #{{ $saleId }}</span>
                </div>
                <table class="nested-table">
                    <thead>
                        <tr>
                            <th>Especialista Participante</th>
                            <th>Ítem / Servicio</th>
                            <th style="text-align:right">Comisión Devengada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $i)
                        <tr>
                            <td><div style="font-weight:700; color:#1a73e8;">{{ $i->specialist_name }}</div></td>
                            <td>{{ $i->item_name }}</td>
                            <td style="text-align:right; font-weight:800; color:#10b981;">$ {{ number_format($i->commission_value, 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @empty
            <div style="text-align:center; padding:50px; color:#9ca3af;">No se encontraron ventas con pago dividido en este periodo</div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .split-list { padding: 20px; }
    .sale-group { border: 1px solid #f3f4f6; border-radius: 12px; margin-bottom: 20px; overflow: hidden; }
    .sale-group-header { background: #f9fafb; padding: 10px 20px; border-bottom: 1px solid #f3f4f6; }
    .sale-badge { background: #1f2937; color: white; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 800; }
    
    .nested-table { width: 100%; border-collapse: collapse; }
    .nested-table th { text-align: left; padding: 10px 20px; font-size: 11px; color: #9ca3af; text-transform: uppercase; border-bottom: 1px solid #f9fafb; }
    .nested-table td { padding: 12px 20px; font-size: 13px; color: #4b5563; border-bottom: 1px solid #f9fafb; }
</style>
@endsection
