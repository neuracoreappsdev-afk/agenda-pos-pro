@extends('admin.configuration._layout')

@section('config_title', 'Reservas en Línea')

@section('config_content')
<style>
    .booking-tab-content { display: none; }
    .booking-tab-content.active { display: block; animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .policy-editor {
        background: #fdfdfd;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .policy-label {
        font-weight: 700;
        font-size: 13px;
        color: #374151;
        margin-bottom: 10px;
        display: block;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}

    <div class="config-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
            <div>
                <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Portal de Reservas</h3>
                <p style="color: #6b7280; font-size: 14px;">Configura la experiencia de tus clientes al agendar por la web.</p>
            </div>
            <label class="switch">
                <input type="hidden" name="online_booking_enabled" value="0">
                <input type="checkbox" name="online_booking_enabled" value="1" {{ ($settings['online_booking_enabled'] ?? true) ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 1px solid #f3f4f6;">
            <button type="button" class="tab-btn active" onclick="switchBooking('general')">General</button>
            <button type="button" class="tab-btn" onclick="switchBooking('restricciones')">Reglas y Límites</button>
            <button type="button" class="tab-btn" onclick="switchBooking('politicas')">Políticas y Textos</button>
        </div>

        <!-- GENERAL -->
        <div id="booking-general" class="booking-tab-content active">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Email de Contacto / Notificaciones</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="form-control" placeholder="reservas@tunegocio.com">
                </div>
                <div class="form-group">
                    <label>Tipo de Pago requerido</label>
                    <select name="payment_type" class="form-control">
                        <option value="establishment" {{ ($settings['payment_type'] ?? '') == 'establishment' ? 'selected' : '' }}>Pagar en establecimiento</option>
                        <option value="online" {{ ($settings['payment_type'] ?? '') == 'online' ? 'selected' : '' }}>Pago total en línea</option>
                        <option value="online_deposit" {{ ($settings['payment_type'] ?? '') == 'online_deposit' ? 'selected' : '' }}>Abono parcial en línea</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-top: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f9fafb; border-radius: 10px; cursor: pointer;">
                    <input type="checkbox" name="allow_specialist_selection" value="1" {{ ($settings['allow_specialist_selection'] ?? true) ? 'checked' : '' }}>
                    <span style="font-size: 13px;">Permitir elegir Especialista</span>
                </label>
                <label style="display: flex; align-items: center; gap: 10px; padding: 12px; background: #f9fafb; border-radius: 10px; cursor: pointer;">
                    <input type="checkbox" name="allow_comments" value="1" {{ ($settings['allow_comments'] ?? true) ? 'checked' : '' }}>
                    <span style="font-size: 13px;">Permitir notas del cliente</span>
                </label>
            </div>
        </div>

        <!-- RESTRICCIONES -->
        <div id="booking-restricciones" class="booking-tab-content">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Anticipación Mínima (Horas)</label>
                    <input type="number" name="min_booking_anticipation_hours" value="{{ $settings['min_booking_anticipation_hours'] ?? 1 }}" class="form-control">
                    <small style="color: #6b7280; font-size: 11px;">Mínimo tiempo antes de la cita para reservar.</small>
                </div>
                <div class="form-group">
                    <label>Meses a Futuro permitidos</label>
                    <input type="number" name="max_booking_future_months" value="{{ $settings['max_booking_future_months'] ?? 3 }}" class="form-control">
                    <small style="color: #6b7280; font-size: 11px;">Qué tan lejos puede el cliente ver la agenda.</small>
                </div>
                <div class="form-group">
                    <label>Aviso de Cancelación (Horas)</label>
                    <input type="number" name="cancellation_notice_hours" value="{{ $settings['cancellation_notice_hours'] ?? 24 }}" class="form-control">
                    <small style="color: #6b7280; font-size: 11px;">Tiempo límite para cancelar sin penalidad.</small>
                </div>
                <div class="form-group">
                    <label>Máx. reservas por día</label>
                    <input type="number" name="max_daily_bookings" value="{{ $settings['max_daily_bookings'] ?? 100 }}" class="form-control">
                </div>
            </div>
        </div>

        <!-- POLÍTICAS -->
        <div id="booking-politicas" class="booking-tab-content">
            <div class="policy-editor">
                <span class="policy-label">📜 Términos y Condiciones</span>
                <textarea name="terms_conditions" class="form-control" style="min-height: 100px;">{{ $settings['terms_conditions'] ?? '' }}</textarea>
            </div>
            <div class="policy-editor">
                <span class="policy-label">🛡️ Política de Privacidad (GDPR/Habeas Data)</span>
                <textarea name="privacy_policy" class="form-control" style="min-height: 100px;">{{ $settings['privacy_policy'] ?? '' }}</textarea>
            </div>
        </div>

        <div class="btn-group" style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Actualizar Reservas en Línea</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: transparent; color: #6b7280;">Volver</a>
        </div>
    </div>
</form>

<script>
function switchBooking(tab) {
    document.querySelectorAll('.booking-tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('booking-' + tab).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>
@endsection