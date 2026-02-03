@extends('admin/dashboard_layout')

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1>Mensajes de Anuncios (Ads)</h1>
        <div class="breadcrumb">Informes / Marketing / Tráfico de Campañas</div>
    </div>

    <!-- Stats -->
    <div class="grid-stats" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-top:20px;">
        <div class="stat-card">
            <h3>Campañas Activas</h3>
            <div class="stat-value" style="color:#1a73e8;">{{ $campaigns->where('status', 'sent')->count() }}</div>
            <div class="stat-desc">Impacto total canales</div>
        </div>
        <div class="stat-card">
            <h3>Mensajes Enviados</h3>
            <div class="stat-value" style="color:#10b981;">{{ $messages->count() }}</div>
            <div class="stat-desc">Clientes alcanzados</div>
        </div>
        <div class="stat-card">
            <h3>Tasa de Entrega</h3>
            <div class="stat-value" style="color:#f59e0b;">{{ $messages->count() > 0 ? round(($messages->where('status', 'sent')->count() / $messages->count()) * 100, 1) : 0 }}%</div>
            <div class="stat-desc">Efectividad de envío</div>
        </div>
    </div>

    <div class="card-table" style="margin-top:30px;">
        @if($campaigns->count() > 0)
            <div class="card-header" style="padding:15px 20px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
                <h3 style="margin:0; font-size:14px; font-weight:700;">Últimas Campañas y Tráfico</h3>
            </div>
            <table class="data-table" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb; text-align:left; font-size:11px; color:#6b7280; text-transform:uppercase;">
                        <th style="padding:12px 20px;">Campaña</th>
                        <th style="padding:12px 20px;">Tipo</th>
                        <th style="padding:12px 20px;">Estado</th>
                        <th style="padding:12px 20px; text-align:right;">Alcance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns->take(10) as $c)
                    <tr style="border-bottom:1px solid #f3f4f6; font-size:14px; color:#4b5563;">
                        <td style="padding:15px 20px; font-weight:600;">{{ $c->name }}</td>
                        <td style="padding:15px 20px;">
                             <span style="font-size:11px; padding:3px 8px; border-radius:12px; background:#f3f4f6; font-weight:700;">{{ strtoupper($c->type) }}</span>
                        </td>
                        <td style="padding:15px 20px;">{{ ucfirst($c->status) }}</td>
                        <td style="padding:15px 20px; text-align:right;">{{ $c->recipients_count }} per.</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="placeholder-content" style="padding:100px 40px; text-align:center;">
                <div style="font-size:64px; margin-bottom:20px;">📣</div>
                <h2 style="font-size:24px; font-weight:800; color:#1f2937;">Seguimiento de Anuncios y Campañas</h2>
                <p style="font-size:16px; color:#6b7280; max-width:550px; margin:0 auto 30px;">
                    Mide el impacto de tus anuncios en Facebook, Google y WhatsApp. Visualiza cuántos clientes llegaron a través de cada canal y su tasa de conversión a citas reales.
                </p>
                <div style="display:flex; justify-content:center; gap:20px; font-weight:700;">
                    <span style="color:#1a73e8;">FACEBOOK ADS</span> • 
                    <span style="color:#ef4444;">GOOGLE ADS</span> • 
                    <span style="color:#10b981;">WHATSAPP MARKETING</span>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .report-container { padding: 30px; }
    .report-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0; }
    .breadcrumb { color: #6b7280; margin-top: 5px; font-size: 14px; }
    
    .card-table { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .btn-filter { background: #1a73e8; color: white; border: none; padding: 12px 30px; border-radius: 8px; cursor: pointer; font-weight: 700; transition: all 0.2s; }
</style>
@endsection
