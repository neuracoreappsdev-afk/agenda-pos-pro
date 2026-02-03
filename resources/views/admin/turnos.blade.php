@extends('admin.layout')

@section('title', 'Turnos Manicura')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<style>
:root { --accent: #6366f1; }
.turnos-wrapper { background: #f5f5f7; font-family: system-ui, sans-serif; }
.card-inactiva { filter: grayscale(1); opacity: 0.6; }
.panel-soft { background: #fff; border-radius: 1.25rem; border: 1px solid #e5e7eb; box-shadow: 0 8px 22px rgba(148,163,184,0.26); }
.app-header, .app-footer { display: none !important; }

/* Modal personalizado */
.modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center; z-index: 9999;
}
.modal-overlay.active { display: flex; }
.modal-box {
    background: white; border-radius: 1rem; padding: 24px;
    max-width: 400px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    animation: modalIn 0.2s ease-out;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-title { font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 12px; }
.modal-message { font-size: 14px; color: #6b7280; margin-bottom: 20px; line-height: 1.5; }
.modal-buttons { display: flex; gap: 10px; justify-content: flex-end; }
.modal-btn {
    padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600;
    cursor: pointer; border: none; transition: all 0.2s;
}
.modal-btn-cancel { background: #f3f4f6; color: #374151; }
.modal-btn-cancel:hover { background: #e5e7eb; }
.modal-btn-confirm { background: #1a73e8; color: white; }
.modal-btn-confirm:hover { background: #1557b0; }
.modal-btn-danger { background: #ef4444; color: white; }
.modal-btn-danger:hover { background: #dc2626; }
</style>
@endpush

@section('content')
<div class="turnos-wrapper min-h-screen flex flex-col justify-center">
  <div class="w-full max-w-5xl mx-auto p-4 flex flex-col gap-4">
    <header class="flex items-center justify-between px-2">
      <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900 flex items-center gap-3">
        Turnos Manicura
        <span class="text-[10px] uppercase tracking-widest bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full font-bold">Orden de llegada</span>
      </h1>
      <div class="flex items-center gap-2">
        <button id="btnResetDia" onclick="resetearDia()" class="px-3 py-2 text-xs font-semibold rounded-lg border border-amber-300 bg-amber-50 text-amber-700 hover:bg-amber-100 transition-all">
          Nuevo Día
        </button>
        <a href="{{ url('admin/dashboard') }}" class="px-4 py-2 text-xs font-semibold rounded-lg border border-slate-200 hover:border-red-400 hover:text-red-600 hover:bg-red-50 transition-all flex items-center gap-1.5 bg-white">
          Salir
        </a>
      </div>
    </header>
    <main>
      <div id="gridColaboradoras" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
    </main>
  </div>
</div>

<!-- Modal personalizado -->
<div id="modalOverlay" class="modal-overlay">
  <div class="modal-box">
    <div id="modalTitle" class="modal-title"></div>
    <div id="modalMessage" class="modal-message"></div>
    <div class="modal-buttons">
      <button id="modalBtnCancel" class="modal-btn modal-btn-cancel" style="display:none;">Cancelar</button>
      <button id="modalBtnConfirm" class="modal-btn modal-btn-confirm">Aceptar</button>
    </div>
  </div>
</div>

<div id="dataManicuristas" style="display:none;">
@if(isset($manicuristas))
@foreach($manicuristas as $m)
<span data-id="{{ $m->id }}" data-name="{{ e($m->name) }}"></span>
@endforeach
@endif
</div>
<div id="dataServicios" style="display:none;">
@if(isset($servicios))
@foreach($servicios as $s)
<span data-id="{{ $s->id }}" data-name="{{ e($s->package_name) }}"></span>
@endforeach
@endif
</div>
<div id="dataCitas" style="display:none;">
@if(isset($citasHoy))
@foreach($citasHoy as $c)
<span data-specialist="{{ $c->specialist_id }}" data-start="{{ $c->start_time }}" data-end="{{ $c->end_time }}" data-status="{{ $c->status }}"></span>
@endforeach
@endif
</div>
<div id="dataFechaHoy" style="display:none;">{{ $fechaHoy ?? date('Y-m-d') }}</div>
@endsection

@push('scripts')
<script>
(function() {
    // === MODAL PERSONALIZADO ===
    var modalCallback = null;
    
    function mostrarModal(titulo, mensaje, esConfirmacion, callback) {
        var overlay = document.getElementById('modalOverlay');
        var titleEl = document.getElementById('modalTitle');
        var msgEl = document.getElementById('modalMessage');
        var btnCancel = document.getElementById('modalBtnCancel');
        var btnConfirm = document.getElementById('modalBtnConfirm');
        
        titleEl.textContent = titulo;
        msgEl.textContent = mensaje;
        
        if (esConfirmacion) {
            btnCancel.style.display = 'block';
            btnConfirm.textContent = 'Confirmar';
        } else {
            btnCancel.style.display = 'none';
            btnConfirm.textContent = 'Aceptar';
        }
        
        modalCallback = callback || null;
        overlay.classList.add('active');
    }
    
    function cerrarModal(resultado) {
        var overlay = document.getElementById('modalOverlay');
        overlay.classList.remove('active');
        if (modalCallback) {
            modalCallback(resultado);
            modalCallback = null;
        }
    }
    
    // Listeners para botones del modal
    document.getElementById('modalBtnConfirm').onclick = function() { cerrarModal(true); };
    document.getElementById('modalBtnCancel').onclick = function() { cerrarModal(false); };
    document.getElementById('modalOverlay').onclick = function(e) {
        if (e.target === this) cerrarModal(false);
    };
    
    function mostrarAlerta(titulo, mensaje) {
        mostrarModal(titulo, mensaje, false, null);
    }
    
    function mostrarConfirmacion(titulo, mensaje, callback) {
        mostrarModal(titulo, mensaje, true, callback);
    }

    var dbColaboradoras = [];
    var els = document.querySelectorAll('#dataManicuristas span');
    for (var i = 0; i < els.length; i++) {
        dbColaboradoras.push({
            id: parseInt(els[i].getAttribute('data-id')),
            nombre: els[i].getAttribute('data-name') || 'Sin nombre'
        });
    }

    var listaServicios = [];
    var sels = document.querySelectorAll('#dataServicios span');
    for (var j = 0; j < sels.length; j++) {
        listaServicios.push({
            id: parseInt(sels[j].getAttribute('data-id')),
            nombre: sels[j].getAttribute('data-name') || 'Servicio'
        });
    }

    // Leer citas de hoy
    var citasHoy = [];
    var citaEls = document.querySelectorAll('#dataCitas span');
    for (var k = 0; k < citaEls.length; k++) {
        var startStr = citaEls[k].getAttribute('data-start');
        var endStr = citaEls[k].getAttribute('data-end');
        if (startStr && endStr) {
            citasHoy.push({
                specialistId: parseInt(citaEls[k].getAttribute('data-specialist')),
                start: new Date(startStr).getTime(),
                end: new Date(endStr).getTime(),
                status: citaEls[k].getAttribute('data-status') || ''
            });
        }
    }

    // Funcion para obtener estado de cita de una manicurista
    function getEstadoCita(colabId) {
        var now = Date.now();
        var ALERTA_MINS = 15 * 60 * 1000; // 15 minutos antes
        
        for (var i = 0; i < citasHoy.length; i++) {
            var cita = citasHoy[i];
            if (cita.specialistId !== colabId) continue;
            if (cita.status === 'cancelled' || cita.status === 'no_show') continue;
            
            // Actualmente EN cita
            if (now >= cita.start && now <= cita.end) {
                return { estado: 'ocupada', texto: 'EN CITA', clase: 'bg-rose-500/90' };
            }
            
            // Cita proxima en menos de 15 min
            if (cita.start > now && (cita.start - now) <= ALERTA_MINS) {
                var mins = Math.ceil((cita.start - now) / 60000);
                return { estado: 'proxima', texto: 'CITA EN ' + mins + ' MIN', clase: 'bg-amber-500/90' };
            }
        }
        
        return { estado: 'libre', texto: 'DISPONIBLE', clase: 'bg-emerald-500/90' };
    }

    var fechaHoy = document.getElementById('dataFechaHoy').textContent.trim();
    var STORAGE_KEY = 'turnosState_AUTO_V2';

    var state = { colaboradoras: [], servicios: listaServicios, fechaGuardada: null };

    function cargarEstado() {
        var saved = null;
        try { saved = JSON.parse(localStorage.getItem(STORAGE_KEY)); } catch(e) {}

        if (saved && saved.fechaGuardada !== fechaHoy) {
            saved = null;
        }

        if (saved && saved.colaboradoras && saved.colaboradoras.length > 0) {
            state.colaboradoras = dbColaboradoras.map(function(db, idx) {
                var found = null;
                for (var k = 0; k < saved.colaboradoras.length; k++) {
                    if (saved.colaboradoras[k].id === db.id) { found = saved.colaboradoras[k]; break; }
                }
                return found ? {
                    id: db.id, nombre: db.nombre, foto: found.foto, activa: found.activa !== false,
                    orden: found.orden || idx, servicioId: found.servicioId, enCurso: found.enCurso
                } : { id: db.id, nombre: db.nombre, foto: null, activa: true, orden: idx, servicioId: null, enCurso: null };
            });
            state.colaboradoras.sort(function(a, b) { return a.orden - b.orden; });
            state.fechaGuardada = saved.fechaGuardada;
        } else {
            state.colaboradoras = dbColaboradoras.map(function(c, i) {
                return { id: c.id, nombre: c.nombre, foto: null, activa: true, orden: i, servicioId: null, enCurso: null };
            });
            state.fechaGuardada = fechaHoy;
        }

        if (state.colaboradoras.length === 0 && dbColaboradoras.length > 0) {
            state.colaboradoras = dbColaboradoras.map(function(c, i) {
                return { id: c.id, nombre: c.nombre, foto: null, activa: true, orden: i, servicioId: null, enCurso: null };
            });
        }

        renderGrid();
    }

    function guardarEstado() {
        state.fechaGuardada = fechaHoy;
        try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch(e) {}
    }

    window.resetearDia = function() {
        mostrarConfirmacion(
            'Reiniciar Orden del Día',
            '¿Está seguro que desea reiniciar el orden de llegada? Esta acción no se puede deshacer.',
            function(confirmado) {
                if (confirmado) {
                    state.colaboradoras = dbColaboradoras.map(function(c, i) {
                        return { id: c.id, nombre: c.nombre, foto: null, activa: true, orden: i, servicioId: null, enCurso: null };
                    });
                    state.fechaGuardada = fechaHoy;
                    guardarEstado();
                    renderGrid();
                }
            }
        );
    };

    function renderGrid() {
        var grid = document.getElementById('gridColaboradoras');
        grid.innerHTML = '';
        for (var i = 0; i < state.colaboradoras.length; i++) {
            state.colaboradoras[i].orden = i;
            grid.appendChild(crearTarjeta(state.colaboradoras[i], i));
        }
        iniciarTimer();
    }

    function crearTarjeta(colab, idx) {
        var card = document.createElement('div');
        card.className = 'panel-soft relative flex flex-row overflow-hidden shadow-lg bg-white group' + (colab.activa ? '' : ' card-inactiva');
        card.style.aspectRatio = '1 / 1';
        card.setAttribute('draggable', 'true');
        card.dataset.id = colab.id;
        card.ondragstart = handleDragStart;
        card.ondragover = handleDragOver;
        card.ondrop = handleDrop;

        var photo = document.createElement('div');
        photo.className = 'relative h-full bg-slate-100 shrink-0 cursor-pointer overflow-hidden';
        photo.style.aspectRatio = '9/16';

        var img = document.createElement('img');
        img.className = 'w-full h-full object-cover';
        if (colab.foto) { img.src = colab.foto; } else { img.style.display = 'none'; }

        var placeholder = document.createElement('div');
        placeholder.className = 'absolute inset-0 flex flex-col items-center justify-center text-slate-400';
        placeholder.innerHTML = '<svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
        if (colab.foto) placeholder.style.display = 'none';

        var badge = document.createElement('div');
        badge.className = 'absolute top-2 left-2 w-10 h-10 rounded-lg bg-white/90 shadow flex items-center justify-center text-xl font-black text-slate-800 border border-slate-200 z-10';
        badge.textContent = (idx + 1);

        // Badge de estado - ABAJO de la foto (dinamico segun agenda)
        var estadoCita = getEstadoCita(colab.id);
        var badgeEstado = document.createElement('div');
        badgeEstado.className = 'absolute bottom-2 left-2 right-2 ' + estadoCita.clase + ' backdrop-blur text-white text-[9px] font-bold uppercase text-center py-1.5 rounded-lg z-10';
        badgeEstado.textContent = estadoCita.texto;

        var fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.style.display = 'none';
        fileInput.onchange = function(e) { subirFoto(e, colab.id); };
        photo.onclick = function() { fileInput.click(); };

        photo.appendChild(img);
        photo.appendChild(placeholder);
        photo.appendChild(badge);
        photo.appendChild(badgeEstado);
        photo.appendChild(fileInput);
        card.appendChild(photo);

        var content = document.createElement('div');
        content.className = 'flex-1 flex flex-col justify-between p-3';

        var nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.value = colab.nombre;
        nameInput.className = 'w-full text-base font-bold text-slate-900 bg-transparent border-b border-transparent hover:border-slate-300 focus:border-indigo-500 outline-none truncate';
        nameInput.onchange = function() { colab.nombre = this.value || colab.nombre; guardarEstado(); };

        var statusRow = document.createElement('div');
        statusRow.className = 'flex items-center justify-between my-1';
        var statusLbl = document.createElement('span');
        statusLbl.className = 'text-[9px] font-bold text-slate-400 uppercase';
        statusLbl.textContent = 'Estado';
        var toggleBtn = document.createElement('button');
        toggleBtn.className = 'w-9 h-5 rounded-full relative ' + (colab.activa ? 'bg-emerald-500' : 'bg-slate-300');
        toggleBtn.innerHTML = '<span class="absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-all ' + (colab.activa ? 'left-4' : 'left-0.5') + '"></span>';
        toggleBtn.onclick = function() { colab.activa = !colab.activa; guardarEstado(); renderGrid(); };
        statusRow.appendChild(statusLbl);
        statusRow.appendChild(toggleBtn);

        var select = document.createElement('select');
        select.className = 'w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 outline-none';
        var optDefault = document.createElement('option');
        optDefault.value = '';
        optDefault.textContent = 'Seleccionar...';
        select.appendChild(optDefault);
        for (var s = 0; s < state.servicios.length; s++) {
            var opt = document.createElement('option');
            opt.value = state.servicios[s].id;
            var sn = state.servicios[s].nombre;
            opt.textContent = sn.length > 20 ? sn.substring(0, 20) + '...' : sn;
            if (colab.servicioId == state.servicios[s].id) opt.selected = true;
            select.appendChild(opt);
        }
        select.onchange = function() { colab.servicioId = this.value || null; guardarEstado(); };

        var timer = document.createElement('div');
        timer.id = 'timer-' + colab.id;
        timer.className = 'text-center font-mono text-xl font-bold text-slate-700 my-1';
        timer.textContent = colab.enCurso ? formatTime(Date.now() - colab.enCurso) : '--:--';

        var btn = document.createElement('button');
        btn.className = 'w-full py-2 rounded-lg text-xs font-black uppercase ' + (colab.enCurso ? 'bg-rose-100 text-rose-600 border border-rose-200' : 'bg-slate-900 text-white');
        btn.textContent = colab.enCurso ? 'Finalizar' : 'Iniciar';
        btn.onclick = function() {
            if (colab.enCurso) {
                mostrarConfirmacion(
                    'Finalizar Turno',
                    '¿Está seguro que desea finalizar el turno de ' + colab.nombre + '?',
                    function(confirmado) {
                        if (confirmado) {
                            colab.enCurso = null;
                            colab.servicioId = null;
                            colab.activa = true;
                            guardarEstado();
                            renderGrid();
                        }
                    }
                );
            } else {
                if (!colab.servicioId) {
                    mostrarAlerta('Servicio Requerido', 'Por favor seleccione un servicio antes de iniciar el turno.');
                    return;
                }
                colab.enCurso = Date.now();
                guardarEstado();
                renderGrid();
            }
        };

        content.appendChild(nameInput);
        content.appendChild(statusRow);
        content.appendChild(select);
        content.appendChild(timer);
        content.appendChild(btn);
        card.appendChild(content);
        return card;
    }

    function subirFoto(e, id) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            for (var i = 0; i < state.colaboradoras.length; i++) {
                if (state.colaboradoras[i].id === id) { state.colaboradoras[i].foto = ev.target.result; break; }
            }
            guardarEstado();
            renderGrid();
        };
        reader.readAsDataURL(file);
    }

    var dragSrc = null;
    function handleDragStart(e) { dragSrc = this; this.style.opacity = '0.5'; e.dataTransfer.effectAllowed = 'move'; }
    function handleDragOver(e) { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; }
    function handleDrop(e) {
        e.stopPropagation();
        if (dragSrc) dragSrc.style.opacity = '1';
        if (dragSrc !== this) {
            var srcId = parseInt(dragSrc.dataset.id);
            var destId = parseInt(this.dataset.id);
            var srcIdx = -1, destIdx = -1;
            for (var i = 0; i < state.colaboradoras.length; i++) {
                if (state.colaboradoras[i].id === srcId) srcIdx = i;
                if (state.colaboradoras[i].id === destId) destIdx = i;
            }
            if (srcIdx >= 0 && destIdx >= 0) {
                var item = state.colaboradoras.splice(srcIdx, 1)[0];
                state.colaboradoras.splice(destIdx, 0, item);
                for (var j = 0; j < state.colaboradoras.length; j++) state.colaboradoras[j].orden = j;
                guardarEstado();
                renderGrid();
            }
        }
        return false;
    }

    var timerInterval = null;
    var refreshInterval = null;
    function iniciarTimer() {
        if (timerInterval) clearInterval(timerInterval);
        if (refreshInterval) clearInterval(refreshInterval);
        
        // Timer para cronometros cada segundo
        timerInterval = setInterval(function() {
            for (var i = 0; i < state.colaboradoras.length; i++) {
                var c = state.colaboradoras[i];
                if (c.enCurso) {
                    var el = document.getElementById('timer-' + c.id);
                    if (el) el.textContent = formatTime(Date.now() - c.enCurso);
                }
            }
        }, 1000);
        
        // Refrescar estados de cita cada 30 segundos
        refreshInterval = setInterval(function() {
            renderGrid();
        }, 30000);
    }

    function formatTime(ms) {
        var s = Math.floor(ms / 1000) % 60;
        var m = Math.floor(ms / 60000) % 60;
        var h = Math.floor(ms / 3600000);
        return (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
    }

    document.addEventListener('DOMContentLoaded', cargarEstado);
})();
</script>
@endpush
