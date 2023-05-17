<?php
require 'db.php';
session_start();

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div id="non-logged-in" style="display: none;">
        <div class="main-block" id="login-form">
            <h2>Login</h2>
            <form onsubmit="event.preventDefault(); login();">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <input class="login-button" type="submit" value="Login">
            </form>
            <div id="unauthorized-message" style="display: none;"></div>
        </div>
    </div>

    <div id="logged-in" style="display: none;">
        <div class="main-block">
            <div id="user-info">
            </div>
            <button class="logout-button" onclick="logout();">Logout</button>
            <div id="authorized-message" style="display: none;"></div>
        </div>
    </div>

    <script src="index.js"></script>

    <script>
        <?php
        if ($user) {
            echo 'window.onload = function() { showUserInfo(' . json_encode($user) . '); };';
        } else {
            echo 'window.onload = function() { showLoginForm(); };';
        }
        ?>
    </script>
</body>

</html>