@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ isset($survey) ? 'Editar Encuesta' : 'Configurar Nueva Encuesta' }}</h1>
        <div class="breadcrumb">Administración / Marketing / Satisfacción</div>
    </div>

    <div class="card-form" style="max-width:800px; margin-top:30px; background:white; border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden;">
        <form action="{{ isset($survey) ? url('admin/surveys/'.$survey->id.'/update') : url('admin/surveys/store') }}" method="POST" style="padding:40px;">
            {!! csrf_field() !!}
            
            <div class="form-group" style="margin-bottom:25px;">
                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Título de la Encuesta</label>
                <input type="text" name="title" value="{{ $survey->title ?? '' }}" class="form-control" placeholder="Ej: Encuesta de Calidad Post-Servicio" required style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
            </div>

            <div class="form-group" style="margin-bottom:25px;">
                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Mensaje de Invitación</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Ej: Hola! Nos encantaría conocer tu opinión sobre tu reciente visita..." style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px; resize:none;">{{ $survey->description ?? '' }}</textarea>
            </div>

            <div class="grid-inputs" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:25px;">
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Evento Disparador</label>
                    <select name="trigger_event" class="form-control" style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                        <option value="appointment_finished" {{ (isset($survey) && $survey->trigger_event == 'appointment_finished') ? 'selected' : '' }}>Cita Finalizada (Checkout)</option>
                        <option value="manual" {{ (isset($survey) && $survey->trigger_event == 'manual') ? 'selected' : '' }}>Envío Manual</option>
                    </select>
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Enviar después de (minutos)</label>
                    <input type="number" name="delay_minutes" value="{{ $survey->delay_minutes ?? 60 }}" class="form-control" style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:30px;">
                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:15px;">Preguntas de la Encuesta</label>
                <div id="questions-container">
                    @if(isset($survey) && is_array($survey->questions_json))
                        @foreach($survey->questions_json as $index => $q)
                        <div class="question-row" style="display:flex; gap:10px; margin-bottom:10px;">
                            <input type="text" name="questions[]" value="{{ $q }}" class="form-control" placeholder="Escribe tu pregunta aquí..." style="flex:1; padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
                            <button type="button" onclick="this.parentElement.remove()" style="background:#fee2e2; color:#ef4444; border:none; padding:10px 15px; border-radius:8px; cursor:pointer;">×</button>
                        </div>
                        @endforeach
                    @else
                    <div class="question-row" style="display:flex; gap:10px; margin-bottom:10px;">
                        <input type="text" name="questions[]" value="¿Cómo calificarías la atención recibida?" class="form-control" placeholder="Escribe tu pregunta aquí..." style="flex:1; padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
                        <button type="button" onclick="this.parentElement.remove()" style="background:#fee2e2; color:#ef4444; border:none; padding:10px 15px; border-radius:8px; cursor:pointer;">×</button>
                    </div>
                    @endif
                </div>
                <button type="button" onclick="addQuestion()" style="background:#f3f4f6; color:#374151; border:1px dashed #d1d5db; padding:10px; border-radius:8px; width:100%; margin-top:10px; cursor:pointer; font-weight:600;">+ Añadir Pregunta</button>
            </div>

            <div class="form-footer" style="padding-top:25px; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="active" {{ (isset($survey) && !$survey->active) ? '' : 'checked' }}>
                    <span style="font-size:14px; font-weight:600; color:#4b5563;">Encuesta Activa</span>
                </label>
                <div style="display:flex; gap:12px;">
                    <a href="{{ url('admin/informes/respuestas-encuestas') }}" style="padding:12px 25px; border-radius:8px; border:1px solid #d1d5db; color:#4b5563; text-decoration:none; font-size:14px; font-weight:600;">Cancelar</a>
                    <button type="submit" style="background:#1a73e8; color:white; border:none; padding:12px 35px; border-radius:8px; cursor:pointer; font-size:14px; font-weight:700; box-shadow:0 10px 15px -3px rgba(26,115,232,0.3);">
                        {{ isset($survey) ? 'Guardar Cambios' : 'Crear Encuesta' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function addQuestion() {
    const container = document.getElementById('questions-container');
    const div = document.createElement('div');
    div.className = 'question-row';
    div.style.display = 'flex';
    div.style.gap = '10px';
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <input type="text" name="questions[]" class="form-control" placeholder="Escribe tu pregunta aquí..." style="flex:1; padding:10px; border:1px solid #e5e7eb; border-radius:8px;">
        <button type="button" onclick="this.parentElement.remove()" style="background:#fee2e2; color:#ef4444; border:none; padding:10px 15px; border-radius:8px; cursor:pointer;">×</button>
    `;
    container.appendChild(div);
}
</script>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    input[type="checkbox"] { width: 18px; height: 18px; border-radius: 4px; border: 2px solid #d1d5db; cursor: pointer; }
</style>
@endsection
