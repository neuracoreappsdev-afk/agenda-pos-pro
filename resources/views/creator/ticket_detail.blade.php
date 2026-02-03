@extends('creator.layout')

@section('content')
<div class="animate-fade">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2rem;">
        <div>
            <div class="badge-premium" style="margin-bottom:0.5rem;">Soporte Técnico Especializado</div>
            <h1 style="font-size:2.5rem; font-weight:800; margin:0; letter-spacing:-1px;">Ticket #{{ $ticket->id }}</h1>
            <p style="color:#94a3b8; margin-top:5px;">Sujeto: {{ $ticket->subject }} · Empresa: {{ $ticket->business ? $ticket->business->name : 'N/A' }}</p>
        </div>
        <button class="btn-creator" onclick="window.location.href='{{ url('creator/support') }}'" style="background:rgba(255,255,255,0.1); color:white; border:1px solid var(--glass-border);">
            <i data-lucide="arrow-left" style="vertical-align:middle; margin-right:8px;"></i> Volver
        </button>
    </div>

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem;">
        <!-- Chat Area -->
        <div class="glass-card" style="display:flex; flex-direction:column; min-height:600px; padding:0;">
            <div style="padding:1.5rem; border-bottom:1px solid var(--glass-border);">
                <h4 style="margin:0; display:flex; align-items:center; gap:10px;">
                    <i data-lucide="message-square"></i> Hilo de Conversación
                </h4>
            </div>
            
            <div style="flex:1; overflow-y:auto; padding:2rem; display:flex; flex-direction:column; gap:1.5rem;">
                <!-- Initial Description -->
                <div style="background:rgba(255,255,255,0.03); border-radius:16px; padding:1.5rem; border-left:4px solid var(--accent-color);">
                    <div style="font-size:0.75rem; color:#64748b; margin-bottom:10px; font-weight:700;">DESCRIPCIÓN INICIAL DEL CLIENTE</div>
                    <div style="line-height:1.6;">{{ $ticket->description }}</div>
                    <div style="font-size:0.7rem; color:#475569; margin-top:10px;">{{ $ticket->created_at->format('d M, Y H:i') }}</div>
                </div>

                @foreach($ticket->messages as $msg)
                <div style="display:flex; flex-direction:column; {{ $msg->sender_type == 'creator' ? 'align-items:flex-end' : 'align-items:flex-start' }}">
                    <div style="max-width:80%; background:{{ $msg->sender_type == 'creator' ? 'var(--primary-gradient)' : 'rgba(255,255,255,0.05)' }}; border-radius:18px; padding:1rem 1.5rem; color:{{ $msg->sender_type == 'creator' ? 'white' : '#e2e8f0' }}; border:1px solid var(--glass-border);">
                        {{ $msg->message }}
                    </div>
                    <div style="font-size:0.65rem; color:#64748b; margin-top:5px; padding:0 10px;">{{ $msg->created_at->diffForHumans() }}</div>
                </div>
                @endforeach
            </div>

            <!-- Input Area -->
            <div style="padding:1.5rem; border-top:1px solid var(--glass-border); background:rgba(0,0,0,0.2);">
                <form action="#" method="POST" style="display:flex; gap:15px;">
                    <textarea class="glass-card" placeholder="Escriba su respuesta oficial de soporte..." style="flex:1; background:rgba(255,255,255,0.05); color:white; border-radius:12px; padding:12px; height:60px; resize:none;"></textarea>
                    <button type="submit" class="btn-creator" style="width:120px;">ENVIAR</button>
                </form>
                <div style="display:flex; gap:15px; margin-top:10px;">
                    <button style="background:none; border:none; color:#64748b; cursor:pointer; font-size:0.8rem; display:flex; align-items:center; gap:5px;"><i data-lucide="paperclip" style="width:14px;"></i> Adjuntar Archivo</button>
                    <button style="background:none; border:none; color:#64748b; cursor:pointer; font-size:0.8rem; display:flex; align-items:center; gap:5px;"><i data-lucide="check-circle" style="width:14px;"></i> Marcar como Resuelto</button>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">
            <div class="glass-card">
                <h4 style="margin-top:0; border-bottom:1px solid var(--glass-border); padding-bottom:10px; margin-bottom:15px;">Detalles Técnicos</h4>
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.7rem; color:#64748b; font-weight:700;">ESTADO ACTUAL</div>
                    <div style="display:flex; align-items:center; gap:8px; margin-top:5px;">
                        <span style="width:10px; height:10px; background:#f43f5e; border-radius:50%;"></span>
                        <span style="font-weight:700;">{{ strtoupper($ticket->status) }}</span>
                    </div>
                </div>
                <div style="margin-bottom:1rem;">
                    <div style="font-size:0.7rem; color:#64748b; font-weight:700;">PRIORIDAD</div>
                    <div style="margin-top:5px; color:#f43f5e; font-weight:700;">{{ strtoupper($ticket->priority) }}</div>
                </div>
                <div style="margin-bottom:0;">
                    <div style="font-size:0.7rem; color:#64748b; font-weight:700;">INQUILINO AFECTADO</div>
                    <div style="margin-top:5px; font-weight:700;">{{ $ticket->business ? $ticket->business->name : 'N/A' }}</div>
                    <div style="font-size:0.75rem; color:#94a3b8;">{{ $ticket->business ? $ticket->business->email : '' }}</div>
                </div>
            </div>

            <div class="glass-card" style="background:rgba(244, 114, 182, 0.05); border-color:rgba(244, 114, 182, 0.2);">
                <h4 style="margin-top:0; color:var(--accent-color);">Acceso Maestro</h4>
                <p style="font-size:0.8rem; color:#94a3b8;">Como Creador, puedes suplantar la identidad para ver el error en vivo.</p>
                <button class="btn-creator" style="width:100%; border:1px solid var(--accent-color); background:none; color:var(--accent-color);">Entrar como Administrador</button>
            </div>
        </div>
    </div>
</div>
@endsection
