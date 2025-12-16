<?php
session_start();
include "templates.php"; // Gọi View Helpers
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #fffdf5; padding: 40px; }
        .box { width: 100%; max-width: 400px; margin: 0 auto; background: #fff; padding: 25px; border: 1px solid #f2e3b3; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.07); }
        h2 { color: #d68b00; text-align: center; margin-bottom: 20px; }
        button { background-color: #ffb100; color: white; border: none; padding: 10px; border-radius: 8px; cursor: pointer; width: 100%; font-size: 16px; margin-top: 10px;}
        button:hover { background-color: #e29a00; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Đăng Ký</h2>

        <?php 
            if(isset($_SESSION['error'])) { displayError($_SESSION['error']); unset($_SESSION['error']); }
        ?>

        <form action="registration.php" method="post">
            <?php editorFor("username", "Tên đăng nhập", "text"); ?>
            <?php editorFor("password", "Mật khẩu (Min 8 chars)", "password"); ?>
            <?php editorFor("confirm_password", "Nhập lại mật khẩu", "password"); ?>
            <?php editorFor("email", "Email", "email"); ?>
            <?php editorFor("phone", "Số điện thoại", "text"); ?>

            <button type="submit">Đăng ký</button>
        </form>
        
        <p style="text-align: center; margin-top: 15px;">
            Đã có tài khoản? <a href="login.php">Đăng nhập</a>
        </p>
    </div>
</body>
</html>