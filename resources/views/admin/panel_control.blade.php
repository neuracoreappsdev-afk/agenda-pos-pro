@extends('admin/dashboard_layout')

@section('content')

<style>
    :root {
        --primary-blue: #3b82f6;
        --dark-blue: #0f4a72;
        --indigo: #4f46e5;
        --border-color: #e5e7eb;
        --text-main: #111827;
        --text-secondary: #6b7280;
        --bg-light: #f8fafc;
    }

    .panel-container {
        padding: 24px;
        background: #fdfdfd;
        min-height: 100vh;
    }

    .panel-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .panel-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--text-main);
    }

    .business-selector {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 12px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        border: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    /* Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        height: min-content;
    }

    .card-full {
        grid-column: span 2;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Date & Select Inputs */
    .input-standard {
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 14px;
        color: var(--text-main);
        background: #fff;
    }

    .btn-refresh {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: var(--text-main);
        padding: 5px;
        transition: transform 0.2s;
    }
    .btn-refresh:hover { transform: rotate(45deg); }

    /* Stats & Widgets */
    .stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .stat-widget {
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }

    .stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #0369a1;
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 0.025em;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: var(--dark-blue);
    }

    .ticket-main {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 20px;
    }

    .ticket-value {
        font-size: 48px;
        font-weight: 800;
        color: var(--dark-blue);
    }

    /* Filters for Ticket */
    .radio-group {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 15px;
    }

    .radio-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--text-secondary);
        cursor: pointer;
    }
    .radio-item input { accent-color: var(--primary-blue); }

    /* Alerts */
    .alert-low-stock {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #c53030;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 16px;
    }

    .count-badge {
        background: #ef4444;
        color: #fff;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
    }

    /* Tables */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .standard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .standard-table th {
        text-align: left;
        padding: 12px 8px;
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
        text-transform: uppercase;
    }

    .standard-table td {
        padding: 14px 8px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
    }

    .standard-table tr:last-child td { border-bottom: none; }

    .text-right { text-align: right; }
    .font-bold { font-weight: 700; }
    .text-secondary { color: var(--text-secondary); font-size: 11px; }

    /* Charts */
    .chart-box {
        position: relative;
        height: 280px;
        width: 100%;
    }

    /* Comparison Indicators */
    .comp-up { color: #10b981; font-weight: bold; }
    .comp-down { color: #ef4444; font-weight: bold; }

    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: 1fr; }
        .card-full { grid-column: auto; }
    }
</style>

<div class="panel-container">
    
    <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h1 class="panel-title">{{ trans('messages.panel_control') }}</h1>
    </div>

    <form action="{{ url('admin/panel-control') }}" method="GET" id="dashboardFilters">
        <!-- Business & Specialist Selector -->
        <div class="business-selector">
            <div style="display:flex; align-items:center; gap:20px; flex:1;">
                <span class="font-bold">{{ trans('messages.specialist_label') }}:</span>
                <select name="specialist_id" class="input-standard" style="min-width:250px;" onchange="this.form.submit()">
                    <option value="">{{ trans('messages.all_specialists') }}</option>
                    @foreach($all_specialists as $sp)
                    <option value="{{ $sp->id }}" {{ $selected_specialist == $sp->id ? 'selected' : '' }}>{{ strtoupper($sp->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="text-secondary" style="font-size:14px;">
                Holguínes Trade Center. <span>▼</span>
            </div>
        </div>

        <!-- Date Filters -->
        <div class="filter-card">
            <div style="display:flex; align-items:center; gap:15px;">
                <span class="font-bold" style="font-size:14px;">{{ trans('messages.date_range') }}</span>
                <input type="date" name="start_date" class="input-standard" value="{{ $startDate }}">
                <span style="color:#cbd5e1;">—</span>
                <input type="date" name="end_date" class="input-standard" value="{{ $endDate }}">
                <button type="submit" class="btn-checkout" style="padding:8px 20px; font-size:13px; border-radius:8px;">{{ trans('messages.update_panel') }}</button>
            </div>
            <div>
                <button type="button" class="btn-secondary-sm" onclick="window.location.href='{{ url('admin/panel-control') }}'">{{ trans('messages.clear_filters') }}</button>
            </div>
        </div>
    </form>

    <div class="dashboard-grid">
        
        <!-- ROW 1 LEFT: Historial de Ventas -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.sales_history') }}</span>
                <button class="btn-refresh" onclick="location.reload()">↻</button>
            </div>
            <div class="chart-box">
                <canvas id="salesBarChart"></canvas>
            </div>

            <div style="margin-top:40px;">
                <span class="card-title" style="display:block; margin-bottom:15px;">{{ trans('messages.recent_sales') }}</span>
                <div class="table-responsive">
                    <table class="standard-table">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.date') }}</th>
                                <th>{{ trans('messages.client') }}</th>
                                <th>{{ trans('messages.specialist_label') }}</th>
                                <th class="text-right">{{ trans('messages.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_sales as $sale)
                            <tr>
                                <td>{{ date('d/m/Y H:i', strtotime($sale->sale_date)) }}</td>
                                <td>{{ $sale->customer ? ($sale->customer->first_name . ' ' . $sale->customer->last_name) : trans('messages.generic_customer') }}</td>
                                <td>{{ $sale->specialist_name ?? 'N/A' }}</td>
                                <td class="text-right font-bold">$ {{ number_format($sale->total, 0, '.', ',') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" style="text-align:center; padding:30px; color:#94a3b8;">{{ trans('messages.no_sales_period') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ROW 1 RIGHT: Clientes Hoy + Resumen Período -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">📊 Resumen del Período</span>
            </div>
            
            <!-- Total Ventas Período con Comparativa -->
            <div class="ticket-main" style="margin-bottom:15px; padding:30px 20px;">
                <div class="stat-label" style="color:#64748b;">💵 TOTAL FACTURADO</div>
                <div class="ticket-value" style="color:#10b981;">$ {{ number_format($totalSalesPeriod ?? 0, 0, '.', ',') }}</div>
                <div style="margin-top:10px; font-size:13px;">
                    @if(($salesVariation ?? 0) > 0)
                        <span class="comp-up">↑ {{ $salesVariation }}% vs período anterior</span>
                    @elseif(($salesVariation ?? 0) < 0)
                        <span class="comp-down">↓ {{ abs($salesVariation) }}% vs período anterior</span>
                    @else
                        <span style="color:#94a3b8;">Sin variación</span>
                    @endif
                </div>
            </div>

            <!-- Mini Stats -->
            <div class="stats-row">
                <div class="stat-widget">
                    <div class="stat-label">📅 {{ trans('messages.clients_scheduled_today') }}</div>
                    <div class="stat-value">{{ $clients_today_scheduled }}</div>
                </div>
                <div class="stat-widget">
                    <div class="stat-label">💲 {{ trans('messages.clients_bought_today') }}</div>
                    <div class="stat-value">{{ $clients_today_bought }}</div>
                </div>
            </div>
            <div style="margin-top:20px; text-align:center;">
                <a href="{{ url('admin/waitlist') }}" style="color:var(--indigo); font-size:14px; font-weight:600; text-decoration:none;">{{ trans('messages.view_detailed') }} →</a>
            </div>
        </div>

        <!-- NEW: Ventas del Día por Especialista -->
        <div class="card card-full">
            <div class="card-header">
                <span class="card-title">👩‍💼 Ventas del Día por Especialista</span>
                <span style="font-size:12px; color:#64748b;">Hoy: {{ date('d/m/Y') }}</span>
            </div>
            <div class="table-responsive">
                <table class="standard-table">
                    <thead>
                        <tr>
                            <th>Especialista</th>
                            <th class="text-right"># Ventas</th>
                            <th class="text-right">Servicios</th>
                            <th class="text-right">Productos</th>
                            <th class="text-right">Ingresos</th>
                            <th class="text-right">Comisión</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesTodayBySpecialist ?? [] as $spec)
                        <tr>
                            <td class="font-bold">{{ strtoupper($spec->specialist_name ?? 'N/A') }}</td>
                            <td class="text-right">{{ $spec->num_sales ?? 0 }}</td>
                            <td class="text-right" style="color:#8b5cf6;">{{ $spec->services_count ?? 0 }}</td>
                            <td class="text-right" style="color:#f59e0b;">{{ $spec->products_count ?? 0 }}</td>
                            <td class="text-right font-bold" style="color:#10b981;">$ {{ number_format($spec->total_revenue ?? 0, 0, '.', ',') }}</td>
                            <td class="text-right" style="color:#3b82f6;">$ {{ number_format($spec->total_commission ?? 0, 0, '.', ',') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:30px; color:#94a3b8;">
                                <div style="font-size:32px; margin-bottom:10px;">📭</div>
                                No hay ventas registradas hoy. ¡Es hora de abrir caja!
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if(count($salesTodayBySpecialist ?? []) > 0)
                    <tfoot style="background:#f8fafc; font-weight:700;">
                        <tr>
                            <td>TOTAL DÍA</td>
                            <td class="text-right">{{ collect($salesTodayBySpecialist)->sum('num_sales') }}</td>
                            <td class="text-right" style="color:#8b5cf6;">{{ collect($salesTodayBySpecialist)->sum('services_count') }}</td>
                            <td class="text-right" style="color:#f59e0b;">{{ collect($salesTodayBySpecialist)->sum('products_count') }}</td>
                            <td class="text-right" style="color:#10b981;">$ {{ number_format(collect($salesTodayBySpecialist)->sum('total_revenue'), 0, '.', ',') }}</td>
                            <td class="text-right" style="color:#3b82f6;">$ {{ number_format(collect($salesTodayBySpecialist)->sum('total_commission'), 0, '.', ',') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>


        <!-- ROW 2 LEFT: Ticket Promedio -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.average_sales_ticket') }}</span>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="input-standard" style="padding:4px 10px; font-size:12px;">{{ trans('messages.this_period') }} ▼</span>
                    <button class="btn-refresh">↻</button>
                </div>
            </div>
            
            <div class="ticket-main">
                <div class="stat-label" style="color:#64748b;">💲 {{ trans('messages.average_sales_ticket') }}</div>
                <div class="ticket-value" id="mainTicketValue">$ {{ number_format($average_ticket, 0, '.', ',') }}</div>
            </div>

            <div class="radio-group">
                <label class="radio-item">
                    <input type="radio" name="tk_filter" value="all" checked onchange="updateTicket('$ {{ number_format($average_ticket, 0, '.', ',') }}')"> {{ trans('messages.all') }}
                </label>
                <label class="radio-item">
                    <input type="radio" name="tk_filter" value="products" onchange="updateTicket('$ {{ number_format($avg_products, 0, '.', ',') }}')"> {{ trans('messages.products_only') }}
                </label>
                <label class="radio-item">
                    <input type="radio" name="tk_filter" value="services" onchange="updateTicket('$ {{ number_format($avg_services, 0, '.', ',') }}')"> {{ trans('messages.services_only') }}
                </label>
                <label class="radio-item">
                    <input type="radio" name="tk_filter" value="clients" onchange="updateTicket('$ {{ number_format($average_ticket, 0, '.', ',') }}')"> {{ trans('messages.clients_only') }}
                </label>
            </div>
        </div>

        <!-- ROW 2 RIGHT: Productos -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.products_title') }}</span>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="input-standard" style="padding:4px 10px; font-size:12px;">{{ trans('messages.stock_status') }} ▼</span>
                    <button class="btn-refresh">↻</button>
                </div>
            </div>

            <div class="alert-low-stock">
                <span>{{ trans('messages.low_stock_alert') }}</span>
                <div style="display:flex; align-items:center; gap:8px;">
                    <span class="count-badge">{{ $low_stock_count }}</span>
                    <span>👁</span>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; padding:10px 5px; font-size:14px; color:var(--text-main);">
                <span>{{ trans('messages.inventory_quantity') }}</span>
                <span class="font-bold">{{ number_format($total_inventory, 0) }}</span>
            </div>

            <div style="margin-top:25px; text-align:center;">
                <span class="font-bold" style="font-size:13px;">{{ trans('messages.top_selling_products') }}</span>
            </div>

            <div class="table-responsive" style="margin-top:15px;">
                <table class="standard-table">
                    <thead>
                        <tr>
                            <th>{{ trans('messages.product') }}</th>
                            <th class="text-right">{{ trans('messages.sold') }}</th>
                            <th class="text-right">{{ trans('messages.total_value') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_products as $tp)
                        <tr>
                            <td>
                                <div>{{ $tp->name }}</div>
                                <div class="text-secondary">{{ $tp->stock }} {{ trans('messages.stocks') }}</div>
                            </td>
                            <td class="text-right">{{ $tp->sold }}</td>
                            <td class="text-right font-bold">$ {{ number_format($tp->total, 0, '.', ',') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ROW 3 LEFT: Top Servicios -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.top_services') }}</span>
                <span class="input-standard" style="padding:4px 10px; font-size:12px;">{{ trans('messages.comparative_branch_sales') }} ▼</span>
            </div>
            <div class="table-responsive">
                <table class="standard-table">
                    <thead>
                        <tr>
                            <th>{{ trans('messages.service') }}</th>
                            <th class="text-right">{{ trans('messages.cumulative') }}</th>
                            <th class="text-right">{{ trans('messages.this_period') }}</th>
                            <th class="text-right">{{ trans('messages.previous_period') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_services as $ts)
                        <tr>
                            <td class="font-bold">{{ $ts['name'] }}</td>
                            <td class="text-right">{{ $ts['total'] }}</td>
                            <td class="text-right" style="background:#f0fdf4; color:#166534;">{{ $ts['this_month'] }}</td>
                            <td class="text-right" style="background:#fef2f2; color:#991b1b;">{{ $ts['last_month'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ROW 3 RIGHT: No realizados -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.not_performed_services') }}</span>
                <span class="btn-refresh">↻</span>
            </div>
            <div class="table-responsive">
                <table class="standard-table">
                    <thead><tr><th>{{ trans('messages.services_no_sales') }}</th></tr></thead>
                    <tbody>
                        @foreach($services_not_performed as $snp)
                        <tr><td>{{ $snp }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ROW 4 LEFT: Especialistas -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.top_specialists_sales') }}</span>
                <span class="btn-refresh">↻</span>
            </div>
            <div class="table-responsive">
                <table class="standard-table">
                    <thead>
                        <tr>
                            <th>{{ trans('messages.specialist_label') }}</th>
                            <th class="text-right">{{ trans('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($top_specialists as $tsp)
                        <tr>
                            <td>{{ strtoupper($tsp->name) }}</td>
                            <td class="text-right font-bold">$ {{ number_format($tsp->total, 0, '.', ',') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ROW 4 RIGHT: Pie Chart -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.service_distribution') }}</span>
                <span class="btn-refresh">↻</span>
            </div>
            <div class="chart-box" style="height:350px;">
                <canvas id="servicesPieChart"></canvas>
            </div>
        </div>

        <!-- ROW 5: Gastos -->
        <div class="card card-full">
            <div class="card-header">
                <span class="card-title">{{ trans('messages.business_expenses') }}</span>
                <div style="display:flex; align-items:center; gap:10px;">
                    <span class="font-bold" style="color:var(--indigo);">TOTAL: $ {{ number_format($total_expenses, 0, '.', ',') }}</span>
                    <button class="btn-refresh">↻</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="standard-table">
                    <thead>
                        <tr>
                            <th>{{ trans('messages.concept') }}</th>
                            <th class="text-right">{{ trans('messages.total') }}</th>
                            <th class="text-right">{{ trans('messages.percentage') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $ex)
                        <tr>
                            <td class="font-bold">{{ $ex['concept'] }}</td>
                            <td class="text-right font-bold">$ {{ number_format($ex['total'], 0, '.', ',') }}</td>
                            <td class="text-right">
                                <div style="display:flex; align-items:center; justify-content:flex-end; gap:10px;">
                                    <div style="width:100px; background:#f1f5f9; height:8px; border-radius:4px; overflow:hidden;">
                                        <div style="width:{{ $ex['percentage'] }}%; background:var(--primary-blue); height:100%;"></div>
                                    </div>
                                    <span>{{ $ex['percentage'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    function updateTicket(val) {
        document.getElementById('mainTicketValue').innerText = val;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Bar Chart - Sales History
        const salesData = {!! json_encode($sales_history) !!};
        const ctxBar = document.getElementById('salesBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: salesData.map(d => d.month),
                datasets: [{
                    label: '{{ trans('messages.monthly_sales') }}',
                    data: salesData.map(d => d.total),
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: { display: false },
                        ticks: { callback: v => '$' + v.toLocaleString() }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Pie Chart - Services
        const serviceData = {!! json_encode(array_values($top_services)) !!};
        const ctxPie = document.getElementById('servicesPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: serviceData.map(s => s.name),
                datasets: [{
                    data: serviceData.map(s => s.this_month),
                    backgroundColor: ['#ef4444', '#d946ef', '#8b5cf6', '#a16207', '#3b82f6', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                }
            }
        });
    });
</script>

@endsection
