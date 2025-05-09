<?php include '../includes/header.php'; ?>

<section class="store-intro">
    <h1>Farm Store</h1>
    <p>Browse our selection of all-natural, handmade farm goods — direct from our land to your hands.</p>
</section>

<section class="product-grid">
<?php
$products = json_decode(file_get_contents('products.json'), true);

foreach ($products as $product) {
    echo '<article class="product-card">';
    echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['title']) . '">';
    echo '<h2>' . htmlspecialchars($product['title']) . '</h2>';
    echo '<p>$' . number_format($product['price'], 2) . '</p>';
    echo '<a href="product.php?id=' . urlencode($product['id']) . '" class="button">View</a>';
    echo '</article>';
}
?>
</section>

<?php include '../includes/footer.php'; ?>
