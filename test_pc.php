<?php
require 'bootstrap/autoload.php';
$app = require 'bootstrap/app.php';

// Boot the app
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

// Create a fake request simulating logged in user
$request = Illuminate\Http\Request::create('/admin/panel-control', 'GET');

// Add session data
$request->setLaravelSession($app['session.store']);
$app['session.store']->put('admin_session', true);
$app['session.store']->put('admin_id', 2);

try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() == 302) {
        echo "Redirect to: " . $response->headers->get('Location') . "\n";
    } elseif ($response->getStatusCode() == 500) {
        echo "Error content:\n";
        echo substr($response->getContent(), 0, 2000);
    } else {
        echo "Success! Content length: " . strlen($response->getContent()) . " bytes\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
