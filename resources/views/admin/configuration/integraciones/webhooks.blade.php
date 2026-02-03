@extends('admin.configuration._layout')

@section('config_title', 'Automatización con n8n')

@section('config_content')
<style>
    .webhook-url-box {
        background: #f9fafb;
        border: 1px dashed #d1d5db;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        font-family: monospace;
        color: #1a73e8;
        word-break: break-all;
    }
    .event-card {
        padding: 12px 15px;
        border-radius: 8px;
        background: white;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13px;
    }
</style>

<form action="{{ url('admin/configuration/webhooks') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin-bottom: 8px;">🔌 Conexión n8n (Webhooks)</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 25px;">Sincroniza tus eventos de agenda en tiempo real con n8n para crear flujos de trabajo personalizados.</p>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 25px; margin-bottom: 25px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-weight: 700;">URL del Webhook de Producción</label>
                    <input type="url" name="n8n_webhook_url" value="{{ $webhookUrl }}" class="form-control" placeholder="https://tu-n8n.com/webhook/..." required>
                    <p style="font-size: 12px; color: #6b7280; mt: 8px;">Pega aquí la URL generada en tu nodo Webhook de n8n.</p>
                </div>
            </div>

            <div style="background: #f0f7ff; border: 1px solid #cce3ff; border-radius: 12px; padding: 25px;">
                <h4 style="margin: 0 0 15px 0; font-size: 15px; font-weight: 700; color: #004a99;">🚀 Eventos que dispararemos</h4>
                
                <div style="display: grid; gap: 10px;">
                    <div class="event-card">
                        <span style="color: #10b981; font-weight: 700;">CREATED</span>
                        <span style="color: #6b7280;">Se envía cuando una nueva cita es agendada desde cualquier canal.</span>
                    </div>
                    <div class="event-card">
                        <span style="color: #3b82f6; font-weight: 700;">UPDATED</span>
                        <span style="color: #6b7280;">Se envía al cambiar fecha, servicio, especialista o estado de la cita.</span>
                    </div>
                    <div class="event-card">
                        <span style="color: #ef4444; font-weight: 700;">DELETED</span>
                        <span style="color: #6b7280;">Se envía cuando una reserva es eliminada permanentemente.</span>
                    </div>
                </div>

                <div style="margin-top: 20px; padding-top:15px; border-top: 1px solid #cce3ff;">
                    <p style="margin: 0; font-size: 13px; color: #004a99;">
                        <b>Payload Incluido:</b> Detalles del cliente, servicios, especialista, sede y <i>Confirmation Links</i>.
                    </p>
                </div>
            </div>
            
            <div style="margin-top: 25px;">
                <button type="button" class="btn btn-secondary" style="background: white; border: 1px solid #d1d5db; color: #374151;" onclick="alert('Enviando evento de prueba a n8n...')">
                    🧪 Enviar Evento de Prueba
                </button>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" style="padding: 12px 30px;">Guardar Webhook</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="background: #f3f4f6; color: #4b5563; border: 1px solid #d1d5db;">Volver</a>
        </div>
    </div>
</form>
@endsection
