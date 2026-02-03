@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Listado de Clientes</h1>
        <div class="breadcrumb">Informes / Clientes / Directorio General</div>
    </div>

    <div class="filter-bar">
        <form method="GET" class="date-form" onsubmit="return false;">
            <div class="group" style="flex:1;">
                <label>Buscar Cliente</label>
                <input type="text" id="searchCustomer" class="control" placeholder="Nombre, email o teléfono..." onkeyup="filterCustomers()">
            </div>
            <button type="button" class="btn-filter" onclick="window.print()" style="background:#1f2937;">Imprimir Listado</button>
        </form>
    </div>

    <!-- KPIs Summary -->
    <div class="grid-stats">
        <div class="stat-card">
            <h3>Total Clientes</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $totalClientes }}</div>
            <div class="stat-desc">Base de datos histórica</div>
        </div>
        <div class="stat-card">
            <h3>Fidelizados</h3>
            <div class="stat-value" style="color:#10b981;">{{ $clientesNuevos }}</div>
            <div class="stat-desc">Nuevos este mes</div>
        </div>
        <div class="stat-card">
            <h3>Con Email</h3>
            <?php $withEmail = $customers->filter(function($c){ return !empty($c->email); })->count(); ?>
            <div class="stat-value">{{ $withEmail }}</div>
            <div class="stat-desc">Listos para marketing</div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card-table" style="margin-top:30px;">
        <div class="card-header">
            <h3>Directorio de Clientes</h3>
        </div>
        <table class="data-table" id="customersTable">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Contacto</th>
                    <th>Registro</th>
                    <th style="text-align:center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr class="customer-row">
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:35px; height:35px; border-radius:50%; background:#eff6ff; color:#1d4ed8; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px;">
                                {{ strtoupper(substr($customer->name ?: 'C', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700; color:#111827;">{{ $customer->name }}</div>
                                <div style="font-size:11px; color:#9ca3af;">ID #{{ $customer->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px; font-weight:600; color:#4b5563;">{{ $customer->email ?: 'Sin email' }}</div>
                        <div style="font-size:12px; color:#6b7280;">{{ $customer->phone ?: 'Sin teléfono' }}</div>
                    </td>
                    <td>
                        <div style="font-size:13px; font-weight:600; color:#4b5563;">{{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '---' }}</div>
                    </td>
                    <td style="text-align:center;">
                        <span style="background:#dcfce7; color:#166534; padding:4px 10px; border-radius:12px; font-size:10px; font-weight:800; text-transform:uppercase;">ACTIVO</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function filterCustomers() {
    const search = document.getElementById('searchCustomer').value.toLowerCase();
    const rows = document.querySelectorAll('.customer-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(search) ? '' : 'none';
    });
}
</script>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .date-form { display: flex; gap: 20px; align-items: flex-end; }
    .group { display: flex; flex-direction: column; gap: 5px; }
    .group label { font-size: 13px; font-weight: 600; color: #4b5563; }
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 8px; outline: none; }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 700; }

    .grid-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .stat-card { background: white; border-radius: 12px; padding: 30px; text-align: center; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .stat-value { font-size: 32px; font-weight: 800; color: #111827; margin: 10px 0; }
    .stat-desc { color: #6b7280; font-size: 13px; }
    .stat-card h3 { color: #4b5563; font-size: 13px; font-weight: 600; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }

    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    
    @media print {
        .filter-bar, .breadcrumb, .btn-filter { display: none !important; }
        .report-container { padding: 0; }
        .grid-stats { grid-template-columns: repeat(3, 1fr) !important; }
        .stat-card { box-shadow: none; border: 1px solid #eee; }
    }
</style>
@endsection
