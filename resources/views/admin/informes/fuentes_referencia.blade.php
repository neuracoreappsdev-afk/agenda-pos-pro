@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ trans('messages.reference_sources') }}</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / {{ trans('messages.clients') }} / {{ trans('messages.reference_sources') }}</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/fuentes-referencia') }}" class="date-form">
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

    <?php
        $totalReferidos = collect($chartData)->sum('y');
        
        // Find top source by Count
        $topSource = collect($chartData)->sortByDesc('y')->first();
        $topSourceName = isset($topSource['name']) ? $topSource['name'] : 'N/A';
        $topSourceValue = isset($topSource['y']) ? $topSource['y'] : 0;
        
        // Find top source by Revenue
        $topRevenueSource = collect($chartData)->sortByDesc('revenue')->first();
        $topRevName = isset($topRevenueSource['name']) ? $topRevenueSource['name'] : 'N/A';
        $topRevValue = isset($topRevenueSource['revenue']) ? $topRevenueSource['revenue'] : 0;
    ?>

    <!-- KPI Cards (Estilo Frecuencia) -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Referidos</h3>
            <div class="stat-value">{{ $totalReferidos }}</div>
            <div class="stat-desc">Clientes nuevos en el periodo</div>
        </div>
        <div class="stat-card">
            <h3>Canal Principal</h3>
            <div class="stat-value" style="font-size: 26px; color: #10b981;">
                {{ strlen($topSourceName) > 15 ? substr($topSourceName, 0, 15) . '...' : $topSourceName }}
            </div>
            <div class="stat-desc">{{ $topSourceValue }} clientes registrados</div>
        </div>
        <div class="stat-card">
            <h3>Más Rentable</h3>
            <div class="stat-value" style="font-size: 26px; color: #3b82f6;">
                {{ strlen($topRevName) > 15 ? substr($topRevName, 0, 15) . '...' : $topRevName }}
            </div>
            <div class="stat-desc">${{ number_format($topRevValue, 0) }} en ventas</div>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="charts-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top:30px;">
        
        <!-- Pie Chart -->
        <div class="stat-card" style="display:flex; flex-direction:column; align-items:center; padding-top:20px;">
            <h3 style="margin-bottom:20px;">Distribución Gráfica</h3>
            
            <?php
                $gradient = [];
                $currentAngle = 0;
                $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6'];
                foreach($chartData as $index => $item) {
                    $val = $item['y'];
                    if($totalReferidos == 0) break;
                    $percent = ($val / $totalReferidos);
                    $deg = $percent * 360;
                    $endAngle = $currentAngle + $deg;
                    $color = $colors[$index % count($colors)];
                    $gradient[] = "$color $currentAngle"."deg $endAngle"."deg";
                    $currentAngle = $endAngle;
                }
                $gradientStr = implode(', ', $gradient);
                if(empty($gradientStr)) $gradientStr = '#f3f4f6 0deg 360deg';
            ?>
            
            <div class="pie-chart" style="background: conic-gradient({{ $gradientStr }});">
                <div class="pie-hole"></div>
            </div>
        </div>

        <!-- Legend List -->
        <div class="stat-card" style="text-align:left;">
            <h3 style="margin-bottom:20px;">Detalle por Fuente</h3>
            <div class="legend-list">
                @foreach($chartData as $index => $item)
                <?php 
                    $percent = $totalReferidos > 0 ? ($item['y'] / $totalReferidos) * 100 : 0;
                    $color = $colors[$index % count($colors)];
                ?>
                <div class="legend-item">
                    <div class="legend-color" style="background:{{ $color }}"></div>
                    <div class="legend-info">
                        <span class="legend-name">{{ $item['name'] }}</span>
                        <div class="progress-bar-bg">
                            <div class="progress-bar-fill" style="width:{{ $percent }}%; background:{{ $color }}"></div>
                        </div>
                    </div>
                    <div class="legend-values" style="display:flex; flex-direction:column; align-items:flex-end;">
                        <span class="legend-count">{{ $item['y'] }} <span style="font-weight:400; font-size:12px; color:#9ca3af;">({{ round($percent, 1) }}%)</span></span>
                        <span class="legend-revenue" style="font-size:12px; color:#10b981; font-weight:600;">${{ number_format($item['revenue'], 0) }}</span>
                    </div>
                </div>
                @endforeach
                @if(empty($chartData))
                    <div style="text-align:center; color:#9ca3af; padding:20px;">No hay datos registrados</div>
                @endif
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
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }

    /* KPI Grid & Cards (Frecuencia Style) */
    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 42px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 14px; }
    .stat-card h3 { color: #4b5563; font-size: 16px; font-weight: 600; margin: 0; }

    /* Chart Specifics */
    .pie-chart {
        width: 250px; height: 250px; border-radius: 50%;
        position: relative; display: flex; align-items: center; justify-content: center;
        margin: 0 auto;
    }
    .pie-hole {
        width: 140px; height: 140px; background: white; border-radius: 50%;
    }

    .legend-list { display: flex; flex-direction: column; gap: 15px; }
    .legend-item { display: flex; align-items: center; gap: 12px; }
    .legend-color { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }
    .legend-info { flex: 1; text-align: left; }
    .legend-name { font-size: 14px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px; }
    .progress-bar-bg { background: #f3f4f6; height: 6px; border-radius: 3px; width: 100%; overflow: hidden; }
    .progress-bar-fill { height: 100%; }
    .legend-values { text-align: right; }
    .legend-count { font-size: 14px; font-weight: 700; color: #1f2937; display: block; }
    .legend-percent { font-size: 12px; color: #6b7280; }
    
    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
        .charts-container { grid-template-columns: 1fr !important; }
    }
</style>

@endsection
