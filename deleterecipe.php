<?php
session_start(); // Phải có session start để lấy user_id
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$recipe_id = $_GET["id"];
$user_id = $_SESSION['user_id']; // Lấy ID người đang đăng nhập

// 1. Kiểm tra xem công thức này có đúng là của người này không?
$check = mysqli_query($con, "SELECT * FROM recipes WHERE recipe_id = '$recipe_id' AND user_id = '$user_id'");

if (mysqli_num_rows($check) > 0) {
    // Nếu đúng là chủ sở hữu thì mới cho xóa
    mysqli_query($con, "DELETE FROM ingredients WHERE recipe_id = $recipe_id");
    mysqli_query($con, "DELETE FROM recipes WHERE recipe_id = $recipe_id");
} else {
    // Nếu không phải chính chủ
    echo "<script>alert('Bạn không có quyền xóa bài này!'); window.location='home.php';</script>";
    exit;
}

header("Location: home.php");
exit;
?>