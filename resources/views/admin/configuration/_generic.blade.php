{{-- Vista genérica para configuraciones simples --}}
@extends('admin.configuration._layout')

@section('config_title', $title ?? 'Configuración')

@section('config_content')
<form action="{{ $action ?? url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">{{ $section_title ?? 'Configuración' }}</h3>
            
            {{-- Mensaje informativo --}}
            <div style="padding: 15px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: #1e40af; font-size: 14px;">
                    📝 {{ $description ?? 'Configure las opciones según las necesidades de su negocio.' }}
                </p>
            </div>

            {{-- Contenido dinámico --}}
            @yield('form_fields')
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection
