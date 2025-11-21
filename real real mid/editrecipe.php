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

<html lang="vi">
<head>
    <title>Edit Recipe</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 80%;
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            background-color: #c869f0; /* Màu tím từ home.php */
            color: white;
            padding: 10px 20px;
            border-radius: 15px;
            font-weight: bold;
        }
        .back-link:hover {
            background-color: #a055c0;
        }

        h2 {
            color: #333;
            margin-top: 0;
            font-size: 2em;
            text-align: center;
            margin-bottom: 30px;
        }
        
        h3 {
            color: #ff5c5c; /* Màu header ở home */
            border-bottom: 2px solid #ff9856;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        /* Style cho các nhóm form (label + input) */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* Quan trọng để padding không làm vỡ layout */
            font-size: 1em;
        }

        .form-group textarea {
            resize: vertical; /* Cho phép kéo chiều cao */
            min-height: 120px;
        }
        
        .form-group input[type="file"] {
            margin-top: 5px;
        }
        
        .current-image {
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 5px;
        }

        /* Style cho danh sách nguyên liệu */
        #ingredient-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .ingredient-item {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .ingredient-item input[type="text"] {
            flex: 1; /* Tự động co giãn */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* Style cho các nút */
        button {
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 0.95em;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        
        /* Nút Thêm nguyên liệu */
        .btn-add {
            background-color: #ff9856; /* Màu cam */
            margin-top: 15px;
        }
        .btn-add:hover {
            background-color: #e08040;
        }

        /* Nút Xóa nguyên liệu */
        .btn-remove {
            background-color: #ff5c5c; /* Màu đỏ */
            padding: 8px 12px; /* Nhỏ hơn một chút */
            font-size: 0.9em;
        }
        .btn-remove:hover {
            background-color: #d94848;
        }
        
        /* Nút Update chính */
        .btn-update {
            background-color: #c869f0; /* Màu tím */
            padding: 14px 28px;
            font-size: 1.1em;
            display: block; /* Tự xuống hàng và căn giữa */
            width: 100%;
            max-width: 300px;
            margin: 30px auto 0 auto;
        }
        .btn-update:hover {
            background-color: #a055c0;
        }
    </style>
    </head>
<body>

<div class="container">

    <a href="home.php" class="back-link">&larr; Back to Home</a>

    <form action="" method="post" enctype="multipart/form-data">
        <h2>Edit Recipe</h2>
        
        <div class="form-group">
            <label for="recipe_name">Recipe Name:</label>
            <input type="text" id="recipe_name" name="recipe_name" value="<?php echo $recipe_name; ?>">
        </div>
        
        <div class="form-group">
            <label>Current Image:</label><br>
            <img src="<?php echo $recipe_image; ?>" class="current-image">
        </div>
        
        <div class="form-group">
            <label for="recipe_image">Change Image:</label>
            <input type="file" id="recipe_image" name="recipe_image">
        </div>
        
        <div class="form-group">
            <label for="cook_time">Cooking Time (minutes):</label>
            <input type="number" id="cook_time" name="cook_time" value="<?php echo $cook_time; ?>">
        </div>
        
        <div class="form-group">
            <label for="instructions">Instructions:</label>
            <textarea id="instructions" name="instructions" rows="8"><?php echo $instructions; ?></textarea>
        </div>
        
        <h3>Ingredients</h3>
        <div id="ingredient-list">
            <?php while($ing = mysqli_fetch_array($ing_res)): ?>
            <div class="ingredient-item">
                <input type="text" name="ingredient_name[]" value="<?php echo $ing['ingredient_name']; ?>" placeholder="Tên nguyên liệu">
                <input type="text" name="quantity[]" value="<?php echo $ing['quantity']; ?>" placeholder="Số lượng">
                <button type="button" class="btn-remove" onclick="removeIngredient(this)">Remove</button>
            </div>
            <?php endwhile; ?>
        </div>
        <button type="button" class="btn-add" onclick="addIngredient()">+ Add Ingredient</button>
        
        <br><br>
        <button type="submit" name="update" class="btn-update">Update</button>
    </form>
    
</div> 
<script>
function addIngredient() {
    const container = document.getElementById('ingredient-list');
    const div = document.createElement('div');
    div.className = 'ingredient-item';
    
    div.innerHTML = 
        `<input type="text" name="ingredient_name[]" placeholder="Ingredient name">
         <input type="text" name="quantity[]" placeholder="Quantity">
         <button type="button" class="btn-remove" onclick="removeIngredient(this)">Remove</button>`;
         
    container.appendChild(div);
}

function removeIngredient(button) {
    button.parentElement.remove();
}
</script>
</body>

<?php
if (isset($_POST["update"])) {
    $new_image = $recipe_image; 
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] == 0) {
        $target_dir = "uploads/";
        $new_image = $target_dir . basename($_FILES["recipe_image"]["name"]);
        move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $new_image);
    }
    
    mysqli_query($con, "UPDATE recipes 
        SET recipe_name='$_POST[recipe_name]',
            recipe_image='$new_image',
            instructions='$_POST[instructions]',
            cook_time='$_POST[cook_time]'
        WHERE recipe_id=$id");

    mysqli_query($con, "DELETE FROM ingredients WHERE recipe_id=$id");   
    if (!empty($_POST['ingredient_name'])) {
        foreach($_POST['ingredient_name'] as $i => $ingredient) {
            $quantity = $_POST['quantity'][$i];
            if ($ingredient && $quantity) {
                mysqli_query($con, "INSERT INTO ingredients (recipe_id, ingredient_name, quantity) 
                                    VALUES ('$id', '$ingredient', '$quantity')");
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
</html>