@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ trans('messages.sales_by_advisor') }}</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / {{ trans('messages.clients') }} / {{ trans('messages.sales_by_advisor') }}</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-por-asesor') }}" class="date-form">
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

    @foreach($stats as $specialist)
    <div class="specialist-group">
        <div class="group-header">
            <div class="specialist-info">
                <div class="avatar">{{ substr($specialist->specialist_name, 0, 1) }}</div>
                <h3>{{ $specialist->specialist_name }}</h3>
            </div>
            <!-- Progress Bar based on max revenue would be cool, but keeping it simple for now -->
            <div class="revenue-badge">${{ number_format($specialist->total_revenue, 0) }}</div>
        </div>
        
        <div class="grid-stats">
            <div class="stat-card mini">
                <div class="label">Ventas Totales</div>
                <div class="value">{{ $specialist->total_sales }}</div>
            </div>
            <div class="stat-card mini">
                <div class="label">Clientes Únicos</div>
                <div class="value">{{ $specialist->unique_clients }}</div>
            </div>
            <div class="stat-card mini">
                <div class="label">Tasa de Retención</div>
                <div class="value {{ $specialist->retention_rate > 30 ? 'good' : 'meh' }}">
                    {{ number_format($specialist->retention_rate, 1) }}%
                </div>
            </div>
            <div class="stat-card mini highlight">
                <div class="label">Mejor Cliente</div>
                <div class="value-text">{{ $specialist->top_client_name }}</div>
                <div class="sub-value">${{ number_format($specialist->top_client_amount, 0) }}</div>
            </div>
        </div>
    </div>
    @endforeach

    @if(count($stats) == 0)
        <div style="text-align:center; padding:50px; color:#9ca3af;">No se encontraron ventas para este rango de fechas.</div>
    @endif
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

    .specialist-group { background: white; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 30px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .group-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #f3f4f6; }
    .specialist-info { display: flex; align-items: center; gap: 15px; }
    .avatar { width: 45px; height: 45px; background: #e0e7ff; color: #4338ca; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 20px; }
    .group-header h3 { margin: 0; color: #1f2937; font-size: 18px; }
    .revenue-badge { background: #ecfdf5; color: #059669; padding: 8px 15px; border-radius: 20px; font-weight: 700; font-size: 16px; border: 1px solid #d1fae5; }

    .grid-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
    .stat-card.mini { background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; }
    .stat-card.mini.highlight { background: #eff6ff; border-color: #dbeafe; }
    
    .label { font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .value { font-size: 24px; font-weight: 700; color: #1f2937; }
    .value.good { color: #10b981; }
    .value.meh { color: #f59e0b; }
    
    .value-text { font-size: 16px; font-weight: 700; color: #1e3a8a; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sub-value { font-size: 13px; color: #60a5fa; font-weight: 600; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 600px) {
        .grid-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection
