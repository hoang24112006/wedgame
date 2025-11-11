<?php
session_start();
include "connect.php";


if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.php");
    exit;
}


$id=(int)$_GET["id"];
$conn->query("DELETE FROM game WHERE id=$id");
header("Location: danhsachgame.php");
exit;
?>
