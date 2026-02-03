@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Header & Back Button */
    .top-bar {
        margin-bottom: 20px;
    }
    
    .btn-back {
        background: white;
        border: 1px solid #1a73e8;
        color: #1a73e8;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        margin-bottom: 20px;
    }
    
    /* Tabs */
    .account-tabs {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 24px;
    }

    .account-tab {
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
    }

    .account-tab.active {
        color: #1a73e8;
        border-bottom-color: #1a73e8;
        font-weight: 600;
    }

    /* Filters Grid */
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 13px;
        color: #374151;
        text-align: center;
    }

    .filter-input {
        border: 1px solid #d1d5db;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 14px;
        color: #4b5563;
        width: 100%;
        background: white;
    }

    /* Actions Row */
    .actions-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-consult {
        background-color: #2e7d32; /* Green */
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-export {
        background-color: #1a73e8; /* Blue */
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
    }
    
    .btn-ingresar {
        background-color: #1a73e8; /* Blue */
        color: white;
        border: none;
        padding: 8px 30px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        display: block;
        margin-left: auto;
        margin-top: 10px;
    }

    /* Filters Level 2 */
    .filters-level-2 {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 20px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 20px;
    }
    
    .more-filters-btn {
        border: 1px solid #d1d5db;
        background: white;
        color: #1a73e8;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .total-display {
        text-align: right;
    }
    
    .total-amount {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .total-label {
        font-size: 11px;
        color: #9ca3af;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Table */
    .table-responsive {
        margin-top: 20px;
        background: white;
    }

    table { width: 100%; border-collapse: collapse; }

    th {
        font-size: 12px;
        color: #1f2937;
        font-weight: 700;
        text-align: left;
        padding: 12px 10px;
        border-bottom: 1px solid #e5e7eb;
    }
    
    td {
        padding: 40px 10px;
        text-align: center;
        color: #6b7280;
        font-size: 14px;
    }

</style>

<div class="top-bar">
    <a href="{{ url('admin/cuenta-empresa') }}" class="btn-back">Regresar</a>
</div>

<div class="account-tabs">
    <a href="#" class="account-tab active">Ingresos</a>
    <a href="#" class="account-tab">Egresos</a>
</div>

<!-- Main Filters -->
<div class="filters-grid">
    <div class="filter-group">
        <label class="filter-label">Sede</label>
        <select class="filter-input"><option>Holguines Trade Center.</option></select>
    </div>
    
    <div class="filter-group">
        <label class="filter-label">Concepto</label>
        <select class="filter-input"><option>Conceptos ...</option></select>
    </div>

    <div class="filter-group">
        <label class="filter-label">Rango de Fechas</label>
        <select class="filter-input"><option>Hoy</option></select>
    </div>
</div>

<div class="actions-row">
    <button class="btn-consult">Consultar</button>
    <button class="btn-export">Exportar</button>
</div>

<div style="text-align:right;">
    <button class="btn-ingresar">Ingresar</button>
</div>


<!-- Secondary Filters & Total -->
<div class="filters-level-2">
    <div style="display:flex; gap:10px; width: 60%;">
         <input type="text" class="filter-input" placeholder="" style="max-width:200px;">
         <button class="more-filters-btn">⚙️ Mas Filtros</button>
    </div>

    <div class="total-display">
        <div class="total-amount">$ 0</div>
        <div class="total-label">GRAN TOTAL</div>
    </div>
</div>

<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Cons</th>
                <th>Estado</th>
                <th>Sede</th>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Notas</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="7">No hay Ingresos</td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
