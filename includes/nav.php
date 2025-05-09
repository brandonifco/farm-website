<nav role="navigation" aria-label="Main Navigation">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <ul class="nav" style="display: flex; gap: 1rem; list-style: none; margin: 0; padding: 0;">
            <li><a href="/index.php">Home</a></li>
            <li><a href="/about.php">About</a></li>
            <li><a href="/store/index.php">Shop</a></li>
            <li><a href="/services/index.php">Services</a></li>
            <li><a href="/blog/index.php">Blog</a></li>
            <li><a href="/contact/index.php">Contact</a></li>
        </ul>
        <ul class="nav" style="display: flex; gap: 1rem; list-style: none; margin: 0; padding: 0;">
            <li><a href="/store/cart.php">🛒 Cart</a></li>
            <?php if (!empty($_SESSION['admin'])): ?>
                <li><a href="/admin/dashboard.php">Dashboard</a></li>
                <li><a href="/admin/logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="/admin/login.php">Admin Login</a></li>
            <?php endif; ?>
        </ul>

    </div>
</nav>