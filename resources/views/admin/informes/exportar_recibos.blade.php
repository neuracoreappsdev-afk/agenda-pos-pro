@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Exportación de Recibos</h1>
        <div class="breadcrumb">Informes / Contabilidad / Recibos de Caja</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/exportar-recibos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Ver Movimientos</button>
            <button type="button" class="btn-filter" style="background:#059669;" onclick="exportToExcel()">Excel / CSV</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Recibos Generados</h3>
            <div class="stat-value">{{ $movements->count() }}</div>
            <div class="stat-desc">Ingresos registrados en caja</div>
        </div>
        <div class="stat-card">
            <h3>Total Ingresos</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($movements->sum('amount'), 0) }}</div>
            <div class="stat-desc">Volumen total recaudado</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Libro Auxiliar de Recibos de Caja</h3>
        </div>
        <table class="data-table" id="exportTable">
            <thead>
                <tr>
                    <th>Recibo #</th>
                    <th>Fecha / Hora</th>
                    <th>Concepto</th>
                    <th style="text-align:right">Monto</th>
                    <th style="text-align:center">Origen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movements as $m)
                <tr>
                    <td><span style="font-weight:700;">RC-{{ $m->id }}</span></td>
                    <td>{{ date('d/m/Y H:i', strtotime($m->created_at)) }}</td>
                    <td>{{ $m->notes ?: 'Ingreso General de Caja' }}</td>
                    <td style="text-align:right; font-weight:800; color:#10b981;">$ {{ number_format($m->amount, 0) }}</td>
                    <td style="text-align:center;">
                        <span class="status-badge source">{{ $m->source ?: 'POS' }}</span>
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
    link.download = "export_recibos.xls";
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
    
    .status-badge.source { background: #eff6ff; color: #1e40af; border: 1px solid #3b82f6; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; }
</style>
@endsection
