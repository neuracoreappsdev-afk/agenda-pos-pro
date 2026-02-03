@extends('admin/dashboard_layout')

@section('content')

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    .page-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-delete:hover { background: #fecaca; }
    
    .card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .form-section {
        padding: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        display: block;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 8px;
        color: #374151;
    }
    .form-input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }
    
    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
    }
    
    .btn-cancel {
        background: white;
        color: #374151;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: 1px solid #d1d5db;
        cursor: pointer;
    }
    .btn-cancel:hover { background: #f3f4f6; }
    
    .btn-create {
        background: #1a73e8;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-create:hover { background: #1557b0; }
    
    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        background: #f9fafb;
    }
    
    .error-box {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<div style="max-width: 700px; margin: 0 auto;">
    
    <div class="page-header">
        <div>
            <a href="{{ url('admin/packages') }}" style="color: #6b7280; text-decoration: none; font-size: 13px;">
                ← Volver a Servicios
            </a>
            <h1 class="page-title">Editar Servicio</h1>
        </div>
    </div>

    @if($errors->any())
    <div class="error-box">
        <strong>Error:</strong>
        <ul style="margin: 5px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <form action="{{ url('admin/packages/'.$package->id.'/update') }}" method="POST">
            {{ csrf_field() }}
            
            <div class="form-section">
                <div class="form-group">
                    <label class="form-label">Nombre del Servicio *</label>
                    <input type="text" name="package_name" class="form-input" value="{{ $package->package_name }}" required>
                </div>

                <div class="form-grid-3">
                    <div class="form-group">
                        <label class="form-label">Precio ($) *</label>
                        <input type="number" name="package_price" class="form-input" value="{{ $package->package_price }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duración (min) *</label>
                        <input type="number" name="package_time" class="form-input" value="{{ $package->package_time }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Comisión (%)</label>
                        <input type="number" name="commission_percentage" class="form-input" value="{{ $package->commission_percentage ?? 0 }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prioridad (Orden)</label>
                        <input type="number" name="display_order" class="form-input" value="{{ $package->display_order ?? 0 }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Categoría</label>
                    <select name="category" class="form-input">
                        <option value="">Sin categoría</option>
                        <option value="MANICURA" {{ $package->category == 'MANICURA' ? 'selected' : '' }}>MANICURA</option>
                        <option value="PEDICURA" {{ $package->category == 'PEDICURA' ? 'selected' : '' }}>PEDICURA</option>
                        <option value="ESTILISTAS" {{ $package->category == 'ESTILISTAS' ? 'selected' : '' }}>ESTILISTAS</option>
                        <option value="ESTÉTICA" {{ $package->category == 'ESTÉTICA' ? 'selected' : '' }}>ESTÉTICA</option>
                        <option value="BARBERÍA" {{ $package->category == 'BARBERÍA' ? 'selected' : '' }}>BARBERÍA</option>
                        <option value="MAQUILLAJE" {{ $package->category == 'MAQUILLAJE' ? 'selected' : '' }}>MAQUILLAJE</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea name="package_description" class="form-input" rows="3">{{ $package->package_description }}</textarea>
                </div>
            </div>

            <div class="form-footer">
                <form action="{{ url('admin/packages/'.$package->id.'/delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                    {{ csrf_field() }}
                    <button type="submit" class="btn-delete">Eliminar Servicio</button>
                </form>
                <div style="display:flex; gap:12px;">
                    <a href="{{ url('admin/packages') }}" class="btn-cancel">Cancelar</a>
                    <button type="submit" class="btn-create">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>

</div>

@endsection