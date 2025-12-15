<?php
session_start();
include "connection.php"; 

// Hàm hỗ trợ trả về lỗi
function returnError($message) {
    $_SESSION['register_error'] = $message;
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? ''; 
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');

// 1. Kiểm tra rỗng
if ($username === '' || $password === '') {
    returnError('Vui lòng nhập đầy đủ Username và Password!');
}

// 2. Kiểm tra khớp mật khẩu
if ($password !== $confirm_password) {
    returnError('Mật khẩu xác nhận không khớp!');
}

// 3. Kiểm tra độ dài (CHỈ CẦN ĐOẠN NÀY LÀ ĐỦ)
if (strlen($password) < 8) {
    returnError('Mật khẩu quá ngắn (tối thiểu 8 ký tự)!');
}

// 4. Kiểm tra trùng User
$stmt = $con->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    returnError('Tên đăng nhập đã tồn tại!');
}
$stmt->close();

// 5. Đăng ký
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$insert = $con->prepare('INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)');
$insert->bind_param('ssss', $username, $hashed_password, $email, $phone);

if ($insert->execute()) {
    $_SESSION['register_success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
    header("Location: login.php");
} else {
    returnError('Lỗi hệ thống: ' . $insert->error);
}

$insert->close();
?>
