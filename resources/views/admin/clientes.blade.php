@extends('admin/dashboard_layout')

@section('content')

<style>
    /* Estilos Generales y Tabs Superiores */
    .page-header { margin-bottom: 20px; }
    .header-tabs { display: flex; gap: 30px; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; padding-left: 10px; }
    .tab-item { padding-bottom: 12px; font-size: 14px; font-weight: 500; color: #4b5563; cursor: pointer; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid transparent; transition: all 0.2s; }
    .tab-item.active { color: #1d4ed8; border-bottom-color: #1d4ed8; font-weight: 600; }
    
    /* Action Bar */
    .action-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .page-title { font-size: 18px; color: #1f2937; font-weight: 500; }
    .btn-create-main { background-color: #1a73e8; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; }
    .btn-create-main:hover { background-color: #1557b0; }

    /* Filtros */
    .filters-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 20px; align-items: end; margin-bottom: 20px; }
    .filter-group { display: flex; flex-direction: column; gap: 6px; }
    .filter-label { font-size: 12px; font-weight: 600; color: #374151; }
    .filter-input { border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #4b5563; background: white; }
    .btn-export-trigger { background-color: #1a73e8; color: white; border: none; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; }

    /* Tabla */
    .table-responsive { background: white; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    table { width: 100%; border-collapse: collapse; }
    th { font-size: 12px; color: #4b5563; font-weight: 700; text-align: left; padding: 14px 16px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; text-transform: uppercase; }
    td { padding: 16px; font-size: 14px; color: #1f2937; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
    tr:hover td { background-color: #f9fafb; }
    .client-name-cell { color: #1a73e8; font-weight: 600; cursor: pointer; }
    .client-name-cell:hover { text-decoration: underline; }
    .badge-type { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 700; background: #eff6ff; color: #1d4ed8; }

    /* Modal Estilo Lizto */
    .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: none; align-items: center; justify-content: center; z-index: 2000; backdrop-filter: blur(2px); }
    .modal-content { background: white; border-radius: 16px; width: 90%; max-width: 850px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); animation: zoomIn 0.2s ease-out; overflow: hidden; }
    @keyframes zoomIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    
    .modal-header { padding: 20px 25px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
    .modal-header h3 { margin: 0; font-size: 18px; color: #111827; font-weight: 700; }
    .btn-close-modal { cursor: pointer; font-size: 24px; color: #9ca3af; border: none; background: none; }

    /* Tabs Internas del Modal */
    .modal-tabs { display: flex; gap: 25px; padding: 0 25px; border-bottom: 1px solid #e5e7eb; background: #fff; }
    .m-tab { padding: 15px 0; font-size: 14px; font-weight: 600; color: #6b7280; cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; gap: 8px; }
    .m-tab.active { color: #1d4ed8; border-bottom-color: #1d4ed8; }

    .modal-body { padding: 25px; max-height: 70vh; overflow-y: auto; }
    .form-section-title { font-size: 15px; font-weight: 700; color: #374151; margin: 20px 0 15px 0; display: flex; align-items: center; gap: 10px; }
    .form-section-title:first-child { margin-top: 0; }
    
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 15px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #4b5563; margin-bottom: 6px; }
    .m-input { width: 100%; padding: 11px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
    .m-input:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.1); }

    .modal-footer { padding: 20px 25px; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end; gap: 12px; background: #f9fafb; }
    .btn-m-cancel { background: white; border: 1px solid #d1d5db; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; color: #374151; }
    .btn-m-save { background: #10b981; color: white; border: none; padding: 10px 30px; border-radius: 8px; font-weight: 700; cursor: pointer; }
    .btn-m-save:hover { background: #059669; }

    /* Import Section */
    .import-container { background: white; border-radius: 12px; padding: 40px; border: 2px dashed #e5e7eb; text-align: center; }
    .import-icon { font-size: 48px; color: #9ca3af; margin-bottom: 20px; }
    .btn-download-template { color: #1a73e8; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-block; margin-bottom: 30px; border: 1px solid #1a73e8; padding: 8px 16px; border-radius: 8px; }
    .btn-select-file { background: #1a73e8; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; display: inline-block; cursor: pointer; }

    /* Utility */
    .hidden { display: none !important; }
    #toast { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); padding: 14px 28px; border-radius: 10px; color: white; font-weight: 700; z-index: 3000; opacity: 0; transition: all 0.3s; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
</style>

<!-- TABS PRINCIPALES SECCIÓN -->
<div class="header-tabs">
    <div class="tab-item active" onclick="switchSection('listado', this)">
        <span class="tab-icon">{{ $isHealth ? '🏥' : '😊' }}</span> {{ $isHealth ? 'Pacientes' : 'Clientes' }}
    </div>
    <div class="tab-item" onclick="switchSection('empresas', this)">
        <span class="tab-icon">🏢</span> Empresas
    </div>
    <div class="tab-item" onclick="switchSection('importar', this)">
        <span class="tab-icon">⬇️</span> Importar {{ $isHealth ? 'Pacientes' : 'Clientes' }}
    </div>
</div>

<!-- SECCIÓN: LISTADO DE CLIENTES -->
<div id="section-listado" class="section-content">
    <div class="page-header">
        <div class="action-bar">
            <div class="page-title">{{ $isHealth ? 'Directorio de Pacientes' : 'Clientes Registrados' }}</div>
            <button class="btn-create-main" onclick="openCreateModal('Persona')">
                <span>+</span> Crear Nuevo {{ $isHealth ? 'Paciente' : 'Cliente' }}
            </button>
        </div>
        
        <div style="font-size: 14px; color: #6b7280; margin-bottom: 15px;">
            <strong id="clientCount">{{ count($clients) }}</strong> {{ $isHealth ? 'Pacientes' : 'Clientes' }} en total
        </div>
        
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">Buscador Inteligente</label>
                <input type="text" id="mainSearch" class="filter-input" placeholder="Nombre, ID, Teléfono o Email..." onkeyup="filterTable('clientsTable', this.value)">
            </div>
            <div class="filter-group">
                <label class="filter-label">Sede de Origen</label>
                <select class="filter-input">
                    <option>Todas las sedes</option>
                    <option>Holguínes Trade Center</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Acciones Masivas</label>
                <button class="btn-export-trigger">📊 Exportar a Excel</button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="clientsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ $isHealth ? 'Nombre del Paciente' : 'Nombre y Apellido' }}</th>
                    <th>Identificación</th>
                    <th>Celular / WhatsApp</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ciudad</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody id="clientsList">
                @foreach($clients as $client)
                @if($client->type != 'Empresa')
                <tr data-id="{{ $client->id }}">
                    <td>#{{ $client->id }}</td>
                    <td class="client-name-cell" onclick="editClient({{ $client->id }})">
                        {{ $client->first_name }} {{ $client->last_name }}
                    </td>
                    <td><span style="color:#6b7280; font-size:12px;">{{ $client->id_type ?? 'CC' }}</span> {{ $client->identification ?? '---' }}</td>
                    <td>{{ $client->contact_number }}</td>
                    <td>{{ $client->email }}</td>
                    <td><span class="badge-type">{{ $client->type ?? 'Persona' }}</span></td>
                    <td>{{ $client->city ?? '---' }}</td>
                    <td style="text-align:right; display: flex; gap: 5px; justify-content: flex-end;">
                        @if($isHealth)
                        <a href="{{ url('admin/historias-clinicas/'.$client->id) }}" class="btn-edit" style="background:#eef2ff; color:#4f46e5; border:1px solid #e0e7ff; text-decoration:none;">📂 Historia</a>
                        @endif
                        <button class="btn-edit" onclick="editClient({{ $client->id }})">📝</button>
                        <button class="btn-edit" style="background: #fee2e2; color: #dc2626;" onclick="deleteClient({{ $client->id }})">🗑️</button>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- SECCIÓN: EMPRESAS -->
<div id="section-empresas" class="section-content hidden">
    <div class="page-header">
        <div class="action-bar">
            <div class="page-title">Directorio de Empresas</div>
            <button class="btn-create-main" style="background:#059669;" onclick="openCreateModal('Empresa')">
                <span>+</span> Registrar Nueva Empresa
            </button>
        </div>
        
        <div class="filters-grid">
            <div class="filter-group">
                <label class="filter-label">Buscar Empresa</label>
                <input type="text" class="filter-input" placeholder="Nombre de Empresa o NIT..." onkeyup="filterTable('companyTable', this.value)">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="companyTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Razón Social / Empresa</th>
                    <th>NIT / Identificación</th>
                    <th>Contacto Directo</th>
                    <th>Email Corporativo</th>
                    <th>Ciudad</th>
                    <th>Sitio Web</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody id="companyList">
                @foreach($clients as $client)
                @if($client->type == 'Empresa')
                <tr data-id="{{ $client->id }}">
                    <td>#{{ $client->id }}</td>
                    <td class="client-name-cell" onclick="editClient({{ $client->id }})">
                        {{ $client->company_name ?? ($client->first_name . ' ' . $client->last_name) }}
                    </td>
                    <td>NIT {{ $client->identification ?? '---' }}-{{ $client->dv ?? '0' }}</td>
                    <td>{{ $client->contact_number }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->city ?? '---' }}</td>
                    <td><a href="{{ $client->website }}" target="_blank" style="color:#1a73e8; text-decoration:none;">{{ str_replace(['http://','https://'], '', $client->website) }}</a></td>
                    <td style="text-align:right;">
                        <button class="btn-edit" onclick="editClient({{ $client->id }})">📝 Editar</button>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- SECCIÓN: IMPORTAR -->
<div id="section-importar" class="section-content hidden">
    <div class="page-header">
        <div class="page-title">Importación Masiva de Clientes</div>
    </div>
    
    <div class="import-container">
        <div class="import-icon">📥</div>
        <h3>Carga tus clientes desde un archivo Excel</h3>
        <p style="color:#6b7280; margin-bottom:30px;">Puedes importar cientos de clientes en pocos segundos usando nuestra plantilla oficial.</p>
        
        <div style="display:flex; flex-direction:column; align-items:center;">
            <a href="#" class="btn-download-template">📄 Descargar Plantilla de Importación</a>
            
            <div style="margin-bottom:20px; width:100%; max-width:400px; text-align:left;">
                <label class="filter-label">Sede de destino</label>
                <select class="m-input">
                    <option>Holguínes Trade Center</option>
                </select>
            </div>
            
            <label class="btn-select-file">
                Seleccionar Archivo de Clientes (.xlsx, .csv)
                <input type="file" style="display:none;" onchange="handleImport(this)">
            </label>
        </div>
    </div>
</div>

<!-- MODAL AVANZADO (ESTILO LIZTO) -->
<div id="clientModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Nuevo Cliente</h3>
            <button class="btn-close-modal" onclick="closeModal()">&times;</button>
        </div>
        
        <!-- Tabs Internas del Modal -->
        <div class="modal-tabs">
            <div class="m-tab active" id="mtab-personal" onclick="switchModalTab('personal')">😊 Datos Personales</div>
            <div class="m-tab" id="mtab-empresa" onclick="switchModalTab('empresa')">🏢 Empresa</div>
            <div class="m-tab" id="mtab-masinfo" onclick="switchModalTab('masinfo')">📝 Más Info / Notas</div>
        </div>

        <form id="clientForm" onsubmit="saveClient(event)">
            {!! csrf_field() !!}
            <input type="hidden" id="clientId">
            <input type="hidden" id="clientType" value="Persona">
            
            <div class="modal-body">
                <!-- TAB: DATOS PERSONALES -->
                <div id="mcontent-personal" class="modal-tab-content">
                    <div class="form-section-title">👤 Información Personal</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Primer Nombre *</label>
                            <input type="text" id="firstName" required class="m-input" placeholder="Nombre">
                        </div>
                        <div class="form-group">
                            <label>Apellido *</label>
                            <input type="text" id="lastName" required class="m-input" placeholder="Apellido">
                        </div>
                    </div>
                    
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Tipo Id.</label>
                            <select id="idType" class="m-input">
                                <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                                <option value="Cédula de extranjería">Cédula de extranjería</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="NIT">NIT</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Identificación</label>
                            <input type="text" id="identification" class="m-input" placeholder="000.000.000">
                        </div>
                        <div class="form-group">
                            <label>DV</label>
                            <input type="text" id="dv" class="m-input" placeholder="0" maxlength="1">
                        </div>
                    </div>

                    <div class="form-section-title">📱 Contacto</div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Celular *</label>
                            <div style="display:flex; gap:5px;">
                                <div style="width:70px; background:#f3f4f6; border:1px solid #d1d5db; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:12px;">+57 🇨🇴</div>
                                <input type="text" id="contactNumber" required class="m-input" placeholder="300 000 0000">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Teléfono Fijo</label>
                            <input type="text" id="phoneLandline" class="m-input" placeholder="(602) ---">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" id="email" required class="m-input" placeholder="ejemplo@correo.com">
                    </div>

                    <div class="form-section-title">🎂 Datos Adicionales</div>
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Año</label>
                            <input type="number" id="birthYear" class="m-input" placeholder="2000">
                        </div>
                        <div class="form-group">
                            <label>Mes</label>
                            <select id="birthMonth" class="m-input">
                                <option value="">Mes</option>
                                @foreach(['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $idx => $mes)
                                <option value="{{ $idx + 1 }}">{{ $mes }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Día</label>
                            <input type="number" id="birthDay" class="m-input" placeholder="01" min="1" max="31">
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Género</label>
                            <select id="gender" class="m-input">
                                <option value="">Seleccionar</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Ciudad</label>
                            <input type="text" id="city" class="m-input" placeholder="Cali">
                        </div>
                    </div>
                </div>

                <!-- TAB: EMPRESA -->
                <div id="mcontent-empresa" class="modal-tab-content hidden">
                    <div class="form-section-title">🏢 Datos Empresariales</div>
                    <div class="form-group">
                        <label>Nombre de la Empresa / Razón Social</label>
                        <input type="text" id="companyName" class="m-input" placeholder="Empresa S.A.S">
                    </div>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Sitio Web</label>
                            <input type="url" id="website" class="m-input" placeholder="https://www.empresa.com">
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" id="address" class="m-input" placeholder="Carrera 45 # 10-20">
                        </div>
                    </div>
                </div>

                <!-- TAB: MAS INFO -->
                <div id="mcontent-masinfo" class="modal-tab-content hidden">
                    <div class="form-section-title">📝 Notas y Preferencias</div>
                    <div class="form-group">
                        <label>Observaciones del Cliente</label>
                        <textarea id="notes" class="m-input" style="height: 120px;" placeholder="Detalles importantes sobre el cliente..."></textarea>
                    </div>
                    <div class="form-group" style="display:flex; align-items:center; gap:10px; background:#f9fafb; padding:15px; border-radius:10px;">
                        <input type="checkbox" id="wantsUpdates" style="width:20px; height:20px;" checked>
                        <label for="wantsUpdates" style="margin:0; cursor:pointer;">Desea recibir notificaciones y promociones</label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-m-cancel" onclick="closeModal()">Cerrar</button>
                <button type="submit" class="btn-m-save" id="btnSaveClient">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<div id="toast"></div>

<script>
    let allClients = {!! json_encode($clients) !!};

    // Cambio de Secciones Principales
    function switchSection(section, el) {
        document.querySelectorAll('.section-content').forEach(s => s.classList.add('hidden'));
        document.getElementById('section-' + section).classList.remove('hidden');
        
        document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    // Cambio de Tabs en Modal
    function switchModalTab(tab) {
        document.querySelectorAll('.modal-tab-content').forEach(c => c.classList.add('hidden'));
        document.getElementById('mcontent-' + tab).classList.remove('hidden');
        
        document.querySelectorAll('.m-tab').forEach(t => t.classList.remove('active'));
        document.getElementById('mtab-' + tab).classList.add('active');
    }

    function openCreateModal(type = 'Persona') {
        document.getElementById('modalTitle').innerText = (type === 'Empresa' ? 'Nueva Empresa' : 'Nuevo Cliente');
        document.getElementById('clientId').value = '';
        document.getElementById('clientForm').reset();
        document.getElementById('clientType').value = type;
        
        // Ajustar tabs iniciales
        if(type === 'Empresa') switchModalTab('empresa');
        else switchModalTab('personal');
        
        document.getElementById('clientModal').style.display = 'flex';
    }

    function editClient(id) {
        const client = allClients.find(c => c.id == id);
        if (!client) return;

        document.getElementById('modalTitle').innerText = 'Ficha del Cliente';
        document.getElementById('clientId').value = client.id;
        document.getElementById('clientType').value = client.type || 'Persona';
        
        // Poblar campos
        document.getElementById('firstName').value = client.first_name || '';
        document.getElementById('lastName').value = client.last_name || '';
        document.getElementById('idType').value = client.id_type || 'Cédula de ciudadanía';
        document.getElementById('identification').value = client.identification || '';
        document.getElementById('dv').value = client.dv || '';
        document.getElementById('contactNumber').value = client.contact_number || '';
        document.getElementById('phoneLandline').value = client.phone_landline || '';
        document.getElementById('email').value = client.email || '';
        document.getElementById('city').value = client.city || '';
        document.getElementById('address').value = client.address || '';
        document.getElementById('companyName').value = client.company_name || '';
        document.getElementById('website').value = client.website || '';
        document.getElementById('notes').value = client.notes || '';
        document.getElementById('gender').value = client.gender || '';
        document.getElementById('wantsUpdates').checked = !!client.wants_updates;

        // Fecha de cumpleaños si existe
        if (client.birthday) {
            const date = new Date(client.birthday);
            document.getElementById('birthYear').value = date.getFullYear();
            document.getElementById('birthMonth').value = date.getMonth() + 1;
            document.getElementById('birthDay').value = date.getDate() + 1;
        }

        switchModalTab(client.type === 'Empresa' ? 'empresa' : 'personal');
        document.getElementById('clientModal').style.display = 'flex';
    }

    function closeModal() { document.getElementById('clientModal').style.display = 'none'; }

    function saveClient(event) {
        event.preventDefault();
        const id = document.getElementById('clientId').value;
        const year = document.getElementById('birthYear').value;
        const month = document.getElementById('birthMonth').value;
        const day = document.getElementById('birthDay').value;
        
        let birthday = null;
        if(year && month && day) {
            birthday = `${year}-${month.padStart(2,'0')}-${day.padStart(2,'0')}`;
        }

        const data = {
            _token: '{{ csrf_token() }}',
            first_name: document.getElementById('firstName').value,
            last_name: document.getElementById('lastName').value,
            id_type: document.getElementById('idType').value,
            identification: document.getElementById('identification').value,
            dv: document.getElementById('dv').value,
            contact_number: document.getElementById('contactNumber').value,
            phone_landline: document.getElementById('phoneLandline').value,
            email: document.getElementById('email').value,
            type: document.getElementById('clientType').value,
            city: document.getElementById('city').value,
            address: document.getElementById('address').value,
            company_name: document.getElementById('companyName').value,
            website: document.getElementById('website').value,
            notes: document.getElementById('notes').value,
            gender: document.getElementById('gender').value,
            birthday: birthday,
            wants_updates: document.getElementById('wantsUpdates').checked ? 1 : 0
        };

        if (id) data.id = id;

        const url = id ? '{{ url("admin/clientes/update") }}' : '{{ url("admin/clientes/store") }}';

        fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(result.error || 'Error al guardar', 'error');
            }
        });
    }

    function deleteClient(id) {
        if (!confirm('¿Seguro que deseas eliminar este registro?')) return;
        fetch('{{ url("admin/clientes/delete") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ _token: '{{ csrf_token() }}', id: id })
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                location.reload();
            } else {
                showToast(result.error || 'Error', 'error');
            }
        });
    }

    function filterTable(tableId, query) {
        const q = query.toLowerCase();
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(q) ? '' : 'none';
        });
    }

    function handleImport(input) {
        if (!input.files || !input.files[0]) return;
        showToast('Leyendo archivo...', 'info');
        setTimeout(() => showToast('Importación masiva iniciada (Simulado)', 'success'), 1500);
    }

    function showToast(msg, type) {
        const toast = document.getElementById('toast');
        toast.innerText = msg;
        toast.style.backgroundColor = type === 'success' ? '#10b981' : (type === 'info' ? '#3b82f6' : '#ef4444');
        toast.style.opacity = 1;
        setTimeout(() => toast.style.opacity = 0, 3000);
    }
</script>

@endsection
