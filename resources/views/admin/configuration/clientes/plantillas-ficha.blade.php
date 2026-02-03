@extends('admin.configuration._layout')

@section('config_title', 'Plantillas de Ficha Técnica')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Plantillas de Ficha Técnica</h3>
            
            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Plantilla de Ficha Técnica (Texto/Markdown)</label>
                <textarea name="technical_sheet_template" rows="10" class="form-control" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;" placeholder="**Antecedentes Médicos:**
- Alergias:
- Medicamentos:

**Observaciones de Piel:**
- Tipo:
- Sensibilidad:">{{ $settings['technical_sheet_template'] ?? '' }}</textarea>
                <small style="color: #6b7280; font-size: 12px;">Esta estructura se cargará automáticamente al crear una nueva ficha para un cliente.</small>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                 <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="force_technical_sheet" value="1" {{ ($settings['force_technical_sheet'] ?? '') == '1' ? 'checked' : '' }}>
                    <span style="font-size: 14px; color: #4b5563;">Hacer obligatoria la ficha técnica para servicios específicos</span>
                </label>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection