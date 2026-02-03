<?php
/**
 * Script para generar automáticamente todas las vistas de configuración
 * Ejecutar desde la raíz del proyecto: php generate_config_views.php
 */

$base_path = __DIR__ . '/resources/views/admin/configuration/';

// Definir todas las vistas a crear con sus configuraciones
$views = [
    // NEGOCIO
    'negocio/sedes.blade.php' => [
        'title' => 'Gestión de Sedes',
        'description' => 'Administre las diferentes sedes o sucursales de su negocio.',
    ],
    'negocio/usuarios.blade.php' => [
        'title' => 'Gestión de Usuarios',
        'description' => 'Administre los usuarios con acceso al sistema.',
    ],
    'negocio/roles.blade.php' => [
        'title' => 'Roles y Permisos',
        'description' => 'Configure los roles y permisos del sistema.',
    ],
    'negocio/permisos.blade.php' => [
        'title' => 'Permisos Detallados',
        'description' => 'Configure permisos específicos para cada funcionalidad.',
    ],
    'negocio/conceptos.blade.php' => [
        'title' => 'Conceptos de Ingresos y Gastos',
        'description' => 'Defina los conceptos contables para organizar sus operaciones.',
    ],
    'negocio/empresas.blade.php' => [
        'title' => 'Empresas Relacionadas',
        'description' => 'Gestione empresas o clientes corporativos.',
    ],
    'negocio/reiniciar.blade.php' => [
        'title' => 'Reiniciar Datos del Sistema',
        'description' => '⚠️ Esta acción eliminará todos los datos del sistema. Use con precaución.',
        'warning' => true,
    ],
    
    // ESPECIALISTAS
    'especialistas/comisiones.blade.php' => [
        'title' => 'Configuración de Comisiones',
        'description' => 'Configure las comisiones por servicio o producto para cada especialista.',
    ],
    'especialistas/comisiones-globales.blade.php' => [
        'title' => 'Comisiones Globales', 
        'description' => 'Defina porcentajes de comisión que apliquen a todos los especialistas.',
    ],
    'especialistas/tipos-colaboradores.blade.php' => [
        'title' => 'Tipos de Colaboradores',
        'description' => 'Categorice a los colaboradores (empleados, freelance, etc.).',
    ],
    'especialistas/especialidades.blade.php' => [
        'title' => 'Especialidades',
        'description' => 'Defina las áreas de especialización disponibles.',
    ],
    'especialistas/config.blade.php' => [
        'title' => 'Configuración General de Especialistas',
        'description' => 'Opciones generales para la gestión de especialistas.',
    ],
    
    //CLIENTES
    'clientes/fuentes-referencia.blade.php' => [
        'title' => 'Fuentes de Referencia',
        'description' => 'Configure cómo los clientes conocieron su negocio.',
    ],
    'clientes/tipos-clientes.blade.php' => [
        'title' => 'Tipos de Clientes',
        'description' => 'Categorice a sus clientes (VIP, regular, nuevo, etc.).',
    ],
    'clientes/zonas.blade.php' => [
        'title' => 'Zonas de Clientes',
        'description' => 'Defina zonas geográficas para organizar a sus clientes.',
    ],
    'clientes/listados.blade.php' => [
        'title' => 'Configuración de Listados',
        'description' => 'Personalice cómo se muestran los listados de clientes.',
    ],
    'clientes/datos-obligatorios.blade.php' => [
        'title' => 'Datos Obligatorios',
        'description' => 'Defina qué campos son obligatorios al registrar un cliente.',
    ],
    'clientes/link-registro.blade.php' => [
        'title' => 'Link de Registro de Clientes',
        'description' => 'Genere y comparta el enlace para que los clientes se registren.',
    ],
    'clientes/razones-cancelacion.blade.php' => [
        'title' => 'Razones de Cancelación',
        'description' => 'Motivos por los que los clientes cancelan sus citas.',
    ],
    'clientes/descuentos.blade.php' => [
        'title' => 'Descuentos',
        'description' => 'Configure descuentos y promociones para clientes.',
    ],
    'clientes/plantillas-ficha.blade.php' => [
        'title' => 'Plantillas de Ficha Técnica',
        'description' => 'Diseñe plantillas para las fichas técnicas de clientes.',
    ],
    
    // WHATSAPP
    'whatsapp/mensajes.blade.php' => [
        'title' => 'Mensajes de WhatsApp',
        'description' => 'Configure mensajes automáticos por WhatsApp.',
    ],
    'whatsapp/plantillas.blade.php' => [
        'title' => 'Plantillas de WhatsApp',
        'description' => 'Cree plantillas de mensajes reutilizables.',
    ],
    'whatsapp/respuestas-rapidas.blade.php' => [
        'title' => 'Respuestas Rápidas',
        'description' => 'Mensajes predefinidos para responder rápidamente.',
    ],
    'whatsapp/integracion-api.blade.php' => [
        'title' => 'Integración WhatsApp API',
        'description' => 'Configure la API de WhatsApp Business.',
    ],
    
    // PRODUCTOS
    'productos/tipos.blade.php' => [
        'title' => 'Tipos de Productos',
        'description' => 'Categorice sus productos.',
    ],
    'productos/bloqueo-precios.blade.php' => [
        'title' => 'Bloqueo de Precios',
        'description' => 'Evite cambios no autorizados en los precios.',
    ],
    'productos/general.blade.php' => [
        'title' => 'Configuración General de Productos',
        'description' => 'Opciones globales para la gestión de productos.',
    ],
    
    // SERVICIOS
    'servicios/lineas.blade.php' => [
        'title' => 'Líneas de Servicios',
        'description' => 'Organice sus servicios por líneas o categorías.',
    ],
    'servicios/bloqueo-precios.blade.php' => [
        'title' => 'Bloqueo de Precios de Servicios',
        'description' => 'Proteja los precios de servicios contra cambios no autorizados.',
    ],
    
    // CAJA
    'caja/pos.blade.php' => [
        'title' => 'POS y POS Electrónico',
        'description' => 'Configure su punto de venta y POS electrónico.',
    ],
    'caja/facturas.blade.php' => [
        'title' => 'Facturas y Recibos',
        'description' => 'Opciones de facturación y recibos.',
    ],
    'caja/plantillas.blade.php' => [
        'title' => 'Plantillas de Facturación',
        'description' => 'Diseñe el formato de sus facturas.',
    ],
    
    // AGENDA
    'agenda/calendario.blade.php' => [
        'title' => 'Configuración de Agenda',
        'description' => 'Opciones del calendario y agenda.',
    ],
    'agenda/reservas-linea.blade.php' => [
        'title' => 'Reservas en Línea',
        'description' => 'Configure las reservas online de sus clientes.',
    ],
    'agenda/links.blade.php' => [
        'title' => 'Links de Reservas',
        'description' => 'Genere y comparta enlaces de reserva.',
    ],
    'agenda/formulario-registro.blade.php' => [
        'title' => 'Formulario de Registro',
        'description' => 'Personalice el formulario de registro de clientes.',
    ],
    'agenda/notificaciones.blade.php' => [
        'title' => 'Configuración de Notificaciones',
        'description' => 'Configure notificaciones automáticas.',
    ],
    'agenda/listado-notificaciones.blade.php' => [
        'title' => 'Listado de Notificaciones',
        'description' => 'Ver historial de notificaciones enviadas.',
    ],
    'agenda/estados.blade.php' => [
        'title' => 'Estados de Reservas',
        'description' => 'Defina los estados posibles de una reserva.',
    ],
    
    // MARKETING
    'marketing/fidelizacion.blade.php' => [
        'title' => 'Programa de Fidelización',
        'description' => 'Configure su programa de lealtad.',
    ],
    'marketing/mensajes-texto.blade.php' => [
        'title' => 'Mensajes de Texto (SMS)',
        'description' => 'Configure campañas de SMS marketing.',
    ],
    'marketing/correos.blade.php' => [
        'title' => 'Correos Electrónicos',
        'description' => 'Templates y campañas de email marketing.',
    ],
    
    // IMPUESTOS
    'impuestos/lista.blade.php' => [
        'title' => 'Impuestos',
        'description' => 'Configure los impuestos aplicables.',
    ],
    'impuestos/retenciones.blade.php' => [
        'title' => 'Retenciones',
        'description' => 'Gestione las retenciones fiscales.',
    ],
    
    // BONOS
    'bonos/tipos.blade.php' => [
        'title' => 'Tipos de Bonos',
        'description' => 'Configure los tipos de bonos disponibles.',
    ],
    
    // EDICIÓN MASIVA
    'edicion-masiva/comisiones-facturadas.blade.php' => [
        'title' => 'Actualizar Comisiones Facturadas',
        'description' => 'Actualización masiva de comisiones.',
    ],
    'edicion-masiva/precios-venta.blade.php' => [
        'title' => 'Actualización Masiva de Precios de Venta',
        'description' => 'Modifique precios de venta en lote.',
    ],
    'edicion-masiva/costos-productos.blade.php' => [
        'title' => 'Actualización Masiva de Costos',
        'description' => 'Actualice costos de productos masivamente.',
    ],
    'edicion-masiva/productos-consumo.blade.php' => [
        'title' => 'Productos de Consumo',
        'description' => 'Gestión de productos de consumo y descuentos administrativos.',
    ],
    'edicion-masiva/importar-productos.blade.php' => [
        'title' => 'Importar Productos',
        'description' => 'Importe productos desde archivos Excel/CSV.',
    ],
    'edicion-masiva/importar-servicios.blade.php' => [
        'title' => 'Importar Servicios',
        'description' => 'Importe servicios desde archivos Excel/CSV.',
    ],
    'edicion-masiva/importar-novedades.blade.php' => [
        'title' => 'Importar Novedades de Participaciones',
        'description' => 'Importe novedades y participaciones.',
    ],
    'edicion-masiva/importar-especialistas.blade.php' => [
        'title' => 'Importar Especialistas',
        'description' => 'Importe especialistas desde archivos.',
    ],
    'edicion-masiva/importar-clientes.blade.php' => [
        'title' => 'Importar Clientes',
        'description' => 'Importe clientes desde archivos Excel/CSV.',
    ],
    
    // INTEGRACIONES
    'integraciones/contabilidad.blade.php' => [
        'title' => 'Integración con Contabilidad',
        'description' => 'Conecte con sistemas de contabilidad.',
    ],
    'integraciones/fidelizacion.blade.php' => [
        'title' => 'Integración de Fidelización',
        'description' => 'Conecte con plataformas de fidelización.',
    ],
];

// Template base para vistas simples
$template = <<<'BLADE'
@extends('admin.configuration._layout')

@section('config_title', '{{TITLE}}')

@section('config_content')
<form action="{{ url('admin/configuration/save') }}" method="POST">
    {{ csrf_field() }}
    
    <div class="config-card">
        <div class="config-section">
            <h3 class="config-section-title">{{TITLE}}</h3>
            
            {{-- Mensaje informativo --}}
            <div style="padding: 15px; background: {{BG_COLOR}}; border: 1px solid {{BORDER_COLOR}}; border-radius: 6px; margin-bottom: 20px;">
                <p style="margin: 0; color: {{TEXT_COLOR}}; font-size: 14px;">
                    {{ICON}} {{DESCRIPTION}}
                </p>
            </div>

            {{-- Placeholder para contenido futuro --}}
            <div class="form-group">
                <p style="color: #6b7280; font-size: 14px;">
                    Esta sección está lista para configurarse. Agregue los campos necesarios según sus necesidades.
                </p>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
        </div>
    </div>
</form>
@endsection
BLADE;

// Generar archivos
$created = 0;
$errors = [];

foreach ($views as $file => $config) {
    $file_path = $base_path . $file;
    $dir = dirname($file_path);
    
    // Crear directorio si no existe
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Saltar si el archivo ya existe
    if (file_exists($file_path)) {
        echo "⏭️  Ya existe: $file\n";
        continue;
    }
    
    // Determinar colores según si es advertencia
    $is_warning = isset($config['warning']) && $config['warning'];
    $bg_color = $is_warning ? '#fee2e2' : '#eff6ff';
    $border_color = $is_warning ? '#fca5a5' : '#bfdbfe';
    $text_color = $is_warning ? '#991b1b' : '#1e40af';
    $icon = $is_warning ? '⚠️' : '📝';
    
    // Reemplazar placeholders
    $content = str_replace(
        ['{{TITLE}}', '{{DESCRIPTION}}', '{{BG_COLOR}}', '{{BORDER_COLOR}}', '{{TEXT_COLOR}}', '{{ICON}}'],
        [$config['title'], $config['description'], $bg_color, $border_color, $text_color, $icon],
        $template
    );
    
    // Crear archivo
    if (file_put_contents($file_path, $content) !== false) {
        echo "✅ Creado: $file\n";
        $created++;
    } else {
        $errors[] = $file;
        echo "❌ Error: $file\n";
    }
}

echo "\n=================================\n";
echo "✅ Archivos creados: $created\n";
if (count($errors) > 0) {
    echo "❌ Errores: " . count($errors) . "\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
}
echo "=================================\n";
