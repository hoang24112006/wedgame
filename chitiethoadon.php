<?php
session_start();
include "connect.php";

// Chỉ admin được xem
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    echo "<h3 style='color:red;text-align:center;margin-top:100px;'>❌ Lỗi: Không có ID hóa đơn hợp lệ được game :3</h3>";
    exit;
}

$id = (int)$_GET["id"];

// --- Truy vấn chi tiết hóa đơn ---
$sql = "
SELECT 
    o.id AS order_id, 
    u.username, 
    t.name AS game_name, 
    o.quantity, 
    o.total_price, 
    o.order_date
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN game t ON o.game_id = t.id
WHERE o.id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(" Lỗi SQL: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$order = $res->fetch_assoc();

if (!$order) {
    echo "<h3 style='color:red;text-align:center;margin-top:100px;'>❌ Không tìm thấy hóa đơn có ID = $id</h3>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Chi tiết hóa đơn</title>
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
    .admin-container {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        text-align: center;
        width: 420px;
        animation: fadeIn 0.8s ease;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    h2 { color: #3498db; margin-bottom: 20px; }
    p { font-size: 16px; color: #333; margin: 8px 0; }
    a {
        display: inline-block;
        text-decoration: none;
        color: white;
        background: #27ae60;
        padding: 10px 20px;
        border-radius: 8px;
        transition: 0.3s;
        margin-top: 20px;
    }
    a:hover { background: #219150; }
</style>
</head>
<body>
<div class="admin-container">
    <h2> Chi tiết hóa đơn #<?= $order["order_id"] ?></h2>
    <p><b> Khách hàng:</b> <?= htmlspecialchars($order["username"]) ?></p>
    <p><b> Tên truyện:</b> <?= htmlspecialchars($order["game_name"]) ?></p>
    <p><b> Số lượng:</b> <?= $order["quantity"] ?></p>
    <p><b> Tổng tiền:</b> <?= number_format($order["total_price"]) ?>₫</p>
    <p><b> Ngày đặt:</b> <?= $order["order_date"] ?></p>
    <a href="danhsachhoadon.php">⬅ Quay lại danh sách hóa đơn</a>
</div>
</body>
</html>
