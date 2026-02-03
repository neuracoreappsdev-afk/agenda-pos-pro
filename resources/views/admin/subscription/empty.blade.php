@extends('admin.dashboard_layout')

@section('content')
<div class="container text-center" style="padding-top: 100px;">
    <div style="font-size: 5rem; margin-bottom: 20px;">🤔</div>
    <h2 style="font-weight: 800; color: #1e293b;">No tienes una suscripción activa</h2>
    <p style="color: #64748b; font-size: 1.1rem; max-width: 500px; margin: 0 auto 30px;">
        Parece que tu cuenta no tiene un plan asignado. Esto no debería suceder. Por favor contacta a soporte o selecciona un plan para continuar.
    </p>
    
    <a href="https://wa.me/573000000000" class="btn btn-primary btn-lg" style="border-radius: 50px; padding: 15px 30px;">
        Contactar Soporte
    </a>
</div>
@endsection
