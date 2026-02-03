@extends('admin.configuration._layout')

@section('config_title', 'Horarios y Festivos')

@section('config_content')

<style>
    /* Premium UI Enhancements */
    .config-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        border: 1px solid #f3f4f6;
        padding: 32px;
    }

    .tabs-container {
        border-bottom: 2px solid #f3f4f6;
        margin-bottom: 30px;
    }
    
    .tab-buttons {
        display: flex;
        gap: 8px;
    }
    
    .tab-btn {
        padding: 12px 20px;
        border: none;
        background: none;
        color: #6b7280;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px 8px 0 0;
    }
    
    .tab-btn:hover {
        color: #111827;
        background: #f9fafb;
    }
    
    .tab-btn.active {
        color: #6366f1;
        border-bottom-color: #6366f1;
        background: rgba(99, 102, 241, 0.05);
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .day-row {
        transition: all 0.3s ease;
    }

    .day-row:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #6366f1 !important;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        padding: 10px 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .btn-save-main {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        padding: 14px 32px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        transition: all 0.3s;
    }

    .btn-save-main:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(4px);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 0;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .modal-header {
        padding: 24px 32px;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-body {
        padding: 32px;
    }

    .btn-create-new {
        background: #111827;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
        cursor: pointer;
    }
    
    /* Mobile-First Grid System for Day Rows */
    .day-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        background: #ffffff;
        border: 1px solid #f3f4f6;
        border-radius: 16px;
        margin-bottom: 12px;
        transition: transform 0.2s;
    }
    
    .day-info {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }
    
    .day-label {
        font-weight: 700;
        font-size: 16px;
        color: #1f2937;
    }

    .time-columns-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    /* Custom Time Input Button (Looks like iOS input) */
    .time-trigger {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 700;
        font-size: 15px;
        color: #000000;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        min-width: 100px;
        justify-content: center;
        transition: all 0.2s;
    }
    
    .time-trigger:active {
        background: #f3f4f6;
        transform: scale(0.98);
    }
    
    .time-label-small {
        font-size: 11px;
        text-transform: uppercase;
        color: #9ca3af;
        margin-right: 4px;
        font-weight: 700;
    }

    /* Mobile Responsive Logic */
    @media (max-width: 768px) {
        .config-card {
            padding: 16px;
        }
        
        .day-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .time-columns-wrapper {
            width: 100%;
            justify-content: space-between;
        }
        
        .time-trigger {
            flex: 1;
            padding: 12px;
            font-size: 16px;
        }
        
        .status-badge {
            display: none; /* Hide badge on mobile, toggle is enough */
        }
    }

    /* WHEEL PICKER STYLES (Ported) */
    .wheel-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        z-index: 2000;
        align-items: flex-end; /* Sheet from bottom on mobile */
        justify-content: center;
    }
    
    .wheel-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 24px 24px 0 0;
        padding: 24px;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        padding-bottom: 40px;
    }
    
    @media (min-width: 769px) {
        .wheel-modal {
            align-items: center;
        }
        .wheel-content {
            border-radius: 24px;
        }
    }
    
    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
    
    .wheel-interface {
        position: relative;
        height: 200px;
        overflow: hidden;
        mask-image: linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 20%, black 80%, transparent 100%);
        margin: 20px 0;
    }
    
    .wheel-scroller {
        height: 100%;
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        -ms-overflow-style: none; /* Hide scrollbar */
        scrollbar-width: none;
    }
    .wheel-scroller::-webkit-scrollbar { display: none; }
    
    .wheel-item {
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #64748b; /* Gris más oscuro para legibilidad */
        scroll-snap-align: center;
        transition: all 0.2s;
    }
    
    .wheel-item.active {
        color: #000000; /* Negro puro */
        font-weight: 900;
        transform: scale(1.2);
    }
    
    .highlight-bar {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 50px;
        transform: translateY(-50%);
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        pointer-events: none;
    }
    
    .btn-confirm-time {
        width: 100%;
        background: #111827;
        color: white;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        border: none;
        margin-top: 20px;
    }
    /* Toggles / Switches */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #e5e7eb;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    input:checked + .slider { background-color: #6366f1; }
    input:checked + .slider:before { transform: translateX(20px); }

    /* Premium Alert/Confirm Modals */
    .premium-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.2s ease-out;
    }
    
    .premium-modal-content {
        background: white;
        width: 100%;
        max-width: 400px;
        border-radius: 24px;
        padding: 32px;
        text-align: center;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: scale(0.9);
        animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    @keyframes modalPop {
        to { transform: scale(1); opacity: 1; }
    }

    .premium-modal-icon {
        width: 64px; height: 64px;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        border-radius: 20px;
        display: flex;
        align-items: center; justify-content: center;
        font-size: 32px;
        margin: 0 auto 20px;
    }
    
    .premium-modal-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 12px;
    }
    
    .premium-modal-text {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    
    .premium-modal-actions {
        display: flex;
        gap: 12px;
    }
    
    .premium-modal-btn {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }
    
    .premium-modal-btn-confirm {
        background: #111827;
        color: white;
    }
    
    .premium-modal-btn-cancel {
        background: #f3f4f6;
        color: #6b7280;
    }
    
    .premium-modal-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="config-card">
    <div class="tabs-container" style="display:flex; justify-content:space-between; align-items:center;">
        <div class="tab-buttons">
            <button class="tab-btn active" onclick="switchTab('semana', event)">Horarios Semanales</button>
            <button class="tab-btn" onclick="switchTab('vacaciones', event)">Festivos</button>
            <button class="tab-btn" onclick="switchTab('extendido', event)">Extendido</button>
        </div>
        
        <!-- Desktop Header Extra actions -->
    </div>

    <!-- TAB: SEMANA -->
    <div id="tab-semana" class="tab-content active">
        <form action="{{ url('admin/configuration/business-schedule') }}" method="POST">
            {{ csrf_field() }}
            
            <div style="margin-bottom: 30px;">
                <?php 
                    $selected_days = isset($selected_days) && is_array($selected_days) ? $selected_days : [];
                    $daily_hours = isset($daily_hours) && is_array($daily_hours) ? $daily_hours : [];
                ?>
                @foreach($days as $value => $label)
                    <?php 
                        $isOpen = in_array($value, $selected_days);
                        $start = isset($daily_hours[$value]['start']) ? $daily_hours[$value]['start'] : '09:00';
                        $end = isset($daily_hours[$value]['end']) ? $daily_hours[$value]['end'] : '18:00';
                        $displayStart = date("h:i A", strtotime($start));
                        $displayEnd = date("h:i A", strtotime($end));
                    ?>
                    <div class="day-row">
                        <div class="day-info">
                            <label class="switch">
                                <input type="checkbox" name="work_days[]" value="{{ $value }}" {{ $isOpen ? 'checked' : '' }} onchange="toggleDayRow(this)">
                                <span class="slider"></span>
                            </label>
                            <span class="day-label">{{ $label }}</span>
                        </div>

                        <div class="time-columns-wrapper" style="opacity: {{ $isOpen ? '1' : '0.4' }}; pointer-events: {{ $isOpen ? 'auto' : 'none' }}">
                            <!-- Start Time Input -->
                            <div class="time-trigger" onclick="openTimePicker('start_{{ $value }}', '{{ $label }} - Inicio')">
                                <span class="time-label-small">DE</span>
                                <span id="display_start_{{ $value }}">{{ $displayStart }}</span>
                                <input type="hidden" name="daily_start[{{ $value }}]" id="input_start_{{ $value }}" value="{{ $start }}">
                            </div>

                            <span style="color:#d1d5db;">—</span>

                            <!-- End Time Input -->
                            <div class="time-trigger" onclick="openTimePicker('end_{{ $value }}', '{{ $label }} - Fin')">
                                <span class="time-label-small">A</span>
                                <span id="display_end_{{ $value }}">{{ $displayEnd }}</span>
                                <input type="hidden" name="daily_end[{{ $value }}]" id="input_end_{{ $value }}" value="{{ $end }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-save-main">Guardar Horarios</button>
            </div>
        </form>
    </div>

    <!-- TAB: VACACIONES -->
    <div id="tab-vacaciones" class="tab-content">
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 24px;">
            <div>
                <h4 style="font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 4px 0;">Fechas de Cierre</h4>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">Gestiona los días festivos o vacaciones del negocio.</p>
            </div>
            <div style="display:flex; gap:10px;">
                <form id="autoLoadForm" action="{{ url('admin/configuration/business-schedule/holidays/auto-load') }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <input type="hidden" name="year" value="{{ date('Y') }}">
                    <button type="button" class="btn-create-new" style="background:#4f46e5;" onclick="confirmAutoLoad()">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="margin-right: 4px;"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>
                        Cargar Festivos Automáticamente
                    </button>
                </form>
                <button class="btn-create-new" onclick="openModal('modalVacaciones')">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Agregar Fecha
                </button>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; border: 1px solid #f3f4f6; overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb;">
                    <tr>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Fecha</th>
                        <th style="padding: 16px 24px; text-align: center; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Estado</th>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Motivo</th>
                        <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #6b7280; text-transform: uppercase;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fechas_cerradas as $fecha)
                    <tr style="border-top: 1px solid #f3f4f6;">
                        <td style="padding: 20px 24px;">
                            <div style="font-weight: 700; color: #1f2937;">{{ \Carbon\Carbon::parse($fecha->date)->format('d M, Y') }}</div>
                            <div style="font-size: 12px; color: #9ca3af; text-transform: capitalize;">{{ \Carbon\Carbon::parse($fecha->date)->formatLocalized('%A') }}</div>
                        </td>
                        <td style="padding: 20px 24px; text-align: center;">
                            <span style="display: inline-block; padding: 6px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; {{ $fecha->active ? 'background: #fee2e2; color: #991b1b;' : 'background: #dcfce7; color: #166534;' }}">
                                {{ $fecha->active ? '🔴 CERRADO' : '🟢 ABIERTO' }}
                            </span>
                        </td>
                        <td style="padding: 20px 24px; color: #4b5563; font-weight: 500;">{{ $fecha->name }}</td>
                        <td style="padding: 20px 24px; text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <form action="{{ url('admin/configuration/business-schedule/holidays/toggle/' . $fecha->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <button type="submit" class="btn" style="background: #f3f4f6; color: #1f2937; padding: 6px 12px; font-size: 12px; font-weight: 600;">
                                        {{ $fecha->active ? 'Habilitar' : 'Cerrar' }}
                                    </button>
                                </form>
                                <a href="{{ url('admin/configuration/business-schedule/holidays/delete/' . $fecha->id) }}" onclick="return confirm('¿Eliminar?')" style="color: #ef4444; padding: 6px; border-radius: 8px; background: #fef2f2; display: flex; align-items: center; justify-content: center;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 60px; text-align: center; color: #9ca3af;">
                            <div style="font-size: 40px; margin-bottom: 15px;">🗓️</div>
                            <p style="font-size: 14px; font-weight: 500;">No hay fechas especiales configuradas todavía.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: EXTENDIDO -->
    <div id="tab-extendido" class="tab-content">
        <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 24px;">
            <div>
                <h4 style="font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 4px 0;">Horario Extendido</h4>
                <p style="font-size: 14px; color: #6b7280; margin: 0;">Configura días especiales con horarios ampliados.</p>
            </div>
            <button class="btn-create-new" style="background: #6366f1;" onclick="openModal('modalExtendido')">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Registro
            </button>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            @forelse($horarios_extendidos as $hex)
                <div style="background: white; border: 1px solid #f3f4f6; border-radius: 20px; padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                        <span style="background: #e0e7ff; color: #4338ca; padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700;">REGISTRO</span>
                        <span style="color: #9ca3af; font-size: 12px;">#{{ substr($hex['id'] ?? 'N/A', -4) }}</span>
                    </div>
                    <div style="display: flex; gap: 12px; margin-bottom: 20px;">
                        <div style="font-weight: 800; color: #1f2937;">
                            {{ $hex['desde'] }} <span style="font-weight: 400; color: #9ca3af;">a</span> {{ $hex['hasta'] }}
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;">
                        <div style="background: #f9fafb; padding: 10px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 10px; color: #9ca3af; text-transform: uppercase;">AM</div>
                            <div style="font-weight: 700;">{{ $hex['am'] ? 'SÍ' : 'NO' }}</div>
                        </div>
                        <div style="background: #f9fafb; padding: 10px; border-radius: 12px; text-align: center;">
                            <div style="font-size: 10px; color: #9ca3af; text-transform: uppercase;">PM</div>
                            <div style="font-weight: 700;">{{ $hex['pm'] ? 'SÍ' : 'NO' }}</div>
                        </div>
                    </div>
                    <div style="font-size: 13px; color: #6b7280; border-top: 1px solid #f3f4f6; padding-top: 15px; display:flex; justify-content:space-between; align-items:center;">
                        <span>{{ $hex['extender'] }}</span>
                        <a href="#" style="color: #ef4444; font-weight: 700; text-decoration: none; font-size: 12px;">ELIMINAR</a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 60px; text-align: center; color: #9ca3af; background: #fdfdfd; border: 2px dashed #f3f4f6; border-radius: 20px;">
                    <p style="margin: 0;">No hay horarios extendidos.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- MODAL: VACACIONES -->
<div id="modalVacaciones" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="font-weight: 800; color: #111827;">Nueva Fecha de Cierre</h3>
            <button onclick="closeModal('modalVacaciones')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#9ca3af;">&times;</button>
        </div>
        <form action="{{ url('admin/configuration/business-schedule/holidays') }}" method="POST">
            {{ csrf_field() }}
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div class="form-group">
                        <label>Desde Fecha</label>
                        <input type="date" name="fecha_desde" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Hasta Fecha</label>
                        <input type="date" name="fecha_hasta" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 24px;">
                    <label>Nombre del Festivo / Motivo</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Eje: Navidad, Vacaciones Colectivas..." required>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; background: #fefce8; padding: 16px; border-radius: 12px; border: 1px solid #fef08a;">
                    <span style="font-size: 20px;">⚠️</span>
                    <p style="font-size: 13px; color: #854d0e; margin: 0; font-weight: 500;">Al crear esta fecha, el negocio aparecerá como CERRADO y no se podrán agendar citas.</p>
                </div>
            </div>
            <div style="padding: 24px 32px; background: #f9fafb; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn" onclick="closeModal('modalVacaciones')" style="background: #fff; border: 1px solid #e5e7eb; color: #374151; font-weight: 600;">Cancelar</button>
                <button type="submit" class="btn" style="background: #111827; color: white; font-weight: 700; padding: 10px 30px;">Crear Fecha</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: EXTENDIDO -->
<div id="modalExtendido" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="font-weight: 800; color: #111827;">Nuevo Horario Extendido</h3>
            <button onclick="closeModal('modalExtendido')" style="background:none; border:none; font-size:24px; cursor:pointer; color:#9ca3af;">&times;</button>
        </div>
        <form action="{{ url('admin/configuration/business-schedule/extended') }}" method="POST">
            {{ csrf_field() }}
            <div class="modal-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <div class="form-group">
                        <label>Día Inicio</label>
                        <input type="date" name="ext_desde" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label>Día Fin</label>
                        <input type="date" name="ext_hasta" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                
                <div style="display:flex; gap: 40px; margin-bottom: 24px;">
                    <label class="switch-container">
                        <input type="checkbox" name="ext_am" value="1"> 
                        <span style="font-weight: 600;">Habilitar Mañana (AM)</span>
                    </label>
                    <label class="switch-container">
                        <input type="checkbox" name="ext_pm" value="1"> 
                        <span style="font-weight: 600;">Habilitar Tarde (PM)</span>
                    </label>
                </div>

                <div class="form-group">
                    <label>¿Dónde aplicar?</label>
                    <select name="extender_en" class="form-control">
                        <option value="agenda">Solo Agenda Interna</option>
                        <option value="reservas">Solo Reservas Online</option>
                        <option value="ambos" selected>Agenda y Reservas Online</option>
                    </select>
                </div>
            </div>
            <div style="padding: 24px 32px; background: #f9fafb; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn" onclick="closeModal('modalExtendido')" style="background: #fff; border: 1px solid #e5e7eb; color: #374151; font-weight: 600;">Cancelar</button>
                <button type="submit" class="btn" style="background: #6366f1; color: white; font-weight: 700; padding: 10px 30px;">Guardar Configuración</button>
            </div>
        </form>
    </div>
</div>

@endsection

<!-- GLOBAL TIME PICKER MODAL -->
<div id="timeWheelModal" class="wheel-modal">
    <div class="wheel-content">
        <div style="text-align:center; font-weight:700; font-size:18px; margin-bottom:10px;" id="wheelTitle">Selecciona Hora</div>
        <div style="text-align:center; font-size:13px; color:#9ca3af; margin-bottom:20px;">Desliza para ajustar</div>
        
        <div class="wheel-interface">
            <div class="highlight-bar"></div>
            <div class="wheel-scroller" id="wheelScroller">
                <!-- Generated JS intervals -->
            </div>
        </div>

        <button type="button" class="btn-confirm-time" onclick="confirmTimeSelection()">Confirmar Hora</button>
    </div>
</div>

<script>
    // State management
    let activeInputId = null;

    // Generate 12h format intervals but keep 24h values
    function generateIntervals() {
        const times = [];
        for (let i = 0; i < 24; i++) {
            for (let j = 0; j < 60; j += 5) {
                const h24 = i.toString().padStart(2, '0');
                const m = j.toString().padStart(2, '0');
                const ampm = i >= 12 ? 'PM' : 'AM';
                const h12 = (i % 12 || 12).toString().padStart(2, '0');
                
                times.push({
                    val: `${h24}:${m}`,
                    display: `${h12}:${m} ${ampm}`
                });
            }
        }
        return times;
    }

    function initWheel() {
        const scroller = document.getElementById('wheelScroller');
        if (scroller.children.length > 5) return; // Already init

        scroller.innerHTML = '<div style="height:75px; flex-shrink:0;"></div>';
        
        const times = generateIntervals();
        times.forEach(t => {
            const div = document.createElement('div');
            div.className = 'wheel-item';
            div.textContent = t.display;
            div.dataset.val = t.val;
            // Manual selection on click
            div.onclick = function() {
                this.scrollIntoView({ block: "center", behavior: 'smooth' });
                document.querySelectorAll('.wheel-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            };
            scroller.appendChild(div);
        });
        
        scroller.insertAdjacentHTML('beforeend', '<div style="height:75px; flex-shrink:0;"></div>');

        // Observer with lower threshold for better reactivity
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
               if (entry.isIntersecting) {
                   document.querySelectorAll('.wheel-item').forEach(i => i.classList.remove('active'));
                   entry.target.classList.add('active');
               } 
            });
        }, { root: scroller, threshold: 0.6, rootMargin: "-40% 0px -40% 0px" });
        
        document.querySelectorAll('.wheel-item').forEach(el => observer.observe(el));

        // FALLBACK: If observer fails or user stops scrolling, center the nearest item
        let isScrolling;
        scroller.addEventListener('scroll', function() {
            window.clearTimeout(isScrolling);
            isScrolling = setTimeout(function() {
                const items = document.querySelectorAll('.wheel-item');
                const scrollerRect = scroller.getBoundingClientRect();
                const center = scrollerRect.top + scrollerRect.height / 2;
                
                let closest = null;
                let minDiff = Infinity;
                
                items.forEach(item => {
                    const rect = item.getBoundingClientRect();
                    const itemCenter = rect.top + rect.height / 2;
                    const diff = Math.abs(center - itemCenter);
                    if(diff < minDiff) {
                        minDiff = diff;
                        closest = item;
                    }
                });
                
                if(closest) {
                    items.forEach(i => i.classList.remove('active'));
                    closest.classList.add('active');
                }
            }, 100);
        });
    }

    function openTimePicker(inputId, title) {
        activeInputId = inputId;
        document.getElementById('wheelTitle').textContent = title;
        document.getElementById('timeWheelModal').style.display = 'flex';
        
        initWheel(); 
        
        // Scroll to current value
        const currentVal = document.getElementById('input_' + inputId).value;
        setTimeout(() => {
            const items = document.querySelectorAll('.wheel-item');
            let found = false;
            for(let item of items) {
                if (item.dataset.val === currentVal) {
                    item.scrollIntoView({ block: "center" });
                    item.classList.add('active'); // Force active
                    found = true;
                    break;
                }
            }
            if(!found && items.length > 0) items[1].classList.add('active'); // Fallback
        }, 50);
    }
    
    function confirmTimeSelection() {
        const activeItem = document.querySelector('.wheel-item.active');
        if (activeItem && activeInputId) {
            const val = activeItem.dataset.val;
            const display = activeItem.textContent;
            
            document.getElementById('input_' + activeInputId).value = val;
            document.getElementById('display_' + activeInputId).textContent = display;
            
            // Close modal
            document.getElementById('timeWheelModal').style.display = 'none';
        } else {
            // fallback close if nothing selected
            document.getElementById('timeWheelModal').style.display = 'none';
        }
    }
    
    // Close on backdrop click
    document.getElementById('timeWheelModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.style.display = 'none';
        }
    });

    // Toggle logic
    function toggleDayRow(checkbox) {
        const wrapper = checkbox.closest('.day-row').querySelector('.time-columns-wrapper');
        if (checkbox.checked) {
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        } else {
            wrapper.style.opacity = '0.4';
            wrapper.style.pointerEvents = 'none';
        }
    }

    /* Existing functions for Tabs/Modals */
    function switchTab(tabName, event) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        
        document.getElementById('tab-' + tabName).classList.add('active');
        if (event) event.target.classList.add('active');
    }
    
    function openModal(id) {
        document.getElementById(id).style.display = 'flex';
    }
    
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>
