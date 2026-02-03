@extends('admin/dashboard_layout')

@section('content')

<style>
    .close-wrapper {
        padding: 40px;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .close-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .close-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        font-family: 'Outfit', sans-serif;
    }
    
    .summary-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 15px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 16px;
        color: #64748b;
    }
    
    .summary-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .summary-val {
        font-weight: 700;
        color: #0f172a;
    }
    
    .total-row {
        background: #f0fdf4;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #166534;
    }
    
    .input-section {
        background: #1e293b;
        color: white;
        padding: 40px;
        border-radius: 24px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(30, 41, 59, 0.3);
    }
    
    .money-input-dark {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.2);
        color: white;
        font-size: 36px;
        padding: 20px;
        width: 100%;
        max-width: 300px;
        border-radius: 12px;
        text-align: center;
        margin: 20px 0;
        font-weight: 700;
    }
    
    .btn-close {
        background: #facc15;
        color: #0f172a;
        padding: 15px 40px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-size: 16px;
        transition: transform 0.2s;
    }
    .btn-close:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(250, 204, 21, 0.4); }

</style>

<div class="close-wrapper">
    <div class="close-header">
        <h1 class="close-title">🌙 Cierre de Turno</h1>
        <p style="color: #64748b; line-height: 1.5;">
            Turno iniciado: {{ \Carbon\Carbon::parse($session->opened_at)->format('d/m/Y h:i A') }}<br>
            <span style="display:inline-block; margin-top:5px; background:#e0f2fe; color:#0369a1; padding:2px 10px; border-radius:10px; font-size:14px; font-weight:600;">
                Abierto por: {{ $session->user ? ($session->user->username ?? $session->user->name) : 'Usuario #' . $session->user_id }}
            </span>
        </p>
    </div>
    
    <div class="summary-card">
        <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:20px;">Resumen del Sistema</h3>
        
        <div class="summary-grid" style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px; margin-bottom:30px;">
            <div class="summary-item" style="text-align:center; padding:15px; background:#f8fafc; border-radius:12px;">
                <span class="label" style="display:block; font-size:13px; color:#64748b; text-transform:uppercase; font-weight:700;">Base Inicial</span>
                <span class="value" style="display:block; font-size:24px; font-weight:700; color:#0f172a; margin-top:5px;">${{ number_format($session->opening_amount, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item" style="text-align:center; padding:15px; background:#f8fafc; border-radius:12px;">
                <span class="label" style="display:block; font-size:13px; color:#64748b; text-transform:uppercase; font-weight:700;">Ventas (Total)</span>
                <span class="value" style="display:block; font-size:24px; font-weight:700; color:#10a37f; margin-top:5px;">${{ number_format($totalSales, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item total" style="text-align:center; padding:15px; background:#1e293b; border-radius:12px; color:white;">
                <span class="label" style="display:block; font-size:13px; opacity:0.8; text-transform:uppercase; font-weight:700;">Total en Caja</span>
                <span class="value" style="display:block; font-size:24px; font-weight:700; margin-top:5px;">${{ number_format($calculatedAmount, 0, ',', '.') }}</span>
            </div>
        </div>

        <form action="{{ url('admin/caja/cerrar') }}" method="POST" class="close-form" style="border-top:1px dashed #e2e8f0; padding-top:20px;">
            {{ csrf_field() }}
            
            <div style="text-align:center;">
                <h4 style="margin:0 0 10px 0;">Conteo de Dinero Físico</h4>
                <p style="opacity: 0.8; font-size: 14px; margin-bottom:20px;">Cuenta el dinero en la caja e ingrésalo abajo.</p>
    
                @if(session('error'))
                    <div style="background: rgba(239, 68, 68, 0.2); color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.4);">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.2); color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.4);">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    
                <div style="background:#1e293b; padding:20px; border-radius:20px; display:inline-block; width:100%; max-width:400px;">
                    <input type="text" id="amount_mask" class="money-input-dark" placeholder="$0" required autofocus>
                    <input type="hidden" name="closing_amount" id="closing_amount">
                </div>
            </div>
            
            <div style="margin: 20px auto; max-width:400px;">
                <textarea name="notes" placeholder="Notas opcionales (ej: sobrante por propinas, descuadre justificado...)" style="width:100%; padding:15px; border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc; height:80px; font-family:inherit;"></textarea>
            </div>
            
            <div style="text-align:center;">
                <button type="submit" class="btn-close">Confirmar Cierre y Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const mask = document.getElementById('amount_mask');
    const hidden = document.getElementById('closing_amount');

    mask.addEventListener('input', function(e) {
        let val = this.value.replace(/\D/g, '');
        hidden.value = val;
        this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
</script>
@endsection
