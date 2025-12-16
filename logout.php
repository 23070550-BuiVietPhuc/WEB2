<?php
session_start();
session_destroy();

if (isset($_COOKIE['username'])) {
    setcookie('username', '', time() - 3600, '/');
}
if (isset($_COOKIE['token'])) {
    setcookie('token', '', time() - 3600, '/');
}

header('location:login.php');
exit;
?>