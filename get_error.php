<?php
$log = file_get_contents('storage/logs/laravel.log');
$pos = strrpos($log, 'local.ERROR');
if ($pos !== false) {
    file_put_contents('last_error_clean.txt', substr($log, $pos));
}
