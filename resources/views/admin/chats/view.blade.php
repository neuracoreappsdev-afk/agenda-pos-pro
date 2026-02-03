@extends('admin/dashboard_layout')

@section('content')
<style>
    :root {
        --chat-bg: #ffffff;
        --sidebar-bg: #f8fafc;
        --accent-color: #1a73e8;
        --text-main: #1e293b;
        --text-dim: #64748b;
        --border-color: #e2e8f0;
        --msg-me: #1a73e8;
        --msg-them: #f1f5f9;
        --text-msg-them: #1e293b;
    }

    /* Reset body background for this view */
    body { background: var(--chat-bg) !important; color: var(--text-main) !important; }

    .chat-pro-container {
        display: flex;
        height: 100vh;
        background: var(--chat-bg);
        overflow: hidden;
        color: var(--text-main);
        font-family: 'Inter', sans-serif;
    }

    /* Minimalist Logo */
    .pro-logo {
        width: 10px;
        height: 10px;
        background: #000;
        border-radius: 2px;
        margin-right: 12px;
    }

    /* Sidebar */
    .chat-sidebar {
        width: 260px;
        background: var(--sidebar-bg);
        border-right: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
    }

    .chat-sidebar-header {
        padding: 30px 24px;
        display: flex;
        align-items: center;
    }

    .chat-sidebar-header h3 {
        margin: 0;
        font-size: 13px;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 700;
        color: #000;
    }

    .chat-sidebar-menu {
        padding: 0 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .menu-tab {
        padding: 14px 16px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        position: relative;
    }

    .menu-tab:hover { background: #f1f5f9; }
    .menu-tab.active { background: white; border-color: #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

    /* New Message Indicator */
    .menu-tab.has-new::after {
        content: '';
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        background: var(--accent-color);
        border-radius: 50%;
    }

    .menu-info { display: flex; flex-direction: column; }
    .menu-info .title { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .menu-info .subtitle { font-size: 11px; color: var(--text-dim); }

    /* Main Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--chat-bg);
    }

    .room-section { display: none; flex-direction: column; height: 100%; }
    .room-section.active { display: flex; }

    .room-header {
        padding: 24px 30px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .room-header h2 { margin: 0; font-size: 16px; font-weight: 700; color: #000; }
    .room-header .status { font-size: 11px; color: #10b981; font-weight: 600; }

    /* Messages */
    .messages-container {
        flex: 1;
        padding: 30px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: #ffffff;
    }

    .message-wrapper { display: flex; flex-direction: column; max-width: 80%; }
    .message-wrapper.me { align-self: flex-end; }
    .message-wrapper.them { align-self: flex-start; }

    .sender-name {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-dim);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .message-bubble {
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }

    .me .message-bubble { background: var(--msg-me); color: white; border-bottom-right-radius: 2px; }
    .them .message-bubble { background: var(--msg-them); color: #1e293b; border-bottom-left-radius: 2px; }

    .message-bubble .time { display: block; font-size: 10px; margin-top: 6px; opacity: 0.7; text-align: right; }

    /* Input Area */
    .chat-input-area {
        padding: 20px 30px;
        background: white;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .input-wrapper { flex: 1; }
    .input-wrapper input {
        width: 100%;
        padding: 12px 20px;
        background: #f1f5f9;
        border: 1px solid transparent;
        border-radius: 10px;
        outline: none;
        font-size: 14px;
        color: var(--text-main);
        transition: all 0.2s;
    }
    .input-wrapper input:focus { background: white; border-color: #cbd5e1; }

    .action-btn { background: none; border: none; font-size: 20px; cursor: pointer; color: var(--text-dim); transition: color 0.2s; }
    .action-btn:hover { color: var(--text-main); }

    .send-btn { background: #000; color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 700; cursor: pointer; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; }

    /* Waiting Room Table */
    .waiting-table { width: 100%; border-collapse: collapse; }
    .waiting-table th { text-align: left; padding: 12px 20px; font-size: 11px; text-transform: uppercase; color: var(--text-dim); border-bottom: 1px solid var(--border-color); }
    .waiting-table td { padding: 16px 20px; font-size: 14px; border-bottom: 1px solid #f8fafc; color: var(--text-main); }
    .status-pill { padding: 4px 10px; border-radius: 20px; font-size: 10px; text-transform: uppercase; font-weight: 800; }
    .status-pill.active { background: #dcfce7; color: #15803d; }
    .status-pill.waiting { background: #f1f5f9; color: #64748b; }

</style>

<div class="chat-pro-container">
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="pro-logo"></div>
            <h3>Staff Hub</h3>
        </div>
        <div class="chat-sidebar-menu">
            <div id="tab-chat" class="menu-tab active" onclick="switchChatRoom('chat', this)">
                <span class="icon">👥</span>
                <div class="menu-info">
                    <span class="title">Chat Staff</span>
                    <span class="subtitle">Comunicación Interna</span>
                </div>
            </div>
            <div id="tab-waiting" class="menu-tab" onclick="switchChatRoom('waiting', this)">
                <span class="icon">⏳</span>
                <div class="menu-info">
                    <span class="title">Sala de Espera</span>
                    <span class="subtitle">Estado de la Sala</span>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-main">
        <div id="chatRoomSection" class="room-section active">
            <div class="room-header">
                <h2>Mensajes del Equipo</h2>
                <span class="status">● Sincronizado</span>
            </div>
            <div id="adminChatMessages" class="messages-container">
                @foreach($messages as $msg)
                    <div class="message-wrapper {{ $msg->sender_type == 'admin' ? 'me' : 'them' }}">
                        @if($msg->sender_type != 'admin')
                            <span class="sender-name">{{ $msg->sender_name ?? 'Staff' }}</span>
                        @endif
                        <div class="message-bubble">
                            @if($msg->message_type == 'image')
                                <img src="{{ url($msg->file_path) }}" style="max-width:100%; border-radius:8px; margin-bottom:10px; border: 1px solid #eee;">
                            @elseif($msg->message_type == 'audio')
                                <audio controls src="{{ url($msg->file_path) }}" style="max-width: 100%;"></audio>
                            @endif
                            @if($msg->message) <p style="margin:0;">{{ $msg->message }}</p> @endif
                            <span class="time">{{ $msg->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="chat-input-area">
                <input type="file" id="adminChatFile" style="display:none;" onchange="uploadImage()">
                <button class="action-btn" onclick="document.getElementById('adminChatFile').click()">📎</button>
                <div class="input-wrapper">
                    <input type="text" id="adminChatInput" placeholder="Escribe un mensaje..." onkeypress="if(event.key==='Enter') sendMessage()">
                </div>
                <button class="send-btn" onclick="sendMessage()">Enviar</button>
            </div>
        </div>

        <div id="waitingRoomSection" class="room-section">
            <div class="room-header">
                <h2>Sala de Espera</h2>
            </div>
            <div id="waitingContent" class="messages-container">
                <!-- Loaded via JS -->
            </div>
        </div>
    </div>
</div>

<script>
    let currentRoom = 'chat';

    function switchChatRoom(room, el) {
        currentRoom = room;
        document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.room-section').forEach(s => s.classList.remove('active'));
        el.classList.add('active');
        el.classList.remove('has-new');
        document.getElementById(room + 'RoomSection').classList.add('active');
        if(room === 'chat') scrollBottom();
        else if(room === 'waiting') loadWaiting();
    }

    function scrollBottom() {
        const container = document.getElementById('adminChatMessages');
        container.scrollTop = container.scrollHeight;
    }
    scrollBottom();

    function sendMessage() {
        const input = document.getElementById('adminChatInput');
        const text = input.value.trim();
        if(!text) return;
        const formData = new FormData();
        formData.append('message', text);
        formData.append('specialist_id', '0');
        formData.append('type', 'text');
        formData.append('_token', '{{ csrf_token() }}');
        
        // Optimistic UI could go here, but reload for now
        input.value = '';
        fetch("{{ url('admin/chats/send') }}", { method: 'POST', body: formData })
        .then(res => res.json()).then(data => { if(data.success) location.reload(); });
    }

    function uploadImage() {
        const file = document.getElementById('adminChatFile').files[0];
        if(!file) return;
        const formData = new FormData();
        formData.append('file', file);
        formData.append('specialist_id', '0');
        formData.append('type', 'image');
        formData.append('_token', '{{ csrf_token() }}');
        fetch("{{ url('admin/chats/send') }}", { method: 'POST', body: formData })
        .then(res => res.json()).then(data => { if(data.success) location.reload(); });
    }

    function loadWaiting() {
        const STORAGE_KEY = "turnosManicura_EmergencyReset_v1";
        const saved = localStorage.getItem(STORAGE_KEY);
        const container = document.getElementById('waitingContent');
        if(!saved) { container.innerHTML = '<p style="color:var(--text-dim); text-align:center; padding-top:40px;">No hay turnos activos en este momento.</p>'; return; }
        try {
            const state = JSON.parse(saved);
            const activeColabs = state.colaboradoras.filter(c => c.servicioEnCurso || c.activa);
            let html = '<table class="waiting-table"><thead><tr><th>Personal</th><th>Estado</th><th>Detalle</th></tr></thead><tbody>';
            activeColabs.forEach(c => {
                const inProgress = c.servicioEnCurso;
                html += `<tr>
                    <td style="font-weight:700;">${c.nombre}</td>
                    <td><span class="status-pill ${inProgress ? 'active' : 'waiting'}">${inProgress ? 'Atendiendo' : 'Libre'}</span></td>
                    <td style="color:var(--text-dim); font-size:12px;">${inProgress ? 'Ocupada con cliente' : 'En sala de espera'}</td>
                </tr>`;
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        } catch(e) { container.innerHTML = '<p>Error al cargar datos.</p>'; }
    }

    let lastCount = {{ count($messages) }};
    setInterval(() => {
        if(currentRoom !== 'chat') {
            fetch("{{ url('admin/chats/unread-count') }}")
            .then(res => res.json())
            .then(data => {
                if(data.total > 0) document.getElementById('tab-chat').classList.add('has-new');
            });
        }
    }, 15000);
</script>
@endsection
