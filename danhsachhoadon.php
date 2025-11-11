<?php
session_start();
include "connect.php";

// Kiểm tra đăng nhập
if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION["username"];
$role = $_SESSION["role"] ?? "user"; // Nếu chưa có role thì mặc định là user

// Lấy ID người dùng
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$user_id = $user["id"];

// ✅ Xử lý xóa hóa đơn (chỉ admin được phép)
if (isset($_GET["delete"]) && $role === "admin") {
    $order_id = intval($_GET["delete"]);

    $stmt_del = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt_del->bind_param("i", $order_id);
    $stmt_del->execute();

    // Quay lại trang danh sách sau khi xóa
    header("Location: danhsachhoadon.php");
    exit;
}

// ✅ Nếu là admin → xem tất cả hóa đơn
if ($role === "admin") {
    $sql = "SELECT o.id, u.username, g.name AS game_name, o.quantity, o.total_price, o.order_date
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN game g ON o.game_id = g.id
            ORDER BY o.order_date DESC";
    $stmt = $conn->prepare($sql);
}
// ✅ Nếu là user → chỉ xem hóa đơn của mình
else {
    $sql = "SELECT o.id, g.name AS game_name, o.quantity, o.total_price, o.order_date
            FROM orders o
            JOIN game g ON o.game_id = g.id
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Danh sách hóa đơn</title>
<style>
body {
    font-family: Arial;
    background: #248e85ff;
}
h2 {
    text-align: center;
    color: #2c3e50;
    margin-top: 30px;
}
table {
    border-collapse: collapse;
    width: 95%;
    margin: 30px auto;
    background: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}
th {
    background: #922323ff;
    color: white;
}
tr:nth-child(even) {
    background: #f2f2f2;
}
a {
    color: #3498db;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
.btn-back {
    display: block;
    text-align: center;
    margin: 20px auto;
    color: white;
    background: #e74c3c;
    width: 200px;
    padding: 10px;
    border-radius: 6px;
    text-decoration: none;
}
.btn-back:hover {
    background: #c0392b;
}
.btn-delete {
    background: #e74c3c;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}
.btn-delete:hover {
    background: #c0392b;
}
.btn-view {
    background: #27ae60;
    color: white;
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
}
.btn-view:hover {
    background: #219150;
}
</style>
</head>
<body>
<h2>📜 DANH SÁCH HÓA ĐƠN</h2>

<table>
<tr>
    <th>ID</th>
    <?php if ($role === "admin"): ?>
        <th>Khách hàng</th>
    <?php endif; ?>
    <th>game</th>
    <th>Số lượng</th>
    <th>Tổng tiền</th>
    <th>Ngày đặt</th>
    <th>Hành động</th>
</tr>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <?php if ($role === "admin"): ?>
                <td><?= htmlspecialchars($row['username']) ?></td>
            <?php endif; ?>
            <td><?= htmlspecialchars($row['game_name']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['total_price'], 0, ',', '.') ?> đ</td>
            <td><?= $row['order_date'] ?></td>
            <td>
                <a href="chitiethoadon.php?id=<?= $row['id'] ?>" class="btn-view">👁 Xem chi tiết</a>
                <?php if ($role === "admin"): ?>
                    <a href="?delete=<?= $row['id'] ?>" class="btn-delete"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này không?');">
                        🗑 Xóa
                    </a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="<?= $role === 'admin' ? 7 : 6 ?>">Không có hóa đơn nào.</td></tr>
<?php endif; ?>
</table>

<a href="<?= $role === 'admin' ? 'admin_index.php' : 'index.php' ?>" class="btn-back">⬅ Quay lại</a>

</body>
</html>
