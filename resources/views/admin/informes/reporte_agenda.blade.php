@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Reporte de Agenda y Reservas</h1>
        <div class="breadcrumb">Informes / Operaciones / Control de Citas</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/reporte-agenda') }}" class="date-form">
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Ver Reservas</button>
            <button type="button" class="btn-filter" style="background:#1f2937;" onclick="window.print()">Imprimir Agenda</button>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Citas</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $appointments->count() }}</div>
            <div class="stat-desc">Reservas en el periodo</div>
        </div>
        <div class="stat-card">
            <h3>Confirmadas</h3>
            <div class="stat-value" style="color:#10b981;">{{ $appointments->where('status', 'confirmed')->count() }}</div>
            <div class="stat-desc">Clientes validados</div>
        </div>
        <div class="stat-card">
            <h3>Ausencias / Canceladas</h3>
            <div class="stat-value" style="color:#ef4444;">{{ $appointments->where('status', 'cancelled')->count() }}</div>
            <div class="stat-desc">Turnos no facturados</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Programación Detallada de Servicios</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Cliente</th>
                    <th>Servicio / Especialista</th>
                    <th style="text-align:center">Duración</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                <tr>
                    <td><div style="font-weight:800; color:#111827;">{{ date('H:i', strtotime($appt->booking_time)) }}</div><div style="font-size:10px; color:#9ca3af;">{{ date('d-m-Y', strtotime($appt->booking_date)) }}</div></td>
                    <td>
                        <div style="font-weight:700;">{{ $appt->customer ? $appt->customer->name : 'Consumidor Final' }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $appt->customer ? $appt->customer->contact_number : '' }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1a73e8;">{{ $appt->package ? $appt->package->package_name : 'Servicio' }}</div>
                        <div style="font-size:11px; color:#6b7280;">Staff: {{ $appt->specialist ? $appt->specialist->name : 'Cualquiera' }}</div>
                    </td>
                    <td style="text-align:center;"><span class="time-tag">{{ $appt->duration ?: ($appt->package ? $appt->package->package_time : 30) }} min</span></td>
                    <td style="text-align:center;">
                        <span class="status-badge {{ $appt->status }}">{{ strtoupper($appt->status ?: 'PENDIENTE') }}</span>
                    </td>
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
    
    .time-tag { background: #f3f4f6; color: #4b5563; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.confirmed { background: #dcfce7; color: #166534; }
    .status-badge.cancelled { background: #fee2e2; color: #991b1b; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
</style>
@endsection
