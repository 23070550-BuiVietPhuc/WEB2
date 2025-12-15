<?php
session_start();
include "connection.php";

$username = $_POST['username'];
$password = $_POST['password'];

// 1. Tìm user trong database
$stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // 2. Kiểm tra mật khẩu (So sánh pass nhập vào với pass đã mã hóa trong DB)
    if (password_verify($password, $user['password'])) {
        
        // Đăng nhập thành công -> Lưu Session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];

        // 3. Xử lý "Remember Me" (Cookie)
        if (isset($_POST['remember'])) {
            // Lưu trong 30 ngày
            setcookie('username', $username, time() + (86400 * 30), "/");
            // Lưu chuỗi hash mật khẩu làm token xác thực
            setcookie('token', $user['password'], time() + (86400 * 30), "/"); 
        }

        header('location:home.php');
    } else {
        $_SESSION['error'] = "Mật khẩu không đúng!";
        header('location:login.php');
    }
} else {
    $_SESSION['error'] = "Tài khoản không tồn tại!";
    header('location:login.php');
}
?>