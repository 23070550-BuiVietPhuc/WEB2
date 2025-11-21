<?php
session_start();
include "connection.php";

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$result = mysqli_query($con,"SELECT user_id FROM users WHERE username = '$username'");
$user = mysqli_fetch_assoc($result);
$user_id = $user['user_id'];
$result = mysqli_query($con, "SELECT * FROM recipes WHERE user_id = '$user_id'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
        }

        /* Header bar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #ff5c5c;
            padding: 15px 30px;
            border-radius: 0 0 25px 25px;
            color: white;
        }

        .topbar .title {
            font-size: 22px;
            font-weight: bold;
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #ff9856;
            padding: 8px 15px;
            border-radius: 15px;
        }

        .user-box span {
            font-weight: bold;
        }

        .user-box a {
            text-decoration: none;
            color: white;
            background-color: #e44e10;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        .user-box a:hover {
            background-color: #c63e0a;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 30px auto;
            width: 80%;
        }

        .header div {
            font-size: 20px;
            font-weight: bold;
        }

        .header a {
            text-decoration: none;
            color: white;
            background-color: #c869f0;
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: bold;
        }

        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            width: 80%;
            margin: 0 auto 50px auto;
        }

        .recipe-card {
            background-color: #ff9856;
            border-radius: 20px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .recipe-card img {
            width: 100%;
            height: 200px;
            border-radius: 10px;
            object-fit: cover;
        }

        .recipe-info {
            text-align: left;
            margin-top: 10px;
            width: 100%;
        }

        .recipe-info h3 {
            margin: 0 0 5px;
        }

        .recipe-buttons {
            margin-top: 10px;
        }

        .recipe-buttons a {
            background-color: #888;
            color: white;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 10px;
            margin: 0 5px;
            display: inline-block;
        }

        .recipe-buttons a:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

    <!-- Top orange bar -->
    <div class="topbar">
        <div class="title">My Recipe Book</div>
        <div class="user-box">
            <span>Welcome, <?php echo $username; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Recipes header -->
    <div class="header">
        <div>Your Recipes</div>
        <a href="newrecipe.php">+ Create New</a>
    </div>

    <!-- Recipe list -->
    <div class="recipe-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $img = !empty($row['recipe_image']) ? $row['recipe_image'] : 'noimage.png';
                echo "
                <div class='recipe-card'>
                    <img src='{$img}'>
                    <div class='recipe-info'>
                        <h3>{$row['recipe_name']}</h3>
                        <p>{$row['cook_time']} min</p>
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
