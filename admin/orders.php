<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$orderFile = '../store/orders.json';
$orders = [];

if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true) ?? [];
}

include '../includes/header.php';
?>

<section>
<p><a href="export-orders.php" class="button">Download Orders as CSV</a></p>
<p><a href="dashboard.php" class="button">← Back to Dashboard</a></p>

    <h1>Customer Orders</h1>

    <?php if (empty($orders)): ?>
        <p>No orders yet.</p>
    <?php else: ?>
        <?php foreach (array_reverse($orders) as $order): ?>
            <div style="border: 1px solid #ccc; padding: 1rem; margin-bottom: 1rem; background: #fefae0;">
                <p><strong>Date:</strong> <?php echo htmlspecialchars($order['timestamp']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($order['address'])); ?></p>
                <p><strong>Total:</strong> $<?php echo number_format($order['total'], 2); ?></p>

                <h4>Items:</h4>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li>
                            <?php echo htmlspecialchars($item['title']); ?> — $<?php echo number_format($item['price'], 2); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php include '../includes/footer.php'; ?>
