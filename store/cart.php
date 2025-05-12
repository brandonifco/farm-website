<?php
session_start();
include '../includes/header.php';

// Load products
$products = json_decode(file_get_contents('products.json'), true);
/**
 * Convert a full‑size …_2048.jpg (or .webp / .avif) to …_thumb.jpeg|webp|avif
 * ‑ If we get .jpg in, we output .jpeg because that’s what’s on disk
 * ‑ If we already have _thumb, we return unchanged
 */
function thumbName(string $filename): string
{
    if (strpos($filename, '_thumb.') !== false) {
        return $filename;                 // already a thumb
    }

    $dotPos = strrpos($filename, '.');
    if ($dotPos === false) return $filename;

    $ext  = substr($filename, $dotPos);   // .jpg / .jpeg / .webp / .avif
    $base = substr($filename, 0, $dotPos);

    // swap trailing "_####" (or "_1234x1234") with "_thumb"
    $thumbBase = preg_replace('/_[0-9]+(?:x[0-9]+)?$/', '_thumb', $base);
    if ($thumbBase === $base) $thumbBase .= '_thumb';

    // ---- convert .jpg to .jpeg so the path matches your files ----
    if ($ext === '.jpg') $ext = '.jpeg';

    return $thumbBase . $ext;
}


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
                <th></th>
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
                            $thumb = thumbName($product['image']);
                            echo '<tr>';
                            // thumbnail cell
                            echo '<td><img src="' . htmlspecialchars($thumb) . '" width="80" height="auto" alt="' . htmlspecialchars($product['title']) . ' thumbnail" class="cart-thumb"></td>';
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
        <!-- Empty Cart button -->
        <form method="post" action="empty-cart.php" id="empty-cart-form" style="display:inline;">
            <button type="submit" class="button danger" id="empty-cart-btn">Empty Cart</button>
        </form><br>
        

    <a href="checkout.php" class="button">Proceed to Checkout</a>

</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('empty-cart-btn');
    if (!btn) return;

    btn.addEventListener('click', function (e) {
        if (!confirm('Are you sure you want to empty your cart?')) {
            e.preventDefault();   // stop submission if they hit “Cancel”
        }
    });
});
</script>


<?php include '../includes/footer.php'; ?>