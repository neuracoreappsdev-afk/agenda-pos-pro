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

    .strat-card {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 30px;
        height: 100%;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .action-item {
        padding: 20px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid var(--ai-border);
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 15px;
        transition: transform 0.2s;
    }
    .action-item:hover { transform: scale(1.02); background: #f3f4f6; }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4);">
                <i class="lucide-trending-up" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">Núcleo Estratégico</div>
                <div style="font-size: 9px; color: #fbbf24; font-weight: 800; text-transform: uppercase;">Growth Manager IA Activo</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-rocket"></i> Plan de Crecimiento</div>
        <div class="ai-nav-item"><i class="lucide-target"></i> Objetivos OKR</div>
        <div class="ai-nav-item"><i class="lucide-pie-chart"></i> Mix de Ventas</div>
        <div class="ai-nav-item"><i class="lucide-users"></i> Segmentación</div>
        <div class="ai-nav-item"><i class="lucide-lightbulb"></i> Ideas de Mercado</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: #f59e0b; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-refresh-cw"></i> REGENERAR PLAN
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #fff8e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #f57f17;">🚀</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo te ayuda este Estratega?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Tu Estratega de IA analiza qué servicios te dan más dinero y cuáles clientes tienen tiempo sin venir. 
                    Te da consejos prácticos para que tu negocio crezca más rápido sin que tengas que pasar horas analizando gráficas.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Plan de Crecimiento</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Tu IA te ayuda a tomar mejores decisiones para ganar más.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px;">
                <span style="font-size: 11px; font-weight: 800;">ESTADO: ANALIZANDO OPORTUNIDADES</span>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">VENTA POR CLIENTE</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">${{ number_format($avgTicket, 0, ',', '.') }}</div>
                <div style="font-size: 11px; color: #4ade80;">Lo que gasta cada persona</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TOTAL DE CLIENTES</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $totalClients }}</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Personas en tu base de datos</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TU SERVICIO #1</div>
                <div style="font-size: 18px; font-weight: 900; margin: 10px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $topServices->first()->package_name ?? 'N/A' }}</div>
                <div style="font-size: 11px; color: #fbbf24;">Lo que más te piden</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="strat-card">
                <h3 style="margin-top: 0; margin-bottom: 25px;">🎯 Plan de Acción Sugerido</h3>
                
                <div class="action-item">
                    <div style="width: 50px; height: 50px; background: #e7f3ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">📧</div>
                    <div style="flex: 1;">
                        <strong style="display: block; font-size: 16px; color: #1c1e21;">Email Marketing Segmentado</strong>
                        <span style="color: #65676b; font-size: 13px;">Enviar promoción del servicio estrella a los 50 clientes más rentables.</span>
                    </div>
                    <button class="glass-panel" style="padding: 8px 15px; font-size: 11px; font-weight: 800; cursor: pointer; background: #f0f2f5; border: 1px solid #e4e6eb;">EJECUTAR</button>
                </div>

                <div class="action-item">
                    <div style="width: 50px; height: 50px; background: #e6fffa; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">💰</div>
                    <div style="flex: 1;">
                        <strong style="display: block; font-size: 16px; color: #1c1e21;">Upselling en Recepción</strong>
                        <span style="color: #65676b; font-size: 13px;">Ofrecer aditivos a los servicios que tienen un ticket por debajo de ${{ number_format($avgTicket, 0) }}.</span>
                    </div>
                    <button class="glass-panel" style="padding: 8px 15px; font-size: 11px; font-weight: 800; cursor: pointer; background: #f0f2f5; border: 1px solid #e4e6eb;">EJECUTAR</button>
                </div>

                <div class="action-item">
                    <div style="width: 50px; height: 50px; background: #fff5f5; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">🔥</div>
                    <div style="flex: 1;">
                        <strong style="display: block; font-size: 16px; color: #1c1e21;">Campaña Reactivación</strong>
                        <span style="color: #65676b; font-size: 13px;">Cupón del 15% para clientes que no han vuelto en los últimos 45 días.</span>
                    </div>
                    <button class="glass-panel" style="padding: 8px 15px; font-size: 11px; font-weight: 800; cursor: pointer; background: #f0f2f5; border: 1px solid #e4e6eb;">EJECUTAR</button>
                </div>
            </div>

            <div class="glass-panel" style="padding: 30px; background: #fff; border: 1px solid #e4e6eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <i class="lucide-brain" style="color: #f59e0b;"></i>
                    <strong style="font-size: 16px; color: #1c1e21;">INSIGHT ESTRATÉGICO IA</strong>
                </div>
                <p style="font-size: 15px; line-height: 1.6; font-style: italic; color: #65676b;">
                    "Analizando tus datos de los últimos 30 días, he notado que el <strong>{{ $topServices->first()->package_name ?? 'servicio principal' }}</strong> está generando el 40% de tu margen neto.
                    <br><br>
                    <strong>Sugerencia operativa:</strong><br>
                    Aumenta ligeramente el precio de este servicio en un 5% y observa la elasticidad de la demanda. Tus números indican que los clientes valoran la calidad actual y no se irán por ese ajuste."
                </p>
                <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f0f2f5; text-align: center;">
                    <span style="font-size: 10px; font-weight: 900; color: #f59e0b; text-transform: uppercase; letter-spacing: 1px;">— Tu Growth Manager IA —</span>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
