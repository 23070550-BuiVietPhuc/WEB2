<?php
session_start();
include "connection.php";

// --- ĐOẠN MỚI THÊM: Tự động đăng nhập bằng Cookie ---
if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {
        $cookie_user = $_COOKIE['username'];
        $cookie_token = $_COOKIE['token']; // Đây là chuỗi hash password

        // Kiểm tra lại trong DB xem cookie có hợp lệ không
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $cookie_user);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            // So khớp token trong cookie với password hash trong DB
            if ($cookie_token === $row['password']) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
            }
        }
    }
}
// ----------------------------------------------------

// Nếu sau khi check cookie mà vẫn chưa có session -> Đuổi về login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// ... (Phần còn lại của file home.php giữ nguyên) ...
$username = $_SESSION['username'];
// ...
?>