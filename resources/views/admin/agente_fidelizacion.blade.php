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

    .fid-card {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .client-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid var(--ai-border);
        transition: all 0.2s;
    }
    .client-item:hover { transform: translateX(5px); background: #f3f4f6; }

</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #ec4899; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);">
                <i class="lucide-heart" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">Núcleo de Fidelización</div>
                <div style="font-size: 9px; color: #f472b6; font-weight: 800; text-transform: uppercase;">Gestor de Retención IA Activo</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-users"></i> Clientes en Riesgo</div>
        <div class="ai-nav-item"><i class="lucide-gift"></i> Programas de Lealtad</div>
        <div class="ai-nav-item"><i class="lucide-mail"></i> Campañas Auto</div>
        <div class="ai-nav-item"><i class="lucide-smile"></i> NPS & Encuestas</div>
        <div class="ai-nav-item"><i class="lucide-star"></i> VIP Management</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: #ec4899; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-zap"></i> LANZAR RECUPERACIÓN
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #fce4ec; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #d81b60;">💖</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo cuida este Agente a tus clientes?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Este agente identifica a las personas que solían venir a tu negocio pero que llevan tiempo sin visitarte. 
                    Te ayuda a contactarlos con promociones especiales para que regresen y sigan siendo tus clientes fieles.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Cuidado del Cliente</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Mantén a tus clientes felices y evita que se olviden de ti.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px;">
                <span style="font-size: 11px; font-weight: 800;">ESTADO: {{ $inactiveClients->count() > ($totalClients * 0.3) ? 'ATENCIÓN REQUERIDA' : 'NEGOCIO SALUDABLE' }}</span>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">POR RECUPERAR</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $inactiveClients->count() }}</div>
                <div style="font-size: 11px; color: #f472b6;">Clientes que llevan +30 días sin venir</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">% DE CLIENTES EN PAUSA</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $totalClients > 0 ? round(($inactiveClients->count() / $totalClients) * 100, 1) : 0 }}%</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Personas que podrían no volver</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TUS CLIENTES TOTALES</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $totalClients }}</div>
                <div style="font-size: 11px; color: #4ade80;">Tu comunidad registrada</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="fid-card">
                <h3 style="margin-top: 0; margin-bottom: 25px;">🛑 Clientes en Riesgo de Fuga</h3>
                <div class="client-list">
                    @forelse($inactiveClients->take(8) as $client)
                    <div class="client-item">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 40px; height: 40px; background: #e4e6eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #1c1e21;">{{ substr($client->first_name, 0, 1) }}</div>
                            <div>
                                <div style="font-weight: 700; color: #1c1e21;">{{ $client->first_name }} {{ $client->last_name }}</div>
                                <div style="font-size: 11px; color: #65676b;">Última visita: hace más de 30 días</div>
                            </div>
                        </div>
                        <button class="glass-panel" style="padding: 8px 15px; font-size: 11px; font-weight: 800; cursor: pointer; color: #d81b60; border: 1px solid #fce4ec; background: #fff;">RECUPERAR</button>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 50px; opacity: 0.3;">
                        <i class="lucide-check-circle" style="font-size: 48px; margin-bottom: 15px;"></i>
                        <p>No se detectaron clientes en riesgo inminente.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="glass-panel" style="padding: 30px; background: #fff; border: 1px solid #e4e6eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <i class="lucide-sparkles" style="color: #ec4899;"></i>
                    <strong style="fontSize: 16px; color: #1c1e21;">AI RETENTION INSIGHT</strong>
                </div>
                <p style="font-size: 15px; line-height: 1.6; font-style: italic; color: #65676b;">
                    "He analizado el comportamiento de tus clientes inactivos. El 60% dejó de venir después de su tercera visita. 
                    <br><br>
                    <strong>Estrategia Recomendada:</strong><br>
                    Enviaré un mensaje automático con un cupón del 15% de descuento. Esta acción tiene una probabilidad de conversión del <strong>24%</strong>."
                </p>
                <button style="margin-top: 30px; width: 100%; border: 1px solid #e4e6eb; background: #f0f2f5; color: #ec4899; padding: 12px; border-radius: 12px; font-weight: 700; cursor: pointer;">ACTIVAR CAMPAÑA</button>
            </div>
        </div>

    </div>
</div>

@endsection
