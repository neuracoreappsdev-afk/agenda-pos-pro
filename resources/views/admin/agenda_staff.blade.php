@extends('admin/dashboard_layout')

@section('content')

<?php
    // --- MOCK DATA FOR AGENDA STAFF ---
    // Usamos los mismos datos mock para consistencia visual
    
    $specialists = [
        ['id' => 1, 'name' => 'CAROLINA LUNA', 'role' => 'ESTETICISTA', 'color' => '#d4a5a5', 'photo' => ''],
        ['id' => 2, 'name' => 'LILIANA GUTIERREZ', 'role' => 'ESTETICISTA', 'color' => '#d8b4e2', 'photo' => ''],
        ['id' => 3, 'name' => 'LINA LUCIO', 'role' => 'ESTETICISTA', 'color' => '#f2abad', 'photo' => ''],
        ['id' => 4, 'name' => 'PAOLA RAMIREZ', 'role' => 'ESTETICISTA', 'color' => '#f2abad', 'photo' => ''],
        ['id' => 5, 'name' => 'CARMEN TORRES', 'role' => 'ESTILISTA', 'color' => '#f08080', 'photo' => ''],
        ['id' => 6, 'name' => 'GLORIA HERMOSA', 'role' => 'ESTILISTA', 'color' => '#f08080', 'photo' => ''],
        ['id' => 7, 'name' => 'SANDRA VALENCIA', 'role' => 'ESTILISTA', 'color' => '#d4a5a5', 'photo' => ''],
    ];

    $bookings = [
        [
            'specialist_id' => 1, 
            'type' => 'appointment', 
            'client' => 'CONSUMIDOR FINAL', 
            'service' => 'EXTENSIONES DE PESTAÑAS', 
            'notes' => 'CON CAROLINA LUNA', 
            'start_time' => '17:00', 
            'end_time' => '19:40',
            'color' => '#c18282' 
        ],
        [
            'specialist_id' => 2, 
            'type' => 'appointment', 
            'client' => 'ELIANA GIL', 
            'service' => 'CEJAS EN HILO [SIN DISEÑO]', 
            'notes' => 'CON LILIANA GUTIERREZ', 
            'start_time' => '18:00', 
            'end_time' => '19:00',
            'color' => '#e685f2' 
        ],
        [
            'specialist_id' => 4, 
            'type' => 'appointment', 
            'client' => 'GISELA VASQUEZ', 
            'service' => 'DISEÑO DE CEJAS', 
            'notes' => '', 
            'start_time' => '17:00', 
            'end_time' => '17:40', 
            'color' => '#ef4444' 
        ]
    ];
?>

<style>
    /* Layout General */
    .agenda-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px);
        background: #fff;
        overflow: hidden;
    }

    /* Header Navigation */
    .agenda-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        border-bottom: 1px solid #f3f4f6;
        flex-shrink: 0;
    }

    .nav-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-white {
        background: white;
        border: 1px solid #d1d5db;
        color: #374151;
        padding: 6px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-white:hover { background: #f9fafb; }
    
    .date-main-display {
        font-weight: 600;
        color: #111827;
        margin: 0 15px;
        text-transform: capitalize;
        background: #f3f4f6;
        padding: 6px 20px;
        border-radius: 4px;
    }

    /* Specialists Carousel */
    .specialists-header {
        display: flex;
        align-items: center;
        padding: 10px 0 10px 60px;
        border-bottom: 1px solid #e5e7eb;
        overflow-x: auto;
        white-space: nowrap;
        flex-shrink: 0;
        position: relative;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 24px;
        color: #374151;
        cursor: pointer;
        z-index: 10;
        background: rgba(255,255,255,0.8);
        padding: 0 10px;
        height: 100%;
        display: flex;
        align-items: center;
    }
    .carousel-btn.left { left: 0; }
    .carousel-btn.right { right: 0; }

    .specialist-card {
        width: 140px;
        min-width: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0 5px;
        position: relative;
    }

    .specialist-photo-wrapper {
        width: 100%;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
    }

    .specialist-photo {
        width: 60px;
        height: 70px;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        padding: 2px;
        background: white;
    }
    
    .specialist-photo img { width: 100%; height: 100%; object-fit: cover; border-radius: 2px; }
    
    .sp-initials {
        width: 100%; height: 100%; background: #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        color: #6b7280; font-weight: bold; font-size: 20px;
    }

    .sp-name {
        font-size: 11px;
        font-weight: 800;
        color: #111827;
        text-transform: uppercase;
        margin-bottom: 2px;
        white-space: normal;
        line-height: 1.2;
        max-width: 120px;
    }

    .sp-role {
        font-size: 9px;
        color: #fff;
        background-color: #d4a5a5;
        padding: 2px 8px;
        border-radius: 10px;
        text-transform: uppercase;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Agenda Grid Body */
    .agenda-body {
        flex: 1;
        overflow-y: auto;
        overflow-x: auto;
        position: relative;
        display: flex;
    }

    /* Time Column (8am - 8pm) */
    .time-column {
        width: 60px;
        min-width: 60px;
        border-right: 1px solid #e5e7eb;
        background: #fff;
        position: sticky;
        left: 0;
        z-index: 20;
    }

    .time-label {
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: start;
        padding-right: 8px;
        padding-top: 0;
    }
    
    .time-text {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        transform: translateY(-8px);
        background: white;
    }

    /* Columns */
    .columns-container { display: flex; }

    .sp-column {
        width: 140px;
        min-width: 140px;
        border-right: 1px solid #f3f4f6;
        position: relative;
        background-color: white;
    }
    
    .unavailable-block {
        background: #6b7280;
        position: absolute;
        width: 100%;
        left: 0;
        z-index: 5;
        opacity: 0.8;
    }

    .grid-lines { position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; z-index: 1; }
    .grid-line-hour { height: 100px; border-bottom: 1px solid #e5e7eb; box-sizing: border-box; }

    /* Event Blocks */
    .event-block {
        position: absolute;
        width: 96%;
        left: 2%;
        padding: 5px;
        border-radius: 4px;
        font-size: 10px;
        z-index: 15;
        color: white;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    
    .client-name { font-weight: 800; text-transform: uppercase; display: flex; align-items: center; gap: 4px; }
    .service-desc { line-height: 1.2; margin-top: 2px; }

    /* Current Time Line */
    .current-time-line {
        position: absolute; width: 100%; height: 2px; background: #ef4444; z-index: 50; pointer-events: none; display: flex; align-items: center;
    }
    .current-time-dot { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; margin-left: -4px; }

</style>

<div class="agenda-wrapper">
    <div class="agenda-header">
        <div class="nav-group">
            <button class="btn-white">‹</button>
            <button class="btn-white">Hoy</button>
            <div class="date-main-display">miércoles 17 dic., 2025 (Agenda Staff)</div>
            <button class="btn-white">›</button>
        </div>
        <div class="nav-group">
            <!-- Same buttons -->
        </div>
    </div>

    <!-- Specialists Header -->
    <div class="specialists-header">
        <div class="carousel-btn left">‹</div>
        @foreach($specialists as $sp)
        <div class="specialist-card">
            <div class="specialist-photo-wrapper">
                <div class="specialist-photo">
                     <div class="sp-initials">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="#9ca3af"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>
                     </div>
                </div>
            </div>
            <div class="sp-name">{{ $sp['name'] }}</div>
            <span class="sp-role" style="background-color: {{ $sp['color'] }}">{{ $sp['role'] }}</span>
        </div>
        @endforeach
        <div class="carousel-btn right">›</div>
    </div>

    <!-- Grid Body 8AM - 8PM -->
    <div class="agenda-body">
        <div class="time-column">
             <?php 
                $start_hour = 8; 
                $end_hour = 20;   
                for($h = $start_hour; $h <= $end_hour; $h++):
                    $ampm = $h >= 12 ? 'p. m.' : 'a. m.';
                    $display_h = $h > 12 ? $h - 12 : $h;
             ?>
             <div class="time-label">
                 <span class="time-text">{{ $display_h }} {{ $ampm }}</span>
                 <span class="time-text" style="margin-top:40px;">{{ $display_h }}:30 {{ $ampm }}</span>
             </div>
             <?php endfor; ?>
        </div>

        <div class="columns-container">
            @foreach($specialists as $sp)
            <div class="sp-column">
                <div class="grid-lines">
                    @for($h = $start_hour; $h <= $end_hour; $h++)
                     <div class="grid-line-hour"></div> 
                    @endfor
                </div>

                @if($sp['id'] == 3 || $sp['id'] == 5 || $sp['id'] == 6)
                    <div class="unavailable-block" style="top:0; height:400px; background:#f3f4f6; z-index:2; opacity:0.5;"></div>
                @endif

                @foreach($bookings as $book)
                    @if($book['specialist_id'] == $sp['id'])
                        <?php
                            $base_min = $start_hour * 60;
                            $start_p = explode(':', $book['start_time']);
                            $start_m = ($start_p[0] * 60) + $start_p[1];
                            $diff = $start_m - $base_min;
                            $top = $diff * (100/60);
                            $end_p = explode(':', $book['end_time']);
                            $end_m = ($end_p[0] * 60) + $end_p[1];
                            $dur = $end_m - $start_m;
                            $height = $dur * (100/60);
                        ?>
                        <div class="event-block" style="top: {{ $top }}px; height: {{ $height }}px; background-color: {{ $book['color'] }};">
                            <div class="client-name">
                                <span style="font-size:12px;">👤</span> {{ $book['client'] }}
                            </div>
                            <div class="service-desc">{{ $book['service'] }}</div>
                             <div class="service-desc">{{ $book['start_time'] }} - {{ $book['end_time'] }}</div>
                        </div>
                    @endif
                @endforeach

                <!-- Linea Roja a las 19:30 (11.5h desde las 8am) -->
                <div class="current-time-line" style="top: 1150px;"> 
                    <div class="current-time-dot"></div>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
