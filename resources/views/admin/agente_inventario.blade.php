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

    .inv-table {
        width: 100%;
        border-collapse: collapse;
    }
    .inv-table th {
        text-align: left;
        padding: 15px 20px;
        color: var(--ai-text-secondary);
        font-size: 11px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--ai-border);
    }
    .inv-table td {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f2f5;
        font-size: 14px;
        color: var(--ai-text-primary);
    }

    .badge-alert {
        background: #fff5f5;
        color: #e53e3e;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        border: 1px solid #fed7d7;
    }
</style>

<div class="agent-container cyber-premium">
    
    <!-- Cyber Sidebar -->
    <div class="ai-sidebar">
        <div style="margin-bottom: 40px; display: flex; align-items: center; gap: 12px; padding: 0 10px;">
            <div style="width: 40px; height: 40px; background: #06b6d4; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(6, 182, 212, 0.4);">
                <i class="lucide-package" style="color: white; font-size: 20px;"></i>
            </div>
            <div>
                <div style="font-weight: 900; font-size: 18px; letter-spacing: -0.5px;">Núcleo de Almacén</div>
                <div style="font-size: 9px; color: #22d3ee; font-weight: 800; text-transform: uppercase;">Auditor de Inventario IA Activo</div>
            </div>
        </div>

        <div class="ai-nav-item active"><i class="lucide-monitor"></i> Monitoreo de Stock</div>
        <div class="ai-nav-item"><i class="lucide-shopping-cart"></i> Órdenes de Compra</div>
        <div class="ai-nav-item"><i class="lucide-archive"></i> Histórico Movimientos</div>
        <div class="ai-nav-item"><i class="lucide-tags"></i> Gestión de Precios</div>
        <div class="ai-nav-item"><i class="lucide-truck"></i> Proveedores</div>

        <div style="margin-top: auto;">
            <button style="width: 100%; background: #06b6d4; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 800; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                <i class="lucide-plus-circle"></i> NUEVO PEDIDO
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div style="flex: 1; padding: 40px; overflow-y: auto;">
        <div style="background: #ffffff; border: 1px solid #e4e6eb; border-radius: 12px; padding: 20px; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <div style="width: 40px; height: 40px; background: #e0f2f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #00897b;">📦</div>
            <div>
                <h4 style="margin: 0; color: #1c1e21; font-size: 14px;">¿Cómo cuida la IA tu inventario?</h4>
                <p style="margin: 5px 0 0; font-size: 13px; color: #65676b; line-height: 1.4;">
                    Tu Auditor de IA vigila tus productos todo el tiempo. Te avisa cuando algo está por terminarse para que nunca te quedes sin stock 
                    y también te dice exactamente cuánto dinero tienes invertido en tus estanterías.
                </p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; margin: 0;">Control de Productos</h1>
                <p style="color: var(--ai-text-secondary); margin-top: 5px;">Mantén tus estantes llenos y tu capital bajo control.</p>
            </div>
            <div class="glass-panel" style="padding: 10px 20px;">
                <span style="font-size: 11px; font-weight: 800;">ALMACÉN: {{ $lowStockProducts->count() > 0 ? 'COMPRAR PRONTO' : 'TODO EN ORDEN' }}</span>
            </div>
        </div>

        <div class="kpi-row">
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">DINERO EN PRODUCTOS</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">${{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
                <div style="font-size: 11px; color: var(--ai-accent-blue);">Valor de todo lo que tienes guardado</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">POR TERMINARSE</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0; color: #f87171;">{{ $lowStockProducts->count() }}</div>
                <div style="font-size: 11px; color: #f87171;">Productos que necesitas comprar ya</div>
            </div>
            <div class="glass-kpi">
                <div style="color: var(--ai-text-secondary); font-size: 11px; font-weight: 700;">VARIEAD DE PRODUCTOS</div>
                <div style="font-size: 28px; font-weight: 900; margin: 10px 0;">{{ $totalProducts }}</div>
                <div style="font-size: 11px; color: #4ade80;">Diferentes tipos de artículos</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
            <div class="glass-panel" style="overflow: hidden; background: #fff;">
                <div style="padding: 20px; border-bottom: 1px solid var(--ai-border); background: #f9fafb;">
                    <h3 style="margin: 0; font-size: 16px;">Auditoría de Stock Crítico</h3>
                </div>
                <table class="inv-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Existencias</th>
                            <th>Mínimo</th>
                            <th>Fecha Estimada de Quiebre</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockProducts as $product)
                        <tr>
                            <td style="font-weight: 700;">{{ $product->name }}</td>
                            <td>{{ $product->quantity }} {{ $product->unit }}</td>
                            <td>{{ $product->min_quantity }}</td>
                            <td style="color: #f87171; font-weight: 800;">48 Horas</td>
                            <td><span class="badge-alert">PEDIR YA</span></td>
                        </tr>
                        @endforeach
                        @if($lowStockProducts->count() == 0)
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 50px; opacity: 0.3;">
                                <i class="lucide-check-circle" style="font-size: 48px; margin-bottom: 15px;"></i>
                                <p>No se detectaron faltantes de stock.</p>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="glass-panel" style="padding: 30px; background: #fff; border: 1px solid #e4e6eb;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <i class="lucide-brain" style="color: #06b6d4;"></i>
                    <strong style="font-size: 16px; color: #1c1e21;">AI AUDIT INSIGHT</strong>
                </div>
                <p style="font-size: 14px; line-height: 1.6; font-style: italic; color: #65676b;">
                    "He detectado que <strong>{{ $lowStockProducts->count() }}</strong> productos están por debajo de su punto de reorden.
                    <br><br>
                    <strong>Análisis de Riesgo:</strong><br>
                    Si no realizas un pedido hoy, podrías perder aproximadamente un 12% de ventas potenciales este fin de semana por falta de insumos."
                </p>
                <button style="margin-top: 30px; width: 100%; border: 1px solid #e4e6eb; background: #f0f2f5; color: #1c1e21; padding: 12px; border-radius: 8px; font-weight: 700; cursor: pointer;">GENERAR ORDEN DE COMPRA</button>
            </div>
        </div>

    </div>
</div>

@endsection
