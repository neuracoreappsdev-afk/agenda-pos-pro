<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Clínico - {{ $patient->name }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
        
        body { font-family: 'Inter', sans-serif; color: #1e293b; margin: 0; padding: 40px; background: #fff; line-height: 1.5; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #f1f5f9; padding-bottom: 30px; margin-bottom: 40px; }
        .business-info h1 { margin: 0; font-size: 24px; font-weight: 900; color: #0f172a; }
        .business-info p { margin: 5px 0 0; font-size: 12px; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .logo { width: 80px; height: 80px; border-radius: 20px; object-fit: cover; }
        
        .report-title { text-align: center; margin-bottom: 40px; }
        .report-title h2 { font-size: 20px; font-weight: 900; background: #f8fafc; display: inline-block; padding: 10px 30px; border-radius: 15px; text-transform: uppercase; letter-spacing: 2px; }
        
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; }
        .card { background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; }
        .card h3 { margin: 0 0 15px; font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; }
        .info-row span:first-child { font-weight: 700; color: #475569; }
        .info-row span:last-child { color: #0f172a; font-weight: 400; }

        .section { margin-bottom: 40px; }
        .section-title { font-size: 11px; font-weight: 900; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
        
        .content-box { background: #fff; border: 1px solid #f1f5f9; padding: 25px; border-radius: 20px; font-size: 14px; color: #334155; white-space: pre-wrap; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }

        .signature-area { margin-top: 60px; display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
        .signature-box { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px; }
        .signature-box p { margin: 5px 0 0; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .signature-img { height: 80px; margin-bottom: -10px; }

        .footer-note { margin-top: 100px; text-align: center; font-size: 10px; color: #94a3b8; font-style: italic; }

        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .card { border: 1px solid #e2e8f0; background: transparent; }
            .content-box { border: 1px solid #e2e8f0; box-shadow: none; }
        }

        .btn-print {
            position: fixed; bottom: 30px; right: 30px;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            color: white; border: none; padding: 15px 30px; border-radius: 50px;
            font-weight: 900; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            text-transform: uppercase; letter-spacing: 1px; font-size: 12px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn-print no-print">🖨️ Imprimir Reporte Oficial</button>

    <div class="header">
        <div class="business-info">
            <h1>{{ $business['name'] }}</h1>
            <p>NIT: {{ $business['nit'] }}</p>
            <p>{{ $business['address'] }}</p>
            <p>TEL: {{ $business['phone'] }}</p>
        </div>
        @if($business['logo'])
            <img src="{{ asset($business['logo']) }}" class="logo">
        @else
            <div class="logo" style="background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 40px;">🏢</div>
        @endif
    </div>

    <div class="report-title">
        <h2>Reporte de Evolución Clínica</h2>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Información del Paciente</h3>
            <div class="info-row"><span>Nombre:</span> <span>{{ $patient->name }}</span></div>
            <div class="info-row"><span>Identificación:</span> <span>{{ $patient->document_id }}</span></div>
            <div class="info-row"><span>Teléfono:</span> <span>{{ $patient->contact_number }}</span></div>
            <div class="info-row"><span>Email:</span> <span>{{ $patient->email }}</span></div>
        </div>
        <div class="card">
            <h3>Detalles de la Cita</h3>
            <div class="info-row"><span>Fecha:</span> <span>{{ date('d-m-Y', strtotime($record->created_at)) }}</span></div>
            <div class="info-row"><span>Hora:</span> <span>{{ date('H:i', strtotime($record->created_at)) }}</span></div>
            <div class="info-row"><span>Folio:</span> <span>#{{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}</span></div>
            <div class="info-row"><span>Estado:</span> <span style="color: #10b981; font-weight: 900;">COMPLETADO</span></div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Motivo de Consulta</div>
        <div class="content-box" style="font-weight: 700; color: #0f172a;">{{ $record->reason ?? 'No especificado' }}</div>
    </div>

    <div class="section">
        <div class="section-title">Notas de Evolución y Hallazgos</div>
        <div class="content-box">{{ $record->notes }}</div>
    </div>

    @if($record->formula)
    <div class="section">
        <div class="section-title">Prescripción Médica / Recetario</div>
        <div class="content-box" style="background: #f0fdf4; border-color: #dcfce7; color: #166534;">{{ $record->formula }}</div>
    </div>
    @endif

    <div class="signature-area">
        <div class="signature-box">
             <div style="height: 100px;"></div>
             <p>Firma Profesional Recibido</p>
        </div>
        <div class="signature-box">
             @if($consent)
                <img src="{{ $consent->signature_data }}" class="signature-img">
             @else
                <div style="height: 100px;"></div>
             @endif
             <p>Firma del Paciente: {{ $patient->name }}</p>
             <p style="font-size: 8px; opacity: 0.5;">ID: {{ $patient->document_id }} | IP: {{ $consent->ip_address ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="footer-note">
        Este documento es confidencial y para uso exclusivo del profesional de la salud y el paciente. 
        Generado automáticamente por AgendaPOS Cloud v2.0
    </div>

</body>
</html>
