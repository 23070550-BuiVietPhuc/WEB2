<?php
//creating a database connection - $con is a variable use for just connection class
$con=mysqli_connect("localhost","root","") or die(mysqli_error($con));
mysqli_select_db($con,"recipe_share_db") or die(mysqli_error($con));

