<?php
require_once __DIR__ . '/config.php';

function loadProducts(): array
{
    return json_decode(@file_get_contents(PRODUCTS_FILE), true) ?: [];
}

function saveProducts(array $products): void
{
    backupProducts();
    file_put_contents(PRODUCTS_FILE, json_encode($products, JSON_PRETTY_PRINT));
}

function backupProducts(): void
{
    for ($i = MAX_BACKUPS; $i >= 2; $i--) {
        $prev = PRODUCTS_FILE . '.bak' . str_pad($i - 1, 2, '0', STR_PAD_LEFT);
        $next = PRODUCTS_FILE . '.bak' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if (file_exists($prev)) rename($prev, $next);
    }
    if (file_exists(PRODUCTS_FILE)) {
        copy(PRODUCTS_FILE, PRODUCTS_FILE . '.bak01');
    }
}

function generateId(): int
{
    return intval(microtime(true) * 1000); // unique‑enough
}
