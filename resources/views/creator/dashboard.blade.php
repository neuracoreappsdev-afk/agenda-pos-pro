@extends('creator.layout')

@section('content')
<div class="animate-fade">
    <!-- Encabezado -->
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2rem;">
        <div>
            <h1 style="font-size:28px; font-weight:800; color:var(--text-primary); letter-spacing:-0.5px; display:flex; align-items:center; gap:12px;">
                <span style="color:var(--accent-blue)">⚡</span> Panel de Control SaaS
            </h1>
            <p style="color:var(--text-secondary); font-size:14px; margin-top:4px;">Gestión de infraestructura, inquilinos y suscripciones premium.</p>
        </div>
        <div style="display:flex; align-items:center; gap:12px;">
            <div class="badge-success" style="padding: 6px 14px; display:flex; align-items:center; gap:8px; font-size:10px; border-radius:8px; background: rgba(74, 222, 128, 0.1); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.2);">
                <span style="width:8px; height:8px; background:#4ade80; border-radius:50%; box-shadow: 0 0 10px #4ade80;"></span>
                NODO CENTRAL ACTIVO
            </div>
            <button class="btn-primary" onclick="openRegisterModal()" style="padding: 10px 20px;">
                <i data-lucide="plus-circle" style="width:18px;"></i> Registrar Empresa
            </button>
        </div>
    </div>

    <!-- Métricas KPI Premium -->
    <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px; margin-bottom:32px;">
        <!-- Card 1 -->
        <div class="std-card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <div style="font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px;">Inquilinos Totales</div>
                    <div style="font-size:32px; font-weight:800; color:var(--text-primary); margin-top:4px;">{{ $stats['total_businesses'] }}</div>
                </div>
                <div style="padding:10px; background:rgba(56, 189, 248, 0.1); border-radius:10px; color:var(--accent-blue);">
                    <i data-lucide="building-2"></i>
                </div>
            </div>
            <div style="margin-top:12px; font-size:12px; color:#4ade80; font-weight:600; display:flex; align-items:center; gap:6px;">
                <i data-lucide="arrow-up-right" style="width:14px;"></i> Registro estable
            </div>
        </div>

        <!-- Card 2 -->
        <div class="std-card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <div style="font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px;">Recaudación Global</div>
                    <div style="font-size:32px; font-weight:800; color:var(--text-primary); margin-top:4px;">{{ $stats['global_sales'] }}</div>
                </div>
                <div style="padding:10px; background:rgba(139, 92, 246, 0.1); border-radius:10px; color:#a78bfa;">
                    <i data-lucide="wallet"></i>
                </div>
            </div>
            <div style="margin-top:12px; font-size:12px; color:var(--text-secondary);">
                Histórico acumulado
            </div>
        </div>

        <!-- Card 3 -->
        <div class="std-card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <div style="font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px;">Facturación Mensual</div>
                    <div style="font-size:32px; font-weight:800; color:var(--accent-blue); margin-top:4px;">${{ $stats['monthly_income'] }}</div>
                </div>
                <div style="padding:10px; background:rgba(74, 222, 128, 0.1); border-radius:10px; color:#4ade80;">
                    <i data-lucide="trending-up"></i>
                </div>
            </div>
            <div style="margin-top:12px; font-size:12px; font-weight:700; display:flex; gap:4px; color:{{ $stats['balance_diff'] >= 0 ? '#4ade80' : '#f87171' }};">
                <span>{{ $stats['balance_diff'] }}%</span>
                <span style="font-weight:400; color:var(--text-secondary);">vs mes anterior</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="std-card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <div style="font-size:11px; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:1px;">Tickets Activos</div>
                    <div style="font-size:32px; font-weight:800; color:var(--text-primary); margin-top:4px;">{{ $stats['support_tickets'] }}</div>
                </div>
                <div style="padding:10px; background:rgba(248, 113, 113, 0.1); border-radius:10px; color:#f87171;">
                    <i data-lucide="alert-circle"></i>
                </div>
            </div>
            <div style="margin-top:12px; font-size:12px; color:#f87171; font-weight:600;">
                Atención requerida
            </div>
        </div>
    </div>

    <!-- Validaciones Pendientes -->
    @if($pendingAdmins->count() > 0)
    <div class="std-card" style="border: 1px solid #fbbf24; background: rgba(251, 191, 36, 0.05); margin-bottom: 32px;">
        <h2 style="font-size:16px; font-weight:700; color:#fbbf24; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="shield-alert"></i> Usuarios Pendientes de Aprobación
        </h2>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="text-align:left; font-size:11px; color:var(--text-secondary); border-bottom:1px solid var(--border-color); text-transform:uppercase; letter-spacing:1px;">
                        <th style="padding:12px;">Usuario</th>
                        <th style="padding:12px;">Email</th>
                        <th style="padding:12px;">Fecha Registro</th>
                        <th style="padding:12px; text-align:right;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingAdmins as $pAdmin)
                    <tr style="font-size:13px; border-bottom:1px solid var(--border-color); color:var(--text-primary);">
                        <td style="padding:12px; font-weight:700;">{{ $pAdmin->username }}</td>
                        <td style="padding:12px;">{{ $pAdmin->email }}</td>
                        <td style="padding:12px;">{{ $pAdmin->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding:12px; text-align:right;">
                            <form action="{{ url('creator/approve-admin/'.$pAdmin->id) }}" method="POST" style="display:inline;">
                                {!! csrf_field() !!}
                                <button type="submit" class="btn-primary" style="padding:6px 14px; font-size:11px; background:#fbbf24; color:#000;">Aprobar Acceso</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="font-size:20px; font-weight:800; color:var(--text-primary); margin:0; display:flex; align-items:center; gap:10px;">
            <i data-lucide="layout" style="color:var(--accent-blue)"></i> Empresas Gestionadas
        </h2>
        
        <!-- Botón Reset de Fábrica -->
        <form action="{{ url('creator/hard-reset') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Esta acción borrará todos los datos operativos (Citas, Clientes, Ventas, etc.) y dejará el software en CERO.')">
            {!! csrf_field() !!}
            <button type="submit" style="background:rgba(248, 113, 113, 0.1); border:1px solid rgba(248, 113, 113, 0.2); color:#f87171; padding:8px 16px; border-radius:8px; font-size:11px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:8px; transition: all 0.2s;">
                <i data-lucide="refresh-ccw" style="width:14px;"></i> RESETEAR SOFTWARE (CERO DATOS)
            </button>
        </form>
    </div>

    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px;">
        @foreach($businesses as $b)
        <div class="std-card" style="padding:0; overflow:hidden; display:flex; flex-direction:column; border-color: rgba(255,255,255,0.03);">
            <div style="padding:24px; border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:16px;">
                    <div style="background:var(--accent-blue); color:#0f172a; width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:20px; box-shadow: 0 4px 12px rgba(56, 189, 248, 0.4);">
                        {{ substr($b->name, 0, 2) }}
                    </div>
                    <span class="badge {{ $b->status == 'active' ? 'badge-success' : 'badge-danger' }}">
                        {{ $b->status }}
                    </span>
                </div>
                <h3 style="font-size:18px; font-weight:800; color:var(--text-primary); margin-bottom:4px;">{{ $b->name }}</h3>
                <div style="font-size:13px; color:var(--text-secondary); display:flex; align-items:center; gap:6px;">
                    <i data-lucide="map-pin" style="width:12px;"></i> {{ $b->location }}
                </div>
            </div>
            <div style="padding:24px; background:rgba(0,0,0,0.1); flex:1;">
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:13px;">
                    <span style="color:var(--text-secondary);">Master Admin:</span>
                    <span style="font-weight:700; color:var(--text-primary);">{{ $b->owner_name }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                    <span style="color:var(--text-secondary);">Suscripción:</span>
                    <span class="badge {{ $b->plan_type == 'enterprise' ? 'badge-info' : 'badge-success' }}" style="font-size:10px;">
                        {{ ucfirst($b->plan_type) }}
                    </span>
                </div>
            </div>
            <div style="padding:16px; border-top:1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.2); display:grid; grid-template-columns: 1fr 1fr 1fr; gap:10px;">
                <button class="btn-primary" style="justify-content:center; font-size:11px; padding:8px; background:var(--bg-main); color:var(--accent-blue); border:1px solid var(--border-color);" onclick="window.location.href='{{ url('creator/impersonate/' . $b->id) }}'" title="Suplantar Identidad">
                    <i data-lucide="shield-check" style="width:14px;"></i>
                </button>
                <button style="background:var(--bg-main); border:1px solid var(--border-color); border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--text-primary); transition: all 0.2s;" onclick="openPlanModal({{ $b->id }}, '{{ $b->name }}')" title="Gestionar Suscripción">
                    <i data-lucide="credit-card" style="width:14px;"></i>
                </button>
                <button style="background:var(--bg-main); border:1px solid var(--border-color); border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--text-primary); transition: all 0.2s;" onclick="window.location.href='{{ url('creator/business/' . $b->id) }}'" title="Configuración Nodo">
                    <i data-lucide="settings-2" style="width:14px;"></i>
                </button>
            </div>
        </div>
        @endforeach

        <!-- Add Card Dark Premium -->
        <div class="std-card" onclick="openRegisterModal()" style="border:2px dashed rgba(255,255,255,0.1); display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; min-height:280px; box-shadow:none; background:rgba(255,255,255,0.02); transition: all 0.2s;">
            <div style="width:52px; height:52px; background:rgba(56, 189, 248, 0.1); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:16px; color:var(--accent-blue); border: 1px solid rgba(56, 189, 248, 0.2); box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);">
                <i data-lucide="plus" style="width:24px; height:24px;"></i>
            </div>
            <div style="font-weight:700; color:var(--text-primary); font-size:16px;">Registrar Inquilino</div>
            <div style="font-size:12px; color:var(--text-tertiary); margin-top:6px; text-align:center; padding: 0 20px;">Crear instancia, base de datos y asignar plan maestro.</div>
        </div>
    </div>
</div>

<!-- Modal Registro (Cyber Dark) -->
<div id="registerModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px); z-index:100; align-items:center; justify-content:center;">
    <div style="background:#1e293b; width:520px; border-radius:24px; border:1px solid rgba(255,255,255,0.1); box-shadow:0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow:hidden;">
        <div style="padding:24px; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:20px; font-weight:800; color:var(--text-primary); margin:0;">Nuevo Inquilino SaaS</h3>
            <button onclick="closeRegisterModal()" style="background:rgba(255,255,255,0.05); border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; color:var(--text-secondary); display:flex; align-items:center; justify-content:center;">
                <i data-lucide="x" style="width:18px;"></i>
            </button>
        </div>
        
        <form action="{{ url('creator/business/store') }}" method="POST" style="padding:32px;">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Nombre Comercial</label>
                <input type="text" name="name" required style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none; transition: border-color 0.2s;" onfocus="this.style.borderColor='var(--accent-blue)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'" placeholder="Ej: Barber Shop Elite">
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                 <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Administrador</label>
                    <input type="text" name="owner_name" required style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none;" placeholder="Nombre maestro">
                </div>
                 <div>
                    <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Ciudad / Sede</label>
                    <input type="text" name="location" required style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none;">
                </div>
            </div>

             <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Email Corporativo</label>
                <input type="email" name="email" required style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none;">
            </div>

            <div style="margin-bottom:32px;">
                <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Plan de Suscripción Inicial</label>
                <select name="plan_type" style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none; appearance:none;">
                    <option value="freemium">AgendaPOS Freemium ($0)</option>
                    <option value="smart-financial" selected>Control Financiero ($17)</option>
                    <option value="pro-ai-team">Control Absoluto PRO IA ($30)</option>
                </select>
            </div>
            <input type="hidden" name="app_id" value="1">

            <div style="display:flex; justify-content:flex-end; gap:16px; padding-top:24px; border-top:1px solid rgba(255,255,255,0.05);">
                <button type="button" onclick="closeRegisterModal()" style="background:transparent; border:1px solid rgba(255,255,255,0.1); color:var(--text-secondary); padding:10px 24px; border-radius:10px; cursor:pointer; font-weight:600;">Cancelar</button>
                <button type="submit" class="btn-primary" style="padding:10px 32px; box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3);">Crear Instancia</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Plan (Cyber Dark) -->
<div id="planModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); backdrop-filter:blur(8px); z-index:100; align-items:center; justify-content:center;">
    <div style="background:#1e293b; width:440px; border-radius:24px; border:1px solid rgba(255,255,255,0.1); box-shadow:0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow:hidden;">
        <div style="padding:24px; border-bottom:1px solid rgba(255,255,255,0.05); display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:18px; font-weight:800; color:white; margin:0;" id="planModalTitle">Gestionar Plan</h3>
            <button onclick="closePlanModal()" style="background:rgba(255,255,255,0.05); border:none; border-radius:8px; width:32px; height:32px; cursor:pointer; color:var(--text-secondary); display:flex; align-items:center; justify-content:center;">
                <i data-lucide="x" style="width:16px;"></i>
            </button>
        </div>
        
        <form action="{{ url('creator/business/activate-plan') }}" method="POST" style="padding:32px;">
            {!! csrf_field() !!}
            <input type="hidden" name="business_id" id="plan_business_id">
            
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Seleccionar Plan</label>
                <select name="plan_id" style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none;">
                    @foreach($saasPlans as $plan)
                    <option value="{{ $plan->id }}">{{ $plan->name }} (${{ $plan->price }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom:32px;">
                <label style="display:block; font-size:11px; font-weight:700; color:var(--text-secondary); margin-bottom:8px; text-transform:uppercase;">Duración (Meses)</label>
                <select name="months" style="width:100%; background:rgba(0,0,0,0.2); border:1px solid rgba(255,255,255,0.1); border-radius:10px; padding:12px 16px; color:white; font-size:14px; outline:none;">
                    @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}">{{ $m }} {{ $m == 1 ? 'Mes' : 'Meses' }}</option>
                    @endfor
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:16px; padding-top:24px; border-top:1px solid rgba(255,255,255,0.05);">
                <button type="button" onclick="closePlanModal()" style="background:transparent; border:1px solid rgba(255,255,255,0.1); color:var(--text-secondary); padding:10px 24px; border-radius:10px; cursor:pointer;">Cancelar</button>
                <button type="submit" class="btn-primary" style="padding:10px 32px; background: #10a37f; color: white;">Activar Plan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPlanModal(id, name) {
        document.getElementById('plan_business_id').value = id;
        document.getElementById('planModalTitle').innerText = 'Plan para: ' + name;
        document.getElementById('planModal').style.display = 'flex';
    }
    function closePlanModal() {
        document.getElementById('planModal').style.display = 'none';
    }
    function openRegisterModal() {
        document.getElementById('registerModal').style.display = 'flex';
    }
    function closeRegisterModal() {
        document.getElementById('registerModal').style.display = 'none';
    }
</script>
@endsection
