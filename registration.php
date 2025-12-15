<?php
// Bật báo lỗi để dễ sửa (có thể tắt khi chạy thực tế)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "connection.php"; // Kết nối database chung

// Chỉ xử lý khi có request POST đến
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // Nếu người dùng cố truy cập trực tiếp link này mà không submit form
    header("Location: login.php");
    exit;
}

// 1. Lấy dữ liệu từ form
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? ''; // <--- MỚI: Lấy mật khẩu xác nhận
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');

// Kiểm tra dữ liệu bắt buộc
if ($username === '' || $password === '') {
    echo "<script>alert('Username và password bắt buộc'); history.back();</script>";
    exit;
}

// 2. MỚI: Kiểm tra mật khẩu xác nhận có khớp không
if ($password !== $confirm_password) {
    echo "<script>
            alert('Mật khẩu xác nhận không khớp! Vui lòng kiểm tra lại.'); 
            history.back(); // Quay lại trang trước để không mất dữ liệu đã nhập
          </script>";
    exit; // Dừng code ngay lập tức
}
if (strlen($password) < 8) {
    returnError('Mật khẩu quá ngắn (tối thiểu 8 ký tự)!');
}
// Hàm kiểm tra xem người dùng đã tồn tại chưa
function user_exists($con, $username) {
    $stmt = $con->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
    if (!$stmt) return false;
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Kiểm tra trùng username
if (user_exists($con, $username)) {
    echo "<script>alert('Tên đăng nhập đã tồn tại!'); history.back();</script>";
    exit;
}

// 3. Mã hóa mật khẩu và thêm người dùng mới vào DB
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$insert = $con->prepare('INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)');

if (!$insert) {
    echo 'Lỗi prepare: ' . $con->error;
    exit;
}

// Gán các tham số vào câu lệnh SQL
$insert->bind_param('ssss', $username, $hashed_password, $email, $phone);

// Thực thi lệnh insert
if ($insert->execute()) {
    echo "<script>
            alert('Đăng ký thành công! Vui lòng đăng nhập.');
            window.location = 'login.php';
          </script>";
} else {
    echo 'Lỗi thực thi: ' . $insert->error;
}

$insert->close();

?>

