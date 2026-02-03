@extends('admin/dashboard_layout')

@section('content')

<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<!-- Lucide Icons -->
<link rel="stylesheet" href="https://unpkg.com/lucide-static/font/lucide.css">
<!-- Custom AI Core Styles -->
<link rel="stylesheet" href="{{ asset('css/ai-core.css') }}">

<style>
    /* Light Theme Overrides for Meta Style */
    .agent-container {
        display: flex;
        height: calc(100vh - 60px);
        margin: -24px;
        background: #f0f2f5;
        color: #1c1e21;
        overflow: hidden;
    }

    .view-pane {
        display: none;
        width: 100%;
        height: 100%;
        overflow-y: auto;
        background: #f0f2f5;
    }
    .view-pane.active {
        display: block;
    }

    /* Inbox Styles Based on Image 1 */
    .inbox-layout {
        display: grid;
        grid-template-columns: 360px 1fr 300px;
        height: 100%;
        background: #fff;
    }

    .inbox-sidebar {
        border-right: 1px solid #e4e6eb;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .inbox-header {
        padding: 16px;
        border-bottom: 1px solid #e4e6eb;
    }

    .msg-list {
        overflow-y: auto;
        flex: 1;
    }

    .msg-item {
        padding: 12px 16px;
        display: flex;
        gap: 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f2f5;
        position: relative;
    }
    .msg-item:hover { background: #f5f6f7; }
    .msg-item.active { background: #ebf5ff; }
    .msg-item.active::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        background: #1877f2;
    }

    .chat-area {
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-header {
        padding: 12px 20px;
        border-bottom: 1px solid #e4e6eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .bubble {
        padding: 8px 12px;
        border-radius: 18px;
        max-width: 70%;
        font-size: 14px;
        line-height: 1.4;
    }
    .bubble-in { background: #f0f2f5; color: #1c1e21; align-self: flex-start; }
    .bubble-out { background: #0084ff; color: #fff; align-self: flex-end; }

    .chat-input {
        padding: 16px;
        border-top: 1px solid #e4e6eb;
    }

    .details-panel {
        border-left: 1px solid #e4e6eb;
        padding: 20px;
        background: #fff;
        overflow-y: auto;
    }

    /* Planner Styles Based on Image 2, 3 & 4 */
    .planner-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: #e4e6eb;
        border: 1px solid #e4e6eb;
    }
    .planner-day {
        background: #fff;
        min-height: 150px;
        padding: 8px;
    }
    .planner-header {
        background: #f0f2f5;
        padding: 10px;
        text-align: center;
        font-weight: 600;
        font-size: 12px;
        color: #65676b;
        text-transform: uppercase;
    }

    .post-preview {
        background: #f0f2f5;
        border-radius: 4px;
        padding: 4px;
        margin-bottom: 4px;
        font-size: 10px;
        border: 1px solid #e4e6eb;
    }

    /* Modal Style Creator */
    .creator-overlay {
        position: fixed;
        inset: 0;
        background: rgba(255, 255, 255, 0.8);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .creator-modal {
        background: #fff;
        width: 900px;
        max-width: 95vw;
        height: 600px;
        border-radius: 8px;
        box-shadow: 0 12px 28px rgba(0,0,0,0.2);
        display: grid;
        grid-template-columns: 1fr 1fr;
        overflow: hidden;
    }
</style>

<div class="agent-container">
    
    <!-- Sidebar Meta Style -->
    <div class="ai-sidebar">
        <div style="padding: 10px 15px; margin-bottom: 20px;">
            <div style="font-weight: 800; font-size: 20px; color: #1c1e21; display: flex; align-items: center; gap: 10px;">
                <i class="lucide-brain-circuit" style="color: #10a37f;"></i>
                NeuraCore
            </div>
        </div>

        <div class="ai-nav-item active" onclick="switchAIPane('overview', this)"><i class="lucide-home"></i> Inicio</div>
        <div class="ai-nav-item" onclick="switchAIPane('inbox', this)"><i class="lucide-message-circle"></i> Bandeja de entrada</div>

        <div class="ai-nav-item" onclick="switchAIPane('automation', this)"><i class="lucide-zap"></i> Automatizaciones</div>
        <div class="ai-nav-item" onclick="switchAIPane('settings', this)"><i class="lucide-settings"></i> Configuración</div>
    </div>

    <!-- Main Viewport -->
    <div style="flex: 1; position: relative; overflow: hidden;">
        
        <!-- OVERVIEW PANE -->
        <div id="pane-overview" class="view-pane active">
            <div style="padding: 30px; max-width: 1000px; margin: 0 auto;">
                <div class="glass-panel" style="padding: 24px; margin-bottom: 24px; background: #fff;">
                    <h1 style="font-size: 24px; font-weight: 700; margin: 0 0 8px;">¡Hola! Aquí tienes el resumen de hoy</h1>
                    <p style="color: #65676b; margin: 0;">Tu asistente de IA ha estado ocupado gestionando tu negocio.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
                    <div class="glass-panel" style="padding: 20px; background: #fff;">
                        <div style="color: #65676b; font-size: 12px; font-weight: 600;">MENSAJES RECIBIODS</div>
                        <div style="font-size: 32px; font-weight: 700; margin: 10px 0;">128</div>
                        <div style="color: #10a37f; font-size: 12px; font-weight: 600;">94% Respondidos por IA</div>
                    </div>
                    <div class="glass-panel" style="padding: 20px; background: #fff;">
                        <div style="color: #65676b; font-size: 12px; font-weight: 600;">CITAS AGENDADAS</div>
                        <div style="font-size: 32px; font-weight: 700; margin: 10px 0;">12</div>
                        <div style="color: #10a37f; font-size: 12px; font-weight: 600;">Sin intervención humana</div>
                    </div>
                    <div class="glass-panel" style="padding: 20px; background: #fff;">
                        <div style="color: #65676b; font-size: 12px; font-weight: 600;">NUEVOS CLIENTES</div>
                        <div style="font-size: 32px; font-weight: 700; margin: 10px 0;">8</div>
                        <div style="color: #10a37f; font-size: 12px; font-weight: 600;">Detectados hoy</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INBOX PANE (Image 1 Style) -->
        <div id="pane-inbox" class="view-pane">
            <div class="inbox-layout">
                <!-- Message List -->
                <div class="inbox-sidebar">
                    <div class="inbox-header">
                        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 12px;">Bandeja de entrada</h2>
                        <div style="background: #f0f2f5; border-radius: 20px; padding: 8px 12px; display: flex; align-items: center; gap: 8px;">
                            <i class="lucide-search" style="font-size: 14px; color: #65676b;"></i>
                            <input type="text" placeholder="Buscar..." style="background: transparent; border: none; outline: none; font-size: 14px; width: 100%;">
                        </div>
                    </div>
                    <div class="msg-list">
                        @foreach($conversacionesActivas as $index => $conv)
                        <div class="msg-item {{ $index == 0 ? 'active' : '' }}" onclick="loadChat('{{ $conv['cliente'] }}')">
                            <div class="avatar">{{ substr($conv['cliente'], 0, 1) }}</div>
                            <div style="flex: 1; overflow: hidden;">
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="font-weight: 600; font-size: 14px;">{{ $conv['cliente'] }}</span>
                                    <span style="font-size: 11px; color: #65676b;">{{ $conv['hace'] }}</span>
                                </div>
                                <div style="font-size: 13px; color: #65676b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $conv['ultimo_mensaje'] }}</div>
                                <div style="margin-top: 4px;">
                                    <span style="background: #e7f3ff; color: #1877f2; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 10px;">{{ $index == 0 ? 'Urgent' : 'General' }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="chat-area">
                    <div class="chat-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div class="avatar" style="width: 40px; height: 40px;">S</div>
                            <div>
                                <div style="font-weight: 700; font-size: 15px;" id="chat-name">Sara Truque Lucio</div>
                                <div style="font-size: 12px; color: #65676b;">Activo ahora</div>
                            </div>
                        </div>
                        <div style="display: flex; gap: 15px; color: #65676b;">
                            <i class="lucide-phone"></i>
                            <i class="lucide-video"></i>
                            <i class="lucide-info"></i>
                        </div>
                    </div>
                    <div class="chat-messages" id="chat-box">
                        <div class="bubble bubble-in">Hola, ¿qué servicios tienen disponibles?</div>
                        <div class="bubble bubble-out">¡Hola! Ofrecemos limpieza facial, hidratación profunda y más. ¿Te gustaría agendar una cita?</div>
                    </div>
                    <div class="chat-input">
                        <div style="background: #f0f2f5; border-radius: 20px; padding: 10px 16px; display: flex; align-items: center; gap: 12px;">
                            <i class="lucide-plus-circle" style="color: #65676b;"></i>
                            <input type="text" placeholder="Escribe un mensaje..." style="background: transparent; border: none; outline: none; flex: 1; font-size: 14px;">
                            <i class="lucide-smile" style="color: #65676b;"></i>
                            <i class="lucide-send" style="color: #0084ff; cursor: pointer;"></i>
                        </div>
                    </div>
                </div>

                <!-- Contact Details -->
                <div class="details-panel">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <div class="avatar" style="width: 80px; height: 80px; margin: 0 auto 12px; font-size: 32px;">S</div>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Sara T. Lucio</h3>
                        <p style="color: #65676b; font-size: 14px;">Cliente frecuente</p>
                    </div>
                    <hr style="border: none; border-top: 1px solid #e4e6eb; margin: 20px 0;">
                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 15px;">Información de contacto</div>
                    <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                        <div style="display: flex; align-items: center; gap: 10px; color: #65676b;"><i class="lucide-phone" size="16"></i> +57 300 123 4567</div>
                        <div style="display: flex; align-items: center; gap: 10px; color: #65676b;"><i class="lucide-mail" size="16"></i> sara.lucio@email.com</div>
                    </div>
                </div>
            </div>
        </div>



        <!-- AUTOMATION PANE -->
        <div id="pane-automation" class="view-pane">
            <div style="padding: 30px; max-width: 1000px; margin: 0 auto;">
                <div class="glass-panel" style="padding: 24px; margin-bottom: 24px; background: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px;">Flujos de Automatización</h2>
                            <p style="color: #65676b; margin: 0;">Configura cómo reacciona tu agente ante diferentes eventos.</p>
                        </div>
                        <button class="btn-primary" onclick="openFlowCreator()"><i class="lucide-plus"></i> Nuevo Flujo</button>
                    </div>
                </div>

                <div id="flows-container" style="display: grid; gap: 16px;">
                    <!-- Flow Item: Confirmation -->
                    <div class="flow-item" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e4e6eb; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 40px; height: 40px; background: #e7f3ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="lucide-calendar-check" style="color: #1877f2;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 15px;">Confirmación de Citas</div>
                                <div style="font-size: 13px; color: #65676b;">Enviar WhatsApp 24h antes de la cita</div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>

                    <!-- Flow Item: Recovery -->
                    <div class="flow-item" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e4e6eb; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 40px; height: 40px; background: #e4f7ef; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="lucide-star" style="color: #10a37f;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600; font-size: 15px;">Recuperación de Clientes</div>
                                <div style="font-size: 13px; color: #65676b;">Contactar clientes con +60 días sin visita</div>
                            </div>
                        </div>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- FLOW CREATOR MODAL (Advanced Style) -->
        <div id="flow-modal" class="creator-overlay" style="display: none; z-index: 2000;">
            <div class="creator-modal" style="width: 800px; max-width: 95vw; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; height: 80vh; max-height: 700px;">
                
                <!-- Modal Header -->
                <div style="padding: 20px 24px; border-bottom: 1px solid #e4e6eb; background: #fff; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Nueva Automatización</h3>
                        <p style="margin: 4px 0 0; font-size: 12px; color: #65676b;">Diseña flujos lógicos de "Causa y Efecto".</p>
                    </div>
                    <i class="lucide-x" style="cursor: pointer; padding: 8px; border-radius: 50%; background: #f0f2f5;" onclick="closeFlowCreator()"></i>
                </div>
                
                <!-- Modal Body (Split Layout) -->
                <div style="display: flex; flex: 1; background: #f7f8fa; overflow: hidden;">
                    
                    <!-- Left: Configuration Form -->
                    <div style="flex: 1; padding: 24px; overflow-y: auto; border-right: 1px solid #e4e6eb; background: #fff;">
                        
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #65676b; margin-bottom: 8px;">1. Nombre del Flujo</label>
                            <input type="text" id="flow-name" placeholder="Ej: Recuperación de Carrito Abandonado" style="width: 100%; padding: 12px; border: 1px solid #e4e6eb; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;">
                        </div>

                        <!-- Step 1: Trigger -->
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #1877f2; margin-bottom: 10px;">
                                <i class="lucide-zap" style="font-size: 12px; margin-right: 4px;"></i> 2. Disparador (Trigger)
                            </label>
                            <div style="border: 1px solid #1877f2; background: #f0f7ff; border-radius: 10px; padding: 4px;">
                                <select id="flow-trigger" style="width: 100%; padding: 10px; border: none; background: transparent; outline: none; font-size: 14px; font-weight: 500;">
                                    <optgroup label="Citas y Reservas">
                                        <option value="appointment_created">Nueva Cita Agendada</option>
                                        <option value="appointment_completed">Cita Completada (Check-out)</option>
                                        <option value="appointment_cancelled">Cita Cancelada</option>
                                        <option value="appointment_noshow">Cliente No Asistió (No-Show)</option>
                                    </optgroup>
                                    <optgroup label="Comportamiento del Cliente">
                                        <option value="customer_birthday">Es el Cumpleaños del Cliente</option>
                                        <option value="customer_inactive_30">Inactivo por 30 días</option>
                                        <option value="customer_inactive_60">Inactivo por 60 días</option>
                                        <option value="customer_new">Nuevo Cliente Registrado</option>
                                        <option value="cart_abandoned">Intento de Reserva Incompleto</option>
                                    </optgroup>
                                    <optgroup label="Eventos Externos">
                                        <option value="review_positive">Nueva Reseña Positiva (4-5 estrellas)</option>
                                        <option value="review_negative">Nueva Reseña Negativa (1-3 estrellas)</option>
                                        <option value="payment_received">Pago Recibido</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <!-- Arrow Connector -->
                        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
                            <i class="lucide-arrow-down" style="color: #65676b;"></i>
                        </div>

                        <!-- Step 2: Action -->
                        <div style="margin-bottom: 24px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #10a37f; margin-bottom: 10px;">
                                <i class="lucide-play-circle" style="font-size: 12px; margin-right: 4px;"></i> 3. Acción a Ejecutar
                            </label>
                            <div style="border: 1px solid #10a37f; background: #f0fdf4; border-radius: 10px; padding: 4px;">
                                <select id="flow-action" style="width: 100%; padding: 10px; border: none; background: transparent; outline: none; font-size: 14px; font-weight: 500;">
                                    <optgroup label="Comunicación Directa">
                                        <option value="whatsapp_msg">Enviar Mensaje de WhatsApp (Plantilla)</option>
                                        <option value="whatsapp_custom">Enviar WhatsApp Personalizado (IA)</option>
                                        <option value="email_send">Enviar Correo Electrónico</option>
                                        <option value="sms_send">Enviar SMS</option>
                                    </optgroup>
                                    <optgroup label="Gestión Interna">
                                        <option value="create_task">Crear Tarea para Recepcionista</option>
                                        <option value="notify_admin">Notificar al Admin (Push)</option>
                                        <option value="tag_customer">Etiquetar Cliente (Segmentación)</option>
                                        <option value="update_crm">Actualizar Estado en CRM</option>
                                    </optgroup>
                                    <optgroup label="Marketing y Fidelización">
                                        <option value="send_coupon">Enviar Cupón de Descuento</option>
                                        <option value="request_review">Solicitar Reseña en Google</option>
                                        <option value="add_points">Sumar Puntos de Lealtad</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Visual Preview & logic -->
                    <div style="width: 300px; padding: 24px; background: #f7f8fa; display: flex; flex-direction: column;">
                        <h4 style="font-size: 12px; font-weight: 700; text-transform: uppercase; margin-top: 0; color: #65676b;">Lógica Visual</h4>
                        
                        <div id="logic-viz" style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; opacity: 0.8;">
                            <!-- Dynamic Content -->
                            <div style="padding: 12px 16px; background: white; border: 1px solid #e4e6eb; border-radius: 8px; font-size: 12px; font-weight: 600; width: 100%; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <i class="lucide-zap" style="color: #1877f2; margin-right: 4px; font-size: 12px;"></i>
                                <span id="viz-trigger">Nueva Cita</span>
                            </div>
                            <div style="height: 20px; width: 2px; background: #ccc;"></div>
                            <div style="padding: 12px 16px; background: white; border: 1px solid #e4e6eb; border-radius: 8px; font-size: 12px; font-weight: 600; width: 100%; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <i class="lucide-play-circle" style="color: #10a37f; margin-right: 4px; font-size: 12px;"></i>
                                <span id="viz-action">Enviar WhatsApp</span>
                            </div>
                        </div>

                        <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid #e4e6eb;">
                            <div style="font-size: 11px; color: #65676b; margin-bottom: 12px; line-height: 1.4;">
                                <b>Nota:</b> Esta automatización se ejecutará inmediatamente después de que ocurra el disparador.
                            </div>
                            <button class="btn-primary" onclick="saveNewFlow()" style="width: 100%; justify-content: center;">
                                <i class="lucide-save"></i> Guardar y Activar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SETTINGS PANE -->
        <div id="pane-settings" class="view-pane" style="overflow-y: auto; height: 100%;">
            <div style="padding: 40px; max-width: 900px; margin: 0 auto;">
                <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 30px; letter-spacing: -0.5px;">Configuración del Agente</h2>
                
                <form id="settingsForm" method="POST" action="{{ url('admin/configuration/save-ai-settings') }}"> <!-- Placeholder Action --> 
                    @csrf
                    <!-- Social Media Channels -->
                    <div class="glass-panel" style="background: #fff; padding: 32px; border-radius: 16px; margin-bottom: 30px; border: 1px solid #e4e6eb; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; color: #1c1e21;">
                            <div style="width: 32px; height: 32px; background: #f0f2f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="lucide-share-2" style="font-size: 18px;"></i>
                            </div>
                            Conectar Canales
                        </h3>
                        
                        <div style="display: grid; gap: 24px;">
                            <!-- Facebook -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #f0f2f5;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="position: relative;">
                                        <i class="lucide-facebook" style="color: #1877f2; font-size: 32px;"></i>
                                        @if($settings['social_fb_connected'])
                                        <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; background: #31a24c; border: 2px solid #fff; border-radius: 50%;"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 16px;">Facebook Page</div>
                                        <div style="font-size: 13px; color: #65676b; margin-top: 2px;">Para responder comentarios y Messenger</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-{{ $settings['social_fb_connected'] ? 'secondary' : 'primary' }}" onclick="connectSocial('fb')" id="btn-fb">
                                    {{ $settings['social_fb_connected'] ? 'Desconectar' : 'Conectar' }}
                                </button>
                                <input type="hidden" name="social_fb_connected" id="input-fb" value="{{ $settings['social_fb_connected'] ? '1' : '0' }}">
                            </div>

                            <!-- Instagram -->
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid #f0f2f5;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="position: relative;">
                                        <i class="lucide-instagram" style="color: #e4405f; font-size: 32px;"></i>
                                        @if($settings['social_ig_connected'])
                                        <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; background: #31a24c; border: 2px solid #fff; border-radius: 50%;"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 16px;">Instagram Business</div>
                                        <div style="font-size: 13px; color: #65676b; margin-top: 2px;">Para responder DMs y comentarios</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-{{ $settings['social_ig_connected'] ? 'secondary' : 'primary' }}" onclick="connectSocial('ig')" id="btn-ig">
                                    {{ $settings['social_ig_connected'] ? 'Desconectar' : 'Conectar' }}
                                </button>
                                <input type="hidden" name="social_ig_connected" id="input-ig" value="{{ $settings['social_ig_connected'] ? '1' : '0' }}">
                            </div>

                            <!-- WhatsApp -->
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="position: relative;">
                                        <div style="width: 32px; height: 32px; background: #25d366; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                            <i class="lucide-phone" style="font-size: 18px;"></i>
                                        </div>
                                        @if($settings['social_wa_connected'])
                                        <div style="position: absolute; bottom: -2px; right: -2px; width: 14px; height: 14px; background: #31a24c; border: 2px solid #fff; border-radius: 50%;"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; font-size: 16px;">WhatsApp Business API</div>
                                        <div style="font-size: 13px; color: #65676b; margin-top: 2px;">Para gestión de citas automatizada</div>
                                    </div>
                                </div>
                                <button type="button" class="btn-{{ $settings['social_wa_connected'] ? 'secondary' : 'primary' }}" onclick="connectSocial('wa')" id="btn-wa">
                                    {{ $settings['social_wa_connected'] ? 'Desconectar' : 'Conectar' }}
                                </button>
                                <input type="hidden" name="social_wa_connected" id="input-wa" value="{{ $settings['social_wa_connected'] ? '1' : '0' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Autonomy Settings -->
                    <div class="glass-panel" style="background: #fff; padding: 32px; border-radius: 16px; border: 1px solid #e4e6eb; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 40px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; color: #1c1e21;">
                            <div style="width: 32px; height: 32px; background: #f0f2f5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="lucide-cpu" style="font-size: 18px;"></i>
                            </div>
                            Nivel de Autonomía
                        </h3>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div style="background: #f7f8fa; padding: 16px; border-radius: 12px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-weight: 600; font-size: 15px;">Cerrar Ventas</span>
                                    <label class="switch">
                                        <input type="checkbox" name="agent_autonomy_sales" {{ $settings['agent_autonomy_sales'] ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <p style="font-size: 13px; color: #65676b; margin: 0; line-height: 1.4;">Permitir al agente enviar links de pago y confirmar transacciones.</p>
                            </div>

                            <div style="background: #f7f8fa; padding: 16px; border-radius: 12px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-weight: 600; font-size: 15px;">Agendar Citas</span>
                                    <label class="switch">
                                        <input type="checkbox" name="agent_autonomy_msg" {{ $settings['agent_autonomy_msg'] ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <p style="font-size: 13px; color: #65676b; margin: 0; line-height: 1.4;">Permitir al agente consultar disponibilidad y crear reservas.</p>
                            </div>

                            <div style="background: #f7f8fa; padding: 16px; border-radius: 12px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-weight: 600; font-size: 15px;">Modificar/Eliminar</span>
                                    <label class="switch">
                                        <input type="checkbox" name="agent_autonomy_edit" {{ $settings['agent_autonomy_edit'] ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <p style="font-size: 13px; color: #65676b; margin: 0; line-height: 1.4;">Permitir cambio y cancelación de citas sin supervisión.</p>
                            </div>
                            
                            <div style="background: #f7f8fa; padding: 16px; border-radius: 12px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <span style="font-weight: 600; font-size: 15px;">Escuchar Llamadas</span>
                                    <label class="switch">
                                        <input type="checkbox" name="agent_autonomy_listen" {{ $settings['agent_autonomy_listen'] ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <p style="font-size: 13px; color: #65676b; margin: 0; line-height: 1.4;">Habilitar transcripción y análisis de voz en tiempo real.</p>
                            </div>
                        </div>

                        <div style="padding-top: 24px; margin-top: 24px; border-top: 1px solid #f0f2f5; text-align: right;">
                            <button type="button" class="btn-primary" onclick="saveSettings()" style="padding: 10px 24px; font-size: 14px;">Guardar Configuración</button>
                        </div>
                    </div>
                </form>

                <!-- Footer Signature -->
                <div style="text-align: center; margin-top: 50px; padding-bottom: 30px; opacity: 0.7;">
                    <div style="font-size: 12px; color: #65676b; font-weight: 500;">
                        POWERED BY <span style="font-weight: 700; color: #1c1e21;">NEURACORE™ AI</span>
                    </div>
                    <div style="font-size: 11px; color: #8a8d91; margin-top: 4px;">
                        Software desarrollado por Núcleo Neuronal | {{ date('Y') }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- CONNECTION MODAL -->
<div id="connection-modal" class="creator-overlay" style="display: none; z-index: 1000;">
    <div style="background: white; width: 400px; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <div id="conn-step-1">
            <div style="width: 60px; height: 60px; margin: 0 auto 20px; border-radius: 50%; background: #f0f2f5; display: flex; align-items: center; justify-content: center;">
                <i class="lucide-loader-2 spin" style="font-size: 30px; color: #1877f2;"></i>
            </div>
            <h3 style="margin-bottom: 10px; font-size: 18px;">Conectando con Meta...</h3>
            <p style="color: #65676b; font-size: 14px; margin-bottom: 0;">Por favor espera mientras validamos tus credenciales.</p>
        </div>
        <div id="conn-step-2" style="display: none;">
            <div style="width: 60px; height: 60px; margin: 0 auto 20px; border-radius: 50%; background: #e7f3ff; display: flex; align-items: center; justify-content: center;">
                <i class="lucide-check" style="font-size: 30px; color: #1877f2;"></i>
            </div>
            <h3 style="margin-bottom: 10px; font-size: 18px;">¡Conexión Exitosa!</h3>
            <p style="color: #65676b; font-size: 14px; margin-bottom: 20px;">Tu cuenta ha sido vinculada correctamente al Agente.</p>
            <button class="btn-primary" style="width: 100%;" onclick="closeConnectionModal()">Continuar</button>
        </div>
    </div>
</div>

<script>
    // --- SETTINGS LOGIC ---
    let currentConnectingPlatform = null;

    function saveSettings() {
        const btn = document.querySelector('button[onclick="saveSettings()"]');
        const originalText = btn.innerText;
        btn.innerText = 'Guardando...';
        btn.disabled = true;

        const form = document.getElementById('settingsForm');
        const formData = new FormData(form);
        formData.append('agent_is_saving_settings', '1'); // Flag to handle unchecked checkboxes

        fetch('{{ url("admin/configuration/save-ai-settings") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                btn.innerText = '¡Guardado!';
                btn.style.background = '#31a24c';
                btn.style.borderColor = '#31a24c';
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.disabled = false;
                    btn.style.background = '';
                    btn.style.borderColor = '';
                }, 2000);
            } else {
                alert('Error al guardar: ' + (data.message || 'Error desconocido'));
                btn.innerText = originalText;
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión');
            btn.innerText = originalText;
            btn.disabled = false;
        });
    }

    function connectSocial(platform) {
        const input = document.getElementById('input-' + platform);
        const btn = document.getElementById('btn-' + platform);
        
        // Disconnect Logic
        if(input.value == '1') {
            if(confirm('¿Estás seguro de desconectar esta cuenta? El agente dejará de responder mensajes.')) {
                // Save disconnect state immediately
                updateConnectionStatus(platform, '0').then(() => {
                    location.reload();
                });
            }
            return;
        }

        // Connect Logic (Open Simulation Modal)
        currentConnectingPlatform = platform;
        const modal = document.getElementById('connection-modal');
        const step1 = document.getElementById('conn-step-1');
        const step2 = document.getElementById('conn-step-2');
        
        modal.style.display = 'flex';
        step1.style.display = 'block';
        step2.style.display = 'none';

        // Simulate API delay, then show success
        setTimeout(() => {
            step1.style.display = 'none';
            step2.style.display = 'block';
        }, 2000);
    }

    function closeConnectionModal() {
        if (currentConnectingPlatform) {
            // Save 'Connected' state (1)
            updateConnectionStatus(currentConnectingPlatform, '1').then(() => {
                document.getElementById('connection-modal').style.display = 'none';
                location.reload(); 
            });
        } else {
             document.getElementById('connection-modal').style.display = 'none';
        }
    }

    // Helper to save connection status via AJAX
    function updateConnectionStatus(platform, status) {
        const formData = new FormData();
        formData.append('social_' + platform + '_connected', status);
        
        return fetch('{{ url("admin/configuration/save-ai-settings") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    }

    // --- AUTOMATION FLOW CREATOR LOGIC ---

    function openFlowCreator() {
        document.getElementById('flow-modal').style.display = 'flex';
        updateFlowSummary(); // Initialize summary
    }

    function closeFlowCreator() {
        document.getElementById('flow-modal').style.display = 'none';
        // Reset form
        document.getElementById('flow-name').value = '';
    }

    // Dynamic Summary Update
    const triggerSelect = document.getElementById('flow-trigger');
    const actionSelect = document.getElementById('flow-action');
    
    if(triggerSelect && actionSelect) {
        function updateFlowSummary() {
            const triggerText = triggerSelect.options[triggerSelect.selectedIndex].text;
            const actionText = actionSelect.options[actionSelect.selectedIndex].text;
            
            // Update Visual Preview Nodes
            const vizTrigger = document.getElementById('viz-trigger');
            if(vizTrigger) vizTrigger.innerText = triggerText;
            
            const vizAction = document.getElementById('viz-action');
            if(vizAction) vizAction.innerText = actionText;
        }
        triggerSelect.addEventListener('change', updateFlowSummary);
        actionSelect.addEventListener('change', updateFlowSummary);
    }

    function saveNewFlow() {
        const name = document.getElementById('flow-name').value;
        const triggerText = triggerSelect.options[triggerSelect.selectedIndex].text;
        const actionText = actionSelect.options[actionSelect.selectedIndex].text;
        
        if(!name) {
            alert('Por favor asigna un nombre al flujo.');
            return;
        }

        const container = document.getElementById('flows-container');
        
        // Create new flow item DOM
        const newFlowHtml = `
            <div class="flow-item" style="background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #e4e6eb; display: flex; align-items: center; justify-content: space-between; animation: fadeIn 0.5s;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 40px; height: 40px; background: #e7f3ff; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="lucide-zap" style="color: #1877f2;"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; font-size: 15px;">${name}</div>
                        <div style="font-size: 13px; color: #65676b;">${triggerText} → ${actionText}</div>
                    </div>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
        `;

        // Append to list
        container.insertAdjacentHTML('beforeend', newFlowHtml);
        
        // Re-initialize icons for the new element if needed (Lucide usually needs re-scan or script handles it)
        // Since we are using lucide-static css, the icons are classes, but the svg replacement might need a trigger.
        // Assuming user setup has lucide.createIcons();
        if(window.lucide) lucide.createIcons();

        closeFlowCreator();
        
        // Optional: Show success toast
        // alert('Flujo creado exitosamente');
    }
</script>

<!-- POST CREATOR MODAL (Image 3 & 4 style) -->
<div id="creator-modal" class="creator-overlay" style="display: none;">
    <div class="creator-modal">
        <div style="padding: 24px; border-right: 1px solid #e4e6eb;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Crear publicación</h3>
                <i class="lucide-x" style="cursor: pointer;" onclick="closeCreator()"></i>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Publicar en</label>
                <div style="display: flex; gap: 10px;">
                    <div style="padding: 8px 12px; border: 1px solid #e4e6eb; border-radius: 6px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="lucide-facebook" style="color: #1877f2;"></i> Facebook
                    </div>
                    <div style="padding: 8px 12px; border: 1px solid #e4e6eb; border-radius: 6px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="lucide-instagram" style="color: #e4405f;"></i> Instagram
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Texto de la publicación</label>
                <textarea placeholder="¿En qué estás pensando?" style="width: 100%; height: 120px; border: 1px solid #e4e6eb; border-radius: 8px; padding: 12px; font-size: 14px; resize: none; outline: none;"></textarea>
            </div>

            <div style="margin-bottom: 20px;">
                <button class="btn-secondary" style="width: 100%;"><i class="lucide-image"></i> Agregar fotos/videos</button>
            </div>

            <div style="margin-top: auto; padding-top: 20px; display: flex; gap: 12px; justify-content: flex-end;">
                <button class="btn-secondary" onclick="closeCreator()">Cancelar</button>
                <button class="btn-primary">Publicar</button>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div style="background: #f0f2f5; padding: 40px; display: flex; align-items: center; justify-content: center;">
            <div style="background: #fff; width: 100%; max-width: 320px; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                <div style="padding: 12px; display: flex; align-items: center; gap: 8px;">
                    <div class="avatar" style="width: 32px; height: 32px;">L</div>
                    <div>
                        <div style="font-weight: 700; font-size: 13px;">Tu Página</div>
                        <div style="font-size: 11px; color: #65676b;">Hace un momento</div>
                    </div>
                </div>
                <div style="height: 200px; background: #e4e6eb; display: flex; align-items: center; justify-content: center;">
                    <i class="lucide-image" style="font-size: 40px; color: #65676b;"></i>
                </div>
                <div style="padding: 12px;">
                    <div style="height: 10px; background: #f0f2f5; width: 100%; margin-bottom: 8px; border-radius: 4px;"></div>
                    <div style="height: 10px; background: #f0f2f5; width: 60%; border-radius: 4px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchAIPane(paneId, btn) {
        document.querySelectorAll('.view-pane').forEach(el => el.classList.remove('active'));
        document.getElementById(`pane-${paneId}`).classList.add('active');
        
        document.querySelectorAll('.ai-nav-item').forEach(el => el.classList.remove('active'));
        btn.classList.add('active');
    }

    function loadChat(name) {
        document.getElementById('chat-name').innerText = name;
        const chatBox = document.getElementById('chat-box');
        chatBox.innerHTML = `
            <div class="bubble bubble-in">Hola, soy ${name}. Quería preguntar sobre mis puntos de fidelidad.</div>
            <div class="bubble bubble-out">¡Claro! Deja que consulto tu saldo actual en el sistema...</div>
            <div style="text-align: center; margin: 10px 0;"><span style="font-size: 10px; color: #10a37f; font-weight: 700;">IA RESPONDIENDO...</span></div>
        `;
    }

    function openCreator() {
        document.getElementById('creator-modal').style.display = 'flex';
    }

    function closeCreator() {
        document.getElementById('creator-modal').style.display = 'none';
    }
</script>

@endsection
