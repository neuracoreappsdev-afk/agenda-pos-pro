@extends('layout')

@section('content')

<style>
    .page-container {
        max-width: 600px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    .header-title {
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        padding: 20px 0;
        position: relative;
        border-bottom: 1px solid #f0f0f0;
        margin-bottom: 20px;
    }

    .back-btn {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #333;
        font-size: 18px;
        text-decoration: none;
    }

    .specialist-list {
        padding: 0 20px;
    }

    .specialist-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: inherit;
        transition: background 0.2s;
    }

    .specialist-card:hover {
        background: #fafafa;
    }

    .avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        background: #eee;
    }

    .avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #000;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 18px;
    }

    .info {
        flex: 1;
    }

    .name {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #000;
    }

    .role {
        font-size: 13px;
        color: #666;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #000;
        font-weight: 500;
        margin-top: 4px;
    }

    .arrow-icon {
        color: #ccc;
        font-size: 14px;
    }

    .any-specialist-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 20px;
        text-decoration: none;
        color: inherit;
        border: 1px solid #eee;
    }
</style>

<div class="page-container">

    {{-- HEADER --}}
    <div class="header-title">
        <a href="{{ route('booking.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>
        Elige un profesional
    </div>

    <div class="specialist-list">
        
        {{-- Opción: Cualquier profesional --}}
        <a href="{{ route('booking.calendar', ['package_id' => $package->id]) }}" class="any-specialist-card">
            <div class="avatar-placeholder" style="background: #000;">
                <i class="fas fa-users" style="font-size: 20px;"></i>
            </div>
            <div class="info">
                <div class="name">Cualquier profesional</div>
                <div class="role">Máxima disponibilidad</div>
            </div>
            <i class="fas fa-chevron-right arrow-icon"></i>
        </a>

        <div style="font-size: 13px; font-weight: 600; color: #999; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">
            Especialistas
        </div>

        @foreach($specialists as $specialist)
        <a href="{{ route('booking.calendar', ['package_id' => $package->id, 'specialist_id' => $specialist->id]) }}" class="specialist-card">
            @if($specialist->avatar)
                <img src="{{ $specialist->avatar }}" alt="{{ $specialist->name }}" class="avatar">
            @else
                <div class="avatar-placeholder" style="background: #eee; color: #333;">
                    {{ substr($specialist->name, 0, 1) }}
                </div>
            @endif
            
            <div class="info">
                <div class="name">{{ $specialist->name }}</div>
                <div class="role">{{ $specialist->title }}</div>
                <div class="rating">
                    <i class="fas fa-star" style="font-size: 10px;"></i> 5.0
                </div>
            </div>
            <i class="fas fa-chevron-right arrow-icon"></i>
        </a>
        @endforeach

        @if($specialists->isEmpty())
            <div style="text-align: center; color: #999; padding: 40px;">
                No hay especialistas disponibles.
            </div>
        @endif

    </div>

</div>

@endsection
