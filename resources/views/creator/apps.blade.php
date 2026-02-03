@extends('creator.layout')

@section('content')
<div class="animate-fade">
    <div style="margin-bottom: 2rem; display:flex; justify-content:space-between; align-items:flex-end;">
        <div>
            <h1 style="font-size:24px; font-weight:700; color:#111827; letter-spacing:-0.5px;">Catálogo de Aplicaciones</h1>
            <p style="color:#6b7280; font-size:14px; margin-top:4px;">Gestión de branding y configuración visual para cada inquilino.</p>
        </div>
        <button class="btn-primary">
            <i data-lucide="plus" style="width:18px;"></i> Nueva App
        </button>
    </div>

    @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:16px; border-radius:12px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
        <i data-lucide="check-circle" style="width:20px;"></i>
        {{ session('success') }}
    </div>
    @endif

    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap:24px;">
        @foreach($apps as $app)
        <div class="std-card">
            <form action="{{ url('creator/apps/update/' . $app->id) }}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:20px; border-bottom:1px solid #f3f4f6; padding-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="width:56px; height:56px; background:{{ $app->primary_color }}; border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
                            <i data-lucide="{{ $app->icon }}" style="width:28px; height:28px;"></i>
                        </div>
                        <div>
                            <input type="text" name="name" value="{{ $app->name }}" style="font-weight:700; font-size:18px; color:#111827; border:none; padding:0; width:100%; outline:none; background:transparent;">
                            <div style="font-size:13px; color:#6b7280; margin-top:2px;">slug: {{ $app->slug }}</div>
                        </div>
                    </div>
                    <div>
                        <span class="badge badge-success">ACTIVA</span>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div>
                        <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px;">Color Primario</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:36px; height:36px; border-radius:6px; background:{{ $app->primary_color }}; border:1px solid #e5e7eb;"></div>
                            <input type="text" name="primary_color" value="{{ $app->primary_color }}" style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:8px; font-size:13px; color:#374151;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px;">Color Secundario</label>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:36px; height:36px; border-radius:6px; background:{{ $app->secondary_color }}; border:1px solid #e5e7eb;"></div>
                            <input type="text" name="secondary_color" value="{{ $app->secondary_color }}" style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:8px; font-size:13px; color:#374151;">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:6px;">Fuente (Google Fonts)</label>
                    <input type="text" name="font_family" value="{{ $app->font_family }}" style="width:100%; border:1px solid #d1d5db; border-radius:6px; padding:8px 12px; font-size:13px; color:#374151;" placeholder="Ej: Outfit">
                </div>

                <div style="display:flex; gap:12px; border-top:1px solid #f3f4f6; padding-top:16px;">
                    <button type="submit" class="btn-primary" style="flex:1; justify-content:center;">
                        Guardar Cambios
                    </button>
                    <button type="button" style="padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; background:white; color:#374151;">
                        <i data-lucide="eye"></i>
                    </button>
                </div>
            </form>
        </div>
        @endforeach

        <!-- Add New Card -->
        <div class="std-card" style="border:2px dashed #e5e7eb; box-shadow:none; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; min-height:300px; background:#f9fafb;">
            <div style="width:56px; height:56px; border-radius:50%; background:white; display:flex; align-items:center; justify-content:center; margin-bottom:16px; border:1px solid #e5e7eb;">
                <i data-lucide="plus" style="color:#9ca3af; width:28px; height:28px;"></i>
            </div>
            <div style="font-weight:600; color:#4b5563; font-size:16px;">Crear Nueva App</div>
            <div style="font-size:13px; color:#9ca3af; margin-top:4px;">Definir nuevo producto de software</div>
        </div>
    </div>
</div>
@endsection
