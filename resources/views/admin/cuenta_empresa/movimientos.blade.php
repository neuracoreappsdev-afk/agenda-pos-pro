@extends('admin/dashboard_layout')

@section('content')

<style>
    .mov-container { font-family: 'Outfit', sans-serif; }
    
    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .btn-back {
        background: #f1f5f9;
        color: #475569;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        text-transform: capitalize;
    }

    /* Tabs */
    .tabs-wrapper {
        display: flex;
        gap: 0;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 30px;
    }

    .tab-item {
        padding: 12px 30px;
        font-size: 15px;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all 0.2s;
    }

    .tab-item.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    /* Stats Card Header */
    .summary-card {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .total-val {
        font-size: 32px;
        font-weight: 800;
        color: {{ $tipo == 'ingreso' ? '#166534' : '#991b1b' }};
    }

    .total-lbl {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-create {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Table */
    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
    }
    td {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #1e293b;
    }
    
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-ingreso { background: #dcfce7; color: #166534; }
    .status-egreso { background: #fee2e2; color: #991b1b; }

    .empty-state {
        text-align: center;
        padding: 60px;
        color: #94a3b8;
    }
</style>

<div class="mov-container">
    <div class="top-bar">
        <a href="{{ url('admin/cuenta-empresa') }}" class="btn-back">➔ Regresar al Tablero</a>
        <h1 class="page-title">Gestión de {{ $tipo }}s</h1>
        <button class="btn-create" onclick="openModal()">+ Nuevo {{ ucfirst($tipo) }}</button>
    </div>

    <div class="tabs-wrapper">
        <a href="{{ url('admin/cuenta-empresa/ingresos') }}" class="tab-item {{ $tipo == 'ingreso' ? 'active' : '' }}">Ingresos</a>
        <a href="{{ url('admin/cuenta-empresa/egresos') }}" class="tab-item {{ $tipo == 'egreso' ? 'active' : '' }}">Egresos</a>
    </div>

    <div class="summary-card">
        <div>
            <div class="total-lbl">Gran Total de {{ $tipo }}s</div>
            <div class="total-val">${{ number_format($total, 0, ',', '.') }}</div>
        </div>
        <div style="display: flex; gap: 10px;">
            <input type="date" class="form-control" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 8px;">
            <button class="btn-back" style="background: white; border: 1px solid #e2e8f0;">🔍 Filtrar</button>
        </div>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Referencia</th>
                    <th>Método Pago</th>
                    <th>Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movimientos as $mov)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($mov->movement_date)->format('d/m/Y') }}</td>
                    <td><strong>{{ $mov->concept }}</strong></td>
                    <td>{{ $mov->reference ?? '---' }}</td>
                    <td>{{ $mov->payment_method }}</td>
                    <td style="font-weight: 700;">${{ number_format($mov->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $tipo }}">
                            {{ $tipo }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div style="font-size: 40px; margin-bottom: 10px;">📋</div>
                        <h3>No hay {{ $tipo }}s registrados</h3>
                        <p>Haz clic en "Nuevo {{ ucfirst($tipo) }}" para comenzar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para nuevo movimiento (Simple) -->
<div id="movModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; padding:30px; border-radius:16px; width:450px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h2 style="margin-top:0;">Registrar {{ ucfirst($tipo) }}</h2>
        <form id="movForm">
            <input type="hidden" name="type" value="{{ $tipo }}">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:5px;">Concepto</label>
                <input type="text" name="concept" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:5px;">Monto</label>
                <input type="number" name="amount" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:5px;">Método de Pago</label>
                <select name="payment_method" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px;">
                    <option value="Efectivo">Efectivo</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Tarjeta">Tarjeta</option>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:5px;">Referencia/Notas</label>
                <textarea name="notes" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:8px;"></textarea>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" onclick="closeModal()" style="padding:10px 20px; border-radius:8px; border:none; cursor:pointer;">Cancelar</button>
                <button type="submit" style="background:#3b82f6; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:600;">Guardar {{ ucfirst($tipo) }}</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('movModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('movModal').style.display = 'none';
}

document.getElementById('movForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    
    fetch('{{ url("admin/cuenta-empresa/store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if(res.success) {
            location.reload();
        } else {
            alert('Error: ' + res.error);
        }
    });
};
</script>

@endsection
