<?php
session_start();
include "user_model.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gọi Model
    $user = checkLoginCredentials($username, $password);

    if ($user) {
        // 1. Lưu Session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];
        
        // 2. Xử lý "Remember Me" (Cookie)
        if (isset($_POST['remember'])) {
            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('token', $user['password'], time() + (86400 * 30), "/");
        }

        header('Location: home.php');
        exit;
    } else {
        $_SESSION['error'] = "Tên đăng nhập hoặc mật khẩu không đúng!";
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>