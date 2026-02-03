@extends('admin/dashboard_layout')

@section('content')

<style>
    .config-page {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .config-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .config-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 10px 0;
    }
    
    .config-breadcrumb {
        color: #6b7280;
        font-size: 14px;
    }
    
    .config-breadcrumb a {
        color: #1a73e8;
        text-decoration: none;
    }
    
    .config-breadcrumb a:hover {
        text-decoration: underline;
    }
    
    .config-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    
    .config-section {
        margin-bottom: 30px;
    }
    
    .config-section:last-child {
        margin-bottom: 0;
    }
    
    .config-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 20px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
        font-size: 14px;
    }
    
    .form-group .help-text {
        display: block;
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    select.form-control {
        cursor: pointer;
    }
    
    .btn-group {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-primary {
        background: #1a73e8;
        color: white;
    }
    
    .btn-primary:hover {
        background: #1557b0;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    
    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #6ee7b7;
    }
    
    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }
    
    .switch-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 26px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #1a73e8;
    }
    
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    /* Data Tables Premium */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .data-table th {
        text-align: left;
        padding: 12px 15px;
        background: #f9fafb;
        color: #4b5563;
        font-weight: 600;
        font-size: 13px;
        border-bottom: 1px solid #e5e7eb;
    }
    .data-table td {
        padding: 15px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    /* Global Modals Premium */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        overflow-y: auto;
        padding: 40px 20px;
        align-items: flex-start;
        justify-content: center;
        backdrop-filter: blur(4px);
    }
    .modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
        padding: 0;
        overflow: hidden;
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f9fafb;
    }
    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #9ca3af;
        cursor: pointer;
    }
    .modal-body {
        padding: 25px;
    }
    
    /* Action Buttons */
    .btn-edit {
        background: #f3f4f6;
        color: #4b5563;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-edit:hover {
        background: #e5e7eb;
        color: #111827;
    }
</style>

<div class="config-page">
    <div class="config-header">
        <div class="config-breadcrumb">
            <a href="{{ url('admin/dashboard') }}">Dashboard</a> / 
            <a href="{{ url('admin/configuration') }}">Configuraciones</a> / 
            <span>@yield('config_title', 'Configuración')</span>
        </div>
        <h1>@yield('config_title', 'Configuración')</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Error:</strong>
            <ul style="margin:5px 0 0 20px; padding:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('config_content')
</div>

@endsection
