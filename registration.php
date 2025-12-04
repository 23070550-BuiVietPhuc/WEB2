<?php
// Bật báo lỗi để dễ sửa
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "connection.php"; // Kết nối database chung

// Xử lý khi người dùng nhấn nút Register
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);

    // 1. Kiểm tra Username đã tồn tại chưa (Dùng Prepared Statement)
    $stmt = $con->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Tên đăng nhập đã tồn tại!'); window.history.back();</script>";
    } else {
        // 2. Mã hóa mật khẩu (BẮT BUỘC để bảo mật)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 3. Thêm người dùng mới
        $insert_stmt = $con->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

        if ($insert_stmt->execute()) {
            echo "<script>
                    alert('Đăng ký thành công! Vui lòng đăng nhập.');
                    window.location = 'login.php';
                  </script>";
        } else {
            echo "Lỗi: " . $con->error;
        }
    }
    $stmt->close();
}
?>