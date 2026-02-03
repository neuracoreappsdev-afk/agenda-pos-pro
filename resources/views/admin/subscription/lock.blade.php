@extends('admin/dashboard_layout')

@section('content')
<div class="lock-container">
    <!-- Fondo borroso simulado con una tabla de ejemplo -->
    <div class="blurry-content" aria-hidden="true">
        <div class="report-header">
            <h1 style="filter: blur(4px);">{{ $title }}</h1>
            <div class="breadcrumb" style="filter: blur(3px);">Informes / Nómina / Privado</div>
        </div>
        <div class="filter-bar" style="filter: blur(5px); opacity: 0.5;">
            <div class="date-form">
                <div class="group"><label>Desde</label><div class="control">01/01/2026</div></div>
                <div class="group"><label>Hasta</label><div class="control">31/01/2026</div></div>
            </div>
        </div>
        <div class="card-table" style="filter: blur(8px); opacity: 0.3; margin-top:30px;">
            <table class="data-table">
                <thead><tr><th>Especialista</th><th>Vendido</th><th>Comisión</th><th>Neto</th></tr></thead>
                <tbody>
                    <tr><td>Demo User 1</td><td>$ 1.000.000</td><td>$ 100.000</td><td>$ 900.000</td></tr>
                    <tr><td>Demo User 2</td><td>$ 1.500.000</td><td>$ 150.000</td><td>$ 1.350.000</td></tr>
                    <tr><td>Demo User 3</td><td>$ 2.000.000</td><td>$ 200.000</td><td>$ 1.800.000</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mensaje de Bloqueo Flotante -->
    <div class="lock-overlay">
        <div class="lock-card">
            <div class="lock-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole">
                    <circle cx="12" cy="16" r="1"/><rect width="18" height="12" x="3" y="10" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/>
                </svg>
            </div>
            <h2>Funcionalidad Premium</h2>
            <p>Para acceder al panel de <strong>{{ $title }}</strong> y gestionar los pagos de tu equipo, debes actualizar tu plan.</p>
            
            <div class="benefit-tag">✓ Cálculo automático de comisiones</div>
            <div class="benefit-tag">✓ Gestión de adelantos y novedades</div>
            <div class="benefit-tag">✓ Reportes detallados por especialista</div>

            <a href="{{ url('admin/subscription') }}" class="btn-upgrade">
                Mejorar Mi Plan Ahora ⚡
            </a>
            
            <p class="lock-footer">Tu negocio merece el control total. AgendaPOS Control Financiero desde $12/mes.</p>
        </div>
    </div>
</div>

<style>
    .lock-container {
        position: relative;
        padding: 30px;
        min-height: 80vh;
        overflow: hidden;
    }

    .blurry-content {
        user-select: none;
        pointer-events: none;
    }

    .lock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(248, 250, 252, 0.4);
        backdrop-filter: blur(2px);
        z-index: 10;
    }

    .lock-card {
        background: white;
        padding: 48px;
        border-radius: 32px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.15);
        max-width: 500px;
        text-align: center;
        border: 1px solid #e2e8f0;
        animation: cardAppear 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes cardAppear {
        from { opacity: 0; transform: scale(0.9) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .lock-icon {
        background: #f1f5f9;
        color: #6366f1;
        width: 100px;
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 24px;
    }

    .lock-card h2 {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
    }

    .lock-card p {
        color: #64748b;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .benefit-tag {
        background: #f8fafc;
        padding: 8px 16px;
        border-radius: 12px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
        display: inline-block;
        border: 1px solid #f1f5f9;
    }

    .btn-upgrade {
        display: block;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        text-decoration: none;
        padding: 18px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 16px;
        margin-top: 32px;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        transition: all 0.2s;
    }

    .btn-upgrade:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);
    }

    .lock-footer {
        font-size: 11px !important;
        margin-top: 24px !important;
        color: #94a3b8 !important;
        margin-bottom: 0 !important;
    }

    /* Reuse from existing logic to keep look similar */
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; display: flex; gap: 20px;}
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
</style>
@endsection
