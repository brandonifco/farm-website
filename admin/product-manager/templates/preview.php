<?php
/** @var array $previewAdds */
/** @var array $previewUpdates */
if (!empty($previewAdds) || !empty($previewUpdates)):
?>
<section style="margin-bottom:2rem;">
    <h2>📦 CSV Import Preview</h2>

    <?php if ($previewAdds): ?>
        <h3>Products to Add (<?= count($previewAdds); ?>)</h3>
        <table border="1" cellpadding="6">
            <tr>
                <th>Title</th><th>Price</th><th>Image</th><th>Description</th>
            </tr>
            <?php foreach ($previewAdds as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['title']); ?></td>
                    <td>$<?= number_format($p['price'], 2); ?></td>
                    <td><?= htmlspecialchars($p['image']); ?></td>
                    <td><?= htmlspecialchars($p['description']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if ($previewUpdates): ?>
        <h3>Products to Update (<?= count($previewUpdates); ?>)</h3>
        <table border="1" cellpadding="6">
            <tr>
                <th>Title</th>
                <th>Old&nbsp;Price</th><th>New&nbsp;Price</th>
                <th>Old&nbsp;Image</th><th>New&nbsp;Image</th>
            </tr>
            <?php foreach ($previewUpdates as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['title']); ?></td>
                    <td>$<?= number_format($p['old_price'], 2); ?></td>
                    <td>$<?= number_format($p['new_price'], 2); ?></td>
                    <td><?= htmlspecialchars($p['old_image']); ?></td>
                    <td><?= htmlspecialchars($p['new_image']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <form method="post" action="product-manager.php?action=import-csv">
        <input type="hidden" name="import_csv_confirmed" value="1">
        <button type="submit" class="button">✔ Confirm and Import</button>
        <a href="product-manager.php?cancel_preview=1" class="button" style="background:#ccc;">Cancel</a>
    </form>
</section>
<?php endif; ?>
