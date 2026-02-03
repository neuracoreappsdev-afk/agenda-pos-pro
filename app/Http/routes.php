<?php
\Log::info("Routes loading...");

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

// Redireccionar inicio a admin
Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('ping', function() {
    return "Laravel is responding on " . date('Y-m-d H:i:s');
});

Route::get('bypass-login', function() {
    session(['admin_session' => true]);
    session(['admin_id' => 1]); // Actualizado a ID 1
    session(['admin_login' => 'admin']);
    session(['user_role' => 'admin']);
    session(['user_name' => 'Lina Lucio']);
    session(['business_name' => 'AgendaPOS PRO']);
    return redirect('admin/dashboard');
});

// Cambiar Idioma
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es', 'pt', 'fr', 'it', 'zh', 'hi', 'ar'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
});

// Shortlink redirection system
Route::get('go/{source}', function ($source) {
    return redirect('booking?source=' . $source);
});

// Rutas Públicas de Booking
Route::get('booking', ['as' => 'booking.index', 'uses' => 'BookingController@index']);
Route::get('booking/calendar/{package_id}/{specialist_id?}', ['as' => 'booking.calendar', 'uses' => 'BookingController@getCalendar']);
Route::post('booking/lock-slot', ['as' => 'booking.lock', 'uses' => 'BookingController@lockSlot']);
Route::get('booking/customer-info', ['as' => 'booking.customerInfo', 'uses' => 'BookingController@customerInfo']);
Route::post('booking/confirm', ['as' => 'booking.confirm', 'uses' => 'BookingController@confirm']);
Route::get('booking/success', ['as' => 'booking.success', 'uses' => 'BookingController@success']);
Route::get('booking/waitlist', ['as' => 'booking.waitlist', 'uses' => 'BookingController@waitlistInfo']);
Route::post('booking/waitlist', ['as' => 'booking.waitlist.store', 'uses' => 'BookingController@waitlistConfirm']);

// Bonos de Regalo (Público)
Route::get('bonos-regalo', ['as' => 'bonos.shop', 'uses' => 'BookingController@bonosShop']);
Route::post('bonos-regalo/comprar', ['as' => 'bonos.purchase', 'uses' => 'BookingController@bonosPurchase']);
Route::get('bonos-regalo/exito/{id}', ['as' => 'bonos.success', 'uses' => 'BookingController@bonosSuccess']);

// API para obtener servicios (packages)
Route::get('api/packages', function() {
    return response()->json(\App\Models\Package::where('active', 1)->orderBy('package_name')->get());
});


// Rutas Públicas de Confirmación de Citas
Route::get('cita/confirmar/{token}', 'AppointmentPublicController@confirm');
Route::get('cita/cancelar/{token}', 'AppointmentPublicController@cancel');
Route::get('cita/modificar/{token}', 'AppointmentPublicController@modify');
Route::post('cita/modificar/{token}', 'AppointmentPublicController@saveModification');
Route::get('cita/{token}', 'AppointmentPublicController@show');

// Rutas de Colaboradores (Mobile)
Route::get('colaborador', function() { return redirect('colaborador/login'); });
Route::group(['prefix' => 'colaborador'], function() {
    Route::get('login', 'SpecialistAuthController@showLogin');
    Route::post('login', 'SpecialistAuthController@login');
    Route::get('logout', 'SpecialistAuthController@logout');
    Route::get('dashboard', 'SpecialistAuthController@dashboard');
    Route::get('cambiar-pin', 'SpecialistAuthController@showChangePin');
    Route::post('cambiar-pin', 'SpecialistAuthController@changePin');
    Route::get('get-messages', 'SpecialistAuthController@getMessages');
    Route::post('send-message', 'SpecialistAuthController@sendMessage');
    Route::post('iniciar-cita', 'SpecialistAuthController@startAppointment');
    Route::post('add-service', 'SpecialistAuthController@addServiceToAppointment');
    Route::get('get-services', 'SpecialistAuthController@getServices');
    Route::get('check-notifications', 'SpecialistAuthController@checkNotifications');
    Route::get('search-services', 'SpecialistAuthController@searchServices');
    Route::post('update-appointment-services', 'SpecialistAuthController@updateAppointmentServices');
    Route::post('acknowledge-arrival', 'SpecialistAuthController@acknowledgeArrival');
});

// Consolidated Admin Routes
Route::group(['prefix' => 'admin'], function () {
    // Login, Logout & Register
    Route::get('/', 'AdminController@index');
    Route::get('login', 'AdminController@index');
    Route::post('login', 'AdminController@login');
    Route::get('register', 'AdminController@showRegister');
    Route::post('register', 'AdminController@register');
    Route::get('logout', function() {
        Auth::logout();
        session()->flush();
        return redirect('admin');
    });

    // Admin Extensions / Communications
    Route::post('send-news', 'AdminController@sendNews');
    Route::get('chats', 'AdminController@chats');
    Route::get('chats/{id}', 'AdminController@chatWithSpecialist');
    Route::post('chats/send', 'AdminController@sendAdminMessage');
    Route::get('chats/unread-count', 'AdminController@getUnreadCount');

    // Dashboard
    Route::get('dashboard', 'AdminController@dashboard');

    // Commercial Plans
    Route::get('planes/create', 'CommercialPlanController@create');
    Route::post('planes/store', 'CommercialPlanController@store');
    Route::get('planes/{id}/edit', 'CommercialPlanController@edit');
    Route::post('planes/{id}/update', 'CommercialPlanController@update');

    // Agenda / Citas
    Route::get('appointments', 'AdminController@appointments');
    Route::get('appointments/list', 'AdminController@getAppointments');
    Route::post('appointments/store', 'AdminController@storeAppointment');
    Route::post('appointments/update', 'AdminController@updateAppointment');
    Route::post('appointments/move', 'AdminController@moveAppointment');
    Route::post('appointments/checkout', 'AdminController@checkoutAppointment');
    Route::post('appointments/mark-arrived', 'AdminController@markClientArrived');
    Route::post('appointments/delete', 'AdminController@deleteAppointment');
    Route::post('appointments/resize', 'AdminController@resizeAppointment');

    // Lista de Espera (Waitlist)
    Route::get('waitlist', 'AdminController@waitlist');
    Route::post('waitlist/store', 'AdminController@waitlistStore');
    Route::post('waitlist/delete', 'AdminController@waitlistDelete');
    Route::get('waitlist/data', 'AdminController@waitlistData');

    // Agente Call Center IA - Dashboard
    Route::get('agente-ia', 'AdminController@agenteIA');
    Route::get('agente-contador', 'AdminController@agenteContador');
    Route::get('agente-nomina', 'AdminController@agenteNomina');
    Route::get('agente-estratega', 'AdminController@agenteEstratega');
    Route::get('agente-inventario', 'AdminController@agenteInventario');
    Route::get('agente-fidelizacion', 'AdminController@agenteFidelizacion');
    Route::get('agentes/reporte/{type}', 'AdminController@downloadReport');

    // Rutas de Control de Agente (Intervención Humana)
    Route::post('configuration/save-ai-settings', 'ConfigurationController@saveAiSettings');
    
    // Explicit Webhook for Meta Integration (Publicly accessible via Ngrok)
    Route::match(['get', 'post'], 'api/ia/webhook', 'AIAgentController@webhook');
    
    Route::post('agente/toggle-pause', 'AIAgentController@togglePause');
    Route::post('agente/send-message', 'AIAgentController@sendMessage');

    // Productos CRUD (Inventario)
    Route::get('productos', 'AdminController@productos');
    Route::post('productos', 'AdminController@storeProduct');
    Route::get('productos/{id}/json', 'AdminController@getProductJson');
    Route::post('productos/{id}/update', 'AdminController@updateProduct');
    Route::post('productos/{id}/delete', 'AdminController@deleteProduct');
    Route::post('productos/adjust-stock', 'AdminController@adjustProductStock');

    // Surveys (Encuestas)
    Route::get('surveys/create', 'SurveyController@create');
    Route::post('surveys/store', 'SurveyController@store');
    Route::get('surveys/{id}/edit', 'SurveyController@edit');
    Route::post('surveys/{id}/update', 'SurveyController@update');

    // Sales Submenu Routes
    Route::get('ventas/bonos', 'ReportsController@listadoBonos');
    Route::post('ventas/bonos/store', 'ReportsController@storeBono');
    Route::get('ventas/planes', 'ReportsController@listadoPlanes');
    Route::get('ventas/devoluciones', 'ReportsController@devoluciones');
    Route::post('ventas/devoluciones/store', 'ReportsController@storeDevolucion');

    // Clientes CRUD
    Route::get('clientes/list', 'AdminController@listClients');
    Route::post('clientes/store', 'AdminController@storeClient');
    Route::post('clientes/update', 'AdminController@updateClient');
    Route::post('notificaciones/read-all', 'AdminController@markNotifsRead');
    Route::post('clientes/delete', 'AdminController@deleteClient');

    // Disponibilidad
    Route::get('availability', 'AdminController@availability');
    Route::post('availability', 'AdminController@updateAvailability');

    // SaaS Subscription & Billing
    Route::get('subscription', 'SubscriptionController@index');
    Route::get('subscription/pay/{plan_id}', 'SubscriptionController@pay'); 
    Route::get('subscription/callback', 'SubscriptionController@callback');

    // Historias Clínicas (Health Vertical)
    Route::get('historias-clinicas', 'AdminController@historiasClinicas');
    Route::get('historias-clinicas/{id}', 'AdminController@verHistoriaClinica');
    Route::get('historias-clinicas/reporte/{record_id}', 'AdminController@generarReporteClinico');
    Route::post('historias-clinicas/save', 'AdminController@saveHistoriaClinica');
    Route::post('historias-clinicas/ai-suggest', 'AdminController@aiSuggestDiagnosis');
    Route::post('consentimientos/guardar', 'AdminController@saveConsent');
    // Auto vertical
    Route::get('historial-vehicular', 'AdminController@historialVehicular');
    // Óptica
    Route::get('formulas-opticas', 'AdminController@formulasOpticas');
    Route::get('formulas-opticas/{id}', 'AdminController@verFormulaOptica');
    Route::post('formulas-opticas/save', 'AdminController@saveFormulaOptica');

    // Contabilidad
    Route::get('reportes-contables', 'AdminController@reportesContables');

    // Legal
    Route::get('expedientes-digitales', 'AdminController@expedientesDigitales');

    // Inmobiliaria
    Route::get('fichas-propiedades', 'AdminController@fichasPropiedades');

    // Gym
    Route::get('fichas-antropometricas', 'AdminController@fichasAntropometricas');

    // Psicología
    Route::get('sesiones-terapeuticas', 'AdminController@sesionesTerapeuticas');

    // Odontología
    Route::get('historias-clinicas-odontologia', 'AdminController@historiasClinicasOdonto');

    // Test Tool - Vertical Switcher
    Route::get('test-vertical/{type}', 'AdminController@testVertical');

    // Configuración Principal
    Route::get('configuration', 'ConfigurationController@index');
    
    // Configuración de Negocio
    Route::group(['prefix' => 'configuration'], function () {
        Route::get('business-details', ['as' => 'admin.config.business', 'uses' => 'ConfigurationController@detallesNegocio']);
        Route::post('business-details', ['as' => 'admin.config.business.save', 'uses' => 'ConfigurationController@saveDetallesNegocio']);
        Route::get('business-schedule', ['as' => 'admin.config.schedule', 'uses' => 'ConfigurationController@horariosFestivos']);
        Route::post('business-schedule', ['as' => 'admin.config.schedule.save', 'uses' => 'ConfigurationController@saveHorariosFestivos']);
        Route::post('business-schedule/holidays', ['as' => 'admin.config.holidays.store', 'uses' => 'ConfigurationController@storeHoliday']);
        Route::post('business-schedule/holidays/auto-load', ['as' => 'admin.config.holidays.autoload', 'uses' => 'ConfigurationController@autoLoadHolidays']);
        Route::post('business-schedule/holidays/update', ['as' => 'admin.config.holidays.update', 'uses' => 'ConfigurationController@updateHoliday']);
        Route::get('business-schedule/holidays/delete/{id}', ['as' => 'admin.config.holidays.delete', 'uses' => 'ConfigurationController@deleteHoliday']);
        Route::post('business-schedule/holidays/toggle/{id}', ['as' => 'admin.config.holidays.toggle', 'uses' => 'ConfigurationController@toggleHoliday']);
        Route::post('business-schedule/extended', ['as' => 'admin.config.extended.store', 'uses' => 'ConfigurationController@storeExtended']);
        Route::get('business-schedule/extended/delete/{id}', ['as' => 'admin.config.extended.delete', 'uses' => 'ConfigurationController@deleteExtended']);
        Route::get('sedes', 'ConfigurationController@sedes');
        Route::post('sedes', ['as' => 'admin.config.sedes.store', 'uses' => 'ConfigurationController@storeSede']);
        Route::post('sedes/update', ['as' => 'admin.config.sedes.update', 'uses' => 'ConfigurationController@updateSede']);
        Route::get('sedes/delete/{id}', ['as' => 'admin.config.sedes.delete', 'uses' => 'ConfigurationController@deleteSede']);
        Route::get('usuarios', ['as' => 'config.usuarios', 'uses' => 'ConfigurationController@usuarios']);
        Route::post('usuarios', 'ConfigurationController@storeUsuario');
        Route::post('usuarios/update', 'ConfigurationController@updateUsuario');
        Route::get('usuarios/delete/{id}', 'ConfigurationController@deleteUsuario');
        
        
        // Negocio y Gestión
        // Negocio y Gestión
        Route::get('roles', ['as' => 'config.roles', 'uses' => 'ConfigurationController@roles']);
        Route::post('roles/save', ['as' => 'config.save_role', 'uses' => 'ConfigurationController@saveRole']);
        Route::post('roles/delete', ['as' => 'config.delete_role', 'uses' => 'ConfigurationController@deleteRole']);
        Route::get('permisos', ['as' => 'config.permisos', 'uses' => 'ConfigurationController@permisos']);
        Route::post('permisos', ['as' => 'config.save_permisos', 'uses' => 'ConfigurationController@savePermisos']);
        Route::get('conceptos', ['as' => 'config.conceptos', 'uses' => 'ConfigurationController@conceptos']);
        Route::post('conceptos/save', ['as' => 'config.save_conceptos', 'uses' => 'ConfigurationController@saveConceptos']);
        Route::post('conceptos/delete', ['as' => 'config.delete_concepto', 'uses' => 'ConfigurationController@deleteConcepto']);
        Route::get('empresas', ['as' => 'config.empresas', 'uses' => 'ConfigurationController@empresas']);
        Route::post('empresas/save', ['as' => 'config.save_empresa', 'uses' => 'ConfigurationController@saveEmpresa']);
        Route::post('empresas/delete', ['as' => 'config.delete_empresa', 'uses' => 'ConfigurationController@deleteEmpresa']);
        Route::get('reiniciar-datos', ['as' => 'config.reset_data', 'uses' => 'ConfigurationController@reiniciarDatos']);
        Route::post('reiniciar/productos', ['as' => 'config.reset_products', 'uses' => 'ConfigurationController@reiniciarProductos']);
        Route::post('reiniciar/servicios', ['as' => 'config.reset_services', 'uses' => 'ConfigurationController@reiniciarServicios']);
        Route::post('reiniciar/especialistas', ['as' => 'config.reset_specialists', 'uses' => 'ConfigurationController@reiniciarEspecialistas']);
        
        
        // Especialistas y Comisiones
        Route::get('comisiones', ['as' => 'config.comisiones', 'uses' => 'ConfigurationController@comisiones']);
        Route::post('comisiones', ['as' => 'config.save_comisiones', 'uses' => 'ConfigurationController@saveComisiones']);
        Route::get('comisiones-globales', ['as' => 'config.comisiones_globales', 'uses' => 'ConfigurationController@comisionesGlobales']);
        Route::get('tipos-colaboradores', ['as' => 'config.tipos_colaboradores', 'uses' => 'ConfigurationController@tiposColaboradores']);
        Route::post('save-tipo-colaborador', ['as' => 'config.save_tipo_colaborador', 'uses' => 'ConfigurationController@saveTipoColaborador']);
        Route::post('delete-tipo-colaborador', ['as' => 'config.delete_tipo_colaborador', 'uses' => 'ConfigurationController@deleteTipoColaborador']);
        Route::get('especialidades', ['as' => 'config.especialidades', 'uses' => 'ConfigurationController@especialidades']);
        Route::post('save-especialidad', ['as' => 'config.save_especialidad', 'uses' => 'ConfigurationController@saveEspecialidad']);
        Route::post('delete-especialidad', ['as' => 'config.delete_especialidad', 'uses' => 'ConfigurationController@deleteEspecialidad']);
        Route::get('config-especialista', ['as' => 'config.especialista_config', 'uses' => 'ConfigurationController@configEspecialista']);
        
        // Cliente
        Route::get('fuentes-referencia', ['as' => 'config.fuentes_referencia', 'uses' => 'ConfigurationController@fuentesReferencia']);
        Route::get('tipos-clientes', ['as' => 'config.tipos_clientes', 'uses' => 'ConfigurationController@tiposClientes']);
        Route::get('zonas-clientes', ['as' => 'config.zonas_clientes', 'uses' => 'ConfigurationController@zonasClientes']);
        Route::get('config-listados', ['as' => 'config.listados', 'uses' => 'ConfigurationController@configListados']);
        Route::get('datos-obligatorios', ['as' => 'config.datos_obligatorios', 'uses' => 'ConfigurationController@datosObligatorios']);
        Route::get('link-registro-cliente', ['as' => 'config.link_registro', 'uses' => 'ConfigurationController@linkRegistroCliente']);
        Route::get('razones-cancelacion', ['as' => 'config.razones_cancelacion', 'uses' => 'ConfigurationController@razonesCancelacion']);
        Route::get('descuentos', ['as' => 'config.descuentos', 'uses' => 'ConfigurationController@descuentos']);
        Route::get('plantillas-ficha-tecnica', ['as' => 'config.plantillas_ficha', 'uses' => 'ConfigurationController@plantillasFichaTecnica']);
        
        // WhatsApp
        Route::get('mensajes-whatsapp', 'ConfigurationController@mensajesWhatsapp');
        Route::get('plantillas-whatsapp', 'ConfigurationController@plantillasWhatsapp');
        Route::get('respuestas-rapidas-whatsapp', 'ConfigurationController@respuestasRapidasWhatsapp');
        Route::get('integracion-whatsapp-api', 'ConfigurationController@integracionWhatsappApi');
        
        // Productos
        Route::get('tipos-productos', 'ConfigurationController@tiposProductos');
        Route::get('bloqueo-precios-productos', 'ConfigurationController@bloqueoPrecios');
        Route::get('config-general-productos', 'ConfigurationController@configGeneralProductos');
        
        // Servicios
        Route::get('lineas-servicios', 'ConfigurationController@lineasServicios');
        Route::get('bloqueo-precios-servicios', 'ConfigurationController@bloquePreciosServicios');
        
        // Integraciones
        Route::get('contabilidad', 'ConfigurationController@contabilidad');
        Route::get('fidelizacion-integracion', 'ConfigurationController@fidelizacionIntegracion');
        Route::get('webhooks', ['as' => 'admin.config.webhooks', 'uses' => 'ConfigurationController@webhooks']);
        Route::post('webhooks', ['as' => 'admin.config.webhooks.save', 'uses' => 'ConfigurationController@saveWebhooks']);
        
        // Caja y Facturación
        Route::get('pos-electronico', 'ConfigurationController@posElectronico');
        Route::post('pos-electronico/save', 'ConfigurationController@savePosElectronico');
        Route::get('facturas-recibos', 'ConfigurationController@facturasRecibos');
        Route::post('facturas-recibos/save', 'ConfigurationController@saveFacturasRecibos');
        Route::post('cajas/save', 'ConfigurationController@saveCaja');
        Route::post('cajas/delete', 'ConfigurationController@deleteCaja');
        Route::post('resoluciones/save', 'ConfigurationController@saveResolucion');
        Route::post('resoluciones/delete', 'ConfigurationController@deleteResolucion');
        Route::post('emision/save', 'ConfigurationController@saveEmisionConsecutivo');
        Route::get('formas-pago', 'ConfigurationController@formasPago');
        Route::post('formas-pago/save', 'ConfigurationController@saveFormaPago');
        Route::post('formas-pago/delete', 'ConfigurationController@deleteFormaPago');
        Route::get('plantillas-facturacion', 'ConfigurationController@plantillasFacturacion');
        Route::post('save-links', 'ConfigurationController@saveLinksConfig');
        
        // Agenda y Reservas
        Route::get('agenda-calendario', ['as' => 'config.agenda_calendario', 'uses' => 'ConfigurationController@agendaCalendario']);
        Route::get('reservas-linea', ['as' => 'config.reservas_linea', 'uses' => 'ConfigurationController@reservasLinea']);
        Route::get('links-reservas', ['as' => 'config.links_reservas', 'uses' => 'ConfigurationController@linksReservas']);
        Route::get('formulario-registro-clientes', ['as' => 'config.formulario_registro', 'uses' => 'ConfigurationController@formularioRegistroClientes']);
        Route::post('save-formulario-registro', ['as' => 'config.save_formulario_registro', 'uses' => 'ConfigurationController@saveFormularioRegistro']);
        Route::get('config-notificaciones', ['as' => 'config.notificaciones', 'uses' => 'ConfigurationController@configNotificaciones']);
        Route::post('save-notificaciones', ['as' => 'config.save_notificaciones', 'uses' => 'ConfigurationController@saveNotificaciones']);
        Route::get('listado-notificaciones', ['as' => 'config.listado_notificaciones', 'uses' => 'ConfigurationController@listadoNotificaciones']);
        Route::get('estados-reservas', ['as' => 'config.estados_reservas', 'uses' => 'ConfigurationController@estadosReservas']);
        Route::post('save-estado-reserva', ['as' => 'config.save_estado_reserva', 'uses' => 'ConfigurationController@saveEstadoReserva']);
        
        // Marketing
        // Marketing
        Route::get('fidelizacion', ['as' => 'config.fidelizacion', 'uses' => 'ConfigurationController@fidelizacion']);
        Route::get('mensajes-texto', ['as' => 'config.sms', 'uses' => 'ConfigurationController@mensajesTexto']);
        Route::get('correos-electronicos', ['as' => 'config.email', 'uses' => 'ConfigurationController@correosElectronicos']);
        
        // Impuestos
        Route::get('impuestos', ['as' => 'config.impuestos', 'uses' => 'ConfigurationController@impuestos']);
        Route::get('retenciones', ['as' => 'config.retenciones', 'uses' => 'ConfigurationController@retenciones']);
        Route::post('save-retencion', ['as' => 'config.save_retencion', 'uses' => 'ConfigurationController@saveRetencion']);
        
        // Comisiones Globales
        Route::post('save-comisiones-globales', 'ConfigurationController@saveComisionesGlobales');
        
        // Bonos
        Route::get('tipos-bonos', ['as' => 'config.tipos_bonos', 'uses' => 'ConfigurationController@tiposBonos']);
        
        // Edición Masiva
        Route::get('actualizar-comisiones-facturadas', ['as' => 'config.bulk_commissions', 'uses' => 'ConfigurationController@actualizarComisionesFacturadas']);
        Route::get('actualizacion-masiva-precios-venta', ['as' => 'config.bulk_prices', 'uses' => 'ConfigurationController@actualizacionMasivaPreciosVenta']);
        Route::get('actualizacion-masiva-costos-productos', ['as' => 'config.bulk_costs', 'uses' => 'ConfigurationController@actualizacionMasivaCostosProductos']);
        Route::get('productos-consumo-descuentos', ['as' => 'config.bulk_consumables', 'uses' => 'ConfigurationController@productosConsumoDescuentos']);
        Route::get('importar-productos', ['as' => 'config.import_products', 'uses' => 'ConfigurationController@importarProductos']);
        Route::get('importar-servicios', ['as' => 'config.import_services', 'uses' => 'ConfigurationController@importarServicios']);
        Route::get('importar-novedades-participaciones', ['as' => 'config.import_news', 'uses' => 'ConfigurationController@importarNovedadesParticipaciones']);
        Route::get('importar-especialistas', ['as' => 'config.import_specialists', 'uses' => 'ConfigurationController@importarEspecialistas']);
        Route::post('process-import-specialists', ['as' => 'config.process_import_specialists', 'uses' => 'ConfigurationController@processImportSpecialists']);
        Route::get('importar-clientes', ['as' => 'config.import_clients', 'uses' => 'ConfigurationController@importarClientes']);
        Route::post('process-import-clients', ['as' => 'config.process_import_clients', 'uses' => 'ConfigurationController@processImportClients']);
        Route::post('process-import-products', ['as' => 'config.process_import_products', 'uses' => 'ConfigurationController@processImportProducts']);
        Route::post('process-import-services', ['as' => 'config.process_import_services', 'uses' => 'ConfigurationController@processImportServices']);
        Route::post('process-import-news', ['as' => 'config.process_import_news', 'uses' => 'ConfigurationController@processImportNews']);
        
        Route::post('update-bulk-costs', ['as' => 'config.update_bulk_costs', 'uses' => 'ConfigurationController@updateBulkCosts']);
        Route::post('update-bulk-prices', ['as' => 'config.update_bulk_prices', 'uses' => 'ConfigurationController@updateBulkPrices']);
        Route::post('recalc-commissions', ['as' => 'config.recalc_commissions', 'uses' => 'ConfigurationController@recalculateCommissions']);
        
        // Generic save route for settings
        Route::post('save', ['as' => 'config.save', 'uses' => 'ConfigurationController@saveSettings']);
    });

    // Servicios (Packages)
    Route::get('packages', 'AdminController@packages');
    Route::get('packages/create', 'AdminController@createPackage');
    Route::post('packages', 'AdminController@storePackage');
    Route::get('packages/{id}/edit', 'AdminController@editPackage');
    Route::post('packages/{id}/update', 'AdminController@updatePackage');
    Route::delete('packages/{id}', 'AdminController@deletePackage');
    Route::post('packages/{id}/delete', 'AdminController@deletePackage'); // Legacy

    // Especialistas
    Route::get('specialists', 'AdminController@specialists');
    Route::get('specialists/create', 'AdminController@createSpecialist');
    Route::post('specialists', 'AdminController@storeSpecialist');
    Route::post('specialists/send-invite', 'AdminController@sendSpecialistInvite');
    Route::get('specialists/advances', 'AdminController@specialistAdvances');
    Route::post('specialists/advances', 'AdminController@storeSpecialistAdvance');
    Route::get('specialists/{id}/edit', 'AdminController@editSpecialist');
    Route::post('specialists/{id}/update', 'AdminController@updateSpecialist');
    Route::delete('specialists/{id}', 'AdminController@deleteSpecialist');

    // Caja y POS
    Route::get('caja', 'AdminController@caja');
    Route::get('caja/apertura', 'CashRegisterController@showOpen');
    Route::post('caja/abrir', 'CashRegisterController@storeOpen');
    Route::get('caja/cierre', 'CashRegisterController@showClose');
    Route::post('caja/cerrar', 'CashRegisterController@storeClose');
    Route::get('caja/resumen/{id}', 'CashRegisterController@showSummary');
    Route::get('caja/pos', 'AdminController@crearFactura');
    Route::get('crear-factura', function() {
        $adminId = session('admin_id') ?: 1;
        $session = \App\Models\CashRegisterSession::where('user_id', $adminId)->where('status', 'open')->first();
        return $session ? redirect('admin/caja/pos') : redirect('admin/caja/apertura');
    });

    // Otros
    Route::get('panel-control', 'AdminController@panelControl');
    Route::get('waitlist', 'AdminController@waitlist');
    Route::get('turnos', 'AdminController@turnos');
    Route::get('clientes', 'AdminController@clientes');
    Route::get('servicios', function() { return redirect('admin/packages'); });
    
    // Otras rutas de negocio
    Route::get('ventas/bonos', 'AdminController@ventasBonos');
    Route::get('ventas/planes', 'AdminController@ventasPlanes');
    Route::get('ventas/devoluciones', 'AdminController@ventasDevoluciones');
    Route::get('compras/proveedores', 'AdminController@comprasProveedores');
    Route::post('compras/proveedores/store', 'AdminController@storeProvider');
    Route::get('compras/proveedores/{id}/json', 'AdminController@getProviderJson');
    Route::get('compras/proveedores/{id}/delete', 'AdminController@deleteProvider');
    Route::get('compras/facturas', 'AdminController@comprasFacturas');
    Route::get('compras/recepcion-documentos', 'AdminController@comprasRecepcionDocumentos');
    Route::get('compras/create', 'AdminController@comprasCreate');
    Route::get('cuenta-empresa', 'AdminController@cuentaEmpresaIndex');
    Route::get('cuenta-empresa/ingresos', 'AdminController@cuentaEmpresaIngresos');
    Route::get('cuenta-empresa/egresos', 'AdminController@cuentaEmpresaEgresos');
    Route::get('cuenta-empresa/informes', 'AdminController@cuentaEmpresaInformes');
    Route::post('cuenta-empresa/store', 'AdminController@cuentaEmpresaStore');
    Route::get('productos', 'AdminController@productos'); // CRUD
    Route::get('inventario', 'AdminController@inventarioIndex');
    Route::get('traslados', 'AdminController@trasladosIndex');
    Route::get('traslados/create', 'AdminController@trasladosCreate');
    Route::post('traslados/store', 'AdminController@trasladosStore');
    Route::get('compuestos', 'AdminController@compuestosIndex');
    Route::get('compuestos/create', 'AdminController@compuestosCreate');
    Route::post('compuestos/store', 'AdminController@compuestosStore');
    

    // API configuration
    Route::group(['prefix' => 'api/configuration'], function() {
        Route::get('payment-methods', 'ConfigurationApiController@getPaymentMethods');
        Route::post('payment-methods', 'ConfigurationApiController@storePaymentMethod');
        Route::put('payment-methods/{id}', 'ConfigurationApiController@updatePaymentMethod');
        Route::delete('payment-methods/{id}', 'ConfigurationApiController@deletePaymentMethod');
    });

    // Sales and Chat
    Route::post('sales/store', 'SalesController@store');
    Route::get('chats/unread-count', 'AdminController@unreadChatsCount');
    Route::get('chats', 'AdminController@indexChats');

    // Informes Extension
    Route::group(['prefix' => 'informes'], function() {
        Route::get('/', function() { return view('admin/informes/index'); });
        Route::get('listado-bonos', 'ReportsController@listadoBonos');
        
        // Especialistas y Nómina
        Route::get('consultar-participaciones', 'ReportsController@comisiones');
        Route::get('participacion-detallada', 'ReportsController@comisionDetallada');
        Route::get('ventas-por-especialista', 'ReportsController@especialistas');
        Route::get('ventas-especialistas-clientes', 'ReportsController@ventasEspecialistaCliente');
        Route::get('planilla-pago', 'ReportsController@planillaPago');
        Route::get('novedades', 'ReportsController@novedades');
        Route::get('participaciones-pago-dividido', 'ReportsController@comisionesPagoDividido');
        Route::get('liquidacion-estilistas', 'ReportsController@liquidacionEstilistas');
        Route::get('reporte-novedades-especialistas', 'ReportsController@reporteNovedades');
        Route::get('bloqueo-especialistas', 'ReportsController@bloqueosAgenda');
        
        // Refactored Reports (Moved Logic to Controller)
        Route::get('ventas', 'ReportsController@ventas');
        Route::get('ventas-generales', 'ReportsController@ventas');
        
        // --- NEW SALES REPORTS ---
        Route::get('ventas-facturacion-impuestos', 'ReportsController@ventasImpuestos');
        Route::get('ventas-por-sede', 'ReportsController@ventasSede');
        Route::get('ventas-por-cliente', 'ReportsController@ventasCliente');
        Route::get('ventas-productos-servicios', 'ReportsController@ventasProdServ');
        Route::get('ventas-por-dia', 'ReportsController@ventasDia');
        Route::get('ventas-por-mes', 'ReportsController@ventasMes');
        Route::get('ventas-por-vendedor', 'ReportsController@ventasVendedor');
        Route::get('ventas-medios-pago', 'ReportsController@ventasMedioPago');
        Route::get('ventas-tipo-facturacion', 'ReportsController@ventasTipoFacturacion');
        Route::get('ventas-mandato-detallado', 'ReportsController@ventasMandato');
        Route::get('ventas-comparativas-sedes', 'ReportsController@comparativaSedes');
        // -------------------------

        Route::get('especialistas', 'ReportsController@especialistas');
        Route::get('clientes', 'ReportsController@clientes');
        Route::get('comisiones', 'ReportsController@comisiones');
        Route::get('listado-precios-servicios', 'ReportsController@servicios');
        Route::get('productos-existencias-bajas', 'ReportsController@productosStock');
        
        // Existing Reports
        Route::get('caja', function() { return redirect('admin/informes/movimientos-caja'); });
        Route::get('estado-resultados', 'ReportsController@estadoResultados'); 
        Route::get('gastos', 'ReportsController@gastos');
        Route::get('rentabilidad-servicios', 'ReportsController@rentabilidadServicios');
        Route::get('rentabilidad-productos', 'ReportsController@rentabilidadProductos');
        Route::get('presupuesto-vs-real', 'ReportsController@presupuestoVsReal');
        Route::get('codigos-verificacion', 'ReportsController@codigosVerificacion');
        Route::get('porcentaje-franquicias', 'ReportsController@porcentajeFranquicias');
        
        // CRM / Customer Reports
        Route::get('fuentes-referencia', 'ReportsController@fuentesReferencia');
        Route::get('frecuencia-clientes', 'ReportsController@frecuenciaClientes');
        Route::get('ventas-por-asesor', 'ReportsController@ventasPorAsesor');
        Route::get('fichas-tecnicas', 'ReportsController@fichasTecnicas');
        Route::get('retorno-nuevos-clientes', 'ReportsController@retornoNuevosClientes');
        Route::get('puntos', 'ReportsController@puntos');
        Route::get('trazabilidad-clientes', 'ReportsController@trazabilidadClientes');

        // Caja / Banking Reports
        Route::get('movimientos-caja', 'ReportsController@movimientosCaja');
        Route::get('movimientos-cuentas-efectivo', 'ReportsController@movimientosCuentasEfectivo');
        Route::get('codigos-verificacion-pago', 'ReportsController@codigosVerificacionPago');

        // Missing Reports implemented (Synced with Index)
        Route::get('ventas-por-servicios', 'ReportsController@ventasPorServicio');
        Route::get('exportar-facturas', 'ReportsController@exportarVentas');
        Route::get('exportar-recibos', 'ReportsController@exportarRecibos');
        Route::get('exportar-compras', 'ReportsController@exportarCompras');
        Route::get('reporte-auto-renta', 'ReportsController@reporteAutoRenta');
        Route::get('exportar-creditos', 'ReportsController@listadoPagosCreditos');
        Route::get('listado-pagos-creditos', 'ReportsController@listadoPagosCreditos');
        Route::get('facturacion-participaciones', 'ReportsController@facturacionComisiones');
        Route::get('analitica-chat', 'ReportsController@analiticaChat');
        Route::get('puntos', 'ReportsController@puntos');
        Route::get('facturacion-electronica', 'ReportsController@ventasImpuestos');
        Route::get('saldos-inventario', 'ReportsController@saldosInventario');
        Route::get('saldos-inventario-hoy', 'ReportsController@saldosInventario');
        Route::get('saldos-inventario-fechas', 'ReportsController@saldosInventario');
        Route::get('movimientos-inventarios', 'ReportsController@movimientosInventario');
        Route::get('mensajes-ads', 'ReportsController@mensajesAds');
        Route::get('devoluciones', 'ReportsController@devoluciones');
        Route::get('rotacion-productos', 'ReportsController@rotacionProductos');
        Route::get('agenda-reservas', 'ReportsController@reporteAgenda');
        Route::get('listado-planes', 'ReportsController@listadoPlanes');
        Route::get('listado-creditos-pagos', 'ReportsController@listadoPagosCreditos');
        Route::get('log-cambios-usuarios', 'ReportsController@logUsuarios');
        Route::get('log-cambios-facturas', 'ReportsController@logFacturas');
        Route::get('impuesto-ventas-productos', 'ReportsController@impuestosVentasProductos');
        Route::get('impuesto-compras-productos', 'ReportsController@impuestosComprasProductos');
        Route::get('historial-pedidos', 'ReportsController@historialPedidos');
        Route::get('listado-pedidos', 'ReportsController@historialPedidos');
        Route::get('listado-precios-productos', 'ReportsController@saldosInventario');
        Route::get('conversion-medidas', 'ReportsController@conversionMedidas');
        Route::get('ventas-por-productos', 'ReportsController@rentabilidadProductos');
        Route::get('ventas-generales-productos', 'ReportsController@rentabilidadProductos');
        Route::get('ventas-productos-consumo', 'ReportsController@ventasProductosConsumo');
        Route::get('ventas-productos-consumo-solo', 'ReportsController@ventasProductosConsumoSolo');
        Route::get('inventario-valorizado', 'ReportsController@saldosInventario');
        Route::get('respuestas-encuestas', 'ReportsController@respuestasEncuestas');

        // Fallback for others (movimientos-caja, etc.)
        Route::get('{view}', 'ReportsController@placeholder');
    });

    // --- RUTAS DE LOS AGENTES DE IA (PARA n8n / AUTOMATIZACIONES) ---
    Route::group(['prefix' => 'api/ia'], function() {
        // Rutas del Agente de Call Center / Reservas
        Route::get('servicios', 'AIAgentController@getServicios');
        Route::get('horarios', 'AIAgentController@getHorariosAtencion');
        Route::get('disponibilidad', 'AIAgentController@getDisponibilidad');
        Route::post('agendar', 'AIAgentController@agendarCita');
        Route::post('gestionar-cita', 'AIAgentController@confirmarCita');
        Route::get('buscar-citas', 'AIAgentController@getCitasCliente');
        Route::post('enviar-mensaje', 'AIAgentController@sendMessage');
        Route::get('info', 'AIAgentController@getInfoNegocio');
        Route::post('webhook', 'AIAgentController@webhook');
        
        // Rutas para los Agentes Financieros (Contador, Nómina, Estratega)
        Route::get('stats-contador', 'AIAgentController@getStatsContador');
        Route::get('stats-nomina', 'AIAgentController@getStatsNomina');
        Route::get('stats-estratega', 'AIAgentController@getStatsEstratega');
        Route::get('stats-inventario', 'AIAgentController@getStatsInventario');
        Route::get('stats-fidelizacion', 'AIAgentController@getStatsFidelizacion');
    });

});

// ==========================================
// RUTAS DEL CREADOR (SaaS MASTER)
// ==========================================

// Módulo de Facturación Electrónica DIAN
    Route::group(['prefix' => 'facturacion'], function() {
        Route::post('emitir', 'FacturacionController@emitir');
        Route::get('download-xml/{id}', function($id) {
            return response("EF-{$id}-" . date('Ymd') . ".xml")
                ->header('Content-Type', 'text/xml')
                ->header('Content-Disposition', 'attachment; filename="factura_'.$id.'.xml"');
        });
    });

// Módulo de Consentimientos Informados
    Route::group(['prefix' => 'consentimientos'], function() {
        Route::post('guardar', 'ConsentController@store');
    });

Route::group(['prefix' => 'creator'], function() {
    Route::get('dashboard', 'CreatorController@dashboard');
    Route::post('business/store', 'CreatorController@store');
    Route::get('business/{id}', 'CreatorController@businessDetail');
    Route::get('impersonate/{id}', 'CreatorController@impersonate');
    Route::get('support', 'CreatorController@support');
    Route::get('ticket/{id}', 'CreatorController@ticketDetail');
    Route::get('apps', 'CreatorController@apps');
    Route::post('apps/update/{id}', 'CreatorController@updateApp');
    Route::post('approve-admin/{id}', 'CreatorController@approveAdmin');
    Route::post('hard-reset', 'CreatorController@hardReset');
    Route::post('business/activate-plan', 'CreatorController@activatePlan');
    
    // Route to install/reset default apps
    Route::get('setup-apps', function() {
        if (!session('admin_session')) return redirect('admin');
        
        \DB::table('core_apps')->delete();
        
        \DB::table('core_apps')->insert([
            [
                'name' => 'AgendaPOS PRO',
                'slug' => 'standard',
                'description' => 'Versión Oficial Estándar',
                'primary_color' => '#111827', // Negro/Gris oscuro profesional
                'secondary_color' => '#3b82f6', // Azul acento
                'font_family' => 'Outfit', // Tipografía del sistema
                'icon' => 'layout-grid',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
        
        return redirect('creator/apps')->with('success', 'Sistema normalizado a versión única estándar.');
    });
    // Install SaaS Plans
    Route::get('setup-billing', function() {
        if (!session('admin_session')) return redirect('admin');
        
        \DB::table('saas_plans')->delete();
        
        \DB::table('saas_plans')->insert([
            // 1. FREE TIER: Core Operational
            [
                'name' => 'AgendaPOS Free',
                'slug' => 'free',
                'description' => 'Todo lo que necesitas para operar tu negocio. Agenda, POS y Clientes ilimitados.',
                'price' => 0.00,
                'max_users' => 99, // Unlimited users for free as per strategy
                'max_branches' => 1,
                'whatsapp_integration' => false,
                'has_ai' => false,
                'features_json' => json_encode([
                    'agenda_unlimited' => true,
                    'pos_unlimited' => true,
                    'inventory_basic' => true,
                    'financial_insights' => false,
                    'growth_tools' => false
                ]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // 2. SMART TIER: Financial Control ($12/mo)
            [
                'name' => 'Control Financiero Inteligente',
                'slug' => 'smart-financial',
                'description' => 'Entiende dónde ganas y pierdes dinero. Alertas de fugas y reportes automáticos. (Cobro Mensual)',
                'price' => 12.00,
                'max_users' => 99,
                'max_branches' => 1,
                'whatsapp_integration' => false,
                'has_ai' => true, // Limited AI (Accountant only)
                'features_json' => json_encode([
                    'agenda_unlimited' => true,
                    'pos_unlimited' => true,
                    'inventory_basic' => true,
                    'financial_insights' => true, // KEY FEATURE
                    'ai_accountant' => true,
                    'growth_tools' => false
                ]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            
            // 3. PRO TIER: Full AI Team ($20/mo)
            [
                'name' => 'Equipo de Crecimiento IA',
                'slug' => 'pro-ai-team',
                'description' => 'Tu equipo completo de expertos: Contador, Nómina, Call Center y Marketing. (Cobro Mensual)',
                'price' => 20.00,
                'max_users' => 99,
                'max_branches' => 1,
                'whatsapp_integration' => true, // Includes WhatsApp Bot
                'has_ai' => true, // Full AI Suite
                'features_json' => json_encode([
                    'agenda_unlimited' => true,
                    'pos_unlimited' => true,
                    'inventory_advanced' => true,
                    'financial_insights' => true,
                    'ai_accountant' => true,
                    'ai_payroll' => true,
                    'ai_receptionist' => true,
                    'growth_tools' => true, // Recovery & Loyalty
                    'whatsapp_automation' => true
                ]),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ]);
        
        return redirect('creator/dashboard')->with('success', 'Arquitectura de Facturación desplegada. Planes creados.');
    });
});
