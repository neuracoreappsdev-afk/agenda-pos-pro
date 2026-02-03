@extends('admin.configuration._layout')

@section('config_title', 'Detalles del Negocio')

@section('config_content')

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #065f46;">
    ✓ {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background: #fee2e2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #991b1b;">
    @foreach($errors->all() as $error)
        <div>• {{ $error }}</div>
    @endforeach
</div>
@endif

<form action="{{ url('admin/configuration/business-details') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div style="text-align: center; margin-bottom: 30px;">
            @if(isset($business_logo) && $business_logo)
                <img id="logo_preview" src="{{ asset($business_logo) }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
            @else
                <div id="logo_placeholder" style="width: 120px; height: 120px; margin: 0 auto 15px; background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px;">
                    🏢
                </div>
            @endif
            <input type="file" name="business_logo" id="business_logo" accept="image/*" capture="environment" style="display: none;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('business_logo').click()">📸 Cambiar Logo (Cámara/Archivo)</button>
            
            <!-- Descubrimiento de Archivos / Recuperación (Demo de Acceso) -->
            <div style="margin-top: 15px; font-size: 0.9em; color: #666; border-top: 1px dashed #ccc; padding-top: 10px;">
                <label style="cursor: pointer; color: #2563eb; display: inline-flex; align-items: center; gap: 5px;">
                    <input type="file" webkitdirectory directory multiple style="display: none;" onchange="alert('📁 Acceso concedido a la carpeta: ' + this.files.length + ' archivos detectados para análisis (Simulación).')">
                    🔍 Escanear Carpeta Local (Recuperación)
                </label>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Nombre del Negocio *</label>
                <input type="text" class="form-control" name="business_name" value="{{ old('business_name', $business_name ?? '') }}" required>
            </div>

            <div class="form-group">
                <label>Tipo de Negocio *</label>
                <select class="form-control" name="business_type" required>
                    <option value="belleza" {{ (old('business_type', $business_type ?? 'belleza') == 'belleza') ? 'selected' : '' }}>💅 Belleza (Peluquería, Barbería, Estética)</option>
                    <option value="salud" {{ (old('business_type', $business_type ?? '') == 'salud') ? 'selected' : '' }}>⚕️ Salud (Odontología, Psicología, Medicina)</option>
                    <option value="taller" {{ (old('business_type', $business_type ?? '') == 'taller') ? 'selected' : '' }}>🔧 Automotriz (Taller Mecánico, Lavadero)</option>
                    <option value="bienestar" {{ (old('business_type', $business_type ?? '') == 'bienestar') ? 'selected' : '' }}>🧘 Bienestar (Spa, Yoga, Masajes)</option>
                    <option value="hospedaje" {{ (old('business_type', $business_type ?? '') == 'hospedaje') ? 'selected' : '' }}>🏨 Hospedaje (Hotel, Hostal, Airbnb)</option>
                    <option value="servicios" {{ (old('business_type', $business_type ?? '') == 'servicios') ? 'selected' : '' }}>💼 Servicios Profesionales (Abogados, Contadores)</option>
                    <option value="otro" {{ (old('business_type', $business_type ?? '') == 'otro') ? 'selected' : '' }}>🌐 Otro</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>NIT/RUT *</label>
                <input type="text" class="form-control" name="business_nit" value="{{ old('business_nit', $business_nit ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label>Email Principal *</label>
                <input type="email" class="form-control" name="business_email" value="{{ old('business_email', $business_email ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label>Teléfono Principal *</label>
                <input type="tel" class="form-control" name="business_phone" value="{{ old('business_phone', $business_phone ?? '') }}" required>
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Dirección Completa *</label>
                <input type="text" class="form-control" name="business_address" value="{{ old('business_address', $business_address ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label>Ciudad *</label>
                <input type="text" class="form-control" name="business_city" value="{{ old('business_city', $business_city ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label>Departamento/Estado *</label>
                <input type="text" class="form-control" name="business_state" value="{{ old('business_state', $business_state ?? '') }}" required>
            </div>
            
            <div class="form-group">
                <label>Código Postal</label>
                <input type="text" class="form-control" name="business_zip" value="{{ old('business_zip', $business_zip ?? '') }}">
            </div>
            
            <div class="form-group">
                <label>País *</label>
                <select class="form-control" name="business_country" required>
                    <option value="Colombia" {{ (old('business_country', $business_country ?? '') == 'Colombia') ? 'selected' : '' }}>Colombia</option>
                    <option value="México" {{ (old('business_country', $business_country ?? '') == 'México') ? 'selected' : '' }}>México</option>
                    <option value="Chile" {{ (old('business_country', $business_country ?? '') == 'Chile') ? 'selected' : '' }}>Chile</option>
                    <option value="Perú" {{ (old('business_country', $business_country ?? '') == 'Perú') ? 'selected' : '' }}>Perú</option>
                </select>
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Sitio Web</label>
                <input type="url" class="form-control" name="business_website" value="{{ old('business_website', $business_website ?? '') }}" placeholder="https://www.minegocio.com">
            </div>
            
            <div class="form-group" style="grid-column: 1 / -1;">
                <label>Descripción del Negocio</label>
                <textarea class="form-control" name="business_description" rows="3" placeholder="Describe brevemente tu negocio...">{{ old('business_description', $business_description ?? '') }}</textarea>
            </div>
        </div>

        <div style="border-top: 2px solid #e5e7eb; margin: 30px 0; padding-top: 30px;">
            <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 20px;">Redes Sociales</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="url" class="form-control" name="social_facebook" value="{{ old('social_facebook', $social_facebook ?? '') }}" placeholder="https://facebook.com/minegocio">
                </div>
                
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="url" class="form-control" name="social_instagram" value="{{ old('social_instagram', $social_instagram ?? '') }}" placeholder="https://instagram.com/minegocio">
                </div>
                
                <div class="form-group">
                    <label>Twitter/X</label>
                    <input type="url" class="form-control" name="social_twitter" value="{{ old('social_twitter', $social_twitter ?? '') }}" placeholder="https://twitter.com/minegocio">
                </div>
                
                <div class="form-group">
                    <label>WhatsApp Business</label>
                    <input type="tel" class="form-control" name="social_whatsapp" value="{{ old('social_whatsapp', $social_whatsapp ?? '') }}" placeholder="+57 310 123 4567">
                </div>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Cancelar</a>
        </div>

        <div style="border-top: 2px solid #e5e7eb; margin: 30px 0; padding-top: 30px;">
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px;">
                <h4 style="font-size: 16px; font-weight: 600; margin-bottom: 10px; color: #1e40af;">🤖 Conexión de IA con Redes (API)</h4>
                <p style="font-size: 13px; color: #3b82f6; margin-bottom: 20px;">
                    Configura estos tokens para permitir que tu Agente de Call Center responda automáticamente mensajes de Instagram Direct y Facebook Messenger.
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Facebook Page ID</label>
                        <input type="text" class="form-control" name="meta_page_id" value="{{ old('meta_page_id', $meta_page_id ?? '') }}" placeholder="Ej: 104567890123">
                    </div>

                    <div class="form-group">
                        <label>Instagram Business Account ID</label>
                        <input type="text" class="form-control" name="meta_instagram_id" value="{{ old('meta_instagram_id', $meta_instagram_id ?? '') }}" placeholder="Ej: 178414053098">
                    </div>
                    
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Meta Access Token (Larga Duración)</label>
                        <input type="password" class="form-control" name="meta_access_token" value="{{ old('meta_access_token', $meta_access_token ?? '') }}" placeholder="EAA...">
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
// Preview de logo al seleccionar archivo
document.getElementById('business_logo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('logo_preview');
            let placeholder = document.getElementById('logo_placeholder');
            
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'logo_preview';
                preview.style.cssText = 'width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;';
                
                if (placeholder) {
                    placeholder.parentNode.insertBefore(preview, placeholder);
                    placeholder.style.display = 'none';
                }
            }
            
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

// Mostrar mensaje temporal al guardar
document.querySelector('form').addEventListener('submit', function() {
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Guardando...';
});
</script>

@endsection
