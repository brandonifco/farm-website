<?php
/* Commit the CSV changes that were previewed */
if (
    isset($_POST['import_csv_confirmed']) &&
    $_FILES['csv']['error'] === UPLOAD_ERR_OK &&
    is_uploaded_file($_FILES['csv']['tmp_name'])
) {
    [$added, $updated] = importCsv($_FILES['csv']['tmp_name'], loadProducts());

    // Clear session preview data
    unset($_SESSION['preview_adds'], $_SESSION['preview_updates']);

    header("Location: product-manager.php?msg=imported&added=$added&updated=$updated");
    exit;
}

/* Fallback: reached without a fresh upload (e.g. after confirmation button) */
if (isset($_POST['import_csv_confirmed'])) {
    // We already have preview data in the session — run import on that
    $adds    = $_SESSION['preview_adds']    ?? [];
    $updates = $_SESSION['preview_updates'] ?? [];

    // Re‑hydrate products and apply updates/adds
    $products = loadProducts();
    foreach ($updates as $u) {
        foreach ($products as &$p) {
            if (
                strcasecmp($p['title'], $u['title']) === 0 &&
                strcasecmp($p['description'], $u['description']) === 0
            ) {
                $p['price'] = (float)$u['new_price'];
                $p['image'] = $u['new_image'];
                break;
            }
        }
    }
    foreach ($adds as $a) {
        $products[] = [
            'id'          => generateId(),
            'title'       => $a['title'],
            'price'       => (float)$a['price'],
            'image'       => $a['image'],
            'description' => $a['description'],
        ];
    }
    saveProducts($products);

    unset($_SESSION['preview_adds'], $_SESSION['preview_updates']);
    header('Location: product-manager.php?msg=imported');
    exit;
}

header('Location: product-manager.php?err=import');
exit;
