@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Movimientos de Caja</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / Caja y Bancos / Movimientos</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/movimientos-caja') }}" class="date-form">
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

    <!-- KPI Cards -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Ingresos</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($totalIngresos, 0) }}</div>
            <div class="stat-desc">Entradas de efectivo</div>
        </div>
        <div class="stat-card">
            <h3>Total Egresos</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalEgresos, 0) }}</div>
            <div class="stat-desc">Salidas de efectivo</div>
        </div>
        <div class="stat-card">
            <h3>Saldo Neto</h3>
            <div class="stat-value" style="color: {{ $saldoNeto >= 0 ? '#10b981' : '#ef4444' }};">$ {{ number_format($saldoNeto, 0) }}</div>
            <div class="stat-desc">Balance del periodo</div>
        </div>
    </div>

    <!-- Charts Container -->
    <div class="charts-container">
        <!-- Payment Methods -->
        <div class="stat-card" style="text-align:left;">
            <h3 style="margin-bottom:20px;">Ingresos por Método de Pago</h3>
            <div class="method-list">
                <?php
                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
                    $totalMethods = 0;
                    if(isset($ingresosPorMetodo) && $ingresosPorMetodo->count() > 0) {
                        $totalMethods = $ingresosPorMetodo->sum();
                    }
                    $idx = 0;
                ?>
                @if(isset($ingresosPorMetodo) && $ingresosPorMetodo->count() > 0)
                    @foreach($ingresosPorMetodo as $method => $amount)
                    <?php
                        $percent = $totalMethods > 0 ? ($amount / $totalMethods) * 100 : 0;
                        $color = $colors[$idx % count($colors)];
                        $idx++;
                    ?>
                    <div class="method-item">
                        <div class="method-color" style="background:{{ $color }}"></div>
                        <div class="method-info">
                            <span class="method-name">{{ $method ?: 'Otro' }}</span>
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width:{{ $percent }}%; background:{{ $color }}"></div>
                            </div>
                        </div>
                        <div class="method-values">
                            <span class="method-amount">$ {{ number_format($amount, 0) }}</span>
                            <span class="method-percent">{{ round($percent, 1) }}%</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-small">Sin movimientos de ingreso</div>
                @endif
            </div>
        </div>

        <!-- Balance Visual -->
        <div class="stat-card" style="display:flex; flex-direction:column; align-items:center;">
            <h3 style="margin-bottom:25px;">Balance Visual</h3>
            <div class="balance-bars">
                <div class="balance-bar-wrapper">
                    <div class="balance-bar income" style="height: 100%;"></div>
                    <div class="balance-label">Ingresos</div>
                    <div class="balance-value income">$ {{ number_format($totalIngresos, 0) }}</div>
                </div>
                <div class="balance-bar-wrapper">
                    <?php $egresoHeight = $totalIngresos > 0 ? min(100, ($totalEgresos / $totalIngresos) * 100) : 50; ?>
                    <div class="balance-bar expense" style="height: {{ $egresoHeight }}%;"></div>
                    <div class="balance-label">Egresos</div>
                    <div class="balance-value expense">$ {{ number_format($totalEgresos, 0) }}</div>
                </div>
                <div class="balance-bar-wrapper">
                    <?php $saldoHeight = $totalIngresos > 0 ? min(100, max(10, abs($saldoNeto / max(1, $totalIngresos)) * 100)) : 50; ?>
                    <div class="balance-bar {{ $saldoNeto >= 0 ? 'profit' : 'expense' }}" style="height: {{ $saldoHeight }}%;"></div>
                    <div class="balance-label">Saldo</div>
                    <div class="balance-value {{ $saldoNeto >= 0 ? 'profit' : 'expense' }}">$ {{ number_format($saldoNeto, 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card-table">
        <div class="card-header">
            <h3>Historial de Movimientos</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Concepto</th>
                    <th>Método</th>
                    <th>Referencia</th>
                    <th style="text-align:right">Monto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $mov)
                <tr>
                    <td>{{ date('d M Y', strtotime($mov->movement_date)) }}</td>
                    <td>
                        @if(in_array($mov->type, ['ingreso', 'income', 'tip']))
                            <span class="type-badge income">Ingreso</span>
                        @else
                            <span class="type-badge expense">Egreso</span>
                        @endif
                    </td>
                    <td><strong>{{ $mov->concept ?? 'Movimiento' }}</strong></td>
                    <td>{{ $mov->payment_method ?? 'Efectivo' }}</td>
                    <td>{{ $mov->reference ?? '---' }}</td>
                    <td style="text-align:right; font-weight:700;">
                        @if(in_array($mov->type, ['ingreso', 'income', 'tip']))
                            <span style="color:#10b981;">+$ {{ number_format($mov->amount, 0) }}</span>
                        @else
                            <span style="color:#ef4444;">-$ {{ number_format($mov->amount, 0) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div style="font-size:32px; margin-bottom:10px;">💰</div>
                        <p>No hay movimientos registrados en el periodo</p>
                    </td>
                </tr>
                @endforelse
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
    .control { border: 1px solid #d1d5db; padding: 8px 12px; border-radius: 6px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 12px; padding: 25px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 36px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .charts-container { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px; }

    .method-list { display: flex; flex-direction: column; gap: 15px; }
    .method-item { display: flex; align-items: center; gap: 12px; }
    .method-color { width: 12px; height: 12px; border-radius: 3px; flex-shrink: 0; }
    .method-info { flex: 1; }
    .method-name { font-size: 14px; font-weight: 600; color: #374151; display: block; margin-bottom: 4px; }
    .progress-bar-bg { background: #f3f4f6; height: 6px; border-radius: 3px; width: 100%; overflow: hidden; }
    .progress-bar-fill { height: 100%; }
    .method-values { text-align: right; min-width: 100px; }
    .method-amount { font-size: 14px; font-weight: 700; color: #1f2937; display: block; }
    .method-percent { font-size: 12px; color: #6b7280; }
    .empty-small { text-align: center; color: #9ca3af; padding: 20px; }

    .balance-bars { display: flex; justify-content: center; align-items: flex-end; height: 180px; gap: 40px; }
    .balance-bar-wrapper { display: flex; flex-direction: column; align-items: center; width: 70px; height: 100%; }
    .balance-bar { width: 50px; border-radius: 8px 8px 0 0; min-height: 10px; }
    .balance-bar.income { background: #10b981; }
    .balance-bar.expense { background: #ef4444; }
    .balance-bar.profit { background: #3b82f6; }
    .balance-label { font-size: 11px; font-weight: 600; color: #6b7280; margin-top: 10px; text-transform: uppercase; }
    .balance-value { font-size: 11px; font-weight: 700; margin-top: 5px; }
    .balance-value.income { color: #10b981; }
    .balance-value.expense { color: #ef4444; }
    .balance-value.profit { color: #3b82f6; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1f2937; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    .data-table tr:hover { background: #f9fafb; }
    
    .type-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .type-badge.income { background: #dcfce7; color: #166534; }
    .type-badge.expense { background: #fee2e2; color: #991b1b; }
    
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
        .charts-container { grid-template-columns: 1fr; }
    }
</style>
@endsection
