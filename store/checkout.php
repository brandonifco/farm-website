<?php
session_start();
include '../includes/header.php';

// Load products
$products = json_decode(file_get_contents('products.json'), true);

// Get cart items from session
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

<section class="checkout">
    <h1>Checkout</h1>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="index.php">Go back to the store</a>.</p>
    <?php else: ?>
        <h2>Order Summary</h2>
        <table>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                    <td>$<?php echo number_format($item['line_total'], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <p><strong>Total:</strong> $<?php echo number_format($total, 2); ?></p>


        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <h2>Thank You!</h2>
            <p>Your order has been received. We'll follow up shortly at <strong><?php echo htmlspecialchars($_POST['email']); ?></strong>.</p>

            <?php
            $order = [
                'timestamp' => date('Y-m-d H:i:s'),
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
                'items' => $cartItems,
                'total' => $total
            ];

            $orderFile = __DIR__ . '/orders.json';
            $allOrders = [];

            if (file_exists($orderFile)) {
                $allOrders = json_decode(file_get_contents($orderFile), true) ?? [];
            }

            $allOrders[] = $order;
            file_put_contents($orderFile, json_encode($allOrders, JSON_PRETTY_PRINT));

            $_SESSION['cart'] = [];
            ?>

        <?php else: ?>
            <h2>Your Info</h2>
            <form method="post" action="checkout.php">
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required><br><br>

                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="address">Address:</label><br>
                <textarea id="address" name="address" required></textarea><br><br>

                <button type="submit" class="button">Place Order</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>