@extends('admin/dashboard_layout')

@section('content')

<?php
    // Cargar productos desde la base de datos
    try {
        $products = \App\Models\Product::orderBy('category')->orderBy('name')->get();
        $categoriesRaw = [];
        foreach ($products as $p) {
            if ($p->category) $categoriesRaw[] = $p->category;
        }
        $categories = collect(array_unique($categoriesRaw));
        $lowStockProducts = $products->filter(function($p) { return $p->quantity <= $p->min_quantity; });
    } catch (\Exception $e) {
        $products = collect([]);
        $categories = collect([]);
        $lowStockProducts = collect([]);
    }
?>

<style>
    .inv-container { font-family: 'Outfit', sans-serif; }
    
    .page-header {
        background: linear-gradient(135deg, #0f766e 0%, #134e4a 100%);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .page-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0;
    }
    .page-subtitle {
        color: rgba(255,255,255,0.8);
        margin-top: 8px;
    }
    
    .actions-row {
        display: flex;
        gap: 15px;
    }
    .btn-action {
        background: white;
        color: #0f766e;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-action:hover { background: #f0fdfa; }
    .btn-action.primary {
        background: #fbbf24;
        color: #1f2937;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .stat-icon.products { background: #dbeafe; }
    .stat-icon.low { background: #fee2e2; }
    .stat-icon.value { background: #d1fae5; }
    .stat-icon.cost { background: #fef3c7; }
    .stat-value { font-size: 24px; font-weight: 700; color: #1f2937; }
    .stat-label { font-size: 13px; color: #6b7280; }
    
    .alerts-section {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 25px;
    }
    .alerts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .alerts-title {
        font-size: 16px;
        font-weight: 700;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alerts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 12px;
    }
    .alert-item {
        background: white;
        border-radius: 8px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert-name { font-weight: 600; color: #1f2937; font-size: 14px; }
    .alert-stock { 
        background: #dc2626; 
        color: white; 
        padding: 4px 10px; 
        border-radius: 12px; 
        font-size: 12px; 
        font-weight: 600; 
    }
    
    .table-section {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .table-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .filters-row {
        display: flex;
        gap: 15px;
    }
    .filter-input {
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        min-width: 180px;
    }
    
    table { width: 100%; border-collapse: collapse; }
    thead { background: #f9fafb; }
    th {
        padding: 16px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
    }
    td {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
    }
    tr:hover { background: #f9fafb; }
    
    .product-name { font-weight: 600; color: #1f2937; }
    .product-sku { font-size: 12px; color: #6b7280; }
    .category-badge {
        display: inline-block;
        padding: 4px 10px;
        background: #e0e7ff;
        color: #3730a3;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }
    .price-cell { font-weight: 600; color: #059669; }
    .stock-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .stock-badge.low { background: #fef2f2; color: #dc2626; }
    .stock-badge.ok { background: #f0fdf4; color: #166534; }
    
    .btn-sm {
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 12px;
        border: none;
        cursor: pointer;
        margin-right: 4px;
    }
    .btn-sm.edit { background: #1a73e8; color: white; }
    .btn-sm.adjust { background: #f59e0b; color: white; }
    .btn-sm.delete { background: #fee2e2; color: #dc2626; }
    
    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-overlay.active { display: flex; }
    .modal-container {
        background: white;
        border-radius: 12px;
        width: 500px;
        max-width: 90%;
    }
    .modal-header {
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-title { font-size: 18px; font-weight: 700; }
    .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #6b7280; }
    .modal-body { padding: 20px; }
    .modal-footer {
        padding: 15px 20px;
        background: #f9fafb;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px; }
    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    .btn-save {
        background: #1a73e8;
        color: white;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    .btn-cancel {
        background: #6b7280;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px;
        color: #9ca3af;
    }
    .empty-icon { font-size: 48px; margin-bottom: 15px; }
</style>

<div class="inv-container">
    <!-- Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">📦 Inventario de Productos</h1>
            <p class="page-subtitle">Control completo de stock y movimientos</p>
        </div>
        <div class="actions-row">
            <a href="{{ url('admin/inventario') }}" class="btn-action">📋 Gestión Inventario</a>
            <button class="btn-action primary" onclick="openModal()">+ Nuevo Producto</button>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon products">📦</div>
            <div>
                <div class="stat-value">{{ $products->count() }}</div>
                <div class="stat-label">Total Productos</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon low">⚠️</div>
            <div>
                <div class="stat-value">{{ $lowStockProducts->count() }}</div>
                <div class="stat-label">Stock Bajo</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon value">💰</div>
            <div>
                <div class="stat-value">${{ number_format($products->sum(function($p) { return $p->price * $p->quantity; }), 0, ',', '.') }}</div>
                <div class="stat-label">Valor Inventario (Venta)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon cost">📊</div>
            <div>
                <div class="stat-value">${{ number_format($products->sum(function($p) { return $p->cost * $p->quantity; }), 0, ',', '.') }}</div>
                <div class="stat-label">Valor Inventario (Costo)</div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alerts -->
    @if($lowStockProducts->count() > 0)
    <div class="alerts-section">
        <div class="alerts-header">
            <div class="alerts-title">⚠️ Productos con Stock Bajo ({{ $lowStockProducts->count() }})</div>
            <a href="{{ url('admin/compras/facturas') }}" style="color: #dc2626; font-weight: 600; text-decoration: none;">Realizar Compra →</a>
        </div>
        <div class="alerts-grid">
            @foreach($lowStockProducts->take(8) as $prod)
            <div class="alert-item">
                <span class="alert-name">{{ $prod->name }}</span>
                <span class="alert-stock">{{ $prod->quantity }} unid.</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if(session('success'))
    <div style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:15px 20px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
    @endif
    
    <!-- Products Table -->
    <div class="table-section">
        <div class="table-header">
            <h3 style="margin:0;">Listado de Productos</h3>
            <div class="filters-row">
                <input type="text" id="searchInput" class="filter-input" placeholder="🔍 Buscar producto..." onkeyup="filterTable()">
                <select id="categoryFilter" class="filter-input" onchange="filterTable()">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <select id="stockFilter" class="filter-input" onchange="filterTable()">
                    <option value="">Todo el stock</option>
                    <option value="low">⚠️ Stock Bajo</option>
                    <option value="ok">✓ Stock OK</option>
                </select>
            </div>
        </div>
        
        @if($products->count() > 0)
        <table id="productsTable">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Precio Venta</th>
                    <th>Costo</th>
                    <th>Stock</th>
                    <th>Mínimo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $prod)
                <tr data-category="{{ $prod->category }}" data-stock="{{ $prod->quantity <= $prod->min_quantity ? 'low' : 'ok' }}">
                    <td>
                        <div class="product-name">{{ $prod->name }}</div>
                        @if($prod->sku)
                        <div class="product-sku">SKU: {{ $prod->sku }}</div>
                        @endif
                    </td>
                    <td>
                        @if($prod->category)
                            <span class="category-badge">{{ $prod->category }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="price-cell">${{ number_format($prod->price, 0, ',', '.') }}</td>
                    <td>${{ number_format($prod->cost, 0, ',', '.') }}</td>
                    <td><strong>{{ $prod->quantity }}</strong></td>
                    <td>{{ $prod->min_quantity }}</td>
                    <td>
                        @if($prod->quantity <= $prod->min_quantity)
                            <span class="stock-badge low">⚠️ Bajo</span>
                        @else
                            <span class="stock-badge ok">✓ OK</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-sm edit" onclick="editProduct({{ $prod->id }})">Editar</button>
                        <button class="btn-sm adjust" onclick="adjustStock({{ $prod->id }}, '{{ $prod->name }}', {{ $prod->quantity }})">Ajustar</button>
                        <form action="{{ url('admin/productos/'.$prod->id.'/delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este producto?');">
                            {{ csrf_field() }}
                            <button type="submit" class="btn-sm delete">🗑</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <div class="empty-icon">📦</div>
            <h3>No hay productos registrados</h3>
            <p>Agrega productos para comenzar a gestionar tu inventario</p>
            <button class="btn-save" style="margin-top:20px;" onclick="openModal()">+ Agregar Producto</button>
        </div>
        @endif
    </div>
</div>

<!-- Modal Nuevo/Editar Producto -->
<div class="modal-overlay" id="productModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Nuevo Producto</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="productForm" method="POST" action="{{ url('admin/productos') }}">
            {{ csrf_field() }}
            <input type="hidden" name="product_id" id="productId">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" name="name" id="prodName" class="form-input" required>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" id="prodSku" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <input type="text" name="category" id="prodCategory" class="form-input" list="categoryList">
                        <datalist id="categoryList">
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Precio de Venta *</label>
                        <input type="number" name="price" id="prodPrice" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Costo</label>
                        <input type="number" name="cost" id="prodCost" class="form-input" value="0">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Cantidad en Stock *</label>
                        <input type="number" name="quantity" id="prodQuantity" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock Mínimo</label>
                        <input type="number" name="min_quantity" id="prodMinQuantity" class="form-input" value="5">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-save">💾 Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ajuste de Stock -->
<div class="modal-overlay" id="adjustModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Ajustar Stock</h3>
            <button class="modal-close" onclick="closeAdjustModal()">&times;</button>
        </div>
        <form method="POST" action="{{ url('admin/productos/adjust-stock') }}">
            {{ csrf_field() }}
            <input type="hidden" name="product_id" id="adjustProductId">
            <div class="modal-body">
                <p style="margin-bottom:20px;"><strong id="adjustProductName"></strong></p>
                <p style="margin-bottom:20px;">Stock actual: <strong id="adjustCurrentStock"></strong> unidades</p>
                
                <div class="form-group">
                    <label class="form-label">Tipo de Ajuste</label>
                    <select name="adjustment_type" class="form-input" required>
                        <option value="add">➕ Agregar al stock</option>
                        <option value="remove">➖ Restar del stock</option>
                        <option value="set">📝 Establecer cantidad exacta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Cantidad</label>
                    <input type="number" name="quantity" class="form-input" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Motivo del ajuste</label>
                    <select name="reason" class="form-input">
                        <option value="inventario">Conteo de inventario</option>
                        <option value="dañado">Producto dañado</option>
                        <option value="perdida">Pérdida/Robo</option>
                        <option value="devolucion">Devolución de proveedor</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notas (opcional)</label>
                    <textarea name="notes" class="form-input" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAdjustModal()">Cancelar</button>
                <button type="submit" class="btn-save">✓ Aplicar Ajuste</button>
            </div>
        </form>
    </div>
</div>

<script>
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const categoryValue = document.getElementById('categoryFilter').value;
    const stockValue = document.getElementById('stockFilter').value;
    const rows = document.querySelectorAll('#productsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const category = row.dataset.category || '';
        const stock = row.dataset.stock || '';
        
        let showBySearch = text.includes(searchValue);
        let showByCategory = categoryValue === '' || category === categoryValue;
        let showByStock = stockValue === '' || stock === stockValue;
        
        row.style.display = (showBySearch && showByCategory && showByStock) ? '' : 'none';
    });
}

function openModal() {
    document.getElementById('modalTitle').textContent = 'Nuevo Producto';
    document.getElementById('productForm').action = '{{ url("admin/productos") }}';
    document.getElementById('productId').value = '';
    document.getElementById('prodName').value = '';
    document.getElementById('prodSku').value = '';
    document.getElementById('prodCategory').value = '';
    document.getElementById('prodPrice').value = '';
    document.getElementById('prodCost').value = '0';
    document.getElementById('prodQuantity').value = '';
    document.getElementById('prodMinQuantity').value = '5';
    document.getElementById('productModal').classList.add('active');
}

function closeModal() {
    document.getElementById('productModal').classList.remove('active');
}

function editProduct(id) {
    fetch('{{ url("admin/productos") }}/' + id + '/json')
        .then(res => res.json())
        .then(prod => {
            document.getElementById('modalTitle').textContent = 'Editar Producto';
            document.getElementById('productForm').action = '{{ url("admin/productos") }}/' + id + '/update';
            document.getElementById('productId').value = prod.id;
            document.getElementById('prodName').value = prod.name;
            document.getElementById('prodSku').value = prod.sku || '';
            document.getElementById('prodCategory').value = prod.category || '';
            document.getElementById('prodPrice').value = prod.price;
            document.getElementById('prodCost').value = prod.cost || 0;
            document.getElementById('prodQuantity').value = prod.quantity;
            document.getElementById('prodMinQuantity').value = prod.min_quantity || 5;
            document.getElementById('productModal').classList.add('active');
        });
}

function adjustStock(id, name, currentStock) {
    document.getElementById('adjustProductId').value = id;
    document.getElementById('adjustProductName').textContent = name;
    document.getElementById('adjustCurrentStock').textContent = currentStock;
    document.getElementById('adjustModal').classList.add('active');
}

function closeAdjustModal() {
    document.getElementById('adjustModal').classList.remove('active');
}
</script>

@endsection
