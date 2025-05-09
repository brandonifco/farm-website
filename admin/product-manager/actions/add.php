<?php
require_once __DIR__ . '/../inc/products.php';

$new = [
    'id'          => generateId(),
    'title'       => $_POST['title'],
    'price'       => (float)$_POST['price'],
    'image'       => $_POST['image'],
    'description' => $_POST['description'],
];
$products = loadProducts();
$products[] = $new;
saveProducts($products);
header('Location: product-manager.php');
exit;
