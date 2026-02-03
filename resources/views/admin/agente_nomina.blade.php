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

    .nom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .nom-table th {
        text-align: left;
        padding: 15px 20px;
        color: var(--ai-text-secondary);
        font-size: 11px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--ai-border);
    }
    .nom-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
        color: var(--ai-text-primary);
    }

    .badge-ready {
        background: #e6fffa;
        color: #10a37f;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        border: 1px solid #b2f5ea;
    }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #6366f1; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);">
                <i class="lucide-users" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">Núcleo de Nómina</div>
                <div style="font-size: 9px; color: #818cf8; font-weight: 800; text-transform: uppercase;">Gestor de Nómina IA Activo</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-clipboard-list"></i> Resumen de Pago</div>
        <div class="ai-nav-item"><i class="lucide-user-check"></i> Desempeño</div>
        <div class="ai-nav-item"><i class="lucide-calendar-days"></i> Novedades</div>
        <div class="ai-nav-item"><i class="lucide-coins"></i> Comisiones</div>
        <div class="ai-nav-item"><i class="lucide-settings-2"></i> Ajustes</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: #6366f1; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-credit-card"></i> EJECUTAR PAGO
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #e7f3ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #1877f2;">📝</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo funciona tu Gestor de Nómina?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Este agente calcula automáticamente cuánto debes pagar a cada colaborador basándose en sus servicios realizados. 
                    Ya no tienes que sumar comisiones a mano; la IA lo hace por ti al instante para evitar errores en los pagos.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Resumen de Pagos y Comisiones</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Control automático de salarios y desempeño del equipo.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px;">
                <span style="font-size: 11px; font-weight: 800;">CORTE: {{ date('01 M') }} - {{ date('d M') }}</span>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TOTAL A PAGAR (ESTIMADO)</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">${{ number_format($ahorro['ahorro_mensual'], 0, ',', '.') }}</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Suma de todas las comisiones</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">SERVICIOS REALIZADOS</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $metricas['nominas_procesadas'] }}</div>
                <div style="font-size: 11px; color: #4ade80;">Validados por la IA</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">TASA DE ERROR</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">0.2%</div>
                <div style="font-size: 11px; color: var(--ai-text-secondary);">Validación biométrica ok</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
            <div class="glass-panel" style="overflow: hidden; background: #fff;">
                <div style="padding: 20px; border-bottom: 1px solid var(--ai-border); background: #f9fafb;">
                    <h3 style="margin: 0; font-size: 16px;">Comisiones Detalladas</h3>
                </div>
                <table class="nom-table">
                    <thead>
                        <tr>
                            <th>Especialista</th>
                            <th>Servicios</th>
                            <th>Producción</th>
                            <th>Comisión</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="font-weight: 700;">Juan Pérez</td>
                            <td>42</td>
                            <td>$4.5M</td>
                            <td style="font-weight: 800; color: var(--ai-accent-blue);">$900.000</td>
                            <td><span class="badge-ready">LISTO</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 700;">Maria G.</td>
                            <td>38</td>
                            <td>$3.8M</td>
                            <td style="font-weight: 800; color: var(--ai-accent-blue);">$760.000</td>
                            <td><span class="badge-ready">LISTO</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 700;">Ana Martínez</td>
                            <td>15</td>
                            <td>$1.2M</td>
                            <td style="font-weight: 800; color: var(--ai-accent-blue);">$240.000</td>
                            <td><span class="badge-ready">LISTO</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="glass-panel" style="padding: 30px; background: #fff; border: 1px solid #e4e6eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <i class="lucide-sparkles" style="color: #6366f1;"></i>
                    <strong style="font-size: 16px; color: #1c1e21;">AGENTE DE TALENTO IA</strong>
                </div>
                <p style="font-size: 14px; line-height: 1.6; font-style: italic; color: #65676b;">
                    "He detectado que <strong>Maria G.</strong> ha superado su meta personal por tercer mes consecutivo. 
                    <br><br>
                    <strong>Recomendación:</strong><br>
                    Asignar bono de liderazgo de $100.000 para mantener la retención. El costo de reemplazo de este talento es de aprox. $2.5M."
                </p>
                <button style="margin-top: 25px; width: 100%; border: 1px solid #e4e6eb; background: #f0f2f5; color: #1c1e21; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer;">APROBAR BONIFICACIÓN</button>
            </div>
        </div>

    </div>
</div>

@section('scripts')
@endsection

@endsection
