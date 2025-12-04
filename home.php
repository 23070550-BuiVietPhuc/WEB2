<?php
session_start();
include "connection.php";

// --- XỬ LÝ GHI NHỚ ĐĂNG NHẬP (COOKIE) ---
if (!isset($_SESSION['username'])) {
    // Nếu chưa có session nhưng có cookie
    if (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {
        $cookie_user = $_COOKIE['username'];
        $cookie_token = $_COOKIE['token']; 

        // Kiểm tra database
        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $cookie_user);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            // So khớp token trong cookie với mật khẩu trong DB
            // LƯU Ý: Đây là một LỖ HỔNG BẢO MẬT (dùng mật khẩu thô làm token).
            // Bạn nên dùng cột 'remember_token' được mã hóa bằng thuật toán băm (hash)
            // hoặc dùng cơ chế Refresh Token phức tạp hơn.
            if ($cookie_token === $row['password']) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
            }
        }
    }
}

// Nếu sau khi check cookie vẫn chưa đăng nhập -> Đẩy về login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
// Lấy user_id an toàn từ session
$user_id = $_SESSION['user_id'];

// Lấy danh sách công thức
// LƯU Ý BẢO MẬT: Nên dùng prepared statement cho cả truy vấn này để an toàn tuyệt đối
// Ví dụ: $stmt = $con->prepare("SELECT * FROM recipes WHERE user_id = ?"); $stmt->bind_param("i", $user_id); $stmt->execute(); $result = $stmt->get_result();
$result = mysqli_query($con, "SELECT * FROM recipes WHERE user_id = '$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Recipes</title>
    <style>
        /* Giữ nguyên CSS cũ của bạn */
        body { font-family: Arial, sans-serif; background-color: #fafafa; margin: 0; padding: 0; }
        .topbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background-color: #f5b66fff; 
            padding: 15px 30px; 
            border-radius: 0 0 25px 25px; 
            color: white; 
        }
        .topbar .title { 
            font-size: 22px; 
            font-weight: bold; 
            flex-grow: 1; /* Cho phép tiêu đề mở rộng */
            text-align: center; /* Giữ tiêu đề ở giữa */
        }
        
        /* CSS MỚI: Định dạng ô tìm kiếm */
        .search-form {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .search-input {
            padding: 8px 15px;
            border: none;
            border-radius: 15px;
            width: 250px;
            font-size: 16px;
            color: #333;
        }
        /* KẾT THÚC CSS MỚI */

        .user-box { display: flex; align-items: center; gap: 10px; background-color: #ff9856; padding: 8px 15px; border-radius: 15px; }
        .user-box span { font-weight: bold; }
        .user-box a { text-decoration: none; color: white; background-color: #e44e10; padding: 6px 12px; border-radius: 10px; font-size: 14px; }
        .user-box a:hover { background-color: #c63e0a; }
        .header { display: flex; justify-content: space-between; align-items: center; margin: 30px auto; width: 80%; }
        .header div { font-size: 20px; font-weight: bold; }
        .header a { text-decoration: none; color: white; background-color: #c869f0; padding: 12px 25px; border-radius: 15px; font-weight: bold; }
        .recipe-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; width: 80%; margin: 0 auto 50px auto; }
        .recipe-card { background-color: #ff9856; border-radius: 20px; padding: 15px; display: flex; flex-direction: column; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .recipe-card img { width: 100%; height: 200px; border-radius: 10px; object-fit: cover; }
        .recipe-info { text-align: left; margin-top: 10px; width: 100%; }
        .recipe-info h3 { margin: 0 0 5px; }
        .recipe-buttons { margin-top: 10px; }
        .recipe-buttons a { background-color: #888; color: white; text-decoration: none; padding: 8px 14px; border-radius: 10px; margin: 0 5px; display: inline-block; }
        .recipe-buttons a:hover { background-color: #555; }
    </style>
</head>
<body>

    <div class="topbar">
        <form action="search_results.php" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search recipes..." class="search-input">
            <button type="submit" style="display:none;"></button>
        </form>
        
        <div class="title">My Recipe Book</div>
        <div class="user-box">
            <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="header">
        <div>Your Recipes</div>
        <a href="newrecipe.php">+ Create New</a>
    </div>

    <div class="recipe-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Đảm bảo đường dẫn ảnh không bị lỗi
                $img = !empty($row['recipe_image']) ? htmlspecialchars($row['recipe_image']) : 'noimage.png';
                echo "
                <div class='recipe-card'>
                    <img src='{$img}'>
                    <div class='recipe-info'>
                        <h3>" . htmlspecialchars($row['recipe_name']) . "</h3>
                        <p>" . htmlspecialchars($row['cook_time']) . " min</p>
                    </div>
                    <div class='recipe-buttons'>
                        <a href='editrecipe.php?id={$row['recipe_id']}'>Edit</a>
                        <a href='deleterecipe.php?id={$row['recipe_id']}' onclick='return confirm(\"Delete this recipe?\");'>Delete</a>
                        <a href='view.php?id={$row['recipe_id']}'>View Details</a>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p>You have no recipes yet.</p>";
        }
        ?>
    </div>

</body>
</html>