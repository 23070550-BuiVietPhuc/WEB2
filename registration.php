<?php
session_start();
include "connection.php"; // Dùng chung file connection

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Kiểm tra user tồn tại
$check = $con->prepare("SELECT * FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Username Exists";
} else {
    // MÃ HÓA MẬT KHẨU TRƯỚC KHI LƯU
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Chèn vào DB
    $stmt = $con->prepare("INSERT INTO users(username, password, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);
    
    if ($stmt->execute()) {
         // Đăng ký xong có thể chuyển hướng về login luôn cho tiện
         echo "<script>alert('Đăng ký thành công!'); window.location='login.php';</script>";
    } else {
         echo "Error: " . $stmt->error;
    }
}
?>