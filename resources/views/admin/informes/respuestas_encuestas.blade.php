@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1>Respuestas de Encuestas</h1>
            <div class="breadcrumb">Informes / Marketing / Opinión del Cliente</div>
        </div>
        @if(count($surveys) > 0)
        <a href="{{ url('admin/surveys/create') }}" class="btn-filter" style="text-decoration:none;">Crear Encuesta</a>
        @endif
    </div>

    @if(count($surveys) == 0)
    <div class="card-table" style="margin-top:30px;">
        <div class="placeholder-content" style="padding:100px 40px; text-align:center;">
            <div style="font-size:64px; margin-bottom:20px;">📝</div>
            <h2 style="font-size:24px; font-weight:800; color:#1f2937;">Módulo de Encuestas de Satisfacción</h2>
            <p style="font-size:16px; color:#6b7280; max-width:500px; margin:0 auto 30px;">
                Aquí se visualizarán los resultados de las encuestas automáticas enviadas vía WhatsApp y Email después de cada cita.
            </p>
            <div style="display:flex; justify-content:center; gap:20px; margin-top:40px;">
                <div class="stat-mini">
                    <div style="font-size:20px; font-weight:800; color:#10b981;">98%</div>
                    <div style="font-size:11px; color:#6b7280; text-transform:uppercase;">Satisfacción</div>
                </div>
                <div class="stat-mini">
                    <div style="font-size:20px; font-weight:800; color:#1a73e8;">4.8/5</div>
                    <div style="font-size:11px; color:#6b7280; text-transform:uppercase;">Calificación</div>
                </div>
            </div>
            <a href="{{ url('admin/surveys/create') }}" class="btn-filter" style="margin-top:40px; text-decoration:none; display:inline-block;">Configurar Nueva Encuesta</a>
        </div>
    </div>
    @else
    <div class="surveys-grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap:20px; margin-top:30px;">
        @foreach($surveys as $s)
        <div class="survey-card" style="background:white; border-radius:12px; padding:20px; border:1px solid #e5e7eb; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <h3 style="font-size:16px; font-weight:700; color:#111827; margin:0;">{{ $s->title }}</h3>
                <span style="font-size:10px; font-weight:800; padding:4px 8px; border-radius:20px; {{ $s->active ? 'background:#dcfce7; color:#166534;' : 'background:#f3f4f6; color:#4b5563;' }}">
                    {{ $s->active ? 'ACTIVA' : 'PAUSADA' }}
                </span>
            </div>
            <p style="font-size:13px; color:#6b7280; margin:10px 0;">{{ $s->description }}</p>
            <div style="margin-top:15px; font-size:12px; color:#374151;">
                <strong>Trigger:</strong> Al finalizar cita | <strong>Delay:</strong> {{ $s->delay_minutes }} min
            </div>
            <div style="margin-top:20px; display:flex; gap:10px;">
                <a href="{{ url('admin/surveys/'.$s->id.'/edit') }}" style="flex:1; text-align:center; padding:8px; border:1px solid #d1d5db; border-radius:6px; color:#374151; text-decoration:none; font-size:13px; font-weight:600;">Configurar</a>
                <button style="padding:8px 12px; background:#fff1f1; color:#ef4444; border:1px solid #fee2e2; border-radius:6px; cursor:pointer;">Eliminar</button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: 700; transition: all 0.2s; }
    .btn-filter:hover { background: #1557b0; transform: translateY(-2px); }
    
    .stat-mini { background: #f9fafb; padding: 15px 25px; border-radius: 12px; border: 1px solid #e5e7eb; }
</style>
@endsection
