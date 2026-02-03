@extends('admin/dashboard_layout')

@section('content')
<div class="welcome-section">
    <h1 class="welcome-title">Chats con el Staff</h1>
    <p class="welcome-subtitle">Comunícate individualmente con tus especialistas</p>
</div>

<!-- Enviar Novedad (Broadcast) -->
<div class="news-card" style="border-left: 4px solid #3b82f6; margin-bottom: 30px;">
    <div class="news-header">
        <span class="news-icon">📢</span>
        <h3 class="news-title">Enviar Novedad a todo el Staff</h3>
    </div>
    <div style="margin-top:15px;">
        <input type="text" id="newsTitle" class="form-control" placeholder="Título de la novedad" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; margin-bottom:10px;">
        <textarea id="newsContent" class="form-control" placeholder="Contenido del mensaje..." style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; height:80px;"></textarea>
        <button onclick="sendNews()" class="quick-access-item" style="display:inline-block; margin-top:10px; padding:10px 20px; background:#1a73e8; color:white; border:none; border-radius:8px; cursor:pointer;">
            🚀 Enviar a todos
        </button>
    </div>
</div>

<!-- Listado de Chats -->
<div class="quick-access-section">
    <h2 class="section-title">Conversaciones Activas</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
        @foreach($specialists as $sp)
            <div class="chat-list-card" onclick="window.location.href='{{ url('admin/chats/'.$sp->id) }}'" style="background:white; border:1px solid #e5e7eb; border-radius:12px; padding:20px; cursor:pointer; transition:all 0.2s; position:relative;">
                @if($sp->unread_count > 0)
                    <div style="position:absolute; top:15px; right:15px; background:#ef4444; color:white; font-size:12px; font-weight:700; width:22px; height:22px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        {{ $sp->unread_count }}
                    </div>
                @endif
                <div style="display:flex; align-items:center; gap:15px;">
                    <div style="width:50px; height:50px; background:#1a73e8; color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:18px;">
                        {{ strtoupper(substr($sp->name, 0, 1)) }}
                    </div>
                    <div style="flex:1;">
                        <h4 style="margin:0; font-size:16px;">{{ $sp->name }}</h4>
                        <div style="font-size:13px; color:#6b7280; margin-top:4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:200px;">
                            @if($sp->last_message)
                                {{ $sp->last_message->sender_type == 'admin' ? 'Tú: ' : '' }}
                                {{ $sp->last_message->message_type == 'text' ? $sp->last_message->message : '[' . ucfirst($sp->last_message->message_type) . ']' }}
                            @else
                                Sin mensajes aún
                            @endif
                        </div>
                    </div>
                    <div style="font-size:11px; color:#9ca3af;">
                        {{ $sp->last_message ? $sp->last_message->created_at->diffForHumans() : '' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .chat-list-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        border-color: #3b82f6;
    }
</style>

<script>
    function sendNews() {
        const title = document.getElementById('newsTitle').value;
        const content = document.getElementById('newsContent').value;
        
        if(!title || !content) {
            showToast('Por favor completa todos los campos', 'error');
            return;
        }

        fetch("{{ url('admin/send-news') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ title, content })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showToast('Novedad enviada correctamente a todo el staff');
                document.getElementById('newsTitle').value = '';
                document.getElementById('newsContent').value = '';
            }
        });
    }
</script>
@endsection
