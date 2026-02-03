@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Nuevo Producto Compuesto</h1>
        <div class="breadcrumb">{{ trans('messages.home') }} / Inventario / <a href="{{ url('admin/compuestos') }}">Compuestos</a> / Nuevo</div>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ url('admin/compuestos/store') }}">
            {{ csrf_field() }}
            
            <div class="form-section">
                <h3>Información del Kit</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre del Kit *</label>
                        <input type="text" name="name" class="form-control" placeholder="Ej: Kit Tratamiento Facial" required>
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" class="form-control" placeholder="Código único">
                    </div>
                    <div class="form-group">
                        <label>Categoría</label>
                        <input type="text" name="category" class="form-control" placeholder="Ej: Kits, Combos">
                    </div>
                    <div class="form-group">
                        <label>Precio del Kit *</label>
                        <input type="number" name="price" class="form-control" placeholder="0" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Descripción</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Descripción detallada del kit..."></textarea>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Componentes del Kit</h3>
                <p class="help-text">Seleccione los productos que conforman este kit y la cantidad de cada uno.</p>
                
                <div id="componentsContainer">
                    <div class="component-row">
                        <select name="components[0][product_id]" class="form-control product-select" required>
                            <option value="">Seleccione producto...</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} - ${{ number_format($p->price, 0) }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="components[0][quantity]" class="form-control qty-input" placeholder="Cantidad" value="1" min="1" required>
                        <button type="button" class="btn-remove" onclick="this.parentElement.remove()">🗑️</button>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addComponentRow()">+ Agregar Componente</button>
            </div>

            <div class="summary-card">
                <div class="summary-title">Resumen</div>
                <div class="summary-row">
                    <span>Componentes:</span>
                    <span id="componentCount">1</span>
                </div>
                <div class="summary-row">
                    <span>Precio individual estimado:</span>
                    <span id="individualPrice">--</span>
                </div>
                <div class="summary-row total">
                    <span>Precio del Kit:</span>
                    <span id="kitPrice">--</span>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ url('admin/compuestos') }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Crear Kit</button>
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
    .form-section h3 { font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 15px; }
    .help-text { color: #6b7280; font-size: 13px; margin-bottom: 15px; }
    
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .form-control { padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; }
    .form-control:focus { outline: none; border-color: #1a73e8; box-shadow: 0 0 0 3px rgba(26,115,232,0.1); }

    .component-row { display: flex; gap: 15px; margin-bottom: 15px; align-items: center; }
    .product-select { flex: 2; }
    .qty-input { flex: 1; max-width: 100px; }
    .btn-remove { background: #fee2e2; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; }
    
    .summary-card { background: #f9fafb; border-radius: 12px; padding: 20px; margin: 20px 0; }
    .summary-title { font-weight: 700; color: #1f2937; margin-bottom: 15px; }
    .summary-row { display: flex; justify-content: space-between; padding: 8px 0; font-size: 14px; color: #4b5563; }
    .summary-row.total { border-top: 1px solid #e5e7eb; padding-top: 12px; margin-top: 8px; font-weight: 700; color: #1f2937; }
    
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
    .btn-primary { background: #1a73e8; color: white; }
    .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
    
    .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 30px; }
</style>

<script>
let componentIndex = 1;
function addComponentRow() {
    const container = document.getElementById('componentsContainer');
    const row = document.createElement('div');
    row.className = 'component-row';
    row.innerHTML = `
        <select name="components[${componentIndex}][product_id]" class="form-control product-select" required>
            <option value="">Seleccione producto...</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}">{{ $p->name }} - ${{ number_format($p->price, 0) }}</option>
            @endforeach
        </select>
        <input type="number" name="components[${componentIndex}][quantity]" class="form-control qty-input" placeholder="Cantidad" value="1" min="1" required>
        <button type="button" class="btn-remove" onclick="this.parentElement.remove(); updateCount();">🗑️</button>
    `;
    container.appendChild(row);
    componentIndex++;
    updateCount();
}

function updateCount() {
    const count = document.querySelectorAll('.component-row').length;
    document.getElementById('componentCount').textContent = count;
}
</script>
@endsection
