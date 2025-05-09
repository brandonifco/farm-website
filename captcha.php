<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Human verified, allow access
    $_SESSION['verified_human'] = true;
    header('Location: /');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Are you a human?</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: sans-serif;
            text-align: center;
            padding: 2em;
            background: #f9f9f9;
        }
        form {
            display: inline-block;
            margin-top: 2em;
        }
        button {
            padding: 0.5em 1em;
            font-size: 1.2em;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Are you a human?</h1>
    <p>We've detected unusual traffic from your browser. If you're a real person, please confirm below to continue.</p>
    <form method="post">
        <button type="submit">Yes, I'm human</button>
    </form>
</body>
</html>
