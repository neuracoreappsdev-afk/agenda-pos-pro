@extends('creator.layout')

@section('content')
<div class="animate-fade">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2rem;">
        <div>
            <h1 style="font-size:24px; font-weight:700; color:#111827; letter-spacing:-0.5px;">Centro de Soporte</h1>
            <p style="color:#6b7280; font-size:14px; margin-top:4px;">Gestiona las solicitudes de asistencia de tus inquilinos.</p>
        </div>
        <div style="display:flex; align-items:center; gap:8px;">
            <div style="background:#f0fdf4; color:#166534; font-size:12px; font-weight:600; padding:6px 12px; border-radius:99px; display:flex; align-items:center; gap:6px;">
                <span style="width:6px; height:6px; background:#166534; border-radius:50%;"></span>
                SISTEMA OPERATIVO
            </div>
        </div>
    </div>

    <!-- Ticket Stats -->
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:24px;">
        <div class="std-card" style="padding:16px;">
            <div style="color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Tickets Abiertos</div>
            <div style="font-size:24px; font-weight:700; color:#111827; margin-top:4px;">{{ $tickets->where('status', 'open')->count() }}</div>
        </div>
        <div class="std-card" style="padding:16px;">
            <div style="color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">En Progreso</div>
            <div style="font-size:24px; font-weight:700; color:#111827; margin-top:4px;">{{ $tickets->where('status', 'in_progress')->count() }}</div>
        </div>
        <div class="std-card" style="padding:16px;">
            <div style="color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Tiempo Respuesta</div>
            <div style="font-size:24px; font-weight:700; color:#111827; margin-top:4px;">14m</div>
        </div>
        <div class="std-card" style="padding:16px;">
            <div style="color:#166534; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Resueltos Hoy</div>
            <div style="font-size:24px; font-weight:700; color:#111827; margin-top:4px;">5</div>
        </div>
    </div>

    <div class="std-card" style="padding:0; overflow:hidden;">
        <div style="padding:20px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:16px; font-weight:700; color:#111827;">Bandeja de Entrada Global</h3>
            <div>
                <select style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px; color:#374151; outline:none;">
                    <option>Todos los estados</option>
                    <option>Abiertos</option>
                    <option>Cerrados</option>
                </select>
            </div>
        </div>

        <style>
            .ticket-table { width: 100%; border-collapse: collapse; }
            .ticket-table th { text-align: left; padding: 12px 20px; background: #f9fafb; color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e7eb; }
            .ticket-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; color: #374151; font-size: 14px; }
            .ticket-row:hover { background: #f9fafb; cursor: pointer; }
            
            .priority-chip { font-size: 11px; padding: 2px 8px; border-radius: 4px; font-weight: 600; display: inline-block; }
            .priority-urgent { background: #fee2e2; color: #991b1b; }
            .priority-normal { background: #eff6ff; color: #1e40af; }
            
            .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
        </style>

        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Inquilino</th>
                    <th>Asunto</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Actualizado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $t)
                <tr class="ticket-row" onclick="window.location.href='{{ url('creator/ticket/'.$t->id) }}'">
                    <td>
                        <div style="font-weight:600; color:#111827;">{{ $t->business ? $t->business->name : 'Soporte General' }}</div>
                        <div style="font-size:12px; color:#6b7280;">ID: #{{ $t->id }}</div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $t->subject }}</div>
                        <div style="font-size:12px; color:#6b7280; max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $t->description }}</div>
                    </td>
                    <td>
                        <span class="priority-chip {{ $t->priority == 'urgent' ? 'priority-urgent' : 'priority-normal' }}">
                            {{ strtoupper($t->priority) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; font-size:13px; font-weight:500;">
                            <span class="status-dot" style="background:{{ $t->status == 'open' ? '#ef4444' : '#22c55e' }}"></span>
                            {{ ucfirst(str_replace('_', ' ', $t->status)) }}
                        </div>
                    </td>
                    <td style="color:#6b7280; font-size:13px;">{{ $t->updated_at->diffForHumans() }}</td>
                </tr>
                @empty
                <!-- Mock Ticket 1 -->
                <tr class="ticket-row">
                    <td>
                        <div style="font-weight:600; color:#111827;">Imperial Salon</div>
                        <div style="font-size:12px; color:#6b7280;">ID: #DEMO-001</div>
                    </td>
                    <td>
                        <div style="font-weight:500; color:#111827;">Problema con facturación electrónica</div>
                        <div style="font-size:12px; color:#6b7280;">La DIAN rechaza el XML generado...</div>
                    </td>
                    <td><span class="priority-chip priority-urgent">URGENTE</span></td>
                    <td>
                        <div style="display:flex; align-items:center; font-size:13px; font-weight:500;">
                            <span class="status-dot" style="background:#ef4444"></span>
                            Abierto
                        </div>
                    </td>
                    <td style="color:#6b7280; font-size:13px;">Hace 12 min</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
