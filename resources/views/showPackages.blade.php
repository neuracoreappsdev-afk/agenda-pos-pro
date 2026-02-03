@extends('layout')

@section('content')

<style>
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .page-subtitle {
        font-size: 14px;
        color: var(--text-muted);
    }

    .packages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 16px;
        margin-top: 24px;
    }

    .package-card {
        background: var(--bg-panel);
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-subtle);
        padding: 18px 18px 16px 18px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 10px;
    }

    .package-tag {
        font-size: 11px;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 4px;
    }

    .package-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .package-desc {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .package-meta {
        display: flex;
        gap: 10px;
        font-size: 12px;
        color: var(--text-muted);
        margin-bottom: 6px;
        flex-wrap: wrap;
    }

    .meta-pill {
        padding: 4px 9px;
        border-radius: 999px;
        background: #f3f4f6;
    }

    .package-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }

    .package-price {
        font-size: 16px;
        font-weight: 600;
    }

    .btn-primary {
        border-radius: 999px;
        padding: 8px 16px;
        font-size: 13px;
        border: none;
        background: var(--accent);
        color: white;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-primary:hover {
        filter: brightness(1.05);
    }

    .empty-state {
        padding: 24px;
        border-radius: var(--radius-lg);
        background: var(--bg-panel);
        border: 1px dashed var(--border-subtle);
        font-size: 14px;
        color: var(--text-muted);
        margin-top: 16px;
    }
</style>

@section('content')
<div class="app-header">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <button class="btn btn-default btn-sm" onclick="history.back()" style="border-radius:50%; width:36px; height:36px; padding:0; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-arrow-left"></i>
        </button>
        <span style="font-weight:600; font-family:var(--font-heading);">Seleccionar Servicio</span>
        <div style="width:36px;"></div> <!-- Spacer -->
    </div>
    
    <!-- Breadcrumb visual -->
    <div class="nav-pills-custom">
        <a href="#" class="nav-pill-item active">
            <i class="fa fa-list"></i> Servicios
        </a>
        <a href="#" class="nav-pill-item">
            <i class="fa fa-calendar"></i> Fecha
        </a>
        <a href="#" class="nav-pill-item">
            <i class="fa fa-clock"></i> Hora
        </a>
        <a href="#" class="nav-pill-item">
            <i class="fa fa-check"></i> Confirmar
        </a>
    </div>
</div>

<div class="p-4" style="background:#f8f9fa; min-height:calc(100vh - 80px);">
    <h2 style="font-size:22px; margin-bottom: 8px; font-weight:700;">Elige tu experiencia</h2>
    <p style="color:var(--text-muted); font-size:14px; margin-bottom:24px;">Selecciona uno de nuestros paquetes exclusivos.</p>

    @if($packages->isEmpty())
        <div class="empty-state text-center" style="padding:40px;">
            <i class="fa fa-box-open" style="font-size:40px; color:#ccc; margin-bottom:16px;"></i>
            <p>No hay servicios disponibles en este momento.</p>
        </div>
    @else
        <div class="packages-list" style="display:flex; flex-direction:column; gap:16px;">
            @foreach($packages as $package)
                <div class="package-card-mobile" style="background:white; border-radius:16px; padding:16px; box-shadow:var(--shadow-sm); border:1px solid transparent; transition:all 0.2s;" onclick="location.href='{{ route('booking.calendar', $package->id) }}'">
                    <div class="d-flex justify-between">
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                                <span style="background:#e0f2fe; color:#0284c7; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700; text-transform:uppercase;">
                                    {{ $package->category ?: 'General' }}
                                </span>
                                <span style="font-size:12px; color:#94a3b8; display:flex; align-items:center; gap:4px;">
                                    <i class="far fa-clock"></i> {{ $package->minutes ?? 45 }} min
                                </span>
                            </div>
                            
                            <h3 style="font-size:16px; font-weight:600; margin-bottom:6px; color:#1e293b;">
                                {{ $package->package_name }}
                            </h3>
                            
                            @if(!empty($package->description))
                            <p style="font-size:13px; color:#64748b; line-height:1.4; margin-bottom:12px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                {{ $package->description }}
                            </p>
                            @endif
                            
                            <div style="font-weight:700; font-size:16px; color:#1e293b;">
                                @if(!empty($package->price))
                                    ${{ number_format($package->price, 0, ',', '.') }}
                                @else
                                    Consultar Precio
                                @endif
                            </div>
                        </div>
                        
                        <div style="display:flex; align-items:center;">
                            <button style="width:40px; height:40px; border-radius:50%; background:#f1f5f9; border:none; color:#1e293b; display:flex; align-items:center; justify-content:center;">
                                <i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .package-card-mobile:hover {
        border-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        cursor: pointer;
    }
</style>

@endsection
