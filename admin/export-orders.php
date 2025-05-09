<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

$orderFile = '../store/orders.json';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders.csv"');

$output = fopen('php://output', 'w');

// Write CSV header
fputcsv($output, ['Timestamp', 'Name', 'Email', 'Address', 'Product', 'Quantity', 'Unit Price', 'Line Total']);

// Load and format each order
if (file_exists($orderFile)) {
    $orders = json_decode(file_get_contents($orderFile), true);

    foreach ($orders as $order) {
        foreach ($order['items'] as $item) {
            fputcsv($output, [
                $order['timestamp'],
                $order['name'],
                $order['email'],
                $order['address'],
                $item['title'],
                $item['quantity'],
                $item['price'],
                $item['line_total']
            ]);
        }
    }
}

fclose($output);
exit;
