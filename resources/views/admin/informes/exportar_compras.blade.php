@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Exportación de Compras</h1>
        <div class="breadcrumb">Informes / Contabilidad / Historial de Adquisiciones</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/exportar-compras') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Ver Compras</button>
            <button type="button" class="btn-filter" style="background:#059669;" onclick="exportToExcel()">Excel / CSV</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Facturas de Compra</h3>
            <div class="stat-value">{{ $purchases->count() }}</div>
            <div class="stat-desc">Documentos recibidos</div>
        </div>
        <div class="stat-card">
            <h3>Inversión Total</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($purchases->sum('total'), 0) }}</div>
            <div class="stat-desc">Monto total de gasto registrado</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Registro de Compras para Exportación</h3>
        </div>
        <table class="data-table" id="exportTable">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $p)
                <tr>
                    <td><span style="font-weight:700;">{{ $p->reference_number ?: '#'.$p->id }}</span></td>
                    <td>{{ date('d/m/Y', strtotime($p->invoice_date)) }}</td>
                    <td>{{ $p->provider_name ?: 'Proveedor Genérico' }}</td>
                    <td style="text-align:right; font-weight:800; color:#ef4444;">$ {{ number_format($p->total, 0) }}</td>
                    <td style="text-align:center;">
                        <span class="status-badge received">RECIBIDO</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function exportToExcel() {
    let table = document.getElementById("exportTable");
    let html = table.outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let link = document.createElement("a");
    link.download = "export_compras.xls";
    link.href = url;
    link.click();
}
</script>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; display: flex; gap: 10px; }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .group { display: flex; flex-direction: column; gap: 5px; }
    .group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; height: 42px; }

    .grid-stats { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
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
    
    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.received { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }
</style>
@endsection
