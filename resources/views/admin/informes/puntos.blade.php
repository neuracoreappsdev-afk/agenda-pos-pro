@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Ranking Puntos de Fidelidad</h1>
        <div class="breadcrumb">Informes / Fidelización / Puntos Acumulados</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Puntos en Circulación</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ number_format($customers->sum('current_points'), 0) }}</div>
            <div class="stat-desc">Saldo total disponible para canje</div>
        </div>
        <div class="stat-card">
            <h3>Clientes Activos</h3>
            <div class="stat-value">{{ $customers->total() }}</div>
            <div class="stat-desc">Participantes del programa</div>
        </div>
        <div class="stat-card">
            <h3>Tasa de Canje</h3>
            <?php 
                $totalEarned = $customers->sum('total_earned');
                $totalRedeemed = $customers->sum('total_redeemed');
                $rate = $totalEarned > 0 ? ($totalRedeemed / $totalEarned) * 100 : 0;
            ?>
            <div class="stat-value" style="color:#10b981;">{{ round($rate) }}%</div>
            <div class="stat-desc">Efectividad de fidelización</div>
        </div>
    </div>

    <!-- Ranking Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
             <h3>Clasificación General de Fidelidad</h3>
             <button class="btn-filter" style="font-size:12px; padding:8px 15px; background:#1f2937;">Configurar Reglas</button>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:60px; text-align:center;">#</th>
                    <th>Cliente</th>
                    <th style="text-align:right;">Ganados</th>
                    <th style="text-align:right;">Canjeados</th>
                    <th style="text-align:right;">Saldo Actual</th>
                    <th style="text-align:center;">Nivel</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $index => $c)
                <tr>
                    <td style="text-align:center; font-weight:800; color:#9ca3af;">{{ $customers->firstItem() + $index }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:35px; height:35px; border-radius:50%; background:#f3f4f6; color:#1a73e8; display:flex; align-items:center; justify-content:center; font-weight:800;">
                                {{ strtoupper(substr($c->first_name ?: 'C', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700; color:#111827;">{{ $c->first_name }} {{ $c->last_name }}</div>
                                <div style="font-size:11px; color:#6b7280;">ID #{{ $c->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#4b5563;">{{ number_format($c->total_earned ?: 0, 0) }}</td>
                    <td style="text-align:right; font-weight:600; color:#ef4444;">{{ number_format($c->total_redeemed ?: 0, 0) }}</td>
                    <td style="text-align:right; font-weight:900; color:#1a73e8; font-size:16px;">{{ number_format($c->current_points ?: 0, 0) }} pts</td>
                    <td style="text-align:center;">
                        @if($c->current_points > 5000)
                            <span class="badge-level gold">GOLD</span>
                        @elseif($c->current_points > 2000)
                            <span class="badge-level silver">SILVER</span>
                        @else
                            <span class="badge-level bronze">BRONZE</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:50px; color:#9fa6b2;">No hay actividad de puntos registrada</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="pagination-container">
            {!! $customers->render() !!}
        </div>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
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
    
    .badge-level { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; }
    .gold { background: #fef3c7; color: #92400e; border: 1px solid #f59e0b; }
    .silver { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    .bronze { background: #fff7ed; color: #9a3412; border: 1px solid #ea580c; }

    .pagination-container { padding: 20px; display: flex; justify-content: center; }
    .pagination { display: flex; list-style: none; gap: 5px; }
    .pagination li span, .pagination li a { padding: 8px 14px; border: 1px solid #e5e7eb; border-radius: 6px; text-decoration: none; color: #374151; font-weight: 600; }
    .pagination li.active span { background: #1a73e8; color: white; border-color: #1a73e8; }
</style>
@endsection
