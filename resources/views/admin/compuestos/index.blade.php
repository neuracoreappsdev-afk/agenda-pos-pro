@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Productos Compuestos (Kits)</h1>
        <div class="breadcrumb">{{ trans('messages.home') }} / Inventario / Compuestos</div>
    </div>

    <div class="actions-bar">
        <a href="{{ url('admin/compuestos/create') }}" class="btn btn-primary">+ Nuevo Kit</a>
    </div>

    <div class="info-card">
        <div class="info-icon">📦</div>
        <div class="info-text">
            <strong>¿Qué son los productos compuestos?</strong>
            <p>Son kits o combos que agrupan varios productos individuales. Al vender un kit, se descuenta automáticamente el stock de cada componente.</p>
        </div>
    </div>

    <!-- Kits Table -->
    <div class="card-table">
        <div class="card-header">
            <h3>Kits Configurados</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nombre del Kit</th>
                    <th>SKU</th>
                    <th>Componentes</th>
                    <th>Precio Kit</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($composites as $kit)
                <tr>
                    <td>
                        <div class="kit-name">{{ $kit->name }}</div>
                        <div class="kit-desc">{{ $kit->description }}</div>
                    </td>
                    <td><span class="sku-badge">{{ $kit->sku ?: 'N/A' }}</span></td>
                    <td>{{ $kit->items_count ?? 0 }} productos</td>
                    <td class="price-cell">$ {{ number_format($kit->price, 0) }}</td>
                    <td>
                        @if($kit->active)
                            <span class="status-badge active">Activo</span>
                        @else
                            <span class="status-badge inactive">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-icon" title="Editar" onclick="alert('Editar kit')">✏️</button>
                        <button class="btn-icon" title="Ver Componentes" onclick="alert('Ver componentes')">👁️</button>
                        <button class="btn-icon danger" title="Eliminar" onclick="alert('Eliminar')">🗑️</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div style="font-size:32px; margin-bottom:10px;">📦</div>
                        <p>No hay kits configurados</p>
                        <a href="{{ url('admin/compuestos/create') }}" class="btn btn-primary" style="margin-top:15px;">Crear Primer Kit</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .actions-bar { margin: 25px 0; }
    .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-block; }
    .btn-primary { background: #1a73e8; color: white; }

    .info-card { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 20px; display: flex; gap: 15px; align-items: center; margin-bottom: 25px; }
    .info-icon { font-size: 32px; }
    .info-text strong { color: #1e40af; }
    .info-text p { margin: 5px 0 0; color: #3b82f6; font-size: 14px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; }
    .card-header h3 { margin: 0; font-size: 16px; font-weight: 700; color: #1f2937; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 12px; font-weight: 600; color: #4b5563; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 14px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #374151; }
    .data-table tr:hover { background: #f9fafb; }
    
    .kit-name { font-weight: 700; color: #1f2937; }
    .kit-desc { font-size: 12px; color: #6b7280; margin-top: 3px; }
    .sku-badge { background: #f3f4f6; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-size: 12px; }
    .price-cell { font-weight: 700; color: #10b981; }
    
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .status-badge.active { background: #dcfce7; color: #166534; }
    .status-badge.inactive { background: #fee2e2; color: #991b1b; }
    
    .btn-icon { background: none; border: none; cursor: pointer; font-size: 16px; padding: 5px; opacity: 0.6; transition: all 0.2s; }
    .btn-icon:hover { opacity: 1; }
    
    .empty-state { text-align: center; padding: 40px; color: #9ca3af; }
</style>
@endsection
