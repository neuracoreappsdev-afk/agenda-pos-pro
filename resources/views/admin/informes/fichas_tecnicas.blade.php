@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ trans('messages.technical_files') }}</h1>
        <div class="breadcrumb">{{ trans('messages.reports') }} / {{ trans('messages.clients') }} / {{ trans('messages.technical_files') }}</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/fichas-tecnicas') }}" style="width: 100%;">
            <div style="display:flex; gap:10px;">
                <input type="text" name="search" value="{{ $search }}" class="control" placeholder="Buscar cliente por nombre o cédula..." style="flex:1;">
                <button type="submit" class="btn-filter">Buscar</button>
            </div>
        </form>
    </div>

    <?php
        $totalCustomers = $customers->total();
    ?>

    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Clientes</h3>
            <div class="stat-value">{{ $totalCustomers }}</div>
            <div class="stat-desc">Clientes registrados en la base</div>
        </div>
        <div class="stat-card">
            <h3>Resultados Búsqueda</h3>
            <div class="stat-value" style="color:#10b981;">{{ $customers->count() }}</div>
            <div class="stat-desc">Mostrados en esta página</div>
        </div>
        <div class="stat-card">
            <h3>Fichas Activas</h3>
            <div class="stat-value" style="color:#3b82f6;">--</div>
            <div class="stat-desc">Módulo en implementación</div>
        </div>
    </div>

    <div class="card-table">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Identificación</th>
                    <th>Email / Contacto</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $c)
                <tr>
                    <td>
                        <div class="client-name">{{ $c->first_name }} {{ $c->last_name }}</div>
                        <div class="client-id">ID: {{ $c->id }}</div>
                    </td>
                    <td><span class="badge-id">{{ $c->identification ?: 'N/D' }}</span></td>
                    <td>
                        <div style="font-weight:500;">{{ $c->email }}</div>
                        <div style="font-size:12px; color:#6b7280;">{{ $c->contact_number }}</div>
                    </td>
                    <td style="text-align:right;">
                        <button class="btn-action">Ver Historial</button>
                        <button class="btn-action primary">Nueva Ficha</button> <!-- Placeholder -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:50px; color:#9fa6b2;">
                        <div style="font-size:16px; font-weight:600;">No se encontraron clientes</div>
                        <div style="font-size:14px;">Intenta con otros términos de búsqueda</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination-container">
            {!! $customers->appends(['search' => $search])->render() !!}
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .control { border: 1px solid #d1d5db; padding: 12px 15px; border-radius: 8px; font-size: 14px; width: 100%; outline:none; transition: border-color 0.2s; }
    .control:focus { border-color: #1a73e8; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: 700; white-space: nowrap; }

    /* KPI Cards - Matching Frecuencia Style */
    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 42px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 14px; }
    .stat-card h3 { color: #4b5563; font-size: 16px; font-weight: 600; margin: 0; }

    /* Table Design */
    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 15px 20px; text-align: left; font-size: 12px; text-transform:uppercase; color: #6b7280; font-weight: 700; border-bottom: 1px solid #e5e7eb; letter-spacing: 0.05em; }
    .data-table td { padding: 18px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; vertical-align: middle; }
    
    .client-name { font-weight: 700; color: #111827; font-size: 15px; }
    .client-id { font-size: 11px; color: #9ca3af; margin-top: 2px; }
    .badge-id { background: #f3f4f6; color: #4b5563; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 600; font-size: 12px; }
    
    .btn-action { background: white; border: 1px solid #d1d5db; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 700; color: #374151; margin-left: 5px; transition: all 0.2s; }
    .btn-action:hover { background: #f9fafb; border-color: #9ca3af; }
    .btn-action.primary { background: #1a73e8; color: white; border-color: #1a73e8; }
    .btn-action.primary:hover { background: #1557b0; }

    .pagination-container { padding: 20px; display: flex; justify-content: center; }
    
    /* Pagination Overrides for L5.1 */
    .pagination { display: flex; list-style: none; padding: 0; gap: 5px; }
    .pagination li { display: inline-block; }
    .pagination li span, .pagination li a { padding: 8px 14px; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #374151; font-weight: 600; }
    .pagination li.active span { background: #1a73e8; color: white; border-color: #1a73e8; }
    .pagination li.disabled span { color: #9ca3af; cursor: not-allowed; }
</style>
@endsection
