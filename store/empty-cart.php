<?php
// /store/empty-cart.php
session_start();

// Only accept POST to prevent accidental empties
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

unset($_SESSION['cart']);    // wipe the cart
header('Location: cart.php');  // back to the cart view
exit;
