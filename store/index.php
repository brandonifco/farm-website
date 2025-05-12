<?php include '../includes/header.php';
require_once __DIR__ . '/../includes/responsive-image.php'; ?>

<section class="store-intro">
    <h1>Farm Store</h1>
    <p>Browse our selection of all-natural, handmade farm goods — direct from our land to your hands.</p>
</section>

<section class="product-grid">
    <?php
    $products = json_decode(file_get_contents('products.json'), true);

    foreach ($products as $product) {
        echo '<article class="product-card">';
        echo responsiveImage($product['image'], $product['title']);
        echo '<h2>' . htmlspecialchars($product['title']) . '</h2>';
        echo '<p>$' . number_format($product['price'], 2) . '</p>';

        // action buttons
        echo '<div class="card-actions" style="display:flex;gap:.5rem;">';
        echo '<a href="product.php?id=' . urlencode($product['id']) . '" class="button">View</a>';

        // new quick‑add button: data-id carries the product id
        echo '<button type="button" class="button secondary quick-add" data-id="' . $product['id'] . '">Add to Cart</button>';
        echo '</div>';

        echo '</article>';
    }

    ?>
</section>
<script src="/js/store.js" defer></script>

<?php include '../includes/footer.php'; ?>