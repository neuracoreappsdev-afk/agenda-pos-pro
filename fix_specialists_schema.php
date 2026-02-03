<?php
require __DIR__ . '/bootstrap/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "Updating 'specialists' table schema...\n";

$columns = [
    'last_name' => 'string',
    'id_type' => 'string',
    'identification' => 'string',
    'dv' => 'string',
    'country_code' => 'string',
    'phone' => 'string',
    'display_name' => 'string',
    'code' => 'string',
    'pin_reset_required' => 'boolean',
    'services_json' => 'text',
    'schedule' => 'text',
    'working_hours' => 'text',
    'time_blocks' => 'text',
    'schedule_exceptions' => 'text',
    'mobile_user' => 'boolean',
    'location_id' => 'integer',
    'commissions' => 'text'
];

try {
    Schema::table('specialists', function ($table) use ($columns) {
        foreach ($columns as $col => $type) {
            if (!Schema::hasColumn('specialists', $col)) {
                if ($type == 'string') $table->string($col)->nullable();
                elseif ($type == 'boolean') $table->boolean($col)->default(false);
                elseif ($type == 'text') $table->text($col)->nullable();
                elseif ($type == 'integer') $table->integer($col)->unsigned()->nullable();
                echo "- Added column '$col'\n";
            }
        }
    });
    echo "Schema update completed.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
