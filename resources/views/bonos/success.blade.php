@extends('layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Outfit', sans-serif;
        background: #fdf2f8; 
    }

    .success-container {
        max-width: 600px;
        margin: 100px auto;
        padding: 40px;
        background: white;
        border-radius: 30px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #10b981;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        margin: 0 auto 30px;
    }

    h1 {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 10px;
    }

    p {
        color: #64748b;
        margin-bottom: 30px;
    }

    .bono-result {
        background: #f8fafc;
        border: 2px solid #f1f5f9;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
    }

    .bono-code {
        font-family: monospace;
        font-size: 24px;
        font-weight: 800;
        color: #c9a962;
        letter-spacing: 2px;
        margin: 10px 0;
        display: block;
    }

    .btn-return {
        display: inline-block;
        padding: 14px 30px;
        background: #1e293b;
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-return:hover {
        background: #0f172a;
        transform: translateY(-2px);
    }
</style>

<div class="success-container">
    <div class="success-icon">✓</div>
    <h1>¡Pago Exitoso!</h1>
    <p>El bono de regalo ha sido generado y enviado a <strong>{{ $bono->recipient_email }}</strong>.</p>

    <div class="bono-result">
        <span style="font-size:12px; text-transform:uppercase; color:#94a3b8; font-weight:600;">Código de Validación</span>
        <span class="bono-code">{{ $bono->code }}</span>
        <p style="font-size:13px; margin:10px 0 0;">Utiliza este código cuando visites el Spa para redimir tu regalo.</p>
    </div>

    <div style="margin-bottom: 30px; font-size: 14px; color: #64748b;">
        📦 <strong>Detalles:</strong><br>
        Valor: ${{ number_format($bono->amount, 0, ',', '.') }}<br>
        Válido hasta: {{ \Carbon\Carbon::parse($bono->expiry_date)->format('d/m/Y') }}
    </div>

    <a href="{{ url('/') }}" class="btn-return">Volver al Sitio Web</a>
</div>

@endsection
