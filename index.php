<?php
session_start();
include "connect.php";

$search = isset($_GET['search']) ? $_GET['search'] : "";

$stmt = $conn->prepare("SELECT * FROM game WHERE name LIKE ?");
if(!$stmt) die("Lỗi SQL: ".$conn->error);
$param="%$search%";
$stmt->bind_param("s",$param);
$stmt->execute();
$result=$stmt->get_result();

// Giỏ hàng tổng
$total_qty=$total_price=0;
if(isset($_SESSION["cart"])){
    foreach($_SESSION["cart"] as $item){
        $total_qty += $item["quantity"];
        $total_price += $item["price"]*$item["quantity"];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title> gameming Shop</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:Arial, sans-serif;
    background:url('hihi.jpg') no-repeat center center fixed;
    background-size:cover;
    color:#333;
    min-height:100vh;
}
header{
    position:fixed;
    top:0;
    width:100%;
    background:rgba(195, 77, 87, 0.95);
    color:white;
    padding:10px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    z-index:10;
    box-shadow:0 2px 8px rgba(0,0,0,0.3);
}
header img{
    height:50px;
    vertical-align:middle;
    border-radius:10px;
}
header a{
    color:white;
    margin:0 10px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}
header a:hover{
    text-decoration:underline;
}
.container{
    width:95%;
    margin:120px auto 40px auto;
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
}
.comic{
    background:rgba(255,255,255,0.9);
    width:220px;
    margin:15px;
    border-radius:15px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
    text-align:center;
    padding:10px;
    transition:transform 0.3s;
}
.comic:hover{transform:translateY(-5px);}
.comic img{
    width:180px;height:220px;object-fit:cover;
    border-radius:10px;
    transition:transform 0.3s;
}
.comic img:hover{transform:scale(1.05);}
.comic h3{font-size:16px;margin:10px 0 5px 0;}
.comic p{margin:5px 0;font-weight:bold;color:#e74c3c;}
.comic input[type=number]{width:50px;padding:3px;margin-right:5px;}
button{
    background:#27ae60;color:white;
    border:none;border-radius:5px;
    padding:6px 12px;cursor:pointer;margin:2px;
}
button:hover{background:#219150;}
.search-box{
    text-align:center;margin-top:90px;
}
.search-box input[type=text]{
    padding:8px;width:250px;border-radius:5px;border:1px solid #ccc;
}
.search-box button{
    padding:8px 12px;border:none;background:#3498db;color:white;border-radius:5px;
}
.cart-summary{position:relative;display:inline-block;}
.cart-popup{
    display:none;position:absolute;right:0;top:30px;background:white;color:black;width:300px;
    padding:10px;border:1px solid #ddd;border-radius:5px;
    box-shadow:0 0 10px rgba(0,0,0,0.2);
}
.cart-summary:hover .cart-popup{display:block;}
.cart-popup table{width:100%;border-collapse:collapse;}
.cart-popup th, .cart-popup td{border:1px solid #ddd;padding:5px;text-align:center;font-size:12px;}
.cart-popup th{background:#3498db;color:white;}
.cart-popup a{
    display:block;text-align:center;margin-top:5px;background:#e74c3c;
    color:white;padding:5px 0;border-radius:5px;text-decoration:none;
}
footer{
    text-align:center;
    color:white;
    padding:10px;
    background:rgba(0,0,0,0.5);
}
</style>
</head>
<body>
<header>
<div style="display:flex;align-items:center;">
<img src="hinh-nen-gaming-2.jpg" alt="Logo"> <span style="font-size:18px;font-weight:bold;">gameming Shop</span>
</div>
<div class="cart-summary">
<?php if(isset($_SESSION["username"])): ?>
Xin chào, <?= $_SESSION["username"] ?> | <a href="logout.php">Đăng xuất</a>
<?php else: ?>
<a href="login.php">Đăng nhập</a> | <a href="register.php">Đăng ký</a>
<?php endif; ?>
&nbsp; | <a href="cart.php" style="color:white;text-decoration:none;">🛒 Giỏ hàng (<?= $total_qty ?>)</a>
<div class="cart-popup">
<?php if(!empty($_SESSION["cart"])): ?>
<table>
<tr><th>Ảnh</th><th>Tên</th><th>SL</th><th>Thành tiền</th><th>Xóa</th></tr>
<?php foreach($_SESSION["cart"] as $item): ?>
<tr>
<td><img src="<?= $item['image'] ?>" width="40"></td>
<td><?= htmlspecialchars($item["name"]) ?></td>
<td><?= $item["quantity"] ?></td>
<td><?= number_format($item["price"]*$item["quantity"]) ?>₫</td>
<td><a style="background:#e74c3c;color:white;padding:2px 5px;border-radius:3px;text-decoration:none;" href="cart.php?remove=<?= $item['id'] ?>">Xóa</a></td>
</tr>
<?php endforeach; ?>
<tr><th colspan="3">Tổng</th><th><?= number_format($total_price) ?>₫</th><th></th></tr>
</table>
<a href="cart.php">Xem giỏ hàng / Thanh toán</a>
<?php else: ?><p>Chưa có sản phẩm!</p><?php endif; ?>
</div>
</div>
</header>

<div class="search-box">
<form method="GET">
<input type="text" name="search" placeholder="Tìm truyện..." value="<?= htmlspecialchars($search) ?>">
<button type="submit">Tìm</button>
</form>
</div>

<div class="container">
<?php while($row=$result->fetch_assoc()): ?>
<div class="comic">
<img src="<?= $row['image'] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
<h3><?= htmlspecialchars($row['name']) ?></h3>
<p>Giá: <?= number_format($row['price']) ?>₫</p>
<form method="POST" action="cart_add.php">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="name" value="<?= $row['name'] ?>">
<input type="hidden" name="price" value="<?= $row['price'] ?>">
<input type="hidden" name="image" value="<?= $row['image'] ?>">
Số lượng: <input type="number" name="quantity" value="1" min="1"><br>
<button type="submit" name="buy_now">Mua ngay</button>
<button type="submit" name="add_cart">Thêm vào giỏ</button>
</form>
</div>
<?php endwhile; ?>
</div>
</body>
</html>
