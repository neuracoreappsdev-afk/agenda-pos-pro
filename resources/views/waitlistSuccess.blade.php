@extends('layout')

@section('content')

<style>
    .page-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 60px 20px;
        text-align: center;
    }

    .success-icon-wrapper {
        width: 80px;
        height: 80px;
        background: #FFA000;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 24px auto;
        box-shadow: 0 10px 20px rgba(255,160,0,0.3);
    }

    .success-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #000;
    }

    .success-message {
        font-size: 16px;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .info-card {
        background: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 24px;
        text-align: left;
        margin-bottom: 30px;
    }

    .btn-home {
        display: block;
        width: 100%;
        background: #000;
        color: #fff;
        padding: 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-home:hover {
        opacity: 0.8;
    }
</style>

<div class="page-container">
    <div class="success-icon-wrapper">
        <i class="fas fa-couch"></i>
    </div>

    <div class="success-title">¡Ya estás en la lista!</div>
    <div class="success-message">
        Hemos registrado tu interés exitosamente. Si se libera un espacio para el servicio seleccionado, nos pondremos en contacto contigo de inmediato.
    </div>

    <div class="info-card">
        <p style="margin:0; font-size:14px; color:#333;">
            <strong>¿Qué sigue?</strong><br>
            Nuestro equipo revisa las cancelaciones en tiempo real. Si surge una disponibilidad, te enviaremos una notificación por WhatsApp o te llamaremos al número proporcionado.
        </p>
    </div>

    <a href="{{ url('booking') }}" class="btn-home">
        Entendido, volver al inicio
    </a>
</div>

@endsection
