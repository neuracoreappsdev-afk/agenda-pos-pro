@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ $pageTitle ?? 'Reporte' }}</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / {{ $pageTitle ?? 'Reporte' }}</div>
    </div>

    <div class="placeholder-content">
        <div class="placeholder-icon">📊</div>
        <h2>{{ $pageTitle ?? 'Reporte' }}</h2>
        <p>Este reporte está en desarrollo. El contenido estará disponible próximamente.</p>
        <a href="{{ url('admin/informes') }}" class="btn-back">← Volver a Informes</a>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .placeholder-content {
        background: white;
        border-radius: 12px;
        padding: 60px 40px;
        text-align: center;
        margin-top: 30px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }
    .placeholder-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }
    .placeholder-content h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }
    .placeholder-content p {
        color: #6b7280;
        max-width: 500px;
        margin: 0 auto 30px;
    }
    .btn-back {
        display: inline-block;
        background: #1a73e8;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
    }
</style>
@endsection
