@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Reporte de Auto Renta</h1>
        <div class="breadcrumb">Informes / Contabilidad / Retenciones Fiscales</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/reporte-auto-renta') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Calcular Retención</button>
            <button type="button" class="btn-filter" style="background:#1f2937;" onclick="window.print()">Imprimir Declaración</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Base Imponible (Subtotal)</h3>
            <div class="stat-value">$ {{ number_format($totalBase, 0) }}</div>
            <div class="stat-desc">Ingresos operacionales gravados</div>
        </div>
        <div class="stat-card">
            <h3>Tarifa Aplicada</h3>
            <div class="stat-value" style="color:#1a73e8;">0.4%</div>
            <div class="stat-desc">Tarifa estándar de auto-retención</div>
        </div>
        <div class="stat-card">
            <h3>Total a Pagar</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($retencionAuto, 0) }}</div>
            <div class="stat-desc">Monto proyectado de retención</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Resumen Mensual de Auto-Retenciones</h3>
        </div>
        <div style="padding:40px; text-align:center;">
            <div style="font-size:18px; color:#4b5563; max-width:600px; margin:0 auto;">
                Este informe consolida las ventas del periodo seleccionado para determinar la base gravable y aplicar la tarifa de auto-retención en la fuente por renta.
            </div>
            <div style="margin-top:30px; display:inline-block; padding:20px 40px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px;">
                <div style="font-size:14px; color:#6b7280; margin-bottom:10px;">PROYECCIÓN DE PAGO (DIAN)</div>
                <div style="font-size:32px; font-weight:900; color:#111827;">$ {{ number_format($retencionAuto, 0) }}</div>
            </div>
        </div>
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
</style>
@endsection
