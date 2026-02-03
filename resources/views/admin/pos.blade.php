@extends('admin/dashboard_layout')

@section('content')
<div class="pos-wrapper">
    <div class="pos-header">
        <h1 class="pos-title">Punto de Venta (POS)</h1>
        <div class="pos-status">
            <span class="status-dot"></span> CAJA ABIERTA · {{ date('d M, Y') }}
            <a href="{{ url('admin/caja/cierre') }}" class="btn-close-register" style="margin-left:10px; background:#ef4444; color:white; padding:3px 10px; border-radius:10px; text-decoration:none; font-weight:600;">Cerrar Caja</a>
        </div>
    </div>

    <div class="pos-grid">
        <!-- Panel Izquierdo: Selección de Productos/Servicios -->
        <div class="pos-left">
            <div class="search-bar">
                <span class="search-icon">🔍</span>
                <input type="text" id="posSearch" placeholder="Buscar servicio o producto..." onkeyup="filterItems()">
            </div>

            <div class="category-tabs">
                <button class="cat-tab active" onclick="filterCategory('all')">Todos</button>
                <button class="cat-tab" onclick="filterCategory('services')">Servicios</button>
                <button class="cat-tab" onclick="filterCategory('products')">Productos</button>
            </div>

            <!-- Global Specialist Selector -->
            <div style="margin-bottom:15px; background:white; padding:12px; border-radius:12px; display:flex; align-items:center; gap:10px; border:1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <span style="font-weight:600; color:#374151; font-size:14px;">👩‍⚕️ Asignar siguientes items a:</span>
                <select id="globalSpecialistSelect" onchange="setGlobalSpecialist(this.value)" style="flex:1; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; outline:none; transition:all 0.2s;">
                    <option value="">-- Automático / Último seleccionado --</option>
                </select>
            </div>

            <div class="items-grid" id="itemsGrid">
                <!-- Los items se cargarán dinámicamente -->
                @foreach(\App\Models\Package::orderBy('display_order', 'desc')->orderBy('package_name', 'asc')->get() as $item)
                <div class="item-card" data-name="{{ $item->package_name }}" data-price="{{ $item->package_price }}" data-id="{{ $item->id }}" data-type="services" onclick="addItemToCart(this)">
                    <div class="item-info">
                        <span class="item-name">{{ $item->package_name }}</span>
                        <span class="item-price">${{ number_format($item->package_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="item-add">+</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Panel Derecho: Carrito y Pago -->
        <div class="pos-right">
            <div class="cart-card">
                <div class="cart-header">
                    <h3>Detalle de Venta</h3>
                    <div style="display:flex; gap:10px;">
                        <button class="btn-clear" style="color: #f59e0b;" onclick="suspendSale()">⏸ Venta en espera</button>
                        <button class="btn-clear" onclick="clearCart()">Vaciar</button>
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <button class="btn-secondary-sm" style="width:100%;" onclick="openSuspendedSales()">
                        📋 Ver Ventas en Espera (<span id="suspendedCount">0</span>)
                    </button>
                </div>

                <div class="cart-items" id="cartItems">
                    <!-- Items del carrito -->
                    <div class="empty-cart-msg">No hay items seleccionados</div>
                </div>

                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span id="summarySubtotal">$0</span>
                    </div>
                    <div class="summary-row">
                        <span>Descuento</span>
                        <input type="number" id="cartDiscount" value="0" min="0" onchange="updateTotals()">
                    </div>
                    <div class="summary-total">
                        <span>TOTAL</span>
                        <span id="summaryTotal">$0</span>
                    </div>
                </div>

                <div class="customer-selection">
                    <label>Cliente</label>
                    <div class="customer-input-row">
                        <select id="posCustomerSelect">
                            <option value="">Consumidor Final</option>
                            @foreach(\App\Models\Customer::orderBy('first_name')->get() as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="payment-methods">
                    <label>Método de Pago</label>
                    <div class="methods-grid">
                        <button class="method-btn active" id="method-Efectivo" onclick="selectMethod('Efectivo', this)">💵 Efectivo</button>
                        <button class="method-btn" id="method-Transferencia" onclick="selectMethod('Transferencia', this)">📱 Transf.</button>
                        <button class="method-btn" id="method-Tarjeta" onclick="selectMethod('Tarjeta', this)">💳 Tarjeta</button>
                    </div>
                </div>

                <!-- Cash Calculation (Only for Efectivo) -->
                <div id="cashCalculation" style="margin-bottom: 20px; background: #f3f4f6; padding: 15px; border-radius: 12px; border: 1px dashed #d1d5db;">
                    <div class="summary-row" style="margin-bottom: 10px;">
                        <span style="font-weight: 600; color: #374151;">Paga con:</span>
                        <input type="number" id="cashReceived" placeholder="0" onkeyup="calculateChange()" style="width: 120px; padding: 5px 10px; border-radius: 8px; border: 1px solid #d1d5db; font-weight: 700; font-size: 16px;">
                    </div>
                    <div class="summary-row" style="margin-bottom: 0;">
                        <span style="font-weight: 600; color: #374151;">Su cambio:</span>
                        <span id="cashChange" style="font-weight: 800; color: #10a37f; font-size: 18px;">$0</span>
                    </div>
                </div>

                <button class="btn-checkout-pos" onclick="processSale()">
                    FINALIZAR VENTA
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .pos-wrapper {
        padding: 20px;
        background: #f9fafb;
        min-height: calc(100vh - 60px);
    }

    .pos-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .pos-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .pos-status {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
        background: white;
        padding: 6px 16px;
        border-radius: 20px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .status-dot {
        width: 8px;
        height: 8px;
        background: #10a37f;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.4; }
        100% { opacity: 1; }
    }

    .pos-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 25px;
        align-items: start;
    }

    /* Left Panel */
    .pos-left {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .search-bar {
        position: relative;
        background: white;
        border-radius: 12px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .search-icon { font-size: 18px; }
    .search-bar input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 15px;
        color: #374151;
    }

    .category-tabs {
        display: flex;
        gap: 10px;
    }

    .cat-tab {
        padding: 8px 20px;
        border-radius: 10px;
        background: white;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .cat-tab.active {
        background: #111827;
        color: white;
        border-color: #111827;
    }

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }

    .item-card {
        background: white;
        padding: 15px;
        border-radius: 15px;
        border: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .item-card:hover {
        border-color: #10a37f;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transform: translateY(-2px);
    }

    .item-info {
        display: flex;
        flex-direction: column;
    }

    .item-name {
        font-weight: 600;
        font-size: 14px;
        color: #111827;
    }

    .item-price {
        font-size: 13px;
        color: #10a37f;
        font-weight: 500;
    }

    .item-add {
        width: 32px;
        height: 32px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #6b7280;
    }

    /* Right Panel */
    .cart-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
        position: sticky;
        top: 80px;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .cart-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
    }

    .btn-clear {
        background: none;
        border: none;
        color: #ef4444;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .cart-items {
        min-height: 200px;
        max-height: 350px;
        overflow-y: auto;
        border-bottom: 2px dashed #f3f4f6;
        margin-bottom: 20px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f9fafb;
    }

    .empty-cart-msg {
        color: #9ca3af;
        text-align: center;
        padding-top: 50px;
        font-size: 14px;
    }

    .cart-summary {
        margin-bottom: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .summary-row input {
        width: 80px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 2px 8px;
        text-align: right;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        font-size: 20px;
        font-weight: 800;
        color: #111827;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #f3f4f6;
    }

    .customer-selection, .payment-methods {
        margin-bottom: 20px;
    }

    .customer-selection label, .payment-methods label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    #posCustomerSelect {
        width: 100%;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 14px;
    }

    .methods-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .method-btn {
        padding: 10px 5px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
        background: white;
        cursor: pointer;
        transition: all 0.2s;
    }

    .method-btn.active {
        background: #111827;
        color: white;
        border-color: #111827;
    }

    .btn-checkout-pos {
        width: 100%;
        background: #111827;
        color: white;
        padding: 18px;
        border-radius: 15px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .btn-checkout-pos:hover {
        background: #000;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
</style>

<script>
    let cart = [];
    let selectedMethod = 'Efectivo';
    let lastSelectedSpecialist = null; // Sticky Specialist
    // Pass specialists from backend safely
    const specialists = {!! isset($specialists) ? json_encode($specialists) : '[]' !!};

    document.addEventListener('DOMContentLoaded', function() {
        // Populate Global Selector
        const globalSpecSelect = document.getElementById('globalSpecialistSelect');
        if(globalSpecSelect && Array.isArray(specialists) && specialists.length > 0) {
            specialists.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.text = s.name;
                globalSpecSelect.appendChild(opt);
            });
        }

        // Cargar datos de sessionStorage si existen (desde la agenda)
        const pendingData = sessionStorage.getItem('pendingCheckout');
        if (pendingData) {
            const data = JSON.parse(pendingData);
            
            // Procesar servicios múltiples con especialista
            if (data.services && data.services.length > 0) {
                data.services.forEach(svc => {
                    let found = false;
                    const specId = svc.specialist_id || (data.specialist ? data.specialist.id : null); // Fallback
                    
                    document.querySelectorAll('.item-card').forEach(card => {
                        if (card.dataset.id == svc.id) {
                            addItemToCart(card, specId); // Pass specialist
                            found = true;
                        }
                    });

                    if (!found) {
                        const defaultSpec = specId || (specialists.length > 0 ? specialists[0].id : null);
                        cart.push({
                            id: svc.id || 'temp',
                            name: svc.name,
                            price: parseFloat(svc.price) || 0,
                            qty: 1,
                            specialistId: defaultSpec
                        });
                        lastSelectedSpecialist = defaultSpec; // Update sticky
                    }
                });
                renderCart();
            } else if (data.service) { // Legacy single service
                const items = document.querySelectorAll('.item-card');
                let found = false;
                const specId = data.specialist_id || (data.specialist ? data.specialist.id : null);

                items.forEach(card => {
                    if (card.dataset.name.toLowerCase() === data.service.toLowerCase()) {
                        addItemToCart(card, specId);
                        found = true;
                    }
                });

                if (!found) {
                    const defaultSpec = specId || (specialists.length > 0 ? specialists[0].id : null);
                    cart.push({
                        id: 'temp',
                        name: data.service,
                        price: parseFloat(typeof data.price === 'string' ? data.price.replace(/[^0-9]/g, '') : data.price),
                        qty: 1,
                        specialistId: defaultSpec
                    });
                    lastSelectedSpecialist = defaultSpec; // Update sticky
                    renderCart();
                }
            }

            // Seleccionar cliente si existe
            if (data.customer_id || data.customer_name || data.client) {
                const select = document.getElementById('posCustomerSelect');
                const targetId = data.customer_id;
                const targetName = (data.customer_name || data.client || "").toLowerCase();

                for (let i = 0; i < select.options.length; i++) {
                    if (targetId && select.options[i].value == targetId) {
                        select.selectedIndex = i;
                        break;
                    } else if (targetName && select.options[i].text.toLowerCase().includes(targetName)) {
                        select.selectedIndex = i;
                        break;
                    }
                }
            }

            // Limpiar para que no se recargue siempre
            sessionStorage.removeItem('pendingCheckout');
        }
    });

    function setGlobalSpecialist(val) {
        lastSelectedSpecialist = val;
        const select = document.getElementById('globalSpecialistSelect');
        if(val) {
            select.style.borderColor = '#10a37f';
            select.style.backgroundColor = '#f0fdf4';
            select.style.fontWeight = '600';
        } else {
            select.style.borderColor = '#d1d5db';
            select.style.backgroundColor = 'white';
            select.style.fontWeight = '400';
        }
    }

    function addItemToCart(element, specialistId = null) {
        const id = element.dataset.id;
        const name = element.dataset.name;
        const price = parseFloat(element.dataset.price);

        // Default specialist: passed one, or Last Selected (Sticky), or first in list
        const defaultSpec = specialistId || lastSelectedSpecialist || (specialists.length > 0 ? specialists[0].id : null);
        
        // Update Sticky
        lastSelectedSpecialist = defaultSpec;

        const existing = cart.find(item => item.id === id && item.specialistId == defaultSpec);
        
        // Logic: if same item AND same specialist, increment qty.
        if (existing) {
            existing.qty++;
        } else {
            cart.push({ id, name, price, qty: 1, specialistId: defaultSpec });
        }

        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        if (cart.length === 0) {
            container.innerHTML = '<div class="empty-cart-msg">No hay items seleccionados</div>';
            updateTotals();
            return;
        }

        container.innerHTML = cart.map((item, index) => {
            // Generate Specialist Options
            const specOptions = specialists.map(s => 
                `<option value="${s.id}" ${item.specialistId == s.id ? 'selected' : ''}>${s.name}</option>`
            ).join('');

            return `
            <div class="cart-item" style="flex-wrap:wrap; gap:10px;">
                <div style="display:flex; justify-content:space-between; width:100%;">
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:600; font-size:14px;">${item.name}</span>
                        <span style="font-size:12px; color:#6b7280;">$${new Intl.NumberFormat('es-CO').format(item.price)} x ${item.qty}</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <span style="font-weight:700;">$${new Intl.NumberFormat('es-CO').format(item.price * item.qty)}</span>
                        <button onclick="removeItem(${index})" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:16px;">✕</button>
                    </div>
                </div>
                
                <!-- Specialist Selector -->
                <div style="width:100%; display:flex; align-items:center; gap:5px; font-size:12px;">
                    <span style="color:#6b7280;">Espec:</span>
                    <select onchange="updateItemSpecialist(${index}, this.value)" style="flex:1; padding:4px; border:1px solid #e5e7eb; border-radius:6px; font-size:12px;">
                        <option value="">Seleccionar...</option>
                        ${specOptions}
                    </select>
                </div>
            </div>`;
        }).join('');

        updateTotals();
    }

    function updateItemSpecialist(index, val) {
        cart[index].specialistId = val;
        lastSelectedSpecialist = val; // Update sticky on manual change
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function updateTotals() {
        const subtotal = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('cartDiscount').value) || 0;
        const total = Math.max(0, subtotal - discount);

        document.getElementById('summarySubtotal').innerText = '$' + new Intl.NumberFormat('es-CO').format(subtotal);
        document.getElementById('summaryTotal').innerText = '$' + new Intl.NumberFormat('es-CO').format(total);
        
        // Recalcular cambio si se está pagando en efectivo
        if (selectedMethod === 'Efectivo') {
            calculateChange();
        }
    }

    function selectMethod(method, btn) {
        selectedMethod = method;
        document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        // Mostrar/Ocultar calculador de cambio
        const cashCalc = document.getElementById('cashCalculation');
        if (method === 'Efectivo') {
            cashCalc.style.display = 'block';
            calculateChange();
        } else {
            cashCalc.style.display = 'none';
        }
    }

    function calculateChange() {
        const totalText = document.getElementById('summaryTotal').innerText.replace(/[^0-9]/g, '');
        const total = parseFloat(totalText) || 0;
        const received = parseFloat(document.getElementById('cashReceived').value) || 0;
        const change = Math.max(0, received - total);
        
        document.getElementById('cashChange').innerText = '$' + new Intl.NumberFormat('es-CO').format(change);
        
        // Visual feedback
        if (received >= total && total > 0) {
            document.getElementById('cashChange').style.color = '#10a37f';
        } else if (received > 0) {
            document.getElementById('cashChange').style.color = '#ef4444';
        }
    }

    function filterItems() {
        const query = document.getElementById('posSearch').value.toLowerCase();
        document.querySelectorAll('.item-card').forEach(card => {
            const name = card.dataset.name.toLowerCase();
            card.style.display = name.includes(query) ? 'flex' : 'none';
        });
    }

    function filterCategory(cat) {
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        event.target.classList.add('active');

        document.querySelectorAll('.item-card').forEach(card => {
            if (cat === 'all' || card.dataset.type === cat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // --- FUNCIONALIDAD SUSPENDER VENTA ---
    
    function suspendSale() {
        if (cart.length === 0) {
            alert('No hay items para poner en espera');
            return;
        }

        const customerId = document.getElementById('posCustomerSelect').value;
        const customerName = document.getElementById('posCustomerSelect').options[document.getElementById('posCustomerSelect').selectedIndex].text;
        
        const sale = {
            id: Date.now(),
            date: new Date().toISOString(),
            cart: [...cart],
            customerId: customerId,
            customerName: customerName,
            total: cart.reduce((acc, item) => acc + (item.price * item.qty), 0)
        };

        let suspended = JSON.parse(localStorage.getItem('suspendedSales') || '[]');
        suspended.push(sale);
        localStorage.setItem('suspendedSales', JSON.stringify(suspended));

        clearCart();
        updateSuspendedCount();
        alert('Venta puesta en espera');
    }

    function updateSuspendedCount() {
        const suspended = JSON.parse(localStorage.getItem('suspendedSales') || '[]');
        document.getElementById('suspendedCount').innerText = suspended.length;
    }

    function openSuspendedSales() {
        const suspended = JSON.parse(localStorage.getItem('suspendedSales') || '[]');
        if (suspended.length === 0) {
            alert('No hay ventas en espera');
            return;
        }

        const modalBody = suspended.map((sale, index) => `
            <div style="border: 1px solid #e5e7eb; padding: 12px; border-radius: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-weight:700;">${sale.customerName}</div>
                    <div style="font-size:12px; color:#6b7280;">${new Date(sale.date).toLocaleTimeString()} - ${sale.cart.length} items</div>
                    <div style="font-weight:600; color:#10a37f;">Total: $${new Intl.NumberFormat('es-CO').format(sale.total)}</div>
                </div>
                <div style="display:flex; gap:5px;">
                    <button onclick="resumeSale(${index})" style="background:#111827; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Cargar</button>
                    <button onclick="deleteSuspended(${index})" style="background:#ef4444; color:white; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">✕</button>
                </div>
            </div>
        `).join('');

        // Usar un simple div modal si no hay uno definido
        const modalHtml = `
            <div id="suspendedModal" style="position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; z-index:10000;">
                <div style="background:white; padding:25px; border-radius:20px; width:450px; max-height:80vh; overflow-y:auto;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                        <h3 style="margin:0;">Ventas en Espera</h3>
                        <button onclick="document.getElementById('suspendedModal').remove()" style="background:none; border:none; font-size:24px; cursor:pointer;">&times;</button>
                    </div>
                    ${modalBody}
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    function resumeSale(index) {
        if (cart.length > 0 && !confirm('El carrito actual tiene items. ¿Deseas reemplazarlos por la venta cargada?')) {
            return;
        }

        let suspended = JSON.parse(localStorage.getItem('suspendedSales') || '[]');
        const sale = suspended[index];
        
        cart = sale.cart;
        document.getElementById('posCustomerSelect').value = sale.customerId;
        
        suspended.splice(index, 1);
        localStorage.setItem('suspendedSales', JSON.stringify(suspended));
        
        renderCart();
        updateSuspendedCount();
        document.getElementById('suspendedModal').remove();
    }

    function deleteSuspended(index) {
        if (!confirm('¿Seguro que deseas eliminar esta venta en espera?')) return;
        
        let suspended = JSON.parse(localStorage.getItem('suspendedSales') || '[]');
        suspended.splice(index, 1);
        localStorage.setItem('suspendedSales', JSON.stringify(suspended));
        
        document.getElementById('suspendedModal').remove();
        openSuspendedSales(); // Re-open to refresh
        updateSuspendedCount();
    }

    // Inicializar contador al cargar
    document.addEventListener('DOMContentLoaded', updateSuspendedCount);

    function processSale() {
        if (cart.length === 0) {
            alert('Agrega items a la venta');
            return;
        }

        const btn = document.querySelector('.btn-checkout-pos');
        btn.disabled = true;
        btn.innerText = 'PROCESANDO...';

        // Prepare Payload
        const subtotal = cart.reduce((acc, item) => acc + (item.price * item.qty), 0);
        const discount = parseFloat(document.getElementById('cartDiscount').value) || 0;
        const total = Math.max(0, subtotal - discount);
        const customerId = document.getElementById('posCustomerSelect').value;
        const customerName = document.getElementById('posCustomerSelect').options[document.getElementById('posCustomerSelect').selectedIndex].text;

        const payload = {
            cart: cart,
            subtotal: subtotal,
            discount: discount,
            total: total,
            customer_id: customerId || null,
            customer_name: customerName,
            payment_method: selectedMethod,
            notes: '' // Future expansion
        };

        fetch('{{ url("admin/sales/store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Venta registrada: #' + data.sale_id);
                // Redirect back to POS to continue selling or Dashboard? 
                // Creating a new sale is cleaner than dashboard.
                window.location.reload(); 
            } else {
                // Check if error is "No opened session"
                if (data.message.includes('No hay caja abierta')) {
                    if(confirm('No tienes caja abierta. ¿Ir a abrir caja?')) {
                        window.location.href = "{{ url('admin/caja/apertura') }}";
                    }
                } else {
                    alert('Error: ' + data.message);
                }
                btn.disabled = false;
                btn.innerText = 'FINALIZAR VENTA';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión al guardar venta');
            btn.disabled = false;
            btn.innerText = 'FINALIZAR VENTA';
        });
    }
</script>
@endsection
