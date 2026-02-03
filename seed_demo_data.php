<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(Illuminate\Http\Request::capture());

// 1. Ensure Reference Sources
$sources = \App\Models\ReferenceSource::lists('id')->all();
if(empty($sources)) {
    $sources = [];
    $list = ['Instagram', 'Google', 'Facebook', 'Recomendación'];
    foreach($list as $n) {
        $s = \App\Models\ReferenceSource::create(['name' => $n, 'active' => 1]);
        $sources[] = $s->id;
    }
}

// 2. Create 20 random customers
$faker = Faker\Factory::create();
echo "Creating customers...\n";
$customerIds = [];
for($i=0; $i<20; $i++) {
    $c = \App\Models\Customer::create([
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'contact_number' => $faker->phoneNumber,
        'reference_source_id' => $sources[array_rand($sources)]
    ]);
    $customerIds[] = $c->id;
}

// 3. Create Sales
$specialists = \App\Models\Specialist::all();
if($specialists->isEmpty()) {
    $specialist = \App\Models\Specialist::create(['name' => 'Lina', 'active' => 1]);
    $specialists = collect([$specialist]);
}
$package = \App\Models\Package::first();

echo "Creating sales...\n";
foreach($customerIds as $cid) {
    $numSales = rand(1, 4);
    for($j=0; $j<$numSales; $j++) {
        $amount = rand(30, 150) * 1000;
        $spec = $specialists->random();
        
        $sale = \App\Models\Sale::create([
            'customer_id' => $cid,
            'user_id' => 1,
            'sale_date' => date('Y-m-d H:i:s', strtotime('-'.rand(1, 30).' days')),
            'total' => $amount,
            'subtotal' => $amount,
            'payment_method' => 'Efectivo',
            'cash_register_session_id' => 1
        ]);
        
        // Create Item
        \App\Models\SaleItem::create([
            'sale_id' => $sale->id,
            'item_type' => 'servicio',
            'item_id' => $package ? $package->id : 1,
            'item_name' => $package ? $package->package_name : 'Servicio General',
            'quantity' => 1,
            'unit_price' => $amount,
            'total' => $amount,
            'specialist_id' => $spec->id,
            'commission_value' => $amount * 0.10
        ]);
        
        // Create Cash Movement (optional but good for consistency)
        \App\Models\CashMovement::create([
            'cash_register_session_id' => 1,
            'sale_id' => $sale->id,
            'type' => 'income',
            'amount' => $amount,
            'concept' => 'Venta #' . $sale->id,
            'movement_date' => $sale->sale_date
        ]);
    }
}

echo "DONE. Created " . count($customerIds) . " customers and related sales.\n";
