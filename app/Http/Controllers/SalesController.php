<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\CashRegisterSession;
use Illuminate\Http\Request;
use Auth;
use DB;
use Mail;
use App\User; // Use App\User for older Laravel

class SalesController extends Controller {

    /**
     * Store a new sale from POS
     */
    public function store(Request $request)
    {
        if (!session('admin_session')) {
            return response()->json(['success' => false, 'message' => 'Sesión expirada'], 401);
        }

        // Transformar Request al formato de Payload estandarizado del Dominio
        $payload = [
            'cliente_id' => ($request->customer_id && $request->customer_id !== "") ? $request->customer_id : null,
            'origen' => $request->origen ?? 'pos',
            'appointment_id' => $request->appointment_id,
            'items' => [],
            'pagos' => [],
            'impuestos' => $request->tax_total ?? 0,
            'descuento_global' => $request->discount ?? 0,
            'usuario_id' => session('admin_id') ?: 2,
            'notes' => $request->notes
        ];

        // Mapear items del carrito
        foreach($request->cart as $item) {
            $payload['items'][] = [
                'tipo' => (isset($item['type']) && $item['type'] == 'product') ? 'producto' : 'servicio',
                'item_id' => $item['id'],
                'cantidad' => $item['qty'] ?? 1,
                'precio_unitario' => $item['price'],
                'specialist_id' => (isset($item['specialistId']) && $item['specialistId'] !== "") ? $item['specialistId'] : null,
                'descuento' => $item['discount'] ?? 0,
                'nombre' => $item['name'] ?? 'Item'
            ];
        }

        // Mapear pagos (si no vienen detallados, crear uno por defecto con el total)
        if ($request->has('payments')) {
             foreach($request->payments as $p) {
                 $payload['pagos'][] = [
                     'metodo_pago_id' => $p['id'],
                     'monto' => $p['amount'],
                     'propina' => $p['tip'] ?? 0
                 ];
             }
        } else {
             // Fallback para POS simple
             $payload['pagos'][] = [
                 'metodo_pago_id' => 1, // Asumimos 1 como Efectivo por defecto si no viene
                 'monto' => $request->total,
                 'propina' => 0
             ];
        }

        return $this->procesarVenta($payload);
    }

    /**
     * motor central y único para registrar ventas en el sistema.
     * Única fuente de verdad para Informes, Caja e Inventario.
     */
    public function procesarVenta($payload)
    {
        // 1️⃣ Validaciones iniciales
        $adminId = $payload['usuario_id'];
        $session = \App\Models\CashRegisterSession::where('status', 'open')
                                                ->orderBy('id', 'desc')
                                                ->first();
        
        if (!$session) {
            return response()->json(['success' => false, 'message' => 'No hay caja abierta para procesar movimientos'], 403);
        }

        // Validar Stock y Especialistas
        foreach ($payload['items'] as $item) {
            if ($item['tipo'] == 'producto') {
                $product = \App\Models\Product::find($item['item_id']);
                if (!$product || $product->quantity < $item['cantidad']) {
                    return response()->json(['success' => false, 'message' => 'Stock insuficiente: ' . ($product ? $product->name : 'ID ' . $item['item_id'])], 422);
                }
            }
            if (!isset($item['specialist_id']) || empty($item['specialist_id'])) {
                return response()->json(['success' => false, 'message' => 'Cada artículo debe tener un especialista asignado para el cálculo de comisiones'], 422);
            }
        }

        // Validar integridad financiera
        $sumaItems = 0;
        foreach ($payload['items'] as $item) {
            $sumaItems += ($item['precio_unitario'] * $item['cantidad']) - $item['descuento'];
        }
        $totalVenta = $sumaItems + $payload['impuestos'] - $payload['descuento_global'];
        
        $sumaPagos = 0;
        foreach ($payload['pagos'] as $p) {
            $sumaPagos += $p['monto'];
        }

        if (abs($totalVenta - $sumaPagos) > 0.01) {
            return response()->json(['success' => false, 'message' => 'Descuadre: Total Venta $' . $totalVenta . ' vs Total Pagos $' . $sumaPagos], 422);
        }

        try {
            DB::beginTransaction();

            // 2️⃣ Crear registro en sales (CABECERA)
            $sale = \App\Models\Sale::create([
                'total' => $totalVenta,
                'subtotal' => $sumaItems,
                'discount' => $payload['descuento_global'],
                'payment_method' => count($payload['pagos']) > 1 ? 'Múltiple' : (isset($payload['pagos'][0]) ? 'ID:'.$payload['pagos'][0]['metodo_pago_id'] : 'Desconocido'),
                'customer_id' => $payload['cliente_id'],
                'customer_name' => $payload['customer_name'] ?? null,
                'user_id' => $adminId,
                'cash_register_session_id' => $session->id,
                'notes' => $payload['notes'] ?? '',
                'items_json' => json_encode($payload['items']),
                'sale_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // 3️⃣ Desglose atómico en sale_items (Single Source of Truth)
            foreach ($payload['items'] as $item) {
                // Cálculo de comisión "congelada" al momento de facturar
                $commissionValue = 0;
                $specialist = \App\Models\Specialist::find($item['specialist_id']);
                
                if ($specialist) {
                    $comms = $specialist->commissions; // Array cast
                    $percentage = 0;
                    if ($item['tipo'] == 'producto') {
                        $percentage = isset($comms['products_percentage']) ? $comms['products_percentage'] : 0;
                    } else {
                        $percentage = isset($comms['services_percentage']) ? $comms['services_percentage'] : 0;
                    }
                    $baseComisionable = ($item['precio_unitario'] * $item['cantidad']) - $item['descuento'];
                    $commissionValue = ($baseComisionable * $percentage) / 100;
                }

                \App\Models\SaleItem::create([
                    'sale_id' => $sale->id,
                    'item_type' => $item['tipo'],
                    'item_id' => $item['item_id'],
                    'item_name' => $item['nombre'] ?? 'Item',
                    'specialist_id' => $item['specialist_id'],
                    'quantity' => $item['cantidad'],
                    'unit_price' => $item['precio_unitario'],
                    'tax_amount' => 0, // Enriquecer con lógica fiscal si aplica
                    'total' => ($item['precio_unitario'] * $item['cantidad']) - $item['descuento'],
                    'commission_value' => $commissionValue,
                    'discount_value' => $item['descuento'],
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // 4️⃣ Inventario (Kardex Log)
                if ($item['tipo'] == 'producto') {
                    $prod = \App\Models\Product::find($item['item_id']);
                    $prod->decrement('quantity', $item['cantidad']);

                    \App\Models\InventoryLog::create([
                        'product_id' => $item['item_id'],
                        'quantity' => $item['cantidad'],
                        'type' => 'out',
                        'reason' => 'VENTA_FACTURA_' . $sale->id
                    ]);
                }
            }

            // 5️⃣ Registro de pagos y caja
            foreach ($payload['pagos'] as $p) {
                \App\Models\Payment::create([
                    'sale_id' => $sale->id,
                    'payment_method_id' => $p['metodo_pago_id'],
                    'amount' => $p['monto'],
                    'tip' => $p['propina']
                ]);

                // Movimiento de Caja (Ingreso)
                \App\Models\CashMovement::create([
                    'cash_register_session_id' => $session->id,
                    'sale_id' => $sale->id,
                    'type' => 'income',
                    'amount' => $p['monto'],
                    'concept' => 'VENTA FACTURA #' . $sale->id,
                    'movement_date' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                // Si hay propina, es un movimiento de flujo diferente o informativo
                if ($p['propina'] > 0) {
                    \App\Models\CashMovement::create([
                        'cash_register_session_id' => $session->id,
                        'sale_id' => $sale->id,
                        'type' => 'tip',
                        'amount' => $p['propina'],
                        'concept' => 'PROPINA - FACTURA #' . $sale->id,
                        'movement_date' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            // 6️⃣ Cierre de agenda
            if (isset($payload['appointment_id']) && !empty($payload['appointment_id'])) {
                $appointment = \App\Models\Appointment::find($payload['appointment_id']);
                if ($appointment) {
                    $appointment->update([
                        'status' => 'paid',
                        'sale_id' => $sale->id
                    ]);
                }
            }

            // 7️⃣ Cierre de transacción
            DB::commit();

            // 8️⃣ Post-procesos (WhatsApp / Email)
            // Se puede disparar un Job asíncrono aquí
            
            return response()->json([
                'success' => true, 
                'sale_id' => $sale->id, 
                'receipt_url' => url('admin/factura/print/' . $sale->id),
                'message' => 'Venta procesada y atomizada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error en motor de ventas: ' . $e->getMessage()], 500);
        }
    }
}
