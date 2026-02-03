@extends('layout')

@section('content')

<style>
    /* Estilos específicos para esta vista */
    .page-container {
        max-width: 800px;
        margin: 0 auto;
        padding-bottom: 100px; /* Espacio para el footer fijo */
    }

    .header-title {
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        padding: 20px 0;
        position: relative;
    }

    .close-btn {
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
        font-size: 20px;
        text-decoration: none;
    }

    /* Date Scroller */
    .date-scroller-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 30px;
    }

    .nav-arrow {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        border-radius: 4px;
        color: #666;
        cursor: pointer;
    }

    .date-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 70px;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .date-card.active {
        background: #000;
        color: #fff;
    }

    .date-card.inactive {
        background: #fff;
        color: #999;
        border-color: #eee;
    }

    .date-day-name {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .date-day-number {
        font-size: 20px;
        font-weight: 700;
        line-height: 1;
    }

    .date-month {
        font-size: 10px;
        text-transform: uppercase;
        margin-top: 2px;
    }

    /* Time Columns */
    .time-columns-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 16px;
    }

    .time-column {
        background: #f9f9f9; /* Gris muy suave */
        border-radius: 8px;
        padding: 16px;
        min-height: 300px; /* Altura mínima para que se vean uniformes */
    }

    .column-header {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .column-icon {
        font-size: 16px;
        color: #666;
    }

    .time-btn {
        display: block;
        width: 100%;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
        padding: 10px 0;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        color: #333;
        margin-bottom: 10px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .time-btn:hover {
        border-color: #000;
        color: #000;
    }

    .empty-message {
        text-align: center;
        font-size: 12px;
        color: #999;
        margin-top: 40px;
        line-height: 1.5;
    }

    /* Footer */
    .fixed-footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        padding: 16px 24px;
        border-top: 1px solid #eee;
        z-index: 100;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }

    .btn-continue {
        background: #000;
        color: #fff;
        width: 100%;
        max-width: 600px;
        padding: 14px;
        border-radius: 8px;
        font-weight: 600;
        text-align: center;
        border: none;
        font-size: 16px;
        cursor: pointer;
    }

    .btn-secondary-action {
        background: transparent;
        border: none;
        color: #333;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    @media (max-width: 600px) {
        .time-columns-grid {
            grid-template-columns: 1fr; /* En móvil, una columna debajo de otra */
        }
        .time-column {
            min-height: auto;
        }
    }
</style>

<div class="page-container">

    {{-- HEADER --}}
    <div class="header-title">
        Selecciona Fecha y Hora
        <a href="{{ route('booking.index') }}" class="close-btn"><i class="fas fa-times"></i></a>
    </div>

    @if(isset($isHoliday) && $isHoliday)
    <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px;">
        <div style="font-size: 40px; margin-bottom: 10px;">🏘️</div>
        <h3 style="color: #991b1b; font-weight: 700; margin: 0 0 5px 0;">¡{{ $holidayName ?? 'Día no Laboral' }}!</h3>
        <p style="color: #b91c1c; font-size: 14px; margin: 0;">Lo sentimos, esta fecha es festiva o no laborable en nuestro calendario. Por favor selecciona otro día.</p>
    </div>
    @endif

    {{-- DATE SCROLLER --}}
    <div class="date-scroller-wrapper">
        <div class="nav-arrow"><i class="fas fa-chevron-left"></i></div>
        
        @for($i = 0; $i < 5; $i++)
            <?php
                $date = \Carbon\Carbon::now()->addDays($i);
                $isToday = $i === 0;
                $isSelected = $date->format('d/m/Y') == $selectedDateFormatted;
                
                // Traducción manual simple para días y meses
                $days = ['Sun' => 'DOM', 'Mon' => 'LUN', 'Tue' => 'MAR', 'Wed' => 'MIÉ', 'Thu' => 'JUE', 'Fri' => 'VIE', 'Sat' => 'SÁB'];
                $months = ['Jan' => 'ENE', 'Feb' => 'FEB', 'Mar' => 'MAR', 'Apr' => 'ABR', 'May' => 'MAY', 'Jun' => 'JUN', 'Jul' => 'JUL', 'Aug' => 'AGO', 'Sep' => 'SEP', 'Oct' => 'OCT', 'Nov' => 'NOV', 'Dec' => 'DIC'];
                
                $dayName = $days[$date->format('D')];
                $monthName = $months[$date->format('M')];
            ?>
            <a href="?date={{ $date->format('d/m/Y') }}{{ isset($specialist) ? '&specialist_id=' . $specialist->id : '' }}" 
               class="date-card {{ $isSelected ? 'active' : 'inactive' }}">
                <span class="date-day-name">{{ $dayName }}.</span>
                <span class="date-day-number">{{ $date->format('d') }}</span>
                <span class="date-month">{{ $monthName }}.</span>
            </a>
        @endfor

        <div class="nav-arrow"><i class="fas fa-chevron-right"></i></div>
    </div>

    {{-- TIME WHEEL PICKER (iOS Style) --}}
    <div class="time-wheel-container">
        
        <div class="wheel-instruction">Desliza para seleccionar una hora</div>
        
        <div class="wheel-interface">
            <!-- Central Highlight Bar -->
            <div class="highlight-bar"></div>
            
            <!-- Scrollable List -->
            <div class="wheel-scroller" id="timeScroller">
                <!-- Spacer for top -->
                <div class="wheel-spacer"></div>
                
                @if(count($availableTimes) > 0)
                    @foreach($availableTimes as $index => $time)
                        <?php 
                            $dt = \Carbon\Carbon::parse($time);
                            $formatted = $dt->format('h:i');
                            $ampm = $dt->format('a');
                            // Build URL for this slot
                            $url = route('booking.customerInfo', ['package_id' => $package->id, 'date' => $selectedDateFormatted, 'time' => $time, 'specialist_id' => isset($specialist) ? $specialist->id : null]);
                        ?>
                        <div class="wheel-item" data-url="{{ $url }}" data-time="{{ $time }}">
                            <span class="w-hour">{{ $formatted }}</span>
                            <span class="w-ampm">{{ $ampm }}.</span>
                        </div>
                    @endforeach
                @else
                    <div class="wheel-item disabled">
                        <span class="w-hour">--:--</span>
                    </div>
                @endif
                
                <!-- Spacer for bottom -->
                <div class="wheel-spacer"></div>
            </div>
        </div>
        
        @if(count($availableTimes) == 0)
            <div class="empty-state-wheel">
                <i class="fas fa-calendar-times"></i> Sin cupos disponibles
            </div>
        @endif

    </div>

    <style>
        /* Ocultar scrollbar pero permitir scroll */
        .wheel-scroller::-webkit-scrollbar {
            display: none;
        }
    /* Time Wheel Container */
    .time-wheel-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 60px 0;
        position: relative;
    }
    
    .wheel-instruction {
        font-size: 13px;
        color: #64748b; /* Gris oscuro para que se lea mejor */
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .wheel-interface {
        position: relative;
        width: 100%;
        max-width: 400px; /* Un poco más ancho para PC */
        height: 300px; 
        overflow: hidden;
        mask-image: linear-gradient(to bottom, transparent 0%, black 25%, black 75%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 25%, black 75%, transparent 100%);
    }

    .highlight-bar {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 70px;
        transform: translateY(-50%);
        border-top: 1px solid rgba(0,0,0,0.08);
        border-bottom: 1px solid rgba(0,0,0,0.08);
        z-index: 0;
        pointer-events: none;
    }

    .wheel-scroller {
        height: 100%;
        overflow-y: scroll;
        scroll-snap-type: y mandatory;
        padding: 0;
        position: relative;
        z-index: 10;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .wheel-scroller::-webkit-scrollbar { display: none; }

    .wheel-spacer {
        height: 115px; /* (300/2 - 70/2) aprox */
        flex-shrink: 0;
    }

    .wheel-item {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        scroll-snap-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0.5;
        transform: scale(0.85);
        color: #64748b; /* El gris oscuro que pediste */
    }
    
    .wheel-item.active {
        opacity: 1;
        transform: scale(1.15); /* Efecto de aumento al centro */
        color: #000000 !important; /* Negro Puro */
    }

    .w-hour {
        font-size: 42px; /* Números más grandes y claros */
        font-weight: 400;
        font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto;
        letter-spacing: -1.5px;
    }
    
    .active .w-hour {
        font-weight: 800; /* Extra Bold al centro */
    }

    .w-ampm {
        font-size: 18px;
        font-weight: 600;
        color: inherit;
        margin-top: 12px;
        text-transform: lowercase;
    }

        .empty-state-wheel {
            margin-top: 20px;
            color: #ef4444;
            font-weight: 600;
        }
        
        /* Mobile adjustment */
        @media (max-width: 600px) {
            .wheel-interface {
                max-width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scroller = document.getElementById('timeScroller');
            const items = document.querySelectorAll('.wheel-item:not(.disabled)');
            const btnContinue = document.querySelector('.btn-continue');
            const footer = document.querySelector('.fixed-footer');
            
            let selectedUrl = null;

            if(items.length === 0) return;

            // Intersection Observer to detect centered item
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target;
                        
                        // 1. Limpiar todos los items
                        items.forEach(i => {
                            i.classList.remove('active');
                            i.querySelector('.w-hour').style.color = '#94a3b8';
                            i.querySelector('.w-hour').style.fontWeight = '400';
                            i.querySelector('.w-ampm').style.color = '#94a3b8';
                        });
                        
                        // 2. Resaltar el activo (EL DEL MEDIO)
                        target.classList.add('active');
                        target.querySelector('.w-hour').style.color = '#000000'; 
                        target.querySelector('.w-hour').style.fontWeight = '800';
                        target.querySelector('.w-ampm').style.color = '#111827';

                        // 3. CAPTURAR EL VALOR PARA EL REGISTRO
                        selectedUrl = target.getAttribute('data-url');
                        console.log("Horario seleccionado centrado:", target.getAttribute('data-time'));
                        updateFooter(true);
                    }
                });
            }, {
                root: scroller,
                threshold: 0.6, // Más estricto para que solo uno sea el "jefe"
                rootMargin: "-40% 0px -40% 0px"
            });

            items.forEach(item => observer.observe(item));

            function updateFooter(isValid) {
                if (isValid && selectedUrl) {
                    btnContinue.disabled = false;
                    btnContinue.style.opacity = '1';
                    btnContinue.style.cursor = 'pointer';
                    btnContinue.style.background = '#000';
                    btnContinue.innerText = 'Continuar';
                    
                    // Asegurar que el click lleve a la URL con los parámetros correctos
                    btnContinue.onclick = function(e) {
                        e.preventDefault();
                        window.location.href = selectedUrl;
                    };
                } else {
                    btnContinue.disabled = true;
                    btnContinue.style.opacity = '0.5';
                }
            }
            
            // Click manual: forzar que ese item se centre y se registre
            items.forEach(item => {
                item.addEventListener('click', () => {
                   item.scrollIntoView({ behavior: 'smooth', block: 'center' }); 
                   // El observer se encargará de actualizar el selectedUrl al terminar el scroll
                });
            });
        });
    </script>
</div>

{{-- FOOTER --}}
<div class="fixed-footer">
    @if(count($availableTimes) == 0)
        <a href="{{ route('booking.waitlist', ['package_id' => $package->id, 'date' => $selectedDateFormatted, 'specialist_id' => isset($specialist) ? $specialist->id : null]) }}" style="width:100%; max-width:600px; text-decoration:none;">
            <button class="btn-continue" style="background: #FFA000;">
                Unirme a la lista de espera
            </button>
        </a>
    @else
        <button class="btn-continue" disabled style="opacity: 0.5; cursor: not-allowed;">
            Selecciona una hora para continuar
        </button>
    @endif
</div>

@endsection
