@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Bloqueos de Agenda</h1>
        <div class="breadcrumb">Informes / Especialistas / Tiempo No Disponible</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/bloqueo-especialistas') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Consultar Bloqueos</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Bloqueos</h3>
            <div class="stat-value">{{ $locks->count() }}</div>
            <div class="stat-desc">Intervalos fuera de servicio</div>
        </div>
        <div class="stat-card">
            <h3>Especialistas</h3>
            <div class="stat-value" style="color:#f59e0b;">{{ $locks->pluck('specialist_id')->unique()->count() }}</div>
            <div class="stat-desc">Con bloqueos activos</div>
        </div>
        <div class="stat-card">
            <h3>Impacto</h3>
            <div class="stat-value" style="color:#6366f1;">Auditado</div>
            <div class="stat-desc">Tiempo no agendable</div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Historial de Bloqueos de Agenda</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th>Fecha / Día</th>
                    <th>Horario de Bloqueo</th>
                    <th>Motivo / Razón</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locks as $lock)
                <tr>
                    <td><div style="font-weight:700; color:#111827;">{{ $lock->specialist_name }}</div></td>
                    <td><div style="font-weight:600;">{{ date('d M, Y', strtotime($lock->datetime)) }}</div></td>
                    <td>
                        <div style="background:#f3f4f6; padding:4px 10px; border-radius:6px; display:inline-block; font-family:monospace; font-weight:700;">
                            {{ date('H:i', strtotime($lock->datetime)) }} (Slot)
                        </div>
                    </td>
                    <td><div style="font-size:13px; color:#4b5563;">Venta en Proceso / Bloqueo</div></td>
                    <td style="text-align:center;">
                        <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; border:1px solid #f59e0b;">ACTIVO</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:50px; color:#9ca3af;">No hay bloqueos de agenda registrados en este periodo</td>
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
    .data-table th { background: #f9fafb; padding: 15px 20px; text-align: left; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
