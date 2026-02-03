@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>{{ isset($plan) ? 'Editar Plan Comercial' : 'Crear Nuevo Plan Comercial' }}</h1>
        <div class="breadcrumb">Administración / Planes / Formulario</div>
    </div>

    <div class="card-form" style="max-width:800px; margin-top:30px; background:white; border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); overflow:hidden;">
        <form action="{{ isset($plan) ? url('admin/planes/'.$plan->id.'/update') : url('admin/planes/store') }}" method="POST" style="padding:40px;">
            {!! csrf_field() !!}
            
            <div class="grid-inputs" style="display:grid; grid-template-columns: 1fr 180px; gap:20px; margin-bottom:25px;">
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Nombre del Plan</label>
                    <input type="text" name="name" value="{{ $plan->name ?? '' }}" class="form-control" placeholder="Ej: Membresía VIP Mensual" required style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Precio</label>
                    <div style="position:relative;">
                        <span style="position:absolute; left:12px; top:12px; color:#9ca3af;">$</span>
                        <input type="number" name="price" value="{{ $plan->price ?? '' }}" class="form-control" placeholder="0.00" required style="width:100%; padding:12px 12px 12px 30px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:25px;">
                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Descripción del Plan</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Describe los beneficios principales del plan..." style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px; resize:none;">{{ $plan->description ?? '' }}</textarea>
            </div>

            <div class="grid-inputs" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:25px;">
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Validez (Días)</label>
                    <input type="number" name="validity_days" value="{{ $plan->validity_days ?? 30 }}" class="form-control" style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                </div>
                <div class="form-group">
                    <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px;">Máximo de Usos (Opcional)</label>
                    <input type="number" name="max_uses" value="{{ $plan->max_uses ?? '' }}" class="form-control" placeholder="Ilimitado" style="width:100%; padding:12px; border:1.5px solid #e5e7eb; border-radius:8px; outline:none; font-size:14px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom:30px;">
                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:15px;">Servicios Incluidos en el Plan</label>
                <div style="background:#f9fafb; padding:20px; border-radius:12px; border:1px solid #e5e7eb; display:grid; grid-template-columns: repeat(2, 1fr); gap:12px; max-height:250px; overflow-y:auto;">
                    @foreach($services as $s)
                    <label style="display:flex; align-items:center; gap:10px; padding:8px 12px; background:white; border:1px solid #e5e7eb; border-radius:8px; cursor:pointer; font-size:13px; transition:all 0.2s;">
                        <?php 
                            $checked = false;
                            if(isset($plan) && isset($plan->benefits_json['services'])) {
                                if(in_array($s->id, $plan->benefits_json['services'])) $checked = true;
                            }
                        ?>
                        <input type="checkbox" name="included_services[]" value="{{ $s->id }}" {{ $checked ? 'checked' : '' }}>
                        <span>{{ $s->package_name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="form-footer" style="padding-top:25px; border-top:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="is_active" {{ (isset($plan) && !$plan->is_active) ? '' : 'checked' }}>
                    <span style="font-size:14px; font-weight:600; color:#4b5563;">Plan Activo</span>
                </label>
                <div style="display:flex; gap:12px;">
                    <a href="{{ url('admin/informes/listado-planes') }}" class="btn-cancel" style="padding:12px 25px; border-radius:8px; border:1px solid #d1d5db; color:#4b5563; text-decoration:none; font-size:14px; font-weight:600;">Cancelar</a>
                    <button type="submit" class="btn-save" style="background:#1a73e8; color:white; border:none; padding:12px 35px; border-radius:8px; cursor:pointer; font-size:14px; font-weight:700; box-shadow:0 10px 15px -3px rgba(26,115,232,0.3);">
                        {{ isset($plan) ? 'Guardar Cambios' : 'Crear Plan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #d1d5db;
        cursor: pointer;
    }
</style>
@endsection
