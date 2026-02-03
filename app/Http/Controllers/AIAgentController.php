<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Specialist;
use App\Models\Setting;
use DB;

/**
 * Controlador para los endpoints que usará el Agente de IA
 * Proporciona acceso a información del negocio, disponibilidad y gestión de citas
 */
class AIAgentController extends Controller
{
    private $apiToken;

    public function __construct()
    {
        // El Token se define en la tabla settings. Si no existe, usamos uno por defecto.
        $this->apiToken = Setting::get('ai_api_token', 'AGENTA_IA_SECURE_2026');
    }

    private function validateRequest(Request $request)
    {
        // 1. Validar Token de Seguridad
        $token = $request->header('X-IA-Token') ?: $request->input('api_token');
        if ($token !== $this->apiToken) {
            return false;
        }

        // 2. Validar que el negocio tenga el Plan IA Activo
        // Usamos el helper que creamos en SubscriptionController
        if (!\App\Http\Controllers\SubscriptionController::hasFeature('has_ai')) {
             return false;
        }

        return true;
    }
    /**
     * GET /api/ia/servicios
     * Retorna el catálogo completo de servicios con precios
     */
    public function getServicios(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $servicios = Package::where('active', 1)
            ->select('id', 'package_name as nombre', 'price as precio', 'duration as duracion', 'description as descripcion')
            ->orderBy('package_name')
            ->get();

        return response()->json([
            'success' => true,
            'servicios' => $servicios
        ]);
    }

    /**
     * GET /api/ia/horarios-atencion
     * Retorna los días y horarios de atención del negocio
     */
    public function getHorariosAtencion(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $workDays = Setting::get('work_days', json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']));
        if (is_string($workDays)) {
            $workDays = json_decode($workDays, true);
        }

        $diasEspanol = [
            'monday' => 'Lunes',
            'tuesday' => 'Martes',
            'wednesday' => 'Miércoles',
            'thursday' => 'Jueves',
            'friday' => 'Viernes',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'
        ];

        $diasLaborales = [];
        foreach ($workDays as $day) {
            $diasLaborales[] = $diasEspanol[$day] ?? $day;
        }

        $horaInicio = Setting::get('work_hours_start', '09:00');
        $horaFin = Setting::get('work_hours_end', '18:00');

        return response()->json([
            'success' => true,
            'dias_laborales' => $diasLaborales,
            'horario' => "$horaInicio - $horaFin",
            'cerrado' => 'Domingos y festivos'
        ]);
    }

    /**
     * GET /api/ia/disponibilidad
     * Consulta los horarios disponibles para agendar
     * Parámetros: fecha, servicio_id, especialista_id (opcional)
     */
    public function getDisponibilidad(Request $request)
    {
        $fecha = $request->get('fecha');
        $servicioId = $request->get('servicio_id');
        $especialistaId = $request->get('especialista_id');

        if (!$fecha) {
            return response()->json(['success' => false, 'error' => 'Fecha requerida'], 400);
        }

        // Obtener citas existentes para esa fecha
        $citasExistentes = DB::table('appointments')
            ->where('date', $fecha)
            ->where('status', '!=', 'cancelled')
            ->when($especialistaId, function($query) use ($especialistaId) {
                return $query->where('specialist_id', $especialistaId);
            })
            ->pluck('start_time')
            ->toArray();

        // Generar horarios disponibles (cada 30 min de 9am a 6pm)
        $horariosDisponibles = [];
        $horaInicio = 9;
        $horaFin = 18;

        for ($h = $horaInicio; $h < $horaFin; $h++) {
            foreach (['00', '30'] as $min) {
                $hora = sprintf('%02d:%s', $h, $min);
                if (!in_array($hora, $citasExistentes)) {
                    $horariosDisponibles[] = $hora;
                }
            }
        }

        return response()->json([
            'success' => true,
            'fecha' => $fecha,
            'horarios_disponibles' => $horariosDisponibles
        ]);
    }

    /**
     * POST /api/ia/agendar-cita
     * Crea una nueva cita
     */
    public function agendarCita(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $telefono = $request->get('telefono');
        $nombre = $request->get('nombre');
        $servicioId = $request->get('servicio_id');
        $fecha = $request->get('fecha');
        $hora = $request->get('hora');
        $especialistaId = $request->get('especialista_id');

        // Validación básica
        if (!$telefono || !$nombre || !$servicioId || !$fecha || !$hora) {
            return response()->json([
                'success' => false,
                'error' => 'Faltan datos requeridos'
            ], 400);
        }

        // Buscar o crear el customer
        $customer = DB::table('customers')->where('phone', $telefono)->first();
        
        if (!$customer) {
            $customerId = DB::table('customers')->insertGetId([
                'name' => $nombre,
                'phone' => $telefono,
                'email' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $customerId = $customer->id;
        }

        // Obtener duración del servicio
        $servicio = Package::find($servicioId);
        $duracion = $servicio ? $servicio->duration : 30;

        // Crear la cita
        $appointmentId = DB::table('appointments')->insertGetId([
            'customer_id' => $customerId,
            'package_id' => $servicioId,
            'specialist_id' => $especialistaId,
            'date' => $fecha,
            'start_time' => $hora,
            'duration' => $duracion,
            'status' => 'pendiente',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'success' => true,
            'mensaje' => 'Cita agendada exitosamente',
            'cita_id' => $appointmentId,
            'detalles' => [
                'fecha' => $fecha,
                'hora' => $hora,
                'servicio' => $servicio ? $servicio->package_name : '',
                'duracion' => $duracion . ' minutos'
            ]
        ]);
    }

    /**
     * POST /api/ia/confirmar-cita
     * Confirma, cancela o modifica una cita
     */
    public function confirmarCita(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $citaId = $request->get('cita_id');
        $accion = $request->get('accion'); // confirmar | cancelar | modificar
        $nuevaFecha = $request->get('nueva_fecha');
        $nuevaHora = $request->get('nueva_hora');

        if (!$citaId || !$accion) {
            return response()->json(['success' => false, 'message' => 'Datos incompletos'], 400);
        }

        $cita = DB::table('appointments')->where('id', $citaId)->first();
        
        if (!$cita) {
            return response()->json(['success' => false, 'message' => 'Cita no encontrada'], 404);
        }

        $updateData = ['updated_at' => date('Y-m-d H:i:s')];
        $mensaje = '';

        switch ($accion) {
            case 'confirmar':
                $updateData['status'] = 'confirmada';
                $mensaje = '¡Cita confirmada exitosamente!';
                break;
            case 'cancelar':
                $updateData['status'] = 'cancelada';
                $mensaje = 'Cita cancelada correctamente.';
                break;
            case 'modificar':
                if ($nuevaFecha) $updateData['date'] = $nuevaFecha;
                if ($nuevaHora) $updateData['start_time'] = $nuevaHora;
                $mensaje = 'Cita reprogramada exitosamente.';
                break;
        }

        DB::table('appointments')->where('id', $citaId)->update($updateData);

        return response()->json([
            'success' => true,
            'mensaje' => $mensaje,
        ]);
    }

    /**
     * GET /api/ia/cliente-citas
     * Busca las citas de un cliente por su teléfono
     */
    public function getCitasCliente(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $telefono = $request->get('telefono');
        if (!$telefono) return response()->json(['success' => false, 'message' => 'Teléfono requerido'], 400);

        $citas = DB::table('appointments')
            ->join('customers', 'appointments.customer_id', '=', 'customers.id')
            ->join('packages', 'appointments.package_id', '=', 'packages.id')
            ->where('customers.phone', 'like', "%$telefono%")
            ->where('appointments.status', '!=', 'cancelada')
            ->select('appointments.id', 'appointments.date as fecha', 'appointments.start_time as hora', 'packages.package_name as servicio', 'appointments.status as estado')
            ->orderBy('appointments.date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'citas' => $citas
        ]);
    }

    /**
     * GET /api/ia/info-negocio
     * Retorna información general del negocio y redes sociales
     */
    public function getInfoNegocio(Request $request)
    {
        if (!$this->validateRequest($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $nombre = Setting::get('business_name', 'Lina Lucio Spa de Cejas');
        $telefono = Setting::get('business_phone', '');
        $direccion = Setting::get('business_address', '');
        $instagram = Setting::get('social_instagram', '');
        $facebook = Setting::get('social_facebook', '');
        $whatsapp = Setting::get('social_whatsapp', '');

        return response()->json([
            'success' => true,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'direccion' => $direccion,
            'redes' => [
                'instagram' => $instagram,
                'facebook' => $facebook,
                'whatsapp' => $whatsapp
            ]
        ]);
    }

    /**
     * Pausar/Reanudar conversación (Intervención Humana)
     */
    public function togglePause(Request $request)
    {
        $conversationId = $request->input('conversation_id');
        $status = $request->input('status'); // 'paused' or 'active'
        
        // Aquí deberíamos actualizar la BBDD real
        // DB::table('ai_conversation_logs')->where('id', $conversationId)->update(['status' => $status]);
        
        return response()->json(['success' => true, 'new_status' => $status]);
    }

    /**
     * Enviar mensaje manual desde el Dashboard
     */
    public function sendMessage(Request $request)
    {
        $phone = $request->input('phone'); // En formato E.164 o PSID (Page Scoped ID) para Social
        $message = $request->input('message');
        $channel = $request->input('channel', 'whatsapp'); // whatsapp, instagram, messenger
        
        if(!$phone || !$message) return response()->json(['error' => 'Faltan datos'], 400);

        if ($channel === 'whatsapp') {
            // Usar WhatsAppService para enviar
            $whatsapp = new \App\Services\WhatsAppService();
            $result = $whatsapp->enviarMensajeTexto($phone, $message);
            return response()->json($result);
        }  
        elseif ($channel === 'instagram' || $channel === 'messenger') {
            // TODO: Integrar Meta Graph API para enviar mensaje a PSID
            // Por ahora simulamos éxito
            \Log::info("Enviando mensaje a $channel ID $phone: $message");
            return response()->json(['success' => true, 'channel' => $channel]);
        }

        return response()->json(['error' => 'Canal no soportado'], 400);
    }
    
    /**
     * Endpoint para que el Python Agent consulte si el humano está controlando
     */
    public function checkStatus(Request $request) 
    {
        $phone = $request->input('phone');
        // $log = DB::table('ai_conversation_logs')->where('phone', $phone)->first();
        // $isPaused = $log && $log->status === 'paused';
        
        $isPaused = false; // Simulado por ahora
        
        return response()->json(['paused' => $isPaused]);
    }

    /**
     * POST /api/ia/webhook
     * Recibe mensajes de WhatsApp y detecta intervención humana
     */
    public function webhook(Request $request)
    {
        $data = $request->all();
        
        // Log para debugging
        \Log::info('Webhook WhatsApp recibido:', $data);

        // Verificación de webhook (Meta requiere esto)
        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token')) {
            $verifyToken = Setting::get('whatsapp_verify_token', '');
            if ($request->get('hub_verify_token') === $verifyToken) {
                return response($request->get('hub_challenge'), 200);
            }
            return response('Forbidden', 403);
        }

        // Procesar mensajes entrantes (WhatsApp)
        if (isset($data['entry'][0]['changes'][0]['value']['messages'])) {
            $message = $data['entry'][0]['changes'][0]['value']['messages'][0];
            $from = $message['from'];
            $messageType = $message['type'];
            $messageBody = isset($message['text']) ? $message['text']['body'] : '';
            $channel = 'whatsapp';

            \Log::info("Mensaje de $from ($channel): $messageBody");
            // TODO: Enviar a IA Brain
        }
        
        // Procesar mensajes entrantes (Instagram / Messenger)
        // Meta envía estos eventos bajo 'messaging' en el entry directo para páginas
        if (isset($data['entry'][0]['messaging'][0])) {
            $messaging = $data['entry'][0]['messaging'][0];
            $from = $messaging['sender']['id'];
            $messageBody = isset($messaging['message']['text']) ? $messaging['message']['text'] : '';
            
            // Detectar si es Instagram o Messenger basado en el objeto o configuración
            // Generalmente se distinguen por el 'recipient' id o el contexto
            // Por simplicidad, asumimos un canal genérico 'meta_social' o tratamos de inferir
            $channel = 'social_dm'; 

            \Log::info("Mensaje de $from ($channel): $messageBody");
             // TODO: Enviar a IA Brain
        }

        return response()->json(['success' => true]);
    }

    /**
     * DATA FOR AGENTE CONTADOR (FINANZAS)
     */
    public function getStatsContador(Request $request)
    {
        if (!$this->validateRequest($request)) return response()->json(['error' => 'Unauthorized'], 401);

        $month = date('m');
        $year = date('Y');

        $income = \App\Models\Sale::whereMonth('created_at', '=', $month)->whereYear('created_at', '=', $year)->sum('total');
        $expenses = \App\Models\CashMovement::whereIn('type', ['expense', 'egreso'])->whereMonth('movement_date', '=', $month)->sum('amount');
        
        return response()->json([
            'success' => true,
            'periodo' => date('F Y'),
            'ingresos' => $income,
            'egresos' => $expenses,
            'utilidad' => $income - $expenses,
            'mensaje_ia' => ($income > $expenses) ? "El negocio es rentable este mes." : "Alerta de flujo de caja negativo."
        ]);
    }

    /**
     * DATA FOR AGENTE NOMINA (TALENTO HUMANO)
     */
    public function getStatsNomina(Request $request)
    {
        if (!$this->validateRequest($request)) return response()->json(['error' => 'Unauthorized'], 401);

        $totalEspecialistas = Specialist::count();
        $comisionesMes = \App\Models\SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereMonth('sales.sale_date', '=', date('m'))
            ->sum('commission_value');

        return response()->json([
            'success' => true,
            'especialistas_activos' => $totalEspecialistas,
            'comisiones_generadas' => $comisionesMes,
            'resumen' => "Hay $totalEspecialistas colaboradores generando comisiones hoy."
        ]);
    }

    /**
     * DATA FOR AGENTE ESTRATEGA (GROWTH)
     */
    public function getStatsEstratega(Request $request)
    {
        if (!$this->validateRequest($request)) return response()->json(['error' => 'Unauthorized'], 401);

        $avgTicket = \App\Models\Sale::avg('total') ?: 0;
        $totalClientes = \App\Models\Customer::count();

        return response()->json([
            'success' => true,
            'ticket_promedio' => round($avgTicket, 2),
            'base_clientes' => $totalClientes,
            'oportunidad' => "Aumentar el ticket promedio un 10% generaría mayores utilidades."
        ]);
    }

    /**
     * DATA FOR AGENTE DE INVENTARIO (AUDITOR)
     */
    public function getStatsInventario(Request $request)
    {
        if (!$this->validateRequest($request)) return response()->json(['error' => 'Unauthorized'], 401);

        $lowStockCount = \App\Models\Product::whereRaw('quantity <= min_quantity')->count();
        $valorInventario = \App\Models\Product::sum(\DB::raw('quantity * cost'));

        return response()->json([
            'success' => true,
            'productos_alerta_stock' => $lowStockCount,
            'valor_total_inventario' => $valorInventario,
            'mensaje_ia' => ($lowStockCount > 0) ? "Tienes $lowStockCount productos con existencias críticas." : "El inventario está saludable."
        ]);
    }

    /**
     * DATA FOR AGENTE DE RETENCION (FIDELIZACION)
     */
    public function getStatsFidelizacion(Request $request)
    {
        if (!$this->validateRequest($request)) return response()->json(['error' => 'Unauthorized'], 401);

        // Clientes que no han vuelto en 30 días
        $oneMonthAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $clientesPerdidos = \App\Models\Customer::whereDoesntHave('appointments', function($q) use ($oneMonthAgo) {
            $q->where('date', '>', $oneMonthAgo);
        })->count();

        return response()->json([
            'success' => true,
            'clientes_por_recuperar' => $clientesPerdidos,
            'estrategia' => "Lanzar una campaña de reactivación para estos $clientesPerdidos clientes aumentaría la recurrencia."
        ]);
    }
}
