@extends('admin.configuration._layout')

@section('config_title', 'Enlaces de Reserva')

@section('config_content')
<style>
    .link-card-mini {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 12px;
        transition: all 0.2s;
    }
    .link-card-mini:hover {
        border-color: #1a73e8;
        transform: translateX(5px);
    }
    .platform-brand {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
    }
    .url-display {
        flex: 1;
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: #1a73e8;
        background: #f0f7ff;
        padding: 8px 12px;
        border-radius: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<div class="config-card">
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #111827; margin: 0;">Comparte tu Agenda</h3>
        <p style="color: #6b7280; font-size: 14px;">Usa estos enlaces para que tus clientes agenden desde cualquier red social.</p>
    </div>

    <!-- Web Link Principal -->
    <div style="background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); padding: 25px; border-radius: 15px; color: white; margin-bottom: 25px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <span style="font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Enlace Principal</span>
            <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 20px; font-size: 11px;">RECOMENDADO</span>
        </div>
        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 10px; font-family: monospace; font-size: 14px; margin-bottom: 15px; word-break: break-all;">
            {{ url('booking') }}
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="btn btn-secondary" style="background: white; color: #1a73e8; border: none; padding: 8px 15px;" onclick="copyLink('{{ url('booking') }}')">📋 Copiar Link</button>
            <a href="{{ url('booking') }}" target="_blank" class="btn btn-secondary" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid white; padding: 8px 15px;">👁️ Ver Página</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
        <!-- Instagram -->
        <div class="link-card-mini">
            <div class="platform-brand" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);">📸</div>
            <div class="payment-info">
                <span style="font-size: 12px; font-weight: 700; display: block;">Instagram Bio</span>
                <div class="url-display">{{ url('go/instagram') }}</div>
            </div>
            <button class="btn-edit" onclick="copyLink('{{ url('go/instagram') }}')">📋</button>
        </div>

        <!-- WhatsApp -->
        <div class="link-card-mini">
            <div class="platform-brand" style="background: #25d366;">💬</div>
            <div class="payment-info">
                <span style="font-size: 12px; font-weight: 700; display: block;">WhatsApp Business</span>
                <div class="url-display">{{ url('go/whatsapp') }}</div>
            </div>
            <button class="btn-edit" onclick="copyLink('{{ url('go/whatsapp') }}')">📋</button>
        </div>

        <!-- Facebook -->
        <div class="link-card-mini">
            <div class="platform-brand" style="background: #1877f2;">👥</div>
            <div class="payment-info">
                <span style="font-size: 12px; font-weight: 700; display: block;">Facebook Page</span>
                <div class="url-display">{{ url('go/facebook') }}</div>
            </div>
            <button class="btn-edit" onclick="copyLink('{{ url('go/facebook') }}')">📋</button>
        </div>

        <!-- Google -->
        <div class="link-card-mini">
            <div class="platform-brand" style="background: #ea4335;">📍</div>
            <div class="payment-info">
                <span style="font-size: 12px; font-weight: 700; display: block;">Google Maps / My Business</span>
                <div class="url-display">{{ url('go/google') }}</div>
            </div>
            <button class="btn-edit" onclick="copyLink('{{ url('go/google') }}')">📋</button>
        </div>
    </div>
</div>

<!-- QR Block -->
<div class="config-card" style="margin-top: 20px;">
    <div style="display: flex; gap: 30px; align-items: center;">
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 15px;">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url('booking') }}" alt="QR Code" style="width: 150px; height: 150px;">
        </div>
        <div style="flex: 1;">
            <h3 style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 10px 0;">Código QR para tu local</h3>
            <p style="color: #6b7280; font-size: 14px; margin-bottom: 20px;">Descarga e imprime este código para que tus clientes agenden escaneando desde la sala de espera.</p>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" onclick="window.print()">🖨️ Imprimir para local</button>
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ url('booking') }}" download="qr-reserva.png" class="btn btn-secondary">💾 Descargar HD</a>
            </div>
        </div>
    </div>
</div>

<script>
function copyLink(text) {
    const el = document.createElement('textarea');
    el.value = text;
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    
    const btn = event.currentTarget;
    const originalText = btn.innerHTML;
    btn.innerHTML = '✅ Copiado';
    setTimeout(() => btn.innerHTML = originalText, 2000);
}
</script>
@endsection