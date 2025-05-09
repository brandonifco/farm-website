<?php
require_once '../includes/env.php';
loadEnv('../.env');

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if ($user === $_ENV['ADMIN_USERNAME'] && $pass === $_ENV['ADMIN_PASSWORD']) {
        $_SESSION['admin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Invalid login.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<section>
    <h1>Admin Login</h1>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="login.php">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" class="button">Login</button>
    </form>
</section>
<?php include '../includes/footer.php'; ?>