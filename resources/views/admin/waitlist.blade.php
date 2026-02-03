@extends('admin.dashboard_layout')

@section('content')
<style>
    /* ========================================
       SALA DE ESPERA HIPERREALISTA
       Lina Lucio Spa de Cejas - Cali
    ======================================== */
    
    :root {
        --spa-white: #FFFFFF;
        --spa-offwhite: #F8F9FA;
        --spa-gray-light: #E9ECEF;
        --spa-gray: #ADB5BD;
        --spa-gray-dark: #6C757D;
        --spa-charcoal: #343A40;
        --spa-black: #212529;
        --spa-gold: #C9A962;
        --spa-gold-light: #D4B87A;
        --spa-beige: #D4C4B0;
    }

    .spa-container {
        min-height: calc(100vh - 80px);
        background: var(--spa-offwhite);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        margin: -24px;
    }

    /* Header */
    .spa-header {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 14px 24px;
        background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(255,255,255,0.95));
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 100;
        border-bottom: 1px solid var(--spa-gray-light);
    }

    .spa-title {
        font-size: 18px;
        font-weight: 300;
        color: var(--spa-charcoal);
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .spa-title span {
        font-weight: 600;
        color: var(--spa-gold);
    }

    .spa-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .spa-count {
        background: var(--spa-charcoal);
        color: var(--spa-white);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
    }

    .spa-count strong {
        color: var(--spa-gold);
        font-size: 16px;
        margin-right: 4px;
    }

    .spa-btn {
        background: var(--spa-gold);
        color: var(--spa-white);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        letter-spacing: 1px;
        text-transform: uppercase;
        transition: all 0.3s;
    }

    .spa-btn:hover {
        background: var(--spa-gold-light);
        transform: translateY(-2px);
    }

    /* Spa Room */
    .spa-room-container {
        position: relative;
        width: 100%;
        height: 520px;
        overflow: hidden;
        margin-top: 60px;
    }

    .spa-room {
        position: relative;
        width: 100%;
        height: 100%;
    }

    /* Ceiling with LEDs */
    .ceiling {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: var(--spa-white);
    }

    .led-lights {
        position: absolute;
        top: 15px;
        left: 50px;
        right: 50px;
        display: flex;
        justify-content: space-around;
    }

    .led-light {
        width: 10px;
        height: 10px;
        background: #FFF;
        border-radius: 50%;
        box-shadow: 0 0 15px 5px rgba(255,255,255,0.8), 0 0 30px 10px rgba(255,255,200,0.4);
    }

    /* Back Wall */
    .wall-back {
        position: absolute;
        top: 50px;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(180deg, var(--spa-white), #FAFAFA);
    }

    /* Floor */
    .floor {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 270px;
        background: linear-gradient(180deg, #C0C0C0, #A8A8A8);
    }

    .floor::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(90deg, transparent, transparent 99px, rgba(0,0,0,0.03) 99px, rgba(0,0,0,0.03) 100px),
                    repeating-linear-gradient(0deg, transparent, transparent 99px, rgba(0,0,0,0.03) 99px, rgba(0,0,0,0.03) 100px);
    }

    /* TV Screens - Negro */
    .tv-screen {
        position: absolute;
        background: linear-gradient(135deg, #1A1A1A, #000);
        border-radius: 4px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
        border: 2px solid #333;
    }

    /* Puertas - Gris suave */
    .door {
        position: absolute;
        background: linear-gradient(180deg, #D0D0D0, #B8B8B8);
        border-radius: 6px 6px 0 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        border: 2px solid #A0A0A0;
    }

    .door::before {
        content: '';
        position: absolute;
        top: 45%;
        right: 12px;
        width: 8px;
        height: 8px;
        background: var(--spa-gold);
        border-radius: 50%;
        box-shadow: 0 0 5px rgba(201, 169, 98, 0.5);
    }

    .tv-screen::after {
        content: '📺';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
        opacity: 0.3;
    }

    /* AC Units - Más visibles */
    .ac-unit {
        position: absolute;
        background: linear-gradient(180deg, #FFFFFF, #E0E0E0);
        border-radius: 6px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        border: 2px solid #CCCCCC;
    }

    .ac-unit::before {
        content: '❄️';
        position: absolute;
        top: 3px;
        left: 8px;
        font-size: 12px;
    }

    .ac-unit::after {
        content: '';
        position: absolute;
        bottom: 6px;
        left: 8%;
        right: 8%;
        height: 8px;
        background: repeating-linear-gradient(90deg, 
            #B0B0B0 0px, #B0B0B0 4px, 
            #E8E8E8 4px, #E8E8E8 8px
        );
        border-radius: 2px;
    }

    /* Mirrors - Large, same size */
    .mirror {
        position: absolute;
        width: 80px;
        height: 100px;
        background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(200,220,255,0.3), rgba(255,255,255,0.8));
        border: 3px solid #C0C0C0;
        border-radius: 3px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    /* Logo */
    .spa-logo {
        position: absolute;
        width: 140px;
        height: auto;
        z-index: 10;
    }

    /* Pedicure Chairs */
    .pedicure-station {
        position: absolute;
        bottom: 90px;
    }

    .pedicure-chair {
        width: 70px;
        height: 90px;
        position: relative;
    }

    .chair-back {
        position: absolute;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        width: 55px;
        height: 45px;
        background: linear-gradient(180deg, #E8E0D5, #D4C4B0);
        border-radius: 15px 15px 3px 3px;
    }

    .chair-seat {
        position: absolute;
        bottom: 18px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 40px;
        background: linear-gradient(135deg, #E0D6C8, #D4C4B0);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .chair-base {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 20px;
        background: linear-gradient(180deg, #D4C4B0, #C4B4A0);
        border-radius: 5px 5px 10px 10px;
    }

    .footrest {
        position: absolute;
        bottom: 5px;
        left: 50%;
        transform: translateX(-50%);
        width: 35px;
        height: 25px;
        background: linear-gradient(180deg, #3A3A3A, #2A2A2A);
        border-radius: 5px;
    }

    .footrest::after {
        content: '';
        position: absolute;
        top: 4px;
        left: 4px;
        right: 4px;
        bottom: 4px;
        background: linear-gradient(180deg, #87CEEB, #5BA3C5);
        border-radius: 3px;
    }

    /* Receptionist - Detrás del escritorio */
    .receptionist {
        position: absolute;
        text-align: center;
        z-index: 50; /* Detrás del escritorio */
    }

    .receptionist-avatar {
        width: 50px;
        height: 75px;
        position: relative;
        margin: 0 auto;
    }

    .receptionist-head {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #F5D0C5, #E8B4A8);
        border-radius: 50%;
    }

    .receptionist-hair {
        position: absolute;
        top: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 28px;
        height: 16px;
        background: #3A2A20;
        border-radius: 50% 50% 0 0;
    }

    .receptionist-body {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 35px;
        background: linear-gradient(180deg, var(--spa-gold), var(--spa-gold-light));
        border-radius: 8px 8px 0 0;
    }

    .receptionist-legs {
        position: absolute;
        top: 52px;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 23px;
        background: #2A2A2A;
        border-radius: 0 0 5px 5px;
    }

    .receptionist-name {
        margin-top: 5px;
        background: var(--spa-charcoal);
        color: var(--spa-gold);
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1px;
        display: inline-block;
    }

    .receptionist-badge {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--spa-gold);
        color: white;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 9px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Reception Desk - Delante de Eliana */
    .reception-desk {
        position: absolute;
        width: 100px;
        height: 50px;
        background: linear-gradient(180deg, var(--spa-white), #E8E8E8);
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        border-top: 3px solid var(--spa-gold);
        z-index: 60; /* Delante de Eliana */
    }

    /* Client Avatars */
    .client-avatar {
        position: absolute;
        cursor: pointer;
        transition: all 0.5s;
        z-index: 50;
    }

    .client-avatar:hover {
        z-index: 100;
        transform: scale(1.1);
    }

    .avatar-badge {
        position: absolute;
        top: -45px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--spa-gold);
        color: white;
        font-size: 16px;
        font-weight: 800;
        padding: 5px 14px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(201,169,98,0.6);
        animation: float 2s ease-in-out infinite;
        z-index: 10;
    }

    @keyframes float {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(-6px); }
    }

    .avatar-name {
        position: absolute;
        top: -18px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.85);
        color: white;
        font-size: 10px;
        padding: 3px 10px;
        border-radius: 4px;
        white-space: nowrap;
        box-shadow: 0 2px 8px rgba(0,0,-1,0.3);
    }

    .person {
        width: 45px;
        height: 80px;
        position: relative;
    }

    .person-head {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #F5D0C5, #E8B4A8);
        border-radius: 50%;
        z-index: 2;
    }

    .person-hair {
        position: absolute;
        top: -3px;
        left: 50%;
        transform: translateX(-50%);
        width: 28px;
        height: 14px;
        border-radius: 50% 50% 0 0;
        z-index: 3;
    }

    .hair-dark { background: #2D2926; }
    .hair-brown { background: #5D4037; }
    .hair-blonde { background: #C8A870; }

    .person-body {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 35px;
        border-radius: 8px 8px 0 0;
        z-index: 1;
    }

    /* Estilos por Género */
    /* Femenino: Cabello un poco más largo/voluminoso */
    .gender-female .person-hair {
        width: 30px;
        height: 18px;
        top: -4px;
        border-radius: 12px 12px 5px 5px;
    }

    /* Masculino: Cabello más corto y pegado */
    .gender-male .person-hair {
        width: 24px;
        height: 10px;
        top: -1px;
        border-radius: 10px 10px 3px 3px;
    }

    /* Cuerpo: Ajustes por género */
    .gender-female .person-body {
        width: 30px;
        border-radius: 10px 10px 0 0;
    }

    .gender-male .person-body {
        width: 34px;
        border-radius: 6px 6px 0 0;
    }

    .person-legs {
        position: absolute;
        top: 52px;
        left: 50%;
        transform: translateX(-50%);
        width: 24px;
        height: 24px;
        background: #2A2A2A;
        border-radius: 0 0 5px 5px;
    }

    .status-dot {
        position: absolute;
        top: -55px;
        left: 50%;
        transform: translateX(-50%);
        width: 8px;
        height: 8px;
        background: #4CAF50;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(76,175,80,0.8);
    }

    /* Client Panel */
    .client-panel {
        position: absolute;
        bottom: 15px;
        left: 15px;
        width: 300px;
        background: rgba(255,255,255,0.98);
        border-radius: 10px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        z-index: 100;
        border: 1px solid var(--spa-gray-light);
    }

    .panel-header {
        background: var(--spa-charcoal);
        color: var(--spa-white);
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .panel-header span {
        color: var(--spa-gold);
    }

    .panel-content {
        max-height: 160px;
        overflow-y: auto;
    }

    .client-row {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        border-bottom: 1px solid var(--spa-gray-light);
        cursor: pointer;
        transition: background 0.2s;
    }

    .client-row:hover {
        background: var(--spa-offwhite);
    }

    .client-num {
        width: 24px;
        height: 24px;
        background: var(--spa-gold);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 11px;
        margin-right: 10px;
    }

    .client-info {
        flex: 1;
    }

    .client-name {
        font-weight: 600;
        font-size: 12px;
        color: var(--spa-charcoal);
    }

    .client-time {
        font-size: 10px;
        color: var(--spa-gray-dark);
    }

    .client-actions {
        display: flex;
        gap: 6px;
    }

    .action-btn {
        width: 24px;
        height: 24px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        font-size: 11px;
    }

    .action-btn.notify { background: var(--spa-gold); }
    .action-btn.remove { background: var(--spa-gray-light); color: var(--spa-gray-dark); }
    .action-btn.remove:hover { background: #EF4444; color: white; }

    /* Empty */
    .empty-state {
        position: absolute;
        top: 45%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: var(--spa-gray-dark);
        z-index: 60;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.show {
        display: flex !important;
    }

    .modal-box {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header {
        background: var(--spa-charcoal);
        color: white;
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 1px;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--spa-gray);
        font-size: 20px;
        cursor: pointer;
    }

    .modal-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 10px;
        font-weight: 600;
        color: var(--spa-gray-dark);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--spa-gray-light);
        border-radius: 6px;
        font-size: 13px;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--spa-gold);
    }

    .modal-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--spa-gray-light);
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-secondary {
        background: var(--spa-gray-light);
        color: var(--spa-gray-dark);
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }
</style>

<div class="spa-container">
    <!-- Header -->
    <div class="spa-header">
        <div class="spa-title">{{ \App\Models\BusinessSetting::getValue('company_name', 'MI NEGOCIO') }} · {{ trans('messages.wait_list') }}</div>
        <div class="spa-controls">
            <div class="spa-count"><strong>{{ $waitlistEntries->count() }}</strong> {{ trans('messages.waiting') }}</div>
            <button class="spa-btn" onclick="openModal()">+ {{ trans('messages.add_client') }}</button>
        </div>
    </div>

    <!-- Spa Room -->
    <div class="spa-room-container">
        <div class="spa-room">
            <!-- Ceiling with LEDs -->
            <div class="ceiling">
                <div class="led-lights">
                    @for($i = 0; $i < 12; $i++)
                    <div class="led-light"></div>
                    @endfor
                </div>
            </div>

            <!-- Back Wall -->
            <div class="wall-back">
                <!-- 2 AC Units - Arriba bien pegados al techo -->
                <div class="ac-unit" style="top: 15px; left: 40px; width: 120px; height: 35px;"></div>
                <div class="ac-unit" style="top: 15px; right: 40px; width: 120px; height: 35px;"></div>
                
                <!-- TV Izquierdo -->
                <div class="tv-screen" style="top: 90px; left: 30px; width: 100px; height: 60px;"></div>
                
                <!-- PUERTA 1 - Entre TV1 y TV2 -->
                <div class="door" style="top: 70px; left: 170px; width: 50px; height: 120px;"></div>
                
                <!-- Logo Dinámico -->
                @php $companyLogo = \App\Models\BusinessSetting::getValue('company_logo'); @endphp
                @if($companyLogo)
                    <img src="{{ url($companyLogo) }}" class="spa-logo" style="top: 10px; left: 440px; max-height: 60px; object-fit: contain;">
                @else
                    <div class="spa-logo" style="top: 20px; left: 440px; font-weight: 800; color: var(--spa-gold); font-size: 20px; letter-spacing: 2px;">
                        {{ strtoupper(\App\Models\BusinessSetting::getValue('company_name', 'LOGO')) }}
                    </div>
                @endif
                
                <!-- TV Derecho de la izquierda (TV2) -->
                <div class="tv-screen" style="top: 90px; left: 320px; width: 100px; height: 60px;"></div>
                
                <!-- PUERTA 2 - Entre la recepción y TV3 -->
                <div class="door" style="top: 70px; right: 530px; width: 50px; height: 120px;"></div>
                
                <!-- 4 Espejos grandes - Lado DERECHO -->
                <div class="mirror" style="top: 80px; right: 30px;"></div>
                <div class="mirror" style="top: 80px; right: 120px;"></div>
                <div class="mirror" style="top: 80px; right: 210px;"></div>
                <div class="mirror" style="top: 80px; right: 300px;"></div>
                
                <!-- TV del centro-derecha (TV3) -->
                <div class="tv-screen" style="top: 90px; right: 400px; width: 100px; height: 60px;"></div>
            </div>

            <!-- Floor -->
            <div class="floor">
                <!-- 6 Pedicure Stations (3 a cada lado) -->
                <div class="pedicure-station" style="left: 30px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>
                <div class="pedicure-station" style="left: 120px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>
                <div class="pedicure-station" style="left: 210px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>
                <!-- Silla central removida para dar espacio a la recepción -->
                <div class="pedicure-station" style="right: 210px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>
                <div class="pedicure-station" style="right: 120px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>
                <div class="pedicure-station" style="right: 30px;">
                    <div class="pedicure-chair">
                        <div class="chair-back"></div>
                        <div class="chair-seat"></div>
                        <div class="chair-base"></div>
                        <div class="footrest"></div>
                    </div>
                </div>

                <!-- Recepcionista Eliana - DETRÁS del escritorio, justo debajo del logo -->
                <div class="receptionist" style="left: 490px; bottom: 175px;">
                    <div class="receptionist-badge">{{ trans('messages.reception') }}</div>
                    <div class="receptionist-avatar">
                        <div class="receptionist-hair"></div>
                        <div class="receptionist-head"></div>
                        <div class="receptionist-body"></div>
                        <div class="receptionist-legs"></div>
                    </div>
                    <div class="receptionist-name">ELIANA</div>
                </div>
                
                <!-- Reception Desk - DELANTE de Eliana, tapa la mitad inferior -->
                <div class="reception-desk" style="left: 465px; bottom: 160px;"></div>

                <!-- Clients Container -->
                <div id="clientContainer"></div>
            </div>
        </div>
    </div>

    <!-- Client Panel -->
    <div class="client-panel">
        <div class="panel-header"><span>👤</span> {{ trans('messages.waiting_clients') }}</div>
        <div class="panel-content" id="panelList">
            @forelse($waitlistEntries as $i => $entry)
            <div class="client-row" onclick="focusClient({{ $entry->id }})" id="row-{{ $entry->id }}">
                <div class="client-num">{{ $i + 1 }}</div>
                <div class="client-info">
                    <div class="client-name">{{ $entry->customer ? $entry->customer->first_name . ' ' . $entry->customer->last_name : 'Cliente' }}</div>
                    <div class="client-time">⏰ {{ $entry->created_at->diffForHumans() }}</div>
                </div>
                <div class="client-actions" onclick="event.stopPropagation()">
                    <button class="action-btn notify" onclick="notifyClient({{ $entry->id }})">📲</button>
                    <button class="action-btn remove" onclick="removeClient({{ $entry->id }})">✕</button>
                </div>
            </div>
            @empty
            <div style="padding: 25px; text-align: center; color: var(--spa-gray);">{{ trans('messages.no_waiting_clients') }}</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">{{ trans('messages.add_to_waitlist') }}</span>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">{{ trans('messages.client_star') }}</label>
                <select id="customer_id" class="form-control" required>
                    <option value="">{{ trans('messages.select_customer_placeholder') }}</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">{{ trans('messages.requested_service') }}</label>
                <select id="package_id" class="form-control">
                    <option value="">{{ trans('messages.any_service') }}</option>
                    @foreach($packages as $p)
                    <option value="{{ $p->id }}">{{ $p->package_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">{{ trans('messages.preferred_specialist') }}</label>
                <select id="specialist_id" class="form-control">
                    <option value="">{{ trans('messages.any_specialist') }}</option>
                    @isset($specialists)
                    @foreach($specialists as $s)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                    @endisset
                </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">{{ trans('messages.available_from') }}</label>
                    <input type="date" id="date_from" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ trans('messages.available_to') }}</label>
                    <input type="date" id="date_to" class="form-control" value="{{ date('Y-m-d', strtotime('+1 month')) }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">{{ trans('messages.notes_label') }}</label>
                <textarea id="notes" class="form-control" rows="2" placeholder="{{ trans('messages.observations_placeholder') }}"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="closeModal()">{{ trans('messages.cancel') }}</button>
            <button class="spa-btn" onclick="saveClient()">✓ {{ trans('messages.add_to_list_btn') }}</button>
        </div>
    </div>
</div>

<script>
    /* Funciones críticas al inicio */
    function openModal() { 
        var modal = document.getElementById('addModal');
        if (modal) modal.classList.add('show'); 
    }
    
    function closeModal() { 
        var modal = document.getElementById('addModal');
        if (modal) modal.classList.remove('show'); 
    }
</script>

<script>
    console.log('Cargando sala de espera...');
    
    var clients = {!! json_encode($waitlistEntries->map(function($e, $i) {
        return [
            'id' => $e->id,
            'num' => $i + 1,
            'name' => $e->customer ? (string)$e->customer->first_name : 'Cliente',
            'fullName' => $e->customer ? (string)$e->customer->first_name . ' ' . substr((string)($e->customer->last_name ?? ''), 0, 1) . '.' : 'Cliente',
            'gender' => $e->customer ? strtolower((string)$e->customer->gender) : 'female'
        ];
    })->values(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};

    var femaleBodyColors = ['#E8D4C8', '#D4C4B8', '#F5DDC5', '#EAD1C0'];
    var maleBodyColors = ['#A0A0A0', '#808080', '#B0A090', '#343A40'];
    var hairTypes = ['hair-dark', 'hair-brown', 'hair-blonde'];
    var positions = [
        { left: 45, bottom: 170 },
        { left: 135, bottom: 165 },
        { left: 225, bottom: 170 },
        { right: 225, bottom: 170 },
        { right: 135, bottom: 165 },
        { right: 45, bottom: 170 }
    ];

    function renderClients() {
        var container = document.getElementById('clientContainer');
        if (!container) return;
        container.innerHTML = '';

        if (clients.length === 0) {
            return;
        }

        for (var i = 0; i < clients.length; i++) {
            var c = clients[i];
            var isMale = c.gender === 'male' || c.gender === 'masculino';
            var genderClass = isMale ? 'gender-male' : 'gender-female';
            
            var pos = positions[i % positions.length];
            var colors = isMale ? maleBodyColors : femaleBodyColors;
            var bodyColor = colors[i % colors.length];
            var hair = hairTypes[i % hairTypes.length];

            var el = document.createElement('div');
            el.className = 'client-avatar ' + genderClass;
            el.id = 'client-' + c.id;
            
            if (pos.left !== undefined) el.style.left = pos.left + 'px';
            else el.style.right = pos.right + 'px';
            el.style.bottom = pos.bottom + 'px';
            
            el.setAttribute('onclick', 'focusClient(' + c.id + ')');

            el.innerHTML = 
                '<div class="status-dot"></div>' +
                '<div class="avatar-badge">#' + c.num + '</div>' +
                '<div class="avatar-name">' + c.fullName + '</div>' +
                '<div class="person">' +
                    '<div class="person-hair ' + hair + '"></div>' +
                    '<div class="person-head"></div>' +
                    '<div class="person-body" style="background:linear-gradient(180deg,' + bodyColor + ',' + bodyColor + 'dd);"></div>' +
                    '<div class="person-legs"></div>' +
                '</div>';
            container.appendChild(el);
        }
    }

    function focusClient(id) {
        var avatars = document.querySelectorAll('.client-avatar');
        for (var i = 0; i < avatars.length; i++) { avatars[i].style.opacity = '0.4'; }
        
        var el = document.getElementById('client-' + id);
        if (el) { el.style.opacity = '1'; el.style.transform = 'scale(1.15)'; }
        
        setTimeout(function() {
            var avatars = document.querySelectorAll('.client-avatar');
            for (var i = 0; i < avatars.length; i++) { 
                avatars[i].style.opacity = '1'; 
                avatars[i].style.transform = ''; 
            }
        }, 2000);
        
        var rows = document.querySelectorAll('.client-row');
        for (var i = 0; i < rows.length; i++) { rows[i].style.background = ''; }
        
        var row = document.getElementById('row-' + id);
        if (row) row.style.background = 'rgba(201,169,98,0.15)';
    }

    function saveClient() {
        var customerId = document.getElementById('customer_id').value;
        if (!customerId) { 
            alert('{{ trans('messages.please_select_customer') }}'); 
            return; 
        }

        var data = {
            customer_id: customerId,
            package_id: document.getElementById('package_id').value || null,
            specialist_id: document.getElementById('specialist_id').value || null,
            date_from: document.getElementById('date_from').value,
            date_to: document.getElementById('date_to').value,
            time_preference: 'any',
            notes: document.getElementById('notes').value || ''
        };

        fetch('{{ url("admin/waitlist/store") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify(data)
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                if (typeof showToast === 'function') {
                    showToast('✅ {{ trans('messages.customer_added_to_waitlist') }}', 'success');
                } else {
                    alert('✅ {{ trans('messages.customer_added_to_waitlist') }}');
                }
                closeModal();
                setTimeout(function() { location.reload(); }, 500);
            } else {
                alert('Error: ' + (res.error || 'No se pudo agregar'));
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('{{ trans('messages.connection_error') }}');
        });
    }

    function removeClient(id) {
        if (!confirm('{{ trans('messages.confirm_remove_from_waitlist') }}')) return;
        fetch('{{ url("admin/waitlist/delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ id: id })
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                var el = document.getElementById('client-' + id);
                if (el) el.remove();
                var row = document.getElementById('row-' + id);
                if (row) row.remove();
                if (typeof showToast === 'function') {
                    showToast('{{ trans('messages.customer_removed') }}', 'success');
                }
            }
        });
    }

    function notifyClient(id) {
        if (typeof showToast === 'function') {
            showToast('📲 {{ trans('messages.customer_notified_availability') }}', 'info');
        } else {
            alert('📲 {{ trans('messages.customer_notified_availability') }}');
        }
    }

    document.addEventListener('DOMContentLoaded', renderClients);
</script>
@endsection
