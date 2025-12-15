<?php
session_start();
// Nếu đã đăng nhập thì vào thẳng home
if (isset($_SESSION['username'])) {
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login & Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #fffdf5; margin: 0; padding: 0; }
        .container { width: 80%; max-width: 900px; margin: 40px auto; display: flex; gap: 40px; justify-content: center; }
        .box { flex: 1; background-color: #ffffff; border: 1px solid #f2e3b3; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.07); }
        h2 { color: #d68b00; font-weight: 600; margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; color: #444; font-size: 14px; }
        input[type="text"], input[type="password"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #e0c77f; border-radius: 8px; margin-bottom: 15px; box-sizing: border-box; background-color: #fffdf7; }
        button { background-color: #ffb100; color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-size: 15px; width: 100%; }
        button:hover { background-color: #e29a00; }
        
        /* CSS cho thông báo lỗi và checkbox */
        .error-msg { color: red; background: #ffe6e6; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
        .remember-row { display: flex; align-items: center; gap: 5px; margin-bottom: 15px; }
        .remember-row input { width: auto; margin: 0; }
        .remember-row label { margin: 0; cursor: pointer; }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="box">
            <h2>Login Here</h2>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="error-msg">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="validation.php" method="post">
                <div>
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div>
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="remember-row">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Remember Me</label>
                </div>

                <button type="submit">Login</button>
            </form>
        </div>
<div class="box">
    <h2>Registration Here</h2>
    <form action="registration.php" method="post">
        <div><label>Username</label><input type="text" name="username" required></div>
        
        <div><label>Password</label><input type="password" name="password" required></div>

        <div>
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <div><label>Email</label><input type="email" name="email" required></div>
        <div><label>Phone Number</label><input type="text" name="phone" required></div>
        <button type="submit">Register</button>
    </form>
</div>

    </div>

</body>
</html>