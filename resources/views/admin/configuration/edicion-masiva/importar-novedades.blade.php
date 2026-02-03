@extends('admin.configuration._layout')

@section('config_title', 'Importar Novedades de Participaciones')

@section('config_content')
<form action="{{ url('admin/configuration/process-import-news') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Importar Novedades de Participaciones</h3>
            
            <div style="margin-bottom: 20px; padding: 15px; background: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 4px;">
                <p style="margin: 0; color: #15803d; font-size: 14px;">
                    Use el formato CSV para importar novedades de participaciones o deducciones de nómina masivas.
                </p>
            </div>

            <div class="form-group">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Archivo CSV</label>
                <input type="file" name="news_import_file" class="form-control" style="width:100%; padding:8px; border:1px solid #d1d5db; border-radius:6px;">
                <small style="color: #6b7280; font-size: 12px;">El archivo debe contener las columnas: ID_Especialista, Concepto, Valor, Fecha.</small>
            </div>

            <div class="form-group" style="margin-top: 15px;">
                <label style="display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="overwrite_existing_news" value="1">
                    <span style="font-size: 14px; font-weight: 600; color: #374151;">Sobrescribir registros existentes si coinciden en fecha y especialista</span>
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