@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Conversión de Medidas y Rendimientos</h1>
        <div class="breadcrumb">Informes / Inventario / Análisis de Insumos</div>
    </div>

    <!-- Alert about usage -->
    <div style="background:#fff7ed; border:1px solid #ffedd5; padding:15px; border-radius:12px; margin:25px 0; display:flex; gap:15px; align-items:center;">
        <div style="font-size:24px;">💡</div>
        <div style="font-size:13px; color:#9a3412;">
            <strong>Nota:</strong> Los rendimientos calculados son estimaciones basadas en el volumen estándar del producto. 
            Ayuda a determinar el <strong>costo real de insumos</strong> por cada uso en servicios técnicos.
        </div>
    </div>

    <div class="card-table">
        <div class="card-header">
            <h3>Calculadora de Rendimientos por Insumo</h3>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Contenido Total</th>
                    <th style="text-align:center">Dosis x Uso</th>
                    <th style="text-align:center">Rendimiento (Usos)</th>
                    <th style="text-align:right">Costo Unitario</th>
                    <th style="text-align:right">Costo x Uso</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $p)
                <tr>
                    <td>
                        <div style="font-weight:700; color:#111827;">{{ $p->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $p->category }}</div>
                    </td>
                    <td style="text-align:center;">{{ $p->volume_total }} {{ $p->unit_measure }}</td>
                    <td style="text-align:center; color:#1a73e8; font-weight:700;">{{ $p->service_usage }} {{ $p->unit_measure }}</td>
                    <td style="text-align:center;"><span class="yield-badge">{{ $p->yield }} servicios</span></td>
                    <td style="text-align:right;">$ {{ number_format($p->cost, 0) }}</td>
                    <td style="text-align:right; font-weight:900; color:#111827;">$ {{ number_format($p->cost_per_service, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .card-header { padding: 20px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .card-header h3 { margin: 0; font-size: 15px; font-weight: 700; color: #374151; }
    
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table th { background: #f9fafb; padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; }
    .data-table td { padding: 15px 20px; border-bottom: 1px solid #f3f4f6; font-size: 14px; color: #4b5563; }
    
    .yield-badge { background: #eff6ff; color: #1e40af; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 800; border: 1px solid #dbeafe; }
</style>
@endsection
