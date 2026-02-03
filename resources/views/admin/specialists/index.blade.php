@extends('admin/dashboard_layout')

@section('content')

<?php
    // Cargar especialistas desde la base de datos
    try {
        $specialists_list = \App\Models\Specialist::orderBy('name')->get();
    } catch (\Exception $e) {
        $specialists_list = collect([]);
    }
?>

<style>
    /* Header Card */
    .header-card {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .page-title { margin:0; font-size:18px; color:#374151; font-weight:600; }

    .btn-create {
        background-color: #1a73e8;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
        transition: background 0.2s;
    }
    .btn-create:hover { background-color: #1557b0; }

    /* Tabs */
    .tabs-container {
        display: flex;
        gap: 20px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 20px;
        background: white;
        padding: 0 30px;
        border-radius: 8px 8px 0 0;
    }

    .tab-item {
        padding: 15px 0;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
        transition: color 0.2s;
    }
    .tab-item:hover { color: #1a73e8; }
    .tab-item.active {
        color: #1a73e8;
        border-bottom-color: #1a73e8;
    }

    /* Filters */
    .filters-section {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        margin-bottom: 5px;
    }
    
    .filters-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 40px;
        gap: 20px;
        align-items: end;
    }

    .filter-group label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        text-align: center;
    }

    .filter-input-wrapper { display: flex; align-items: center; gap: 10px; }

    .filter-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
        color: #6b7280;
        background: #fff;
    }
    
    .btn-more-filters {
        color: #1a73e8; font-size: 14px; cursor: pointer; white-space: nowrap; 
        border: 1px solid #e5e7eb; padding: 10px; border-radius: 6px;
        background: white;
    }

    .btn-plus-blue {
        width: 40px; height: 40px; background: #1a73e8; color: white; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; font-size: 24px; 
        border: none; cursor: pointer; transition: transform 0.2s;
    }
    .btn-plus-blue:hover { transform: scale(1.1); }

    /* Table */
    .table-container { background: white; border-radius: 8px; padding: 0 30px 30px 30px; }
    
    table { width: 100%; border-collapse: collapse; }
    
    th {
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        padding: 15px 10px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
    }
    th:hover { color: #1a73e8; }
    
    td {
        padding: 20px 10px;
        font-size: 14px;
        color: #4b5563;
        border-bottom: 1px solid #f9fafb;
    }

    .specialist-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    .specialist-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }

    .btn-edit {
        background-color: #1a73e8;
        color: white;
        padding: 6px 20px;
        border-radius: 6px;
        font-size: 12px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-edit:hover { background-color: #1557b0; }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-inactive { background: #fee2e2; color: #991b1b; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    .empty-state-icon { font-size: 48px; margin-bottom: 15px; }
    .empty-state-text { font-size: 16px; margin-bottom: 20px; }

</style>

<!-- Header -->
<div class="header-card">
    <h1 class="page-title">Especialistas</h1>
    <a href="{{ url('admin/specialists/create') }}" class="btn-create">+ Crear Nuevo</a>
</div>

<!-- Tabs -->
<div class="tabs-container">
    <a href="{{ url('admin/specialists') }}" class="tab-item {{ Request::is('admin/specialists') ? 'active' : '' }}">Miembros</a>
    <a href="{{ url('admin/specialists/advances') }}" class="tab-item {{ Request::is('admin/specialists/advances') ? 'active' : '' }}">Descuentos</a>
    <a href="{{ url('admin/configuration/importar-especialistas') }}" class="tab-item">Importar Especialistas</a>
</div>

<!-- Filters -->
<div class="filters-section">
    <div class="filters-grid">
        <div class="filter-group">
            <label>Buscador</label>
            <div class="filter-input-wrapper">
                <input type="text" id="searchInput" class="filter-input" placeholder="Nombre o Identificación" onkeyup="filterTable()">
                <button class="btn-more-filters">⚙ Mas Filtros</button>
            </div>
        </div>
        
        <div class="filter-group">
            <label>Sede</label>
            <select class="filter-input">
                <option>Todas las sedes</option>
                <option>Holguines Trade Center.</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Estado</label>
            <select class="filter-input" id="statusFilter" onchange="filterTable()">
                <option value="">Todos</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>

        <a href="{{ url('admin/specialists/create') }}" class="btn-plus-blue">+</a>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    @if($specialists_list->count() > 0)
    <table id="specialistsTable">
        <thead>
            <tr>
                <th style="width:50px;">#</th>
                <th style="width:60px;">Foto</th>
                <th style="width:100px;">Código</th>
                <th>Nombre</th>
                <th>Especialidad</th>
                <th>Celular</th>
                <th>Email</th>
                <th style="width:80px;">Estado</th>
                <th style="width:100px;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($specialists_list as $key => $sp)
            <tr data-active="{{ $sp->active ? '1' : '0' }}">
                <td>{{ $key + 1 }}</td>
                <td>
                    @if($sp->avatar)
                        <img src="{{ $sp->avatar }}" alt="{{ $sp->name }}" class="specialist-avatar">
                    @else
                        <div class="specialist-avatar-placeholder">
                            {{ strtoupper(substr($sp->name, 0, 1)) }}
                        </div>
                    @endif
                </td>
                <td>{{ $sp->code ?? '=' }}</td>
                <td><strong>{{ $sp->name }}</strong></td>
                <td>{{ $sp->title ?? '-' }}</td>
                <td>{{ $sp->phone ?? '-' }}</td>
                <td>{{ $sp->email ?? '-' }}</td>
                <td>
                    @if($sp->active !== false)
                        <span class="status-badge status-active">Activo</span>
                    @else
                        <span class="status-badge status-inactive">Inactivo</span>
                    @endif
                </td>
                <td>
                    <a href="{{ url('admin/specialists/'.$sp->id.'/edit') }}" class="btn-edit">Editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">👤</div>
        <div class="empty-state-text">No hay especialistas registrados</div>
        <a href="{{ url('admin/specialists/create') }}" class="btn-create">+ Crear Primer Especialista</a>
    </div>
    @endif
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#specialistsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const isActive = row.dataset.active;
        
        let showBySearch = text.includes(searchValue);
        let showByStatus = statusValue === '' || isActive === statusValue;
        
        row.style.display = (showBySearch && showByStatus) ? '' : 'none';
    });
}
</script>

@endsection
