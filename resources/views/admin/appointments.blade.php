@extends('admin/dashboard_layout')

@section('content')
<style>
    /* Branding Lina Lucio - Fuentes */
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600&display=swap');
    
    /* Override Main Content Padding for Full Screen Agenda */
    .main-content {
        padding: 0 !important;
        overflow: hidden !important;
    }

    /* Layout General */
    .agenda-wrapper {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 60px); /* Complete height minus header */
        background: #fdfcfa;
        overflow: hidden;
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Header */
    .agenda-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .header-left { display: flex; align-items: center; gap: 15px; }
    .header-right { display: flex; align-items: center; gap: 10px; }

    .nav-btns { display: flex; gap: 5px; }

    .btn-nav {
        background: white;
        border: 1px solid #d1d5db;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.2s;
    }

    .btn-nav:hover { background: #f3f4f6; }

    .date-display {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .btn-primary {
        background: #2563eb;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-primary:hover { background: #1d4ed8; }

    /* Specialists Row */
    .specialists-row {
        display: flex;
        padding: 10px 20px 10px 60px; /* Reduced right padding to maximize width but keep scrollbar space */
        border-bottom: 1px solid #e5e7eb;
        overflow-x: hidden; /* Hide scrollbar here, controlled by JS */
        gap: 0;
    }

    .sp-header {
        flex: 1; /* Allow expansion */
        min-width: 200px; /* Wider columns */
        text-align: center;
        padding: 10px;
        /* width: 150px; REMOVED fixed width */
    }

    .sp-avatar {
        width: 70px;
        height: 124px; /* 9:16 ratio strict */
        border-radius: 8px;
        margin: 0 auto 10px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-weight: 600;
        font-size: 24px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 2px solid white;
    }

    .sp-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sp-name {
        font-size: 11px;
        font-weight: 700;
        color: #111827;
        text-transform: uppercase;
    }

    .sp-role {
        font-size: 10px;
        color: white;
        padding: 2px 8px;
        border-radius: 10px;
        display: inline-block;
        margin-top: 4px;
    }

    /* Calendar Body */
    .agenda-body {
        flex: 1;
        display: flex;
        overflow: auto;
    }

    /* Time Column */
    .time-column {
        width: 60px;
        min-width: 60px;
        border-right: 1px solid #e5e7eb;
        background: #f9fafb;
    }

    .time-slot {
        height: 80px;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 5px;
        border-bottom: 1px solid #f3f4f6;
    }

    .time-text {
        font-size: 11px;
        font-weight: 600;
        color: #6b7280;
    }

    /* Specialists Columns Container */
    .columns-container {
        display: flex;
        flex: 1;
    }

    /* Individual Specialist Column */
    .sp-column {
        flex: 1; /* Allow expansion */
        min-width: 200px; /* Match header min-width */
        border-right: 1px solid #f3f4f6;
        position: relative;
        /* width: 150px; REMOVED fixed width */
    }

    .hour-block {
        height: 80px;
        border-bottom: 1px solid #f3f4f6;
        position: relative;
        cursor: pointer;
        z-index: 1; /* Ensure clickability */
    }

    .hour-block:hover {
        background: #f0f9ff;
        z-index: 2;
    }

    .half-hour-line {
        position: absolute;
        top: 40px;
        left: 0;
        right: 0;
        border-top: 1px dashed #e5e7eb;
    }

    /* Appointment Block - Diseño limpio y profesional */
    .appointment-block {
        position: absolute;
        left: 3px;
        right: 3px;
        border-radius: 8px;
        padding: 8px 10px;
        padding-bottom: 14px; /* Espacio para el resize handle */
        color: white;
        font-size: 11px;
        overflow: hidden;
        cursor: pointer;
        z-index: 5;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        border-left: 4px solid rgba(255,255,255,0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .appointment-block:hover {
        transform: translateY(-2px);
        z-index: 100;
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }

    /* Status Colors - Branding Lina Lucio */
    .status-confirmada { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; 
    }
    .status-pendiente { 
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important; 
    }
    .status-completada, .status-facturada { 
        background: linear-gradient(135deg, #9CA3AF 0%, #6B7280 100%) !important; 
    }
    .status-cancelada { 
        background: linear-gradient(135deg, #fca5a5 0%, #ef4444 100%) !important; 
        opacity: 0.5; 
    }

    /* Blocks Inactivos (Fuera de jornada, Festivos, Domingos) */
    .hour-block.inactive-block {
        background: repeating-linear-gradient(
            45deg,
            #f3f4f6,
            #f3f4f6 10px,
            #f9fafb 10px,
            #f9fafb 20px
        );
        cursor: not-allowed;
        pointer-events: none;
        opacity: 0.7;
    }

    /* Bloqueos temporales (En curso) */
    .appointment-block.status-lock {
        background: rgba(156, 163, 175, 0.4) !important;
        border: 2px dashed #9ca3af;
        color: #4b5563;
        box-shadow: none;
        backdrop-filter: blur(2px);
    }
    
    /* Fix Resize - Deshabilitar transiciones durante redimensionamiento */
    .appointment-block.resizing {
        transition: none !important;
        transform: none !important;
    }

    /* Resize Handle - Las rayitas inferiores para estirar */
    .resize-handle {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 12px;
        cursor: ns-resize;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.15) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 6px 6px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .resize-handle::before {
        content: '';
        width: 30px;
        height: 3px;
        background: rgba(255,255,255,0.7);
        border-radius: 2px;
    }

    .appointment-block:hover .resize-handle {
        opacity: 1;
    }

    /* ============== DRAG & DROP STYLES ============== */
    .appointment-block {
        cursor: grab;
    }
    
    .appointment-block.dragging {
        opacity: 0.5;
        cursor: grabbing;
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        z-index: 9999;
    }
    
    .sp-column.drag-over {
        background: linear-gradient(to bottom, 
            rgba(34, 197, 94, 0.15) 0%, 
            rgba(34, 197, 94, 0.05) 100%);
        border: 2px dashed #22c55e !important;
        transition: all 0.2s ease;
    }
    
    .ghost-preview {
        position: absolute;
        left: 2px;
        right: 2px;
        background: rgba(34, 197, 94, 0.3);
        border: 2px dashed #22c55e;
        border-radius: 6px;
        pointer-events: none;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        color: #22c55e;
    }

    /* Move Confirmation Modal */
    .move-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        z-index: 12000;
        justify-content: center;
        align-items: center;
    }
    .move-modal-overlay.show { display: flex; }

    .move-modal {
        background: white;
        border-radius: 16px;
        width: 420px;
        max-width: 90vw;
        box-shadow: 0 30px 80px rgba(0,0,0,0.4);
        overflow: hidden;
    }
    
    .move-modal-header {
        background: #22c55e;
        color: white;
        padding: 20px 24px;
    }
    
    .move-modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }
    
    .move-modal-body {
        padding: 24px;
    }
    
    .move-info-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
    }
    
    .move-info-row .icon {
        font-size: 24px;
    }
    
    .move-info-row .text {
        flex: 1;
    }
    
    .move-info-row .label {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 2px;
    }
    
    .move-info-row .value {
        font-weight: 700;
        color: #1f2937;
    }
    
    .move-arrow {
        text-align: center;
        font-size: 24px;
        color: #22c55e;
        margin: 8px 0;
    }
    
    .move-notify-option {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 8px;
        padding: 14px;
        margin-top: 16px;
    }
    
    .move-notify-option label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        font-size: 14px;
        color: #92400e;
    }
    
    .move-notify-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }
    
    .move-modal-footer {
        display: flex;
        gap: 12px;
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    
    .move-modal-footer button {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-cancel-move {
        background: #e5e7eb;
        color: #374151;
    }
    
    .btn-cancel-move:hover {
        background: #d1d5db;
    }
    
    .btn-confirm-move {
        background: #22c55e;
        color: white;
    }
    
    .btn-confirm-move:hover {
        background: #16a34a;
    }

    .apt-client {
        font-weight: 700;
        font-size: 11px;
        margin-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .apt-service {
        font-size: 10px;
        opacity: 0.95;
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .apt-time {
        font-size: 9px;
        opacity: 0.85;
        margin-top: 4px;
        font-weight: 500;
    }

    .apt-status-badge {
        display: none; /* Ocultar para diseño más limpio */
    }
    
    .apt-notes-preview {
        display: none; /* Ocultar notas para diseño limpio */
    }

    /* Floating Details Card (Lizto Style) */
    .apt-details-card {
        display: none;
        position: fixed;
        width: 420px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        background: white;
        border-radius: 20px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.5);
        z-index: 5000;
        pointer-events: none;
        overflow: hidden;
        border: none;
        opacity: 0;
        visibility: hidden;
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.2s ease-out, visibility 0s 0.3s;
    }

    .apt-details-card.show {
        display: block;
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
        pointer-events: auto; /* Allow clicks and hover when visible */
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.2s ease-out, visibility 0s;
    }

    .apt-details-header {
        background: #22c55e;
        color: white;
        padding: 18px 24px;
    }

    .details-client-row {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 18px;
        text-transform: uppercase;
        letter-spacing: -0.02em;
    }

    .details-phone {
        font-size: 14px;
        opacity: 0.9;
        margin-top: 6px;
        color: #f0fdf4;
    }

    .apt-details-body {
        padding: 24px;
        color: #1f2937;
    }

    .details-service-row {
        display: flex;
        justify-content: space-between;
        font-weight: 800;
        margin-bottom: 12px;
        font-size: 16px;
        color: #111827;
        border-bottom: 2px solid #f3f4f6;
        padding-bottom: 12px;
    }

    .details-meta {
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 24px;
        line-height: 1.8;
    }

    .details-status-line {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #22c55e;
        font-weight: 800;
        font-size: 14px;
        text-transform: uppercase;
        border-top: 1px solid #f3f4f6;
        padding-top: 18px;
    }

    .details-source {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 8px;
    }

    .btn-checkout {
        background: #059669;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        flex: 1;
        transition: all 0.2s;
    }
    .btn-checkout:hover { background: #047857; transform: translateY(-1px); }
    
    .btn-secondary-sm {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-secondary-sm:hover { background: #e2e8f0; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        z-index: 20000; /* Higher than chat overlay */
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(2px);
    }

    .modal-overlay.show { display: flex; }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 650px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title { font-size: 18px; font-weight: 600; color: #1f2937; margin: 0; }
    .modal-close { background: none; border: none; font-size: 24px; color: #9ca3af; cursor: pointer; }

    .modal-body { padding: 24px; }

    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-weight: 500; font-size: 14px; color: #374151; margin-bottom: 6px; }
    .form-input, .form-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .form-row { display: flex; gap: 15px; }
    .form-row .form-group { flex: 1; }

    /* Services List */
    .services-list {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        background: #f9fafb;
    }

    .service-item {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        padding: 12px;
        background: white;
        border-radius: 6px;
        margin-bottom: 10px;
        border: 1px solid #e5e7eb;
    }

    .service-item:last-child { margin-bottom: 0; }

    .service-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .service-item-num {
        background: #2563eb;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .service-item-body { flex: 1; }

    .service-item .form-group { margin-bottom: 10px; }
    .service-item .form-group:last-child { margin-bottom: 0; }

    .btn-remove-service {
        background: #fee2e2;
        color: #ef4444;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
    }

    .btn-add-service {
        background: #dbeafe;
        color: #2563eb;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        width: 100%;
        margin-top: 10px;
    }

    .modal-footer {
        display: flex;
        justify-content: space-between;
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn-cancel { background: white; color: #ef4444; border: 1px solid #fecaca; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
    .btn-delete { background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; display: none; }
    .btn-save { background: #2563eb; color: white; border: none; padding: 10px 24px; border-radius: 6px; cursor: pointer; }
    .footer-right { display: flex; gap: 10px; }

    /* Checkbox */
    .checkbox-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .checkbox-row input[type="checkbox"] {
        width: 18px;
        height: 18px;
    }

    /* Current Time Line */
    .current-time-line {
        position: absolute;
        left: 0;
        right: 0;
        border-top: 2px solid #ef4444;
        z-index: 100; /* Increased to be above appointments */
        pointer-events: none;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.4);
    }

    .current-time-dot {
        width: 10px;
        height: 10px;
        background: #ef4444;
        border-radius: 50%;
        position: absolute;
        left: 0;
        top: -5px;
        box-shadow: 0 0 10px #ef4444;
    }

    /* Conflict Warning */
    .conflict-warning {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        color: #92400e;
        padding: 12px 18px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.6;
        display: none;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.1);
    }

    .conflict-warning.show { display: block; }

    /* Draggable Client Sidebar (New) */
    .agenda-overall-container {
        display: flex;
        height: calc(100vh - 80px);
        overflow: hidden;
    }
    
    .client-sidebar {
        width: 280px;
        min-width: 280px;
        border-right: 1px solid #e5e7eb;
        background: #fcfcfd;
        display: flex;
        flex-direction: column;
        z-index: 50;
    }
    
    .sidebar-search-box { padding: 15px; border-bottom: 1px solid #e5e7eb; }
    .sidebar-list { flex: 1; overflow-y: auto; padding: 10px; }
    
    .draggable-client {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 8px;
        cursor: grab;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .draggable-client:hover {
        border-color: #2563eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        background: #f0f9ff;
    }
    
    .draggable-client:active { cursor: grabbing; }
    .client-drag-avatar { width: 32px; height: 32px; background: #e0e7ff; color: #4338ca; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; }
    .client-drag-info { flex: 1; overflow: hidden; }
    .client-drag-name { font-weight: 600; font-size: 13px; color: #1f2937; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .client-drag-phone { font-size: 11px; color: #6b7280; }

    .hour-block.drag-over { background: #dbeafe !important; border: 2px dashed #2563eb !important; }

    /* Estilos para el Selector de Cliente Dinámico */
    .client-selector-container { position: relative; }
    .client-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1100;
        max-height: 250px;
        overflow-y: auto;
        display: none;
    }
    .client-search-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .client-search-item:hover { background: #f0f9ff; }
    .client-search-name { font-weight: 600; color: #1f2937; }
    .client-search-info { font-size: 12px; color: #6b7280; }
    
    .new-client-fields {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 15px;
        margin-top: 10px;
        display: none;
    }
    .btn-toggle-client {
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        margin-top: 5px;
        text-decoration: underline;
    }
    /* Resize Handle - Las rayitas inferiores para estirar */
    .resize-handle {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 14px; /* Un poco más alto para facilitar agarre */
        cursor: ns-resize;
        background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.2) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 8px 8px;
        opacity: 0;
        transition: opacity 0.2s ease;
        z-index: 100 !important; /* Siempre encima de todo */
        pointer-events: auto !important;
    }

    .resize-handle::after {
        content: "";
        width: 30px;
        height: 4px;
        background: rgba(255,255,255,0.8);
        border-radius: 2px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .btn-add-pill {
        background: #1f2937;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-add-pill:hover {
        background: #374151;
        transform: scale(1.05);
    }
    .btn-add-pill:active {
        transform: scale(0.95);
    }
    .btn-primary-sm {
        background: #1f2937;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }
</style>

<!-- ============ MOVE CONFIRMATION MODAL ============ -->
<div class="move-modal-overlay" id="moveModalOverlay">
    <div class="move-modal">
        <div class="move-modal-header">
            <h3>📅 {{ trans('messages.move_appointment') }}</h3>
        </div>
        <div class="move-modal-body">
            <p style="margin-bottom: 16px; color: #6b7280;">{{ App::getLocale() == 'es' ? 'Estás moviendo la cita de:' : (App::getLocale() == 'en' ? 'You are moving the appointment of:' : 'Você está movendo a consulta de:') }}</p>
            
            <div class="move-info-row">
                <span class="icon">👤</span>
                <div class="text">
                    <div class="label">{{ trans('messages.client') }}</div>
                    <div class="value" id="moveClientName">-</div>
                </div>
            </div>
            
            <div class="move-info-row" style="background: #fef2f2;">
                <span class="icon">📍</span>
                <div class="text">
                    <div class="label">{{ trans('messages.current_location') }}</div>
                    <div class="value" id="moveFromInfo">-</div>
                </div>
            </div>
            
            <div class="move-arrow">⬇️</div>
            
            <div class="move-info-row" style="background: #f0fdf4;">
                <span class="icon">✨</span>
                <div class="text">
                    <div class="label">{{ trans('messages.new_location') }}</div>
                    <div class="value" id="moveToInfo">-</div>
                </div>
            </div>
            
            <div class="move-notify-option">
                <label>
                    <input type="checkbox" id="moveNotifyClient">
                    📧 {{ trans('messages.notify_client') }}
                </label>
            </div>
        </div>
        <div class="move-modal-footer">
            <button class="btn-cancel-move" onclick="cancelMove()">{{ trans('messages.cancel') }}</button>
            <button class="btn-confirm-move" onclick="confirmMove()">✓ {{ trans('messages.confirm') }}</button>
        </div>
    </div>
</div>

<!-- ============ DELETE CONFIRMATION MODAL ============ -->
<div class="move-modal-overlay" id="deleteModalOverlay">
    <div class="move-modal" style="border-top: 4px solid #ef4444;">
        <div class="move-modal-header" style="background: #ef4444;">
            <h3>⚠️ {{ trans('messages.cancel_appointment') }}</h3>
        </div>
        <div class="move-modal-body">
            <div class="move-info-row">
                <span class="icon">👤</span>
                <div class="text">
                    <div class="label">{{ trans('messages.client') }}</div>
                    <div class="value" id="deleteClientName">-</div>
                </div>
            </div>
            
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin: 16px 0;">
                <p style="margin: 0; color: #991b1b; font-weight: 600;">
                    🗓️ {{ App::getLocale() == 'es' ? 'El horario quedará disponible nuevamente para otros clientes.' : (App::getLocale() == 'en' ? 'The slot will be available again for other clients.' : 'O horário ficará disponível novamente para outros clientes.') }}
                </p>
            </div>
            
            <div style="margin: 16px 0; display: flex; align-items: center; justify-content: center; gap: 8px;">
                <input type="checkbox" id="notify_client_delete" checked style="width: 18px; height: 18px; cursor: pointer;">
                <label for="notify_client_delete" style="font-size: 14px; color: #6b7280; cursor: pointer;">{{ trans('messages.notify_client') }}</label>
            </div>
            
            <p style="color: #6b7280; text-align: center;">{{ App::getLocale() == 'es' ? '¿Está seguro de cancelar esta cita?' : (App::getLocale() == 'en' ? 'Are you sure about canceling this appointment?' : 'Tem certeza de que deseja cancelar esta consulta?') }}</p>
        </div>
        <div class="move-modal-footer">
            <button class="btn-cancel-move" onclick="cancelDelete()">{{ trans('messages.keep') }}</button>
            <button class="btn-confirm-move" style="background: #ef4444;" onclick="executeDelete()">{{ trans('messages.delete') }}</button>
        </div>
    </div>
</div>

<!-- Floating Details Card - COMPLETELY OUTSIDE agenda-wrapper -->
<div id="aptDetailsCard" class="apt-details-card" onmouseenter="keepDetailsCardOpen()" onmouseleave="closeDetailsCard()">
    <div class="apt-details-header">
        <div class="details-client-row">
            <span id="detailClientIcon">👤</span>
            <span id="detailClientName"></span>
        </div>
        <div class="details-phone" id="detailClientPhone"></div>
    </div>
    <div class="apt-details-body">
        <div class="details-service-row">
            <span id="detailServiceName"></span>
            <span id="detailServicePrice"></span>
        </div>
        <div class="details-meta">
            <div>💳 {{ App::getLocale() == 'es' ? 'Paga en Centro' : (App::getLocale() == 'en' ? 'Pay at Center' : 'Paga no Centro') }}</div>
            <div style="margin-top:10px;">
                <span id="detailTimeRange"></span><br>
                <span id="detailDate"></span><br>
                {{ App::getLocale() == 'es' ? 'CON' : (App::getLocale() == 'en' ? 'WITH' : 'COM') }} 💙 <span id="detailSpecialist" style="text-transform: uppercase;"></span><br>
                <span id="detailDuration"></span>
            </div>
        </div>
        <div class="details-status-line">
            <span id="detailStatusText">✔ {{ App::getLocale() == 'es' ? 'CITA CONFIRMADA' : (App::getLocale() == 'en' ? 'APPOINTMENT CONFIRMED' : 'CONSULTA CONFIRMADA') }}</span>
        </div>
        <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 8px;">
            <button class="btn-checkout" id="btnArrived" style="background:#f59e0b; color:white; width:100%; font-weight:800;" onclick="markArrived()">
                📍 CLIENTE LLEGÓ / ANUNCIAR
            </button>
            <div style="display: flex; gap: 8px;">
                <button class="btn-checkout" id="btnCheckout" onclick="goToCheckout()" style="flex:1;">
                    💳 {{ App::getLocale() == 'es' ? 'Facturar' : (App::getLocale() == 'en' ? 'Checkout' : 'Faturar') }}
                </button>
                <button class="btn-secondary-sm" onclick="editAppointmentFromDetails()" style="flex:1;">
                    ✏️ {{ App::getLocale() == 'es' ? 'Editar' : (App::getLocale() == 'en' ? 'Edit' : 'Editar') }}
                </button>
            </div>
        </div>
        <div class="details-source">{{ App::getLocale() == 'es' ? 'Reservado desde WhatsApp' : (App::getLocale() == 'en' ? 'Booked via WhatsApp' : 'Reservado pelo WhatsApp') }}</div>
    </div>
</div>

<script>
    function markArrived() {
        if (!selectedAptForDetails) return;
        
        const btn = document.getElementById('btnArrived');
        btn.disabled = true;
        btn.textContent = 'Anunciando...';

        fetch('{{ url("admin/appointments/mark-arrived") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ appointment_id: selectedAptForDetails.id })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.style.background = '#10b981';
                btn.textContent = '✓ ANUNCIADO';
                setTimeout(() => {
                    hideDetailsCard();
                    loadAppointments();
                }, 1000);
            } else {
                alert(data.message || 'Error al marcar llegada');
                btn.disabled = false;
                btn.textContent = '📍 CLIENTE LLEGÓ / ANUNCIAR';
            }
        });
    }
</script>

<div class="agenda-wrapper">
    <div class="agenda-header">
        <div class="header-left">
            <div class="nav-btns">
                <button class="btn-nav" onclick="changeDate(-1)">‹</button>
                <button class="btn-nav" onclick="goToToday()">{{ trans('messages.today') }}</button>
                <button class="btn-nav" onclick="changeDate(1)">›</button>
            </div>
            <div class="date-display" id="currentDateDisplay">
                {{ date('l, d M Y') }}
            </div>
        </div>
        <div class="header-right">
            <input type="date" id="datePicker" class="form-input" style="width: auto;" onchange="loadDate(this.value)">
            <button class="btn-primary" onclick="openNewAppointment()">+ {{ trans('messages.new_appointment') }}</button>
        </div>
    </div>

    <div class="specialists-row">
        @foreach($specialists as $sp)
        <div class="sp-header">
            <div class="sp-avatar">
                @if(!empty($sp['avatar']))
                <img src="{{ $sp['avatar'] }}" alt="{{ $sp['name'] }}">
                @else
                {{ substr($sp['name'], 0, 2) }}
                @endif
            </div>
            <div class="sp-name">{{ $sp['name'] }}</div>
            <span class="sp-role" style="background-color: {{ $sp['color'] }}">{{ $sp['role'] }}</span>
        </div>
        @endforeach
        @if(count($specialists) == 0)
        <div style="padding: 20px; color: #6b7280;">{{ App::getLocale() == 'es' ? 'No hay especialistas registrados.' : (App::getLocale() == 'en' ? 'No specialists registered.' : 'Não há especialistas registrados.') }} <a href="{{ url('admin/specialists/create') }}">{{ App::getLocale() == 'es' ? 'Crear uno' : (App::getLocale() == 'en' ? 'Create one' : 'Criar um') }}</a></div>
        @endif
    </div>

    <div class="agenda-body">
        <div class="time-column">
            @for($h = 7; $h <= 21; $h++)
            <div class="time-slot">
                <span class="time-text">{{ $h > 12 ? ($h - 12) : $h }}:00 {{ $h >= 12 ? 'PM' : 'AM' }}</span>
                <span class="time-text" style="margin-top: 30px;">{{ $h > 12 ? ($h - 12) : $h }}:30</span>
            </div>
            @endfor
        </div>

        <div class="columns-container">
            @foreach($specialists as $sp)
            <div class="sp-column" data-specialist-id="{{ $sp['id'] }}" data-specialist-name="{{ $sp['name'] }}">
                @for($h = 7; $h <= 21; $h++)
                <div class="hour-block" data-hour="{{ $h }}" data-specialist="{{ $sp['id'] }}" onclick="openNewAppointmentAt({{ $sp['id'] }}, {{ $h }})">
                    <div class="half-hour-line"></div>
                </div>
                @endfor
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal Nueva/Editar Cita -->
<div class="modal-overlay" id="appointmentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">{{ App::getLocale() == 'es' ? 'Nueva Cita' : (App::getLocale() == 'en' ? 'New Appointment' : 'Novo Compromisso') }}</h3>
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="conflict-warning" id="conflictWarning">
                ⚠️ <strong>Conflicto de horario:</strong> <span id="conflictMessage"></span>
            </div>

            <!-- Buscador de Cliente Dinámico -->
            <div class="form-group" id="clientSelectionGroup">
                <label class="form-label">{{ trans('messages.client') }} *</label>
                <div class="client-selector-container">
                    <input type="text" id="clientSearchInput" class="form-input" placeholder="{{ App::getLocale() == 'es' ? 'Buscar por Nombre, Cédula o Celular...' : (App::getLocale() == 'en' ? 'Search by Name, ID or Mobile...' : 'Pesquisar por Nome, ID ou Celular...') }}" onkeyup="searchClients(this.value)">
                    <input type="hidden" id="aptCustomer">
                    <div id="clientSearchResults" class="client-search-results"></div>
                </div>
                <button type="button" class="btn-toggle-client" id="btnShowNewClient" onclick="toggleNewClientForm(true)">+ {{ App::getLocale() == 'es' ? 'Crear Cliente Nuevo' : (App::getLocale() == 'en' ? 'Create New Client' : 'Criar Novo Cliente') }}</button>
            </div>

            <!-- Formulario Cliente Nuevo (Colapsable) -->
            <div id="newClientForm" class="new-client-fields">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                    <div style="font-weight:700; font-size:12px; color:#1e293b;">{{ App::getLocale() == 'es' ? 'DATOS DEL NUEVO CLIENTE' : (App::getLocale() == 'en' ? 'NEW CLIENT DATA' : 'DADOS DO NOVO CLIENTE') }}</div>
                    <button type="button" class="btn-toggle-client" onclick="toggleNewClientForm(false)">{{ trans('messages.cancel') }}</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ App::getLocale() == 'es' ? 'Nombre' : (App::getLocale() == 'en' ? 'First Name' : 'Nome') }} *</label>
                        <input type="text" id="newClientFirstName" class="form-input" placeholder="{{ App::getLocale() == 'es' ? 'Nombre' : (App::getLocale() == 'en' ? 'First Name' : 'Nome') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ App::getLocale() == 'es' ? 'Apellido' : (App::getLocale() == 'en' ? 'Last Name' : 'Sobrenome') }} *</label>
                        <input type="text" id="newClientLastName" class="form-input" placeholder="{{ App::getLocale() == 'es' ? 'Apellido' : (App::getLocale() == 'en' ? 'Last Name' : 'Sobrenome') }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ App::getLocale() == 'es' ? 'Celular' : (App::getLocale() == 'en' ? 'Mobile' : 'Celular') }} *</label>
                        <input type="text" id="newClientPhone" class="form-input" placeholder="300...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ App::getLocale() == 'es' ? 'Email (opcional)' : (App::getLocale() == 'en' ? 'Email (optional)' : 'Email (opcional)') }}</label>
                        <input type="email" id="newClientEmail" class="form-input" placeholder="correo@ejemplo.com">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">{{ App::getLocale() == 'es' ? 'Identificación' : (App::getLocale() == 'en' ? 'ID Number' : 'Identificação') }}</label>
                        <input type="text" id="newClientDoc" class="form-input" placeholder="CC...">
                    </div>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">{{ App::getLocale() == 'es' ? 'Fecha' : (App::getLocale() == 'en' ? 'Date' : 'Data') }} *</label>
                    <input type="date" class="form-input" id="aptDate" required>
                </div>
                <div class="form-group">
                    <label class="form-label">{{ App::getLocale() == 'es' ? 'Hora de inicio' : (App::getLocale() == 'en' ? 'Start Time' : 'Hora de início') }} *</label>
                    <input type="time" class="form-input" id="aptTime" required onchange="checkConflicts()">
                </div>
            </div>

            <!-- Servicios (múltiples) -->
            <div class="form-group">
                <label class="form-label" style="display: flex; justify-content: space-between; align-items: center;">
                    Servicios 
                    <span style="font-size: 11px; font-weight: 400; color: #64748b;">(Puedes agregar varios a la vez)</span>
                </label>
                
                <!-- Selector de Especialista Sticky (como en Caja) -->
                <div style="margin-bottom:12px; background:#f0f9ff; padding:10px; border-radius:10px; display:flex; align-items:center; gap:10px; border:1px solid #bae6fd;">
                    <span style="font-weight:700; color:#0369a1; font-size:12px; white-space:nowrap;">👩‍⚕️ ASIGNAR A:</span>
                    <select id="globalStickySpecialist" onchange="setStickySpecialist(this.value)" style="flex:1; padding:6px 10px; border:1px solid #7dd3fc; border-radius:6px; font-size:13px; font-weight:600; background:white; outline:none;">
                        <option value="">-- Seleccionar especialista --</option>
                        @foreach($specialists as $sp)
                            <option value="{{ $sp['id'] }}">{{ $sp['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div style="position: relative; margin-bottom: 15px;">
                    <div style="display: flex; gap: 8px;">
                        <input type="text" id="quickServiceSearch" class="form-input" 
                               placeholder="🔍 Busca servicios... (Manicure, Corte, etc.)" 
                               onkeyup="searchServicesQuick(this.value)"
                               onfocus="searchServicesQuick(this.value)"> <!-- Mostrar al enfocar -->
                        <button type="button" class="btn-primary-sm" onclick="toggleQuickServices()" style="white-space: nowrap;">Ver todos</button>
                    </div>
                    <div id="quickServiceResults" class="client-search-results" style="display:none; max-height: 500px; width: 100%; top: 45px; overflow-y: auto;"></div>
                </div>

                <div class="services-list" id="servicesList">
                    <!-- Services will be added here -->
                </div>
                
                <div style="margin-top: 10px; display: flex; gap: 10px;">
                    <button type="button" class="btn-add-service" onclick="addServiceRow()" style="flex: 1;">+ Agregar manually</button>
                </div>
            </div>

            <!-- Notas -->
            <div class="form-group">
                <label class="form-label">Notas</label>
                <textarea class="form-input" id="aptNotes" rows="2" placeholder="Observaciones..."></textarea>
            </div>

            <!-- Notificaciones -->
            <div class="form-group">
                <label class="form-label">Notificaciones</label>
                <div class="checkbox-row">
                    <input type="checkbox" id="sendEmail" checked>
                    <label for="sendEmail">Enviar confirmación por Email</label>
                </div>
                <div class="checkbox-row">
                    <input type="checkbox" id="sendWhatsapp" checked>
                    <label for="sendWhatsapp">Enviar confirmación por WhatsApp</label>
                </div>
            </div>

            <!-- Estado -->
            <div class="form-group">
                <label class="form-label">Estado</label>
                <select class="form-select" id="aptStatus">
                    <option value="pendiente">⏳ Pendiente</option>
                    <option value="confirmada">✅ Confirmada</option>
                    <option value="completada">✔️ Completada</option>
                    <option value="cancelada">❌ Cancelada</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeModal()">{{ trans('messages.cancel') }}</button>
            <div class="footer-right">
                <button type="button" class="btn-delete" id="btnDeleteApt" onclick="deleteAppointment()">{{ trans('messages.delete') }}</button>
                <button type="button" class="btn-save" onclick="saveAppointment()">{{ trans('messages.save') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Sync Scroll
    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.specialists-row');
        const body = document.querySelector('.agenda-body');
        
        if(header && body) {
            body.addEventListener('scroll', function() {
                header.scrollLeft = body.scrollLeft;
            });
        }
    });
</script>

<input type="hidden" id="currentAptId" value="">
<input type="hidden" id="currentDate" value="{{ date('Y-m-d') }}">

<script>
    let currentDate = new Date();
    let appointments = [];
    let allServices = [];
    let allCustomers = [];
    let allSpecialists = {!! json_encode($specialists) !!} || [];
    let serviceCounter = 0;
    let selectedAptForDetails = null;
    let pendingMove = null;
    let draggedAppointment = null;
    let filterSpecialistId = null; // Para filtrar servicios por especialista
    let stickySpecialistId = null; // Para heredar especialista (como en Caja)
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Set initial state
        const datePicker = document.getElementById('datePicker');
        if (datePicker) {
            datePicker.value = formatDate(currentDate);
        }
        
        // Initial Loads
        try {
            loadAppointments();
            loadServices();
            loadCustomers();
            updateCurrentTimeLine();
        } catch(e) {
            console.error("Error en inicialización:", e);
        }
        
        // Timer for time line
        setInterval(updateCurrentTimeLine, 30000);
        
        // Initial force render of timeline after a short delay to ensure columns are parsed
        setTimeout(updateCurrentTimeLine, 500);
    });

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }

    function formatDisplayDate(date) {
        const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
        return date.toLocaleDateString('es-ES', options);
    }

    function changeDate(delta) {
        currentDate.setDate(currentDate.getDate() + delta);
        document.getElementById('datePicker').value = formatDate(currentDate);
        document.getElementById('currentDateDisplay').textContent = formatDisplayDate(currentDate);
        loadAppointments();
    }

    function goToToday() {
        currentDate = new Date();
        document.getElementById('datePicker').value = formatDate(currentDate);
        document.getElementById('currentDateDisplay').textContent = formatDisplayDate(currentDate);
        loadAppointments();
    }

    function loadDate(dateStr) {
        currentDate = new Date(dateStr + 'T00:00:00');
        document.getElementById('currentDateDisplay').textContent = formatDisplayDate(currentDate);
        loadAppointments();
    }

    function loadAppointments() {
        const dateStr = formatDate(currentDate);
        document.getElementById('currentDate').value = dateStr;
        
        document.querySelectorAll('.appointment-block').forEach(el => el.remove());
        
        // Sombreado visual de horas inactivas (Festivos, Domingos, Fuera de jornada)
        const holidays = {!! json_encode($holidays) !!} || [];
        const dailyHours = {!! json_encode($dailyHours) !!} || [];
        const locks = {!! json_encode($locks) !!} || [];
        
        const dayNames = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        const dayOfWeek = dayNames[currentDate.getDay()];
        const isHoliday = holidays.includes(dateStr);
        const isSunday = (dayOfWeek === 'sunday');

        document.querySelectorAll('.hour-block').forEach(block => {
            block.classList.remove('inactive-block');
            block.title = "";
            
            const spId = block.getAttribute('data-specialist');
            const specialist = allSpecialists.find(s => s.id == spId);
            const blockHour = parseInt(block.getAttribute('data-hour'));

            if (!specialist) return;

            // 1. Verificar Excepciones primero (Habilitar días)
            const exceptions = specialist.schedule_exceptions || [];
            const exception = exceptions.find(ex => ex.date === dateStr);
            
            if (exception) {
                const [exStartH] = exception.start.split(':').map(Number);
                const [exEndH] = exception.end.split(':').map(Number);
                
                if (blockHour >= exStartH && blockHour < exEndH) {
                    // Este bloque está habilitado por excepción
                    block.title = "Horario especial habilitado";
                    return; 
                }
            }

            // 2. Verificar Horario Regular del Especialista
            const wh = specialist.working_hours || {};
            const dayWh = wh[dayOfWeek];

            if (!dayWh || !dayWh.is_working) {
                block.classList.add('inactive-block');
                block.title = "Descanso (No labora este día)";
            } else {
                const [startH] = dayWh.start.split(':').map(Number);
                const [endH] = dayWh.end.split(':').map(Number);
                
                if (blockHour < startH || blockHour >= endH) {
                    block.classList.add('inactive-block');
                    block.title = "Fuera de su turno laboral";
                }
            }
            
            // 3. Festivos Globales (Solo si no hay excepción para este especialista)
            if (isHoliday && !exception) {
                block.classList.add('inactive-block');
                block.title = "Festivo - Negocio cerrado";
            }
        });

        fetch('{{ url("admin/appointments/list") }}?date=' + dateStr)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    appointments = data.appointments;
                    
                    // Mezclar bloqueos temporales como citas visuales si coinciden con la fecha
                    locks.forEach(lock => {
                        const lockDate = lock.datetime.split(' ')[0];
                        if (lockDate === dateStr) {
                            appointments.push({
                                id: 'lock-' + lock.id,
                                customer_name: 'RESERVA EN CURSO (WEB)',
                                service_name: 'Bloqueo temporal',
                                start_time: lock.datetime.split(' ')[1],
                                end_time: moment(lock.datetime, 'YYYY-MM-DD HH:mm').add(30, 'minutes').format('HH:mm'),
                                specialist_id: lock.specialist_id,
                                status: 'lock',
                                duration: 30
                            });
                        }
                    });

                    renderAppointments();
                }
            })
            .catch(err => console.error('Error loading appointments:', err));
    }

    function renderAppointments() {
        document.querySelectorAll('.appointment-block').forEach(el => el.remove());
        
        appointments.forEach(apt => {
            const column = document.querySelector(`.sp-column[data-specialist-id="${apt.specialist_id}"]`);
            if (!column) return;
            
            const [startH, startM] = apt.start_time.split(':').map(Number);
            const [endH, endM] = apt.end_time.split(':').map(Number);
            
            const startMinutes = (startH - 7) * 80 + (startM / 60) * 80;
            const endMinutes = (endH - 7) * 80 + (endM / 60) * 80;
            const height = Math.max(endMinutes - startMinutes, 30);
            
            const block = document.createElement('div');
            block.className = `appointment-block status-${apt.status || 'pendiente'}`;
            block.style.top = startMinutes + 'px';
            block.style.height = height + 'px';
            block.setAttribute('data-id', apt.id);
            block.id = `apt-${apt.id}`;
            
            // Contenedor interno para el contenido (para separar del resize handle)
            const contentWrapper = document.createElement('div');
            contentWrapper.className = 'apt-content';
            contentWrapper.style.cssText = 'position:relative; z-index:2; pointer-events:auto;';
            
            contentWrapper.innerHTML = `
                <div class="apt-client">${apt.customer_name}</div>
                <div class="apt-service">${apt.service_name}</div>
                <div class="apt-time">${apt.start_time} - ${apt.end_time}</div>
            `;
            
            // IMPORTANTE: Anexar el contenido al bloque
            block.appendChild(contentWrapper);
            
            // Clicks en el bloque principal abren el editor
            block.onclick = (e) => {
                if (e.target.closest('.resize-handle')) return;
                editAppointment(apt);
            };

            // Mouse events para la tarjeta de detalles - SOLO EN EL CONTENIDO
            contentWrapper.onmouseenter = (e) => {
                if (!isResizing) showDetailsCard(e, apt);
            };
            contentWrapper.onmouseleave = () => scheduleHideCard();
            
            // Mouse events del bloque principal - Desactivar el hover general para control libre
            block.onmouseenter = null;
            block.onmouseleave = null;

            // Tirador de redimensión - Z-INDEX ALTO para asegurar agarre
            const resizer = document.createElement('div');
            resizer.className = 'resize-handle';
            resizer.style.zIndex = "100";
            
            // Si el mouse toca las rayas, ocultamos la ficha de inmediato para dejar libre la vista
            resizer.onmouseenter = (e) => {
                e.stopPropagation();
                hideDetailsCard();
            };

            resizer.onmousedown = (e) => {
                e.preventDefault();
                e.stopPropagation();
                startResizing(e, apt.id);
            };
            
            block.appendChild(resizer);
            column.appendChild(block);
        });
    }

    function showDetailsCard(e, apt) {
        selectedAptForDetails = apt;
        const card = document.getElementById('aptDetailsCard');
        if (!card) return;
        
        // Populate data
        document.getElementById('detailClientName').textContent = apt.customer_name;
        document.getElementById('detailClientPhone').textContent = apt.customer_phone || 'Sin Teléfono de Contacto';
        document.getElementById('detailServiceName').textContent = apt.service_name;
        document.getElementById('detailServicePrice').textContent = '$ ' + Number(apt.service_price).toLocaleString();
        document.getElementById('detailTimeRange').textContent = `${apt.start_time} - ${apt.end_time}`;
        
        try {
            if (typeof formatDisplayDate === 'function') {
                document.getElementById('detailDate').textContent = formatDisplayDate(new Date(apt.date + 'T00:00:00'));
            } else {
                document.getElementById('detailDate').textContent = apt.date;
            }
        } catch(err) {
            document.getElementById('detailDate').textContent = apt.date;
        }
        
        document.getElementById('detailSpecialist').textContent = apt.specialist_name;
        document.getElementById('detailDuration').textContent = apt.duration + 'm';
        
        const statusText = document.getElementById('detailStatusText');
        if (apt.status === 'cancelada') {
            statusText.textContent = '❌ CITA CANCELADA';
            statusText.parentElement.style.color = '#ef4444';
        } else {
            statusText.textContent = '✔ CITA ' + apt.status.toUpperCase();
            statusText.parentElement.style.color = '#22c55e';
        }

        // Show centered with animation
        card.style.display = 'block';
        setTimeout(() => {
            card.classList.add('show');
        }, 10);
    }

    // Variables for persistent hover
    let cardHoverTimeout = null;
    let isCardHovered = false;

    function hideDetailsCard() {
        // Only hide if not hovering the card
        if (isCardHovered) return;
        
        const card = document.getElementById('aptDetailsCard');
        if (card) {
            card.classList.remove('show');
            setTimeout(() => {
                if (!card.classList.contains('show')) {
                    card.style.display = 'none';
                }
            }, 300);
        }
    }

    function keepDetailsCardOpen() {
        isCardHovered = true;
        if (cardHoverTimeout) {
            clearTimeout(cardHoverTimeout);
            cardHoverTimeout = null;
        }
    }

    function closeDetailsCard() {
        isCardHovered = false;
        cardHoverTimeout = setTimeout(() => {
            hideDetailsCard();
        }, 200);
    }

    function scheduleHideCard() {
        // Called when leaving the appointment block
        if (cardHoverTimeout) clearTimeout(cardHoverTimeout);
        cardHoverTimeout = setTimeout(() => {
            if (!isCardHovered) {
                hideDetailsCard();
            }
        }, 300);
    }

    function editAppointmentFromDetails() {
        if (!selectedAptForDetails) return;
        isCardHovered = false;
        hideDetailsCard();
        editAppointment(selectedAptForDetails);
    }

    // Go to Caja with appointment data for billing
    function goToCheckout() {
        if (!selectedAptForDetails) return;
        
        // Buscar todas las citas del mismo grupo (confirm_token)
        let relatedServices = [];
        if (selectedAptForDetails.confirm_token) {
            relatedServices = appointments.filter(a => a.confirm_token === selectedAptForDetails.confirm_token);
        } else {
            relatedServices = [selectedAptForDetails];
        }

        // Prepare checkout data
        const checkoutData = {
            appointment_id: selectedAptForDetails.id,
            customer_id: selectedAptForDetails.customer_id,
            customer_name: selectedAptForDetails.customer_name,
            customer_phone: selectedAptForDetails.customer_phone,
            services: relatedServices.map(s => ({
                id: s.service_id,
                name: s.service_name,
                price: s.service_price
            })),
            date: selectedAptForDetails.date,
            from_appointment: true
        };
        
        // Store in sessionStorage for Caja to pick up
        sessionStorage.setItem('pendingCheckout', JSON.stringify(checkoutData));
        
        // Redirect to POS
        window.location.href = '{{ url("admin/crear-factura") }}';
    }


    function checkoutAppointment() {
        if (!selectedAptForDetails) return;
        
        // Confirm checkout
        if (!confirm(`¿Deseas marcar como "Facturada" la cita de ${selectedAptForDetails.customer_name}?`)) {
            return;
        }

        const btn = document.getElementById('btnCheckout');
        btn.disabled = true;
        btn.textContent = 'Procesando...';

        fetch('{{ url("admin/appointments/checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                appointment_id: selectedAptForDetails.id
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Cita facturada y registrada en informes', 'success');
                hideDetailsCard();
                loadAppointments();
            } else {
                showToast(data.error || 'Error al facturar', 'error');
                btn.disabled = false;
                btn.textContent = '💳 Facturar';
            }
        })
        .catch(err => {
            console.error('Checkout error:', err);
            showToast('Error de conexión', 'error');
            btn.disabled = false;
            btn.textContent = '💳 Facturar';
        });
    }

    function loadServices() {
        fetch('{{ url("api/packages") }}')
            .then(res => res.json())
            .then(data => {
                allServices = data;
            })
            .catch(() => {
                // Fallback sample services
                allServices = [
                    {id: 1, package_name: 'Corte de cabello', package_price: 25000, package_time: 30},
                    {id: 2, package_name: 'Manicure', package_price: 20000, package_time: 45},
                    {id: 3, package_name: 'Pedicure', package_price: 25000, package_time: 60},
                ];
            });
    }

    function loadCustomers() {
        fetch('{{ url("admin/clientes/list") }}')
            .then(res => res.json())
            .then(data => {
                allCustomers = data;
                console.log("Clientes cargados:", data.length);
            })
            .catch(err => console.error('Error loading customers:', err));
    }

    function searchClients(query) {
        const resultsDiv = document.getElementById('clientSearchResults');
        if (!query || query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }

        const filtered = allCustomers.filter(c => {
            const searchStr = `${c.first_name} ${c.last_name} ${c.identification} ${c.contact_number}`.toLowerCase();
            return searchStr.includes(query.toLowerCase());
        }).slice(0, 8);

        if (filtered.length > 0) {
            resultsDiv.innerHTML = filtered.map(c => `
                <div class="client-search-item" onclick="selectClient(${c.id}, '${c.first_name} ${c.last_name}')">
                    <div>
                        <div class="client-search-name">${c.first_name} ${c.last_name}</div>
                        <div class="client-search-info">${c.contact_number || 'Sin cel'} | ${c.identification || 'Sin ID'}</div>
                    </div>
                    <span style="font-size:12px; color:#3b82f6;">Seleccionar</span>
                </div>
            `).join('');
            resultsDiv.style.display = 'block';
        } else {
            resultsDiv.innerHTML = '<div class="client-search-item" style="color:#6b7280; cursor:default;">No se encontraron resultados</div>';
            resultsDiv.style.display = 'block';
        }
    }

    function selectClient(id, name) {
        document.getElementById('aptCustomer').value = id;
        document.getElementById('clientSearchInput').value = name;
        document.getElementById('clientSearchResults').style.display = 'none';
        toggleNewClientForm(false);
    }

    function toggleNewClientForm(show) {
        const form = document.getElementById('newClientForm');
        const searchInput = document.getElementById('clientSearchInput');
        const customerIdInput = document.getElementById('aptCustomer');
        
        if (show) {
            form.style.display = 'block';
            searchInput.value = '';
            searchInput.disabled = true;
            customerIdInput.value = 'NEW';
            document.getElementById('btnShowNewClient').style.display = 'none';
        } else {
            form.style.display = 'none';
            searchInput.disabled = false;
            if (customerIdInput.value === 'NEW') customerIdInput.value = '';
            document.getElementById('btnShowNewClient').style.display = 'inline-block';
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.client-selector-container')) {
            document.getElementById('clientSearchResults').style.display = 'none';
        }
    });
    
    function setStickySpecialist(val) {
        stickySpecialistId = val;
        // Actualizar todos los select de especialistas en las filas que aún no tengan servicio o si se desea unificar
        const selects = document.querySelectorAll('.specialist-select');
        selects.forEach(select => {
            if (!select.value || select.value == "") {
                select.value = val;
            }
        });
        checkConflicts();
    }

    function addServiceRow(service = null, specialist = null, duration = null) {
        serviceCounter++;
        const container = document.getElementById('servicesList');
        
        // Prioridad: Specialist pasado > stickySpecialistId > filterSpecialistId
        const finalSpecId = specialist || stickySpecialistId || filterSpecialistId;

        let servicesOptions = '<option value="">Seleccionar servicio</option>';
        if (Array.isArray(allServices)) {
            allServices.forEach(s => {
                const selected = service && s.id == service ? 'selected' : '';
                servicesOptions += `<option value="${s.id}" data-duration="${s.package_time || 60}" ${selected}>${s.package_name} - $${s.package_price}</option>`;
            });
        }

        let specialistsOptions = '<option value="">Seleccionar...</option>';
        if (Array.isArray(allSpecialists)) {
            allSpecialists.forEach(sp => {
                const selected = finalSpecId && sp.id == finalSpecId ? 'selected' : '';
                specialistsOptions += `<option value="${sp.id}" ${selected}>${sp.name}</option>`;
            });
        }

        const html = `
            <div class="service-item" data-index="${serviceCounter}">
                <div class="service-item-num">${container.children.length + 1}</div>
                <div class="service-item-body">
                    <div class="form-group">
                        <label class="form-label">Servicio</label>
                        <select class="form-select service-select" onchange="updateServiceDuration(this)">
                            ${servicesOptions}
                        </select>
                        <input type="hidden" class="custom-duration" value="${duration || ''}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Especialista</label>
                        <select class="form-select specialist-select" onchange="stickySpecialistId = this.value; document.getElementById('globalStickySpecialist').value = this.value; checkConflicts();">
                            ${specialistsOptions}
                        </select>
                    </div>
                </div>
                <button type="button" class="btn-remove-service" onclick="removeServiceRow(${serviceCounter})">✕</button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        updateServiceNumbers();
        checkConflicts(); 
    }

    function searchServicesQuick(query = '') {
        const resultsDiv = document.getElementById('quickServiceResults');
        
        // Determinar si hay un especialista seleccionado para filtrar
        let specialistId = filterSpecialistId;
        
        // Si no hay filterSpecialistId manual, miramos el primer select de especialista en el modal
        if (!specialistId) {
            const firstSp = document.querySelector('.specialist-select');
            if (firstSp) specialistId = firstSp.value;
        }

        let filtered = allServices;
        
        // Filtrar por nombre
        if (query) {
            filtered = filtered.filter(s => s.package_name.toLowerCase().includes(query.toLowerCase()));
        }
        
        // Filtrar por especialista si hay uno seleccionado
        if (specialistId && specialistId !== "") {
            const sp = allSpecialists.find(s => s.id == specialistId);
            if (sp && sp.package_ids) {
                filtered = filtered.filter(s => sp.package_ids.includes(s.id));
            }
        }

        if (filtered.length > 0) {
            resultsDiv.innerHTML = `
                <div style="padding: 10px 15px; background: #f8fafc; font-size: 11px; font-weight: 700; color: #64748b; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; position: sticky; top: 0; z-index: 10;">
                    ${query ? 'RESULTADOS DE BÚSQUEDA' : 'SERVICIOS DISPONIBLES'}
                    <span style="cursor: pointer; color: #ef4444;" onclick="document.getElementById('quickServiceResults').style.display='none'">[Cerrar]</span>
                </div>
                <div style="max-height: 400px; overflow-y: auto;">
                    ${filtered.map(s => `
                        <div class="client-search-item" style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                            <div style="flex: 1;">
                                <div class="client-search-name" style="font-size: 14px; font-weight: 700; color: #1e293b;">${s.package_name}</div>
                                <div class="client-search-info" style="color: #64748b;">$${new Intl.NumberFormat('es-CO').format(s.package_price)} | ${s.package_time} min</div>
                            </div>
                            <button type="button" 
                                    onclick="quickAddService(${s.id}, '${s.package_name}'); event.stopPropagation();" 
                                    class="btn-add-pill">
                                + AÑADIR
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
            resultsDiv.style.display = 'block';
            resultsDiv.style.zIndex = '3000';
            resultsDiv.style.boxShadow = '0 25px 60px rgba(0,0,0,0.4)';
        } else {
            if (query || specialistId) {
                resultsDiv.innerHTML = '<div style="padding: 20px; text-align: center; color: #94a3b8;">No se encontraron servicios para este especialista</div>';
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.style.display = 'none';
            }
        }
    }

    function toggleQuickServices() {
        const div = document.getElementById('quickServiceResults');
        if (div.style.display === 'block') {
            div.style.display = 'none';
        } else {
            searchServicesQuick('');
        }
    }

    function quickAddService(id, name) {
        // Priorizar el especialista del filtro actual
        let defaultSpecialist = filterSpecialistId;
        
        // Si no hay filtro manual, obtener el de la primera fila
        if (!defaultSpecialist) {
            const firstRow = document.querySelector('.service-item');
            if (firstRow) {
                const spSelect = firstRow.querySelector('.specialist-select');
                if (spSelect) defaultSpecialist = spSelect.value;
            }
        }

        // Si hay una fila vacía (sin servicio seleccionado), la llenamos
        const rows = document.querySelectorAll('.service-item');
        let filled = false;

        rows.forEach(row => {
            const select = row.querySelector('.service-select');
            const spSelect = row.querySelector('.specialist-select');
            
            if (!select.value && !filled) {
                select.value = id;
                // Usar el especialista de la primera fila
                if (spSelect && defaultSpecialist) {
                    spSelect.value = defaultSpecialist;
                }
                updateServiceDuration(select);
                filled = true;
            }
        });

        if (!filled) {
            // Agregar nueva fila con el especialista sticky
            addServiceRow(id, stickySpecialistId || defaultSpecialist);
        }

        // MANTENER FOCO Y MENÚ ABIERTO para "eficacia"
        const searchInput = document.getElementById('quickServiceSearch');
        searchInput.focus();
        
        // Pequeño feedback visual
        showToast(`Añadido: ${name}`, 'success');
        
        // IMPORTANTE: No ocultar el menú (ya lo manejamos por CSS o no llamando a hide)
    }


    function removeServiceRow(index) {
        const item = document.querySelector(`.service-item[data-index="${index}"]`);
        if (item) {
            item.remove();
            updateServiceNumbers();
        }
    }

    function updateServiceNumbers() {
        const items = document.querySelectorAll('#servicesList .service-item');
        items.forEach((item, i) => {
            item.querySelector('.service-item-num').textContent = i + 1;
        });
    }

    function updateServiceDuration(select) {
        // Can be used to show duration info
        checkConflicts();
    }

    function checkConflicts() {
        const date = document.getElementById('aptDate').value;
        const time = document.getElementById('aptTime').value;
        const currentId = document.getElementById('currentAptId').value;
        
        if (!date || !time) return;
        
        const [startH, startM] = time.split(':').map(Number);
        let currentMinutes = startH * 60 + startM;
        
        let allConflicts = [];
        
        const rows = document.querySelectorAll('.service-item');
        rows.forEach(item => {
            const serviceSelect = item.querySelector('.service-select');
            const specialistSelect = item.querySelector('.specialist-select');
            
            if (!specialistSelect || !serviceSelect) return;

            const specialistId = specialistSelect.value;
            const specialistName = specialistSelect.options[specialistSelect.selectedIndex].text;
            
            if (!serviceSelect.value) {
                // Si no hay servicio en esta fila, seguimos sumando un tiempo default si hubiera más filas
                currentMinutes += 60;
                return; 
            }

            const opt = serviceSelect.options[serviceSelect.selectedIndex];
            const customDurationInput = item.querySelector('.custom-duration');
            const customDuration = customDurationInput ? parseInt(customDurationInput.value) : 0;
            const duration = customDuration || ((opt && opt.dataset.duration) ? parseInt(opt.dataset.duration) : 60);
            
            const slotStart = currentMinutes;
            const slotEnd = currentMinutes + duration;
            
            if (specialistId) {
                appointments.forEach(apt => {
                    if (currentId && apt.id == currentId) return;
                    if (apt.specialist_id != specialistId) return;
                    
                    const [aptStartH, aptStartM] = apt.start_time.split(':').map(Number);
                    const [aptEndH, aptEndM] = apt.end_time.split(':').map(Number);
                    const aptStart = aptStartH * 60 + aptStartM;
                    const aptEnd = aptEndH * 60 + aptEndM;
                    
                    if (slotStart < aptEnd && slotEnd > aptStart) {
                        allConflicts.push(`${specialistName} tiene cita de ${apt.start_time} a ${apt.end_time}`);
                    }
                });
            }
            
            currentMinutes += duration;
        });

        const warning = document.getElementById('conflictWarning');
        const message = document.getElementById('conflictMessage');
        
        if (allConflicts.length > 0) {
            if (message) message.textContent = [...new Set(allConflicts)].join(', ');
            warning.classList.add('show');
        } else {
            warning.classList.remove('show');
        }
    }

    function openNewAppointment() {
        document.getElementById('currentAptId').value = '';
        document.getElementById('modalTitle').textContent = 'Nueva Cita';
        document.getElementById('aptCustomer').value = '';
        document.getElementById('clientSearchInput').value = '';
        toggleNewClientForm(false);
        document.getElementById('aptDate').value = formatDate(currentDate);
        document.getElementById('aptTime').value = '09:00';
        document.getElementById('aptNotes').value = '';
        document.getElementById('aptStatus').value = 'pendiente';
        document.getElementById('sendEmail').checked = true;
        document.getElementById('sendWhatsapp').checked = true;
        document.getElementById('btnDeleteApt').style.display = 'none';
        document.getElementById('conflictWarning').classList.remove('show');
        filterSpecialistId = null; // Reset filter
        stickySpecialistId = null; 
        document.getElementById('globalStickySpecialist').value = '';
        
        // Reset services list
        document.getElementById('servicesList').innerHTML = '';
        serviceCounter = 0;
        addServiceRow();
        
        document.getElementById('appointmentModal').classList.add('show');
    }

    function openNewAppointmentAt(specialistId, hour) {
        try {
            console.log('Opening appointment at:', specialistId, hour);
            openNewAppointment();
            filterSpecialistId = specialistId; // Set filter for search
            document.getElementById('aptTime').value = String(hour).padStart(2, '0') + ':00';
            
            // Set specialist in first service row
            setTimeout(() => {
                stickySpecialistId = specialistId;
                document.getElementById('globalStickySpecialist').value = specialistId;
                const firstSpecialist = document.querySelector('.specialist-select');
                if (firstSpecialist) {
                    firstSpecialist.value = specialistId;
                    console.log('Set specialist:', specialistId);
                    checkConflicts(); 
                }
            }, 100);
        } catch(err) {
            console.error('Error opening appointment:', err);
            alert('Error al abrir formulario: ' + err.message);
        }
    }

    function editAppointment(apt) {
        document.getElementById('currentAptId').value = apt.id;
        document.getElementById('modalTitle').textContent = 'Editar Cita';
        document.getElementById('aptCustomer').value = apt.customer_id || '';
        document.getElementById('clientSearchInput').value = apt.customer_name || '';
        toggleNewClientForm(false);
        document.getElementById('aptDate').value = apt.date;
        document.getElementById('aptTime').value = apt.start_time;
        document.getElementById('aptNotes').value = apt.notes || '';
        document.getElementById('aptStatus').value = apt.status || 'pendiente';
        document.getElementById('btnDeleteApt').style.display = 'block';
        document.getElementById('conflictWarning').classList.remove('show');
        filterSpecialistId = apt.specialist_id; // Set filter for search
        stickySpecialistId = apt.specialist_id;
        document.getElementById('globalStickySpecialist').value = apt.specialist_id || '';
        
        // Load services
        document.getElementById('servicesList').innerHTML = '';
        serviceCounter = 0;
        addServiceRow(apt.service_id, apt.specialist_id, apt.duration);
        
        document.getElementById('appointmentModal').classList.add('show');
    }

    function closeModal() {
        document.getElementById('appointmentModal').classList.remove('show');
    }

    function saveAppointment() {
        const id = document.getElementById('currentAptId').value;
        
        // Gather services
        const services = [];
        document.querySelectorAll('.service-item').forEach(item => {
            const serviceId = item.querySelector('.service-select').value;
            const specialistId = item.querySelector('.specialist-select').value;
            const customDuration = item.querySelector('.custom-duration').value;
            if (serviceId && specialistId) {
                services.push({ 
                    service_id: serviceId, 
                    specialist_id: specialistId,
                    duration: customDuration || null 
                });
            }
        });
        
        if (services.length === 0) {
            showToast('Agregue al menos un servicio', 'error');
            return;
        }
        
        const data = {
            _token: '{{ csrf_token() }}',
            customer_id: document.getElementById('aptCustomer').value,
            date: document.getElementById('aptDate').value,
            start_time: document.getElementById('aptTime').value,
            notes: document.getElementById('aptNotes').value,
            status: document.getElementById('aptStatus').value,
            services: services,
            send_email: document.getElementById('sendEmail').checked,
            send_whatsapp: document.getElementById('sendWhatsapp').checked
        };

        // Si es cliente nuevo, recolectar sus datos
        if (data.customer_id === 'NEW') {
            data.new_customer = {
                first_name: document.getElementById('newClientFirstName').value,
                last_name: document.getElementById('newClientLastName').value,
                contact_number: document.getElementById('newClientPhone').value,
                email: document.getElementById('newClientEmail').value,
                identification: document.getElementById('newClientDoc').value
            };
            if (!data.new_customer.first_name || !data.new_customer.contact_number) {
                showToast('Complete los datos del nuevo cliente', 'error');
                return;
            }
        }

        if (!data.customer_id || !data.date || !data.start_time) {
            showToast('Complete los campos obligatorios', 'error');
            return;
        }

        const url = id 
            ? '{{ url("admin/appointments/update") }}'
            : '{{ url("admin/appointments/store") }}';
        
        if (id) data.id = id;

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                let msg = id ? 'Cita actualizada' : 'Cita creada';
                if (result.whatsapp_url) {
                    msg += ' • Abriendo WhatsApp...';
                    window.open(result.whatsapp_url, '_blank');
                }
                showToast(msg, 'success');
                closeModal();
                loadAppointments();
            } else {
                showToast(result.error || 'Error', 'error');
            }
        })
        .catch(err => showToast('Error de conexión', 'error'));
    }

    function deleteAppointment() {
        const id = document.getElementById('currentAptId').value;
        if (!id) return;
        
        // Cerrar modal principal para evitar superposición
        closeModal();
        
        // Get client name from current appointment
        const apt = appointments.find(a => a.id == id);
        const clientName = apt ? apt.customer_name : 'Cliente';
        
        // Show custom modal instead of browser confirm
        document.getElementById('deleteClientName').textContent = clientName;
        
        // Reset checkbox si existe (lo añadiré en el HTML si es necesario o asumiré true)
        const notifyCheck = document.getElementById('notify_client_delete');
        if (notifyCheck) notifyCheck.checked = true;

        document.getElementById('deleteModalOverlay').classList.add('show');
    }

    function cancelDelete() {
        document.getElementById('deleteModalOverlay').classList.remove('show');
    }

    function executeDelete() {
        const id = document.getElementById('currentAptId').value;
        if (!id) return;
        
        const notify = document.getElementById('notify_client_delete')?.checked ? 1 : 0;
        document.getElementById('deleteModalOverlay').classList.remove('show');

        fetch('{{ url("admin/appointments/delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ _token: '{{ csrf_token() }}', id: id, notify_client: notify })
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                showToast('Cita cancelada - Horario disponible', 'success');
                closeModal();
                loadAppointments();
            } else {
                showToast(result.error || 'Error', 'error');
            }
        })
        .catch(err => showToast('Error de conexión', 'error'));
    }

    function updateCurrentTimeLine() {
        const now = new Date();
        const viewingStr = formatDate(currentDate);
        const todayStr = formatDate(now);
        
        // Remove existing lines
        document.querySelectorAll('.current-time-line').forEach(el => el.remove());
        
        // Only show if we are looking at TODAY
        if (viewingStr !== todayStr) return;

        const currentHour = now.getHours();
        const currentMin = now.getMinutes();
        
        if (currentHour >= 7 && currentHour <= 21) {
            const topPos = (currentHour - 7) * 80 + (currentMin / 60) * 80;
            
            document.querySelectorAll('.sp-column').forEach(col => {
                const line = document.createElement('div');
                line.className = 'current-time-line';
                line.style.top = topPos + 'px';
                line.innerHTML = '<div class="current-time-dot"></div>';
                col.appendChild(line);
            });
            console.log("Timeline updated at:", topPos);
        }
    }

    document.getElementById('appointmentModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // =============================================
    // DRAG & DROP SYSTEM
    // =============================================
    // Variables already declared at top: draggedAppointment, pendingMove
    let draggedElement = null;
    let ghostPreview = null;

    // Initialize drag & drop after appointments are rendered
    function initDragAndDrop() {
        // Make appointment blocks draggable
        document.querySelectorAll('.appointment-block').forEach(block => {
            block.setAttribute('draggable', 'true');
            
            block.addEventListener('dragstart', handleDragStart);
            block.addEventListener('dragend', handleDragEnd);
        });

        // Make columns drop targets
        document.querySelectorAll('.sp-column').forEach(column => {
            column.addEventListener('dragover', handleDragOver);
            column.addEventListener('dragleave', handleDragLeave);
            column.addEventListener('drop', handleDrop);
        });
    }

    function handleDragStart(e) {
        const aptId = this.getAttribute('data-id'); // Cambio crítico: usar el atributo data-id real
        if (!aptId) return;
        
        draggedAppointment = appointments.find(a => a.id == aptId);
        draggedElement = this;
        
        this.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', aptId);
        
        // Hide details card during drag
        hideDetailsCard();
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedElement = null;
        
        // Remove all drag-over states
        document.querySelectorAll('.sp-column').forEach(col => {
            col.classList.remove('drag-over');
        });
        
        // Remove ghost preview
        if (ghostPreview) {
            ghostPreview.remove();
            ghostPreview = null;
        }
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        
        const column = this;
        const targetSpecialistId = parseInt(column.dataset.specialistId);
        
        // Check if specialist can perform this service
        if (draggedAppointment && !canSpecialistPerformService(targetSpecialistId, draggedAppointment.service_id)) {
            e.dataTransfer.dropEffect = 'none';
            return;
        }
        
        column.classList.add('drag-over');
        
        // Calculate and show ghost preview
        const rect = column.getBoundingClientRect();
        const y = e.clientY - rect.top + column.scrollTop;
        const slotMinutes = Math.floor(y / 80) * 60 + Math.floor((y % 80) / 80 * 60);
        const roundedMinutes = Math.round(slotMinutes / 15) * 15; // Round to 15 min
        const topPos = (roundedMinutes / 60) * 80;
        
        if (!ghostPreview) {
            ghostPreview = document.createElement('div');
            ghostPreview.className = 'ghost-preview';
            column.appendChild(ghostPreview);
        }
        
        const duration = draggedAppointment ? draggedAppointment.duration : 60;
        const height = (duration / 60) * 80;
        
        ghostPreview.style.top = topPos + 'px';
        ghostPreview.style.height = height + 'px';
        
        const hours = 7 + Math.floor(roundedMinutes / 60);
        const mins = roundedMinutes % 60;
        ghostPreview.textContent = `${String(hours).padStart(2,'0')}:${String(mins).padStart(2,'0')}`;
    }

    function handleDragLeave(e) {
        this.classList.remove('drag-over');
        if (ghostPreview && ghostPreview.parentElement === this) {
            ghostPreview.remove();
            ghostPreview = null;
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        
        const column = this;
        column.classList.remove('drag-over');
        
        if (!draggedAppointment) return;
        
        const targetSpecialistId = parseInt(column.dataset.specialistId);
        const targetSpecialistName = column.dataset.specialistName || 'Especialista';
        
        // Validate service authorization
        if (!canSpecialistPerformService(targetSpecialistId, draggedAppointment.service_id)) {
            showToast(`${targetSpecialistName} no puede realizar "${draggedAppointment.service_name}"`, 'error');
            return;
        }
        
        // Calculate new time from drop position
        const rect = column.getBoundingClientRect();
        const y = e.clientY - rect.top + column.scrollTop;
        const slotMinutes = Math.floor(y / 80) * 60 + Math.floor((y % 80) / 80 * 60);
        const roundedMinutes = Math.round(slotMinutes / 15) * 15;
        
        const newHour = 7 + Math.floor(roundedMinutes / 60);
        const newMin = roundedMinutes % 60;
        const newTime = `${String(newHour).padStart(2,'0')}:${String(newMin).padStart(2,'0')}`;
        
        // Get original specialist name
        const originalSpecialist = allSpecialists.find(s => s.id == draggedAppointment.specialist_id);
        const originalSpecialistName = originalSpecialist ? originalSpecialist.name : 'Anterior';
        
        // Prepare move data
        pendingMove = {
            appointmentId: draggedAppointment.id,
            clientName: draggedAppointment.customer_name,
            serviceName: draggedAppointment.service_name,
            fromSpecialist: originalSpecialistName,
            fromTime: draggedAppointment.start_time,
            toSpecialistId: targetSpecialistId,
            toSpecialistName: targetSpecialistName,
            toTime: newTime
        };
        
        // Show confirmation modal
        showMoveModal(pendingMove);
        
        // Clean up ghost
        if (ghostPreview) {
            ghostPreview.remove();
            ghostPreview = null;
        }
    }

    function canSpecialistPerformService(specialistId, serviceId) {
        const specialist = allSpecialists.find(s => s.id == specialistId);
        if (!specialist) return false;
        
        // Check services_json array
        if (specialist.services_json && Array.isArray(specialist.services_json)) {
            if (specialist.services_json.includes(Number(serviceId)) || 
                specialist.services_json.includes(String(serviceId))) {
                return true;
            }
        }
        
        // Check packages relationship (if available)
        if (specialist.packages && Array.isArray(specialist.packages)) {
            if (specialist.packages.some(p => p.id == serviceId)) {
                return true;
            }
        }
        
        // Fallback: allow if no restriction data (graceful degradation)
        if (!specialist.services_json && !specialist.packages) {
            return true;
        }
        
        return false;
    }

    function showMoveModal(moveData) {
        document.getElementById('moveClientName').textContent = moveData.clientName;
        document.getElementById('moveFromInfo').textContent = 
            `${moveData.fromSpecialist} a las ${moveData.fromTime}`;
        document.getElementById('moveToInfo').textContent = 
            `${moveData.toSpecialistName} a las ${moveData.toTime}`;
        
        // Reset notification checkbox (unchecked by default)
        document.getElementById('moveNotifyClient').checked = false;
        
        document.getElementById('moveModalOverlay').classList.add('show');
    }

    function cancelMove() {
        document.getElementById('moveModalOverlay').classList.remove('show');
        pendingMove = null;
        draggedAppointment = null;
    }

    function confirmMove() {
        if (!pendingMove) return;
        
        const notifyClient = document.getElementById('moveNotifyClient').checked;
        
        // Close modal immediately
        document.getElementById('moveModalOverlay').classList.remove('show');
        
        // Send update to server
        fetch('{{ url("admin/appointments/move") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}',
                appointment_id: pendingMove.appointmentId,
                new_specialist_id: pendingMove.toSpecialistId,
                new_time: pendingMove.toTime,
                duration: draggedAppointment ? draggedAppointment.duration : null, // MANTENER DURACIÓN AL MOVER
                notify_client: notifyClient
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error('HTTP ' + res.status);
            }
            return res.json();
        })
        .then(result => {
            if (result.success) {
                let msg = 'Cita movida correctamente - Horario anterior liberado';
                if (notifyClient && result.notification_sent) {
                    msg += ' • Cliente notificado';
                }
                showToast(msg, 'success');
                loadAppointments();
            } else {
                showToast(result.error || 'Error al mover la cita', 'error');
            }
        })
        .catch(err => {
            console.error('Move error:', err);
            showToast('Error: ' + err.message, 'error');
        });
        
        pendingMove = null;
        draggedAppointment = null;
    }

    // Override renderAppointments to add drag functionality
    const originalRenderAppointments = renderAppointments;
    renderAppointments = function() {
        originalRenderAppointments();
        
        // Add data-apt-id to each block for drag reference
        appointments.forEach(apt => {
            const column = document.querySelector(`.sp-column[data-specialist-id="${apt.specialist_id}"]`);
            if (!column) return;
            
            const blocks = column.querySelectorAll('.appointment-block');
            blocks.forEach(block => {
                // Match by client name and time (temporary until we add data-id)
                if (block.querySelector('.apt-client')?.textContent === apt.customer_name &&
                    block.querySelector('.apt-time')?.textContent.includes(apt.start_time)) {
                    block.dataset.aptId = apt.id;
                }
            });
        });
        
        // Initialize drag handlers
        initDragAndDrop();
    };

    // --- LÓGICA DE REDIMENSIÓN ---
    let isResizing = false;
    let resizeAptId = null;
    let startY, startHeight, currentApt, nextAptY;

    window.startResizing = function(e, id) {
        if (e) e.stopPropagation();
        isResizing = true;
        resizeAptId = id;
        startY = e.pageY;
        
        const element = document.getElementById(`apt-${id}`);
        if (!element) return;
        
        startHeight = parseInt(element.style.height);
        currentApt = appointments.find(a => a.id == id);
        
        // Buscar la siguiente cita en el mismo especialista para limitar el "bajar la cortina"
        const column = element.parentElement;
        const otherApts = Array.from(column.querySelectorAll('.appointment-block'))
            .filter(el => el.id !== `apt-${id}`)
            .map(el => ({
                top: parseInt(el.style.top),
                height: parseInt(el.style.height)
            }))
            .filter(a => a.top >= parseInt(element.style.top) + startHeight)
            .sort((a, b) => a.top - b.top);
            
        nextAptY = otherApts.length > 0 ? otherApts[0].top : 1200; // Limite del calendario si no hay más citas
        
        document.addEventListener('mousemove', handleResize);
        document.addEventListener('mouseup', stopResizing);
        element.classList.add('resizing');
        document.body.style.cursor = 'ns-resize';
    };

    function handleResize(e) {
        if (!isResizing) return;
        const element = document.getElementById(`apt-${resizeAptId}`);
        const delta = e.pageY - startY;
        
        let newHeight = Math.max(30, startHeight + delta);
        const top = parseInt(element.style.top);
        
        // No pasar de la siguiente cita
        if (top + newHeight > nextAptY) {
            newHeight = nextAptY - top;
        }
        
        // Snapping suave a 5 minutos (1.33px por min si 80px = 60min, o aprox 6.6px por 5 min)
        // Para simplificar, usaremos 15 min (20px) como base pero permitiremos el arrastre fluido
        // Si el cliente quiere "usar tiempo real", calculamos el tiempo dinámicamente
        const snappedHeight = Math.round(newHeight / 6.66) * 6.66; // Snap a 5 min aprox
        
        element.style.height = `${snappedHeight}px`;
        
        // Actualizar etiqueta de tiempo y el input oculto si el modal está abierto
        const duration = Math.round(snappedHeight * 60 / 80);
        
        // Si el modal de esa cita está abierto, actualizar su custom-duration
        const modalAptId = document.getElementById('currentAptId').value;
        if (modalAptId == resizeAptId) {
            const firstDurationInput = document.querySelector('.service-item .custom-duration');
            if (firstDurationInput) firstDurationInput.value = duration;
        }

        const timeEl = element.querySelector('.apt-time');
        if (timeEl && currentApt) {
            const [h, m] = currentApt.start_time.split(':').map(Number);
            const startTotal = h * 60 + m;
            const endTotal = startTotal + duration;
            const endH = Math.floor(endTotal / 60);
            const endM = endTotal % 60;
            const endTimeStr = `${String(endH).padStart(2,'0')}:${String(endM).padStart(2,'0')}`;
            timeEl.textContent = `${currentApt.start_time} - ${endTimeStr}`;
        }
    }

    function stopResizing() {
        if (!isResizing) return;
        isResizing = false;
        document.body.style.cursor = 'default';
        const element = document.getElementById(`apt-${resizeAptId}`);
        if (element) {
            element.classList.remove('resizing');
            const newHeight = parseInt(element.style.height);
            const newDuration = Math.round(newHeight * 60 / 80);
            
            fetch('{{ url("admin/appointments/resize") }}', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: resizeAptId, duration: newDuration })
            }).then(res => res.json()).then(data => {
                if (!data.success) { 
                    Swal.fire('Error', data.error || 'No se pudo actualizar', 'error'); 
                    loadAppointments(); 
                } else { 
                    loadAppointments(); 
                }
            });
        }
        document.removeEventListener('mousemove', handleResize);
        document.removeEventListener('mouseup', stopResizing);
    }
</script>

@endsection