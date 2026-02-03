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
        grid-template-columns: repeat(4, 1fr);
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

    .camp-card {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        transition: transform 0.3s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .camp-card:hover { transform: translateY(-5px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

    .client-chip {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid var(--ai-border);
    }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #ec4899; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(236, 72, 153, 0.4);">
                <i class="lucide-megaphone" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">MarkeCore</div>
                <div style="font-size: 9px; color: #f472b6; font-weight: 800; text-transform: uppercase;">Agente de Marketing IA</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-rocket"></i> Campañas</div>
        <div class="ai-nav-item"><i class="lucide-users"></i> Segmentos</div>
        <div class="ai-nav-item"><i class="lucide-cake"></i> Cumpleañeros</div>
        <div class="ai-nav-item"><i class="lucide-mail"></i> Automatización</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: #ec4899; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-plus-circle"></i> NUEVA CAMPAÑA
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #fce4ec; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #d81b60;">📣</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo te ayuda este Agente de Marketing?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Este agente se encarga de que tu negocio siempre esté en la mente de tus clientes. 
                    Envía felicitaciones de cumpleaños y mensajes de "te extrañamos" automáticamente para que más personas agenden servicios contigo.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Publicidad & Ventas</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Traer más gente a tu negocio y hacer que vuelvan pronto.</p>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TUS CLIENTES</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $totalClientes }}</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Personas registradas</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">CLIENTES NUEVOS</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $clientesNuevos }}</div>
                <div style="font-size: 11px; color: #4ade80;">Personas que vinieron este mes</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">POR RECUPERAR</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: #f87171;">{{ count($inactivos) }}</div>
                <div style="font-size: 11px; color: #f87171;">Tienen mucho tiempo sin venir</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">CUMPLEAÑOS</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: #fbbf24;">{{ count($cumpleaneros) }}</div>
                <div style="font-size: 11px; color: #fbbf24;">Eventos de este mes</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <h3 style="margin-bottom: 20px;">🚀 Campañas Sugeridas por IA</h3>
                
                <div class="camp-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0; font-size: 18px; color: #1c1e21;">Campaña de Reactivación</h4>
                            <p style="font-size: 13px; color: #65676b;">Enviado masivo a {{ count($inactivos) }} clientes inactivos.</p>
                        </div>
                        <span style="background: #e7f3ff; color: #1877f2; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700;">ROI EST. 4.2x</span>
                    </div>
                    <button class="glass-panel" style="width: 100%; padding: 12px; color: #1877f2; background: #f0f2f5; cursor: pointer; font-weight: 700; border: 1px solid #e4e6eb;">LANZAR AHORA</button>
                </div>

                <div class="camp-card">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0; font-size: 18px; color: #1c1e21;">Regalo de Cumpleaños</h4>
                            <p style="font-size: 13px; color: #65676b;">Felicita a tus {{ count($cumpleaneros) }} cumpleañeros con un beneficio.</p>
                        </div>
                        <span style="background: #e6fffa; color: #10a37f; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700;">ALTA CONVERSIÓN</span>
                    </div>
                    <button class="glass-panel" style="width: 100%; padding: 12px; color: #1877f2; background: #f0f2f5; cursor: pointer; font-weight: 700; border: 1px solid #e4e6eb;">CONFIGURAR AUTO-ENVÍO</button>
                </div>
            </div>

            <div class="glass-panel" style="padding: 30px; background: #ffffff;">
                <h3 style="margin-top: 0; margin-bottom: 25px;">😴 Clientes para Reactivar</h3>
                <div style="display: grid; grid-template-columns: 1fr; gap: 12px; max-height: 400px; overflow-y: auto; padding-right: 10px;">
                    @forelse($inactivos as $cliente)
                    <div class="client-chip">
                        <div style="width: 36px; height: 36px; background: #e4e6eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: #1c1e21;">{{ substr($cliente->name ?? 'C', 0, 1) }}</div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; font-size: 14px; color: #1c1e21;">{{ $cliente->name ?? 'Sin nombre' }}</div>
                            <div style="font-size: 11px; color: #65676b;">{{ $cliente->phone ?? 'Sin teléfono' }}</div>
                        </div>
                        <div style="display: flex; gap: 5px;">
                            <button class="glass-panel" style="padding: 6px; cursor: pointer; background: #f0f2f5; border: 1px solid #e4e6eb;"><i class="lucide-message-circle" style="font-size: 14px; color: #65676b;"></i></button>
                            <button class="glass-panel" style="padding: 6px; cursor: pointer; background: #f0f2f5; border: 1px solid #e4e6eb;"><i class="lucide-phone" style="font-size: 14px; color: #65676b;"></i></button>
                        </div>
                    </div>
                    @empty
                    <p style="text-align: center; opacity: 0.3; padding-top: 50px;">No hay clientes inactivos por reactivar.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
