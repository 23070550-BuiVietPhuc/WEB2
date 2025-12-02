<?php
// --- BẬT BÁO LỖI (Để tìm nguyên nhân nếu màn hình trắng) ---
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Danh sách IP cục bộ
$whitelist = array('127.0.0.1', '::1');

// Kiểm tra: Nếu là Localhost (IP whitelist HOẶC tên miền là localhost)
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist) || $_SERVER['SERVER_NAME'] === 'localhost') {
    // --- CẤU HÌNH CHO MÁY TÍNH (XAMPP) ---
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db_name = "recipe_share_db";
} else {
    // --- CẤU HÌNH CHO INFINITYFREE ---
    $host = "sql206.infinityfree.com"; 
    $user = "if0_40503839";
    $pass = "dungphuctai"; 
    $db_name = "if0_40503839_recipe_share_db";
}

// Thực hiện kết nối
try {
    $con = mysqli_connect($host, $user, $pass, $db_name);
} catch (Exception $e) {
    // Nếu lỗi, in ra màn hình để biết đường sửa
    die("<h3 style='color:red'>Lỗi kết nối Database!</h3>" .
        "<b>Thông báo lỗi:</b> " . $e->getMessage() . "<br>" .
        "<b>Đang thử kết nối tới Host:</b> " . $host . "<br>" .
        "<b>User:</b> " . $user);
}

// Thiết lập Font tiếng Việt
mysqli_set_charset($con, 'utf8');
?>