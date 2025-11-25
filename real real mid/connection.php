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
    // --- CẤU HÌNH CHO INFINITYFREE ---
    // LƯU Ý: Mày phải vào web InfinityFree mục MySQL Databases để xem cái 'sqlXXX' chính xác là gì nhé (Ví dụ: sql305.infinityfree.com)
    // Tao để tạm sql300, mày phải sửa lại cho đúng cái của mày
    $host = "sql305.infinityfree.com"; 
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