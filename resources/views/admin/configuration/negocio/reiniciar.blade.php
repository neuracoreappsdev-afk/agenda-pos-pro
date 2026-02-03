@extends('admin.configuration._layout')

@section('config_title', 'Reiniciar Datos')

@section('config_content')

<style>
    .danger-zone {
        max-width: 900px;
        margin: 0 auto;
    }

    .danger-header {
        border-bottom: 2px solid #fee2e2;
        padding-bottom: 24px;
        margin-bottom: 40px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .danger-icon {
        width: 64px;
        height: 64px;
        background: #fee2e2;
        color: #ef4444;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
    }

    .reset-option-card {
        background: white;
        border: 2px solid #f3f4f6;
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s;
    }

    .reset-option-card:hover {
        border-color: #fca5a5;
        box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.05);
    }

    .reset-info h4 {
        margin: 0 0 8px 0;
        font-size: 20px;
        font-weight: 800;
        color: #111827;
    }

    .reset-info p {
        margin: 0;
        color: #6b7280;
        font-size: 15px;
        max-width: 500px;
        line-height: 1.5;
    }

    .btn-reset-danger {
        background: #ffffff;
        color: #ef4444;
        border: 2px solid #fee2e2;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-reset-danger:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }

    .warning-banner {
        background: #fff7ed;
        border: 1px solid #ffedd5;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 40px;
        display: flex;
        gap: 16px;
    }

    .warning-banner svg {
        color: #f97316;
        flex-shrink: 0;
    }

    .warning-banner p {
        margin: 0;
        color: #9a3412;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<div class="danger-zone">
    <div class="danger-header">
        <div class="danger-icon">⚠️</div>
        <div>
            <h2 style="font-size: 28px; font-weight: 900; color: #111827; margin: 0;">Zona de Peligro</h2>
            <p style="color: #6b7280; font-size: 15px; margin: 0;">Acciones destructivas e irreversibles sobre la información del negocio.</p>
        </div>
    </div>

    <div class="warning-banner">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <p>Al ejecutar cualquiera de estas acciones, la información se borrará permanentemente de la base de datos central y de todas las sedes conectadas. No hay forma de recuperar estos datos a menos que tengas un respaldo externo.</p>
    </div>

    <!-- PRODUCTOS -->
    <div class="reset-option-card">
        <div class="reset-info">
            <h4>Reiniciar Catálogo de Productos</h4>
            <p>Borra todos los productos, existencias actuales, códigos de barras y categorías de inventario.</p>
        </div>
        <form action="{{ url('admin/configuration/reiniciar/productos') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Se borrarán TODOS los productos para siempre.')">
            {{ csrf_field() }}
            <button type="submit" class="btn-reset-danger">Borrar Productos</button>
        </form>
    </div>

    <!-- SERVICIOS -->
    <div class="reset-option-card">
        <div class="reset-info">
            <h4>Reiniciar Portafolio de Servicios</h4>
            <p>Elimina todos los servicios, paquetes, precios y duraciones configuradas para el agendamiento.</p>
        </div>
        <form action="{{ url('admin/configuration/reiniciar/servicios') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Se borrarán TODOS los servicios para siempre.')">
            {{ csrf_field() }}
            <button type="submit" class="btn-reset-danger">Borrar Servicios</button>
        </form>
    </div>

    <!-- ESPECIALISTAS -->
    <div class="reset-option-card">
        <div class="reset-info">
            <h4>Reiniciar Equipo de Trabajo</h4>
            <p>Borra la lista de especialistas, sus comisiones personalizadas, fotos de perfil y accesos móviles.</p>
        </div>
        <form action="{{ url('admin/configuration/reiniciar/especialistas') }}" method="POST" onsubmit="return confirm('¿ESTÁS SEGURO? Se borrarán TODOS los especialistas para siempre.')">
            {{ csrf_field() }}
            <button type="submit" class="btn-reset-danger">Borrar Especialistas</button>
        </form>
    </div>

    <p style="text-align: center; color: #9ca3af; font-size: 13px; margin-top: 40px;">
        ID de Operación Segura habilitado. Todas las acciones quedan registradas en el Log de Auditoría.
    </p>
</div>

@endsection