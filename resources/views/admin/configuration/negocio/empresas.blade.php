@extends('admin.configuration._layout')

@section('config_title', 'Empresas')

@section('config_content')

<style>
    .config-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f3f4f6;
        padding: 32px;
    }

    .empresa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 24px;
        margin-top: 32px;
    }

    .empresa-card {
        background: #ffffff;
        border: 1px solid #f3f4f6;
        border-radius: 24px;
        padding: 32px;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }

    .empresa-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);
        border-color: #6366f1;
    }

    .empresa-icon {
        width: 48px;
        height: 48px;
        background: #f5f3ff;
        color: #6366f1;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 14px;
        color: #4b5563;
    }

    .info-icon {
        color: #9ca3af;
        width: 16px;
    }

    .btn-create-empresa {
        background: #111827;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(17, 24, 39, 0.7);
        backdrop-filter: blur(8px);
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 24px;
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .form-section {
        margin-bottom: 32px;
    }

    .form-section-title {
        font-size: 14px;
        font-weight: 800;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f3f4f6;
    }
</style>

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h2 style="font-size: 32px; font-weight: 900; color: #111827; margin: 0 0 8px 0;">Mis Empresas</h2>
            <p style="color: #6b7280; font-size: 15px; margin: 0;">Gestiona la información legal de tus razones sociales.</p>
        </div>
        <button class="btn-create-empresa" onclick="openModal()">
            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nueva Empresa
        </button>
    </div>

    @if(empty($empresas) || count($empresas) == 0)
        <div style="padding: 100px 0; text-align: center; color: #9ca3af;">
            <div style="font-size: 64px; margin-bottom: 20px;">🏢</div>
            <h3 style="font-size: 20px; font-weight: 700; color: #374151;">No hay empresas registradas</h3>
            <p style="max-width: 300px; margin: 10px auto;">Agrega tu primera empresa para comenzar a facturar y gestionar sedes.</p>
        </div>
    @endif

    <div class="empresa-grid">
        @foreach($empresas as $index => $emp)
        <div class="empresa-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                <div class="empresa-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="action-btn" onclick='editEmpresa({{ $index }}, @json($emp))' style="border:none; background:#f9fafb; padding:8px; border-radius:10px; cursor:pointer;">✏️</button>
                    <button class="action-btn" onclick="deleteEmpresa({{ $index }})" style="border:none; background:#fee2e2; padding:8px; border-radius:10px; cursor:pointer;">🗑️</button>
                </div>
            </div>
            
            <h3 style="font-size: 20px; font-weight: 800; color: #111827; margin: 0 0 4px 0;">{{ $emp['nombre'] }}</h3>
            <p style="color: #6366f1; font-weight: 700; font-size: 13px; margin: 0 0 24px 0;">{{ $emp['tipo_id'] }}: {{ $emp['identificacion'] }}-{{ $emp['dv'] ?? '0' }}</p>

            <div class="info-row">
                <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1.01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                {{ $emp['indicador'] ?? '(+57)' }} {{ $emp['celular'] }}
            </div>
            <div class="info-row">
                <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                {{ $emp['email'] }}
            </div>
            <div class="info-row">
                <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                {{ $emp['direccion'] }}, {{ $emp['ciudad'] }}
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- MODAL -->
<div id="empresaModal" class="modal">
    <div class="modal-content">
        <div style="padding: 24px 40px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
            <h3 id="modalTitle" style="font-weight: 900; color: #111827; margin: 0; font-size: 20px;">Nueva Empresa</h3>
            <button onclick="closeModal()" style="background:none; border:none; font-size:28px; cursor:pointer; color:#9ca3af;">&times;</button>
        </div>
        <form action="{{ url('admin/configuration/empresas/save') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="index" id="empresaIndex">
            
            <div style="padding: 40px;">
                <div class="form-section">
                    <div class="form-section-title">Datos Básicos</div>
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label style="font-weight: 700;">Razón Social / Nombre Empresa</label>
                        <input type="text" name="nombre" id="inputNombre" class="form-control" placeholder="Eje: Salon Imperial S.A.S" required>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Identificación Legal</div>
                    <div style="display: grid; grid-template-columns: 150px 1fr 80px; gap: 16px;">
                        <div class="form-group">
                            <label style="font-weight: 700;">Tipo</label>
                            <select name="tipo_id" id="inputTipoId" class="form-control">
                                <option value="NIT">NIT</option>
                                <option value="CC">Cédula</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700;">Identificación</label>
                            <input type="text" name="identificacion" id="inputIdentificacion" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700;">DV</label>
                            <input type="text" name="dv" id="inputDv" class="form-control" maxlength="1">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="form-section-title">Contacto & Ubicación</div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group">
                            <label style="font-weight: 700;">Celular</label>
                            <div style="display: flex; gap: 8px;">
                                <select name="indicador" id="inputIndicador" class="form-control" style="width: 100px;">
                                    <option value="+57">🇨🇴 +57</option>
                                    <option value="+1">🇺🇸 +1</option>
                                </select>
                                <input type="text" name="celular" id="inputCelular" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700;">Email Corporativo</label>
                            <input type="email" name="email" id="inputEmail" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700;">Dirección Principal</label>
                        <input type="text" name="direccion" id="inputDireccion" class="form-control">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                        <div class="form-group">
                            <label style="font-weight: 700;">Ciudad</label>
                            <input type="text" name="ciudad" id="inputCiudad" class="form-control">
                        </div>
                        <div class="form-group">
                            <label style="font-weight: 700;">Departamento</label>
                            <input type="text" name="departamento" id="inputDepartamento" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 24px 40px; background: #fafafa; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; gap: 16px;">
                <button type="button" class="btn" onclick="closeModal()" style="background:white; border:1px solid #e5e7eb; color:#374151; font-weight:700; padding:12px 24px; border-radius:12px; cursor:pointer;">Cancelar</button>
                <button type="submit" class="btn" style="background:#111827; color:white; font-weight:800; padding:12px 40px; border-radius:12px; border:none; cursor:pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">Guardar Empresa</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').innerText = 'Registrar Empresa';
    document.getElementById('empresaIndex').value = '';
    document.getElementById('inputNombre').value = '';
    document.getElementById('inputIdentificacion').value = '';
    document.getElementById('inputEmail').value = '';
    document.getElementById('empresaModal').style.display = 'flex';
}

function editEmpresa(index, data) {
    document.getElementById('modalTitle').innerText = 'Editar Empresa';
    document.getElementById('empresaIndex').value = index;
    document.getElementById('inputNombre').value = data.nombre || '';
    document.getElementById('inputTipoId').value = data.tipo_id || 'NIT';
    document.getElementById('inputIdentificacion').value = data.identificacion || '';
    document.getElementById('inputDv').value = data.dv || '';
    document.getElementById('inputIndicador').value = data.indicador || '+57';
    document.getElementById('inputCelular').value = data.celular || '';
    document.getElementById('inputEmail').value = data.email || '';
    document.getElementById('inputDireccion').value = data.direccion || '';
    document.getElementById('inputCiudad').value = data.ciudad || '';
    document.getElementById('inputDepartamento').value = data.departamento || '';
    document.getElementById('empresaModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('empresaModal').style.display = 'none';
}

function deleteEmpresa(index) {
    if(confirm('¿Estás seguro de que deseas eliminar esta empresa?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url("admin/configuration/empresas/delete") }}';
        form.innerHTML = `
            {{ csrf_field() }}
            <input type="hidden" name="index" value="${index}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

@endsection