<?php
/* Update an existing product */
$products = loadProducts();

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    header('Location: product-manager.php?err=missing_id');
    exit;
}

foreach ($products as &$p) {
    if ($p['id'] === $id) {
        $p['title']       = $_POST['title']       ?? $p['title'];
        $p['price']       = isset($_POST['price']) ? (float)$_POST['price'] : $p['price'];
        $p['image']       = $_POST['image']       ?? $p['image'];
        $p['description'] = $_POST['description'] ?? $p['description'];
        saveProducts($products);
        break;
    }
}

header('Location: product-manager.php');
exit;
