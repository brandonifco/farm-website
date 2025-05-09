<?php
/** @var bool       $editing      */
/** @var array|null $editProduct  */
?>
<section>
    <h2><?= $editing ? 'Edit Product' : 'Add New Product'; ?></h2>

    <form method="post"
          action="product-manager.php?action=<?= $editing ? 'update' : 'add'; ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="id" value="<?= $editProduct['id']; ?>">
        <?php endif; ?>

        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title"
               value="<?= $editing ? htmlspecialchars($editProduct['title']) : ''; ?>"
               required><br><br>

        <label for="price">Price (USD):</label><br>
        <input type="number" step="0.01" id="price" name="price"
               value="<?= $editing ? $editProduct['price'] : ''; ?>"
               required><br><br>

        <label for="image">Image Path:</label><br>
        <input type="text" id="image" name="image"
               value="<?= $editing ? htmlspecialchars($editProduct['image']) : ''; ?>"
               required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?= $editing ? htmlspecialchars($editProduct['description']) : ''; ?></textarea><br><br>

        <button type="submit" class="button">
            <?= $editing ? 'Update Product' : 'Add Product'; ?>
        </button>

        <?php if ($editing): ?>
            <a href="product-manager.php" class="button" style="background:#ccc;">Cancel Edit</a>
        <?php endif; ?>
    </form>
</section>
