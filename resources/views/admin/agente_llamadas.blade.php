@extends('admin/dashboard_layout')

@section('content')

<!-- Google Fonts: Inter & Outfit -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<!-- Lucide Icons -->
<link rel="stylesheet" href="https://unpkg.com/lucide-static/font/lucide.css">
<!-- Custom AI Core Styles -->
<link rel="stylesheet" href="{{ asset('css/ai-core.css') }}">

<style>
    .agent-container {
        display: flex;
        height: calc(100vh - 60px);
        margin: -24px;
        background: var(--ai-bg);
        color: var(--ai-text-primary);
        overflow: hidden;
    }

    .kpi-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .glass-kpi {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .call-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--ai-border);
        margin-bottom: 15px;
        transition: 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .call-item:hover { background: #f9fafb; transform: translateX(5px); }

    .btn-action {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
    }
    .btn-whatsapp { background: #25D366; color: white; }
    .btn-call { background: #1877f2; color: white; }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);">
                <i class="lucide-phone-call" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">VozCore</div>
                <div style="font-size: 9px; color: #fbbf24; font-weight: 800; text-transform: uppercase;">Agente de Llamadas IA</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-calendar-check"></i> Citas de Hoy</div>
        <div class="ai-nav-item"><i class="lucide-history"></i> Seguimiento</div>
        <div class="ai-nav-item"><i class="lucide-user-minus"></i> No-Shows</div>
        <div class="ai-nav-item"><i class="lucide-bar-chart"></i> Reporte Diario</div>

        <div style="margin-top: auto;">
            <div class="glass-panel" style="padding: 15px; text-align: center;">
                <div style="font-size: 10px; color: var(--ai-text-secondary);">ESTADO DEL SERVICIO</div>
                <div style="font-size: 14px; font-weight: 800; color: #4ade80;">CONECTADO</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #fff8e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #f57f17;">📞</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo te ayuda este Asistente de Llamadas?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Tu asistente revisa tu agenda y contacta a los clientes para confirmar si vendrán. 
                    Si alguien no puede asistir, te avisa de inmediato para que puedas darle ese espacio a otra persona y no pierdas dinero.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Control de Agenda</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Tu IA confirma tus citas por ti para que tu agenda siempre esté llena.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px;">
                <span style="font-size: 11px; font-weight: 800;">📍 SEDE SELECCIONADA: {{ $locations->find(request('location_id'))->name ?? 'TODAS' }}</span>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TOTAL CITAS DE HOY</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ count($citasHoy) }}</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Personas que vienen hoy</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">CITAS POR CONFIRMAR</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ count($citasManana) }}</div>
                <div style="font-size: 11px; color: #4ade80;">Pendientes para mañana</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">PERSONAS QUE NO VINIERON</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: #f87171;">{{ count($noShows) }}</div>
                <div style="font-size: 11px; color: #f87171;">Hay que llamarlos para reagendar</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="glass-panel" style="padding: 30px;">
                <h3 style="margin-top: 0; margin-bottom: 25px;">📅 Lista de Contacto Prioritario</h3>
                
                @forelse($citasHoy as $cita)
                <div class="call-item">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="width: 45px; height: 45px; background: #e7f3ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1c1e21;">{{ substr($cita->customer_name, 0, 1) }}</div>
                        <div>
                            <div style="font-weight: 700; font-size: 16px; color: #1c1e21;">{{ $cita->customer_name }}</div>
                            <div style="font-size: 12px; color: #65676b;">⏰ {{ $cita->start_time }} | 📞 {{ $cita->phone }}</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cita->phone) }}" target="_blank" class="btn-action btn-whatsapp"><i class="lucide-message-circle"></i> WhatsApp</a>
                        <a href="tel:{{ $cita->phone }}" class="btn-action btn-call"><i class="lucide-phone"></i> Llamar</a>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 50px; opacity: 0.3;">
                    <i class="lucide-smile" style="font-size: 48px; margin-bottom: 15px;"></i>
                    <p>No hay citas pendientes de confirmación hoy.</p>
                </div>
                @endforelse
            </div>

            <div class="glass-panel" style="background: #fff; border: 1px solid #e4e6eb; padding: 30px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <i class="lucide-sparkles" style="color: #f59e0b;"></i>
                    <strong style="font-size: 16px; color: #1c1e21;">CONSEJO DE ASISTENTE IA</strong>
                </div>
                <p style="font-size: 14px; line-height: 1.6; color: #65676b; font-style: italic;">
                    "He detectado que el 40% de las citas de hoy aún no han confirmado por mensaje. 
                    <br><br>
                    <strong>Sugerencia:</strong><br>
                    Realiza las llamadas de confirmación ahora mismo. Prioriza a los clientes nuevos, ya que tienen una tasa de No-Show 3 veces mayor que los recurrentes."
                </p>
                <div style="margin-top: 30px; border-top: 1px solid #f0f2f5; padding-top: 20px;">
                    <button class="glass-panel" style="width: 100%; padding: 12px; color: #f59e0b; border: 1px solid #e4e6eb; cursor: pointer; font-weight: 800; background: #fff;">VER ESTADÍSTICAS SEMANALES</button>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
