@extends('admin.configuration._layout')

@section('config_title', 'Importar Servicios')

@section('config_content')
<form action="{{ url('admin/configuration/process-import-services') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Importar Servicios</h3>
            
            <div style="padding: 15px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: #1e40af; font-size: 14px;">
                    📝 Importe servicios desde archivos Excel/CSV con el siguiente formato: Nombre, Precio, Duración, Categoría.
                    <a href="#" style="font-weight: 700; text-decoration: underline;">Descargar plantilla</a>
                </p>
            </div>

            <div class="form-group" style="border: 2px dashed #d1d5db; padding: 40px; text-align: center; border-radius: 12px; background: #f9fafb;">
                <div style="font-size: 40px; margin-bottom: 10px;">💇‍♀️</div>
                <label for="file_import" style="cursor: pointer;">
                    <span id="file-label" style="display: block; font-weight: 600; color: #374151;">Seleccionar archivo CSV</span>
                    <span style="display: block; font-size: 12px; color: #6b7280; margin-top: 5px;">Soporta .csv</span>
                </label>
                <input type="file" id="file_import" name="file" style="display: none;" onchange="document.getElementById('file-label').innerText = this.files[0].name; document.querySelector('button[type=submit]').disabled = false;">
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection