@extends('admin.dashboard_layout')

@section('content')
<div class="container-fluid" style="padding-top: 50px; font-family: 'DM Sans', sans-serif;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card" style="border: none; border-radius: 20px; box-shadow: 0 20px 50px -10px rgba(0,0,0,0.1); overflow: hidden;">
                <!-- Header del Plan -->
                <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 40px; text-align: center; color: white;">
                    <h5 style="text-transform: uppercase; letter-spacing: 2px; font-size: 0.9rem; opacity: 0.8; margin-bottom: 10px;">Estás adquiriendo</h5>
                    <h1 style="font-weight: 800; font-size: 2.5rem; margin: 0;">{{ $sub->plan_name }}</h1>
                    <div style="font-size: 3rem; font-weight: 700; margin-top: 10px;">
                        ${{ number_format($sub->price, 0) }} <span style="font-size: 1rem; opacity: 0.8;">/ mes</span>
                    </div>
                </div>

                <div class="card-body" style="padding: 40px;">
                    <p style="text-align: center; color: #64748b; margin-bottom: 30px;">
                        Completa tu pago seguro para activar las funciones de Inteligencia Artificial inmediatas.
                    </p>

                    <!-- Wompi Widget Container -->
                    <div style="text-align: center;">
                        <form action="https://checkout.wompi.co/p/" method="GET">
                            <!-- Campos ocultos requeridos por Wompi (Simulados según el controlador) -->
                            <input type="hidden" name="public-key" value="{{ $paymentData['public_key'] }}" />
                            <input type="hidden" name="currency" value="{{ $paymentData['currency'] }}" />
                            <input type="hidden" name="amount-in-cents" value="{{ $paymentData['amount_in_cents'] }}" />
                            <input type="hidden" name="reference" value="{{ $paymentData['reference'] }}" />
                            <input type="hidden" name="signature:integrity" value="{{ $paymentData['signature:integrity'] }}" />
                            <input type="hidden" name="redirect-url" value="{{ $paymentData['redirect_url'] }}" />
                            <input type="hidden" name="customer-data:email" value="{{ $paymentData['customer_email'] }}" />

                            <button type="submit" style="
                                background: #2563eb; 
                                color: white; 
                                border: none; 
                                padding: 18px 40px; 
                                font-size: 1.1rem; 
                                font-weight: 700; 
                                border-radius: 50px; 
                                width: 100%; 
                                cursor: pointer; 
                                box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                gap: 10px;
                                transition: transform 0.2s;
                            " onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                🔒 Pagar con Wompi
                            </button>
                        </form>
                    </div>

                    <div style="margin-top: 30px; text-align: center;">
                        <span style="font-size: 0.85rem; color: #94a3b8;">
                            <i class="fa fa-lock"></i> Pagos procesados de forma segura. No almacenamos tu información financiera.
                        </span>
                    </div>

                    <!-- Botón de Simulación para Desarrollo (Solo visible si es local o dev) -->
                    @if(config('app.env') == 'local' || true) 
                    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px dashed #e2e8f0; text-align: center;">
                        <p style="font-size: 0.8rem; color: #ef4444; font-weight: bold; margin-bottom: 10px;">ZONA DE DESARROLLO (Simulación)</p>
                        <a href="{{ url('admin/subscription/callback?id=SIM-' . time()) }}" class="btn btn-outline-secondary btn-sm">
                            ⚡ Simular Pago Exitoso (Bypass Wompi)
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
