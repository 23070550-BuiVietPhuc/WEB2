<?php
session_start();
include "connection.php";

// --- COOKIE LOGIN ---
if (!isset($_SESSION['username'])) {
    if (isset($_COOKIE['username']) && isset($_COOKIE['token'])) {
        $cookie_user = $_COOKIE['username'];
        $cookie_token = $_COOKIE['token']; 

        $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $cookie_user);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if ($cookie_token === $row['password']) {
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];
            }
        }
    }
}

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

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

        /* TOPBAR */
        .topbar { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            background-color: #f5b66fff; 
            padding: 15px 30px; 
            border-radius: 0 0 25px 25px; 
            color: white; 
            box-shadow: 0 3px 10px rgba(0,0,0,0.10);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar .title { 
            font-size: 24px; 
            font-weight: bold; 
            flex-grow: 1;
            text-align: center;
            letter-spacing: 1px;
        }

        /* SEARCH BAR */
        .search-form {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .search-input {
            padding: 10px 18px;
            border: none;
            border-radius: 20px;
            width: 260px;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: 0.2s;
        }
        .search-input:focus {
            box-shadow: 0 0 8px rgba(255,255,255,0.7);
        }

        /* USER BOX */
        .user-box { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            background-color: #ff9856; 
            padding: 8px 15px; 
            border-radius: 20px; 
        }
        .user-box span { font-weight: bold; }
        .user-box a { 
            text-decoration: none; 
            color: white; 
            background-color: #e44e10; 
            padding: 7px 14px; 
            border-radius: 12px; 
            font-size: 14px; 
        }
        .user-box a:hover { background-color: #c63e0a; }

        /* HEADER */
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin: 35px auto; 
            width: 80%; 
        }
        .header div { 
            font-size: 24px; 
            font-weight: bold;
        }
        .header a { 
            text-decoration: none; 
            color: white; 
            background-color: #c869f0; 
            padding: 12px 25px; 
            border-radius: 15px; 
            font-weight: bold;
            transition: 0.2s;
        }
        .header a:hover {
            background-color: #b452dd;
        }

        /* GRID */
        .recipe-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 25px; 
            width: 80%; 
            margin: 0 auto 60px auto; 
        }

        /* CARD */
        .recipe-card { 
            background-color: white; 
            border-radius: 20px; 
            padding: 15px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); 
            transition: 0.25s; 
        }
        .recipe-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .recipe-card img { 
            width: 100%; 
            height: 200px; 
            border-radius: 12px; 
            object-fit: cover; 
        }

        .recipe-info { 
            text-align: left; 
            margin-top: 12px; 
            width: 100%; 
        }
        .recipe-info h3 { 
            margin: 0 0 5px; 
            font-size: 20px; 
        }
        .recipe-info p {
            margin: 0;
            color: #444;
            font-size: 15px;
        }

        .recipe-buttons { 
            margin-top: 15px; 
        }
        .recipe-buttons a { 
            background-color: #888; 
            color: white; 
            text-decoration: none; 
            padding: 8px 14px; 
            border-radius: 10px; 
            margin: 0 5px; 
            display: inline-block; 
            transition: 0.2s;
        }
        .recipe-buttons a:hover { 
            background-color: #555; 
        }

        .empty-box {
            text-align: center;
            margin-top: 100px;
            color: #666;
            font-size: 18px;
        }
        .empty-box img {
            width: 180px;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        .empty-box a {
            display: inline-block;
            margin-top: 10px;
            background-color: #c869f0;
            padding: 12px 25px;
            border-radius: 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .empty-box a:hover {
            background-color: #b452dd;
        }
    </style>

</head>
<body>

    <div class="topbar">

        <!-- NEW SEARCH BAR (NO FORM) -->
        <div class="search-form">
            <input type="text" id="searchInput" placeholder="Search recipes..." class="search-input">
        </div>

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

    <?php if (mysqli_num_rows($result) > 0): ?>

        <div class="recipe-grid" id="recipeContainer">
        <?php while ($row = mysqli_fetch_assoc($result)): 
            $img = !empty($row['recipe_image']) ? htmlspecialchars($row['recipe_image']) : 'noimage.png';
        ?>
            <div class="recipe-card">
                <img src="<?= $img ?>">
                <div class="recipe-info">
                    <h3><?= htmlspecialchars($row['recipe_name']) ?></h3>
                    <p><?= htmlspecialchars($row['cook_time']) ?> min</p>
                </div>

                <div class="recipe-buttons">
                    <a href="editrecipe.php?id=<?= $row['recipe_id'] ?>">Edit</a>
                    <a href="deleterecipe.php?id=<?= $row['recipe_id'] ?>" onclick="return confirm('Delete this recipe?');">Delete</a>
                    <a href="view.php?id=<?= $row['recipe_id'] ?>">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
        </div>

    <?php else: ?>

        <div class="empty-box">
            <img src="no_recipe.png" alt="">
            <div>You have no recipes yet.</div>
            <a href="newrecipe.php">Create your first recipe</a>
        </div>

    <?php endif; ?>


<!-- 🔍 LIVE SEARCH SCRIPT -->
<script>
document.getElementById("searchInput").addEventListener("keyup", function() {
    let keyword = this.value.toLowerCase();
    let cards = document.querySelectorAll(".recipe-card");

    cards.forEach(card => {
        let name = card.querySelector("h3").innerText.toLowerCase();
        if (name.includes(keyword)) {
            card.style.display = "block";
        } else {
            card.style.display = "none";
        }
    });
});
</script>

</body>
</html>