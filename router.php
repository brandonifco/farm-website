<?php
// Route static files directly
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $fullPath = __DIR__ . $path;
    if (is_file($fullPath)) {
        return false;
    }
}

// Normalize path
$requested = ltrim($_SERVER["REQUEST_URI"], '/');

// If the path is empty (root), serve index.php
if ($requested === '' || $requested === '/') {
    require __DIR__ . '/index.php';
    exit;
}

// If the requested PHP file exists, include it
$fullPath = __DIR__ . '/' . $requested;
if (file_exists($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
    require $fullPath;
    exit;
}

// Otherwise, serve 404 page
http_response_code(404);
require __DIR__ . '/404.php';
