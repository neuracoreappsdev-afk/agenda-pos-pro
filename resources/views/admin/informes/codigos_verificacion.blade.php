@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Códigos de Verificación</h1>
        <div class="breadcrumb">Informes / Información General / Auditoría Operativa</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/codigos-verificacion') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Sincronizar Auditoría</button>
        </form>
    </div>

    <!-- KPI Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Validaciones</h3>
            <div class="stat-value">{{ count($verificaciones) }}</div>
            <div class="stat-desc">Transacciones autorizadas</div>
        </div>
        <div class="stat-card">
            <h3>Autorización</h3>
            <div class="stat-value" style="color:#10b981;">100%</div>
            <div class="stat-desc">Tasa de integridad</div>
        </div>
        <div class="stat-card">
            <h3>Sistema</h3>
            <div class="stat-value" style="color:#3b82f6;">Activo</div>
            <div class="stat-desc">Auditoría en tiempo real</div>
        </div>
    </div>

    <!-- Audit Log Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Registro Histórico de Verificación (VX)</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código VX</th>
                    <th>Timestamp</th>
                    <th>Sujeto / Cliente</th>
                    <th>Método</th>
                    <th style="text-align:right">Monto</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($verificaciones as $ver)
                <tr>
                    <td>
                        <span class="vx-code">{{ $ver['codigo'] }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ date('d M, Y', strtotime($ver['fecha'])) }}</div>
                        <div style="font-size:11px; color:#9ca3af;">{{ date('H:i:s', strtotime($ver['fecha'])) }}</div>
                    </td>
                    <td>
                        <div style="font-weight:700; color:#1f2937;">{{ $ver['cliente'] }}</div>
                        <div style="font-size:10px; color:#9ca3af; text-transform:uppercase; letter-spacing:0.5px;">Auth Secure</div>
                    </td>
                    <td>
                        <span class="method-label">{{ $ver['metodo_pago'] ?: 'Efectivo' }}</span>
                    </td>
                    <td style="text-align:right; font-weight:800; color:#111827;">
                        $ {{ number_format($ver['total'], 0) }}
                    </td>
                    <td style="text-align:center;">
                        <span class="status-badge authorized">
                            {{ $ver['estado'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px; color:#9ca3af;">No se encontraron registros de auditoría en este periodo</td>
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
    .data-table tr:hover { background: #f9fafb; }

    .vx-code { font-family: 'Courier New', Courier, monospace; font-weight: 800; background: #f3f4f6; padding: 5px 10px; border-radius: 6px; border: 1px solid #e5e7eb; color: #111827; font-size: 13px; }
    .method-label { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; background: #f9fafb; padding: 2px 8px; border-radius: 4px; border: 1px solid #e5e7eb; }
    
    .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
    .status-badge.authorized { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }

    @media (max-width: 1024px) {
        .grid-stats { grid-template-columns: 1fr; }
    }
</style>
@endsection
