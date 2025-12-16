<?php
session_start();
include "user_model.php"; // Gọi Model
include "templates.php";  // Gọi View Helpers

// 1. Nếu đã có Session -> Vào Home
if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit;
}

// 2. Tự động Login bằng Cookie (Nếu có)
if (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {
    $user = getUserByUsername($_COOKIE['username']);
    if ($user && $_COOKIE['token'] === $user['password']) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];
        header('Location: home.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #fffdf5; padding: 40px; }
        .box { width: 100%; max-width: 400px; margin: 0 auto; background: #fff; padding: 25px; border: 1px solid #f2e3b3; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.07); }
        h2 { color: #d68b00; text-align: center; margin-bottom: 20px; }
        button { background-color: #ffb100; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 10px;}
        button:hover { background-color: #e29a00; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Login</h2>
        
        <?php 
            if(isset($_SESSION['error'])) { displayError($_SESSION['error']); unset($_SESSION['error']); }
            if(isset($_SESSION['success'])) { displaySuccess($_SESSION['success']); unset($_SESSION['success']); }
        ?>

        <form action="validation.php" method="post">
            <?php editorFor("username", "User name", "text"); ?>
            <?php editorFor("password", "Password", "password"); ?>

            <div style="margin-bottom: 15px;">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember" style="cursor: pointer;">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            Have an account yet? <a href="register.php">Register</a>
        </p>
    </div>
</body>
</html>