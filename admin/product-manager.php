<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['cancel_preview'])) {
    unset($_SESSION['preview_adds'], $_SESSION['preview_updates']);
    header('Location: product-manager.php');
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
$productsFile = '../store/products.json';
// Load products AFTER path is defined
$products = json_decode(file_get_contents($productsFile), true) ?? [];

function backupProductsJson($filePath)
{
    // Create rolling backups: .bak10 ← .bak09 ← ... ← .bak01 ← current
    for ($i = 10; $i >= 2; $i--) {
        $prev = $filePath . '.bak' . str_pad($i - 1, 2, '0', STR_PAD_LEFT);
        $next = $filePath . '.bak' . str_pad($i, 2, '0', STR_PAD_LEFT);
        if (file_exists($prev)) {
            rename($prev, $next);
        }
    }

    // Copy current file to .bak01
    if (file_exists($filePath)) {
        copy($filePath, $filePath . '.bak01');
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $newProduct = [
        'id' => time(), // Unique enough for now
        'title' => $_POST['title'],
        'price' => (float) $_POST['price'],
        'image' => $_POST['image'],
        'description' => $_POST['description']
    ];

    $products[] = $newProduct;
    backupProductsJson($productsFile);

    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));

    // Redirect to self to avoid form resubmission
    header('Location: product-manager.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = (int) $_POST['id'];

    foreach ($products as &$p) {
        if ($p['id'] === $id) {
            $p['title'] = $_POST['title'];
            $p['price'] = (float) $_POST['price'];
            $p['image'] = $_POST['image'];
            $p['description'] = $_POST['description'];
            break;
        }
    }
    backupProductsJson($productsFile);

    file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));
    header('Location: product-manager.php');
    exit;
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int) $_POST['delete_id'];

    $products = array_filter($products, fn($p) => $p['id'] !== $deleteId);
    backupProductsJson($productsFile);

    file_put_contents($productsFile, json_encode(array_values($products), JSON_PRETTY_PRINT));

    header('Location: product-manager.php');
    exit;
}
// Handle bulk delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete']) && !empty($_POST['selected'])) {
    $selectedIds = array_map('intval', $_POST['selected']);

    $products = array_filter($products, function ($p) use ($selectedIds) {
        return !in_array($p['id'], $selectedIds);
    });
    backupProductsJson($productsFile);

    file_put_contents($productsFile, json_encode(array_values($products), JSON_PRETTY_PRINT));

    header('Location: product-manager.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_csv']) && isset($_FILES['csv'])) {
    $csvPath = $_FILES['csv']['tmp_name'];

    if (is_uploaded_file($csvPath)) {
        $added = 0;
        $updated = 0;

        $handle = fopen($csvPath, 'r');
        if ($handle !== false) {
            $header = fgetcsv($handle); // read and discard header

            while (($row = fgetcsv($handle)) !== false) {
                [$title, $price, $image, $description] = array_map('trim', $row);
                $found = false;

                foreach ($products as &$existing) {
                    if (
                        strcasecmp(trim($existing['title']), $title) === 0 &&
                        strcasecmp(trim($existing['description']), $description) === 0
                    ) {
                        $newPrice = (float)$price;
                        $priceChanged = $newPrice !== (float)$existing['price'];
                        $imageChanged = trim($existing['image']) !== $image;

                        if ($priceChanged || $imageChanged) {
                            $existing['price'] = $newPrice;
                            $existing['image'] = $image;
                            $updated++;
                        }

                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $products[] = [
                        'id' => time() + rand(1, 9999),
                        'title' => $title,
                        'price' => (float)$price,
                        'image' => $image,
                        'description' => $description
                    ];
                    $added++;
                }
            }

            fclose($handle);

            backupProductsJson($productsFile);
            file_put_contents($productsFile, json_encode($products, JSON_PRETTY_PRINT));
            unset($_SESSION['preview_adds'], $_SESSION['preview_updates']);
            echo "<script>alert('Import complete: $added added, $updated updated.'); window.location.href = 'product-manager.php';</script>";
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['preview_csv']) && isset($_FILES['csv'])) {
    $csvPath = $_FILES['csv']['tmp_name'];

    if (is_uploaded_file($csvPath)) {
        $previewAdds = [];
        $previewUpdates = [];

        $handle = fopen($csvPath, 'r');
        if ($handle !== false) {
            $header = fgetcsv($handle); // skip header

            while (($row = fgetcsv($handle)) !== false) {
                [$title, $price, $image, $description] = array_map('trim', $row);
                $found = false;

                foreach ($products as $existing) {
                    if (
                        strcasecmp(trim($existing['title']), $title) === 0 &&
                        strcasecmp(trim($existing['description']), $description) === 0
                    ) {
                        $newPrice = (float)$price;
                        $priceChanged = $newPrice !== (float)$existing['price'];
                        $imageChanged = trim($existing['image']) !== $image;

                        if ($priceChanged || $imageChanged) {
                            $previewUpdates[] = [
                                'title' => $title,
                                'old_price' => $existing['price'],
                                'new_price' => $newPrice,
                                'old_image' => $existing['image'],
                                'new_image' => $image
                            ];
                        }

                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $previewAdds[] = [
                        'title' => $title,
                        'price' => (float)$price,
                        'image' => $image,
                        'description' => $description,
                    ];
                }
            }

            fclose($handle); // ✅ Correct placement

            $_SESSION['preview_adds'] = $previewAdds;
            $_SESSION['preview_updates'] = $previewUpdates;

            echo "<script>window.location.href = 'product-manager.php?preview=1';</script>";
            exit;
        }
    }
}

include '../includes/header.php';

?>

<section>
    <p><a href="dashboard.php" class="button">← Back to Dashboard</a></p>

    <h1>Product Manager</h1>

    <h2>Current Products</h2>
    <hr>
    <?php
    $editing = false;
    $editProduct = null;

    if (isset($_GET['edit_id'])) {
        $editId = (int) $_GET['edit_id'];
        foreach ($products as $p) {
            if ($p['id'] === $editId) {
                $editing = true;
                $editProduct = $p;
                break;
            }
        }
    }
    ?>

    <hr>
    <h2><?php echo $editing ? 'Edit Product' : 'Add New Product'; ?></h2>
    <form method="post" action="product-manager.php">
        <input type="hidden" name="id" value="<?php echo $editing ? $editProduct['id'] : ''; ?>">

        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo $editing ? htmlspecialchars($editProduct['title']) : ''; ?>" required><br><br>

        <label for="price">Price (USD):</label><br>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo $editing ? $editProduct['price'] : ''; ?>" required><br><br>

        <label for="image">Image Path:</label><br>
        <input type="text" id="image" name="image" value="<?php echo $editing ? htmlspecialchars($editProduct['image']) : ''; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?php echo $editing ? htmlspecialchars($editProduct['description']) : ''; ?></textarea><br><br>

        <button type="submit" name="<?php echo $editing ? 'update' : 'add'; ?>" class="button">
            <?php echo $editing ? 'Update Product' : 'Add Product'; ?>
        </button>
    </form>
    <hr>
    <h2>Import Products from CSV</h2>
    <form method="post" action="product-manager.php" enctype="multipart/form-data">
        <label for="csv">Upload CSV File:</label><br>
        <input type="file" name="csv" id="csv" accept=".csv" required><br><br>

        <button type="submit" name="preview_csv" class="button">Preview Only</button>
        <button type="submit" name="import_csv" class="button">Import Now</button>
    </form>


    <?php if (isset($_GET['preview']) && !empty($_SESSION['preview_adds']) || !empty($_SESSION['preview_updates'])): ?>
        <section style="margin-bottom: 2rem;">
            <h2>📦 CSV Import Preview</h2>

            <?php if (!empty($_SESSION['preview_adds'])): ?>
                <h3>Products to Add (<?php echo count($_SESSION['preview_adds']); ?>)</h3>
                <table border="1" cellpadding="6">
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach ($_SESSION['preview_adds'] as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td>$<?php echo number_format($p['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($p['image']); ?></td>
                            <td><?php echo htmlspecialchars($p['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <?php if (!empty($_SESSION['preview_updates'])): ?>
                <h3>Products to Update (<?php echo count($_SESSION['preview_updates']); ?>)</h3>
                <table border="1" cellpadding="6">
                    <tr>
                        <th>Title</th>
                        <th>Old Price</th>
                        <th>New Price</th>
                        <th>Old Image</th>
                        <th>New Image</th>
                    </tr>
                    <?php foreach ($_SESSION['preview_updates'] as $p): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td>$<?php echo number_format($p['old_price'], 2); ?></td>
                            <td>$<?php echo number_format($p['new_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($p['old_image']); ?></td>
                            <td><?php echo htmlspecialchars($p['new_image']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <form method="post" action="product-manager.php">
                <input type="hidden" name="import_csv_confirmed" value="1">
                <button type="submit" class="button">✔ Confirm and Import</button>
                <a href="product-manager.php?cancel_preview=1" class="button" style="background-color: #ccc;">Cancel</a>
            </form>
        </section>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <form method="post" action="product-manager.php">

            <table border="1" cellpadding="8" cellspacing="0">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all" onclick="toggleAll(this)"></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><input type="checkbox" name="selected[]" value="<?php echo $p['id']; ?>"></td>
                            <td><?php echo $p['id']; ?></td>
                            <td><?php echo htmlspecialchars($p['title']); ?></td>
                            <td>$<?php echo number_format($p['price'], 2); ?></td>
                            <td><img src="<?php echo htmlspecialchars($p['image']); ?>" alt="" width="50"></td>
                            <td><?php echo htmlspecialchars($p['description']); ?></td>
                            <td>
                                <form method="get" action="product-manager.php" style="display:inline;">
                                    <input type="hidden" name="edit_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="button">Edit</button>
                                </form>

                                <form method="post" action="product-manager.php" onsubmit="return confirm('Delete this product?');" style="display:inline;">
                                    <input type="hidden" name="delete_id" value="<?php echo $p['id']; ?>">
                                    <button type="submit" class="button" style="background-color: red;">Delete</button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <button type="submit" name="bulk_delete" class="button" style="background-color: red;" onclick="return confirm('Delete selected products?');">
                    Delete Selected
                </button>
            </p>

        </form>
    <?php endif; ?>
    <script>
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('input[name="selected[]"]');
            for (const box of checkboxes) {
                box.checked = source.checked;
            }
        }
    </script>

</section>

<?php include '../includes/footer.php'; ?>