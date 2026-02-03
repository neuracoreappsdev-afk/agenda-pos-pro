@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Movimientos de Inventario</h1>
        <div class="breadcrumb">Informes / Inventario / Auditoría de Kárdex</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/movimientos-inventario') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Ver Auditoría</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Entradas</h3>
            <div class="stat-value" style="color:#10b981;">+ {{ $logs->where('type', 'addition')->sum('quantity') }}</div>
            <div class="stat-desc">Aumentos de stock registrados</div>
        </div>
        <div class="stat-card">
            <h3>Salidas / Ventas</h3>
            <div class="stat-value" style="color:#ef4444;">- {{ $logs->where('type', 'subtraction')->sum('quantity') }}</div>
            <div class="stat-desc">Disminuciones de stock</div>
        </div>
        <div class="stat-card">
            <h3>Ajustes Manuales</h3>
            <div class="stat-value">{{ $logs->where('type', 'adjustment')->count() }}</div>
            <div class="stat-desc">Intervenciones administrativas</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Historial Cronológico de Movimientos</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Producto</th>
                    <th style="text-align:center">Tipo</th>
                    <th style="text-align:center">Cantidad</th>
                    <th>Motivo / Observación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ date('d/m/Y H:i', strtotime($log->created_at)) }}</td>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $log->product ? $log->product->name : 'N/A' }}</div>
                    </td>
                    <td style="text-align:center;">
                        @if($log->type == 'addition')
                            <span class="type-badge addition">ENTRADA</span>
                        @elseif($log->type == 'subtraction')
                            <span class="type-badge subtraction">SALIDA</span>
                        @else
                            <span class="type-badge adjustment">AJUSTE</span>
                        @endif
                    </td>
                    <td style="text-align:center; font-weight:800; color:{{ $log->type == 'addition' ? '#10b981' : ($log->type == 'subtraction' ? '#ef4444' : '#6b7280') }}">
                        {{ $log->type == 'addition' ? '+' : ($log->type == 'subtraction' ? '-' : '') }}{{ $log->quantity }}
                    </td>
                    <td style="font-size:12px; color:#6b7280;">{{ $log->reason ?: 'Sin detalle' }}</td>
                </tr>
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

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    
    .type-badge { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; }
    .addition { background: #dcfce7; color: #166534; }
    .subtraction { background: #fee2e2; color: #991b1b; }
    .adjustment { background: #f3f4f6; color: #374151; }
</style>
@endsection
