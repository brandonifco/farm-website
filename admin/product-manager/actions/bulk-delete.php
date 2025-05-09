<?php
/* Delete multiple selected products */
$ids = array_map('intval', $_POST['selected'] ?? []);

if ($ids) {
    $products = loadProducts();
    $products = array_values(array_filter(
        $products,
        fn($p) => !in_array($p['id'], $ids, true)
    ));
    saveProducts($products);
}

header('Location: product-manager.php');
exit;
