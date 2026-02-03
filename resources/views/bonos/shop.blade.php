@extends('layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Outfit', sans-serif;
        background: #fdf2f8; /* Soft pink background */
    }

    .bonos-page {
        max-width: 1000px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .shop-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: start;
    }

    /* Voucher Preview Card */
    .voucher-preview {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        position: sticky;
        top: 40px;
        border: 2px solid #fce7f3;
        overflow: hidden;
    }

    .voucher-preview::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 10px;
        background: linear-gradient(90deg, #c9a962, #d896ac);
    }

    .voucher-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .voucher-logo {
        font-size: 24px;
        font-weight: 800;
        color: #d896ac;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .voucher-title {
        font-size: 14px;
        color: #64748b;
        letter-spacing: 4px;
        margin-top: 5px;
    }

    .voucher-content {
        border-top: 1px dashed #e2e8f0;
        border-bottom: 1px dashed #e2e8f0;
        padding: 30px 0;
        margin: 20px 0;
    }

    .voucher-field {
        margin-bottom: 15px;
    }

    .field-label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        font-weight: 600;
    }

    .field-value {
        font-size: 18px;
        color: #1e293b;
        font-weight: 400;
        min-height: 27px;
    }

    .voucher-amount {
        text-align: center;
        font-size: 48px;
        font-weight: 800;
        color: #c9a962;
        margin: 20px 0;
    }

    /* Form Styles */
    .form-container {
        background: white;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .form-title {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
    }

    .form-subtitle {
        color: #64748b;
        margin-bottom: 30px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #475569;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        transition: all 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #d896ac;
        box-shadow: 0 0 0 4px rgba(216, 150, 172, 0.1);
    }

    .btn-buy {
        width: 100%;
        background: linear-gradient(90deg, #c9a962, #d896ac);
        color: white;
        padding: 16px;
        border: none;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s;
        margin-top: 20px;
        box-shadow: 0 10px 20px rgba(201, 169, 98, 0.2);
    }

    .btn-buy:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 850px) {
        .shop-grid { grid-template-columns: 1fr; }
        .voucher-preview { position: relative; top: 0; order: 2; }
    }
</style>

<div class="bonos-page">
    <div class="shop-grid">
        <!-- Formulario -->
        <div class="form-container">
            <h1 class="form-title">Bono de Regalo</h1>
            <p class="form-subtitle">Regala una experiencia inolvidable en Lina Lucio Spa.</p>

            <form action="{{ route('bonos.purchase') }}" method="POST">
                {{ csrf_field() }}

                <div class="input-group">
                    <label>Tu Nombre (Comprador)</label>
                    <input type="text" name="buyer_name" id="input_buyer" class="form-input" placeholder="Ej: Maria Lopez" required>
                </div>

                <div class="input-group">
                    <label>Para (Recipiente)</label>
                    <input type="text" name="recipient_name" id="input_recipient" class="form-input" placeholder="Ej: Juan Perez" required>
                </div>

                <div class="input-group">
                    <label>Email del Recipiente</label>
                    <input type="email" name="recipient_email" id="input_email" class="form-input" placeholder="donde enviaremos el bono" required>
                </div>

                <div class="input-group">
                    <label>Teléfono (Opcional)</label>
                    <input type="text" name="recipient_phone" id="input_phone" class="form-input" placeholder="Ej: 300 123 4567">
                </div>

                <div class="input-group">
                    <label>Valor del Bono ($)</label>
                    <input type="number" name="amount" id="input_amount" class="form-input" placeholder="Min. $50.000" min="10000" value="100000" required>
                </div>

                <div class="input-group">
                    <label>Mensaje Especial</label>
                    <textarea name="message" id="input_message" class="form-input" rows="3" placeholder="Feliz día, te lo mereces..."></textarea>
                </div>

                <button type="submit" class="btn-buy">Comprar Online ✨</button>
                
                <p style="text-align:center; font-size:12px; color:#94a3b8; margin-top:15px;">
                    💳 Pago seguro 100% online con cifrado SSL.
                </p>
            </form>
        </div>

        <!-- Preview -->
        <div class="voucher-preview">
            <div class="voucher-header">
                <div class="voucher-logo">Lina Lucio</div>
                <div class="voucher-title">GIFT VOUCHER</div>
            </div>

            <div class="voucher-amount" id="preview_amount">$100.000</div>

            <div class="voucher-content">
                <div class="voucher-field">
                    <div class="field-label">De</div>
                    <div class="field-value" id="preview_buyer">---</div>
                </div>
                <div class="voucher-field">
                    <div class="field-label">Para</div>
                    <div class="field-value" id="preview_recipient">---</div>
                </div>
                <div class="voucher-field">
                    <div class="field-label">Mensaje</div>
                    <div class="field-value" id="preview_message" style="font-size:14px; color:#64748b; font-style:italic;">---</div>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; font-size:10px; color:#cbd5e1;">
                <div>LL-XXXX-YYYY</div>
                <div>Válido por 6 meses</div>
            </div>
            
            <div style="margin-top:20px; text-align:center;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=PROXIMAMENTE" style="opacity:0.3; filter:grayscale(1);">
            </div>
        </div>
    </div>
</div>

<script>
    const fields = ['buyer', 'recipient', 'amount', 'message'];
    
    fields.forEach(field => {
        const input = document.getElementById('input_' + field);
        const preview = document.getElementById('preview_' + field);
        
        input.addEventListener('input', () => {
            let val = input.value || '---';
            if (field === 'amount') {
                val = '$' + Number(val).toLocaleString();
            }
            preview.innerText = val;
        });
    });
</script>

@endsection
