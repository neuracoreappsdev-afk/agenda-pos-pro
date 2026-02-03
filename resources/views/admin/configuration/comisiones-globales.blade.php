@extends('admin.configuration._layout')

@section('config_title', 'Comisiones Globales')

@section('config_content')

<style>
    .page-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .btn-apply {
        background: #2563eb;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-apply:hover {
        background: #1d4ed8;
    }

    /* Tabs */
    .tabs-nav {
        display: flex;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 25px;
        flex-wrap: wrap;
        gap: 5px;
    }

    .tab-btn {
        background: none;
        border: none;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        color: #1f2937;
    }

    .tab-btn.active {
        color: #2563eb;
        border-bottom-color: #2563eb;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Search */
    .search-section {
        margin-bottom: 25px;
    }

    .search-label {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        display: block;
    }

    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
    }

    /* Toggle */
    .toggle-section {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
        flex-direction: column;
    }

    .toggle-label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #d1d5db;
        transition: 0.3s;
        border-radius: 26px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #2563eb;
    }

    input:checked + .slider:before {
        transform: translateX(24px);
    }

    /* Commission Table */
    .commission-table {
        width: 100%;
    }

    .commission-row {
        display: flex;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .commission-row:last-child {
        border-bottom: none;
    }

    .commission-name {
        flex: 1;
        font-size: 14px;
        color: #374151;
        font-weight: 500;
    }

    .commission-value {
        width: 120px;
        margin-right: 10px;
    }

    .commission-value input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        text-align: right;
    }

    .commission-type {
        display: flex;
        gap: 2px;
    }

    .type-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .type-btn.percent {
        background: #dbeafe;
        color: #2563eb;
    }

    .type-btn.percent.active {
        background: #2563eb;
        color: white;
    }

    .type-btn.fixed {
        background: #fef3c7;
        color: #d97706;
    }

    .type-btn.fixed.active {
        background: #d97706;
        color: white;
    }

    /* Productos Tab Additional */
    .global-commission-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .global-commission-row input[type="text"],
    .global-commission-row input[type="number"] {
        width: 100px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        text-align: right;
    }

    /* Productos Consumo Tab */
    .config-select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 13px;
        min-width: 180px;
    }

    .percentage-input-section {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .percentage-input-section label {
        font-size: 14px;
        color: #374151;
    }

    .percentage-input-section input {
        width: 150px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
    }

    .section-subtitle {
        font-size: 15px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 15px;
    }

    .toast {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
</style>

<div class="page-container">
    <div class="page-header">
        <h1 class="page-title">Comisiones Globales</h1>
        <button type="button" class="btn-apply" onclick="aplicarCambios()">Aplicar Cambios</button>
    </div>

    <!-- Tabs Navigation -->
    <div class="tabs-nav">
        <button class="tab-btn active" onclick="showTab('categorias', this)">Categorías</button>
        <button class="tab-btn" onclick="showTab('servicios', this)">Servicios</button>
        <button class="tab-btn" onclick="showTab('marcas', this)">Marcas</button>
        <button class="tab-btn" onclick="showTab('productos', this)">Productos</button>
        <button class="tab-btn" onclick="showTab('consumo', this)">Productos de Consumo</button>
        <button class="tab-btn" onclick="showTab('bonos', this)">Bonos</button>
        <button class="tab-btn" onclick="showTab('colaboradores', this)">Tipos de Colaboradores</button>
    </div>

    <!-- TAB: Categorías -->
    <div id="tab-categorias" class="tab-content active">
        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar categoría..." onkeyup="filterItems(this, 'categorias')">
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Asignar comisiones de Categorías a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarCategorias" {{ $settings['asignar_categorias_especialistas'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="commission-table" id="table-categorias">
            @foreach($categorias as $cat)
            <div class="commission-row section-item" data-name="{{ strtolower($cat['nombre']) }}" data-id="{{ $cat['id'] }}" data-type="cat">
                <span class="commission-name">{{ $cat['nombre'] }}</span>
                <div class="commission-value">
                    <input type="number" step="0.01" value="{{ $cat['comision'] }}" class="value-input">
                </div>
                <div class="commission-type">
                    <input type="hidden" class="type-input" value="{{ $cat['tipo'] }}">
                    <button type="button" class="type-btn percent {{ $cat['tipo'] == 'porcentaje' ? 'active' : '' }}" onclick="setType(this, 'porcentaje')">%</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB: Servicios -->
    <div id="tab-servicios" class="tab-content">
        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar servicio..." onkeyup="filterItems(this, 'servicios')">
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Asignar comisiones de Servicios a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarServicios" {{ $settings['asignar_servicios_especialistas'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="commission-table" id="table-servicios">
            @foreach($servicios as $serv)
            <div class="commission-row section-item" data-name="{{ strtolower($serv['nombre']) }}" data-id="{{ $serv['id'] }}" data-type="serv">
                <span class="commission-name">{{ $serv['nombre'] }}</span>
                <div class="commission-value">
                    <input type="number" step="0.01" value="{{ $serv['comision'] }}" class="value-input">
                </div>
                <div class="commission-type">
                    <input type="hidden" class="type-input" value="{{ $serv['tipo'] }}">
                    <button type="button" class="type-btn percent {{ $serv['tipo'] == 'porcentaje' ? 'active' : '' }}" onclick="setType(this, 'porcentaje')">%</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB: Marcas -->
    <div id="tab-marcas" class="tab-content">
        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar marca..." onkeyup="filterItems(this, 'marcas')">
        </div>

        <div class="commission-table" id="table-marcas">
            @foreach($marcas as $marca)
            <div class="commission-row" data-name="{{ strtolower($marca['nombre']) }}">
                <span class="commission-name">{{ $marca['nombre'] }}</span>
                <div class="commission-value">
                    <input type="text" value="{{ $marca['comision'] }}" name="marca_{{ $marca['id'] }}">
                </div>
                <div class="commission-type">
                    <button type="button" class="type-btn percent {{ $marca['tipo'] == 'porcentaje' ? 'active' : '' }}" onclick="setType(this, 'percent')">%</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB: Productos -->
    <div id="tab-productos" class="tab-content">
        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar producto..." onkeyup="filterItems(this, 'productos')">
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Asignar comisión general de Productos a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarProductosGeneral" {{ $settings['asignar_productos_general'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="global-commission-row">
            <label class="switch">
                <input type="checkbox" id="aplicarComisionGlobal">
                <span class="slider"></span>
            </label>
            <span>Aplicar Comisión Global a todos los productos</span>
            <input type="number" value="{{ $settings['comision_global_productos'] }}" id="comisionGlobalProductos">
            <div class="commission-type">
                <button type="button" class="type-btn percent active" onclick="setType(this, 'percent')">%</button>
            </div>
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Asignar comisión de Productos a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarProductosEspecialistas" {{ $settings['asignar_productos_especialistas'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="commission-table" id="table-productos">
            @foreach($productos as $prod)
            <div class="commission-row section-item" data-name="{{ strtolower($prod['nombre']) }}" data-id="{{ $prod['id'] }}" data-type="prod">
                <span class="commission-name">{{ $prod['nombre'] }}</span>
                <div class="commission-value">
                    <input type="number" step="0.01" value="{{ $prod['comision'] }}" class="value-input">
                </div>
                <div class="commission-type">
                    <input type="hidden" class="type-input" value="{{ $prod['tipo'] }}">
                    <button type="button" class="type-btn percent {{ $prod['tipo'] == 'porcentaje' ? 'active' : '' }}" onclick="setType(this, 'porcentaje')">%</button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB: Productos de Consumo -->
    <div id="tab-consumo" class="tab-content">
        <div class="toggle-section">
            <span class="toggle-label">Asignar Porcentaje de productos de consumo a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarConsumo" {{ $settings['asignar_consumo_especialistas'] ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="percentage-input-section">
            <label>Porcentaje asumido por el especialista</label>
            <input type="number" value="{{ $settings['porcentaje_consumo_especialista'] }}" id="porcentajeConsumo">
        </div>

        <div class="section-subtitle">Configuración por Categorías de Servicios</div>

        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar categoría..." onkeyup="filterItems(this, 'consumo')">
        </div>

        <div class="commission-table" id="table-consumo">
            @foreach($productosConsumo as $pc)
            <div class="commission-row" data-name="{{ strtolower($pc['nombre']) }}">
                <span class="commission-name">{{ $pc['nombre'] }}</span>
                <select class="config-select" name="consumo_{{ $pc['id'] }}">
                    <option value="general" {{ $pc['config'] == 'general' ? 'selected' : '' }}>Configuración General</option>
                    <option value="precio_venta" {{ $pc['config'] == 'precio_venta' ? 'selected' : '' }}>Precio Total de Venta</option>
                    <option value="participacion" {{ $pc['config'] == 'participacion' ? 'selected' : '' }}>Participación Especialista</option>
                    <option value="empresa" {{ $pc['config'] == 'empresa' ? 'selected' : '' }}>Empresa</option>
                </select>
            </div>
            @endforeach
        </div>
    </div>

    <!-- TAB: Bonos -->
    <div id="tab-bonos" class="tab-content">
        <div class="toggle-section">
            <span class="toggle-label">Asignar comisión general de Bonos a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarBonos">
                <span class="slider"></span>
            </label>
        </div>

        <div class="global-commission-row">
            <span>Aplicar Comision Global a todos los bonos</span>
            <label class="switch">
                <input type="checkbox" id="aplicarComisionGlobalBonos">
                <span class="slider"></span>
            </label>
            <input type="number" value="0" id="comisionGlobalBonos">
            <div class="commission-type">
                <button type="button" class="type-btn percent active" onclick="setType(this, 'percent')">%</button>
            </div>
        </div>
    </div>

    <!-- TAB: Tipos de Colaboradores -->
    <div id="tab-colaboradores" class="tab-content">
        <div class="search-section">
            <label class="search-label">Buscador</label>
            <input type="text" class="search-input" placeholder="Buscar tipo de colaborador..." onkeyup="filterItems(this, 'colaboradores')">
        </div>

        <div class="toggle-section">
            <span class="toggle-label">Asignar comisiones de Tipos de especialista a todos los especialistas</span>
            <label class="switch">
                <input type="checkbox" id="asignarTiposColaboradores">
                <span class="slider"></span>
            </label>
        </div>

        <div class="commission-table" id="table-colaboradores">
            <!-- Tipos de Colaboradores will be loaded here -->
        </div>
    </div>
</div>

<script>
    function showTab(tabId, btn) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        
        // Show selected tab
        document.getElementById('tab-' + tabId).classList.add('active');
        btn.classList.add('active');
    }

    function setType(btn, type) {
        const container = btn.parentElement;
        container.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        container.querySelector('.type-input').value = type;
    }

    function filterItems(input, tableId) {
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('#table-' + tableId + ' .commission-row');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(filter)) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function aplicarCambios() {
        const btn = document.querySelector('.btn-apply');
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Guardando...';

        const commissions = [];
        document.querySelectorAll('.section-item').forEach(item => {
            commissions.push({
                type: item.dataset.type,
                id: item.dataset.id,
                name: item.querySelector('.commission-name').textContent, // Using name for categories/marcas
                value: item.querySelector('.value-input').value,
                commission_type: item.querySelector('.type-input').value
            });
        });

        const data = {
            _token: '{{ csrf_token() }}',
            asignar_categorias: document.getElementById('asignarCategorias').checked ? '1' : '0',
            asignar_servicios: document.getElementById('asignarServicios').checked ? '1' : '0',
            asignar_productos_general: document.getElementById('asignarProductosGeneral').checked ? '1' : '0',
            comision_global_productos: document.getElementById('comisionGlobalProductos').value,
            asignar_productos_especialistas: document.getElementById('asignarProductosEspecialistas').checked ? '1' : '0',
            asignar_consumo: document.getElementById('asignarConsumo').checked ? '1' : '0',
            porcentaje_consumo: document.getElementById('porcentajeConsumo').value,
            aplicar_comision_global_prod: document.getElementById('aplicarComisionGlobal').checked ? '1' : '0',
            commissions: commissions
        };
        
        fetch('{{ url("admin/configuration/save-comisiones-globales") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                showToast(result.message || 'Comisiones actualizadas correctamente', 'success');
            } else {
                showToast('Error: ' + (result.message || 'Error desconocido'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error de conexión', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = originalText;
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.style.display = 'block';
        toast.style.background = type === 'success' ? '#10b981' : '#ef4444';
        toast.textContent = (type === 'success' ? '✓ ' : '✕ ') + message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>

@endsection
