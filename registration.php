<?php
session_start();
include "connection.php"; 

// Nếu không phải POST thì đá về login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username']);
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);

// 1. Kiểm tra cơ bản (Rỗng, Độ dài pass, Pass khớp)
if (empty($username) || empty($password) || empty($email)) {
    echo "<script>alert('Vui lòng điền đủ thông tin!'); history.back();</script>";
    exit;
}
if (strlen($password) < 8) {
    echo "<script>alert('Mật khẩu phải từ 8 ký tự trở lên!'); history.back();</script>";
    exit;
}
if ($password !== $confirm) {
    echo "<script>alert('Mật khẩu xác nhận không khớp!'); history.back();</script>";
    exit;
}

// 2. Kiểm tra xem Username HOẶC Email đã tồn tại chưa (Gộp 2 bước làm 1)
$check = $con->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
$check->bind_param("ss", $username, $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Nếu tìm thấy kết quả => Đã trùng Username hoặc Email
    echo "<script>alert('Tên đăng nhập hoặc Email này đã được sử dụng!'); history.back();</script>";
    exit;
}
$check->close();

// 3. Nếu chưa tồn tại thì thêm mới
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt = $con->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

if ($stmt->execute()) {
    echo "<script>alert('Đăng ký thành công!'); window.location='login.php';</script>";
} else {
    echo "<script>alert('Lỗi hệ thống, vui lòng thử lại.'); history.back();</script>";
}

$stmt->close();
?>
