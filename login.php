<?php
session_start();
include "connect.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        // Đăng nhập thành công
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        // Nếu là admin thì vào trang admin
        if ($user["role"] === "admin") {
            header("Location: admin_index.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $message = "❌ Sai tài khoản hoặc mật khẩu!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng nhập</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body {
    font-family: Arial, sans-serif;
    background: url('hinh-nen-game.jpg') no-repeat center center fixed;
    background-size: cover;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.container {
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    text-align: center;
    width: 350px;
}
.container img {
    width: 100px;
    margin-bottom: 15px;
}
h2 {
    color: #3498db;
    margin-bottom: 15px;
}
input, button {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    background: #27ae60;
    color: white;
    border: none;
    cursor: pointer;
}
button:hover {
    background: #219150;
}
a {
    color: #3498db;
}
</style>
</head>
<body>
<div class="container">
    <img src="hinh-nen-gaming-2.jpg" alt="Logo">
    <h2>Đăng nhập</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
    <p style="color:red;"><?= $message ?></p>
    <p><a href="register.php">Chưa có tài khoản? Đăng ký</a></p>
</div>
</body>
</html>
