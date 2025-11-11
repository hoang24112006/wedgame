<?php
session_start();
include "connect.php";

// Nếu chưa đăng nhập hoặc giỏ hàng trống thì quay lại trang chủ
if (!isset($_SESSION["username"]) || empty($_SESSION["cart"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["username"];

// Lấy ID người dùng theo username
$stmt_user = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    die("Không tìm thấy người dùng.");
}

$user_id = $user["id"];

// ✅ Tạo bảng orders nếu chưa có
$conn->query("
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (game_id) REFERENCES game(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

$sql = "INSERT INTO orders (user_id, game_id, quantity, total_price, order_date) 
        VALUES (?, ?, ?, ?, NOW())";
$stmt_order = $conn->prepare($sql);

foreach ($_SESSION["cart"] as $item) {
    $game_id = $item["id"];
    $quantity = $item["quantity"];
    $price = $item["price"];
    $total_price = $price * $quantity;

    $stmt_order->bind_param("iiid", $user_id, $game_id, $quantity, $total_price);
    $stmt_order->execute();
}

// Xóa giỏ hàng sau khi thanh toán
unset($_SESSION["cart"]);
?>
    
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán thành công</title>
<style>
body { font-family: Arial; background:#f5f5f5; }
.box {
    margin:120px auto;
    width:400px;
    background:white;
    padding:50px 70px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}
.box h2 { color:#27ae60; }
.box a {
    display:inline-block;
    margin-top:20px;
    padding:10px 20px;
    background:#e74c3c;
    color:white;
    border-radius:6px;
    text-decoration:none;
}
</style>
</head>
<body>
<div class="box">
<h2>✅ Thanh toán thành công!</h2>
<p>Cảm ơn bạn đã mua game của shop $</p>
<p>Đơn hàng của bạn đã được ghi nhận.</p>
<p> hẹn bạn sớm quay lại hehe</p>
<a href="index.php">⬅ Quay lại cửa hàng</a>
</div>
</body>
</html>
