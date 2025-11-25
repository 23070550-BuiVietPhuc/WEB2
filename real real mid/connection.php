<?php
// Kiểm tra xem đang chạy ở đâu
$whitelist = array('127.0.0.1', '::1', 'localhost');

if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
    // --- CẤU HÌNH CHO MÁY TÍNH CỦA MÀY (XAMPP) ---
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "recipe_share_db";
} else {
    $host = "sql206.infinityfree.com"; 
    $user = "if0_40503839";
    $pass = "dungphuctai"; 
    $db_name = "if0_40503839_recipe_share_db";
}

$con = mysqli_connect($host, $user, $pass, $db_name);

if (mysqli_connect_errno()) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Đảm bảo tiếng Việt không bị lỗi font
mysqli_set_charset($con, 'utf8');
?>