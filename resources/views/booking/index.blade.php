@extends('layout')

@section('content')
<style>
    /* Modern Gradient Background */
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }
    
    .booking-container {
        max-width: 480px;
        margin: 40px auto;
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        position: relative;
    }

    /* Hero Section */
    .hero {
        background-image: url('https://images.unsplash.com/photo-1560066984-138dadb4c035?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80');
        background-size: cover;
        background-position: center;
        height: 200px;
        position: relative;
    }
    .hero-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.8));
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
    }
    .business-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        margin-bottom: 10px;
        background: #fff;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .business-info h1 {
        color: white;
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    .business-info p {
        color: rgba(255,255,255,0.9);
        margin: 5px 0 0;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Categories & Services */
    .content-area {
        padding: 20px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Category Chips */
    .category-scroll {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 15px;
        margin-bottom: 10px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .category-scroll::-webkit-scrollbar { display: none; }
    
    .cat-chip {
        padding: 8px 16px;
        background: #f3f4f6;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 600;
        color: #4b5563;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .cat-chip.active {
        background: #111827;
        color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Upcoming Categories Style */
    .cat-chip.upcoming {
        background: #f3f4f6;
        color: #9ca3af;
        cursor: default;
        position: relative;
        filter: grayscale(1);
        opacity: 0.6;
    }
    .upcoming-label {
        font-size: 8px;
        text-transform: uppercase;
        display: block;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin-top: -2px;
    }

    /* Service Cards */
    .service-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        margin-bottom: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .service-card:hover {
        border-color: #000;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .service-info h3 {
        margin: 0 0 4px;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    .service-info .meta {
        font-size: 13px;
        color: #6b7280;
        display: flex;
        gap: 10px;
    }
    .service-price {
        text-align: right;
    }
    .price-tag {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }
    .book-btn {
        background: #000;
        color: white;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
        display: inline-block;
    }

    /* Floating Action Button (Cart-like) */
    .fab-cart {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #2563eb;
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        font-size: 24px;
        z-index: 100;
        transition: transform 0.2s;
    }
    .fab-cart:active { transform: scale(0.9); }
    
    @media (min-width: 768px) {
        .booking-container { margin: 60px auto; max-width: 600px; }
    }
</style>

<div class="booking-container">
    <!-- Header / Hero -->
    <div class="hero">
        <div class="hero-overlay">
            <img src="{{ \App\Models\Setting::get('business_logo') ? asset(\App\Models\Setting::get('business_logo')) : 'https://ui-avatars.com/api/?name=AP&background=fff&color=000' }}" class="business-avatar">
            <div class="business-info">
                <h1>{{ \App\Models\Setting::get('business_name', 'AgendaPOS PRO') }}</h1>
                <p><i class="fa fa-map-marker"></i> {{ \App\Models\Setting::get('business_address', 'Ubicación no disponible') }}</p>
                <div style="margin-top: 8px;">
                     @if(\App\Models\Setting::get('social_instagram'))
                        <a href="{{ \App\Models\Setting::get('social_instagram') }}" class="text-white" target="_blank"><i class="fa fa-instagram"></i></a> &nbsp;
                     @endif
                     @if(\App\Models\Setting::get('social_whatsapp'))
                        <a href="https://wa.me/{{ \App\Models\Setting::get('social_whatsapp') }}" class="text-white" target="_blank"><i class="fa fa-whatsapp"></i></a>
                     @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content-area">
        
        <!-- Filters -->
        <div class="category-scroll">
            <div class="cat-chip active" onclick="filterServices('all', this)">Todos</div>
            <div class="cat-chip" onclick="filterServices('populares', this)">🔥 Populares</div>
            @foreach($categories as $cat)
                <div class="cat-chip" onclick="filterServices('{{ str_slug($cat) }}', this)">{{ $cat }}</div>
            @endforeach

            @foreach($upcomingCategories as $uCat)
                <div class="cat-chip upcoming">
                    {{ $uCat }}
                    <span class="upcoming-label">Próximos días</span>
                </div>
            @endforeach
        </div>

        <!-- Service List -->
        <div class="section-title">
            <span>Servicios</span>
        </div>

        <div id="services-grid">
            @foreach($packages as $package)
            <div class="service-card" data-category="{{ str_slug($package->category ?? 'otros') }}" onclick="selectService({{ $package->id }})">
                <div class="service-info">
                    <h3>{{ $package->package_name }}</h3>
                    <div class="meta">
                        <span><i class="fa fa-clock-o"></i> {{ $package->package_time }} min</span>
                        @if($package->category)
                            <span>• {{ $package->category }}</span>
                        @endif
                    </div>
                </div>
                <div class="service-price">
                    <div class="price-tag">${{ number_format($package->package_price, 0) }}</div>
                    <span class="book-btn">Reservar</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function filterServices(category, element) {
        // Update active chip
        document.querySelectorAll('.cat-chip').forEach(el => el.classList.remove('active'));
        element.classList.add('active');

        // Filter items
        const cards = document.querySelectorAll('.service-card');
        cards.forEach(card => {
            if(category === 'all') {
                card.style.display = 'flex';
            } else if(category === 'populares') {
                // Mock logic for popular
                card.style.display = 'flex'; 
            } else {
                if(card.dataset.category === category) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }

    function selectService(id) {
        // Redirigir a la selección de especialista
        window.location.href = "{{ url('booking/calendar') }}/" + id;
    }
</script>

@stop
