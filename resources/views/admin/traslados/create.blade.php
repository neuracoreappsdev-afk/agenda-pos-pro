@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Nuevo Traslado</h1>
        <div class="breadcrumb">{{ trans('messages.home') }} / Inventario / <a href="{{ url('admin/traslados') }}">Traslados</a> / Nuevo</div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ url('admin/traslados/store') }}">
            {{ csrf_field() }}
            
            <div class="form-section">
                <h3>Información del Traslado</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Número de Traslado</label>
                        <input type="text" name="transfer_number" class="form-control" value="TR-{{ date('Ymd') }}-{{ rand(100,999) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Fecha</label>
                        <input type="date" name="transfer_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Sede Origen</label>
                        <select name="from_location" class="form-control" required>
                            <option value="Principal">Sede Principal</option>
                            <option value="Sucursal 1">Sucursal 1</option>
                            <option value="Sucursal 2">Sucursal 2</option>
                            <option value="Bodega">Bodega Central</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Sede Destino</label>
                        <select name="to_location" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="Principal">Sede Principal</option>
                            <option value="Sucursal 1">Sucursal 1</option>
                            <option value="Sucursal 2">Sucursal 2</option>
                            <option value="Bodega">Bodega Central</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Productos a Trasladar</h3>
                <div id="productsContainer">
                    <div class="product-row">
                        <select name="products[0][product_id]" class="form-control product-select" required>
                            <option value="">Seleccione producto...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" data-stock="{{ $p->quantity }}">{{ $p->name }} (Stock: {{ $p->quantity }})</option>
                            @endforeach
                        </select>
                        <input type="number" name="products[0][quantity]" class="form-control qty-input" placeholder="Cantidad" min="1" required>
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">🗑️</button>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addProductRow()">+ Agregar Producto</button>
            </div>

            <div class="form-section">
                <div class="form-group full-width">
                    <label>Notas (Opcional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Observaciones sobre el traslado..."></textarea>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ url('admin/traslados') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Traslado</button>
            </div>
        </form>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    .breadcrumb a { color: #1a73e8; text-decoration: none; }

    .form-card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; padding: 30px; margin-top: 25px; }
    .form-section { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
    .form-section:last-of-type { border-bottom: none; }
    .form-section h3 { font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 20px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .form-control { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.1); }

    .product-row { display: flex; gap: 15px; margin-bottom: 15px; align-items: center; }
    .product-select { flex: 2; }
    .qty-input { flex: 1; max-width: 120px; }
    .btn-remove { background: #fee2e2; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
    
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
    .btn-primary { background: #1a73e8; color: white; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
</style>

<script>
let productIndex = 1;
function addProductRow() {
    const container = document.getElementById('productsContainer');
    const row = document.createElement('div');
    row.className = 'product-row';
    row.innerHTML = `
        <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
            <option value="">Seleccione producto...</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->quantity }})</option>
            @endforeach
        </select>
        <input type="number" name="products[${productIndex}][quantity]" class="form-control qty-input" placeholder="Cantidad" min="1" required>
        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">🗑️</button>
    `;
    container.appendChild(row);
    productIndex++;
}
</script>
@endsection
