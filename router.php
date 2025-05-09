<?php
// Route static files through directly
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;
    if (is_file($fullPath)) {
        return false;
    }
}

// Serve index.php normally if file exists
if (file_exists(__DIR__ . $_SERVER["REQUEST_URI"])) {
    include __DIR__ . $_SERVER["REQUEST_URI"];
    exit;
}

// Otherwise, serve 404 page
http_response_code(404);
include '404.php';
