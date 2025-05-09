<?php
require_once __DIR__ . '/inc/auth.php';
require_once __DIR__ . '/inc/products.php';
require_once __DIR__ . '/inc/csv.php';

/* ---------- tiny “router” ---------- */
$action = $_REQUEST['action'] ?? null;
if ($action) {
    $path = __DIR__ . "/actions/$action.php";
    if (is_file($path)) require $path;
    // every action ends with exit; if we’re here just fall through
}

/* ---------- view data ---------- */
$products      = loadProducts();
$editing       = isset($_GET['edit_id']);
$editProduct = null;
if ($editing) {
    $editId = (int)$_GET['edit_id'];
    foreach ($products as $p) {
        if ($p['id'] === $editId) {
            $editProduct = $p;
            break;
        }
    }
}


$previewAdds   = $_SESSION['preview_adds']   ?? [];
$previewUpdates = $_SESSION['preview_updates'] ?? [];

/* ---------- render ---------- */
include '../../includes/header.php';
include __DIR__ . '/templates/form.php';
include __DIR__ . '/templates/preview.php';
include __DIR__ . '/templates/table.php';
include '../../includes/footer.php';
