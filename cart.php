<?php
session_start();
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

// Xóa sản phẩm
if (isset($_GET["remove"])) {
    $remove_name = $_GET["remove"];
    foreach ($_SESSION["cart"] as $key => $item) {
        if ($item["name"] === $remove_name) {
            unset($_SESSION["cart"][$key]);
            break;
        }
    }
    $_SESSION["cart"] = array_values($_SESSION["cart"]); // sắp xếp lại mảng
    header("Location: cart.php");
    exit;
}

// Nếu nhấn thanh toán
if (isset($_POST["checkout"])) {
    if (empty($_SESSION["username"])) {
        echo "<script>alert('Vui lòng đăng nhập để thanh toán!'); window.location.href='login.php';</script>";
        exit;
    } else {
        header("Location: thanh toán.php"); // file xử lý thanh toán
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>🛒 Giỏ hàng</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f8f8f8;
    padding: 20px;
}
.container {
    width: 90%;
    margin: auto;
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: center;
}
img {
    width: 80px;
    border-radius: 8px;
}
button, a {
    padding: 8px 15px;
    border: none;
    border-radius: 6px;
    text-decoration: none;
    cursor: pointer;
}
.delete {
    background: #e74c3c;
    color: white;
}
.delete:hover {
    background: #c0392b;
}
.pay {
    background: #27ae60;
    color: white;
}
.pay:hover {
    background: #1e8449;
}
h2 {
    text-align: center;
    color: #3498db;
}
.empty {
    text-align: center;
    padding: 50px;
    color: gray;
}
</style>
</head>
<body>
<div class="container">
<h2>Giỏ hàng của bạn</h2>

<?php if (empty($_SESSION["cart"])): ?>
    <p class="empty">Chưa có sản phẩm nào trong giỏ hàng.</p>
<?php else: ?>
<table>
    <tr>
        <th>Ảnh</th>
        <th>Tên game</th>
        <th>Giá</th>
        <th>Số lượng</th>
        <th>Thành tiền</th>
        <th>Xóa</th>
    </tr>
    <?php 
    $tong = 0;
    foreach ($_SESSION["cart"] as $item): 
        $thanhtien = $item["price"] * $item["quantity"];
        $tong += $thanhtien;
    ?>
    <tr>
        <td><img src="<?= htmlspecialchars($item["image"]) ?>" alt="<?= htmlspecialchars($item["name"]) ?>"></td>
        <td><?= htmlspecialchars($item["name"]) ?></td>
        <td><?= number_format($item["price"]) ?>đ</td>
        <td><?= $item["quantity"] ?></td>
        <td><?= number_format($thanhtien) ?>đ</td>
        <td><a href="cart.php?remove=<?= urlencode($item["name"]) ?>" class="delete">Xóa</a></td>
    </tr>
    <?php endforeach; ?>
</table>

<h3 style="text-align:right; margin-top:20px;">Tổng tiền: <span style="color:red;"><?= number_format($tong) ?>đ</span></h3>

<form method="POST" style="text-align:right;">
    <button type="submit" name="checkout" class="pay">Thanh toán</button>
</form>
<?php endif; ?>

<a href="index.php" style="display:inline-block; margin-top:20px;">⬅ Tiếp tục mua hàng</a>
</div>
</body>
</html>
