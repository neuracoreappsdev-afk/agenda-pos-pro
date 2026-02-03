@extends('admin/dashboard_layout')

@section('content')

<style>
    .advance-container { font-family: 'Outfit', sans-serif; }
    
    .header-card {
        background: white;
        padding: 20px 30px;
        border-radius: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .page-title { margin:0; font-size:18px; color:#374151; font-weight:600; }

    .btn-create {
        background-color: #1a73e8;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        border: none;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }

    /* Tabs */
    .tabs-container {
        display: flex;
        gap: 20px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 20px;
        background: white;
        padding: 0 30px;
        border-radius: 8px 8px 0 0;
    }

    .tab-item {
        padding: 15px 0;
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        margin-bottom: -1px;
        text-decoration: none;
    }
    .tab-item.active {
        color: #1a73e8;
        border-bottom-color: #1a73e8;
    }

    /* Table */
    .table-container { background: white; border-radius: 8px; padding: 20px 30px; }
    
    table { width: 100%; border-collapse: collapse; }
    
    th {
        text-align: left;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        padding: 15px 10px;
        border-bottom: 1px solid #f3f4f6;
    }
    
    td {
        padding: 15px 10px;
        font-size: 14px;
        color: #4b5563;
        border-bottom: 1px solid #f9fafb;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-deducted { background: #d1fae5; color: #065f46; }

    /* Modal */
    #advanceModal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        margin-top: 5px;
    }
    .form-group { margin-bottom: 15px; }
    .form-label { font-weight: 600; font-size: 13px; color: #374151; }

</style>

<div class="advance-container">
    <!-- Header -->
    <div class="header-card">
        <h1 class="page-title">Descuentos y Anticipos de Especialistas</h1>
        <button class="btn-create" onclick="openModal()">+ Nuevo Registro</button>
    </div>

    <!-- Tabs -->
    <div class="tabs-container">
        <a href="{{ url('admin/specialists') }}" class="tab-item">Miembros</a>
        <a href="{{ url('admin/specialists/advances') }}" class="tab-item active">Descuentos</a>
        <a href="{{ url('admin/configuration/importar-especialistas') }}" class="tab-item">Importar Especialistas</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Especialista</th>
                    <th>Concepto</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($advances as $adv)
                <tr>
                    <td>{{ $adv->date->format('d/m/Y') }}</td>
                    <td><strong>{{ $adv->specialist->name }}</strong></td>
                    <td>{{ $adv->reason }}</td>
                    <td>{{ ucfirst($adv->type) }}</td>
                    <td style="font-weight:700;">${{ number_format($adv->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $adv->status }}">
                            {{ $adv->status == 'pending' ? 'Pendiente' : 'Deducido' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#9ca3af;">
                        No hay descuentos o anticipos registrados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="advanceModal">
    <div class="modal-content">
        <h2 style="margin-top:0; font-size:18px; margin-bottom:20px;">Registrar Descuento / Anticipo</h2>
        <form id="advanceForm">
            <div class="form-group">
                <label class="form-label">Especialista</label>
                <select name="specialist_id" class="form-control" required>
                    <option value="">Seleccione un especialista...</option>
                    @foreach($specialists as $sp)
                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Tipo</label>
                <select name="type" class="form-control" required>
                    <option value="descuento">Descuento (Deducción)</option>
                    <option value="anticipo">Anticipo (Adelanto de nómina)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Monto</label>
                <input type="number" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Concepto / Motivo</label>
                <input type="text" name="reason" class="form-control" required placeholder="Ej: Daño de equipo, Anticipo quincena...">
            </div>
            <div class="form-group">
                <label class="form-label">Fecha</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="button" onclick="closeModal()" style="padding:10px 20px; border-radius:6px; border:1px solid #d1d5db; background:white; cursor:pointer;">Cancelar</button>
                <button type="submit" style="padding:10px 20px; border-radius:6px; border:none; background:#1a73e8; color:white; font-weight:600; cursor:pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() { document.getElementById('advanceModal').style.display = 'flex'; }
function closeModal() { document.getElementById('advanceModal').style.display = 'none'; }

document.getElementById('advanceForm').onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => data[key] = value);
    
    fetch('{{ url("admin/specialists/advances") }}', {
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
            alert('Error al guardar: ' + (res.error || 'Desconocido'));
        }
    });
};
</script>

@endsection
