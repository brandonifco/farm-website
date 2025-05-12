<?php include '../includes/header.php';
require_once __DIR__ . '/../includes/responsive-image.php'; ?>

<?php
// Get the product ID from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Load products
$products = json_decode(file_get_contents('products.json'), true);

// Search for the matching product
$product = null;
foreach ($products as $item) {
    if ($item['id'] === $id) {
        $product = $item;
        break;
    }
}
?>

<?php if ($product): ?>
    <section class="product-detail">
        <h1><?php echo htmlspecialchars($product['title']); ?></h1>
        <?php echo responsiveImage($product['image'], $product['title']); ?>
        <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <form class="add-to-cart-form" method="post" action="#">
            <input type="hidden" name="add_id" value="<?php echo $product['id']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="1" min="1" required>
            <button type="submit" class="button" id="add-btn">Add to Cart</button>
        </form>
    </section>
<?php else: ?>
    <section>
        <h1>Product Not Found</h1>
        <p>We couldn’t find the product you’re looking for.</p>
    </section>
<?php endif; ?>

<script src="/js/store.js" defer></script>

<?php include '../includes/footer.php'; ?>