@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h1>Planes y Membresías</h1>
            <div class="breadcrumb">Informes / Comercial / Gestión de Suscripciones</div>
        </div>
        @if(count($plans) > 0)
        <a href="{{ url('admin/planes/create') }}" class="btn-filter" style="text-decoration:none;">Crear Nuevo Plan</a>
        @endif
    </div>

    @if(count($plans) == 0)
    <div class="card-table" style="margin-top:30px;">
        <div class="placeholder-content" style="padding:100px 40px; text-align:center;">
            <div style="font-size:64px; margin-bottom:20px;">💎</div>
            <h2 style="font-size:24px; font-weight:800; color:#1f2937;">Programa de Membresías y Planes Recurrentes</h2>
            <p style="font-size:16px; color:#6b7280; max-width:550px; margin:0 auto 30px;">
                Administra los planes de servicios prepagados y membresías VIP. Visualiza el vencimiento de cupos y la rentabilidad por suscriptor.
            </p>
            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; max-width:600px; margin:40px auto 0;">
                <div style="background:#eff6ff; padding:20px; border-radius:12px; border:1px solid #dbeafe;">
                    <div style="font-size:11px; font-weight:700; color:#1e40af; text-transform:uppercase;">Planes Activos</div>
                    <div style="font-size:24px; font-weight:900; color:#111827; margin-top:5px;">--</div>
                </div>
                <div style="background:#f0fdf4; padding:20px; border-radius:12px; border:1px solid #dcfce7;">
                    <div style="font-size:11px; font-weight:700; color:#166534; text-transform:uppercase;">Recaudo Recurrente</div>
                    <div style="font-size:24px; font-weight:900; color:#111827; margin-top:5px;">$ --</div>
                </div>
                <div style="background:#fff7ed; padding:20px; border-radius:12px; border:1px solid #ffedd5;">
                    <div style="font-size:11px; font-weight:700; color:#9a3412; text-transform:uppercase;">Vencimientos Hoy</div>
                    <div style="font-size:24px; font-weight:900; color:#111827; margin-top:5px;">0</div>
                </div>
            </div>
            <a href="{{ url('admin/planes/create') }}" class="btn-filter" style="margin-top:40px; text-decoration:none; display:inline-block;">Crear Nuevo Plan Comercial</a>
        </div>
    </div>
    @else
    <div class="plans-grid" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:25px; margin-top:30px;">
        @foreach($plans as $p)
        <div class="plan-card" style="background:white; border-radius:16px; border:1px solid #e5e7eb; padding:25px; box-shadow:0 1px 3px rgba(0,0,0,0.1); position:relative;">
            <div style="position:absolute; top:20px; right:20px;">
                <span class="status-badge {{ $p->is_active ? 'active' : 'expired' }}">{{ $p->is_active ? 'ACTIVO' : 'INACTIVO' }}</span>
            </div>
            <div style="font-size:32px; margin-bottom:15px;">{{ $p->max_uses ? '🎟️' : '💎' }}</div>
            <h3 style="font-size:18px; font-weight:800; color:#111827; margin:0 0 5px;">{{ $p->name }}</h3>
            <div style="color:#6b7280; font-size:13px; margin-bottom:20px;">{{ $p->validity_days }} días de validez</div>
            
            <div style="margin-bottom:20px; padding:15px; background:#f9fafb; border-radius:10px;">
                <div style="font-size:22px; font-weight:900; color:#1a73e8;">$ {{ number_format($p->price, 0) }}</div>
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; font-weight:700;">Precio de venta</div>
            </div>

            <div style="margin-bottom:25px;">
                <div style="font-size:11px; font-weight:700; color:#4b5563; text-transform:uppercase; margin-bottom:10px;">Incluye:</div>
                <ul style="padding:0; margin:0; list-style:none; font-size:13px; color:#4b5563;">
                    @if(isset($p->benefits_json['services']))
                        <li style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                            <span style="color:#10b981;">✓</span> {{ count($p->benefits_json['services']) }} Servicios Técnicos
                        </li>
                    @endif
                    @if($p->max_uses)
                        <li style="display:flex; align-items:center; gap:8px; margin-bottom:5px;">
                            <span style="color:#10b981;">✓</span> {{ $p->max_uses }} Cupos/Tiquetes
                        </li>
                    @endif
                </ul>
            </div>

            <div style="display:flex; gap:10px;">
                <a href="{{ url('admin/planes/'.$p->id.'/edit') }}" style="flex:1; text-align:center; padding:10px; border:1px solid #e5e7eb; border-radius:8px; color:#374151; text-decoration:none; font-size:13px; font-weight:700; transition:all 0.2s;">Editar</a>
                <button style="width:40px; border:1px solid #fee2e2; background:#fff1f1; color:#ef4444; border-radius:8px; cursor:pointer;">×</button>
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

    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.expired { background: #fee2e2; color: #991b1b; }
</style>
@endsection
