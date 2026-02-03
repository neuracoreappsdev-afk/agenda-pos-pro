@extends('admin/dashboard_layout')

@section('content')
<div class="app-main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ trans('messages.vouchers_title') }}</h1>
            <div class="breadcrumb">
                <a href="{{ url('admin/dashboard') }}">{{ trans('messages.home') }}</a> / 
                <a href="{{ url('admin/ventas') }}">{{ trans('messages.sales') }}</a> / 
                {{ trans('messages.vouchers_title') }}
            </div>
        </div>
        <div>
             <button class="btn btn-primary" onclick="openCreateBonoModal()">
                <span>+</span> {{ trans('messages.new') }} Bono
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Bonos Activos</div>
            <div class="stat-value">{{ $stats['active_count'] }}</div>
            <div class="stat-trend neutral">Total en circulación</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Saldo Pendiente</div>
            <div class="stat-value">${{ number_format($stats['active_balance'], 0) }}</div>
            <div class="stat-trend">Dinero por redimir</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Canjeados este mes</div>
            <div class="stat-value">${{ number_format($stats['redeemed_month'], 0) }}</div>
            <div class="stat-trend good">Uso reciente</div>
        </div>
    </div>

    <!-- Table -->
    <div class="content-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Comprador / Destinatario</th>
                    <th>Valor Original</th>
                    <th>Saldo Actual</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bonos as $bono)
                <tr>
                    <td>
                        <div class="code-badge">{{ $bono->code }}</div>
                    </td>
                    <td>
                        <div style="font-weight:600; color:#1f2937;">De: {{ $bono->buyer_name ?: ($bono->customer ? $bono->customer->first_name : 'N/A') }}</div>
                        <div style="font-size:12px; color:#6b7280;">Para: {{ $bono->recipient_name }}</div>
                    </td>
                    <td>${{ number_format($bono->amount, 0) }}</td>
                    <td style="font-weight:700; color:#1f2937;">${{ number_format($bono->balance, 0) }}</td>
                    <td>
                        <div style="font-size:13px;">{{ date('d M Y', strtotime($bono->expiry_date)) }}</div>
                        @if(\Carbon\Carbon::parse($bono->expiry_date)->isPast() && $bono->status == 'active')
                            <span style="font-size:10px; color:#ef4444; font-weight:600;">VENCIDO</span>
                        @endif
                    </td>
                    <td>
                        @if($bono->status == 'active')
                            <span class="status-badge active">Activo</span>
                        @elseif($bono->status == 'redeemed')
                            <span class="status-badge inactive">Canjeado</span>
                        @elseif($bono->status == 'expired')
                             <span class="status-badge inactive">Expirado</span>
                        @else
                             <span class="status-badge">{{ $bono->status }}</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-icon" title="Ver Historial" onclick="alert('Historial en construcción')">👁️</button>
                        <button class="btn-icon" title="Reenviar Correo" onclick="alert('Reenviando...')">📧</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px; color: #9ca3af;">
                        <div style="font-size:24px; margin-bottom:10px;">🎫</div>
                        No hay bonos registrados aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:20px;">
            <!-- Pagination Placeholder -->
        </div>
    </div>
</div>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #111827; margin: 0; }
    .breadcrumb { color: #6b7280; font-size: 13px; margin-top: 5px; }
    .breadcrumb a { color: #1a73e8; text-decoration: none; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .stat-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 5px; }
    .stat-value { font-size: 28px; font-weight: 800; color: #1f2937; }
    .stat-trend { font-size: 13px; margin-top: 5px; color: #9ca3af; }
    .stat-trend.good { color: #10b981; }

    .content-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; vertical-align: middle; }
    .data-table tr:hover { background: #f9fafb; }
    
    .code-badge { background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 700; color: #374151; display: inline-block; border: 1px solid #e5e7eb; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    
    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; padding: 5px; opacity: 0.6; transition: all 0.2s; }
    .btn-icon:hover { opacity: 1; transform: scale(1.1); }
    
    .btn-primary { background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; }
    .btn-primary:hover { background: #1557b0; }
</style>
@endsection

<!-- Create Bono Modal -->
<div id="createBonoModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; width:100%; max-width:500px; border-radius:12px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="font-size:18px; font-weight:700; color:#111827; margin-bottom:16px;">Nuevo Bono de Regalo</h2>
        <form action="{{ url('admin/ventas/bonos/store') }}" method="POST">
            {{ csrf_field() }}
            
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Nombre del Comprador</label>
                <input type="text" name="buyer_name" class="form-control" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Nombre del Beneficiario</label>
                <input type="text" name="recipient_name" class="form-control" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Email del Beneficiario (Opcional)</label>
                <input type="email" name="recipient_email" class="form-control" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:16px;">
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Valor ($)</label>
                    <input type="number" name="amount" class="form-control" required min="1" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
                </div>
                <div>
                    <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Fecha de Vencimiento</label>
                    <input type="date" name="expiry_date" class="form-control" required value="{{ date('Y-m-d', strtotime('+1 year')) }}" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
                </div>
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Mensaje Personalizado</label>
                <textarea name="message" rows="2" class="form-control" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-family:inherit;"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" onclick="closeCreateBonoModal()" style="padding:8px 16px; border:1px solid #d1d5db; background:white; color:#374151; border-radius:6px; font-weight:600; cursor:pointer;">Cancelar</button>
                <button type="submit" class="btn-primary" style="padding:8px 16px; border-radius:6px;">Crear Bono</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateBonoModal() {
        document.getElementById('createBonoModal').style.display = 'flex';
    }
    
    function closeCreateBonoModal() {
        document.getElementById('createBonoModal').style.display = 'none';
    }
    
    // Close on click outside
    document.getElementById('createBonoModal').addEventListener('click', function(e) {
        if (e.target === this) closeCreateBonoModal();
    });
</script>
