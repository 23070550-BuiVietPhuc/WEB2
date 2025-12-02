<?php
include "connection.php";

$id = $_GET["id"];
mysqli_query($con, "DELETE FROM ingredients WHERE recipe_id = $id");
mysqli_query($con, "DELETE FROM recipes WHERE recipe_id = $id");

header("Location: home.php");
exit;
?>

<script type="text/javascript">
    window.location = "home.php";
</script>
