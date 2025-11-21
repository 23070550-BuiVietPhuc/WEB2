<?php
include "connection.php";

$id = $_GET["id"];

$res = mysqli_query($con, "SELECT * FROM recipes WHERE recipe_id=$id");
while ($row = mysqli_fetch_array($res)) {
    $recipe_name = $row["recipe_name"];
    $recipe_image = $row["recipe_image"];
    $cook_time = $row["cook_time"];
    $instructions = $row["instructions"];
}

$ing_res = mysqli_query($con, "SELECT * FROM ingredients WHERE recipe_id=$id");
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>View Recipe</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        h2 {
            color: #ff5c5c;
        }

        .cook-time {
            background: #ff9856;
            color: white;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
            margin: 10px 0;
        }

        .ingredients, .instructions {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            line-height: 1.6;
        }

        .back-btn {
            display: inline-block;
            background-color: #c869f0;
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .back-btn:hover {
            background-color: #a055c0;
        }
    </style>
</head>
<body>
<div class="container">
    <a href="home.php" class="back-btn">&larr; Back to Home</a>

    <h1><?php echo $recipe_name; ?></h1>
    <img src="<?php echo $recipe_image; ?>" alt="Recipe Image">

    <div class="cook-time">
        ⏱ Cooking Time: <?php echo $cook_time; ?> minutes
    </div>

    <h2>Ingredients</h2>
    <div class="ingredients">
        <?php 
        if (mysqli_num_rows($ing_res) > 0) {
            while($ing = mysqli_fetch_array($ing_res)) {
                echo "- " . htmlspecialchars($ing['ingredient_name']) . ": " . htmlspecialchars($ing['quantity']) . "<br>";
            }
        } else {
            echo "No ingredients listed.";
        }
        ?>
    </div>

    <h2>Instructions</h2>
    <div class="instructions">
    <?php echo nl2br(htmlspecialchars($instructions)); ?>

    </div>
</div>

</body>
</html>
