@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Liquidación de Estilistas</h1>
        <div class="breadcrumb">Informes / Nómina / Formato de Liquidación</div>
    </div>

    <div class="filter-bar">
        <form method="GET" action="{{ url('admin/informes/liquidacion-estilistas') }}" class="date-form">
            <div class="group">
                <label>Especialista</label>
                <select name="specialist_id" class="control">
                    <option value="">Seleccione uno...</option>
                    @foreach($specialists as $s)
                        <option value="{{ $s->id }}" {{ $specialist && $specialist->id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="group">
                <label>Desde</label>
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="control">
            </div>
            <div class="group">
                <label>Hasta</label>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="control">
            </div>
            <button type="submit" class="btn-filter">Liquidar Periodo</button>
        </form>
    </div>

    @if($specialist && $stats)
    <div class="liquidation-card">
        <div class="liq-header">
            <div class="liq-brand">
                <div class="liq-logo">LP</div>
                <div class="liq-title">
                    <h2>COMPROBANTE DE LIQUIDACIÓN</h2>
                    <span>AgendaPOS Pro - Gestión de Nómina</span>
                </div>
            </div>
            <div class="liq-meta">
                <p><strong>Fecha Emisión:</strong> {{ date('d/m/Y') }}</p>
                <p><strong>Periodo:</strong> {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</p>
            </div>
        </div>

        <div class="liq-body">
            <div class="liq-section">
                <h3>DATOS DEL BENEFICIARIO</h3>
                <div class="liq-row">
                    <span>Nombre:</span> <strong>{{ $specialist->name }}</strong>
                </div>
                <div class="liq-row">
                    <span>Identificación:</span> <strong>{{ $specialist->identification ?: 'N/A' }}</strong>
                </div>
            </div>

            <table class="liq-table">
                <thead>
                    <tr>
                        <th>CONCEPTO</th>
                        <th style="text-align:right">DEVENGADO</th>
                        <th style="text-align:right">DEDUCCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Comisiones por Servicios y Productos</td>
                        <td style="text-align:right">$ {{ number_format($stats->commission, 0) }}</td>
                        <td></td>
                    </tr>
                    <?php 
                        $advances = \App\Models\SpecialistAdvance::where('specialist_id', $specialist->id)
                            ->whereBetween('advance_date', [$dateFrom, $dateTo])
                            ->sum('amount');
                    ?>
                    @if($advances > 0)
                    <tr>
                        <td>Deducciones (Adelantos, Novedades)</td>
                        <td></td>
                        <td style="text-align:right">$ {{ number_format($advances, 0) }}</td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th>TOTAL NETO A PERCIBIR</th>
                        <th colspan="2" style="text-align:right; font-size:24px;">$ {{ number_format($stats->commission - $advances, 0) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="liq-footer">
            <div class="liq-sign">
                <div class="sign-line"></div>
                <span>Firma de la Empresa</span>
            </div>
            <div class="liq-sign">
                <div class="sign-line"></div>
                <span>Firma del Especialista</span>
            </div>
        </div>
    </div>
    <div style="margin-top:20px; text-align:right;">
        <button class="btn-filter" onclick="window.print()" style="background:#1f2937;">Imprimir Comprobante</button>
    </div>
    @elseif($specialist)
        <div style="background:white; padding:50px; border-radius:12px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.1); border:1px solid #e5e7eb;">
            <p style="color:#6b7280; font-size:16px;">No se encontraron movimientos comisionables para este especialista en el rango de fechas.</p>
        </div>
    @else
        <div style="background:white; padding:80px; border-radius:12px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,0.1); border:1px solid #e5e7eb;">
            <div style="font-size:48px; margin-bottom:20px;">📄</div>
            <h3 style="color:#1f2937; margin-bottom:10px;">Seleccione un especialista</h3>
            <p style="color:#6b7280;">Para generar el formato de liquidación, elija un colaborador y el rango de fechas.</p>
        </div>
    @endif
</div>

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

    .liquidation-card { background: white; padding: 50px; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.05); max-width: 900px; margin: 0 auto; color: #1f2937; }
    .liq-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; border-bottom: 2px solid #f3f4f6; padding-bottom: 30px; }
    .liq-brand { display: flex; gap: 20px; align-items: center; }
    .liq-logo { background: #1f2937; color: white; width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 900; }
    .liq-title h2 { margin: 0; font-size: 20px; font-weight: 800; letter-spacing: 1px; }
    .liq-title span { font-size: 12px; color: #6b7280; font-weight: 600; }
    .liq-meta p { margin: 2px 0; font-size: 13px; text-align: right; }

    .liq-section { margin-bottom: 30px; }
    .liq-section h3 { font-size: 12px; color: #9ca3af; letter-spacing: 2px; margin-bottom: 15px; border-bottom: 1px solid #f3f4f6; padding-bottom: 8px; }
    .liq-row { margin-bottom: 5px; font-size: 14px; }
    .liq-row span { color: #6b7280; min-width: 120px; display: inline-block; }

    .liq-table { width: 100%; border-collapse: collapse; margin-top: 30px; }
    .liq-table th { text-align: left; padding: 15px; background: #f9fafb; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; }
    .liq-table td { padding: 15px; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
    .liq-table tfoot th { padding: 25px 15px; background: #f9fafb; border-top: 2px solid #1f2937; color: #111827; }

    .liq-footer { display: flex; justify-content: space-around; margin-top: 80px; }
    .liq-sign { text-align: center; width: 250px; }
    .sign-line { border-top: 1px solid #d1d5db; margin-bottom: 10px; }
    .liq-sign span { font-size: 11px; font-weight: 700; color: #6b7280; text-transform: uppercase; }

    @media print {
        .report-header, .filter-bar, .breadcrumb, .btn-filter { display: none !important; }
        .liquidation-card { box-shadow: none; border: none; padding: 0; margin: 0; max-width: 100%; }
        body { background: white !important; }
    }
</style>
@endsection
