@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Log de Usuarios y Auditoría</h1>
        <div class="breadcrumb">Informes / Seguridad / Trazabilidad de Acciones</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Eventos Registrados</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $logs->total() }}</div>
            <div class="stat-desc">Acciones auditadas en el sistema</div>
        </div>
        <div class="stat-card">
            <h3>Usuarios Activos</h3>
            <div class="stat-value">{{ $logs->groupBy('user_id')->count() }}</div>
            <div class="stat-desc">Operadores que han realizado acciones</div>
        </div>
        <div class="stat-card">
            <h3>Nivel de Seguridad</h3>
            <div class="stat-value" style="color:#10b981;">ALTO</div>
            <div class="stat-desc">Todos los eventos están siendo firmados</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Historial de Interacciones y Cambios</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Usuario / Operador</th>
                    <th>Acción / Evento</th>
                    <th style="text-align:center">IP / Origen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>{{ date('d/m/Y H:i:s', strtotime($log->created_at)) }}</td>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $log->user_name ?: 'Sistema / IA' }}</div>
                        <div style="font-size:11px; color:#6b7280;">ID Operador: #{{ $log->user_id ?: '0' }}</div>
                    </td>
                    <td>
                        <div style="font-family:monospace; font-size:13px; color:#4b5563;">{{ $log->prompt ?: ($log->action ?: 'Interacción IA registrada') }}</div>
                    </td>
                    <td style="text-align:center; font-family:monospace; color:#9ca3af;">{{ $log->ip_address ?: '127.0.0.1' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-container">
            {!! $logs->render() !!}
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
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

    .pagination-container { padding: 20px; display: flex; justify-content: center; }
    .pagination { display: flex; list-style: none; gap: 5px; }
    .pagination li span, .pagination li a { padding: 8px 14px; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #374151; font-weight: 600; }
    .pagination li.active span { background: #1a73e8; color: white; border-color: #1a73e8; }
</style>
@endsection
