<?php
session_start();
include "connection.php"; // Kết nối CSDL

// Lấy dữ liệu từ form
$username = $_POST['username'];
$password = $_POST['password'];

// 1. Dùng Prepared Statement để chống SQL Injection
$stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra xem có user này không
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // 2. Kiểm tra mật khẩu (Dùng password_verify cho mật khẩu đã mã hóa)
    // Lưu ý: Nếu database cũ của bạn đang lưu password thường, dòng này sẽ lỗi.
    // Bạn cần reset lại DB hoặc đăng ký user mới sau khi sửa file registration.php
    if (password_verify($password, $user['password'])) {
        
        // Đăng nhập thành công -> Lưu Session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['user_id'];

        // 3. Xử lý "Remember Me" (Cookie)
        if (isset($_POST['remember'])) {
            // Lưu username vào cookie trong 30 ngày
            setcookie('username', $username, time() + (86400 * 30), "/");
            
            // Lưu mật khẩu ĐÃ MÃ HÓA (từ DB) vào cookie để đối chiếu sau này (Không lưu password thường!)
            setcookie('token', $user['password'], time() + (86400 * 30), "/"); 
        }

        header('location:home.php');
        exit;
    } else {
        // Sai mật khẩu
        $_SESSION['error'] = "Mật khẩu không đúng!";
        header('location:login.php');
        exit;
    }
} else {
    // Sai username
    $_SESSION['error'] = "Tài khoản không tồn tại!";
    header('location:login.php');
    exit;
}
?>