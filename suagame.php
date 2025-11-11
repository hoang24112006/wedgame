<?php
session_start();
include "connect.php";


if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = (int)$_GET["id"];
} else {
    die("❌ Không có ID game hợp lệ được truyền!");
}

$res=$conn->query("SELECT * FROM game WHERE id=$id");
$comic=$res->fetch_assoc();
$message="";

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name=$_POST["name"];
    $price=(int)$_POST["price"];
    $image_path=$comic["image"];

   if(isset($_FILES["image"]) && $_FILES["image"]["size"] > 0){
    $target_dir = "../images/";
    if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
        $image_path = "images/" . $image_name;
    }
}


    $stmt=$conn->prepare("UPDATE game SET name=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("sisi",$name,$price,$image_path,$id);
    if($stmt->execute()) $message="Cập nhật thành công!";
    else $message="Lỗi cập nhật!";
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa game</title>
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
<h2>✏️ Sửa game</h2>
<form method="POST" enctype="multipart/form-data">
<input type="text" name="name" value="<?= htmlspecialchars($comic["name"]) ?>" required><br>
<input type="number" name="price" value="<?= $comic["price"] ?>" required><br>
<img src="../<?= $comic["image"] ?>" width="100"><br>
<input type="file" name="image"><br>
<button type="submit">Cập nhật</button>
</form>
<p><?= $message ?></p>
<p><a href="danhsachgame.php">⬅ Quay lại danh sách game</a></p>
</div>
</body>
</html>
