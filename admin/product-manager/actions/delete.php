<?php
/* Delete a single product */
$products  = loadProducts();
$deleteId  = (int)($_POST['delete_id'] ?? 0);

if ($deleteId) {
    $products = array_values(array_filter(
        $products,
        fn($p) => $p['id'] !== $deleteId
    ));
    saveProducts($products);
}

header('Location: product-manager.php');
exit;
