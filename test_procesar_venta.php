<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\SalesController;

// 1. Asegurar sesión de caja
$session = \App\Models\CashRegisterSession::where('status', 'open')->first();
if (!$session) {
    echo "Abriendo caja de prueba...\n";
    $session = \App\Models\CashRegisterSession::create([
        'user_id' => 1,
        'status' => 'open',
        'opening_amount' => 100000,
        'opened_at' => date('Y-m-d H:i:s')
    ]);
}

// 2. Asegurar Especialista y Producto
$specialist = \App\Models\Specialist::first();
if (!$specialist) {
    echo "Creando especialista de prueba...\n";
    $specialist = \App\Models\Specialist::create([
        'name' => 'Luis',
        'commissions' => ['services_percentage' => 40, 'products_percentage' => 10]
    ]);
}

$product = \App\Models\Product::first();
if (!$product) {
    echo "Creando producto de prueba...\n";
    $product = \App\Models\Product::create([
        'name' => 'Laca Pro',
        'quantity' => 10,
        'price' => 20000
    ]);
} else {
    $product->update(['quantity' => 10]); // Asegurar stock
}

// 3. Simular Payload
$payload = [
  "cliente_id" => null,
  "origen" => "pos",
  "appointment_id" => null,
  "items" => [
    [
      "tipo" => "servicio",
      "item_id" => 1,
      "cantidad" => 1,
      "precio_unitario" => 50000,
      "specialist_id" => $specialist->id,
      "descuento" => 0,
      "nombre" => "Corte Estilo"
    ],
    [
      "tipo" => "producto",
      "item_id" => $product->id,
      "cantidad" => 1,
      "precio_unitario" => 20000,
      "specialist_id" => $specialist->id,
      "descuento" => 0,
      "nombre" => "Laca Pro"
    ]
  ],
  "pagos" => [
    [
      "metodo_pago_id" => 1,
      "monto" => 70000,
      "propina" => 5000
    ]
  ],
  "impuestos" => 0,
  "descuento_global" => 0,
  "usuario_id" => 2
];

// 4. Ejecutar Motor
$controller = new SalesController();
$response = $controller->procesarVenta($payload);

$content = json_decode($response->getContent(), true);
file_put_contents('error_pos.txt', $response->getContent());
echo "RESPONSE SUCCESS: " . ($content['success'] ? 'YES' : 'NO') . "\n";
echo "MESSAGE: " . ($content['message'] ?? 'N/A') . "\n";

// 5. Verificar Resultados
echo "\n--- VERIFICACIÓN ---\n";
$sale = \App\Models\Sale::latest()->first();
echo "Venta ID: " . $sale->id . " | Total: " . $sale->total . "\n";

$items = \App\Models\SaleItem::where('sale_id', $sale->id)->get();
foreach($items as $it) {
    echo " Item: " . $it->item_name . " | Comision: " . $it->commission_value . "\n";
}

$payments = \App\Models\Payment::where('sale_id', $sale->id)->get();
foreach($payments as $p) {
    echo " Pago: $" . $p->amount . " | Propina: $" . $p->tip . "\n";
}

$movements = \App\Models\CashMovement::where('sale_id', $sale->id)->get();
foreach($movements as $m) {
    echo " Movimiento: " . $m->type . " | Motivo: " . $m->concept . " | Valor: $" . $m->amount . "\n";
}

$prodCheck = \App\Models\Product::find($product->id);
echo "Nuevo Stock Producto: " . $prodCheck->quantity . " (Antes: 10)\n";
