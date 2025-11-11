<?php
include "connect.php";

// Tài khoản admin mới
$username = "admin";
$email = "admin@gmail.com";
$password = password_hash("123", PASSWORD_DEFAULT);
$role = "admin";

// Kiểm tra có tồn tại chưa
$check = $conn->prepare("SELECT * FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "❌ Tài khoản admin đã tồn tại.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $role);

    if ($stmt->execute()) {
        echo "✅ Đã thêm tài khoản admin thành công!<br>";
        echo "Tên đăng nhập: admin<br>Mật khẩu: 123";
    } else {
        echo "❌ Lỗi khi thêm admin: " . $stmt->error;
    }
}
?>
