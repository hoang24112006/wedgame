<?php
session_start();
include "connect.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = (int)$_POST["price"];

    // ✅ Thư mục lưu hình
    $target_dir = "images/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = "images/" . $image_name;
        $stmt = $conn->prepare("INSERT INTO game (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $price, $image_path);

        if ($stmt->execute()) {
            $message = "✅ Thêm games thành công!";
        } else {
            $message = "❌ Lỗi thêm games vào database!";
        }
    } else {
        $message = "❌ Lỗi upload hình!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm games</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #2980b9, #6dd5fa);
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        text-align: center;
        width: 400px;
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
        text-decoration: none;
    }
</style>
</head>
<body>
<div class="container">
    <h2> Thêm game mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Tên games" required><br>
        <input type="number" name="price" placeholder="Giá" required><br>
        <input type="file" name="image" required><br>
        <button type="submit">Thêm games</button>
    </form>
    <p style="color: red;"><?= $message ?></p>
    <p><a href="danhsachgame.php">⬅ Quay lại danh sách games</a></p>
</div>
</body>
</html>
