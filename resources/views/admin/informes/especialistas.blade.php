@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Ventas por Especialista</h1>
        <div class="breadcrumb">Informes / Especialistas / Rendimiento de Staff</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/ventas-por-especialista') }}" class="date-form">
            <div class="group">
                <label>Especialista</label>
                <select name="specialist_id" class="control">
                    <option value="">Todo el Staff</option>
                    @foreach($allSpecialists as $sp)
                        <option value="{{ $sp->id }}" {{ $selectedSpecialistId == $sp->id ? 'selected' : '' }}>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Generar Informe</button>
        </form>
    </div>

    <!-- KPIs Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Venta Total</h3>
            <div class="stat-value" style="color:#1a73e8;">$ {{ number_format($totalVentas, 0) }}</div>
            <div class="stat-desc">Ingresos generados por el staff</div>
        </div>
        <div class="stat-card">
            <h3>Staff Activo</h3>
            <div class="stat-value">{{ $specialistsData->count() }}</div>
            <div class="stat-desc">Colaboradores analizados</div>
        </div>
        <div class="stat-card">
            <h3>Ticket Promedio / Staff</h3>
            <div class="stat-value">$ {{ number_format($specialistsData->count() > 0 ? $totalVentas / $specialistsData->count() : 0, 0) }}</div>
            <div class="stat-desc">Venta media por persona</div>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Ranking de Rendimiento y Comisiones</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Especialista / Perfil</th>
                    <th style="text-align:center">Servicios</th>
                    <th style="text-align:center">Ventas</th>
                    <th style="text-align:right">Monto Total</th>
                    <th>Participación</th>
                    <th style="text-align:right">Comisión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialistsData as $sp)
                <?php
                    $spSales = $salesBySpecialist->get($sp->id);
                    $spTotal = $spSales ? $spSales->total_monto : 0;
                    $spCount = $spSales ? $spSales->total_ventas : 0;
                    $percentage = $totalVentas > 0 ? ($spTotal / $totalVentas) * 100 : 0;
                ?>
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $sp->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $sp->category ?: 'Senior Specialist' }}</div>
                    </td>
                    <td style="text-align:center;"><div class="count-bubble">{{ $sp->packages()->count() }}</div></td>
                    <td style="text-align:center; font-weight:700;">{{ $spCount }}</td>
                    <td style="text-align:right; font-weight:700; color:#111827;">$ {{ number_format($spTotal, 0) }}</td>
                    <td>
                        <div class="progress-container">
                            <div class="progress-bar" style="width:{{ $percentage }}%"></div>
                            <span class="progress-text">{{ round($percentage) }}%</span>
                        </div>
                    </td>
                    <td style="text-align:right; font-weight:900; color:#10b981;">$ {{ number_format($spSales ? $spSales->total_comision : 0, 0) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px; color:#9ca3af;">No hay datos de rendimiento para este periodo</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .group { display: flex; flex-direction: column; gap: 5px; }
    .group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

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

    .count-bubble { background: #eff6ff; color: #1d4ed8; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 800; display: inline-block; }
    
    .progress-container { display: flex; align-items: center; gap: 10px; width: 150px; }
    .progress-bar { height: 6px; background: #1a73e8; border-radius: 10px; }
    .progress-text { font-size: 11px; font-weight: 700; color: #111827; }
</style>
@endsection
