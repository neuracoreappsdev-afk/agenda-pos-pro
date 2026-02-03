@extends('admin/dashboard_layout')

@section('content')
<div class="app-main-layout">
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ trans('messages.plans_title') }}</h1>
            <div class="breadcrumb">
                <a href="{{ url('admin/dashboard') }}">{{ trans('messages.home') }}</a> / 
                <a href="{{ url('admin/ventas') }}">{{ trans('messages.sales') }}</a> / 
                {{ trans('messages.plans_title') }}
            </div>
        </div>
        <div>
             <button class="btn btn-primary" onclick="alert('Funcionalidad de Planes en desarrollo')">
                <span>+</span> {{ trans('messages.new') }} Plan
            </button>
        </div>
    </div>

    <!-- Empty State -->
    <div class="content-card" style="text-align: center; padding: 60px 20px;">
         <div style="font-size: 48px; margin-bottom: 20px;">📅</div>
         <h2 style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 10px;">Gestión de Planes y Suscripciones</h2>
         <p style="color: #6b7280; max-width: 500px; margin: 0 auto 30px;">
             Aquí podrás administrar los paquetes de sesiones y suscripciones recurrentes de tus clientes.
         </p>
         <button class="btn btn-outline" style="border: 1px solid #d1d5db; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">
             Configurar Tipos de Planes
         </button>
    </div>
</div>

<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #111827; margin: 0; }
    .breadcrumb { color: #6b7280; font-size: 13px; margin-top: 5px; }
    .breadcrumb a { color: #1a73e8; text-decoration: none; }
    .content-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .btn-primary { background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
</style>
@endsection
