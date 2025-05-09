<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
include '../includes/header.php'; ?>
<section>
    <h1>Dashboard</h1>
    <p>Welcome to the admin dashboard. Control your site here.</p>
    <?php
    $orderFile = '../store/orders.json';
    $totalOrders = 0;
    $totalRevenue = 0.0;

    if (file_exists($orderFile)) {
        $orders = json_decode(file_get_contents($orderFile), true);
        $totalOrders = count($orders);

        foreach ($orders as $order) {
            $totalRevenue += $order['total'];
        }
    }
    ?>
    <div style="margin-bottom: 1rem;">
        <strong>Total Orders:</strong> <?php echo $totalOrders; ?><br>
        <strong>Total Revenue:</strong> $<?php echo number_format($totalRevenue, 2); ?>
    </div>

    <ul style="list-style-type: none; padding: 0;">
        <li><a href="/admin/product-manager/product-manager.php">Manage Products</a></li>
        <li><a href="/admin/orders.php">View Orders</a></li>
        <li><a href="/admin/export-orders.php">Download Orders (CSV)</a></li>
        <li><a href="/admin/restore-backup.php">Restore Product Backup</a></li>

    </ul>
    <p><a href="logout.php" class="button">Log Out</a></p>


</section>
<?php include '../includes/footer.php'; ?>