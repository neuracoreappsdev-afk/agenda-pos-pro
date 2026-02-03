@extends('admin.configuration._layout')

@section('config_title', 'Gestión de Sedes')

@section('config_content')

<style>
    /* Premium UI Styles */
    :root {
        --primary-color: #4f46e5; /* Modern Indigo */
        --primary-hover: #4338ca;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --text-main: #111827;
        --text-secondary: #6b7280;
        --bg-card: #ffffff;
        --bg-hover: #f9fafb;
    }

    .config-card {
        background: transparent; /* Remove default container bg for cleaner look */
        padding: 0;
        box-shadow: none;
        border: none;
    }

    .sede-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .sede-card {
        background: var(--bg-card);
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 30px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
    }
    
    .sede-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-color: var(--primary-color);
    }
    
    .sede-card.principal {
        border: 2px solid var(--primary-color);
        background: linear-gradient(145deg, #ffffff 0%, #f5f3ff 100%);
    }
    
    .sede-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 6px 14px;
        background: var(--primary-color);
        color: white;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }
    
    .sede-header {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 20px;
        padding-right: 90px; /* Prevent text overlap with absolute badge */
        min-height: 80px; /* Ensure consistent height */
    }
    
    .sede-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #312e81 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
    }
    
    .sede-info {
        flex: 1;
    }
    
    .sede-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 6px 0;
        line-height: 1.3;
    }
    
    .sede-address {
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }
    
    .sede-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
        flex: 1;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .detail-label {
        font-size: 10px;
        color: #9ca3af;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 1px;
    }
    
    .detail-value {
        font-size: 14px;
        color: var(--text-main);
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .sede-actions {
        display: flex;
        gap: 12px;
        margin-top: auto;
    }
    
    .action-btn {
        flex: 1;
        padding: 10px 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .action-btn.primary {
        background: var(--primary-color);
        color: white;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }
    
    .action-btn.primary:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.3);
    }
    
    .action-btn.secondary {
        background: #f3f4f6;
        color: var(--text-secondary);
    }
    
    .action-btn.secondary:hover {
        background: #e5e7eb;
        color: var(--text-main);
    }

    .action-btn.danger {
        background: #fee2e2;
        color: var(--danger-color);
        flex: 0 0 auto;
        width: 42px;
        padding: 0;
    }
    
    .action-btn.danger:hover {
        background: #fecaca;
        color: #b91c1c;
    }
    
    .add-sede-card {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
        cursor: pointer;
        transition: all 0.3s;
        min-height: 250px;
    }
    
    .add-sede-card:hover {
        background: #eff6ff;
        border-color: var(--primary-color);
        transform: translateY(-4px);
    }
    
    .add-icon {
        width: 64px;
        height: 64px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--primary-color);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s;
    }

    .add-sede-card:hover .add-icon {
        transform: scale(1.1);
    }
    
    .add-text {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    /* Floating Alert */
    .alert-floating {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease-out;
        font-weight: 500;
    }

    .alert-floating.success {
        background: #ffffff;
        border-left: 5px solid var(--success-color);
        color: #065f46;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    /* Modal Tweaks */
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
        backdrop-filter: blur(4px); /* Premium blur effect */
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        padding: 35px;
        border-radius: 20px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    /* Rest of modal styles same as before but refined */
    .modal-header { border-bottom: 0; padding-bottom: 0; margin-bottom: 25px; }
    .modal-header h3 { font-size: 24px; font-weight: 700; color: var(--text-main); }
    .form-control {
        border: 1px solid #e2e8f0;
        padding: 10px 14px;
        border-radius: 8px;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    
    .row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .col-md-6 { flex: 1; }
    .col-md-4 { flex: 1; }
    .col-md-8 { flex: 2; }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .add-icon {
        height: 60px; /* This was duplicated and incorrect, removed from here */
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        color: #1a73e8;
    }
    
    .add-text {
        font-size: 16px;
        font-weight: 600;
        color: #6b7280;
    }

    .section-title {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        margin: 20px 0 10px 0;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 5px;
    }

    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }
</style>

<div class="config-card">
    <div style="margin-bottom: 35px;">
        <h2 style="font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 10px;">Gestión de Sedes</h2>
        <p style="color: #6b7280; font-size: 15px; margin: 0; max-width: 600px; line-height: 1.5;">
            Administra las ubicaciones físicas de tu negocio. Configura la información de contacto, legal y preferencias para cada sede.
        </p>
    </div>

    <!-- Alert is now handled by JS Auto-Dismiss logic, cleaner implementation -->
    @if(session('success'))
        <div id="flash-message" class="alert-floating success">
            <span style="font-size: 20px;">✓</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="sede-grid">
        @forelse($sedes as $sede)
        <div class="sede-card {{ isset($sede['is_principal']) && $sede['is_principal'] ? 'principal' : '' }}">
            @if(isset($sede['is_principal']) && $sede['is_principal'])
                <span class="sede-badge">PRINCIPAL</span>
            @endif
            <div class="sede-header">
                <div class="sede-icon">🏢</div>
                <div class="sede-info">
                    <h3 class="sede-name">{{ $sede['name'] }}</h3>
                    <p class="sede-address">
                        <i class="fa fa-map-marker"></i> {{ $sede['address'] }}
                    </p>
                </div>
            </div>
            
            <div class="sede-details">
                <div class="detail-item">
                    <span class="detail-label">Teléfono</span>
                    <span class="detail-value">{{ $sede['phone'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value" title="{{ $sede['email'] ?? '-' }}">{{ $sede['email'] ?? '-' }}</span>
                </div>
                <!-- Row 2 -->
                 <div class="detail-item">
                    <span class="detail-label">Ciudad</span>
                    <span class="detail-value">{{ $sede['city'] ?? '-' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Estado</span>
                    <span class="detail-value" style="color: {{ isset($sede['active']) && $sede['active'] ? 'var(--success-color)' : 'var(--text-secondary)' }}">
                        {{ isset($sede['active']) && $sede['active'] ? '● Activo' : '○ Inactivo' }}
                    </span>
                </div>
            </div>
            
            <div class="sede-actions">
                <button type="button" class="action-btn primary" 
                        data-sede="{{ json_encode($sede) }}"
                        onclick="editSede(this)">
                    <span>✏️ Editar</span>
                </button>
                
                @if(isset($sede['id']))
                <a href="{{ url('admin/configuration/sedes/delete/' . $sede['id']) }}" 
                   class="action-btn danger" 
                   onclick="return confirm('¿Estás seguro de eliminar esta sede permanentemente?')"
                   title="Eliminar Sede">
                    🗑️
                </a>
                @endif
            </div>
        </div>
        @empty
        <!-- Empty State in Grid if needed -->
        @endforelse

        <!-- Agregar Nueva Sede -->
        <div class="add-sede-card" onclick="openSedeModal()">
            <div class="add-icon">＋</div>
            <div class="add-text">Crear Nueva Sede</div>
        </div>
    </div>

    <div class="btn-group" style="margin-top: 20px;">
        <a href="{{ url('admin/configuration') }}" class="btn btn-secondary" style="padding: 10px 25px;">&larr; Volver</a>
    </div>
</div>

<!-- Modal Principal (Mantiene estructura funcionalidad) -->
<div id="sedeModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Gestión de Sede</h3>
            <button class="modal-close" onclick="closeModal('sedeModal'); resetSedeForm();">×</button>
        </div>
        
        <form action="{{ url('admin/configuration/sedes') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" id="sede_id">

            <div class="section-title">Información Principal</div>
            <div class="row">
                <div class="col-md-8 form-group">
                    <label>Nombre de la Sede <span style="color:red">*</span></label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Sede Norte" required>
                </div>
                 <div class="col-md-4 form-group">
                    <label>Estado</label>
                    <div style="margin-top: 10px;">
                        <label style="cursor:pointer;"><input type="checkbox" name="activo" checked> &nbsp;Sede Activa</label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                 <label style="cursor:pointer; font-weight: 600; color:var(--primary-color);">
                    <input type="checkbox" name="principal"> ⭐ Establecer como sede principal
                </label>
            </div>

            <div class="section-title">Datos Legales</div>
            <div class="form-group">
                <label><input type="checkbox" name="usar_legal_principal" checked> Usar datos legales de la compañía principal</label>
            </div>
            
            <div id="legal_fields">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nombre Legal</label>
                        <input type="text" name="nombre_legal" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>NIT / Identificación</label>
                        <input type="text" name="nit" class="form-control">
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6 form-group">
                        <label>Régimen</label>
                        <select name="regimen" class="form-control">
                            <option value="">Seleccione...</option>
                            <option value="responsable_iva">Responsable de IVA</option>
                            <option value="no_responsable_iva">No Responsable de IVA</option>
                        </select>
                    </div>
                     <div class="col-md-6 form-group">
                        <label>Tipo de Persona</label>
                         <select name="tipo_persona" class="form-control">
                            <option value="">Seleccione...</option>
                            <option value="natural">Persona Natural</option>
                            <option value="juridica">Persona Jurídica</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="section-title">Ubicación</div>
            <div class="form-group">
                <label><input type="checkbox" name="usar_ubicacion_principal"> Usar Ubicación de la compañía principal</label>
            </div>
            
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Ciudad</label>
                    <input type="text" name="ciudad" class="form-control">
                </div>
                <div class="col-md-4 form-group">
                     <label>Departamento</label>
                    <input type="text" name="departamento" class="form-control">
                </div>
                 <div class="col-md-4 form-group">
                     <label>Código Postal</label>
                    <input type="text" name="codigo_postal" class="form-control">
                </div>
            </div>
             <div class="row">
                <div class="col-md-8 form-group">
                    <label>Dirección <span style="color:red">*</span></label>
                    <input type="text" name="direccion" class="form-control" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Barrio</label>
                    <input type="text" name="barrio" class="form-control">
                </div>
            </div>

            <div class="section-title">Contacto</div>
             <div class="form-group">
                <label><input type="checkbox" name="usar_contacto_principal"> Usar Contacto de la compañía principal</label>
            </div>
             <div class="row">
                <div class="col-md-6 form-group">
                    <label>Teléfono <span style="color:red">*</span></label>
                    <input type="text" name="telefono" class="form-control" required>
                </div>
                 <div class="col-md-6 form-group">
                    <label>Celular</label>
                    <input type="text" name="celular" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Email</label>
                 <input type="email" name="email" class="form-control">
            </div>

            <div class="section-title">Otros Datos</div>
            <div class="form-group">
                <label><input type="checkbox" name="usar_nombre_comercial"> Usar Nombre Comercial del negocio</label>
            </div>
             <div class="row">
                <div class="col-md-6 form-group">
                    <label>Nombre para mostrar al cliente</label>
                    <input type="text" name="nombre_mostrar" class="form-control">
                </div>
                 <div class="col-md-6 form-group">
                    <label>Nombre Franquicia</label>
                    <input type="text" name="nombre_franquicia" class="form-control">
                </div>
            </div>
             <div class="row">
                <div class="col-md-6 form-group">
                    <label>Código de Sede</label>
                    <input type="text" name="codigo_sede" class="form-control">
                </div>
                 <div class="col-md-6 form-group">
                    <label>Tipo de Sede</label>
                    <input type="text" name="tipo_sede" class="form-control">
                </div>
            </div>

            <div class="section-title">Alertas</div>
            <div class="form-group">
                <label><input type="checkbox" name="mostrar_alertas_docs"> Mostrar Alertas de Documentos Electrónicos</label>
            </div>
            <div class="form-group">
                <label>Límite para mostrar alerta de saldo bajo</label>
                <input type="number" name="limite_alertas_docs" class="form-control" value="200" style="max-width: 150px;">
            </div>

            
            <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 35px; border-top: 1px solid #eee; padding-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('sedeModal'); resetSedeForm();" style="padding: 10px 25px;">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="background: var(--primary-color); border:none; padding: 10px 30px; border-radius: 8px;">Guardar Sede</button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-Dismiss Flash Messages
document.addEventListener('DOMContentLoaded', function() {
    var flash = document.getElementById('flash-message');
    if (flash) {
        setTimeout(function() {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-20px)';
            flash.style.transition = 'all 0.5s ease-out';
            setTimeout(function() {
                flash.style.display = 'none';
            }, 500);
        }, 3000); // 3 seconds visibility
    }
});

function openSedeModal() {
    document.getElementById('sedeModal').classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

function resetSedeForm() {
    var modal = document.getElementById('sedeModal');
    var form = modal.querySelector('form');
    form.reset();
    form.action = "{{ url('admin/configuration/sedes') }}";
    modal.querySelector('h3').innerText = 'Nueva Sede';
    document.getElementById('sede_id').value = '';
}

function editSede(btn) {
    var data = JSON.parse(btn.dataset.sede);
    var modal = document.getElementById('sedeModal');
    var form = modal.querySelector('form');
    
    resetSedeForm(); // Limpiar primero
    
    // Configurar para Update
    form.action = "{{ url('admin/configuration/sedes/update') }}";
    modal.querySelector('h3').innerText = 'Editar Sede';
    document.getElementById('sede_id').value = data.id;

    // Llenar campos
    form.querySelector('[name="nombre"]').value = data.name;
    form.querySelector('[name="direccion"]').value = data.address;
    form.querySelector('[name="telefono"]').value = data.phone;
    form.querySelector('[name="email"]').value = data.email || '';
    form.querySelector('[name="ciudad"]').value = data.city || '';
    form.querySelector('[name="departamento"]').value = data.state || '';
    form.querySelector('[name="codigo_postal"]').value = data.zip || '';
    form.querySelector('[name="barrio"]').value = data.barrio || '';
    
    form.querySelector('[name="nombre_legal"]').value = data.nombre_legal || '';
    form.querySelector('[name="nit"]').value = data.nit || '';
    form.querySelector('[name="regimen"]').value = data.regimen || '';
    form.querySelector('[name="tipo_persona"]').value = data.tipo_persona || '';
    
    form.querySelector('[name="celular"]').value = data.celular || '';
    form.querySelector('[name="nombre_mostrar"]').value = data.nombre_mostrar || '';
    form.querySelector('[name="nombre_franquicia"]').value = data.nombre_franquicia || '';
    form.querySelector('[name="codigo_sede"]').value = data.codigo_sede || '';
    form.querySelector('[name="tipo_sede"]').value = data.tipo_sede || '';
    form.querySelector('[name="limite_alertas_docs"]').value = data.limite_alertas_docs || '200';

    // Checkboxes
    form.querySelector('[name="activo"]').checked = data.active == 1 || data.active == true;
    form.querySelector('[name="principal"]').checked = data.is_principal;
    form.querySelector('[name="usar_legal_principal"]').checked = data.usar_legal_principal;
    form.querySelector('[name="usar_ubicacion_principal"]').checked = data.usar_ubicacion_principal;
    form.querySelector('[name="usar_contacto_principal"]').checked = data.usar_contacto_principal;
    form.querySelector('[name="usar_nombre_comercial"]').checked = data.usar_nombre_comercial;
    form.querySelector('[name="mostrar_alertas_docs"]').checked = data.mostrar_alertas_docs;

    openSedeModal();
}
</script>

@endsection