<?php
session_start();
include "user_model.php"; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);

// 1. Validation Logic
if (empty($username) || empty($password) || empty($email)) {
    $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin!";
    header("Location: register.php");
    exit;
}
if (strlen($password) < 8) {
    $_SESSION['error'] = "Mật khẩu phải từ 8 ký tự trở lên!";
    header("Location: register.php");
    exit;
}
if ($password !== $confirm) {
    $_SESSION['error'] = "Mật khẩu xác nhận không khớp!";
    header("Location: register.php");
    exit;
}

// 2. Gọi Model kiểm tra & tạo user
if (isUserExists($username, $email)) {
    $_SESSION['error'] = "Tên đăng nhập hoặc Email đã được sử dụng!";
    header("Location: register.php");
    exit;
}

if (createUser($username, $password, $email, $phone)) {
    $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
    header("Location: login.php");
    exit;
} else {
    $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại.";
    header("Location: register.php");
    exit;
}
?>