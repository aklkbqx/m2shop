<?php
session_start();
require_once __DIR__ . "/../lib/util.php";

if (isset($_GET["addToCart"]) && isset($_GET["product_id"]) && isset($_GET["amount"]) && isset($_GET["size"])) {
    $product_id = $_GET["product_id"];
    $amount = $_GET["amount"];
    $size = $_GET["size"];
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : msg("กรุณาทำการเข้าสู่ระบบก่อน", 'danger', "../pages/login.php");

    $products = sql("SELECT * FROM `products` WHERE `product_id` = ?", [$product_id])->fetch(PDO::FETCH_ASSOC);
    $carts = sql("SELECT * FROM `carts` WHERE `product_id` = ? AND `size` = ? AND `user_id` = ?", [$product_id, $size, $user_id]);

    if ($carts->rowCount() > 0) {
        $cart = $carts->fetch(PDO::FETCH_ASSOC);
        $updateAmount = sql("UPDATE `carts` SET `amount` = ? WHERE `product_id` = ? AND `size` = ? AND `user_id` = ?", [
            $cart["amount"] + $amount,
            $product_id,
            $size,
            $user_id
        ]);
    } else {
        $insert = sql("INSERT INTO `carts`(`product_id`, `amount`, `size`, `user_id`) VALUES(?, ?, ?, ?)", [
            $product_id,
            $amount,
            $size,
            $user_id
        ]);
    }

    header("Location: ../pages/cart.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET["deleteItemOnCart"])) {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : msg("กรุณาทำการเข้าสู่ระบบก่อน", 'danger', "../pages/login.php");
    $carts = sql("DELETE FROM `carts` WHERE `item_id` = ? AND `user_id` = ?", [$item_id, $user_id]);
    echo json_encode(['success' => true, 'message' => 'ลบสินค้าในตะกร้าของคุณแล้ว']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET["updateAmount"])) {
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $amount = isset($_POST['amount']) ? intval($_POST['amount']) : 0;
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : null;
    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'กรุณาทำการเข้าสู่ระบบก่อน']);
        exit;
    }
    $updateAmount = sql("UPDATE `carts` SET `amount` = ? WHERE `item_id` = ? AND `user_id` = ?", [
        $amount,
        $item_id,
        $user_id
    ]);
    echo json_encode(['status' => 'success', 'message' => 'อัปเดตตะกร้าเรียบร้อย']);
}
