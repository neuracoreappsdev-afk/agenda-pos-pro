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
        background: #FFF8E1;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #FFE082;
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
        color: #FFA000;
        border: 1px solid #FFE082;
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
        border-color: #FFA000;
        box-shadow: 0 0 0 2px rgba(255,160,0,0.05);
    }

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
        background: #FFA000;
        color: #fff;
        width: 100%;
        max-width: 600px;
        padding: 16px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        border: none;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-confirm:hover {
        background: #E68A00;
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
</style>

<div class="page-container">
    
    <a href="{{ route('booking.calendar', ['package_id' => $package->id, 'specialist_id' => isset($specialist) ? $specialist->id : null]) }}?date={{ $date }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Volver a horarios
    </a>

    <div class="header-section">
        <div class="header-title">Lista de Espera 🛋️</div>
        <div class="header-subtitle">No hay citas para este día, pero podemos avisarte si alguien cancela.</div>
    </div>

    <div class="summary-card">
        <div class="service-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="summary-details">
            <h3>{{ $package->package_name }}</h3>
            <p>
                Día solicitado: {{ $date }}
                @if(isset($specialist))
                    · con {{ explode(' ', $specialist->name)[0] }}
                @endif
            </p>
        </div>
    </div>

    <form action="{{ route('booking.waitlist.store') }}" method="POST">
        {!! csrf_field() !!}
        
        <input type="hidden" name="package_id" value="{{ $package->id }}">
        <input type="hidden" name="date" value="{{ $date }}">
        @if(isset($specialist))
            <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
        @endif

        <div class="form-group">
            <label class="form-label">Tu nombre</label>
            <input type="text" name="name" class="form-input" placeholder="Ej: Diana Pérez" required>
        </div>

        <div class="form-group">
            <label class="form-label">Teléfono / WhatsApp</label>
            <input type="tel" name="phone" class="form-input" placeholder="+57 300 123 4567" required>
        </div>

        <div class="form-group">
            <label class="form-label">Correo electrónico (Opcional)</label>
            <input type="email" name="email" class="form-input" placeholder="ejemplo@correo.com">
        </div>

        <div class="fixed-footer">
            <button type="submit" class="btn-confirm">
                Unirme a la lista de espera
            </button>
        </div>
    </form>
</div>

@endsection
