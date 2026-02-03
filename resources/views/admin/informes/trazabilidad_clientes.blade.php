@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Trazabilidad de Clientes</h1>
        <div class="breadcrumb">Informes / Clientes / Trazabilidad</div>
    </div>

    <!-- Customer Selector -->
    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/trazabilidad-clientes') }}" style="width: 100%;">
            <div style="display:flex; gap:10px;">
               @if(isset($allCustomers) && count($allCustomers) > 0)
                <select name="customer_id" class="control select-search" onchange="this.form.submit()">
                    <option value="">Seleccione un cliente...</option>
                    @foreach($allCustomers as $c)
                        <option value="{{ $c->id }}">{{ $c->first_name }} {{ $c->last_name }}</option>
                    @endforeach
                </select>
               @endif
                <!-- If customer is selected, show name -->
               @if($customer)
                <div style="display:flex; align-items:center; gap:10px; flex:1;">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($customer->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:16px;">{{ $customer->first_name }} {{ $customer->last_name }}</div>
                        <div style="font-size:13px; color:#6b7280;">{{ $customer->email }} • {{ $customer->contact_number }}</div>
                    </div>
                </div>
                <a href="{{ url('admin/informes/trazabilidad-clientes') }}" style="margin-left:auto; color:#6b7280; text-decoration:underline; font-size:13px;">Cambiar Cliente</a>
               @endif
            </div>
        </form>
    </div>

    @if($customer)
    <div class="timeline-container">
        @forelse($history as $event)
        <div class="timeline-item">
            <div class="timeline-marker {{ $event['type'] == 'sale' ? 'marker-sale' : 'marker-appt' }}"></div>
            <div class="timeline-content">
                <div class="time">{{ date('d M Y - H:i', strtotime($event['date'])) }}</div>
                <div class="title">{{ $event['type'] == 'sale' ? 'Venta Realizada' : 'Cita Agendada' }}</div>
                <div class="details">{{ $event['details'] }}</div>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:40px; color:#9fa6b2;">
            No hay historial registrado para este cliente.
        </div>
        @endforelse
    </div>
    @else
    <div style="text-align:center; padding:50px; background:white; border-radius:12px; border:1px solid #e5e7eb; color:#6b7280;">
        <p>Seleccione un cliente para ver su historial completo de interacciones.</p>
    </div>
    @endif
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    .filter-bar { background: white; padding: 20px; border-radius: 12px; margin: 25px 0; border: 1px solid #e5e7eb; }
    .control { border: 1px solid #d1d5db; padding: 10px 15px; border-radius: 6px; width: 100%; font-size:14px; }
    
    .avatar-circle { width:40px; height:40px; background:#e0e7ff; color:#3730a3; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; }

    /* Timeline */
    .timeline-container { position: relative; padding-left: 30px; margin-top: 30px; }
    .timeline-item { position: relative; margin-bottom: 30px; }
    .timeline-marker { position: absolute; left: -36px; top: 0; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 0 2px #e5e7eb; background: #9ca3af; }
    .timeline-container::before { content: ''; position: absolute; left: -31px; top: 5px; bottom: 0; width: 2px; background: #e5e7eb; }
    
    .marker-sale { background: #10b981; box-shadow: 0 0 0 2px #10b981; }
    .marker-appt { background: #3b82f6; box-shadow: 0 0 0 2px #3b82f6; }
    
    .timeline-content { background: white; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .timeline-content .time { font-size: 12px; color: #9ca3af; font-family: monospace; }
    .timeline-content .title { font-weight: 700; color: #1f2937; margin: 4px 0; }
    .timeline-content .details { font-size: 14px; color: #4b5563; }
</style>
@endsection
