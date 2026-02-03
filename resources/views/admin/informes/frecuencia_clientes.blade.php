@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Frecuencia de Visita</h1>
        <div class="breadcrumb">Informes / Clientes / Frecuencia</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/frecuencia-clientes') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar</button>
        </form>
    </div>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Clientes Únicos (1 Visita)</h3>
            <div class="stat-value">{{ $distribution['1 visita'] }}</div>
            <div class="stat-desc">Clientes que vinieron solo una vez</div>
        </div>
        <div class="stat-card">
            <h3>Clientes Regulares (2-3 Visitas)</h3>
            <div class="stat-value">{{ $distribution['2-3 visitas'] }}</div>
            <div class="stat-desc">Clientes en proceso de fidelización</div>
        </div>
        <div class="stat-card">
            <h3>Clientes Frecuentes (4+ Visitas)</h3>
            <div class="stat-value">{{ $distribution['4+ visitas'] }}</div>
            <div class="stat-desc">Clientes altamente leales</div>
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .group { display: flex; flex-direction: column; gap: 5px; }
    .group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; }
    
    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 42px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 14px; }
    .stat-card h3 { color: #4b5563; font-size: 16px; font-weight: 600; margin: 0; }
</style>
@endsection
