<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

include '../includes/header.php';

$productsFile = '../store/products.json';
$backupFiles = [];

for ($i = 1; $i <= 10; $i++) {
    $bak = $productsFile . '.bak' . str_pad($i, 2, '0', STR_PAD_LEFT);
    if (file_exists($bak)) {
        $backupFiles[] = basename($bak);
    }
}

// Handle restore
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restore']) && isset($_POST['confirm_restore'])) {
    $selectedBackup = basename($_POST['restore']);
    $backupPath = '../store/' . $selectedBackup;

    if (file_exists($backupPath)) {
        copy($backupPath, $productsFile);
        $message = "Restored from $selectedBackup";
    } else {
        $message = "Backup file not found.";
    }
}
?>

<section>
    <h1>Restore Product Backup</h1>
    <p><a href="dashboard.php" class="button">← Back to Dashboard</a></p>

    <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>

    <?php if (empty($backupFiles)): ?>
        <p>No backups found.</p>
    <?php else: ?>
        <form method="post" action="restore-backup.php">
            <label for="restore">Choose a backup to restore:</label><br><br>
            <select name="restore" id="restore" required onchange="this.form.submit()">
                <option value="">-- Select Backup --</option>
                <?php foreach ($backupFiles as $file): ?>
                    <option value="<?php echo htmlspecialchars($file); ?>" <?php echo (isset($_POST['restore']) && $_POST['restore'] === $file) ? 'selected' : ''; ?>>
                        <?php echo $file; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit" class="button">Preview</button></noscript>
        </form>
        <?php if (isset($_POST['restore']) && in_array($_POST['restore'], $backupFiles)): ?>
            <h3>Preview of <?php echo htmlspecialchars($_POST['restore']); ?></h3>
            <pre style="max-height: 300px; overflow-y: scroll; background: #f0f0f0; padding: 1rem;">
<?php
            $preview = file_get_contents('../store/' . basename($_POST['restore']));
            echo htmlspecialchars($preview);
            echo "</pre>";
            $products = json_decode($preview, true);

            if (is_array($products)) {
                echo '<h3>Products in this Backup</h3>';
                echo '<table border="1" cellpadding="6" cellspacing="0">';
                echo '<tr><th>ID</th><th>Title</th><th>Price</th><th>Description</th><th>Image</th></tr>';
                foreach ($products as $p) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p['id']) . '</td>';
                    echo '<td>' . htmlspecialchars($p['title']) . '</td>';
                    echo '<td>$' . number_format((float)$p['price'], 2) . '</td>';
                    echo '<td>' . htmlspecialchars($p['description']) . '</td>';
                    echo '<td>';
                    if (!empty($p['image'])) {
                        echo '<img src="' . htmlspecialchars($p['image']) . '" alt="" width="50">';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "<p style='color:red;'>Could not parse product data.</p>";
            }

?>
    </pre>

            <form method="post" action="restore-backup.php">
                <input type="hidden" name="restore" value="<?php echo htmlspecialchars($_POST['restore']); ?>">
                <button type="submit" name="confirm_restore" class="button" onclick="return confirm('Restore this backup?')">Restore This Backup</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

</section>

<?php include '../includes/footer.php'; ?>