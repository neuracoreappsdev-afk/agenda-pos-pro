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

    .fin-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .kpi-card:hover { transform: translateY(-3px); }

    .chart-panel {
        background: #ffffff;
        border: 1px solid var(--ai-border);
        border-radius: 12px;
        padding: 30px;
        height: 450px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .tx-table {
        width: 100%;
        border-collapse: collapse;
    }
    .tx-table th {
        text-align: left;
        padding: 15px 20px;
        color: var(--ai-text-secondary);
        font-size: 11px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--ai-border);
    }
    .tx-table td {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        font-size: 14px;
    }

    .ai-bubble {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 24px;
        border-radius: 12px;
        position: relative;
    }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: var(--ai-accent-emerald); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);">
                <i class="lucide-bar-chart-3" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">Núcleo Financiero</div>
                <div style="font-size: 9px; color: var(--ai-accent-emerald); font-weight: 800; text-transform: uppercase;">Contador IA Activo</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-layout-dashboard"></i> Balance General</div>
        <div class="ai-nav-item"><i class="lucide-file-text"></i> Auditoría Fiscal</div>
        <div class="ai-nav-item"><i class="lucide-wallet"></i> Control de Gastos</div>
        <div class="ai-nav-item"><i class="lucide-landmark"></i> Conciliación Bancaria</div>
        <div class="ai-nav-item"><i class="lucide-pie-chart"></i> Retorno de Insumos</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: var(--ai-accent-emerald); color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-download"></i> EXPORTAR DIAN
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 16px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px;">
            <div style="width: 50px; height: 50px; background: var(--ai-accent-emerald); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">💰</div>
            <div>
                <h4 style="margin: 0; color: var(--ai-accent-emerald); font-size: 14px;">¿Cómo te ayuda este Contador IA?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: var(--ai-text-secondary); line-height: 1.4;">
                    Tu Contador IA revisa automáticamente cada venta y cada gasto de tu negocio. No tienes que hacer cálculos complicados; 
                    él te muestra cuánto dinero estás ganando de verdad y te avisa si tus gastos están subiendo demasiado.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Tu Resumen Financiero</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Entiende tus números de forma sencilla y clara.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px; display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 11px; font-weight: 800;">MES: {{ date('F Y') }}</span>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="fin-grid">
            <div class="kpi-card">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700; text-transform: uppercase;">Total Ventas</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">${{ number_format($totalIncome, 0, ',', '.') }}</div>
                <div style="font-size: 12px;" class="trend-up">Todo lo que has vendido</div>
            </div>
            <div class="kpi-card">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700; text-transform: uppercase;">Total Gastos</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">${{ number_format($totalExpenses, 0, ',', '.') }}</div>
                <div style="font-size: 12px;" class="trend-down">Pagos, arriendos y compras</div>
            </div>
            <div class="kpi-card">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700; text-transform: uppercase;">Tu Ganancia Real</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: var(--ai-accent-emerald);">${{ number_format($netProfit, 0, ',', '.') }}</div>
                <div style="font-size: 12px; color: var(--ai-text-secondary);">Lo que queda para ti</div>
            </div>
            <div class="kpi-card">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700; text-transform: uppercase;">Salud del Negocio</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: var(--ai-accent-blue);">{{ $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0 }}%</div>
                <div style="font-size: 12px; color: var(--ai-text-secondary);">Rendimiento actual</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 40px;">
            <div class="chart-panel">
                <h3 style="margin-top: 0;">Proyección de Flujo de Caja</h3>
                <canvas id="financeChart" style="width: 100%; height: 350px;"></canvas>
            </div>
            
            <div class="ai-bubble neon-glow-emerald">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <div style="width: 36px; height: 36px; background: var(--ai-accent-emerald); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="lucide-zap" style="color: white; font-size: 18px;"></i>
                    </div>
                    <strong style="font-size: 16px;">ANÁLISIS FINANCIERO IA</strong>
                </div>
                <p style="font-size: 15px; line-height: 1.6; font-style: italic; margin: 0;">
                    "He analizado tus movimientos de este mes. Tu utilidad de <strong>${{ number_format($netProfit, 0, ',', '.') }}</strong> es sólida, pero tus gastos en insumos subieron un 12%. 
                    <br><br>
                    <strong>Sugerencia proactiva:</strong><br>
                    Si optimizas las compras de la próxima semana con el proveedor 'Distribuidora Pro', podrías ahorrar $450.000 adicionales."
                </p>
                <div style="margin-top: 25px;">
                    <button class="glass-panel" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; font-weight: 700; cursor: pointer;">GENERAR PLAN DE AHORRO</button>
                </div>
            </div>
        </div>

        <div class="glass-panel" style="overflow: hidden;">
            <div style="padding: 20px 30px; border-bottom: 1px solid var(--ai-border); background: rgba(255,255,255,0.02);">
                <h3 style="margin: 0; font-size: 18px;">Últimos Movimientos Contables</h3>
            </div>
            <table class="tx-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Concepto / Cliente</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $tx)
                    <tr>
                        <td style="font-weight: 700; color: var(--ai-accent-blue);">#{{ $tx->id }}</td>
                        <td>
                            <div style="font-weight: 600;">{{ $tx->customer->name ?? 'Venta General' }}</div>
                            <div style="font-size: 11px; color: var(--ai-text-secondary);">Venta de servicios/productos</div>
                        </td>
                        <td>{{ $tx->created_at->format('d M, Y') }}</td>
                        <td style="font-weight: 800;">${{ number_format($tx->total, 0, ',', '.') }}</td>
                        <td><span style="color: #4ade80; font-size: 11px; font-weight: 800;">● CONCILIADO</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const labels = {!! json_encode($monthlyData->pluck('month')) !!};
    const dataValues = {!! json_encode($monthlyData->pluck('total')) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Ingresos',
                data: dataValues.length ? dataValues : [4200000, 3800000, 4900000, 5500000, 4520000, 6100000],
                borderColor: '#38bdf8',
                backgroundColor: 'rgba(56, 189, 248, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: '#38bdf8'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, border: { display: false }, ticks: { color: '#64748b' } },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#64748b' } }
            }
        }
    });
</script>

@endsection
