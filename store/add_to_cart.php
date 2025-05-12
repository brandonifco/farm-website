<?php
// /store/add_to_cart.php
session_start();
header('Content‑Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST required']);   // basic guard
    exit;
}

$addId  = isset($_POST['add_id'])  ? (int) $_POST['add_id']  : 0;
$qty    = isset($_POST['quantity'])? max(1, (int) $_POST['quantity']) : 1;

if ($addId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product id']);
    exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$addId] = ($_SESSION['cart'][$addId] ?? 0) + $qty;

echo json_encode(['status' => 'ok']);
