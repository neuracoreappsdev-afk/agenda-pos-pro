@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Analítica de Comunicación</h1>
        <div class="breadcrumb">Informes / Operaciones / Chat y WhatsApp</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Mensajes</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $totalMessages }}</div>
            <div class="stat-desc">Interacciones históricas</div>
        </div>
        <div class="stat-card">
            <h3>Por Leer / Pendientes</h3>
            <div class="stat-value" style="color:#f59e0b;">{{ $unreadMessages }}</div>
            <div class="stat-desc">Conversaciones que requieren atención</div>
        </div>
        <div class="stat-card">
            <h3>Tasa de Respuesta</h3>
            <div class="stat-value" style="color:#10b981;">92%</div>
            <div class="stat-desc">Eficiencia del contact center</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
             <h3>Actividad Reciente en Chats</h3>
             <a href="{{ url('admin/chats') }}" class="btn-filter" style="text-decoration:none; font-size:12px;">Ir al Centro de Mensajería</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Emisor</th>
                    <th>Mensaje (Fragmento)</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentMessages as $msg)
                <tr>
                    <td>{{ date('d/m/Y H:i', strtotime($msg->created_at)) }}</td>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $msg->sender_name ?: ($msg->from_admin ? 'ADMIN' : 'ESPECIALISTA') }}</div>
                    </td>
                    <td style="font-style:italic; color:#4b5563;">"{{ substr($msg->message, 0, 80) }}..."</td>
                    <td style="text-align:center;">
                        @if($msg->is_read)
                            <span class="status-badge" style="background:#f3f4f6; color:#9ca3af;">LEÍDO</span>
                        @else
                            <span class="status-badge" style="background:#eff6ff; color:#1e40af; border:1px solid #3b82f6;">NUEVO</span>
                        @endif
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
    
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }
</style>
@endsection
