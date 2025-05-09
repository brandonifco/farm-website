<?php
/** @var array $products */
?>
<section>
    <h2>Current Products</h2>
    <hr>

    <?php if (!$products): ?>
        <p>No products found.</p>
    <?php else: ?>
        <form method="post"
              action="product-manager.php?action=bulk-delete"
              onsubmit="return confirm('Delete selected products?');">

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
                        <td><input type="checkbox" name="selected[]" value="<?= $p['id']; ?>"></td>
                        <td><?= $p['id']; ?></td>
                        <td><?= htmlspecialchars($p['title']); ?></td>
                        <td>$<?= number_format($p['price'], 2); ?></td>
                        <td><img src="<?= htmlspecialchars($p['image']); ?>" alt="" width="50"></td>
                        <td><?= htmlspecialchars($p['description']); ?></td>
                        <td>
                            <form method="get" action="product-manager.php" style="display:inline;">
                                <input type="hidden" name="edit_id" value="<?= $p['id']; ?>">
                                <button type="submit" class="button">Edit</button>
                            </form>

                            <form method="post"
                                  action="product-manager.php?action=delete"
                                  style="display:inline;"
                                  onsubmit="return confirm('Delete this product?');">
                                <input type="hidden" name="delete_id" value="<?= $p['id']; ?>">
                                <button type="submit" class="button" style="background:red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <p>
                <button type="submit" class="button" style="background:red;">Delete Selected</button>
            </p>
        </form>
    <?php endif; ?>
</section>

<!-- Minimal helper; if you already import product‑manager.js elsewhere you can delete this -->
<script>
function toggleAll(src){
    document.querySelectorAll('input[name="selected[]"]')
            .forEach(cb => cb.checked = src.checked);
}
</script>
