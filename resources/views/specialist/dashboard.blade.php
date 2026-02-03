<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#ffffff">
    <title>Panel Especialista | AgendaPOS PRO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #111827;
            --accent: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.15);
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-dark: #f9fafb;
            --bg-card: #ffffff;
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --text-muted: #9ca3af;
            --border: #e5e7eb;
            --shadow-premium: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
        body { background: var(--bg-dark); color: var(--text-primary); min-height: 100vh; padding-bottom: 90px; overflow-x: hidden; }

        header {
            background: #ffffff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border);
        }

        .logo { font-size: 16px; font-weight: 800; color: #000; display: flex; align-items: center; gap: 6px; }
        .logo span { color: var(--accent); }
        .logo-dot { width: 8px; height: 8px; background: var(--accent); border-radius: 2px; }

        .user-avatar {
            width: 32px; height: 32px; background: var(--accent); border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: white; cursor: pointer; font-size: 12px;
        }

        /* Filter & Specialist Selector */
        .header-controls {
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            position: sticky;
            top: 57px;
            z-index: 999;
        }

        .filter-form { width: 100%; }
        .filter-group { display: flex; align-items: center; gap: 10px; }
        .filter-input-wrapper { flex: 1; display: flex; flex-direction: column; gap: 4px; }
        .filter-input-wrapper label { font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
        .date-picker-input { 
            width: 100%; border: 1px solid var(--border); border-radius: 8px; padding: 6px 8px; 
            font-size: 13px; font-weight: 600; color: #000; outline: none; background: #f9fafb;
        }
        .btn-filter-submit {
            background: var(--accent); color: white; border: none; border-radius: 8px; 
            width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transform: translateY(8px);
        }

        /* Tabs */
        .tabs-container { padding: 10px 20px; background: #fff; border-bottom: 1px solid var(--border); position: sticky; top: 125px; z-index: 998; }
        .tabs-nav { display: flex; gap: 8px; background: #f3f4f6; border-radius: 12px; padding: 4px; }
        .tab-item {
            flex: 1; text-align: center; padding: 8px; font-weight: 600; font-size: 12px;
            color: var(--text-secondary); cursor: pointer; border-radius: 8px; transition: all 0.2s;
        }
        .tab-item.active { background: #fff; color: #000; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }

        .main-content { padding: 0; }
        .section { display: none; }
        .section.active { display: block; }

        /* Timeline Styles */
        .agenda-view { position: relative; background: #fff; }
        .timeline-container { display: flex; position: relative; }
        .time-scale { width: 60px; border-right: 1px solid #f3f4f6; background: #fafafa; }
        .time-hour { height: 80px; border-bottom: 1px solid #f3f4f6; color: var(--text-muted); font-size: 10px; font-weight: 600; text-align: center; padding-top: 5px; }
        
        .timeline-grid { flex: 1; position: relative; background: #fff; }
        .grid-hour { height: 80px; border-bottom: 1px solid #f3f4f6; position: relative; }
        .grid-half { position: absolute; top: 40px; left: 0; right: 0; border-top: 1px dashed #f9fafb; pointer-events: none; }

        .appointment-box {
            position: absolute; left: 4px; right: 4px; border-radius: 10px; padding: 8px;
            color: white; font-size: 11px; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden; display: flex; flex-direction: column; gap: 2px;
            border-left: 4px solid rgba(255,255,255,0.3);
            cursor: pointer;
        }
        .apt-box-client { font-weight: 800; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .apt-box-service { opacity: 0.9; font-size: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .apt-box-time { font-weight: 500; font-size: 9px; margin-top: auto; }

        /* Status Colors */
        .status-pendiente { background: linear-gradient(135deg, #fbbf24, #f59e0b); }
        .status-confirmada { background: linear-gradient(135deg, #10b981, #059669); }
        .status-iniciado { background: linear-gradient(135deg, #6366f1, #4f46e5); }
        .status-completada { background: linear-gradient(135deg, #9ca3af, #6b7280); }

        /* Stats */
        .stats-summary { display: flex; gap: 10px; padding: 15px 20px; background: #fff; border-bottom: 1px solid var(--border); }
        .stat-mini { flex: 1; background: #f8fafc; border-radius: 12px; padding: 10px; text-align: center; }
        .stat-mini-val { font-size: 16px; font-weight: 800; color: #000; }
        .stat-mini-lbl { font-size: 9px; color: var(--text-secondary); text-transform: uppercase; font-weight: 700; }

        /* Vouchers */
        .voucher-card { background: #fff; padding: 20px; }
        .voucher-item { padding: 15px; border: 1px solid var(--border); border-radius: 16px; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; }
        .voucher-info { flex: 1; }

        /* Modal Structure (Standard & Robust) */
        .modal {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 2000;
            display: none; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-content {
            background: #fff; width: 100%; max-width: 500px; border-radius: 20px;
            max-height: 90vh; display: flex; flex-direction: column;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .modal-header { padding: 20px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-size: 18px; font-weight: 800; }
        .modal-body { padding: 20px; overflow-y: auto; flex: 1; }
        .modal-footer { padding: 20px; border-top: 1px solid var(--border); display: flex; gap: 10px; }

        .btn { border: none; border-radius: 12px; padding: 12px 20px; font-weight: 700; cursor: pointer; font-size: 14px; text-align: center; flex: 1; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-success { background: var(--success); color: #fff; }
        .btn-ghost { background: #f3f4f6; color: var(--text-secondary); }

        /* Service Picker */
        .service-search-box { position: relative; margin-bottom: 15px; }
        .search-input { width: 100%; border: 2px solid var(--border); border-radius: 12px; padding: 12px; font-weight: 600; outline: none; }
        .search-results-list { 
            position: absolute; top: 100%; left: 0; right: 0; background: #fff; 
            border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-premium);
            z-index: 3000; max-height: 200px; overflow-y: auto; display: none; margin-top: 5px;
        }
        .search-item { padding: 12px; border-bottom: 1px solid #f3f4f6; cursor: pointer; display: flex; justify-content: space-between; }
        .search-item:last-child { border-bottom: none; }

        .selected-services-list { display: flex; flex-direction: column; gap: 10px; margin-top: 15px; }
        .service-tag { 
            background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 10px 15px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .remove-service { color: var(--danger); font-size: 18px; font-weight: 800; cursor: pointer; padding: 4px; }

        /* Arrival Alert Overlay */
        #arrivalAlert {
            position: fixed; top: 20px; left: 20px; right: 20px; background: #000; color: #fff; 
            padding: 20px; border-radius: 20px; z-index: 3000; box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            display: none; flex-direction: column; gap: 15px;
        }
        .current-time-marker { position: absolute; left: 0; right: 0; border-top: 2px solid var(--danger); z-index: 50; pointer-events: none; }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <div class="logo-dot"></div>
            Agenda<span>POS</span> PRO
        </div>
        <div class="user-avatar" onclick="window.location.href='{{ url('colaborador/logout') }}'">
            {{ strtoupper(substr($specialist->name, 0, 1)) }}
        </div>
    </header>

    <div class="header-controls">
        <form action="{{ url('colaborador/dashboard') }}" method="GET" class="filter-form">
            <div class="filter-group">
                <div class="filter-input-wrapper">
                    <label>Desde</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="date-picker-input">
                </div>
                <div class="filter-input-wrapper">
                    <label>Hasta</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="date-picker-input">
                </div>
                <button type="submit" class="btn-filter-submit">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
            </div>
        </form>
    </div>

    <div class="tabs-container">
        <div class="tabs-nav">
            <div class="tab-item active" onclick="switchTab('agenda', this)">Agenda</div>
            <div class="tab-item" onclick="switchTab('pagos', this)">Mis Comisiones</div>
            <div class="tab-item" onclick="switchTab('novedades', this)">Noticias</div>
        </div>
    </div>

    <div class="stats-summary" id="stats-agenda">
        <div class="stat-mini">
            <div class="stat-mini-val" id="count-total">0</div>
            <div class="stat-mini-lbl">Turnos</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" id="count-pend">0</div>
            <div class="stat-mini-lbl">Espera</div>
        </div>
        <div class="stat-mini">
            <div class="stat-mini-val" id="count-complet">0</div>
            <div class="stat-mini-lbl">Hechos</div>
        </div>
    </div>

    <div class="main-content">
        <!-- AGENDA SECTION -->
        <div id="agenda" class="section active">
            <div class="agenda-view">
                <div class="timeline-container">
                    <div class="time-scale">
                        @for($h = 7; $h <= 21; $h++)
                            <div class="time-hour">{{ $h > 12 ? ($h-12) : $h }}:00</div>
                        @endfor
                    </div>
                    <div class="timeline-grid" id="timelineGrid">
                        @for($h = 7; $h <= 21; $h++)
                            <div class="grid-hour"><div class="grid-half"></div></div>
                        @endfor
                        <div id="timeMarker" class="current-time-marker"></div>
                        <div id="aptPlaceHolder"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COMISIONES SECTION -->
        <div id="pagos" class="section">
            <div style="padding: 20px; background: #fff; border-bottom: 1px solid var(--border);">
                <div style="font-size: 10px; font-weight: 800; color: var(--text-muted);">TOTAL COMISIONES</div>
                <div style="font-size: 32px; font-weight: 800;">$ {{ number_format($totalComisiones, 0, ',', '.') }}</div>
            </div>
            <div class="voucher-card">
                @forelse($saleItems as $item)
                    <div class="voucher-item">
                        <div class="voucher-info">
                            <div style="font-size: 14px; font-weight: 700;">{{ $item->item_name }}</div>
                            <div style="font-size: 11px; color: var(--text-muted);">👤 {{ $item->sale ? $item->sale->customer_name : 'Cliente' }} | {{ date('d/m H:i', strtotime($item->created_at)) }}</div>
                        </div>
                        <div style="font-size: 16px; font-weight: 800; color: var(--success);">$ {{ number_format($item->commission_value, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; color: var(--text-muted);">Sin registros.</div>
                @endforelse
            </div>
        </div>

        <!-- NOVEDADES SECTION -->
        <div id="novedades" class="section" style="padding: 20px;">
            @foreach($news as $item)
                <div style="background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 15px; margin-bottom: 15px;">
                    <div style="font-size: 15px; font-weight: 700;">{{ $item->title }}</div>
                    <div style="font-size: 13px; color: var(--text-secondary); margin-top: 5px;">{{ $item->content }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- SERVICE TICKET MODAL -->
    <div id="ticketModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="m-client-name">Validar Servicios</div>
                <div style="cursor: pointer; font-size: 20px;" onclick="closeModal()">✕</div>
            </div>
            <div class="modal-body">
                <p style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Servicios en curso</p>
                <div id="selectedServicesList" class="selected-services-list"></div>
                
                <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border);">
                
                <p style="font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 10px;">Agregar más servicios</p>
                <div class="service-search-box">
                    <input type="text" class="search-input" id="searchServiceInput" placeholder="Escriba para buscar..." onkeyup="doSearch(this.value)">
                    <div id="searchResultsList" class="search-results-list"></div>
                </div>

                <div style="background: #f0fdf4; padding: 10px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 12px; font-weight: 700; color: #166534;">TIEMPO TOTAL:</span>
                    <span style="font-size: 14px; font-weight: 800; color: #166534;" id="m-total-time">0 min</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" onclick="closeModal()">CANCELAR</button>
                <button class="btn btn-primary" onclick="saveAptServices()">GUARDAR Y ACTUALIZAR</button>
            </div>
        </div>
    </div>

    <!-- ARRIVAL ALERT -->
    <div id="arrivalAlert">
        <div>
            <div id="a-alert-title" style="font-size: 18px; font-weight: 800; margin-bottom: 5px;"></div>
            <div id="a-alert-msg" style="font-size: 13px; color: #9ca3af;"></div>
        </div>
        <button onclick="acknowledgeAlert()" class="btn btn-primary">ACEPTAR</button>
    </div>

    <script>
        const allAppointments = {!! json_encode($appointments) !!};
        let currentApt = null;
        let activeServices = [];

        function switchTab(tabId, el) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            if(el) el.classList.add('active');
        }

        function renderAgenda() {
            const container = document.getElementById('aptPlaceHolder');
            container.innerHTML = '';
            
            document.getElementById('count-total').innerText = allAppointments.length;
            document.getElementById('count-pend').innerText = allAppointments.filter(a => a.status != 'completada').length;
            document.getElementById('count-complet').innerText = allAppointments.filter(a => a.status == 'completada').length;

            allAppointments.forEach(apt => {
                const dateTime = new Date(apt.appointment_datetime);
                const h = dateTime.getHours();
                const m = dateTime.getMinutes();
                if (h < 7 || h > 21) return;

                const top = (h - 7) * 80 + (m / 60) * 80;
                const height = (apt.duration / 60) * 80;

                const box = document.createElement('div');
                box.className = `appointment-box status-${apt.status}`;
                box.style.top = top + 'px';
                box.style.height = height + 'px';
                
                box.innerHTML = `
                    <div class="apt-box-client">${apt.customer_name}</div>
                    <div class="apt-box-service">${apt.service_name}</div>
                    <div class="apt-box-time">${apt.time} (${apt.duration}m)</div>
                `;

                box.onclick = () => handleAptClick(apt);
                container.appendChild(box);
            });
        }

        function handleAptClick(apt) {
            if (apt.status === 'completada') return;
            if (apt.status === 'iniciado') {
                showTicket(apt);
            } else if (confirm('¿Desea iniciar este turno?')) {
                fetch("{{ url('colaborador/iniciar-cita') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ appointment_id: apt.id })
                }).then(() => location.reload());
            }
        }

        function showTicket(apt) {
            currentApt = apt;
            document.getElementById('m-client-name').innerText = apt.customer_name;
            try {
                activeServices = typeof apt.additional_services === 'string' ? JSON.parse(apt.additional_services) : (apt.additional_services || []);
            } catch(e) { activeServices = []; }
            
            renderSelectedServices();
            document.getElementById('ticketModal').style.display = 'flex';
        }

        function renderSelectedServices() {
            const list = document.getElementById('selectedServicesList');
            list.innerHTML = '';
            let total = 0;
            activeServices.forEach((s, i) => {
                total += parseInt(s.duration);
                const div = document.createElement('div');
                div.className = 'service-tag';
                div.innerHTML = `
                    <div>
                        <div style="font-size:13px; font-weight:700;">${s.name}</div>
                        <div style="font-size:11px; color:var(--text-muted);">${s.duration} min</div>
                    </div>
                    <div class="remove-service" onclick="removeService(${i})">✕</div>
                `;
                list.appendChild(div);
            });
            document.getElementById('m-total-time').innerText = total + ' min';
        }

        function doSearch(q) {
            if (q.length < 2) { document.getElementById('searchResultsList').style.display = 'none'; return; }
            fetch("{{ url('colaborador/search-services') }}?q=" + q)
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById('searchResultsList');
                    list.innerHTML = data.map(s => `
                        <div class="search-item" onclick="addService(${s.id}, '${s.package_name}', ${s.package_time}, ${s.package_price})">
                            <span>${s.package_name}</span>
                            <span style="font-weight:800; color:var(--accent);">+</span>
                        </div>
                    `).join('');
                    list.style.display = 'block';
                });
        }

        function addService(id, name, duration, price) {
            activeServices.push({ id, name, duration, price });
            document.getElementById('searchServiceInput').value = '';
            document.getElementById('searchResultsList').style.display = 'none';
            renderSelectedServices();
        }

        function removeService(index) {
            activeServices.splice(index, 1);
            renderSelectedServices();
        }

        function closeModal() { document.getElementById('ticketModal').style.display = 'none'; }

        function saveAptServices() {
            fetch("{{ url('colaborador/update-appointment-services') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ appointment_id: currentApt.id, services: activeServices })
            }).then(() => location.reload());
        }

        // Notifications
        let activeAlertId = null;
        const notificationAudio = new (window.AudioContext || window.webkitAudioContext)();

        function playArrivalSound() {
            const osc = notificationAudio.createOscillator();
            const gain = notificationAudio.createGain();
            osc.connect(gain);
            gain.connect(notificationAudio.destination);
            osc.type = 'sine';
            osc.frequency.setValueAtTime(880, notificationAudio.currentTime);
            gain.gain.setValueAtTime(0, notificationAudio.currentTime);
            gain.gain.linearRampToValueAtTime(0.5, notificationAudio.currentTime + 0.1);
            gain.gain.linearRampToValueAtTime(0, notificationAudio.currentTime + 0.5);
            osc.start();
            osc.stop(notificationAudio.currentTime + 0.5);
            
            // Second tone for 'ping-pong' effect
            setTimeout(() => {
                const osc2 = notificationAudio.createOscillator();
                const gain2 = notificationAudio.createGain();
                osc2.connect(gain2);
                gain2.connect(notificationAudio.destination);
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(1046.50, notificationAudio.currentTime);
                gain2.gain.setValueAtTime(0, notificationAudio.currentTime);
                gain2.gain.linearRampToValueAtTime(0.5, notificationAudio.currentTime + 0.1);
                gain2.gain.linearRampToValueAtTime(0, notificationAudio.currentTime + 0.5);
                osc2.start();
                osc2.stop(notificationAudio.currentTime + 0.5);
            }, 200);
        }

        function checkNotifications() {
            fetch("{{ url('colaborador/check-notifications') }}").then(res => res.json()).then(data => {
                if (data.type !== 'none') {
                    activeAlertId = data.id;
                    document.getElementById('a-alert-title').innerText = data.title;
                    document.getElementById('a-alert-msg').innerText = data.message;
                    document.getElementById('arrivalAlert').style.display = 'flex';
                    
                    // Sonido y Vibración
                    playArrivalSound();
                    if ("vibrate" in navigator) {
                        navigator.vibrate([300, 100, 300, 100, 400]);
                    }
                }
            });
        }

        function acknowledgeAlert() {
            fetch("{{ url('colaborador/acknowledge-arrival') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ appointment_id: activeAlertId })
            }).then(() => {
                document.getElementById('arrivalAlert').style.display = 'none';
            });
        }

        function updateTimeMarker() {
            const marker = document.getElementById('timeMarker');
            const now = new Date();
            const h = now.getHours(), m = now.getMinutes();
            if (h >= 7 && h <= 21) {
                marker.style.top = ((h - 7) * 80 + (m / 60) * 80) + 'px';
                marker.style.display = 'block';
            } else marker.style.display = 'none';
        }

        renderAgenda();
        updateTimeMarker();
        setInterval(updateTimeMarker, 60000);
        setInterval(checkNotifications, 15000);
    </script>
</body>
</html>
