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
        background: #000;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin: 0 auto 24px auto;
    }

    .success-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #000;
    }

    .success-message {
        font-size: 15px;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.5;
    }

    .receipt-card {
        background: #f9f9f9;
        border: 1px solid #eee;
        border-radius: 16px;
        padding: 24px;
        text-align: left;
        margin-bottom: 30px;
    }

    .receipt-header {
        border-bottom: 1px dashed #ddd;
        padding-bottom: 16px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .receipt-brand {
        font-weight: 700;
        font-size: 14px;
    }

    .receipt-date {
        font-size: 12px;
        color: #999;
    }

    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .receipt-label {
        color: #666;
    }

    .receipt-value {
        font-weight: 600;
        color: #000;
        text-align: right;
    }

    .receipt-total {
        border-top: 1px dashed #ddd;
        padding-top: 16px;
        margin-top: 16px;
        display: flex;
        justify-content: space-between;
        font-weight: 700;
        font-size: 16px;
    }

    .btn-home {
        display: block;
        width: 100%;
        background: #000;
        color: #fff;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .btn-home:hover {
        opacity: 0.8;
    }

    .specialist-info {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 4px;
        justify-content: flex-end;
    }

    .specialist-avatar-mini {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        object-fit: cover;
        background: #eee;
    }
</style>

<div class="page-container">

    <div class="success-icon-wrapper">
        <i class="fas fa-check"></i>
    </div>

    <div class="success-title">¡Reserva Confirmada!</div>
    <div class="success-message">
        Hemos enviado los detalles de tu cita a tu correo electrónico.<br>
        Te esperamos en Lina Lucio Spa.
    </div>

    <div class="receipt-card">
        <div class="receipt-header">
            <div class="receipt-brand">Lina Lucio</div>
            <div class="receipt-date">{{ date('d M Y, H:i') }}</div>
        </div>

        <div class="receipt-row">
            <div class="receipt-label">Servicio</div>
            <div class="receipt-value">{{ $appointment->package ? $appointment->package->package_name : 'Servicio' }}</div>
        </div>

        <div class="receipt-row">
            <div class="receipt-label">Fecha</div>
            <div class="receipt-value">{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('d M, Y') }}</div>
        </div>

        <div class="receipt-row">
            <div class="receipt-label">Hora</div>
            <div class="receipt-value">{{ \Carbon\Carbon::parse($appointment->appointment_datetime)->format('h:i A') }}</div>
        </div>

        @if($appointment->specialist)
        <div class="receipt-row">
            <div class="receipt-label">Especialista</div>
            <div class="receipt-value">
                <div class="specialist-info">
                    <span>{{ $appointment->specialist->name }}</span>
                    @if($appointment->specialist->avatar)
                        <img src="{{ $appointment->specialist->avatar }}" class="specialist-avatar-mini">
                    @endif
                </div>
            </div>
        </div>
        @endif

        <div class="receipt-row">
            <div class="receipt-label">Cliente</div>
            <div class="receipt-value">{{ $appointment->customer ? $appointment->customer->first_name : '' }}</div>
        </div>

        <div class="receipt-total">
            <div>Total estimado</div>
            <div>${{ number_format($appointment->package ? $appointment->package->package_price : 0, 0) }}</div>
        </div>
    </div>

    <a href="{{ url('booking') }}" class="btn-home">
        Volver al Inicio
    </a>

</div>

@endsection
