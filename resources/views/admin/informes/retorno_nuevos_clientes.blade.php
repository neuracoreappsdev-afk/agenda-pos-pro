@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ trans('messages.return_new_clients_report') }}</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / {{ trans('messages.clients') }} / {{ trans('messages.return_new_clients_report') }}</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/retorno-nuevos-clientes') }}" class="date-form">
            <div class="group">
                <label>Mes de Registro</label>
                <select name="month" class="control">
                    <?php
                    $months = [
                        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                    ];
                    foreach($months as $m => $name) {
                        $selected = ($month == $m) ? 'selected' : '';
                        echo "<option value='$m' $selected>$name</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="group">
                <label>Año</label>
                <select name="year" class="control">
                    <?php
                    for($y=date('Y'); $y>=2020; $y--) {
                        $selected = ($year == $y) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn-filter">Analizar Cohorte</button>
        </form>
    </div>

    <?php
        $retentionRate = $totalNew > 0 ? round(($returned / $totalNew) * 100, 1) : 0;
    ?>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Clientes Nuevos</h3>
            <div class="stat-value">{{ $totalNew }}</div>
            <div class="stat-desc">Registrados en el mes</div>
        </div>
        <div class="stat-card">
            <h3>Retornaron</h3>
            <div class="stat-value" style="color:#10b981;">{{ $returned }}</div>
            <div class="stat-desc">Hicieron +1 compra después</div>
        </div>
        <div class="stat-card">
            <h3>Tasa de Retorno</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $retentionRate }}%</div>
            <div class="stat-desc">Fidelización de cohorte</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div style="padding:20px; border-bottom:1px solid #e5e7eb;">
            <h3 style="margin:0; font-size:16px; color:#1f2937;">Detalle del Cohorte</h3>
        </div>
        <div style="padding:40px; text-align:center; color:#6b7280;">
            <div style="font-size:18px; font-weight:700; color:#1f2937; margin-bottom:10px;">Análisis de Fidelización</div>
            <p style="max-width:600px; margin:0 auto;">Este informe analiza a los clientes que se registraron por primera vez en <strong>{{ $months[$month] }} de {{ $year }}</strong> y rastrea si volvieron a realizar una compra en cualquier momento posterior.</p>
            <div style="margin-top:30px; display:inline-block; padding:15px 30px; background:#f0f9ff; border-radius:12px; border:1px solid #bae6fd; color:#0369a1; font-weight:600;">
                Tasa de Retorno: {{ $retentionRate }}%
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
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; font-size: 14px; min-width: 150px; outline:none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 42px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 14px; }
    .stat-card h3 { color: #4b5563; font-size: 16px; font-weight: 600; margin: 0; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; }
</style>
@endsection


