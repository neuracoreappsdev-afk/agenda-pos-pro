@extends('admin/dashboard_layout')

@section('content')

<style>
    .cash-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        background: #f8fafc;
    }
    
    .cash-card {
        background: white;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
        text-align: center;
        animation: scaleIn 0.3s ease-out;
    }
    
    @keyframes scaleIn {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    .cash-icon {
        font-size: 48px;
        margin-bottom: 20px;
        display: inline-block;
        animation: floating 3s ease-in-out infinite;
    }
    
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .cash-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        font-family: 'Outfit', sans-serif;
    }
    
    .cash-subtitle {
        color: #64748b;
        font-size: 16px;
        margin-bottom: 30px;
    }
    
    .money-input-group {
        position: relative;
        margin-bottom: 30px;
    }
    
    .currency-symbol {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        color: #94a3b8;
        font-weight: 600;
    }
    
    .money-input {
        width: 100%;
        padding: 20px 20px 20px 50px;
        font-size: 32px;
        font-weight: 700;
        color: #0d9488;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        outline: none;
        transition: all 0.2s;
        text-align: center;
    }
    
    .money-input:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.1);
    }
    
    .btn-open {
        background: #0f172a;
        color: white;
        width: 100%;
        padding: 18px;
        border-radius: 16px;
        font-size: 18px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .btn-open:hover {
        transform: translateY(-2px);
        background: #1e293b;
    }

</style>

<div class="cash-wrapper">
    <div class="cash-card">
        <div class="cash-icon">🌅</div>
        <h1 class="cash-title">¡Buenos Días!</h1>
        <p class="cash-subtitle">Antes de empezar, necesitamos registrar la base de efectivo en caja.</p>
        
        @if(session('error'))
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ url('admin/caja/abrir') }}" method="POST">
            {{ csrf_field() }}
            
            <div class="money-input-group">
                <span class="currency-symbol">$</span>
                <input type="text" id="amount_mask" class="money-input" placeholder="0" required autofocus>
                <input type="hidden" name="opening_amount" id="opening_amount">
            </div>
            
            <button type="submit" class="btn-open">
                Abrir Caja y Comenzar
            </button>
        </form>
    </div>
</div>

<script>
    const mask = document.getElementById('amount_mask');
    const hidden = document.getElementById('opening_amount');

    mask.addEventListener('input', function(e) {
        let val = this.value.replace(/\D/g, '');
        hidden.value = val;
        this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    });
</script>

@endsection
