@extends('admin/dashboard_layout')

@section('content')



<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px 24px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .report-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
    }
    .breadcrumb {
        color: #6b7280;
        font-size: 13px;
        margin-top: 5px;
    }
    .breadcrumb a {
        color: #1a73e8;
        text-decoration: none;
    }
    
    .filters-card {
        background: white;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .filters-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .filter-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
    }
    .filter-input {
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        min-width: 180px;
    }
    .filter-input:focus {
        outline: none;
        border-color: #1a73e8;
    }
    
    .btn-filter {
        background: #1a73e8;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-filter:hover { background: #1557b0; }
    
    .btn-export {
        background: #059669;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-export:hover { background: #047857; }
    
    .report-content {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .report-table {
        width: 100%;
        border-collapse: collapse;
    }
    .report-table th {
        background: #f9fafb;
        padding: 14px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
    }
    .report-table td {
        padding: 14px 16px;
        font-size: 14px;
        border-bottom: 1px solid #f3f4f6;
    }
    .report-table tr:hover {
        background: #f9fafb;
    }
    
    .total-row {
        background: #eff6ff !important;
        font-weight: 600;
    }
    .total-row td {
        border-top: 2px solid #1a73e8;
    }
    
    /* KPI Grid */
    .grid-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 24px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; font-weight: 500; }
    .stat-card h3 { color: #4b5563; font-size: 14px; font-weight: 700; text-transform: uppercase; margin: 0; letter-spacing: 0.5px; }

    
    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .grid-stats { grid-template-columns: 1fr; }
    }
</style>

<div class="report-header">
    <div>
        <h1 class="report-title">{{ trans('messages.general_sales') }}</h1>
        <div class="breadcrumb">
            <a href="{{ url('admin/informes') }}">{{ trans('messages.reports') }}</a> / {{ trans('messages.general_sales') }}
        </div>
    </div>
    <div style="display:flex; gap:12px;">
        <button class="btn-export" onclick="exportToExcel()">📥 Exportar Excel</button>
        <button class="btn-export" style="background:#dc2626;" onclick="window.print()">📄 Imprimir</button>
    </div>
</div>

<!-- Resumen -->
<!-- KPI Cards (Estilo Premium) -->
<div class="grid-stats">
    <div class="stat-card">
        <h3>Total Ventas</h3>
        <div class="stat-value" style="color:#10b981;">${{ number_format($totalVentas, 0, ',', '.') }}</div>
        <div class="stat-desc">Ingresos brutos en el periodo</div>
    </div>
    <div class="stat-card">
        <h3>Transacciones</h3>
        <div class="stat-value">{{ $cantidadVentas }}</div>
        <div class="stat-desc">Facturas generadas</div>
    </div>
    <div class="stat-card">
        <h3>Ticket Promedio</h3>
        <div class="stat-value" style="color:#3b82f6;">${{ number_format($promedioVenta, 0, ',', '.') }}</div>
        <div class="stat-desc">Promedio por venta</div>
    </div>
    <div class="stat-card">
        <h3>Comisiones Est.</h3>
        <div class="stat-value" style="color:#f59e0b;">${{ number_format($totalComisionesReal, 0, ',', '.') }}</div>
        <div class="stat-desc">Calculado según items</div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="filters-card">
    <div class="filters-row">
        <div class="filter-group">
            <label class="filter-label">Fecha Desde</label>
            <input type="date" name="date_from" class="filter-input" value="{{ $dateFrom }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Fecha Hasta</label>
            <input type="date" name="date_to" class="filter-input" value="{{ $dateTo }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Sede</label>
            <select name="location" class="filter-input">
                <option value="">Todas las sedes</option>
                <option>Holguines Trade Center</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Especialista</label>
            <select name="specialist_id" class="filter-input">
                <option value="">Todos</option>
                @foreach($specialists as $sp)
                    <option value="{{ $sp->id }}" {{ $specialistId == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-filter">Filtrar</button>
    </div>
</form>

<!-- Tabla del Reporte -->
<div class="report-content">
    @if($sales->count() > 0)
    <table class="report-table" id="reportTable">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Factura #</th>
                <th>Cliente ID</th>
                <th>Especialista ID</th>
                <th>Medio de Pago</th>
                <th>Estado</th>
                <th style="text-align:right;">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d H:i') }}</td>
                <td>#{{ $sale->invoice_number ?? $sale->id }}</td>
                <td>{{ $sale->customer ? ($sale->customer->first_name . ' ' . $sale->customer->last_name) : 'Genérico' }}</td>
                <td>{{ $sale->specialist ? $sale->specialist->name : 'N/A' }}</td>
                <td>{{ $sale->payment_method ?? 'N/A' }}</td>
                <td>
                    <span style="background:#dcfce7; color:#166534; padding:4px 8px; border-radius:4px; font-size:12px;">
                        {{ $sale->status ?? 'Completada' }}
                    </span>
                </td>
                <td style="text-align:right;">${{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6"><strong>TOTAL</strong></td>
                <td style="text-align:right;"><strong>${{ number_format($totalVentas, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <div class="empty-icon">📊</div>
        <p>No hay ventas registradas en el período seleccionado</p>
    </div>
    @endif
</div>

<!-- Resumen por Método de Pago -->
@if($ventasPorMetodo->count() > 0)
<div class="report-content" style="margin-top:20px;">
    <h3 style="margin-bottom:20px;">Resumen por Método de Pago</h3>
    <table class="report-table">
        <thead>
            <tr>
                <th>Método de Pago</th>
                <th style="text-align:right;">Total</th>
                <th style="text-align:right;">Porcentaje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventasPorMetodo as $metodo => $total)
            <tr>
                <td>{{ $metodo }}</td>
                <td style="text-align:right;">${{ number_format($total, 0, ',', '.') }}</td>
                <td style="text-align:right;">{{ number_format(($total / $totalVentas) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<script>
function exportToExcel() {
    let table = document.getElementById('reportTable');
    if (!table) {
        alert('No hay datos para exportar');
        return;
    }
    
    let html = table.outerHTML;
    let blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    a.href = url;
    a.download = 'ventas_generales_{{ date("Y-m-d") }}.xls';
    a.click();
}
</script>

@endsection
