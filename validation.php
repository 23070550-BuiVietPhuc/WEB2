
<?php
session_start();

/* connect to database check user*/
$con=mysqli_connect('localhost','root',''); 
mysqli_select_db($con,'recipe_share_db');

/* create variables to store data */
$username =$_POST['username'];
$password =$_POST['password'];

$result = mysqli_query($con,"select * from users where username='$username'AND password='$password'");
$num =mysqli_num_rows($result);

if($num==1){
    
    $user_data = mysqli_fetch_array($result); 
    $_SESSION['username'] =$user_data['username'];
    $_SESSION['user_id'] =$user_data['user_id'];
    
    header('location:home.php');
}else{
    header('location:login.php');
}
?>