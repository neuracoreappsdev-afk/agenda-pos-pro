<?php
try {
    $table = 'cash_register_sessions';
    $results = DB::select("DESCRIBE $table");
    echo "Table: $table\n";
    foreach ($results as $column) {
        echo "- " . $column->Field . " (" . $column->Type . ")\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
