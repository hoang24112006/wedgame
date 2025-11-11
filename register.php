<?php
session_start();
include "connect.php";
$message="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $username=$_POST["username"];
    $email=$_POST["email"];
    $password=password_hash($_POST["password"],PASSWORD_DEFAULT);
    $stmt=$conn->prepare("INSERT INTO users(username,password,email) VALUES(?,?,?)");
    $stmt->bind_param("sss",$username,$password,$email);
    if($stmt->execute()) $message="Đăng ký thành công! <a href='login.php'>Đăng nhập</a>";
    else $message="Tài khoản đã tồn tại!";
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký</title>
<style>
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

.overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.container {
    position: relative;
    z-index: 2;
    width: 400px;
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    text-align: center;
}

.container img {
    width: 100px;
    margin-bottom: 15px;
}

h2 {
    color: #3498db;
    margin-bottom: 10px;
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

</style>
</head>
<body>
<div class="container">
<img src="hinh-nen-gaming-2.jpg" alt="Logo">
<h2>Đăng ký tài khoản</h2>
<form method="POST">
<input type="text" name="username" placeholder="Tên đăng nhập" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Mật khẩu" required>
<button type="submit">Đăng ký</button>
</form>
<p><?= $message ?></p>
<p><a href="login.php">Đã có tài khoản? Đăng nhập</a></p>
</div>
</body>
</html>
