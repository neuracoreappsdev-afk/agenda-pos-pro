<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cita - {{ $businessName }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0d0d0d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #1a1a1a;
            border-radius: 20px;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            border: 1px solid #2a2a2a;
        }
        .card-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 40px 32px;
            text-align: center;
        }
        .modify-icon {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.15);
            border-radius: 18px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            backdrop-filter: blur(10px);
        }
        .title { 
            color: white; 
            font-size: 24px; 
            font-weight: 700; 
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }
        .subtitle { 
            color: rgba(255,255,255,0.85); 
            font-size: 14px; 
        }
        .card-body { 
            padding: 32px; 
        }
        .section-title {
            font-size: 11px;
            color: #737373;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .current-info {
            background: #262626;
            border: 1px solid #404040;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 28px;
        }
        .current-label { 
            font-size: 11px; 
            color: #737373;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .current-value { 
            font-size: 15px; 
            font-weight: 600; 
            color: #e5e5e5;
            margin-top: 4px;
        }
        .form-group { 
            margin-bottom: 24px; 
        }
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            color: #a3a3a3;
            margin-bottom: 10px;
        }
        .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #404040;
            border-radius: 10px;
            font-size: 15px;
            background: #262626;
            color: #e5e5e5;
            cursor: pointer;
            transition: all 0.2s;
        }
        .form-select:focus {
            outline: none;
            border-color: #10a37f;
            box-shadow: 0 0 0 3px rgba(16, 163, 127, 0.15);
        }
        .time-slots {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
            padding: 4px;
        }
        .time-slots::-webkit-scrollbar {
            width: 6px;
        }
        .time-slots::-webkit-scrollbar-track {
            background: #262626;
            border-radius: 3px;
        }
        .time-slots::-webkit-scrollbar-thumb {
            background: #404040;
            border-radius: 3px;
        }
        .time-slot {
            padding: 10px 8px;
            border: 1px solid #404040;
            border-radius: 8px;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            color: #a3a3a3;
            background: #262626;
        }
        .time-slot:hover {
            border-color: #10a37f;
            background: rgba(16, 163, 127, 0.1);
            color: #10a37f;
        }
        .time-slot.selected {
            background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%);
            color: white;
            border-color: #10a37f;
        }
        .btn-row { 
            display: flex; 
            gap: 12px; 
            margin-top: 32px; 
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-cancel {
            background: #262626;
            color: #a3a3a3;
            border: 1px solid #404040;
        }
        .btn-cancel:hover {
            background: #333333;
        }
        .btn-save {
            background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%);
            color: white;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(16, 163, 127, 0.25);
        }
        .footer {
            padding: 20px;
            background: #141414;
            border-top: 1px solid #262626;
            font-size: 11px;
            color: #525252;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="modify-icon">✏️</div>
            <h1 class="title">Modificar Cita</h1>
            <p class="subtitle">Selecciona una nueva fecha y hora</p>
        </div>
        <div class="card-body">
            <!-- Cita actual -->
            <div class="section-title">Tu Cita Actual</div>
            <div class="current-info">
                <?php
                    $firstApt = $appointments->first();
                    $dateCarbon = \Carbon\Carbon::parse($firstApt->appointment_datetime);
                ?>
                <div class="current-label">Fecha y hora</div>
                <div class="current-value">{{ $dateCarbon->format('l, d M Y - h:i A') }}</div>
            </div>

            <!-- Formulario -->
            <form action="{{ url('cita/modificar/' . $token) }}" method="POST" id="modifyForm">
                {{ csrf_field() }}
                
                <div class="form-group">
                    <label class="form-label">Nueva Fecha</label>
                    <select class="form-select" id="dateSelect" name="date" onchange="updateTimeSlots()">
                        @foreach($availableSlots as $dateKey => $slot)
                        <option value="{{ $dateKey }}">{{ $slot['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nueva Hora</label>
                    <div class="time-slots" id="timeSlots">
                        @php $firstSlot = array_values($availableSlots)[0] ?? ['times' => []]; @endphp
                        @foreach($firstSlot['times'] as $time)
                        <div class="time-slot" onclick="selectTime(this, '{{ $time }}')">{{ $time }}</div>
                        @endforeach
                    </div>
                    <input type="hidden" name="time" id="selectedTime" required>
                </div>

                <div class="btn-row">
                    <a href="{{ url('cita/' . $token) }}" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ $businessName }}
        </div>
    </div>

    <script>
        const availableSlots = @json($availableSlots);

        function updateTimeSlots() {
            const date = document.getElementById('dateSelect').value;
            const container = document.getElementById('timeSlots');
            const slots = availableSlots[date]?.times || [];
            
            container.innerHTML = slots.map(time => 
                `<div class="time-slot" onclick="selectTime(this, '${time}')">${time}</div>`
            ).join('');
            
            document.getElementById('selectedTime').value = '';
        }

        function selectTime(el, time) {
            document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('selectedTime').value = time;
        }

        document.getElementById('modifyForm').addEventListener('submit', function(e) {
            if (!document.getElementById('selectedTime').value) {
                e.preventDefault();
                alert('Por favor selecciona una hora');
            }
        });
    </script>
</body>
</html>
