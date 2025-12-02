<?php
session_start();
include "connection.php";
$user_id = $_SESSION['user_id'];
?>


<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Add New Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fafafa;
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .container {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: auto;
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

        h2 {
            color: #ff5c5c;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #c869f0;
            border: none;
            border-radius: 12px;
        }
        .btn-primary:hover {
            background-color: #a055c0;
        }
        .btn-warning {
            background-color: #ff9856;
            border: none;
            border-radius: 12px;
        }
        .btn-warning:hover {
            background-color: #ff7f24;
        }
        .btn-danger {
            border-radius: 12px;
        }
        .ingredient-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .ingredient-item input {
            flex: 1;
        }
        .ingredient-item button {
            flex-shrink: 0;
        }
    </style>
</head>
<body>

<div class="container">
        <a href="home.php" class="back-btn">&larr; Back to Home</a>
    <h2>Add New Recipe</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="recipe_name" class="form-label">Recipe Name:</label>
            <input type="text" class="form-control" name="recipe_name" placeholder="Enter recipe name">
        </div>

        <div class="mb-3">
            <label for="recipe_image" class="form-label">Recipe Image:</label>
            <input type="file" class="form-control" name="recipe_image" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="cook_time" class="form-label">Cooking Time (minutes):</label>
            <input type="number" class="form-control" name="cook_time" placeholder="Enter cooking time">
        </div>

        <div class="mb-3">
            <label for="instructions" class="form-label">Instructions:</label>
            <textarea class="form-control" name="instructions" rows="5" placeholder="Enter cooking instructions"></textarea>
        </div>

        <h3 class="mt-4 mb-3 text-center text-warning">Ingredients</h3>

        <div id="ingredient-list">
            <div class="ingredient-item">
                <input type="text" class="form-control" name="ingredient_name[]" placeholder="Ingredient name">
                <input type="text" class="form-control" name="quantity[]" placeholder="Quantity">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeIngredient(this)">Remove</button>
            </div>
        </div>

        <button type="button" class="btn btn-warning mt-2" onclick="addIngredient()">+ Add Ingredient</button>

        <div class="text-center mt-4">
            <button type="submit" name="insert" class="btn btn-primary px-4">Insert</button>
        </div>
    </form>
</div>

<script>
function addIngredient() {
    const container = document.getElementById('ingredient-list');
    const div = document.createElement('div');
    div.className = 'ingredient-item';
    div.innerHTML = 
       `<input type="text" class="form-control" name="ingredient_name[]" placeholder="Ingredient name">
        <input type="text" class="form-control" name="quantity[]" placeholder="Quantity">
        <button type="button" class="btn btn-danger btn-sm" onclick="removeIngredient(this)">Remove</button>`;
    container.appendChild(div);
}

function removeIngredient(button) {
    button.parentElement.remove();
}
</script>

<?php
if (isset($_POST["insert"])) {
    $recipe_name = $_POST['recipe_name'];
    $instructions = $_POST['instructions'];
    $cook_time = $_POST['cook_time'];
    $recipe_image = '';

    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
        $target_dir = "uploads/";
        $recipe_image = $target_dir . $_FILES["recipe_image"]["name"];
        move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $recipe_image);
    }

    mysqli_query($con, "INSERT INTO recipes (user_id, recipe_name, recipe_image, instructions, cook_time) 
                        VALUES ('$user_id', '$recipe_name', '$recipe_image', '$instructions', '$cook_time')");
    $recipe_id = mysqli_insert_id($con);


    if (!empty($_POST['ingredient_name'])) {
        foreach($_POST['ingredient_name'] as $i => $ingredient) {
            $quantity = $_POST['quantity'][$i];
            if ($ingredient && $quantity) {
                mysqli_query($con, "INSERT INTO ingredients (recipe_id, ingredient_name, quantity) 
                                    VALUES ('$recipe_id', '$ingredient', '$quantity')");
            }
        }
    }
    ?>
    <script type="text/javascript">
        window.location = "home.php";
    </script>
    <?php
}
?>
</body>
</html>
