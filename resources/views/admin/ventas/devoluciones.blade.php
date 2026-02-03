@extends('admin/dashboard_layout')

@section('content')
<div class="app-main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ trans('messages.refunds_returns') }}</h1>
            <div class="breadcrumb">
                <a href="{{ url('admin/dashboard') }}">{{ trans('messages.home') }}</a> / 
                <a href="{{ url('admin/ventas') }}">{{ trans('messages.sales') }}</a> / 
                {{ trans('messages.refunds_returns') }}
            </div>
        </div>
        <div>
             <button class="btn btn-primary" onclick="openCreateRefundModal()">
                <span>+</span> Nueva Devolución
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Devoluciones Mes</div>
            <div class="stat-value">{{ $stats['count_month'] }}</div>
            <div class="stat-trend">Este periodo</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Monto Devuelto</div>
            <div class="stat-value" style="color:#ef4444;">${{ number_format($stats['amount_month'], 0) }}</div>
            <div class="stat-trend">Mes actual</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Tasa Devolución</div>
            <div class="stat-value">{{ $stats['rate'] }}%</div>
            <div class="stat-trend {{ $stats['rate'] < 3 ? 'good' : '' }}">{{ $stats['rate'] < 3 ? 'Dentro de lo normal' : 'Revisar' }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="content-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Factura Original</th>
                    <th>Cliente</th>
                    <th>Motivo</th>
                    <th style="text-align:right">Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($refunds as $r)
                <tr>
                    <td>{{ date('d M Y', strtotime($r->created_at)) }}</td>
                    <td><a href="#" style="color:#1a73e8;">#{{ $r->sale_id }}</a></td>
                    <td>{{ $r->customer_name ?? 'N/A' }}</td>
                    <td>{{ $r->reason ?? 'Sin especificar' }}</td>
                    <td style="text-align:right; font-weight:700; color:#ef4444;">${{ number_format($r->amount, 0) }}</td>
                    <td>
                        <span class="status-badge {{ $r->status == 'approved' ? 'active' : ($r->status == 'pending' ? 'pending' : 'inactive') }}">
                            {{ ucfirst($r->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color: #9ca3af;">
                        <div style="font-size:24px; margin-bottom:10px;">🔄</div>
                        No hay devoluciones registradas.
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
    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; }
    .stat-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 5px; }
    .stat-value { font-size: 28px; font-weight: 800; color: #1f2937; }
    .stat-trend { font-size: 13px; margin-top: 5px; color: #9ca3af; }
    .stat-trend.good { color: #10b981; }

    .content-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.pending { background: #fef3c7; color: #92400e; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    
    .btn-primary { background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
</style>
@endsection

<!-- Create Devolucion Modal -->
<div id="createRefundModal" class="modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; width:100%; max-width:500px; border-radius:12px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
        <h2 style="font-size:18px; font-weight:700; color:#111827; margin-bottom:16px;">Registrar Devolución</h2>
        <form action="{{ url('admin/ventas/devoluciones/store') }}" method="POST">
            {{ csrf_field() }}
            
            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Número de Factura / Venta (Opcional)</label>
                <input type="number" name="sale_id" class="form-control" placeholder="Ej: 1045" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            </div>

            <div style="margin-bottom:16px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Monto a Devolver ($)</label>
                <input type="number" name="amount" class="form-control" required min="1" style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px;">
            </div>
            
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:4px;">Motivo de la Devolución</label>
                <textarea name="reason" rows="3" class="form-control" required style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-family:inherit;"></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" onclick="closeCreateRefundModal()" style="padding:8px 16px; border:1px solid #d1d5db; background:white; color:#374151; border-radius:6px; font-weight:600; cursor:pointer;">Cancelar</button>
                <button type="submit" class="btn-primary" style="padding:8px 16px; border-radius:6px;">Registrar Devolución</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateRefundModal() {
        document.getElementById('createRefundModal').style.display = 'flex';
    }
    
    function closeCreateRefundModal() {
        document.getElementById('createRefundModal').style.display = 'none';
    }
    
    // Close on click outside
    document.getElementById('createRefundModal').addEventListener('click', function(e) {
        if (e.target === this) closeCreateRefundModal();
    });
</script>
