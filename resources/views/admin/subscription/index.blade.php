@extends('admin.dashboard_layout')

@section('content')
<style>
    :root {
        --sub-bg: #f8fafc;
        --sub-card: #ffffff;
        --sub-accent: #4f46e5;
        --sub-text-main: #0f172a;
        --sub-text-muted: #64748b;
        --sub-border: #e2e8f0;
    }
    .sub-container { background: var(--sub-bg); min-height: 100vh; font-family: 'Outfit', sans-serif; padding: 40px 24px 80px 24px; }
    
    .plan-card {
        background: var(--sub-card);
        border: 1px solid var(--sub-border);
        border-radius: 32px;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        padding: 40px;
    }
    
    .plan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px -15px rgba(15, 23, 42, 0.08);
        border-color: var(--sub-accent);
    }
    
    .plan-card.active-plan {
        border: 2px solid var(--sub-accent);
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.1);
    }
    
    .feature-icon {
        width: 18px;
        height: 18px;
        background: #eef2ff;
        color: var(--sub-accent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .badge-premium {
        position: absolute;
        top: -14px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        padding: 6px 20px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
        z-index: 10;
        white-space: nowrap;
    }

    .price-text {
        font-size: 3.5rem;
        line-height: 1;
        font-weight: 900;
        letter-spacing: -0.04em;
        color: var(--sub-text-main);
    }
</style>

<div class="sub-container">
    <!-- Header -->
    <div class="max-w-4xl mx-auto text-center mb-20 mt-4">
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-6">
            Impulsa tu <span class="text-indigo-600">Potencial</span>
        </h1>
        <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed px-4">
            Elige la infraestructura perfecta para tu negocio. Desde operaciones básicas hasta automatización con Inteligencia Artificial.
        </p>

        <!-- Current Status -->
        <div class="mt-10 inline-flex items-center gap-3 bg-white px-5 py-2.5 rounded-full shadow-sm border border-slate-100">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Suscripción actual:</span>
            <span class="text-sm font-extrabold text-indigo-600 uppercase">
                <?php echo $subscription->plan_name ?: 'Freemium'; ?>
            </span>
            <span class="flex h-2 w-2 relative">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
            </span>
        </div>
    </div>

    <!-- Grid -->
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch px-2">
        
        <?php foreach($availablePlans as $index => $plan): ?>
        <?php 
            $isPremium = $plan->price > 18;
            $isControl = $plan->price > 10 && $plan->price < 18;
            $isCurrent = (isset($subscription->plan_id) && $subscription->plan_id == $plan->id);
            
            $features = [];
            if ($plan->price == 0) {
                $features = [
                    'Agenda Digital Ilimitada',
                    'Punto de Venta (POS) Básico',
                    'Base de Clientes (hasta 500)',
                    'Usuarios / Colaboradores 24/7',
                    'Control de Stock esencial',
                    'Soporte comunitario'
                ];
            } elseif ($index == 1 || $isControl) {
                $features = [
                    'TODO lo del Plan Free +',
                    'Reportes Financieros Reales',
                    '🤖 Agente Contador IA Básico',
                    'Control de Caja y Fugas',
                    'Gestión de Gastos y Nómina',
                    'Soporte Técnico por Email'
                ];
            } else {
                $features = [
                    'TODO el Plan Control +',
                    '📞 Call Center IA (WhatsApp)',
                    '🚀 Marketing IA Automatizado',
                    '💰 Nómina & Liquidación IA',
                    'WhatsApp Business API',
                    'Soporte VIP Prioritario 24/7'
                ];
            }
        ?>

        <div class="plan-card <?php echo $isCurrent ? 'active-plan' : ''; ?>">
            
            <?php if($isPremium): ?>
                <div class="badge-premium">Inteligencia Artificial Pro</div>
            <?php endif; ?>

            <!-- Head -->
            <div class="mb-10 min-h-[140px]">
                <h3 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-4">Plan <?php echo $plan->name; ?></h3>
                <div class="flex items-baseline gap-2 mb-4">
                    <span class="price-text">$<?php echo number_format($plan->price, 0); ?></span>
                    <span class="text-xs font-black text-slate-400 uppercase tracking-tighter">/ Mes</span>
                </div>
                <p class="text-sm text-slate-500 leading-relaxed min-h-[48px]">
                    <?php echo $plan->description ?: 'Potencia tus operaciones diarias con herramientas eficientes.'; ?>
                </p>
            </div>

            <!-- List -->
            <div class="flex-1">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-6">Especificaciones:</p>
                <ul class="space-y-4">
                    <?php foreach($features as $feature): ?>
                    <li class="flex items-start gap-4">
                        <span class="feature-icon font-black">✓</span>
                        <span class="text-[13px] font-semibold text-slate-600 leading-snug"><?php echo $feature; ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-slate-50">
                <?php if($isCurrent): ?>
                    <div class="w-full py-5 text-center rounded-2xl bg-slate-50 border border-slate-100 text-slate-400 font-extrabold text-xs uppercase tracking-widest">
                        Tu Plan Actual
                    </div>
                <?php else: ?>
                    <a href="<?php echo url('admin/subscription/pay/' . $plan->id); ?>" 
                       class="block w-full py-5 text-center rounded-2xl font-black text-xs uppercase tracking-[0.15em] transition-all duration-300
                       <?php echo $isPremium ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-200 hover:shadow-indigo-300 hover:-translate-y-1' : 'bg-slate-900 text-white hover:bg-slate-800 hover:-translate-y-1'; ?>">
                        <?php echo $plan->price > (isset($subscription->price) ? $subscription->price : 0) ? 'Mejorar Ahora ⚡' : 'Seleccionar Plan'; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Security -->
    <div class="max-w-4xl mx-auto mt-24 text-center">
        <div class="inline-flex flex-col md:flex-row items-center gap-8 px-10 py-6 bg-white rounded-[32px] border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4 text-left">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">🔒</div>
                <div>
                    <h4 class="text-sm font-black text-slate-800">Transacciones Seguras</h4>
                    <p class="text-[11px] text-slate-400 font-medium">Encriptación SSL de grado bancario activa.</p>
                </div>
            </div>
            <div class="h-px w-12 bg-slate-100 hidden md:block"></div>
            <div class="text-[11px] text-slate-500 max-w-[300px] leading-relaxed">
                Todos los planes incluyen actualizaciones gratuitas y soporte técnico especializado.
            </div>
        </div>
    </div>
</div>
@endsection
