@extends('admin.configuration._layout')

@section('config_title', 'Gestión de Roles')

@section('config_content')

<style>
    .roles-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .btn-create-role {
        background: #4f46e5;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-create-role:hover {
        background: #4338ca;
        transform: translateY(-1px);
    }
    
    .roles-table {
        width: 100%;
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .roles-table th {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .roles-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
    }
    
    .roles-table tr:hover {
        background: #f8fafc;
    }
    
    .btn-edit-role {
        background: white;
        border: 1px solid #e2e8f0;
        color: #4f46e5;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-edit-role:hover {
        border-color: #4f46e5;
        background: #f5f3ff;
    }
    
    .btn-delete-role {
        background: white;
        border: 1px solid #e2e8f0;
        color: #ef4444;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: 5px;
    }
    
    .btn-delete-role:hover {
        border-color: #ef4444;
        background: #fef2f2;
    }
    
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    
    .modal.active {
        display: flex;
    }
    
    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #94a3b8;
        cursor: pointer;
        line-height: 1;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 14px;
        color: #334155;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .form-control:focus {
        border-color: #4f46e5;
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .btn-secondary {
        background: white;
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #10b981;
        color: white;
        border: none;
        padding: 10px 30px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-primary:hover {
        background: #059669;
    }
</style>

@if(session('success'))
    <div id="flash-message" style="position:fixed; top:20px; right:20px; background:white; border-left:5px solid #10b981; padding:16px 20px; border-radius:12px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1); z-index:9999; font-weight:500; color:#065f46;">
        ✓ {{ session('success') }}
    </div>
@endif

<div class="config-card">
    <div class="roles-header">
        <div>
            <h2 style="font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 5px;">Roles</h2>
            <p style="color: #6b7280; font-size: 14px; margin: 0;">Define roles y permisos para usuarios del sistema</p>
        </div>
        <button class="btn-create-role" onclick="openRoleModal()">
            ＋ Crear Nuevo
        </button>
    </div>
    
    <table class="roles-table">
        <thead>
            <tr>
                <th style="width: 60px;">#</th>
                <th>Nombre del Rol</th>
                <th style="text-align: right; width: 200px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $index => $role)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="font-weight: 600;">{{ $role['nombre'] }}</td>
                <td style="text-align: right;">
                    <button class="btn-edit-role" 
                            data-role-id="{{ $role['id'] }}" 
                            data-role-nombre="{{ $role['nombre'] }}"
                            onclick="editRole(this)">Editar</button>
                    <button class="btn-delete-role" onclick="deleteRole('{{ $role['id'] }}')">✕</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">
                    No hay roles registrados
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Create/Edit Role -->
<div id="roleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Rol</h3>
            <button class="modal-close" onclick="closeRoleModal()">×</button>
        </div>
        
        <form action="{{ url('admin/configuration/roles') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="role_id">
            
            <div class="form-group">
                <label>Nombre del Rol *</label>
                <input type="text" name="nombre" id="role_nombre" class="form-control" placeholder="Ej: Gerente de Ventas" required>
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 25px;">
                <button type="button" class="btn-secondary" onclick="closeRoleModal()">Cancelar</button>
                <button type="submit" class="btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>

document.addEventListener('DOMContentLoaded', function() {
    var flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(function() {
            flash.style.opacity = '0';
            setTimeout(function() { flash.style.display = 'none'; }, 500);
        }, 3000);
    }
    
    // Modal click outside listener
    var modal = document.getElementById('roleModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if(e.target === this) closeRoleModal();
        });
    }
});

function openRoleModal() {
    document.getElementById('role_id').value = '';
    document.getElementById('role_nombre').value = '';
    document.getElementById('roleModal').classList.add('active');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.remove('active');
}

function editRole(btn) {
    var roleId = btn.getAttribute('data-role-id');
    var roleNombre = btn.getAttribute('data-role-nombre');
    
    document.getElementById('role_id').value = roleId;
    document.getElementById('role_nombre').value = roleNombre;
    document.getElementById('roleModal').classList.add('active');
}

function deleteRole(id) {
    if(confirm('¿Está seguro de que desea eliminar este rol?')) {
        window.location.href = "{{ url('admin/configuration/roles/delete') }}/" + id;
    }
}
</script>

@endsection