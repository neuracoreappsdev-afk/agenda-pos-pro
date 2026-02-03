@extends('admin.configuration._layout')

@section('config_title', 'Mensajes de WhatsApp')

@section('config_content')
<style>
    .msg-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    .msg-card:hover {
        border-color: #1a73e8;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .msg-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    .msg-icon {
        width: 45px;
        height: 45px;
        background: #f3f4f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .msg-title {
        font-weight: 700;
        color: #111827;
        font-size: 16px;
    }
    .variable-tag {
        display: inline-block;
        padding: 4px 10px;
        background: #eff6ff;
        color: #1a73e8;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 5px;
        margin-bottom: 5px;
        cursor: pointer;
        border: 1px solid #dbeafe;
    }
    .variable-tag:hover {
        background: #1a73e8;
        color: white;
    }
</style>

<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">Mensajes Automáticos</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Configura el contenido de los mensajes que se envían automáticamente por WhatsApp.</p>

            <!-- Bienvenida -->
            <div class="msg-card">
                <div class="msg-header">
                    <div class="msg-icon">👋</div>
                    <div class="msg-title">Mensaje de Bienvenida</div>
                </div>
                <div class="form-group">
                    <label>Contenido del Mensaje</label>
                    <textarea name="whatsapp_welcome_message" rows="4" class="form-control" placeholder="Escribe el mensaje...">{{ $settings['whatsapp_welcome_message'] ?? '' }}</textarea>
                </div>
                <div style="margin-top: 10px;">
                    <span class="variable-tag" onclick="insertVar(this, '{nombre}')">{nombre}</span>
                    <span class="variable-tag" onclick="insertVar(this, '{negocio}')">{negocio}</span>
                </div>
                <p style="font-size: 12px; color: #6b7280; margin-top: 10px;">Enviado al registrar un cliente manualmente o por reserva pública.</p>
            </div>

            <!-- Recordatorio -->
            <div class="msg-card">
                <div class="msg-header">
                    <div class="msg-icon">⏰</div>
                    <div class="msg-title">Recordatorio de Cita</div>
                </div>
                <div class="form-group">
                    <label>Contenido del Mensaje</label>
                    <textarea name="whatsapp_reminder_message" rows="4" class="form-control" placeholder="Escribe el mensaje...">{{ $settings['whatsapp_reminder_message'] ?? '' }}</textarea>
                </div>
                <div style="margin-top: 10px;">
                    <span class="variable-tag" onclick="insertVar(this, '{nombre}')">{nombre}</span>
                    <span class="variable-tag" onclick="insertVar(this, '{fecha}')">{fecha}</span>
                    <span class="variable-tag" onclick="insertVar(this, '{hora}')">{hora}</span>
                    <span class="variable-tag" onclick="insertVar(this, '{servicio}')">{servicio}</span>
                </div>
            </div>

            <!-- Cumpleaños -->
            <div class="msg-card">
                <div class="msg-header">
                    <div class="msg-icon">🎂</div>
                    <div class="msg-title">Felicitar Cumpleaños</div>
                </div>
                <div class="form-group">
                    <label>Contenido del Mensaje</label>
                    <textarea name="whatsapp_birthday_message" rows="4" class="form-control" placeholder="Escribe el mensaje...">{{ $settings['whatsapp_birthday_message'] ?? '' }}</textarea>
                </div>
                <div style="margin-top: 10px;">
                    <span class="variable-tag" onclick="insertVar(this, '{nombre}')">{nombre}</span>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Mensajes</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>

<script>
function insertVar(element, tag) {
    const textarea = element.closest('.msg-card').querySelector('textarea');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + tag + text.substring(end);
    textarea.focus();
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
}
</script>
@endsection