@extends('layout')

@section('content')

<style>
    .page-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 40px 20px 100px 20px;
    }

    .header-section {
        text-align: center;
        margin-bottom: 40px;
    }

    .header-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #000;
    }

    .header-subtitle {
        font-size: 14px;
        color: #666;
    }

    .summary-card {
        background: #f9f9f9;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #eee;
    }

    .service-icon {
        width: 48px;
        height: 48px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #000;
        border: 1px solid #eee;
    }

    .summary-details h3 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .summary-details p {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
        outline: none;
    }

    .form-input:focus {
        border-color: #000;
        box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        font-size: 14px;
        min-height: 100px;
        resize: vertical;
        outline: none;
        transition: all 0.2s;
    }

    .form-textarea:focus {
        border-color: #000;
    }

    /* Footer fijo igual a la pantalla anterior */
    .fixed-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 16px 24px;
        border-top: 1px solid #eee;
        z-index: 100;
        display: flex;
        justify-content: center;
    }

    .btn-confirm {
        background: #000;
        color: #fff;
        width: 100%;
        max-width: 600px;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        border: none;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-confirm:hover {
        background: #333;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #666;
        text-decoration: none;
        font-size: 13px;
        margin-bottom: 20px;
    }
    
    .back-link:hover {
        color: #000;
    }
</style>

<div class="page-container">
    
    <a href="{{ route('booking.calendar', ['package_id' => $package->id, 'specialist_id' => isset($specialist) ? $specialist->id : null]) }}?date={{ $date }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Volver
    </a>

    <div class="header-section">
        <div class="header-title">Cuéntanos sobre ti</div>
        <div class="header-subtitle">Completa tus datos para confirmar tu reserva.</div>
    </div>

    {{-- RESUMEN DE LA CITA --}}
    <div class="summary-card">
        <div class="service-icon">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="summary-details">
            <h3>{{ $package->package_name }}</h3>
            <p>
                {{ $dateFormatted }} · {{ $time }}
                @if(isset($specialist))
                    · con {{ explode(' ', $specialist->name)[0] }}
                @endif
            </p>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <form action="{{ route('booking.confirm') }}" method="POST" id="bookingForm">
        {!! csrf_field() !!}
        
        <input type="hidden" name="package_id" value="{{ $package->id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        <input type="hidden" name="time" value="{{ $time }}">
        @if(isset($specialist))
            <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
        @endif

        <div class="form-group">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="name" class="form-input" placeholder="Ej: Ana García" required>
        </div>

        <div class="form-group">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-input" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="form-group">
            <label class="form-label">Teléfono / WhatsApp</label>
            <input type="tel" name="phone" class="form-input" placeholder="+57 300 123 4567" required>
        </div>

        <div class="form-group">
            <label class="form-label">Notas adicionales (Opcional)</label>
            <textarea name="notes" class="form-textarea" placeholder="¿Tienes alguna alergia o requerimiento especial?"></textarea>
        </div>

        <div class="fixed-footer">
            <button type="submit" class="btn-confirm">
                Confirmar Reserva
            </button>
        </div>

    </form>

</div>

@endsection
