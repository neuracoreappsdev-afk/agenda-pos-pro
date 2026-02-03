@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Estado de Resultados</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / Información General / Estado de Resultados</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/estado-resultados') }}" class="date-form">
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
            <h3>Ingresos Brutos</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($totalSales, 0) }}</div>
            <div class="stat-desc">{{ $salesCount }} ventas realizadas</div>
        </div>
        <div class="stat-card">
            <h3>Egresos y Costos</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalCost, 0) }}</div>
            <div class="stat-desc">Gastos + Comisiones</div>
        </div>
        <div class="stat-card">
            <h3>Utilidad Neta</h3>
            <div class="stat-value" style="color: {{ $netProfit >= 0 ? '#10b981' : '#ef4444' }};">$ {{ number_format($netProfit, 0) }}</div>
            <div class="stat-desc">Resultado del periodo</div>
        </div>
        <div class="stat-card">
            <h3>Margen de Ganancia</h3>
            <div class="stat-value" style="color:#3b82f6;">{{ round($margin, 1) }}%</div>
            <div class="stat-desc">Rentabilidad operativa</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Financial Breakdown -->
        <div class="card-main">
            <div class="card-header">
                <h3>Desglose del Ejercicio</h3>
            </div>
            <table class="breakdown-table">
                <tbody>
                    <tr>
                        <td class="label-cell">Ingresos por Servicios</td>
                        <td class="value-cell">$ {{ number_format($serviceRevenue, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">Ingresos por Productos</td>
                        <td class="value-cell">$ {{ number_format($productRevenue, 0) }}</td>
                    </tr>
                    <tr class="subtotal-row">
                        <td class="label-cell"><strong>INGRESOS TOTALES</strong></td>
                        <td class="value-cell"><strong>$ {{ number_format($totalSales, 0) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label-cell">(-) Gastos Administrativos</td>
                        <td class="value-cell expense">-$ {{ number_format($totalExpenses, 0) }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell">(-) Comisiones Especialistas</td>
                        <td class="value-cell expense">-$ {{ number_format($totalComisiones, 0) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td class="label-cell">UTILIDAD NETA</td>
                        <td class="value-cell profit">$ {{ number_format($netProfit, 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Visual Chart -->
        <div class="card-chart">
            <h3>Distribución de Flujo</h3>
            <div class="bar-chart">
                <div class="bar-item">
                    <div class="bar" style="height: 100%; background: #111827;"></div>
                    <div class="bar-label">Ingresos</div>
                    <div class="bar-value">$ {{ number_format($totalSales, 0) }}</div>
                </div>
                <div class="bar-item">
                    <div class="bar" style="height: {{ $heightGastos }}%; background: #ef4444;"></div>
                    <div class="bar-label">Egresos</div>
                    <div class="bar-value">$ {{ number_format($totalCost, 0) }}</div>
                </div>
                <div class="bar-item">
                    <div class="bar" style="height: {{ max(5, $heightUtilidad) }}%; background: #10b981;"></div>
                    <div class="bar-label">Utilidad</div>
                    <div class="bar-value">$ {{ number_format($netProfit, 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="card-expenses">
        <div class="card-header">
            <h3>Últimos Egresos Registrados</h3>
        </div>
        <div class="expenses-list">
            @forelse($expenses->take(10) as $exp)
            <div class="expense-item">
                <div class="expense-info">
                    <div class="expense-concept">{{ $exp->concept ?? 'Gasto operativo' }}</div>
                    <div class="expense-date">{{ date('d M Y', strtotime($exp->movement_date)) }}</div>
                </div>
                <div class="expense-amount">-$ {{ number_format($exp->amount, 0) }}</div>
            </div>
            @empty
            <div class="empty-state">
                <div style="font-size:32px; margin-bottom:10px;">📋</div>
                <p>No hay egresos registrados en el periodo</p>
            </div>
            @endforelse
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

    .grid-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 12px; padding: 25px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px; }
    
    .card-main, .card-chart, .card-expenses { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1f2937; }
    
    .breakdown-table { width: 100%; border-collapse: collapse; }
    .breakdown-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; }
    .label-cell { font-size: 14px; color: #6b7280; }
    .value-cell { text-align: right; font-size: 14px; font-weight: 600; color: #1f2937; }
    .value-cell.expense { color: #ef4444; }
    .value-cell.profit { font-size: 24px; font-weight: 800; color: #10b981; }
    .subtotal-row { background: #f9fafb; }
    .total-row { background: #111827; }
    .total-row .label-cell { color: white; font-weight: 700; font-size: 12px; letter-spacing: 1px; }
    .total-row .value-cell { color: white; }

    .card-chart { padding: 25px; display: flex; flex-direction: column; }
    .card-chart h3 { font-size: 14px; font-weight: 600; color: #4b5563; margin-bottom: 20px; text-align: center; }
    .bar-chart { display: flex; justify-content: center; align-items: flex-end; height: 200px; gap: 30px; }
    .bar-item { display: flex; flex-direction: column; align-items: center; width: 60px; }
    .bar { width: 40px; border-radius: 8px 8px 0 0; transition: height 0.5s; }
    .bar-label { font-size: 11px; font-weight: 600; color: #6b7280; margin-top: 10px; text-transform: uppercase; }
    .bar-value { font-size: 11px; font-weight: 700; color: #1f2937; margin-top: 5px; }

    .expenses-list { max-height: 300px; overflow-y: auto; }
    .expense-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; border-bottom: 1px solid #f3f4f6; }
    .expense-item:hover { background: #f9fafb; }
    .expense-concept { font-size: 14px; font-weight: 600; color: #1f2937; }
    .expense-date { font-size: 12px; color: #9ca3af; margin-top: 3px; }
    .expense-amount { font-size: 14px; font-weight: 700; color: #ef4444; }
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: repeat(2, 1fr); }
        .content-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
