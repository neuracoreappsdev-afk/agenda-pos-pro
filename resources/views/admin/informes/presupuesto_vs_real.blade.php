@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Presupuesto VS Real</h1>
        <div class="breadcrumb">Informes / Información General / Presupuesto VS Real</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/presupuesto-vs-real') }}" class="date-form">
            <div class="group">
                <label>Mes</label>
                <select name="month" class="control">
                    <?php
                    $meses = [
                        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                    ];
                    ?>
                    @foreach($meses as $num => $nombre)
                        <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="group">
                <label>Año</label>
                <input type="number" name="year" value="{{ $year }}" class="control" style="width:100px;">
            </div>
            <button type="submit" class="btn-filter">Analizar Desviación</button>
        </form>
    </div>

    <!-- KPI Progress Cards -->
    <div class="grid-stats">
        @foreach(['ingresos', 'gastos', 'utilidad'] as $tipo)
        <?php 
            $data = $comparativa[$tipo]; 
            $perc = $data['variacion'];
            $isPositive = $perc >= 0;
            
            // Logic for status
            $statusClass = '';
            if ($tipo == 'gastos') {
                $statusClass = $isPositive ? 'negative' : 'positive'; // Gastos suben = negativo
            } else {
                $statusClass = $isPositive ? 'positive' : 'negative'; // Ingresos suben = positivo
            }
            
            $progress = $data['presupuesto'] > 0 ? min(100, ($data['real'] / $data['presupuesto']) * 100) : 0;
        ?>
        <div class="stat-card budget-card">
            <div class="card-top">
                <h3>{{ ucfirst($tipo) }}</h3>
                <span class="var-badge {{ $statusClass }}">
                    {{ $perc > 0 ? '+' : '' }}{{ $perc }}%
                </span>
            </div>
            
            <div class="main-value">$ {{ number_format($data['real'], 0) }}</div>
            
            <div class="budget-progress-container">
                <div class="progress-labels">
                    <span>Presupuesto: $ {{ number_format($data['presupuesto'], 0) }}</span>
                    <span>{{ round($progress) }}%</span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill {{ $statusClass }}" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Insights Section -->
    <div class="insights-box" style="margin-top:40px;">
        <div class="insights-header">
            <span class="icon">💡</span>
            <h3>Inteligencia de Negocio</h3>
        </div>
        <div class="insights-body">
            <p>Este informe compara el rendimiento financiero real contra las metas presupuestadas para el mes de <strong>{{ $meses[$month] }} {{ $year }}</strong>.</p>
            <ul>
                <li>Una variación <strong>positiva</strong> en ingresos indica cumplimiento de metas comerciales.</li>
                <li>Una variación <strong>negativa</strong> en gastos indica eficiencia operativa.</li>
                <li>El objetivo ideal es mantener los gastos por debajo del presupuesto y los ingresos por encima.</li>
            </ul>
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
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 25px; }
    .stat-card.budget-card { background: white; border-radius: 16px; padding: 30px; border: 1px solid #e5e7eb; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); text-align: left; }
    
    .card-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .card-top h3 { margin: 0; font-size: 14px; font-weight: 700; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; }
    
    .var-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 800; }
    .var-badge.positive { background: #d1fae5; color: #065f46; }
    .var-badge.negative { background: #fee2e2; color: #991b1b; }

    .main-value { font-size: 32px; font-weight: 900; color: #111827; margin-bottom: 30px; }

    .budget-progress-container .progress-labels { display: flex; justify-content: space-between; font-size: 12px; color: #6b7280; margin-bottom: 8px; font-weight: 600; }
    .progress-bar-bg { background: #f3f4f6; height: 8px; border-radius: 4px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 4px; transition: width 1s ease-in-out; }
    .progress-bar-fill.positive { background: #10b981; }
    .progress-bar-fill.negative { background: #ef4444; }

    .insights-box { background: #1f2937; border-radius: 20px; padding: 30px; color: white; }
    .insights-header { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .insights-header .icon { font-size: 24px; }
    .insights-header h3 { margin: 0; font-size: 18px; font-weight: 700; }
    .insights-body p { margin-bottom: 15px; color: #d1d5db; font-size: 14px; }
    .insights-body ul { padding-left: 20px; color: #9ca3af; font-size: 13px; }
    .insights-body li { margin-bottom: 8px; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection
