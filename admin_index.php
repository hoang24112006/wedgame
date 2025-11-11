<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Trang quản trị Admin</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #bbbb2dff, #6dd5fa);
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
        box-shadow: 0 0 20px rgba(162, 12, 12, 0.3);
        text-align: white;
        width: 400px;
        animation: fadeIn 0.8s ease;
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    .admin-container img {
        width: 100px;
        margin-bottom: 15px;
    }
    h2 {
        color: #6d44afff;
        margin-bottom: 10px;
    }
    p {
        font-size: 16px;
        color: #333;
    }
    .menu a {
        display: block;
        text-decoration: none;
        color: white;
        background: #27ae60;
        padding: 12px;
        margin: 8px 0;
        border-radius: 8px;
        transition: 0.3s;
    }
    .menu a:hover {
        background: #219150;
    }
    .logout {
        background: #e74c3c !important;
    }
    .logout:hover {
        background: #c0392b !important;
    }
</style>
</head>
<body>

<div class="admin-container">
    <h2> Quản trị hệ thống của bạn</h2>
    <p>Xin chào, <b><?= htmlspecialchars($_SESSION["username"]) ?></b> 👋</p>
    <div class="menu">
         <button onclick="location.href='danhsachgame.php'"> Danh sách game</button>
        <button onclick="location.href='themgame.php'"> Thêm game</button>
        <button onclick="location.href='danhsachhoadon.php'"> Xem hóa đơn</button>
        <button onclick="location.href='chitiethoadon.php'"> Chi tiết hóa đơn</button>
        <a href="logout.php" class="logout"> log out</a>
    </div>
</div>

</body>
</html>
