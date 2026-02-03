@extends('admin/dashboard_layout')

@section('content')

<style>
    .form-container {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-header {
        margin-bottom: 25px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 15px;
    }

    .form-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 6px;
    }

    .input-field {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    .btn-save {
        background-color: #1a73e8;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">Registrar Nueva Compra / Factura</h2>
    </div>

    <form action="#" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label class="label">Proveedor</label>
                <select class="input-field">
                    <option>Seleccione un proveedor</option>
                </select>
            </div>
            <div class="form-group">
                <label class="label">Número de Factura</label>
                <input type="text" class="input-field" placeholder="Ej: FAC-123">
            </div>
            <div class="form-group">
                <label class="label">Fecha de Compra</label>
                <input type="date" class="input-field" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
                <label class="label">Sede Destino</label>
                <select class="input-field">
                    <option>Principal</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <label class="label">Detalles / Notas</label>
            <textarea class="input-field" rows="3"></textarea>
        </div>

        <div style="text-align: right;">
            <button type="button" onclick="history.back()" class="btn-save" style="background-color: #f3f4f6; color: #4b5563; margin-right: 10px;">Cancelar</button>
            <button type="submit" class="btn-save">Guardar Compra</button>
        </div>
    </form>
</div>

@endsection
