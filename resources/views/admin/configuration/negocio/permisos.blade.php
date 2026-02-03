@extends('admin.configuration._layout')

@section('config_title', 'Gestión de Permisos')

@section('config_content')

<style>
    .config-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f3f4f6;
        padding: 0;
        overflow: hidden;
    }

    .permissions-header-block {
        padding: 32px;
        background: #ffffff;
        border-bottom: 1px solid #f3f4f6;
    }

    .search-container {
        padding: 20px 32px;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
    }

    .search-box {
        width: 100%;
        max-width: 400px;
        padding: 12px 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.2s;
        background: white;
    }

    .search-box:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    .permission-category {
        border-bottom: 1px solid #f3f4f6;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 32px;
        cursor: pointer;
        transition: background 0.2s;
        background: #fff;
    }

    .category-header:hover {
        background: #fcfcfd;
    }

    .category-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .category-header .folder-icon {
        color: #6366f1;
    }

    .category-content {
        display: none;
        background: #fff;
    }

    .category-content.active {
        display: block;
    }

    .permissions-table {
        width: 100%;
        border-collapse: collapse;
    }

    .permissions-table th {
        background: #f9fafb;
        padding: 12px 20px;
        font-size: 11px;
        font-weight: 800;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f3f4f6;
        text-align: center;
    }

    .permissions-table th:first-child {
        text-align: left;
        padding-left: 32px;
    }

    .permissions-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #f9fafb;
        text-align: center;
        font-size: 14px;
        color: #374151;
    }

    .permissions-table td:first-child {
        text-align: left;
        padding-left: 32px;
        font-weight: 500;
        color: #111827;
    }

    .permissions-table tr:hover {
        background: #fbfbfb;
    }

    .role-header {
        min-width: 100px;
    }

    /* Modern Checkbox */
    .checkbox-custom {
        appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
        display: inline-block;
        vertical-align: middle;
    }

    .checkbox-custom:checked {
        background: #6366f1;
        border-color: #6366f1;
    }

    .checkbox-custom:checked::after {
        content: '\2713';
        color: white;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: bold;
    }

    .btn-save-permissions {
        background: #111827;
        color: white;
        padding: 14px 40px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        display: block;
        margin: 32px auto;
    }

    .btn-save-permissions:hover {
        background: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }
</style>

<div class="config-card">
    <div class="permissions-header-block">
        <h2 style="font-size: 24px; font-weight: 800; color: #111827; margin: 0 0 8px 0;">Matriz de Permisos</h2>
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Configura qué acciones puede realizar cada rol en el sistema.</p>
    </div>

    <div class="search-container">
        <input type="text" class="search-box" id="searchPermissions" placeholder="🔍 Buscar un permiso..." onkeyup="filterPermissions()">
    </div>

    <form action="{{ url('admin/configuration/permisos') }}" method="POST">
        {{ csrf_field() }}
        
        @foreach($permissionCategories as $catIndex => $category)
        <div class="permission-category" id="cat-block-{{ $catIndex }}">
            <div class="category-header" onclick="toggleCategory({{ $catIndex }})">
                <h3>
                    <svg class="folder-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    {{ $category['name'] }}
                </h3>
                <svg id="icon-{{ $catIndex }}" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="category-content" id="category-{{ $catIndex }}">
                <table class="permissions-table">
                    <thead>
                        <tr>
                            <th style="width: 350px;">Nombre del Permiso</th>
                            @foreach($roles as $role)
                            <th class="role-header">{{ $role['nombre'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category['permissions'] as $permIndex => $permission)
                        <tr class="permission-row" data-name="{{ strtolower($permission) }}">
                            <td>{{ $permission }}</td>
                            @foreach($roles as $role)
                            <td>
                                <input type="checkbox" 
                                       class="checkbox-custom"
                                       name="permissions[{{ $role['id'] }}][{{ $catIndex }}_{{ $permIndex }}]" 
                                       value="1"
                                       {{ (isset($currentMatrix[$role['id']][$catIndex . '_' . $permIndex]) && $currentMatrix[$role['id']][$catIndex . '_' . $permIndex] == "1") ? 'checked' : '' }}>
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <button type="submit" class="btn-save-permissions">Guardar configuración de seguridad</button>
    </form>
</div>

<script>
function toggleCategory(index) {
    const content = document.getElementById('category-' + index);
    const icon = document.getElementById('icon-' + index);
    
    if (content.style.display === 'block') {
        content.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    } else {
        content.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    }
}

function filterPermissions() {
    const query = document.getElementById('searchPermissions').value.toLowerCase();
    const rows = document.querySelectorAll('.permission-row');
    const categories = document.querySelectorAll('.permission-category');

    rows.forEach(row => {
        const name = row.dataset.name;
        if (name.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Handle category visibility
    categories.forEach(cat => {
        const visibleRows = cat.querySelectorAll('.permission-row:not([style*="display: none"])');
        if (visibleRows.length === 0 && query !== '') {
            cat.style.display = 'none';
        } else {
            cat.style.display = '';
        }
    });
}
</script>

@endsection