@extends('admin/dashboard_layout')

@section('content')

<style>
    .account-container {
        font-family: 'Outfit', sans-serif;
    }

    .page-header {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 16px;
        padding: 40px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-subtitle {
        color: rgba(255,255,255,0.7);
        margin-top: 5px;
        font-size: 16px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        transition: transform 0.2s;
        border: 1px solid #f1f5f9;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Menu Grid */
    .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .menu-card {
        background: white;
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        text-decoration: none;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .menu-card:hover {
        border-color: #3b82f6;
        background: #f8faff;
        transform: scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }

    .menu-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 10px;
    }

    .icon-ingreso { background: #dcfce7; color: #166534; }
    .icon-egreso { background: #fee2e2; color: #991b1b; }
    .icon-informe { background: #dbeafe; color: #1e40af; }

    .menu-title {
        font-size: 20px;
        font-weight: 700;
    }

    .menu-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
    }

    .btn-action {
        margin-top: 10px;
        color: #3b82f6;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="account-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">💰 Cuenta de Empresa</h1>
        <p class="page-subtitle">Gestiona los ingresos y egresos de tu negocio de forma centralizada</p>
    </div>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: #dcfce7;">📈</div>
                <div class="stat-label">Ingresos</div>
            </div>
            <div class="stat-value">${{ number_format($totalIngresos, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: #fee2e2;">📉</div>
                <div class="stat-label">Egresos</div>
            </div>
            <div class="stat-value">${{ number_format($totalEgresos, 0, ',', '.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: #fef3c7;">📊</div>
                <div class="stat-label">Saldo Neto</div>
            </div>
            <div class="stat-value" style="color: {{ $saldoNeto >= 0 ? '#166534' : '#991b1b' }}">
                ${{ number_format($saldoNeto, 0, ',', '.') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon" style="background: #dbeafe;">💰</div>
                <div class="stat-label">Ventas Hoy</div>
            </div>
            <div class="stat-value">${{ number_format($ventasHoy, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Main Navigation Menu -->
    <div class="menu-grid">
        <a href="{{ url('admin/cuenta-empresa/ingresos') }}" class="menu-card">
            <div class="menu-icon-wrapper icon-ingreso">➡️</div>
            <div class="menu-title">Ingresos</div>
            <p class="menu-desc">Registra y visualiza todas las entradas de dinero externas a las ventas del terminal pos.</p>
            <div class="btn-action">Gestionar Ingresos ➔</div>
        </a>

        <a href="{{ url('admin/cuenta-empresa/egresos') }}" class="menu-card">
            <div class="menu-icon-wrapper icon-egreso">⬅️</div>
            <div class="menu-title">Egresos</div>
            <p class="menu-desc">Controla los gastos, pagos a proveedores, servicios públicos y otros egresos fijos.</p>
            <div class="btn-action">Gestionar Gastos ➔</div>
        </a>

        <a href="{{ url('admin/informes/caja') }}" class="menu-card">
            <div class="menu-icon-wrapper icon-informe">📄</div>
            <div class="menu-title">Informes de Caja</div>
            <p class="menu-desc">Analiza el rendimiento financiero con reportes detallados de movimientos y cierres.</p>
            <div class="btn-action">Ver Reportes ➔</div>
        </a>
    </div>
</div>

@endsection
