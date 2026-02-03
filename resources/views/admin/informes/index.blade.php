@extends('admin/dashboard_layout')

@section('content')

<style>
    .reports-header {
        margin-bottom: 30px;
    }
    .reports-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }
    .breadcrumb {
        color: #6b7280;
        font-size: 14px;
        margin-top: 8px;
    }
    .breadcrumb a {
        color: #1a73e8;
        text-decoration: none;
    }
    
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: start;
    }
    
    @media (max-width: 1200px) {
        .reports-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .reports-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .report-category {
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
    
    .report-links {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .report-link {
        color: #1a73e8;
        text-decoration: none;
        font-size: 13px;
        padding: 6px 0;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .report-link:hover {
        color: #1557b0;
        text-decoration: underline;
    }
    
    .badge-new {
        background: #1a73e8;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .reports-column {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }
</style>

<div class="reports-header">
    <div class="breadcrumb">
        <a href="{{ url('admin/dashboard') }}">{{ trans('messages.reports') }}</a> / <span>{{ trans('messages.list') }}</span>
    </div>
</div>

<div class="reports-grid">
    
    <!-- COLUMNA 1 -->
    <div class="reports-column">
        
        <!-- Información general del negocio -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Información General</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/estado-resultados') }}" class="report-link">Estado de Resultados</a>
                <a href="{{ url('admin/informes/gastos') }}" class="report-link">Reporte de Gastos</a>
                <a href="{{ url('admin/informes/rentabilidad-servicios') }}" class="report-link">Rentabilidad por Servicio</a>
                <a href="{{ url('admin/informes/rentabilidad-productos') }}" class="report-link">Rentabilidad por Producto</a>
                <a href="{{ url('admin/informes/presupuesto-vs-real') }}" class="report-link">Presupuesto vs Real</a>
                <a href="{{ url('admin/informes/codigos-verificacion') }}" class="report-link">Códigos de Verificación</a>
            </div>
        </div>
        
        <!-- Caja -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Caja y Bancos</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/movimientos-caja') }}" class="report-link">Movimientos de Caja</a>
                <a href="{{ url('admin/informes/movimientos-cuentas-efectivo') }}" class="report-link">Cuentas de Efectivo</a>
                <a href="{{ url('admin/informes/codigos-verificacion-pago') }}" class="report-link">Verificación de Pagos</a>
                <a href="{{ url('admin/informes/porcentaje-franquicias') }}" class="report-link">Comisiones Bancarias (Franquicias)</a>
            </div>
        </div>
        
        <!-- Clientes -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Clientes</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/fuentes-referencia') }}" class="report-link">Fuentes de Referencia</a>
                <a href="{{ url('admin/informes/frecuencia-clientes') }}" class="report-link">Frecuencia de Visita</a>
                <a href="{{ url('admin/informes/ventas-por-asesor') }}" class="report-link">Ventas por Asesor</a>
                <a href="{{ url('admin/informes/fichas-tecnicas') }}" class="report-link">Fichas Técnicas</a>
                <a href="{{ url('admin/informes/retorno-nuevos-clientes') }}" class="report-link">Retorno de Nuevos Clientes</a>
                <a href="{{ url('admin/informes/puntos') }}" class="report-link">Puntos de Fidelidad</a>
                <a href="{{ url('admin/informes/trazabilidad-clientes') }}" class="report-link">Trazabilidad de Clientes</a>
            </div>
        </div>
        
        <!-- Contabilidad -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Contabilidad</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/facturacion-electronica') }}" class="report-link">
                    Facturación Electrónica e Impuestos
                    <span class="badge-new">Nuevo</span>
                </a>
                <a href="{{ url('admin/informes/exportar-facturas') }}" class="report-link">Exportar Facturas</a>
                <a href="{{ url('admin/informes/exportar-recibos') }}" class="report-link">Exportar Recibos</a>
                <a href="{{ url('admin/informes/exportar-compras') }}" class="report-link">Exportar Compras</a>
                <a href="{{ url('admin/informes/reporte-auto-renta') }}" class="report-link">Reporte Auto Renta</a>
                <a href="{{ url('admin/informes/exportar-creditos') }}" class="report-link">Exportar Créditos</a>
                <a href="{{ url('admin/informes/facturacion-participaciones') }}" class="report-link">Facturación de Comisiones</a>
            </div>
        </div>
        
        <!-- Chat / Whatsapp -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Chat y WhatsApp</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/analitica-chat') }}" class="report-link">Analítica de Chat</a>
                <a href="{{ url('admin/informes/mensajes-ads') }}" class="report-link">Mensajes de Anuncios</a>
            </div>
        </div>
        
    </div>
    
    <!-- COLUMNA 2 -->
    <div class="reports-column">
        
        <!-- Especialistas -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Especialistas y Nómina</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/facturacion-participaciones') }}" class="report-link">Facturación de Comisiones</a>
                <a href="{{ url('admin/informes/comisiones') }}" class="report-link">Consultar Comisiones (Histórico)</a>
                <a href="{{ url('admin/informes/participacion-detallada') }}" class="report-link">Comisión Detallada</a>
                <a href="{{ url('admin/informes/ventas-por-especialista') }}" class="report-link">Ventas por Especialista</a>
                <a href="{{ url('admin/informes/ventas-especialistas-clientes') }}" class="report-link">Ventas Especialista/Cliente</a>
                <a href="{{ url('admin/informes/planilla-pago') }}" class="report-link">Planilla de Pago</a>
                <a href="{{ url('admin/informes/novedades') }}" class="report-link">Novedades y Deducciones</a>
                <a href="{{ url('admin/informes/participaciones-pago-dividido') }}" class="report-link">Comisiones de Pago Dividido</a>
                <a href="{{ url('admin/informes/liquidacion-estilistas') }}" class="report-link">Formato Liquidación Estilistas</a>
                <a href="{{ url('admin/informes/reporte-novedades-especialistas') }}" class="report-link">Reporte de Novedades</a>
                <a href="{{ url('admin/informes/bloqueo-especialistas') }}" class="report-link">Bloqueos de Agenda</a>
            </div>
        </div>
        
        <!-- Servicios -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Servicios</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/ventas-por-servicios') }}" class="report-link">Ventas por Servicio</a>
                <a href="{{ url('admin/informes/listado-precios-servicios') }}" class="report-link">Listado de Precios</a>
            </div>
        </div>
        
        <!-- Bonos -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Bonos y Giftcards</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/listado-bonos') }}" class="report-link">Listado de Bonos</a>
            </div>
        </div>
        
        <!-- Créditos -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Cuentas por Cobrar (Créditos)</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/listado-creditos-pagos') }}" class="report-link">Resumen de Cartera</a>
                <a href="{{ url('admin/informes/exportar-creditos') }}" class="report-link">Cuentas por Cobrar Detalladas</a>
            </div>
        </div>
        
        <!-- Campañas -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Campañas y Encuestas</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/respuestas-encuestas') }}" class="report-link">Respuestas de Encuestas</a>
            </div>
        </div>
        
    </div>
    
    <!-- COLUMNA 3 -->
    <div class="reports-column">
        
        <!-- Ventas -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Ventas</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/ventas-generales') }}" class="report-link">Ventas Generales</a>
                <a href="{{ url('admin/informes/ventas-facturacion-impuestos') }}" class="report-link">
                    Ventas e Impuestos
                    <span class="badge-new">Nuevo</span>
                </a>
                <a href="{{ url('admin/informes/ventas-por-sede') }}" class="report-link">Ventas por Sede</a>
                <a href="{{ url('admin/informes/ventas-por-cliente') }}" class="report-link">Ventas por Cliente</a>
                <a href="{{ url('admin/informes/ventas-productos-servicios') }}" class="report-link">Ventas Prod. vs Serv.</a>
                <a href="{{ url('admin/informes/ventas-por-dia') }}" class="report-link">Ventas por Día</a>
                <a href="{{ url('admin/informes/ventas-por-mes') }}" class="report-link">Ventas por Mes</a>
                <a href="{{ url('admin/informes/ventas-por-vendedor') }}" class="report-link">Ventas por Vendedor</a>
                <a href="{{ url('admin/informes/ventas-medios-pago') }}" class="report-link">Ventas por Medio de Pago</a>
                <a href="{{ url('admin/informes/ventas-tipo-facturacion') }}" class="report-link">Ventas por Tipo de Facturación</a>
                <a href="{{ url('admin/informes/ventas-mandato-detallado') }}" class="report-link">Ventas Mandato Detallado</a>
                <a href="{{ url('admin/informes/ventas-comparativas-sedes') }}" class="report-link">Comparativa de Sedes</a>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Productos e Inventario</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/ventas-generales-productos') }}" class="report-link">Ventas Generales Productos</a>
                <a href="{{ url('admin/informes/impuesto-ventas-productos') }}" class="report-link">Impuesto Ventas Productos</a>
                <a href="{{ url('admin/informes/impuesto-compras-productos') }}" class="report-link">Impuesto Compras Productos</a>
                <a href="{{ url('admin/informes/ventas-por-productos') }}" class="report-link">Ventas por Producto</a>
                <a href="{{ url('admin/informes/ventas-productos-consumo') }}" class="report-link">Ventas Productos + Consumo</a>
                <a href="{{ url('admin/informes/ventas-productos-consumo-solo') }}" class="report-link">Consumo Interno</a>
                <a href="{{ url('admin/informes/saldos-inventario-hoy') }}" class="report-link">Saldos de Inventario (Hoy)</a>
                <a href="{{ url('admin/informes/saldos-inventario-fechas') }}" class="report-link">Saldos por Fecha</a>
                <a href="{{ url('admin/informes/inventario-valorizado') }}" class="report-link">Inventario Valorizado</a>
                <a href="{{ url('admin/informes/productos-existencias-bajas') }}" class="report-link">Productos con Stock Bajo</a>
                <a href="{{ url('admin/informes/movimientos-inventarios') }}" class="report-link">Movimientos de Inventario</a>
                <a href="{{ url('admin/informes/rotacion-productos') }}" class="report-link">Rotación de Productos</a>
                <a href="{{ url('admin/informes/devoluciones') }}" class="report-link">Devoluciones</a>
                <a href="{{ url('admin/informes/listado-precios-productos') }}" class="report-link">Listado de Precios y Costos</a>
                <a href="{{ url('admin/informes/conversion-medidas') }}" class="report-link">Conversión de Medidas</a>
                <a href="{{ url('admin/informes/historial-pedidos') }}" class="report-link">Historial de Pedidos</a>
                <a href="{{ url('admin/informes/listado-pedidos') }}" class="report-link">Listado de Pedidos</a>
            </div>
        </div>
        
        <!-- Planes -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Planes y Membresías</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/listado-planes') }}" class="report-link">Listado de Planes</a>
            </div>
        </div>
        
        <!-- Reservas -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Agenda y Reservas</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/agenda-reservas') }}" class="report-link">Reporte de Agenda</a>
            </div>
        </div>
        
        <!-- Logs de Cambios -->
        <div class="report-category">
            <div class="category-header">
                <span class="category-icon"></span>
                <h3 class="category-title">Auditoría y Logs</h3>
            </div>
            <div class="report-links">
                <a href="{{ url('admin/informes/log-cambios-usuarios') }}" class="report-link">Log de Usuarios</a>
                <a href="{{ url('admin/informes/log-cambios-facturas') }}" class="report-link">Log de Facturas</a>
            </div>
        </div>
        
    </div>
    
</div>

@endsection
