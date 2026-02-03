@extends('admin.configuration._layout')

@section('config_title', 'Importar Clientes')

@section('config_content')
<form action="{{ url('admin/configuration/process-import-clients') }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">Importar Clientes</h3>
            
            {{-- Mensajes de estado --}}
            @if(session('success'))
                <div style="padding: 15px; background: #d1fae5; border: 1px solid #10b981; color: #065f46; border-radius: 6px; margin-bottom: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div style="padding: 15px; background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; border-radius: 6px; margin-bottom: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div style="padding: 15px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: #1e40af; font-size: 14px;">
                    📝 Importe sus clientes de forma masiva. 
                    <a href="{{ url('templates/import_clients_template.csv') }}" download style="font-weight: 700; text-decoration: underline;">Descargar plantilla sugerida</a>
                </p>
            </div>

            <div class="form-group" style="border: 2px dashed #d1d5db; padding: 40px; text-align: center; border-radius: 12px; background: #f9fafb;">
                <div style="font-size: 40px; margin-bottom: 10px;">👥</div>
                <label for="file_import" style="cursor: pointer;">
                    <span id="file-label" style="display: block; font-weight: 600; color: #374151;">Haga clic para seleccionar o arrastre el archivo</span>
                    <span style="display: block; font-size: 12px; color: #6b7280; margin-top: 5px;">Soporta .xlsx, .xls, .csv</span>
                </label>
                <input type="file" id="file_import" name="file" style="display: none;" onchange="handleFileSelect(this)">
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary" id="btn-submit" disabled>Procesar Importación</button>
            <a href="{{ url('admin/clientes/list') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>

<script>
    function handleFileSelect(input) {
        if (input.files && input.files[0]) {
            document.getElementById('file-label').textContent = 'Archivo seleccionado: ' + input.files[0].name;
            document.getElementById('file-label').style.color = '#1a73e8';
            document.getElementById('btn-submit').disabled = false;
        }
    }
</script>
@endsection