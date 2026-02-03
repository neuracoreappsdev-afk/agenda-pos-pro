@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Reporte de Gastos</h1>
        <div class="breadcrumb">Informes / Información General / Egresos</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/gastos') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Filtrar Gastos</button>
        </form>
    </div>

    <?php
        $totalGastos = $expenses->sum('amount');
        $countGastos = $expenses->count();
        $avgGasto = $countGastos > 0 ? $totalGastos / $countGastos : 0;
        
        // Handle empty categories safely
        $sortedCategories = $expensesByCategory->sortByDesc(function($amount) { return $amount; });
        $topCategory = $sortedCategories->keys()->first() ?: 'N/A';
        $topCategoryAmount = $sortedCategories->first() ?: 0;
    ?>

    <!-- KPI Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Egresos</h3>
            <div class="stat-value" style="color:#ef4444;">$ {{ number_format($totalGastos, 0) }}</div>
            <div class="stat-desc">{{ $countGastos }} movimientos registrados</div>
        </div>
        <div class="stat-card">
            <h3>Gasto Promedio</h3>
            <div class="stat-value">$ {{ number_format($avgGasto, 0) }}</div>
            <div class="stat-desc">Costo por transacción</div>
        </div>
        <div class="stat-card">
            <h3>Categoría Top</h3>
            <div class="stat-value" style="color:#3b82f6;">{{ strlen($topCategory) > 12 ? substr($topCategory, 0, 12).'...' : $topCategory }}</div>
            <div class="stat-desc">$ {{ number_format($topCategoryAmount, 0) }}</div>
        </div>
    </div>

    <div class="charts-container" style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-top:30px;">
        <!-- Categories List -->
        <div class="stat-card" style="text-align:left;">
            <h3 style="margin-bottom:20px;">Distribución por Concepto</h3>
            <div class="category-list">
                <?php 
                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6']; 
                    $i = 0;
                ?>
                @foreach($sortedCategories->take(6) as $category => $amount)
                <?php 
                    $percent = $totalGastos > 0 ? ($amount / $totalGastos) * 100 : 0;
                    $color = $colors[$i % count($colors)];
                    $i++;
                ?>
                <div class="category-item" style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                    <div class="color-dot" style="width:12px; height:12px; border-radius:3px; background:{{ $color }}"></div>
                    <div style="flex:1">
                        <div style="display:flex; justify-content:space-between; font-size:14px; margin-bottom:4px;">
                            <span style="font-weight:600;">{{ $category ?: 'Otros' }}</span>
                            <span style="font-weight:700;">$ {{ number_format($amount, 0) }}</span>
                        </div>
                        <div style="background:#f3f4f6; height:6px; border-radius:3px; overflow:hidden;">
                            <div style="width:{{ $percent }}%; height:100%; background:{{ $color }}"></div>
                        </div>
                    </div>
                </div>
                @endforeach
                @if($sortedCategories->isEmpty())
                    <div style="text-align:center; padding:30px; color:#9ca3af;">No hay datos para mostrar</div>
                @endif
            </div>
        </div>

        <!-- Details Table Snippet -->
        <div class="card-table">
            <div class="card-header">
                <h3>Movimientos Recientes</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Concepto</th>
                        <th style="text-align:right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses->take(10) as $exp)
                    <tr>
                        <td style="font-size:12px;">{{ date('d/m/y', strtotime($exp->movement_date)) }}</td>
                        <td style="font-weight:600;">{{ $exp->concept ?: 'Gasto' }}</td>
                        <td style="text-align:right; font-weight:700; color:#ef4444;">$ {{ number_format($exp->amount, 0) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:30px; color:#9ca3af;">Sin registros</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 15px 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; text-align: left; }
    .card-header h3 { margin: 0; font-size: 14px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 10px 20px; text-align: left; font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 12px 20px; border-bottom: 1px solid #f3f4f6; font-size: 13px; color: #4b5563; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
        .charts-container { grid-template-columns: 1fr !important; }
    }
</style>
@endsection
