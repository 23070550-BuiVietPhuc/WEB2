<?php
include "connection.php";

// Lấy thông tin user (Dùng cho cả Login và Check Cookie)
function getUserByUsername($username) {
    global $con;
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    }
    return null;
}

// Kiểm tra đăng nhập
function checkLoginCredentials($username, $password) {
    $user = getUserByUsername($username);
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

// Kiểm tra tồn tại user (cho đăng ký)
function isUserExists($username, $email) {
    global $con;
    $stmt = $con->prepare("SELECT username FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

// Tạo user mới
function createUser($username, $password, $email, $phone) {
    global $con;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $con->prepare("INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);
    
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}
?>