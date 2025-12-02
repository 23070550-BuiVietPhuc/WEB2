<?php
session_start();
include "connection.php"; // <--- QUAN TRỌNG: Gọi file kết nối chung để tự động nhận diện Hosting

/* Lấy dữ liệu từ form */
$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone = $_POST['phone'];

/* 1. Kiểm tra username đã tồn tại chưa */
// Dùng Prepared Statement để bảo mật và tránh lỗi SQL
$check_stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "Username Exists";
} else {
    /* 2. Nếu chưa tồn tại thì thêm mới */
    // MÃ HÓA PASSWORD (Bắt buộc nếu bạn dùng login.php mới mình gửi lúc nãy)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_stmt = $con->prepare("INSERT INTO users(username, password, email, phone) VALUES (?, ?, ?, ?)");
    $insert_stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

    if ($insert_stmt->execute()) {
        // Đăng ký thành công -> Chuyển về trang login
        echo "<script>
                alert('Registration Successful');
                window.location = 'login.php';
              </script>";
    } else {
        echo "Lỗi: " . $con->error;
    }
}
?>