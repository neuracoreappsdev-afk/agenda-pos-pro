<?php
echo "<h1>Laravel Application</h1>";
echo "<p>Server.php is working!</p>";
echo "<p>URI: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Working Dir: " . __DIR__ . "</p>";
echo "<p>Files in public: </p><pre>";
print_r(scandir(__DIR__ . '/public'));
echo "</pre>";
