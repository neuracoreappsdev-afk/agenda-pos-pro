@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Listado de Bonos y Giftcards</h1>
        <div class="breadcrumb">Informes / Bonos / Control de Títulos Valor</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Bonos Emitidos</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $bonos->count() }}</div>
            <div class="stat-desc">Total de tarjetas generadas</div>
        </div>
        <div class="stat-card">
            <h3>Saldo en Circulación</h3>
            <div class="stat-value" style="color:#10b981;">$ {{ number_format($bonos->where('status', 'active')->sum('balance'), 0) }}</div>
            <div class="stat-desc">Pasivo exigible por bonos</div>
        </div>
        <div class="stat-card">
            <h3>Consumidos / Vencidos</h3>
            <div class="stat-value" style="color:#ef4444;">{{ $bonos->filter(function($b){ return in_array($b->status, ['used', 'expired']); })->count() }}</div>
            <div class="stat-desc">Ítems fuera de curso</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Auditoría de Bonos de Regalo</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Beneficiario</th>
                    <th style="text-align:right">Valor Inicial</th>
                    <th style="text-align:right">Saldo</th>
                    <th style="text-align:center">Estado</th>
                    <th style="text-align:center">Vence</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonos as $b)
                <tr>
                    <td><div style="font-family:monospace; font-weight:800; color:#1a73e8; font-size:15px; letter-spacing:1px;">{{ $b->code }}</div></td>
                    <td>
                        <div style="font-weight:700;">{{ $b->recipient_name }}</div>
                        <div style="font-size:11px; color:#6b7280;">Comp: {{ $b->buyer_name }}</div>
                    </td>
                    <td style="text-align:right; font-weight:600; color:#9ca3af;">$ {{ number_format($b->amount, 0) }}</td>
                    <td style="text-align:right; font-weight:900; color:#111827;">$ {{ number_format($b->balance, 0) }}</td>
                    <td style="text-align:center;">
                        @if($b->status == 'active')
                            <span class="status-badge active">ACTIVO</span>
                        @elseif($b->status == 'used')
                            <span class="status-badge used">CONSUMIDO</span>
                        @else
                            <span class="status-badge expired">VENCIDO</span>
                        @endif
                    </td>
                    <td style="text-align:center; font-size:12px; color:#6b7280;">{{ $b->expiry_date ? date('d/m/Y', strtotime($b->expiry_date)) : 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
    
    .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.used { background: #f3f4f6; color: #4b5563; }
    .status-badge.expired { background: #fee2e2; color: #991b1b; }
</style>
@endsection
