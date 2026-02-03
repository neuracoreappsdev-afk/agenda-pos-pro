@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Comisiones Bancarias (Franquicias)</h1>
        <div class="breadcrumb">Informes / Caja / Tarjetas Crédito/Débito</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/porcentaje-franquicias') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Analizar Franquicias</button>
        </form>
    </div>

    <!-- KPIs -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Volumen Tarjeta</h3>
            <div class="stat-value" style="color:#111827;">$ {{ number_format($totalSales, 0) }}</div>
            <div class="stat-desc">Ventas totales procesadas</div>
        </div>
        <div class="stat-card">
            <h3>Transacciones</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ count($sales) }}</div>
            <div class="stat-desc">Operaciones exitosas</div>
        </div>
        <div class="stat-card">
            <h3>Est. Comisiones</h3>
            <?php $estimatedComm = $totalSales * 0.029; // Promedio estimado 2.9% ?>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($estimatedComm, 0) }}</div>
            <div class="stat-desc">Costo financiero proyectado</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:25px;">
        <div class="card-header">
            <h3>Distribución por Franquicia</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Franquicia</th>
                    <th style="text-align:center">Operaciones</th>
                    <th style="text-align:right">Total Procesado</th>
                    <th>Participación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $franquicias = [
                        'VISA' => ['total' => 0, 'count' => 0, 'color' => '#1a73e8'],
                        'MASTERCARD' => ['total' => 0, 'count' => 0, 'color' => '#eb001b'],
                        'AMEX' => ['total' => 0, 'count' => 0, 'color' => '#007bc1'],
                        'OTRAS' => ['total' => 0, 'count' => 0, 'color' => '#6b7280']
                    ];
                    
                    foreach($sales as $sale) {
                        $f = 'OTRAS';
                        $notes = strtoupper($sale->notes);
                        if(strpos($notes, 'VISA') !== false) $f = 'VISA';
                        elseif(strpos($notes, 'MASTERCARD') !== false) $f = 'MASTERCARD';
                        elseif(strpos($notes, 'AMEX') !== false) $f = 'AMEX';
                        
                        $franquicias[$f]['total'] += $sale->total;
                        $franquicias[$f]['count']++;
                    }
                ?>
                @foreach($franquicias as $nombre => $fData)
                @if($fData['count'] > 0 || $nombre == 'VISA')
                <tr>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:10px; height:10px; border-radius:50%; background:{{ $fData['color'] }}"></div>
                            <span style="font-weight:700;">{{ $nombre }}</span>
                        </div>
                    </td>
                    <td style="text-align:center;">{{ $fData['count'] }}</td>
                    <td style="text-align:right; font-weight:700;">$ {{ number_format($fData['total'], 0) }}</td>
                    <td>
                        <?php $p = $totalSales > 0 ? ($fData['total'] / $totalSales) * 100 : 0; ?>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="flex:1; height:6px; background:#f3f4f6; border-radius:10px; overflow:hidden;">
                                <div style="background:{{ $fData['color'] }}; width:{{ $p }}%; height:100%;"></div>
                            </div>
                            <span style="font-size:11px; font-weight:700;">{{ round($p) }}%</span>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
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
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
