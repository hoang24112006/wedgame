<?php
session_start();
if(!isset($_SESSION["cart"])) $_SESSION["cart"] = [];

$id = $_POST["id"];
$name = $_POST["name"];
$price = $_POST["price"];
$image = $_POST["image"];
$quantity = (int)$_POST["quantity"];

$found=false;
foreach($_SESSION["cart"] as &$item){
    if($item["id"]==$id){
        $item["quantity"]+=$quantity;
        $found=true;
        break;
    }
}
unset($item);

if(!$found){
    $_SESSION["cart"][]=compact('id','name','price','image','quantity');
}

if(isset($_POST["buy_now"])){
    header("Location:thanh toán.php");
}else{
    header("Location:index.php");
}
exit;
