<?php
session_start();
$con=mysqli_connect('localhost','root','');
mysqli_select_db($con,"recipe_share_db");

/* create variables to store data */
$username =$_POST['username'];
$password =$_POST['password'];
$email =$_POST['email'];
$phone =$_POST['phone'];


$result = mysqli_query($con,"select * from users where username='$username'");

$num =mysqli_num_rows($result);
if($num==1){
    echo "Username Exists";
}else{
    mysqli_query($con,"insert into users(username,password,email,phone) 
                            values ('$username','$password','$email','$phone')");
     echo "Resgistration Successful";
}
