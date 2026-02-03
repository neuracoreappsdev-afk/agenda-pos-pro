@extends('admin/dashboard_layout')

@section('content')
<div class="app-main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">Facturas de Compra</h1>
            <div class="breadcrumb">
                <a href="{{ url('admin/dashboard') }}">{{ trans('messages.home') }}</a> / 
                <a href="{{ url('admin/compras') }}">{{ trans('messages.purchases') }}</a> / 
                Facturas
            </div>
        </div>
        <div>
            <a href="{{ url('admin/compras/create') }}" class="btn btn-primary">
                <span>+</span> Nueva Compra
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Compras del Mes</div>
            <div class="stat-value">${{ number_format($stats['total_month'], 0) }}</div>
            <div class="stat-trend">{{ $stats['count_month'] }} facturas</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Por Pagar</div>
            <div class="stat-value" style="color:#ef4444;">${{ number_format($stats['pending'], 0) }}</div>
            <div class="stat-trend">Facturas pendientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Vencidas</div>
            <div class="stat-value" style="color:#b91c1c;">{{ $stats['overdue_count'] }}</div>
            <div class="stat-trend">Requieren atención</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/compras/facturas') }}" class="filter-form">
            <div class="filter-group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="filter-input">
            </div>
            <div class="filter-group">
                <label>Proveedor</label>
                <select name="provider_id" class="filter-input">
                    <option value="">Todos</option>
                    @foreach($providers as $p)
                        <option value="{{ $p->id }}" {{ request('provider_id') == $p->id ? 'selected' : '' }}>{{ $p->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Estado</label>
                <select name="status" class="filter-input">
                    <option value="">Todos</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagada</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Parcial</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    <!-- Table -->
    <div class="content-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Factura #</th>
                    <th>Proveedor</th>
                    <th style="text-align:right">Total</th>
                    <th style="text-align:right">Pagado</th>
                    <th style="text-align:right">Pendiente</th>
                    <th>Vencimiento</th>
                    <th>Estado</th>
                    <th style="text-align:right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td>{{ date('d M Y', strtotime($inv->invoice_date)) }}</td>
                    <td><span class="invoice-number">#{{ $inv->invoice_number }}</span></td>
                    <td>
                        <div class="provider-link">{{ $inv->provider ? $inv->provider->company_name : 'N/A' }}</div>
                    </td>
                    <td style="text-align:right; font-weight: 700;">${{ number_format($inv->total, 0) }}</td>
                    <td style="text-align:right; color:#10b981;">${{ number_format($inv->paid_amount, 0) }}</td>
                    <td style="text-align:right; color:#ef4444;">${{ number_format($inv->total - $inv->paid_amount, 0) }}</td>
                    <td>
                        @if($inv->due_date)
                            <span class="{{ strtotime($inv->due_date) < time() && $inv->status != 'paid' ? 'overdue-text' : '' }}">
                                {{ date('d M', strtotime($inv->due_date)) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($inv->status == 'paid')
                            <span class="status-badge paid">Pagada</span>
                        @elseif($inv->status == 'partial')
                            <span class="status-badge partial">Parcial</span>
                        @else
                            <span class="status-badge pending">Pendiente</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-icon" title="Ver Detalle" onclick="alert('Detalle en desarrollo')">👁️</button>
                        @if($inv->status != 'paid')
                        <button class="btn-icon" title="Registrar Pago" onclick="alert('Pago en desarrollo')">💳</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding: 40px; color: #9ca3af;">
                        <div style="font-size:24px; margin-bottom:10px;">📄</div>
                        No hay facturas de compra registradas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if(method_exists($invoices, 'links'))
        <div style="padding:20px;">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #111827; margin: 0; }
    .breadcrumb { color: #6b7280; font-size: 13px; margin-top: 5px; }
    .breadcrumb a { color: #1a73e8; text-decoration: none; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e5e7eb; }
    .stat-label { font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; }
    .stat-value { font-size: 28px; font-weight: 800; color: #1f2937; margin: 5px 0; }
    .stat-trend { font-size: 12px; color: #9ca3af; }

    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e5e7eb; }
    .filter-form { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 12px; font-weight: 600; color: #6b7280; }
    .filter-input { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; min-width: 150px; }

    .content-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 16px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    .data-table tr:hover { background: #f9fafb; }

    .invoice-number { background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 600; }
    .provider-link { color: #1a73e8; font-weight: 600; }
    .overdue-text { color: #b91c1c; font-weight: 700; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.paid { background: #dcfce7; color: #166534; }
    .status-badge.partial { background: #fef3c7; color: #92400e; }
    .status-badge.pending { background: #fee2e2; color: #991b1b; }

    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; padding: 5px; opacity: 0.6; transition: all 0.2s; }
    .btn-icon:hover { opacity: 1; }
    
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-primary { background: #1a73e8; color: white; }
</style>
@endsection
