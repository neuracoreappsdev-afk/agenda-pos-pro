@extends('admin/dashboard_layout')

@section('content')
<div class="summary-wrapper">
    <!-- Header -->
    <div class="summary-header">
        <h1>📑 Resumen de Caja #{{ $session->id }}</h1>
        <div class="summary-date">
             {{ \Carbon\Carbon::parse($session->closed_at)->format('d M Y, h:i A') }}
        </div>
        <div class="summary-user">
            Cajero: <strong>{{ $session->user->username ?? ($session->user->name ?? 'Usuario') }}</strong>
        </div>
    </div>

    <!-- Main Cards -->
    <div class="summary-grid">
        <!-- Balance Card -->
        <div class="card balance-card">
            <h3>Balance Final</h3>
            <div class="balance-row">
                <span>Base Inicial:</span>
                <span>${{ number_format($session->opening_amount, 0) }}</span>
            </div>
            <div class="balance-row">
                <span>+ Ventas Totales:</span>
                <span>${{ number_format($totalSales, 0) }}</span>
            </div>
            <div class="balance-row total">
                <span>= Total Esperado:</span>
                <span>${{ number_format($session->calculated_amount, 0) }}</span>
            </div>
             <div class="balance-row real">
                <span>Dinero Reportado:</span>
                <span>${{ number_format($session->closing_amount, 0) }}</span>
            </div>
            
            @if($session->difference != 0)
            <div class="balance-row diff {{ $session->difference < 0 ? 'negative' : 'positive' }}">
                <span>Diferencia:</span>
                <span>${{ number_format($session->difference, 0) }}</span>
            </div>
            @else
            <div class="balance-row diff" style="background:#f1f5f9; color:#475569;">
                <span>Diferencia:</span>
                <span>$0 (Cuadre Perfecto)</span>
            </div>
            @endif
        </div>

        <!-- Payment Methods -->
        <div class="card">
            <h3>Ventas por Método</h3>
            <ul class="method-list">
                @foreach($salesByMethod as $method => $amount)
                <li>
                    <span>{{ ucfirst($method) }}</span>
                    <strong>${{ number_format($amount, 0) }}</strong>
                </li>
                @endforeach
                 @if($salesByMethod->isEmpty())
                    <li style="color:#aaa; text-align:center;">Sin ventas registradas</li>
                @endif
            </ul>
        </div>
    </div>

    @if($session->closing_notes)
    <div class="notes-section">
        <h4>📝 Notas del Cierre:</h4>
        <p>{{ $session->closing_notes }}</p>
    </div>
    @endif

    <div class="actions">
        <button onclick="window.print()" class="btn-print">🖨 Imprimir Resumen</button>
        <a href="{{ url('admin/dashboard') }}" class="btn-home">Volver al Inicio</a>
    </div>
</div>

<style>
    /* CSS Styles */
    .summary-wrapper { padding: 30px; max-width: 900px; margin: 0 auto; }
    .summary-header { text-align: center; margin-bottom: 30px; }
    .summary-header h1 { font-size: 28px; margin-bottom: 10px; color: #1e293b; }
    .summary-date { color: #64748b; font-size: 16px; margin-bottom: 5px; }
    .summary-user { background: #e2e8f0; display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 14px; color: #334155; }
    
    .summary-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; margin-bottom: 30px; }
    .card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .card h3 { margin-top: 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 20px; color: #0f172a; }
    
    .balance-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 15px; color: #475569; }
    .balance-row.total { border-top: 2px dashed #cbd5e1; padding-top: 12px; font-weight: 700; color: #0f172a; font-size: 16px; }
    .balance-row.real { color: #0f172a; font-weight: 600; }
    .balance-row.diff { padding: 10px; border-radius: 8px; font-weight: 700; margin-top: 10px; justify-content: space-between; display: flex; align-items: center;}
    .balance-row.diff.negative { background: #fee2e2; color: #991b1b; }
    .balance-row.diff.positive { background: #dcfce7; color: #166534; }
    
    .method-list { list-style: none; padding: 0; margin: 0; }
    .method-list li { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
    .method-list li:last-child { border-bottom: none; }
    
    .notes-section { background: #fffbeb; padding: 20px; border-radius: 12px; border: 1px solid #fcd34d; margin-bottom: 30px; color: #92400e; }
    
    .actions { text-align: center; display: flex; gap: 15px; justify-content: center; }
    .btn-print { background: #1e293b; color: white; border: none; padding: 12px 25px; border-radius: 10px; font-weight: 600; cursor: pointer; font-size: 15px; }
    .btn-home { background: #fff; border: 2px solid #e2e8f0; padding: 10px 25px; border-radius: 10px; font-weight: 600; color: #475569; text-decoration: none; display: inline-block; }
    .btn-print:hover { background: #0f172a; transform: translateY(-2px); transition:all 0.2s;}

    @media print {
        .btn-print, .btn-home, header, .sidebar, #sidebar-wrapper { display: none !important; }
        .summary-wrapper { margin: 0; width: 100%; max-width: 100%; padding:0; }
        body { background: white; }
        .card { box-shadow: none; border: 1px solid #eee; }
    }
</style>
@endsection
