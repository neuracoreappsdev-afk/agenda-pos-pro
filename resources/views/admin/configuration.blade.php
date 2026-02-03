@extends('admin/dashboard_layout')

@section('content')

<style>
    .config-header {
        margin-bottom: 30px;
    }
    .breadcrumb {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 8px;
    }
    .breadcrumb a {
        color: #1a73e8;
        text-decoration: none;
    }
    
    .search-box {
        text-align: center;
        margin-bottom: 40px;
    }
    .search-box-label {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .search-input {
        width: 100%;
        max-width: 400px;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
    }
    .search-input:focus {
        outline: none;
        border-color: #1a73e8;
        box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }
    
    .config-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 992px) {
        .config-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .config-column {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
    
    .config-category {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }
    
    .category-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .category-icon {
        width: 8px;
        height: 8px;
        background: #1a73e8;
        border-radius: 2px;
    }
    
    .category-title {
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .config-links {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .config-link {
        color: #1a73e8;
        text-decoration: none;
        font-size: 13px;
        padding: 6px 0;
        transition: all 0.2s;
    }
    
    .config-link:hover {
        color: #1557b0;
        text-decoration: underline;
    }
</style>

<div class="config-header">
    <div class="breadcrumb">
        <a href="{{ url('admin/dashboard') }}">Configuraciones</a> / <span>General</span>
    </div>
</div>

<!-- Buscador -->
<div class="search-box">
    <div class="search-box-label">
        🔍 Buscador
    </div>
    <input type="text" class="search-input" id="configSearch" placeholder="Buscar configuración..." onkeyup="filterConfig()">
</div>

<div class="config-grid">
    
    <!-- COLUMNA 1 -->
    <div class="config-column">
        
        <!-- Configuración de Negocio -->
        <div class="config-category" data-category="negocio">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración de Negocio</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('admin.config.business') }}" class="config-link">Detalles del Negocio</a>
                <a href="{{ route('admin.config.schedule') }}" class="config-link">Horarios y Festivos</a>
                <a href="{{ url('admin/configuration/sedes') }}" class="config-link">Sedes</a>
                <a href="{{ route('config.usuarios') }}" class="config-link">Usuarios</a>
                <a href="{{ route('config.roles') }}" class="config-link">Roles</a>
                <a href="{{ route('config.permisos') }}" class="config-link">Permisos</a>
                <a href="{{ route('config.conceptos') }}" class="config-link">Conceptos (Ingresos y Gastos)</a>
                <a href="{{ route('config.empresas') }}" class="config-link">Empresas</a>
                <a href="{{ route('config.reset_data') }}" class="config-link">Reiniciar Datos</a>
            </div>
        </div>
        
        <!-- Configuración Participaciones y Especialistas -->
        <div class="config-category" data-category="especialistas">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Participaciones y Especialistas</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.comisiones') }}" class="config-link">Configuración de Comisiones</a>
                <a href="{{ route('config.comisiones_globales') }}" class="config-link">Comisiones Globales</a>
                <a href="{{ route('config.tipos_colaboradores') }}" class="config-link">Tipos de Colaboradores</a>
                <a href="{{ route('config.especialidades') }}" class="config-link">Especialidades</a>
                <a href="{{ route('config.especialista_config') }}" class="config-link">Configuración de Especialista</a>
            </div>
        </div>
        
        <!-- Configuración Cliente -->
        <div class="config-category" data-category="cliente">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Cliente</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.fuentes_referencia') }}" class="config-link">Fuentes de Referencia</a>
                <a href="{{ route('config.tipos_clientes') }}" class="config-link">Tipos de Clientes</a>
                <a href="{{ route('config.zonas_clientes') }}" class="config-link">Zonas de Clientes</a>
                <a href="{{ route('config.listados') }}" class="config-link">Configuración de Listados</a>
                <a href="{{ route('config.datos_obligatorios') }}" class="config-link">Datos obligatorios</a>
                <a href="{{ route('config.link_registro') }}" class="config-link">Link de registro de Cliente</a>
                <a href="{{ route('config.razones_cancelacion') }}" class="config-link">Razones de cancelación</a>
                <a href="{{ route('config.descuentos') }}" class="config-link">Descuentos</a>
                <a href="{{ route('config.plantillas_ficha') }}" class="config-link">Plantillas de Ficha Técnica</a>
            </div>
        </div>
        
        <!-- Configuración Chat / Whatsapp -->
        <div class="config-category" data-category="whatsapp">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Chat / Whatsapp</h3>
            </div>
            <div class="config-links">
                <a href="{{ url('admin/configuration/mensajes-whatsapp') }}" class="config-link">Mensajes de Whatsapp</a>
                <a href="{{ url('admin/configuration/plantillas-whatsapp') }}" class="config-link">Plantillas de Whatsapp</a>
                <a href="{{ url('admin/configuration/respuestas-rapidas-whatsapp') }}" class="config-link">Respuestas Rápidas de Whatsapp</a>
                <a href="{{ url('admin/configuration/integracion-whatsapp-api') }}" class="config-link">Integración de Whatsapp API</a>
            </div>
        </div>
        
        <!-- Configuración Productos -->
        <div class="config-category" data-category="productos">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Productos</h3>
            </div>
            <div class="config-links">
                <a href="{{ url('admin/configuration/tipos-productos') }}" class="config-link">Tipos de Productos</a>
                <a href="{{ url('admin/configuration/bloqueo-precios-productos') }}" class="config-link">Bloqueo de precios de productos</a>
                <a href="{{ url('admin/configuration/config-general-productos') }}" class="config-link">Configuración General de Productos</a>
            </div>
        </div>
        
        <!-- Configuración Servicios -->
        <div class="config-category" data-category="servicios">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Servicios</h3>
            </div>
            <div class="config-links">
                <a href="{{ url('admin/configuration/lineas-servicios') }}" class="config-link">Líneas de Servicios</a>
                <a href="{{ url('admin/configuration/bloqueo-precios-servicios') }}" class="config-link">Bloqueo de precios de servicios</a>
            </div>
        </div>
        
        <!-- Configuración Integraciones -->
        <div class="config-category" data-category="integraciones">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Integraciones</h3>
            </div>
            <div class="config-links">
                <a href="{{ url('admin/configuration/contabilidad') }}" class="config-link">Contabilidad</a>
                <a href="{{ url('admin/configuration/fidelizacion-integracion') }}" class="config-link">Fidelización</a>
                <a href="{{ url('admin/configuration/webhooks') }}" class="config-link">Webhooks (n8n)</a>
            </div>
        </div>
        
    </div>
    
    <!-- COLUMNA 2 -->
    <div class="config-column">
        
        <!-- Configuración Caja y Facturación -->
        <div class="config-category" data-category="caja">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Caja y Facturación</h3>
            </div>
            <div class="config-links">
                <a href="{{ url('admin/configuration/pos-electronico') }}" class="config-link">Configuración de POS y POS Electrónico</a>
                <a href="{{ url('admin/configuration/facturas-recibos') }}" class="config-link">Facturas y Recibos</a>
                <a href="{{ url('admin/configuration/formas-pago') }}" class="config-link">Formas de Pago</a>
                <a href="{{ url('admin/configuration/plantillas-facturacion') }}" class="config-link">Plantillas de Facturación</a>
            </div>
        </div>
        
        <!-- Configuración Agenda y Reservas en Línea -->
        <div class="config-category" data-category="reservas">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Agenda y Reservas en Línea</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.agenda_calendario') }}" class="config-link">Configuración de Agenda / Calendario</a>
                <a href="{{ route('config.reservas_linea') }}" class="config-link">Configuración de Reservas en Línea</a>
                <a href="{{ route('config.links_reservas') }}" class="config-link">Links de Reservas en Línea</a>
                <a href="{{ route('config.formulario_registro') }}" class="config-link">Configuración Formulario Registro de Clientes</a>
                <a href="{{ route('config.notificaciones') }}" class="config-link">Configuración de Notificaciones</a>
                <a href="{{ route('config.listado_notificaciones') }}" class="config-link">Listado de Notificaciones</a>
                <a href="{{ route('config.estados_reservas') }}" class="config-link">Estados de Reservas</a>
            </div>
        </div>
        
        <!-- Configuración Marketing -->
        <div class="config-category" data-category="marketing">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Marketing</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.fidelizacion') }}" class="config-link">Fidelización</a>
                <a href="{{ route('config.sms') }}" class="config-link">Mensajes de Texto</a>
                <a href="{{ route('config.email') }}" class="config-link">Correos electrónicos</a>
            </div>
        </div>
        
        <!-- Configuración Impuestos -->
        <div class="config-category" data-category="impuestos">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Impuestos</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.impuestos') }}" class="config-link">Impuestos</a>
                <a href="{{ route('config.retenciones') }}" class="config-link">Retenciones</a>
            </div>
        </div>
        
        <!-- Configuración Bonos -->
        <div class="config-category" data-category="bonos">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Configuración Bonos</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.tipos_bonos') }}" class="config-link">Tipos de Bonos</a>
            </div>
        </div>
        
        <!-- Edición Masiva -->
        <div class="config-category" data-category="edicion-masiva">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Edición Masiva</h3>
            </div>
            <div class="config-links">
                <a href="{{ route('config.bulk_commissions') }}" class="config-link">Actualizar Comisiones Facturadas</a>
                <a href="{{ route('config.bulk_prices') }}" class="config-link">Actualización masiva de Precios de venta</a>
                <a href="{{ route('config.bulk_costs') }}" class="config-link">Actualización masiva de Costos de productos</a>
                <a href="{{ route('config.bulk_consumables') }}" class="config-link">Productos de Consumo / Descuentos Administrativos</a>
                <a href="{{ route('config.import_products') }}" class="config-link">Importar Productos</a>
                <a href="{{ route('config.import_services') }}" class="config-link">Importar Servicios</a>
                <a href="{{ route('config.import_news') }}" class="config-link">Importar Novedades Participaciones</a>
                <a href="{{ route('config.import_specialists') }}" class="config-link">Importar Especialistas</a>
                <a href="{{ route('config.import_clients') }}" class="config-link">Importar Clientes</a>
            </div>
        </div>
        
    </div>
    
</div>

<script>
function filterConfig() {
    const searchValue = document.getElementById('configSearch').value.toLowerCase();
    const categories = document.querySelectorAll('.config-category');
    
    categories.forEach(category => {
        const links = category.querySelectorAll('.config-link');
        let hasMatch = false;
        
        links.forEach(link => {
            const text = link.textContent.toLowerCase();
            if (text.includes(searchValue)) {
                link.style.display = '';
                hasMatch = true;
            } else {
                link.style.display = 'none';
            }
        });
        
        category.style.display = hasMatch ? '' : 'none';
    });
}
</script>

@endsection