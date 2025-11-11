<?php
session_start();
include "connect.php"; 

if(!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}


$search = "";
if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
    $sql = "SELECT * FROM game WHERE ten_game LIKE ? OR anh LIKE ?";
    $stmt = $conn->prepare($sql);
    $param = "%$search%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM game");

}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title> Danh sách games</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #6dd5fa, #2980b9);
    margin: 0;
    padding: 0;
}
.container {
    width: 90%;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
h2 {
    color: #3498db;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
table, th, td {
    border: 1px solid #ccc;
}
th, td {
    padding: 10px;
    text-align: center;
}
th {
    background: #3498db;
    color: white;
}
tr:nth-child(even) {
    background: #f2f2f2;
}
img {
    width: 60px;
    border-radius: 8px;
}
a {
    text-decoration: none;
    color: #2980b9;
    font-weight: bold;
}
a:hover {
    color: #1c6ea4;
}
.btn {
    display: inline-block;
    padding: 8px 12px;
    margin: 10px 5px;
    background: #27ae60;
    color: white;
    border-radius: 5px;
    text-decoration: none;
}
.btn:hover {
    background: #219150;
}
.search-box {
    text-align: center;
    margin-bottom: 15px;
}
.search-box input[type="text"] {
    padding: 8px;
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.search-box button {
    padding: 8px 12px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.search-box button:hover {
    background: #2980b9;
}
</style>
</head>
<body>
<div class="container">
    <h2> Danh sách game</h2>

    <!-- Nút chức năng -->
    <a class="btn" href="themgame.php"> Thêm game</a>
    <a class="btn" href="admin_index.php">🏠 Quay lại Admin</a>

    <!-- Ô tìm kiếm -->
    <div class="search-box">
        <form method="get">
            <input type="text" name="search" placeholder="🔍 Tìm games theo tên hoặc giá..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Tìm</button>
            <?php if (!empty($search)): ?>
                <a href="danhsachgame.php" style="margin-left:10px;color:#e74c3c;">❌về lại </a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Bảng games -->
    <table>
        <tr>
            <th>ID</th>
            <th>Tên games</th>
            <th>Giá</th>
            <th>Hình</th>
            <th>Hành động</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= number_format($row['price']) ?>₫</td>
                <td>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="Ảnh games">
                    <?php else: ?>
                        <span>Không có hình</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="suagame.php?id=<?= $row['id'] ?>">✏️ Sửa</a> | 
                    <a href="xoagame.php?id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa games này không?')">🗑️ Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5">Không tìm thấy games nào phù hợp.</td></tr>
        <?php endif; ?>
    </table>
</div>
</body>
</html>
