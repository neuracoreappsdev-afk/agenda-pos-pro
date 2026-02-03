@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Listado de Servicios</h1>
        <div class="breadcrumb">Informes / Catálogo / Servicios y Precios</div>
    </div>

    <div class="filter-bar">
        <div style="display:flex; justify-content:space-between; align-items:center; width:100%;">
            <div style="font-size:14px; color:#4b5563; font-weight:600;">Listado oficial de la empresa para el periodo actual</div>
            <button type="button" class="btn-filter" onclick="window.print()" style="background:#1f2937;">Imprimir Tarifas</button>
        </div>
    </div>

    <!-- KPIs Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Servicios</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $totalServicios }}</div>
            <div class="stat-desc">Ítems configurados en catálogo</div>
        </div>
        <div class="stat-card">
            <h3>Precio Promedio</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($precioPromedio, 0) }}</div>
            <div class="stat-desc">Valor medio por prestación</div>
        </div>
        <div class="stat-card">
            <h3>Categorías</h3>
            <div class="stat-value">{{ $servicesByCategory->count() }}</div>
            <div class="stat-desc">Segmentos de negocio</div>
        </div>
    </div>

    <!-- Data Tables by Category -->
    @foreach($servicesByCategory as $category => $categoryServices)
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h3 style="text-transform:uppercase; letter-spacing:1px; color:#1a73e8;">{{ $category ?: 'General' }}</h3>
            <span style="font-size:11px; font-weight:700; color:#9ca3af;">{{ $categoryServices->count() }} ÍTEMS</span>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:40%;">Servicio</th>
                    <th style="text-align:center">Duración</th>
                    <th style="text-align:right">Precio Base</th>
                    <th style="text-align:center">Comisión</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryServices as $service)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $service->package_name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $service->sku ?: 'No SKU' }}</div>
                    </td>
                    <td style="text-align:center;"><span class="time-badge">{{ $service->package_time }} min</span></td>
                    <td style="text-align:right; font-weight:800; color:#111827;">$ {{ number_format($service->package_price, 0) }}</td>
                    <td style="text-align:center; font-weight:600; color:#4b5563;">{{ $service->commission_percentage ?? 0 }}%</td>
                    <td style="text-align:center;">
                        @if($service->active)
                            <span class="status-badge active">Activo</span>
                        @else
                            <span class="status-badge inactive">Inactivo</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 2px solid #f3f4f6; background: #fff; }
    .card-header h3 { margin: 0; font-size: 14px; font-weight: 800; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    
    .time-badge { background: #f3f4f6; color: #4b5563; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; }
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }

    @media print {
        .filter-bar, .breadcrumb, .btn-filter { display: none !important; }
        .report-container { padding: 0 !important; }
        .card-table { break-inside: avoid; margin-top: 20px !important; }
        body { background: white !important; }
    }
</style>
@endsection
