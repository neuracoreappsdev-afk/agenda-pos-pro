@extends('admin/dashboard_layout')

@section('content')

<?php
    // Cargar servicios desde la base de datos
    try {
        $packages_list = \App\Models\Package::orderBy('category')->orderBy('package_name')->get();
        $categoriesRaw = [];
        foreach ($packages_list as $p) {
            if ($p->category) $categoriesRaw[] = $p->category;
        }
        $categories = collect(array_unique($categoriesRaw));
    } catch (\Exception $e) {
        $packages_list = collect([]);
        $categories = collect([]);
    }
?>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: white;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .page-title {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }
    .page-subtitle {
        color: #6b7280;
        margin: 8px 0 0 0;
        font-size: 14px;
    }
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
    
    .filters-row {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        align-items: center;
    }
    .filter-input {
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        min-width: 200px;
    }
    .filter-input:focus {
        outline: none;
        border-color: #1a73e8;
    }
    
    .table-container {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    thead {
        background: #f9fafb;
    }
    th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        border-bottom: 1px solid #e5e7eb;
    }
    td {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    tr:hover { background: #f9fafb; }
    
    .service-name {
        font-weight: 600;
        color: #1f2937;
    }
    .service-description {
        font-size: 13px;
        color: #6b7280;
        margin-top: 4px;
    }
    .category-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e0e7ff;
        color: #3730a3;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .price-cell {
        font-weight: 600;
        color: #059669;
    }
    .duration-cell {
        color: #6b7280;
    }
    
    .btn-edit {
        background: #1a73e8;
        color: white;
        padding: 6px 16px;
        border-radius: 6px;
        font-size: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-edit:hover { background: #1557b0; }
    
    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        border: none;
        cursor: pointer;
        margin-left: 8px;
    }
    .btn-delete:hover { background: #fecaca; }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }
    
    .success-msg {
        background: #d1fae5;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .error-msg {
        background: #fee2e2;
        border: 1px solid #ef4444;
        color: #991b1b;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
</style>

<!-- Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Servicios</h1>
        <p class="page-subtitle">Gestiona los servicios y precios de tu negocio</p>
    </div>
    <a href="{{ url('admin/packages/create') }}" class="btn-create">+ Nuevo Servicio</a>
</div>

@if(session('success'))
<div class="success-msg">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="error-msg">{{ session('error') }}</div>
@endif

<!-- Filtros -->
<div class="filters-row">
    <input type="text" id="searchInput" class="filter-input" placeholder="Buscar servicio..." onkeyup="filterTable()">
    <select id="categoryFilter" class="filter-input" onchange="filterTable()">
        <option value="">Todas las categorías</option>
        @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
    </select>
</div>

<!-- Tabla -->
<div class="table-container">
    @if($packages_list->count() > 0)
    <table id="packagesTable">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th>Servicio</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Duración</th>
                <th>Comisión</th>
                <th style="width:150px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($packages_list as $key => $pkg)
            <tr data-category="{{ $pkg->category }}">
                <td>{{ $key + 1 }}</td>
                <td>
                    <div class="service-name">{{ $pkg->package_name }}</div>
                    @if($pkg->package_description)
                    <div class="service-description">{{ $pkg->package_description }}</div>
                    @endif
                </td>
                <td>
                    @if($pkg->category)
                        <span class="category-badge">{{ $pkg->category }}</span>
                    @else
                        <span style="color:#9ca3af;">-</span>
                    @endif
                </td>
                <td class="price-cell">${{ number_format($pkg->package_price, 0, ',', '.') }}</td>
                <td class="duration-cell">{{ $pkg->package_time }} min</td>
                <td>{{ $pkg->commission_percentage ?? 0 }}%</td>
                <td>
                    <a href="{{ url('admin/packages/'.$pkg->id.'/edit') }}" class="btn-edit">Editar</a>
                    <form action="{{ url('admin/packages/'.$pkg->id.'/delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este servicio?');">
                        {{ csrf_field() }}
                        <button type="submit" class="btn-delete">🗑</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">📦</div>
        <p style="font-size:16px; margin-bottom:20px;">No hay servicios registrados</p>
        <a href="{{ url('admin/packages/create') }}" class="btn-create">+ Crear Primer Servicio</a>
    </div>
    @endif
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const categoryValue = document.getElementById('categoryFilter').value;
    const rows = document.querySelectorAll('#packagesTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const category = row.dataset.category || '';
        
        let showBySearch = text.includes(searchValue);
        let showByCategory = categoryValue === '' || category === categoryValue;
        
        row.style.display = (showBySearch && showByCategory) ? '' : 'none';
    });
}
</script>

@endsection