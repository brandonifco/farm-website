<?php
session_start();
include '../includes/header.php';

// Load products
$products = json_decode(file_get_contents('products.json'), true);

// Handle Add-to-Cart POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_id'])) {
    $addId = (int) $_POST['add_id'];
    $qty = max(1, (int) $_POST['quantity']);
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    $_SESSION['cart'][$addId] = ($_SESSION['cart'][$addId] ?? 0) + $qty;

    header('Location: cart.php');
    exit;
}
// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = (int) $id;
        $qty = (int) $qty;
        if ($qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } else {
            unset($_SESSION['cart'][$id]); // Remove if set to 0
        }
    }

    header('Location: cart.php');
    exit;
}

// Prepare cart products
$cartItems = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        foreach ($products as $product) {
            if ($product['id'] === (int)$id) {
                $lineTotal = $qty * $product['price'];
                $product['quantity'] = $qty;
                $product['line_total'] = $lineTotal;

                $cartItems[] = $product;
                $total += $lineTotal;
                break;
            }
        }
    }
}

?>

<section>
    <h1>Your Cart</h1>
    <form method="post" action="cart.php">
    <table>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        <?php
        $cartItems = [];
        $total = 0;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $qty) {
                foreach ($products as $product) {
                    if ($product['id'] === (int)$id) {
                        $lineTotal = $qty * $product['price'];
                        $total += $lineTotal;

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($product['title']) . '</td>';
                        echo '<td><input type="number" name="quantities[' . $id . ']" value="' . $qty . '" min="0"></td>';
                        echo '<td>$' . number_format($product['price'], 2) . '</td>';
                        echo '<td>$' . number_format($lineTotal, 2) . '</td>';
                        echo '</tr>';
                    }
                }
            }
        }
        ?>
    </table>

    <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>
    <button type="submit" name="update_cart" class="button">Update Cart</button>
</form>

<a href="checkout.php" class="button">Proceed to Checkout</a>

</section>

<?php include '../includes/footer.php'; ?>
